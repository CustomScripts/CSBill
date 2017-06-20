<?php

declare(strict_types=1);

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\PaymentBundle\Entity;

use CSBill\CoreBundle\Traits\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Payum\Core\Model\GatewayConfigInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation as Serialize;

/**
 * @ORM\Table(name="payment_methods")
 * @ORM\Entity(repositoryClass="CSBill\PaymentBundle\Repository\PaymentMethodRepository")
 * @UniqueEntity("gatewayName")
 * @Gedmo\SoftDeleteable()
 * @Gedmo\Loggable()
 */
class PaymentMethod implements GatewayConfigInterface
{
    use Entity\TimeStampable,
        Entity\SoftDeleteable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=125)
     * @Assert\NotBlank
     * @Serialize\Groups({"payment_api"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="gateway_name", type="string", length=125, unique=true)
     * @Serialize\Groups({"payment_api"})
     */
    private $gatewayName;

    /**
     * @var string
     *
     * @ORM\Column(name="factory", type="string", length=125)
     */
    private $factoryName;

    /**
     * @var array
     *
     * @ORM\Column(name="config", type="array", nullable=true)
     * @Serialize\Groups({"payment_api"})
     */
    private $config;

    /**
     * @ORM\Column(name="internal", type="boolean", nullable=true)
     *
     * @var bool
     */
    private $internal;

    /**
     * @ORM\Column(name="enabled", type="boolean",  nullable=true)
     * @Serialize\Groups({"payment_api"})
     *
     * @var bool
     */
    private $enabled;

    /**
     * @var Collection|Payment[]
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="method", cascade={"persist"})
     */
    private $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return PaymentMethod
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getGatewayName(): ?string
    {
        return $this->gatewayName;
    }

    /**
     * @param string $gatewayName
     *
     * @return PaymentMethod
     */
    public function setGatewayName($gatewayName): self
    {
        $this->gatewayName = $gatewayName;

        return $this;
    }

    /**
     * Set settings.
     *
     * @param array $config
     *
     * @return PaymentMethod
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get settings.
     *
     * @return array
     */
    public function getConfig(): ?array
    {
        return $this->config;
    }

    /**
     * @return bool
     */
    public function isInternal(): bool
    {
        return (bool) $this->internal;
    }

    /**
     * @param bool $internal
     *
     * @return PaymentMethod
     */
    public function setInternal(bool $internal): self
    {
        $this->internal = (bool) $internal;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->enabled;
    }

    /**
     * @param bool $enabled
     *
     * @return PaymentMethod
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = (bool) $enabled;

        return $this;
    }

    /**
     * @return PaymentMethod
     */
    public function enable(): self
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * @return PaymentMethod
     */
    public function disable(): self
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * Add payment.
     *
     * @param Payment $payment
     *
     * @return PaymentMethod
     */
    public function addPayment(Payment $payment): self
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * Removes a payment.
     *
     * @param Payment $payment
     *
     * @return PaymentMethod
     */
    public function removePayment(Payment $payment): self
    {
        $this->payments->removeElement($payment);

        return $this;
    }

    /**
     * Get payments.
     *
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    /**
     * {@inheritdoc}
     */
    public function getFactoryName(): ?string
    {
        return $this->factoryName;
    }

    /**
     * {@inheritdoc}
     */
    public function setFactoryName($factory): self
    {
        $this->factoryName = $factory;

        return $this;
    }

    /**
     * Return the payment method name as a string.
     *
     * @return string
     */
    public function __toString(): ?string
    {
        return $this->name;
    }
}
