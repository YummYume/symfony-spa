<?php

namespace App\Components;

use App\Enum\ColorTypeEnum;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent('button')]
final class ButtonComponent
{
    public const LEFT = 'left';
    public const RIGHT = 'right';

    public ?string $id = null;

    public bool $centered = true;

    public bool $circle = false;

    public bool $ghost = false;

    public bool $square = false;

    public bool $submit = false;

    public ?string $href = null;

    public ?string $form = null;

    public bool $disabled = false;

    public string $class = '';

    public string $content = '';

    public string $ariaLabel = '';

    public ?string $icon = null;

    public string $iconPosition = self::RIGHT;

    public string $variant = ColorTypeEnum::Primary->value;

    #[ExposeInTemplate]
    private bool $button = true;

    #[PostMount]
    public function postMount(): void
    {
        if ($this->href) {
            $this->button = false;
        }

        $this->class = sprintf(
            'btn btn-%s gap-2 %s %s %s %s %s',
            $this->variant,
            $this->circle ? 'btn-circle' : '',
            $this->ghost ? 'btn-ghost' : '',
            $this->centered ? 'mx-auto flex' : '',
            $this->square ? 'btn-square' : '',
            $this->class
        );
    }

    public function isButton(): bool
    {
        return $this->button;
    }
}
