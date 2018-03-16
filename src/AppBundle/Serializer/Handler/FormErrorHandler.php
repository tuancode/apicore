<?php

namespace AppBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Re-define FOSRest Form error handler to make it clear and simple.
 */
class FormErrorHandler extends \FOS\RestBundle\Serializer\Normalizer\FormErrorHandler
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $translationDomain;

    /**
     * @param TranslatorInterface $translator
     * @param string              $translationDomain
     */
    public function __construct(TranslatorInterface $translator, string $translationDomain = 'validators')
    {
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;

        parent::__construct($translator);
    }

    /**
     * Serialize Symfony form error to json format.
     *
     * @param JsonSerializationVisitor $visitor
     * @param Form                     $form
     * @param array                    $type
     * @param Context                  $context
     *
     * @return array|FormInterface
     */
    public function serializeFormToJson(
        JsonSerializationVisitor $visitor,
        Form $form,
        array $type,
        Context $context = null
    ) {
        $isRoot = null === $visitor->getRoot();
        $result = $this->convertFormToArray($form, $context);

        if ($isRoot) {
            $visitor->setRoot($result);
        }

        return $result;
    }

    /**
     * Restructure form array.
     *
     * @param FormInterface $form
     * @param Context       $context
     *
     * @return array|FormInterface
     */
    private function convertFormToArray(FormInterface $form, Context $context)
    {
        $statusCode = $this->getStatusCode($context);
        if (null !== $statusCode) {
            return [
                'code' => $statusCode,
                'message' => 'Validation Failed',
                'errors' => $this->getErrorsFromForm($form),
            ];
        }

        return $form;
    }

    /**
     * Rebuild form error children.
     *
     * For example,
     *
     * ```php
     * [
     *     'email'    => [
     *         'message' => 'This value should not be blank.',
     *     ],
     *     'password' => [
     *         'message' => 'This value should not be blank.',
     *     ]
     * ]
     * ```
     *
     * @param FormInterface $form
     *
     * @return array
     */
    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $this->getErrorMessage($error);
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    /**
     * Parse and translate error message.
     *
     * @param FormError $error
     *
     * @return string
     */
    private function getErrorMessage(FormError $error)
    {
        if (null === $this->translator) {
            return $error->getMessage();
        }

        if (null !== $error->getMessagePluralization()) {
            return $this->translator->transChoice(
                $error->getMessageTemplate(),
                $error->getMessagePluralization(),
                $error->getMessageParameters(),
                $this->translationDomain
            );
        }

        return $this->translator->trans(
            $error->getMessageTemplate(),
            $error->getMessageParameters(),
            $this->translationDomain
        );
    }

    /**
     * Extract context status code.
     *
     * @param Context|null $context
     *
     * @return mixed
     */
    private function getStatusCode(Context $context = null)
    {
        if (null === $context) {
            return null;
        }

        $statusCode = $context->attributes->get('status_code');
        if ($statusCode->isDefined()) {
            return $statusCode->get();
        }

        return null;
    }
}
