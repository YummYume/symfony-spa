<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
final class UserController extends AbstractController
{
    #[Route('/users', name: 'admin_user', methods: ['GET'])]
    public function index(UserRepository $userRepo, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $userRepo->createQueryBuilder('u'),
            $request->query->getInt('page', 1),
            5
        );

        $config = [
            "cols" => [
                "id" => [
                    "type" => "text",
                    "label" => "id",
                    "queryKey" => "u.id",
                    "extras" => [
                        // custom value
                    ],
                ],
                "email" => [
                    "type" => "text",
                    "label" => "e-mail",
                    "queryKey" => "u.email",
                    "extras" => [
                        // custom value
                    ],
                ],
                "actions" => [
                    "success" => [
                        "route" => "app_homepage",
                        "icon" => "eye",
                    ],
                    "accent" => [
                        "route" => "app_homepage",
                        "icon" => "pencil",
                    ],
                    "error" => [
                        "route" => "app_homepage",
                        "icon" => "trash",
                    ]
                ],
            ],
            "pagination" => $pagination,
        ];
        
        return $this->render('admin/user/index.html.twig', ["config" => $config]);
    }
}
