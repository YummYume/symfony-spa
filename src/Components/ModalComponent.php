<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('modal')]
final class ModalComponent
{
    public string $modalId;

    public string $title;

    public string $content;

    public string $openButtonClass = '';

    public string $openButtonText = '';

    public string $modalClass = '';

    public string $closeButtonClass = '';

    public string $closeButtonText = '';
}
