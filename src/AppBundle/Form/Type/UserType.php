<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use AppBundle\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * UserType.
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('status', ChoiceType::class, ['choices' => User::STATUS])
            ->add('phone', TextType::class)
            ->add('enabled', ChoiceType::class, ['choices' => [User::DISABLED, User::ENABLED]]);
    }
}
