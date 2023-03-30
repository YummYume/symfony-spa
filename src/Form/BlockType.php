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
                'disabled' => false,
                'data' => json_decode('{"time":1680182022973,"blocks":[{"id":"4O4EkNTkqy","type":"paragraph","data":{"text":"Hello world!"},"tunes":{"textVariant":""}},{"id":"O61k29N1is","type":"image","data":{"url":"https://www.caroom.fr/guide/wp-content/uploads/2022/02/quel-tesla-model-x-choisir.jpg","caption":"Sa mère la Tesla de fou","withBorder":false,"withBackground":false,"stretched":false}},{"id":"027WxeVpsU","type":"quote","data":{"text":"Salut à tous","caption":"Comment ça va","alignment":"left"}}],"version":"2.26.5"}', true),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
