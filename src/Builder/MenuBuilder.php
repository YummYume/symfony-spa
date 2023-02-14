<?php

namespace App\Builder;

use App\Enum\SearchTypeEnum;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class MenuBuilder
{
    private string $requestUri;

    private string $pathInfo;

    public function __construct(private readonly FactoryInterface $factory, private readonly RequestStack $requestStack)
    {
        $currentRequest = $requestStack->getCurrentRequest();

        $this->requestUri = $currentRequest->getRequestUri();
        $this->pathInfo = $currentRequest->getPathInfo();
    }

    public function createBackOffice(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('back_office', [
            'childrenAttributes' => ['class' => 'menu menu-compact-sm bg-base-100 shadow-md w-fit px-2 rounded-box'],
        ]);

        $menu->addChild('back_office.home', [
            'route' => 'admin_homepage',
            'extras' => [
                'icon' => 'home',
            ],
        ]);
        $menu->addChild('back_office.users', [
            'route' => 'admin_user',
            'extras' => [
                'icon' => 'users',
            ],
        ]);

        return $menu;
    }

    public function createGlobalSearch(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('global_search', [
            'childrenAttributes' => ['class' => 'menu menu-compact-sm bg-base-100 shadow-md w-fit px-2 rounded-box'],
        ]);

        $this->setCurrent($menu->addChild('global_search.profiles', [
            'route' => 'app_search',
            'routeParameters' => ['t' => SearchTypeEnum::Profiles->value, 'q' => $options['query'] ?? ''],
            'extras' => [
                'icon' => 'users',
            ],
        ]), true);

        return $menu;
    }

    private function setCurrent(ItemInterface $item, bool $strict = false): void
    {
        $uri = $item->getUri();

        if (!$uri) {
            return;
        }

        $item->setCurrent($strict ? $this->requestUri === $uri : explode('?', $uri)[0] === $this->pathInfo);
    }
}
