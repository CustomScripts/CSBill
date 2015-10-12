<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2015 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\ClientBundle\Repository;

use CSBill\ClientBundle\Entity\Client;
use CSBill\ClientBundle\Entity\Credit;
use Doctrine\ORM\EntityRepository;
use Money\Money;

class CreditRepository extends EntityRepository
{
    /**
     * @param Client $client
     * @param Money  $amount
     *
     * @return \CSBill\ClientBundle\Entity\Credit
     */
    public function addCredit(Client $client, Money $amount)
    {
        $credit = $client->getCredit();

        $credit->setValue($credit->getValue()->add($amount));

        return $this->save($credit);
    }

    /**
     * @param Client $client
     * @param Money  $amount
     *
     * @return \CSBill\ClientBundle\Entity\Credit
     */
    public function deductCredit(Client $client, Money $amount)
    {
        $credit = $client->getCredit();

        $credit->setValue($credit->getValue()->subtract($amount));

        return $this->save($credit);
    }

    /**
     * @param Credit $credit
     *
     * @return Credit
     */
    private function save(Credit $credit)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($credit);
        $entityManager->flush();

        return $credit;
    }
}
