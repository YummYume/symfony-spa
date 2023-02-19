<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enum\ColorTypeEnum;
use App\Manager\FlashManager;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\UX\Turbo\TurboBundle;

final class UserController extends AbstractController
{
    public function __construct(
        private readonly FlashManager $flashManager,
        private readonly UserRepository $userRepository,
        private readonly CsrfTokenManagerInterface $csrfTokenManager
    ) {
    }

    #[Route('/users', name: 'admin_user', methods: ['GET', 'POST'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
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
                    'queryKey' => 'u.id.toBase32',
                ],
                'email' => [
                    'type' => 'text',
                    'label' => 'table.user.email',
                    'queryKey' => 'u.email',
                ],
                'verified' => $this->getUserVerifiedRowOptions(),
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

    // TODO use sprintf for condition when php-cs-fixer supports PHP 8.2
    #[Route(
        '/users/{id}/verify',
        name: 'admin_user_verify',
        methods: ['POST'],
        condition: ('"'.TurboBundle::STREAM_FORMAT.'" === request.getPreferredFormat()')
    )]
    public function setUserVerified(User $user, Request $request): Response
    {
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        $token = $request->get('_token');

        if ($this->isCsrfTokenValid(sprintf('switch-%s-user', $user->getId()->toBase32()), $token)) {
            $verified = 'on' === $request->get('_verified');

            $user->setIsVerified($verified);
            $this->userRepository->save($user, true);

            $this->flashManager->flash(ColorTypeEnum::Success->value, $verified ? 'flash.user.verified' : 'flash.user.unverified', [
                'email' => $user->getEmail(),
            ], 'admin');
        } else {
            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_csrf');
        }

        return $this->render('components/table/row/stream/switch.stream.html.twig', [
            'target' => sprintf('user-verify-form_%s', $user->getId()->toBase32()),
            'config' => [
                'value' => $user->isVerified(),
                'item' => $user,
                'col' => $this->getUserVerifiedRowOptions(),
            ],
        ]);
    }

    private function getUserVerifiedRowOptions(): array
    {
        return [
            'type' => 'switch',
            'label' => 'table.user.verified',
            'queryKey' => 'u.verified',
            'formRoute' => 'admin_user_verify',
            'formRouteParams' => [
                'id' => static fn (User $user): string => $user->getId()->toBase32(),
            ],
            'extras' => [
                'id' => static fn (User $user): string => sprintf('user-verify-form_%s', $user->getId()->toBase32()),
                'name' => '_verified',
                'csrfToken' => fn (User $user): string => $this->csrfTokenManager->getToken(
                    sprintf('switch-%s-user', $user->getId()->toBase32())
                )->getValue(),
                'submitOnChange' => true,
            ],
        ];
    }
}
