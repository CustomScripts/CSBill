<?php

/**
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\DataGridBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class GridConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
	$treeBuilder = new TreeBuilder();
	$node = $treeBuilder->root('datagrid');

	$node
	    ->useAttributeAsKey('name', true)
	    ->prototype('array')
		->children()
		    ->scalarNode('icon')
			->defaultValue('')
		    ->end()
		    ->scalarNode('title')
			->defaultValue('')
		    ->end()
		    ->arrayNode('source')
			->children()
			    ->scalarNode('repository')
				->cannotBeEmpty()
				->isRequired()
			    ->end()
			    ->scalarNode('method')
				->cannotBeEmpty()
				->isRequired()
			    ->end()
			->end()
		    ->end()
		    ->arrayNode('columns')
			->prototype('array')
			    ->children()
				->scalarNode('name')
				    ->isRequired()
				    ->cannotBeEmpty()
				->end()
				->scalarNode('label')
				    ->isRequired()
				    ->cannotBeEmpty()
				->end()
				->booleanNode('editable')
				    ->defaultFalse()
				->end()
				->scalarNode('cell')
				    ->isRequired()
				    ->cannotBeEmpty()
				->end()
			    ->end()
			->end()
		    ->end()
		    ->arrayNode('search')
			->children()
			    ->arrayNode('fields')
				->prototype('scalar')
				->end()
			    ->end()
			->end()
		    ->end()
		    ->arrayNode('filters')
			->prototype('array')
			    ->children()
				->scalarNode('type')
				    ->isRequired()
				    ->cannotBeEmpty()
				->end()
				->booleanNode('multiple')
				    ->defaultFalse()
				->end()
				->arrayNode('source')
				    ->children()
					->scalarNode('repository')
					    ->cannotBeEmpty()
					    ->isRequired()
					->end()
					->scalarNode('method')
					    ->cannotBeEmpty()
					    ->isRequired()
					->end()
				    ->end()
				->end()
			    ->end()
			->end()
		    ->end()
		->end()
	    ->end();

	// Here you should define the parameters that are allowed to
	// configure your bundle. See the documentation linked above for
	// more information on that topic.
	return $treeBuilder;
    }
}