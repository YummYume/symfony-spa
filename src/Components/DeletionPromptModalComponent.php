<?php

namespace App\Components;

use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('deletion_prompt_modal')]
final class DeletionPromptModalComponent
{
    use DefaultActionTrait;

    public string $id = 'deletion-modal';

    public string $title = 'Delete Modal';

    public string $content = '';

    public ?string $warningMessage = null;

    public string $closeLabel = 'Close';

    public string $deleteLabel = 'Delete';

    public ?string $prompt = null;

    public string $action = '';

    public ?string $token = null;
}
