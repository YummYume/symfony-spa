<?php

namespace App\Builder;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    public function __construct(private readonly FactoryInterface $factory)
    {
    }

    public function createBackoffice(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('backoffice', [
          'childrenAttributes' => ['class' => 'menu menu-compact-sm bg-base-100 shadow-md w-fit px-2 rounded-box'],
        ]);

        $menu->addChild('page.menu.home', [
          'route' => 'admin_homepage',
          'extras' => [
            'icon' => 'home',
          ],
        ]);
        $menu->addChild('page.menu.users', [
          'route' => 'admin_user',
          'extras' => [
            'icon' => 'users',
          ],
        ]);

        return $menu;
    }
}
