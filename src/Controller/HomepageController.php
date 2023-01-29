<?php

namespace App\Controller;

use App\Manager\FlashManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig');
    }

    #[Route('/test', name: 'app_flash-test', methods: ['GET'])]
    public function flashTest(FlashManager $flashManager): RedirectResponse
    {
        $flashManager->flash(FlashManager::FLASH_SUCCESS, 'Test success', ['type' => FlashManager::FLASH_SUCCESS]);
        $flashManager->flash(FlashManager::FLASH_ERROR, 'Test error');

        return $this->redirectToRoute('app_homepage');
    }
}
