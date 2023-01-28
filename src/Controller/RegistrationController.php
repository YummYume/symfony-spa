<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Manager\Email\SecurityEmailManager;
use App\Manager\FlashManager;
use App\Manager\LogManager;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\ExpiredSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

final class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly FlashManager $flashManager,
        private readonly SecurityEmailManager $securityEmailManager
    ) {
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        LogManager $logManager
    ): Response {
        $user = new User();
        $form = $this
            ->createForm(RegistrationFormType::class, $user)
            ->handleRequest($request)
        ;

        if ($form->isSubmitted()) {
            try {
                if ($form->isValid()) {
                    $entityManager->persist($user);
                    $entityManager->flush();

                    // generate a signed url and email it to the user
                    $this->securityEmailManager->sendRegisterEmailConfirmation($user);

                    $this->flashManager->flash(FlashManager::FLASH_SUCCESS, 'flash.register.email_sent');

                    return $this->redirectToRoute('app_homepage');
                }

                $this->flashManager->flash(FlashManager::FLASH_ERROR, 'flash.common.invalid_form');
            } catch (\Exception $e) {
                $logManager->logException($e);

                $this->flashManager->flash(FlashManager::FLASH_ERROR, 'flash.common.something_went_wrong');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email', methods: ['GET'])]
    public function verifyUserEmail(
        Request $request,
        UserRepository $userRepository,
        EmailVerifier $emailVerifier
    ): RedirectResponse {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        if ($user->isVerified()) {
            return $this->redirectToRoute('security_login');
        }

        try {
            $emailVerifier->handleEmailConfirmation($request, $user);
        } catch (ExpiredSignatureException $exception) {
            $this->securityEmailManager->sendRegisterEmailConfirmation($user);

            $this->flashManager->flash(FlashManager::FLASH_ERROR, 'flash.register.link_expired');

            return $this->redirectToRoute('app_register');
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->flashManager->flash(FlashManager::FLASH_ERROR, $exception->getReason(), translationDomain: 'VerifyEmailBundle');

            return $this->redirectToRoute('app_register');
        }

        $this->flashManager->flash(FlashManager::FLASH_SUCCESS, 'flash.register.email_verified');

        return $this->redirectToRoute('security_login');
    }
}
