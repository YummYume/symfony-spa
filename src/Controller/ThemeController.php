<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class ThemeController extends AbstractController
{
    #[Route('/theme/change', name: 'app_change_theme')]
    public function changeTheme(Request $request): RedirectResponse
    {
        $referer = $request->headers->get('referer', $this->generateUrl('app_homepage'));
        $response = new RedirectResponse($referer);
        $mode = $request->cookies->get('theme_mode', 'light');
        $cookie = Cookie::create('theme_mode')
            ->withValue('light' === $mode ? 'dark' : 'light')
            ->withSecure(true)
            ->withHttpOnly(false)
        ;
        $response->headers->setCookie($cookie);

        return $response;
    }
}
