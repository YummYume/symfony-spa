<?php

namespace App\Form\Admin;

use App\Entity\User;
use App\Enum\UserRoleEnum;
use App\Form\ProfileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'user.email',
                'required' => true,
            ])
            ->add('verified', CheckboxType::class, [
                'label' => 'user.verified',
                'required' => false,
            ])
            ->add('profile', ProfileType::class, [
                'label' => false,
            ])
        ;

        if ($options['can_edit_roles']) {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'user.roles',
                'choices' => UserRoleEnum::toArray(false, [UserRoleEnum::AllowedToSwitch]),
                'choice_label' => static fn (string $role): string => sprintf('user.role.%s', strtolower($role)),
                'translation_domain' => 'tables',
                'multiple' => true,
                'autocomplete' => true,
                'required' => true,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'can_edit_roles' => false,
        ]);
    }
}
