<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * UserType.
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'documentation' => [
                        'type' => 'string',
                        'description' => 'Email',
                    ],
                ]
            )
            ->add(
                'password',
                TextType::class,
                [
                    'property_path' => 'plainPassword',
                    'documentation' => [
                        'type' => 'string',
                        'description' => 'Password',
                    ],
                ]
            )
            ->add(
                'status',
                ChoiceType::class,
                [
                    'choices' => User::STATUS,
                    'documentation' => [
                        'type' => 'string',
                        'description' => 'Status',
                    ],
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'documentation' => [
                        'type' => 'string',
                        'description' => 'Phone number',
                    ],
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }
}
