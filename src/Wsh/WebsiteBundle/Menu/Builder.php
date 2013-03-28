<?php
namespace Wsh\WebsiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Values', array(
            'route' => 'values',
        ));
        $menu['Values']->setLinkAttribute('data-description', 'House rules');

        $menu->addChild('Services', array(
            'route' => 'services',
        ));
        $menu['Services']->setLinkAttribute('data-description', 'What we do');


        $menu->addChild('Clients', array(
            'route' => 'clients', 
        ));
        $menu['Clients']->setLinkAttribute('data-description', 'We call them partners');    


        $menu->addChild('Team', array(
            'route' => 'team',
        ));
        $menu['Team']->setLinkAttribute('data-description', 'Our family members');


        $menu->addChild('Blog', array(
            'route' => 'blog',
        ));
        $menu['Blog']->setLinkAttribute('data-description', 'More about us');

        $menu->addChild('Contact', array(
            'route' => 'contact',
        ));
        $menu['Contact']->setLinkAttribute('data-description', 'Fancy us?');

        $request = $this->container->get('request');
        $routeName = $request->get('_route');
        $reqUri = $request->getRequestUri();


        foreach ($menu as $menuItemName => $item) {
            if (strstr($reqUri, $item->getUri())) {
                $menu->getChild($menuItemName)->setCurrent(true);
            }
        }
        return $menu;
    }
}