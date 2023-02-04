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
                'required' => false,
                'allow_delete' => true,
                'imagine_pattern' => 'avatar',
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
