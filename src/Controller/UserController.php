<?php

namespace App\Controller;

use App\Form\UserType;
use App\Manager\FlashManager;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    public function __construct(private readonly FlashManager $flashManager)
    {
    }

    #[Route('/me', name: 'app_user_profile', methods: ['GET', 'POST'])]
    public function editProfile(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $userRepository->save($user, true);

                $this->flashManager->flash(FlashManager::FLASH_SUCCESS, 'flash.user.profile_updated');

                return $this->redirectToRoute('app_user_profile');
            }

            $this->flashManager->flash(FlashManager::FLASH_ERROR, 'flash.common.invalid_form');
        }

        return $this->render('user/edit_profile.html.twig', [
            'form' => $form,
        ]);
    }
}
