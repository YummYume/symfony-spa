<?php

namespace App\Controller;

use App\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
final class ProfileController extends AbstractController
{
    #[Route('/{slug}', name: 'app_profile_show', methods: ['GET'])]
    public function showProfile(Profile $profile): Response
    {
        if (!$profile->getUser()->isVerified()) {
            throw $this->createNotFoundException('This profile cannot be accessed.');
        }

        return $this->render('profile/show.html.twig', [
            'profile' => $profile,
        ]);
    }
}
