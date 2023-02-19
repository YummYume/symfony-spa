<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enum\ColorTypeEnum;
use App\Manager\FlashManager;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Turbo\TurboBundle;

final class UserController extends AbstractController
{

    public function __construct(private readonly FlashManager $flashManager, private readonly UserRepository $userRepository)
    {
    }

    #[Route('/users', name: 'admin_user', methods: ['GET', 'POST'])]
    public function index(
        PaginatorInterface $paginator, 
        Request $request,
        TokenStorageInterface $tokenStorage,
    ): Response
    {
        $pagination = $paginator->paginate(
            $this->userRepository->createQueryBuilder('u'),
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

        if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            $targetUser = null;

            $targetId = $request->get('id');
            if ($targetId) {
                $targetUser = $this->userRepository->find($targetId);
            }

            if(!$targetUser) {
                $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_form');

                return $this->redirectToRoute('admin_user', ['config' => $config]);
            }
            
            dd($this->isCsrfTokenValid(sprintf('switch-%s-user', $targetId), $request->get('_token')));

            if (!$this->isCsrfTokenValid(sprintf('switch-%s-user', $targetId), $request->get('_token'))) {
                $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_csrf');
    
                return $this->redirectToRoute('admin_user', ['config' => $config]);
            }

            $tokenStorage->setToken(null);

            $targetUser->setIsVerified(!$targetUser->isVerified());
            $this->userRepository->save($targetUser, true);

            $this->flashManager->flash(ColorTypeEnum::Success->value, 'flash.update_user.success.is_verified');

            return $this->render('components/table/row/stream/switch.stream.html.twig', 
                [
                    'config' => [
                        'value' => $targetUser->isVerified(),
                        'item' => [
                            'id' => $targetUser->getId(),
                        ],
                    ]
                ]
            );
        }

        return $this->render('admin/user/index.html.twig', ['config' => $config]);
    }
}
