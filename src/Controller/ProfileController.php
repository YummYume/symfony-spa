<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Manager\FlashManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
final class ProfileController extends AbstractController
{
    public function __construct(
        private readonly FlashManager $flashManager,
    ) {
    }

    #[Route('/{slug}', name: 'app_profile_show', methods: ['GET'])]
    public function showProfile(Profile $profile): Response
    {
        return $this->render('profile/show.html.twig', [
            'profile' => $profile,
        ]);
    }
}
