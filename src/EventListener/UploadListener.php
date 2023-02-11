<?php

namespace App\EventListener;

use App\Entity\ImageUploadInterface;
use Liip\ImagineBundle\Message\WarmupCache;
use Symfony\Component\Messenger\MessageBusInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

final class UploadListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly UploaderHelper $uploadHelper
    ) {
    }

    public function onVichUploaderPostUpload(Event $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof ImageUploadInterface) {
            return;
        }

        $this->messageBus->dispatch(new WarmupCache($this->uploadHelper->asset($object, 'file'), force: true));
    }
}
