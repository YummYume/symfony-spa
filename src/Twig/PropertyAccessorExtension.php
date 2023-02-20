<?php

namespace App\Twig;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class PropertyAccessorExtension extends AbstractExtension
{
    public function __construct(private readonly PropertyAccessorInterface $propertyAccessor)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_property', [$this, 'getProperty']),
        ];
    }

    public function getProperty(object|array $entity, string|PropertyPathInterface $property): mixed
    {
        return $this->propertyAccessor->getValue($entity, $property);
    }
}
