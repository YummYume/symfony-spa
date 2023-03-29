<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsTwigComponent('button')]
final class ButtonComponent
{
    public const LEFT = 'left';
    public const RIGHT = 'right';

    public const TARGET_SELF = '_self';
    public const TARGET_BLANK = '_blank';

    public ?string $id = null;

    public bool $centered = true;

    public bool $submit = false;

    public ?string $href = null;

    public ?string $form = null;

    public bool $disabled = false;

    public ?string $target = null;

    public bool $externalLink = false;

    public string $class = '';

    public string $content = '';

    public string $ariaLabel = '';

    public ?string $icon = null;

    public string $iconPosition = self::RIGHT;

    public array $additionalProps = [];

    #[ExposeInTemplate]
    private bool $button = true;

    #[PostMount]
    public function postMount(): void
    {
        if ($this->href) {
            $this->button = false;
        }

        $this->class = sprintf(
            'inline-block gap-2 %s %s %s',
            $this->centered ? 'mx-auto flex' : '',
            $this->class
        );
    }

    public function isButton(): bool
    {
        return $this->button;
    }
}
