<?php

namespace App\Form;

use App\Entity\ProfilePicture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class ProfilePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', VichImageType::class, [
                'label' => 'profile_picture.file',
                'help' => 'profile_picture.file.help',
                'required' => false,
                'allow_delete' => true,
                'image_alt' => 'profile_picture.current_profile_picture',
                'image_imagine_filter' => 'avatar',
                'image_class' => 'mask mask-squircle',
                'attr' => [
                    'accept' => 'image/*',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProfilePicture::class,
        ]);
    }
}
