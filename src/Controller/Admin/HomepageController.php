<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'admin_homepage', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/homepage/index.html.twig');
    }
}
