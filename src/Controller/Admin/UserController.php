<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enum\ColorTypeEnum;
use App\Form\Admin\UserType;
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
            $this->userRepository->createQueryBuilder('u')->leftJoin('u.profile', 'p'),
            $request->query->getInt('page', 1),
            5
        );

        $config = [
            'cols' => [
                'email' => [
                    'type' => 'text',
                    'label' => 'user.email',
                    'queryKey' => 'u.email',
                ],
                'profile.username' => [
                    'type' => 'text',
                    'label' => 'user.username',
                    'queryKey' => 'p.username',
                ],
                'verified' => $this->getUserVerifiedRowOptions(),
                'actions' => [
                    'info' => [
                        'route' => 'admin_user_show',
                        'routeParams' => [
                            'id' => static fn (User $user): string => $user->getId()->toBase32(),
                        ],
                        'icon' => 'eye',
                    ],
                    'accent' => [
                        'route' => 'admin_user_edit',
                        'routeParams' => [
                            'id' => static fn (User $user): string => $user->getId()->toBase32(),
                        ],
                        'icon' => 'pencil',
                    ],
                ],
            ],
            'pagination' => $pagination,
        ];

        return $this->render('admin/user/index.html.twig', ['config' => $config]);
    }

    #[Route('/{id}', name: 'admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $userRepository->save($user, true);

                $this->flashManager->flash(ColorTypeEnum::Success->value, 'flash.common.updated', translationDomain: 'admin');
            } else {
                $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_form');
            }

            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->render('admin/user/stream/edit.stream.html.twig', [
                    'user' => $user,
                    'form' => $form->isValid() ? $this->createForm(UserType::class, $user) : $form,
                ]);
            } elseif ($form->isValid()) {
                return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()->toBase32()], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete-'.$user->getId()->toBase32(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);

            $this->flashManager->flash(ColorTypeEnum::Success->value, 'flash.common.deleted', translationDomain: 'admin');
        } else {
            $this->flashManager->flash(ColorTypeEnum::Error->value, 'flash.common.invalid_csrf');
        }

        return $this->redirectToRoute('admin_user', status: Response::HTTP_SEE_OTHER);
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

            $user->setVerified($verified);
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
            'label' => 'user.verified',
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
