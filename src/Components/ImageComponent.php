<?php

namespace App\Components;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('image')]
final class ImageComponent
{
    public ?string $src = null;

    public string $alt;

    public string $class = '';

    public ?string $filter = null;

    public ?int $width = null;

    public ?int $height = null;

    public array $dimensionSrcset = [];

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(['src', 'alt']);

        $resolver->setDefined([
            'src',
            'alt',
            'class',
            'filter',
            'width',
            'height',
            'dimensionSrcset',
        ]);

        $resolver
            ->setInfo('src', 'The path to the image. Required.')
            ->setInfo('alt', 'A text which describes the image. Required.')
            ->setInfo('class', 'Classes to add to the image. Optional.')
            ->setInfo('filter', 'The imagine filter to use for the image. Optional.')
            ->setInfo('width', 'The width of the image. Must be above 0. Required.')
            ->setInfo('height', 'The height of the image. Must be above 0. Required.')
            ->setInfo('dimensionSrcset', 'An array with srcset sizes to apply to the img tag to provide. Optional.')
        ;

        $resolver
            ->setAllowedValues('width', static fn (?int $width): bool => $width > 0)
            ->setAllowedValues('height', static fn (?int $height): bool => $height > 0)
        ;

        $resolver
            ->setAllowedTypes('src', ['string', 'null'])
            ->setAllowedTypes('alt', 'string')
            ->setAllowedTypes('class', 'string')
            ->setAllowedTypes('filter', ['string', 'null'])
            ->setAllowedTypes('width', 'int')
            ->setAllowedTypes('height', 'int')
            ->setAllowedTypes('dimensionSrcset', 'array')
        ;

        return $resolver->resolve($data);
    }
}
