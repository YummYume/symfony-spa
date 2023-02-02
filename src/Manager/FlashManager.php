<?php

namespace App\Manager;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FlashManager
{
    public const FLASH_SUCCESS = 'success';
    public const FLASH_INFO = 'info';
    public const FLASH_ERROR = 'error';
    public const FLASH_WARNING = 'warning';

    public const ALLOWED_FLASH_TYPES = [
        self::FLASH_SUCCESS,
        self::FLASH_INFO,
        self::FLASH_ERROR,
        self::FLASH_WARNING,
    ];

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly RequestStack $requestStack
    ) {
    }

    public function flash(
        string $type,
        string $message,
        array $parameters = [],
        ?string $translationDomain = 'messages',
        ?string $locale = null
    ): void {
        /** @var Session */
        $session = $this->requestStack->getSession();
        $translationType = \in_array($type, self::ALLOWED_FLASH_TYPES, true) ? $type : self::FLASH_INFO;

        $session->getFlashBag()->add($translationType, $this->translator->trans($message, $parameters, $translationDomain, $locale));
    }
}
