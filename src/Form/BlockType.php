<?php

namespace App\Form;

use App\Form\Type\EditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('editor', EditorType::class, [
                'mapped' => false,
                'data' => json_decode('{"time":1680125078509,"blocks":[{"id":"4O4EkNTkqy","type":"paragraph","data":{"text":"Hello world!"},"tunes":{"textVariant":""}}],"version":"2.26.5"}', true),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
