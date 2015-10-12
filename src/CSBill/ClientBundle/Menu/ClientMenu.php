<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2015 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\ClientBundle\Menu;

use CSBill\CoreBundle\Menu\Core\AuthenticatedMenu;
use Knp\Menu\ItemInterface;

class ClientMenu extends AuthenticatedMenu
{
    /**
     * Renders the top menu for clients.
     *
     * @param ItemInterface $menu
     */
    public function topMenu(ItemInterface $menu)
    {
        $translator = $this->container->get('translator');

        $menu->addChild(
            $translator->trans('client.menu.index'),
            array('route' => '_clients_index')
        );
    }

    /**
     * Renders the client index menu.
     *
     * @param ItemInterface $menu
     */
    public function clientsMenu(ItemInterface $menu)
    {
        $translator = $this->container->get('translator');

        $menu->addChild(
            $translator->trans('client.menu.list'),
            array(
                'extras' => array(
                    'icon' => 'file-o',
                ),
                'route' => '_clients_index',
            )
        );

        $menu->addChild(
            $translator->trans('client.menu.add'),
            array(
                'extras' => array(
                    'icon' => 'user',
                ),
                'route' => '_clients_add',
            )
        );
    }

    /**
     * Renders the client view menu.
     *
     * @param ItemInterface $menu
     */
    public function clientViewMenu(ItemInterface $menu)
    {
        $request = $this->container->get('request');
        $translator = $this->container->get('translator');

        $this->clientsMenu($menu);

        $menu->addChild(
            $translator->trans('view_client'),
            array(
                'extras' => array(
                    'icon' => 'eye',
                ),
                'route' => '_clients_view',
                'routeParameters' => array(
                    'id' => $request->get('id'),
                ),
            )
        );

        $menu->addChild(
            $translator->trans('client.menu.create.invoice'),
            array(
                'extras' => array(
                    'icon' => 'file-o',
                ),
                'route' => '_invoices_create',
                'routeParameters' => array(
                    'client' => $request->get('id'),
                ),
            )
        );

        $menu->addChild(
            $translator->trans('client.menu.create.quote'),
            array(
                'extras' => array(
                    'icon' => 'file-o',
                ),
                'route' => '_quotes_create',
                'routeParameters' => array(
                    'client' => $request->get('id'),
                ),
            )
        );
    }
}
