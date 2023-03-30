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

    public string $modalClass = 'fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full';

    public string $closeButtonClass = '';

    public string $closeButtonText = '';
}
