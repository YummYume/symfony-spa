<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\ColorTypeEnum;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Manager\Email\SecurityEmailManager;
use App\Manager\FlashManager;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private readonly ResetPasswordHelperInterface $resetPasswordHelper,
        private readonly FlashManager $flashManager
    ) {
    }

    /**
     * Display & process form to request a password reset.
     */
    #[Route('', name: 'app_forgot_password_request')]
    public function request(
        Request $request,
        SecurityEmailManager $securityEmailManager,
        UserRepository $userRepository
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        $form = $this->createForm(ResetPasswordRequestFormType::class)->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $userRepository->findOneBy([
                    'email' => $form->get('email')->getData(),
                ]);

                // Do not reveal whether a user account was found or not.
                if (!$user) {
                    return $this->redirectToRoute('app_check_email');
                }

                try {
                    $resetToken = $this->resetPasswordHelper->generateResetToken($user);
                } catch (ResetPasswordExceptionInterface $e) {
                    return $this->redirectToRoute('app_check_email');
                }

                $securityEmailManager->sendPasswordResetEmail($user, $resetToken);

                // Store the token object in session for retrieval in check-email route.
                $this->setTokenObjectInSession($resetToken);

                return $this->redirectToRoute('app_check_email');
            }

            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_form');
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form,
        ]);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     */
    #[Route('/check-email', name: 'app_check_email')]
    public function checkEmail(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        // Generate a fake token if the user does not exist or someone hit this page directly.
        // This prevents exposing whether or not a user was found with the given email address or not
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     */
    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(
        Request $request,
        TranslatorInterface $translator,
        UserRepository $userRepository,
        string $token = null
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();

        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            /** @var User */
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->flashManager->flash(ColorTypeEnum::Error->value, sprintf(
                '%s - %s',
                $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, domain: 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), domain: 'ResetPasswordBundle')
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class)->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // A password reset token should be used only once, remove it.
                $this->resetPasswordHelper->removeResetRequest($token);

                $user->setPlainPassword($form->get('plainPassword')->getData());
                $userRepository->save($user, true);

                // The session is cleaned up after the password has been changed.
                $this->cleanSessionAfterReset();

                $this->flashManager->flash(ColorTypeEnum::Success->value, 'flash.reset_password.updated');

                return $this->redirectToRoute('security_login');
            }

            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_form');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
