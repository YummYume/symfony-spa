<?php

namespace App\Controller;

use App\Form\BlockType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(BlockType::class)->handleRequest($request);
        $data = null;

        if ($form->isSubmitted()) {
            $data = 'hi';
        }

        return $this->render('homepage/index.html.twig', [
            'form' => $form,
        ], new Response(status: $data ? 401 : 200));
    }
}
