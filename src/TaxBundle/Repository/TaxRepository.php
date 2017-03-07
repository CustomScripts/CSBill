<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2015 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\TaxBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TaxRepository extends EntityRepository
{
    /**
     * Gets an array of all the available tax rates.
     */
    public function getTaxList()
    {
	$queryBuilder = $this->createQueryBuilder('t')
	    ->select(
		[
		    't.name',
		    't.rate',
		    't.type',
		]
	    );

	$query = $queryBuilder->getQuery();

	$query->useQueryCache(true)
	    ->useResultCache(true, (60 * 60 * 24), 'tax_list');

	return $query->getArrayResult();
    }

    /**
     * @return bool
     */
    public function taxRatesConfigured()
    {
	return $this->getTotal() > 0;
    }

    /**
     * Gets an array of all the available tax rates.
     */
    public function getTotal()
    {
	$queryBuilder = $this->createQueryBuilder('t')
	->select('COUNT(t.id)');

	return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getGridQuery()
    {
	$qb = $this->createQueryBuilder('t');

	$qb->select('t');

	return $qb;
    }
}
