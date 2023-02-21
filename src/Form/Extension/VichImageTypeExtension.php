<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validation;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class VichImageTypeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'image_class' => '',
            'image_imagine_filter' => null,
            'image_alt' => 'common.upload.default_alt',
            'image_width' => 200,
            'image_height' => 200,
        ]);

        $resolver
            ->setAllowedTypes('image_class', 'string')
            ->setAllowedTypes('image_imagine_filter', ['string', 'null'])
            ->setAllowedTypes('image_alt', 'string')
            ->setAllowedTypes('image_width', 'int')
            ->setAllowedTypes('image_height', 'int')
        ;

        $resolver
            ->setAllowedValues('image_width', Validation::createIsValidCallable(
                new Type('int'),
                new Positive()
            ))
            ->setAllowedValues('image_height', Validation::createIsValidCallable(
                new Type('int'),
                new Positive()
            ))
        ;

        $resolver
            ->setInfo('image_class', 'Classes to add to the image')
            ->setInfo('image_imagine_filter', 'Imagine filter to apply to the image')
            ->setInfo('image_alt', 'Alt attribute for the image')
            ->setInfo('image_width', 'Width of the image. Must be a positive integer')
            ->setInfo('image_height', 'Height of the image. Must be a positive integer')
        ;

        // Unset imagine_pattern, use our own instead
        // Return type should be :null but this is only allowed in PHP 8.2 and cs-fixer is still using 8.1
        $resolver->setNormalizer('imagine_pattern', static fn (): ?string => null);
        $resolver->setDeprecated(
            'imagine_pattern',
            'VichImageType',
            '2.0.1',
            'The option "imagine_pattern" is not used. Use "image_imagine_filter" instead.'
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['image_class'] = $options['image_class'];
        $view->vars['image_imagine_filter'] = $options['image_imagine_filter'];
        $view->vars['image_alt'] = $options['image_alt'];
        $view->vars['image_width'] = $options['image_width'];
        $view->vars['image_height'] = $options['image_height'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attr = [
            'placeholder' => 'common.upload.click_or_drop',
            ...$options['attr'],
        ];

        $builder->add('file', DropzoneType::class, [
            'required' => $options['required'],
            'label' => $options['label'],
            'attr' => $attr,
            'translation_domain' => $options['translation_domain'],
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [VichImageType::class];
    }
}
