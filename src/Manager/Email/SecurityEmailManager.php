<?php

namespace App\Manager\Email;

use App\Entity\User;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;

final class SecurityEmailManager
{
    public function __construct(
        private readonly EmailVerifier $emailVerifier,
        private readonly MailerInterface $mailer,
        private readonly TranslatorInterface $translator,
        private readonly RequestStack $requestStack
    ) {
    }

    public function sendRegisterEmailConfirmation(User $user): void
    {
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->subject($this->translator->trans('registration_confirmation.subject', domain: 'email'))
            ->htmlTemplate('email/registration/registration_confirmation_email.html.twig')
            ->context([
                'locale' => $this->requestStack->getCurrentRequest()->getLocale(),
                'username' => $user->getProfile()->getUsername(),
            ])
        ;

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $email);
    }

    public function sendPasswordResetEmail(User $user, ResetPasswordToken $resetToken): void
    {
        $email = (new TemplatedEmail())
            ->to($user->getEmail())
            ->subject($this->translator->trans('reset_password.subject', domain: 'email'))
            ->htmlTemplate('email/reset_password/reset_password_email.html.twig')
            ->context([
                'locale' => $this->requestStack->getCurrentRequest()->getLocale(),
                'username' => $user->getProfile()->getUsername(),
                'resetToken' => $resetToken,
            ])
        ;

        $this->mailer->send($email);
    }

    public function sendAccountDeletionConfirmationEmail(string $email, string $username): void
    {
        $email = (new TemplatedEmail())
            ->to($email)
            ->subject($this->translator->trans('account_deletion.subject', domain: 'email'))
            ->htmlTemplate('email/account/account_deletion_email.html.twig')
            ->context([
                'locale' => $this->requestStack->getCurrentRequest()->getLocale(),
                'username' => $username,
            ])
        ;

        $this->mailer->send($email);
    }
}
