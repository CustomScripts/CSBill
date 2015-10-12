<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2015 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\InvoiceBundle\Entity;

use APY\DataGridBundle\Grid\Mapping as Grid;
use CSBill\ClientBundle\Entity\Client;
use CSBill\CoreBundle\Traits\Entity;
use CSBill\PaymentBundle\Entity\Payment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serialize;
use Money\Money;
use Rhumsaa\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="invoices")
 * @ORM\Entity(repositoryClass="CSBill\InvoiceBundle\Repository\InvoiceRepository")
 * @Gedmo\Loggable()
 * @Gedmo\SoftDeleteable()
 * @ORM\HasLifecycleCallbacks()
 * @Serialize\ExclusionPolicy("all")
 * @Serialize\XmlRoot("invoice")
 * @Hateoas\Relation("self", href=@Hateoas\Route("get_invoices", absolute=true))
 */
class Invoice
{
    use Entity\TimeStampable,
        Entity\SoftDeleteable,
        Entity\Archivable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serialize\Expose()
     */
    private $id;

    /**
     * @var Uuid
     *
     * @ORM\Column(name="uuid", type="uuid", length=36)
     * @Grid\Column(visible=false)
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=25)
     * @Grid\Column(name="status", type="status", field="status", title="status", filter="select", selectFrom="source", safe=false, label_function="invoice_label")
     * @Serialize\Expose()
     */
    private $status;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="CSBill\ClientBundle\Entity\Client", inversedBy="invoices")
     * @Assert\NotBlank
     * @Grid\Column(type="client", name="clients", field="client.name", title="client", filter="select", selectFrom="source", joinType="inner")
     * @Grid\Column(field="client.id", visible=false, joinType="inner")
     */
    private $client;

    /**
     * @var Money
     *
     * @ORM\Column(name="total", type="money")
     * @Grid\Column(type="currency")
     * @Serialize\Expose()
     */
    private $total;

    /**
     * @var Money
     *
     * @ORM\Column(name="base_total", type="money")
     * @Grid\Column(visible=false)
     * @Serialize\Expose()
     */
    private $baseTotal;

    /**
     * @var Money
     *
     * @ORM\Column(name="balance", type="money")
     * @Grid\Column(visible=false)
     * @Serialize\Expose()
     */
    private $balance;

    /**
     * @var Money
     *
     * @ORM\Column(name="tax", type="money", nullable=true)
     * @Grid\Column(type="currency")
     * @Serialize\Expose()
     */
    private $tax;

    /**
     * @var Money
     *
     * @ORM\Column(name="discount", type="float", nullable=true)
     * @Grid\Column(type="percent")
     * @Serialize\Expose()
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(name="terms", type="text", nullable=true)
     * @Grid\Column(visible=false)
     * @Serialize\Expose()
     */
    private $terms;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     * @Grid\Column(visible=false)
     * @Serialize\Expose()
     */
    private $notes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due", type="date", nullable=true)
     * @Assert\DateTime
     * @Grid\Column(visible=false)
     * @Serialize\Expose()
     */
    private $due;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paid_date", type="datetime", nullable=true)
     * @Assert\DateTime
     * @Grid\Column(visible=false)
     * @Serialize\Expose()
     */
    private $paidDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="invoice", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Assert\Valid
     * @Assert\Count(min=1, minMessage="You need to add at least 1 item to the Invoice")
     * @Serialize\Expose()
     */
    private $items;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="CSBill\PaymentBundle\Entity\Payment",
     *     mappedBy="invoice",
     *     cascade={"persist"}
     * )
     * @Serialize\Exclude()
     */
    private $payments;

    /**
     * @var ArrayCollection
     *
     * @ORM\Column(name="users", type="array", nullable=false)
     * @Assert\Count(min=1, minMessage="You need to select at least 1 user to attach to the Invoice")
     * @Grid\Column(visible=false)
     * @Serialize\Exclude()
     */
    private $users;

    /**
     * Constructer.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->setUuid(Uuid::uuid1());
    }

    /**
     * @param Uuid $uuid
     *
     * @return Invoice
     */
    public function setUuid(Uuid $uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return Uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Return users array.
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param array $users
     *
     * @return Invoice
     */
    public function setUsers(array $users = array())
    {
        $this->users = new ArrayCollection($users);

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Invoice
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set client.
     *
     * @param Client $client
     *
     * @return Invoice
     */
    public function setClient(Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get Client.
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set total.
     *
     * @param Money $total
     *
     * @return Invoice
     */
    public function setTotal(Money $total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total.
     *
     * @return Money
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set base total.
     *
     * @param Money $baseTotal
     *
     * @return Invoice
     */
    public function setBaseTotal(Money $baseTotal)
    {
        $this->baseTotal = $baseTotal;

        return $this;
    }

    /**
     * Get base total.
     *
     * @return Money
     */
    public function getBaseTotal()
    {
        return $this->baseTotal;
    }

    /**
     * @return Money
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param Money $balance
     *
     * @return Invoice
     */
    public function setBalance(Money $balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Set discount.
     *
     * @param float $discount
     *
     * @return Invoice
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount.
     *
     * @return Money
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set due.
     *
     * @param \DateTime $due
     *
     * @return Invoice
     */
    public function setDue(\DateTime $due)
    {
        $this->due = $due;

        return $this;
    }

    /**
     * Get due.
     *
     * @return \DateTime
     */
    public function getDue()
    {
        return $this->due;
    }

    /**
     * Set paidDate.
     *
     * @param \DateTime $paidDate
     *
     * @return Invoice
     */
    public function setPaidDate(\DateTime $paidDate)
    {
        $this->paidDate = $paidDate;

        return $this;
    }

    /**
     * Get paidDate.
     *
     * @return \DateTime
     */
    public function getPaidDate()
    {
        return $this->paidDate;
    }

    /**
     * Add item.
     *
     * @param Item $item
     *
     * @return Invoice
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
        $item->setInvoice($this);

        return $this;
    }

    /**
     * Removes an item.
     *
     * @param Item $item
     *
     * @return Invoice
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
        $item->setInvoice(null);

        return $this;
    }

    /**
     * Get items.
     *
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add payment.
     *
     * @param Payment $payment
     *
     * @return Invoice
     */
    public function addPayment(Payment $payment)
    {
        $this->payments[] = $payment;
        $payment->setInvoice($this);

        return $this;
    }

    /**
     * Removes a payment.
     *
     * @param Payment $payment
     *
     * @return Invoice
     */
    public function removePayment(Payment $payment)
    {
        $this->payments->removeElement($payment);

        return $this;
    }

    /**
     * Get payments.
     *
     * @return ArrayCollection
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @return string
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * @param string $terms
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return Money
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param Money $tax
     *
     * @return Invoice
     */
    public function setTax(Money $tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * PrePersist listener to update the invoice total.
     *
     * @ORM\PrePersist
     */
    public function updateItems()
    {
        if (count($this->items)) {
            foreach ($this->items as $item) {
                $item->setInvoice($this);
            }
        }
    }
}
