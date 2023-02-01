<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/legal')]
final class LegalController extends AbstractController
{
    #[Route('/cookie_policy', name: 'app_cookie_policy', methods: ['GET'])]
    public function cookiePolicy(): Response
    {
        return $this->render('legal/cookie_policy.html.twig');
    }

    #[Route('/privacy_policy', name: 'app_privacy_policy', methods: ['GET'])]
    public function privacyPolicy(): Response
    {
        return $this->render('legal/privacy_policy.html.twig');
    }

    #[Route('/terms_of_use', name: 'app_terms_of_use', methods: ['GET'])]
    public function termsOfUse(): Response
    {
        return $this->render('legal/terms_of_use.html.twig');
    }
}
