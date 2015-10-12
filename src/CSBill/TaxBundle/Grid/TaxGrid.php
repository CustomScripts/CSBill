<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2015 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\TaxBundle\Grid;

use APY\DataGridBundle\Grid\Source\Entity;
use CSBill\DataGridBundle\Action\ActionColumn;
use CSBill\DataGridBundle\Action\Collection;
use CSBill\DataGridBundle\Action\DeleteMassAction;
use CSBill\DataGridBundle\Grid\Filters;
use CSBill\DataGridBundle\GridInterface;
use Doctrine\ORM\QueryBuilder;

class TaxGrid implements GridInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSource()
    {
        return new Entity('CSBillTaxBundle:Tax');
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(Filters $filters)
    {
        $filters->add(
            'inclusive',
            function (QueryBuilder $queryBuilder) {
                $aliases = $queryBuilder->getRootAliases();
                $alias = $aliases[0];

                $queryBuilder
                    ->andWhere($alias.'.type = :type')
                    ->setParameter('type', 'inclusive');
            }
        );

        $filters->add(
            'exclusive',
            function (QueryBuilder $queryBuilder) {
                $aliases = $queryBuilder->getRootAliases();
                $alias = $aliases[0];

                $queryBuilder
                    ->andWhere($alias.'.type = :type')
                    ->setParameter('type', 'exclusive');
            }
        );

        return $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function search(QueryBuilder $queryBuilder, $searchString)
    {
        $aliases = $queryBuilder->getRootAliases();

        $queryBuilder->andWhere($aliases[0].'.name LIKE :search')
            ->setParameter('search', "%{$searchString}%");
    }

    /**
     * {@inheritdoc}
     */
    public function getRowActions(Collection $collection)
    {
        $editAction = new ActionColumn();
        $editAction->setIcon('edit')
            ->setTitle('Edit Tax Rate')
            ->setRoute('_edit_tax_rate');

        $deleteAction = new ActionColumn();
        $deleteAction->setIcon('times')
            ->setTitle('Delete Tax')
            ->setRoute('_delete_tax_rate')
            ->setConfirm('Are you sure you want to delete this tax method?')
            ->setAttributes(array('class' => 'delete-tax'))
            ->setClass('danger');

        $collection->add($editAction);
        $collection->add($deleteAction);

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getMassActions()
    {
        return array(
            new DeleteMassAction(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'CSBillTaxBundle:Default:index.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function isSearchable()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isFilterable()
    {
        return true;
    }
}
