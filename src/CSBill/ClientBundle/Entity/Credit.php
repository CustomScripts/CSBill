<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2015 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\ClientBundle\Entity;

use CSBill\CoreBundle\Traits\Entity;
use Money\Money;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serialize;

/**
 * CSBill\ClientBundle\Entity\Credit.
 *
 * @ORM\Table(name="client_credit")
 * @ORM\Entity(repositoryClass="CSBill\ClientBundle\Repository\CreditRepository")
 * @Gedmo\Loggable()
 * @Gedmo\SoftDeleteable()
 */
class Credit
{
    use Entity\TimeStampable,
        Entity\SoftDeleteable;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serialize\Groups({"noneg"})
     */
    private $id;

    /**
     * @ORM\Embedded(class="CSBill\MoneyBundle\Entity\Money")
     *
     * @var Money
     * @Serialize\Groups({"api", "js"})
     * @Serialize\SerializedName("credit")
     */
    private $value;

    /**
     * @var Client
     * @ORM\OneToOne(targetEntity="CSBill\ClientBundle\Entity\Client", inversedBy="credit")
     * @Serialize\Groups({"js"})
     */
    private $client;

    public function __construct()
    {
	$this->value = new \CSBill\MoneyBundle\Entity\Money();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Money
     */
    public function getValue()
    {
	return $this->value->getMoney();
    }

    /**
     * @param Money $value
     *
     * @return $this
     */
    public function setValue(Money $value)
    {
	$this->value->setMoney($value);

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
	return $this->getValue()->getAmount();
    }
}
