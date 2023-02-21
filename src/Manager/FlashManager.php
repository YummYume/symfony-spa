<?php

namespace App\Manager;

use App\Enum\ColorTypeEnum;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FlashManager
{
    public const ALLOWED_FLASH_TYPES = [
        ColorTypeEnum::Success->value,
        ColorTypeEnum::Info->value,
        ColorTypeEnum::Error->value,
        ColorTypeEnum::Warning->value,
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
        $translationType = \in_array($type, self::ALLOWED_FLASH_TYPES, true) ? $type : ColorTypeEnum::Info->value;

        $session->getFlashBag()->add($translationType, $this->translator->trans($message, $parameters, $translationDomain, $locale));
    }
}
