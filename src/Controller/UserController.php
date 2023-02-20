<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\ColorTypeEnum;
use App\Enum\UserRoleEnum;
use App\Form\ProfileType;
use App\Form\UserType;
use App\Manager\Email\SecurityEmailManager;
use App\Manager\FlashManager;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Turbo\TurboBundle;

#[Route('/me')]
final class UserController extends AbstractController
{
    public function __construct(private readonly FlashManager $flashManager, private readonly UserRepository $userRepository)
    {
    }

    #[Route('', name: 'app_edit_profile', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, ProfileRepository $profileRepository): Response
    {
        /** @var User */
        $user = $this->getUser();
        $profile = $user->getProfile();
        $userForm = $this->createForm(UserType::class, $user)->handleRequest($request);
        $profileForm = $this->createForm(ProfileType::class, $profile)->handleRequest($request);

        if ($userForm->isSubmitted()) {
            if ($userForm->isValid()) {
                $this->userRepository->save($user, true);

                $this->flashManager->flash(ColorTypeEnum::Success->value, 'flash.update_profile.account_updated');
            } else {
                $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_form');
            }

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->render('user/stream/edit_account.stream.html.twig', [
                    'userForm' => $userForm,
                ]);
            } elseif ($userForm->isValid()) {
                return $this->redirectToRoute('app_edit_profile', status: Response::HTTP_SEE_OTHER);
            }
        } elseif ($profileForm->isSubmitted()) {
            if ($profileForm->isValid()) {
                $profileRepository->save($profile, true);

                $this->flashManager->flash(ColorTypeEnum::Success->value, 'flash.update_profile.profile_updated');
            } else {
                $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_form');
            }

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->render('user/stream/edit_profile.stream.html.twig', [
                    // This is necessary to avoid a problem with VichUploader listeners
                    'profileForm' => $profileForm->isValid() ? $this->createForm(ProfileType::class, $profile) : $profileForm,
                ]);
            } elseif ($profileForm->isValid()) {
                return $this->redirectToRoute('app_edit_profile', status: Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('user/edit_profile.html.twig', [
            'userForm' => $userForm,
            'profileForm' => $profileForm,
        ]);
    }

    #[Route('/delete', name: 'app_delete_profile', methods: ['POST'])]
    public function deleteProfile(
        Request $request,
        TranslatorInterface $translator,
        TokenStorageInterface $tokenStorage,
        SecurityEmailManager $securityEmailManager
    ): RedirectResponse {
        if ($this->isGranted(UserRoleEnum::Admin->value)) {
            return $this->redirectToRoute('app_edit_profile');
        }

        /** @var User */
        $user = $this->getUser();

        if ($translator->trans('page.edit_profile.deletion_prompt') !== $request->get('input')) {
            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.delete_profile.invalid_prompt');

            return $this->redirectToRoute('app_edit_profile');
        }

        if (!$this->isCsrfTokenValid(sprintf('delete-%s-user', $user->getProfile()?->getSlug()), $request->get('_token'))) {
            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_csrf');

            return $this->redirectToRoute('app_edit_profile');
        }

        $email = $user->getEmail();
        $username = $user->getProfile()->getUsername();

        $tokenStorage->setToken(null);
        $this->userRepository->remove($user, true);

        $securityEmailManager->sendAccountDeletionConfirmationEmail($email, $username);

        $this->flashManager->flash(ColorTypeEnum::Info->value, 'flash.delete_profile.success');

        return $this->redirectToRoute('app_homepage', status: Response::HTTP_SEE_OTHER);
    }
}
