<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EditorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'autofocus' => false,
            'tools' => [],
            'inline_toolbar' => [],
        ]);

        $resolver
            ->setAllowedTypes('autofocus', 'boolean')
            ->setAllowedTypes('tools', 'array')
            ->setAllowedTypes('inline_toolbar', 'string[]')
        ;

        $resolver
            ->setInfo('autofocus', 'Wether to autofocus the editor on load.')
            ->setInfo('tools', 'An array of tools you want to use.')
            ->setInfo('inline_toolbar', 'The toolbar for the editor.')
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['options'] = [
            'autofocus' => $options['autofocus'],
            'inlineToolbar' => $options['inline_toolbar'],
            'readonly' => $options['required'],
            'placeholder' => $options['attr']['placeholder'] ?? null,
        ];
        $view->vars['tools'] = $options['tools'];
    }

    public function getParent(): string
    {
        return TextareaType::class;
    }
}
