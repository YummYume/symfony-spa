<?php

namespace App\Manager\Email;

use App\Entity\User;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;

final class SecurityEmailManager
{
    public function __construct(
        private readonly EmailVerifier $emailVerifier,
        private readonly MailerInterface $mailer,
        private readonly TranslatorInterface $translator,
        private readonly string $noreplyEmail,
        private readonly string $noreplyName
    ) {
    }

    public function sendRegisterEmailConfirmation(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from($this->getDefaultNoreplyAddress())
            ->to($user->getEmail())
            ->subject($this->translator->trans('registration.subject', ['username' => 'test'], 'email'))
            ->htmlTemplate('email/registration/confirmation_email.html.twig')
        ;

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $email);
    }

    public function sendPasswordResetEmail(User $user, ResetPasswordToken $resetToken): void
    {
        $email = (new TemplatedEmail())
            ->from($this->getDefaultNoreplyAddress())
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('email/reset_password/reset_password_email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;

        $this->mailer->send($email);
    }

    private function getDefaultNoreplyAddress(): Address
    {
        return new Address($this->noreplyEmail, $this->noreplyName);
    }
}
