<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\ColorTypeEnum;
use App\Form\UserType;
use App\Manager\FlashManager;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserController extends AbstractController
{
    public function __construct(private readonly FlashManager $flashManager, private readonly UserRepository $userRepository)
    {
    }

    #[Route('/me', name: 'app_edit_profile', methods: ['GET', 'POST'])]
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->userRepository->save($user, true);

                $this->flashManager->flash(ColorTypeEnum::Success->value, 'flash.user.profile_updated');

                return $this->redirectToRoute('app_edit_profile');
            }

            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_form');
        }

        return $this->render('user/edit_profile.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/me/delete', name: 'app_delete_profile', methods: ['POST'])]
    public function deleteProfile(Request $request, TranslatorInterface $translator, TokenStorageInterface $tokenStorage): RedirectResponse
    {
        /** @var User */
        $user = $this->getUser();

        if ($user->isAdmin()) {
            return $this->redirectToRoute('app_edit_profile');
        }

        if ($translator->trans('page.edit_profile.deletion_prompt') !== $request->get('input')) {
            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.delete_profile.invalid_prompt');

            return $this->redirectToRoute('app_edit_profile');
        }

        if (!$this->isCsrfTokenValid(sprintf('delete-%s-user', $user->getProfile()?->getSlug()), $request->get('_token'))) {
            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_csrf');

            return $this->redirectToRoute('app_edit_profile');
        }

        $tokenStorage->setToken(null);
        $this->userRepository->remove($user, true);

        $this->flashManager->flash(ColorTypeEnum::Info->value, 'flash.delete_profile.success');

        return $this->redirectToRoute('app_homepage');
    }
}
