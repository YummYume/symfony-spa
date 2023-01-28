<?php

namespace App\Manager\Email;

use App\Entity\User;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

final class SecurityEmailManager
{
    public function __construct(
        private readonly EmailVerifier $emailVerifier,
        private readonly string $noreplyEmail,
        private readonly string $noreplyName
    ) {
    }

    public function sendRegisterEmailConfirmation(User $user): void
    {
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address($this->noreplyEmail, $this->noreplyName))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('email/registration/confirmation_email.html.twig')
        );
    }
}
