<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\NotificationBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NotificationHandlerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
	if (!$container->hasDefinition('notification.sender')) {
	    return;
	}

	$definition = $container->getDefinition('notification.sender');

	$services = $container->findTaggedServiceIds('notification.handler');

	foreach ($services as $id => $parameters) {
	    $definition->addMethodCall('addHandler', [new Reference($id)]);
	}
    }
}
