<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/info')]
final class InfoController extends AbstractController
{
    #[Route('/about_us', name: 'app_about_us', methods: ['GET'])]
    public function aboutUs(): Response
    {
        return $this->render('info/about_us.html.twig');
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('info/contact.html.twig');
    }
}
