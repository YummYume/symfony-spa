<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\ColorTypeEnum;
use App\Form\RegistrationFormType;
use App\Manager\Email\SecurityEmailManager;
use App\Manager\FlashManager;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Meilisearch\Bundle\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
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
    public function register(Request $request, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user)->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $userRepository->save($user, true);

                // generate a signed url and email it to the user
                $this->securityEmailManager->sendRegisterEmailConfirmation($user);

                $this->flashManager->flash(ColorTypeEnum::Success->value, 'flash.register.email_sent');

                return $this->redirectToRoute('app_homepage');
            }

            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_form');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email', methods: ['GET'])]
    public function verifyUserEmail(
        Request $request,
        UserRepository $userRepository,
        EmailVerifier $emailVerifier,
        SearchService $searchService,
        EntityManagerInterface $entityManager
    ): RedirectResponse {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find(Uuid::fromBase58($id));

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        if ($user->isVerified()) {
            return $this->redirectToRoute('security_login');
        }

        try {
            $emailVerifier->handleEmailConfirmation($request, $user);

            $searchService->index($entityManager, $user->getProfile());
        } catch (ExpiredSignatureException $exception) {
            $this->securityEmailManager->sendRegisterEmailConfirmation($user);

            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.register.link_expired');

            return $this->redirectToRoute('app_register');
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->flashManager->flash(ColorTypeEnum::Error->value, $exception->getReason(), translationDomain: 'VerifyEmailBundle');

            return $this->redirectToRoute('app_register');
        }

        $this->flashManager->flash(ColorTypeEnum::Success->value, 'flash.register.email_verified');

        return $this->redirectToRoute('security_login');
    }
}
