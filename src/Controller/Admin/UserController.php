<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    #[Route('/users', name: 'admin_user', methods: ['GET'])]
    public function index(UserRepository $UserRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $UserRepository->createQueryBuilder('u'),
            $request->query->getInt('page', 1),
            5
        );

        $config = [
            'cols' => [
                'id' => [
                    'type' => 'text',
                    'label' => 'table.user.id',
                    'queryKey' => 'u.id',
                    'extras' => [
                        // custom value
                    ],
                ],
                'email' => [
                    'type' => 'text',
                    'label' => 'table.user.email',
                    'queryKey' => 'u.email',
                    'extras' => [
                        // custom value
                    ],
                ],
                'verified' => [
                    'type' => 'switch',
                    'label' => 'table.user.verified',
                    'queryKey' => 'u.verified',
                    'extras' => [
                        'route' => 'admin_user_status',
                    ],
                ],
                'actions' => [
                    'info' => [
                        'route' => 'app_homepage',
                        'icon' => 'eye',
                    ],
                    'accent' => [
                        'route' => 'app_homepage',
                        'icon' => 'pencil',
                    ],
                    'error' => [
                        'route' => 'app_homepage',
                        'icon' => 'trash',
                        'validation' => [
                            'message' => 'table.user.delete.message',
                        ],
                    ],
                ],
            ],
            'pagination' => $pagination,
        ];

        return $this->render('admin/user/index.html.twig', ['config' => $config]);
    }

    #[Route('/users/check/{id}', name: 'admin_user_status', methods: ['GET'])]
    public function changeStatus(User $user, UserRepository $UserRepository): JsonResponse
    {
        $user->setIsVerified(!$user->isVerified());
        $UserRepository->save($user, true);

        return $this->json(['response' => 'value change']);
    }
}
