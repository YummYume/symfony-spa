<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ThemeController extends AbstractController
{
    #[Route('/theme/{mode<light|dark>}', name: 'app_change_theme')]
    public function changeTheme(string $mode, Request $request): RedirectResponse
    {
        $referer = $request->headers->get('referer');

        $response = new RedirectResponse($referer);

        $cookie = Cookie::create('theme_mode')
            ->withValue($mode === 'light' ? 'dark' : 'light')
            ->withSecure(true)
            ->withHttpOnly(false)
        ;

        $response->headers->setCookie($cookie);

        if (!$referer) {
            return $this->redirectToRoute('app_homepage')->headers->setCookie($cookie);
        }

        return $response;
    }
}
