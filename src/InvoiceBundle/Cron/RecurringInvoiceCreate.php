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

namespace CSBill\InvoiceBundle\Cron;

use Carbon\Carbon;
use Cron\CronExpression;
use CSBill\CronBundle\CommandInterface;
use CSBill\InvoiceBundle\Entity\Invoice;
use CSBill\InvoiceBundle\Entity\Item;
use CSBill\InvoiceBundle\Manager\InvoiceManager;
use CSBill\InvoiceBundle\Repository\InvoiceRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class RecurringInvoiceCreate.
 */
class RecurringInvoiceCreate implements CommandInterface
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var InvoiceManager
     */
    private $invoiceManager;

    /**
     * RecurringInvoiceCreate constructor.
     *
     * @param ManagerRegistry $registry
     * @param InvoiceManager  $invoiceManager
     */
    public function __construct(ManagerRegistry $registry, InvoiceManager $invoiceManager)
    {
        $this->entityManager = $registry->getManager();
        $this->invoiceManager = $invoiceManager;
    }

    /**
     * {@inheritdoc}
     */
    public function isDue(): bool
    {
        // We want to run this process always when a cron runs, to ensure we always send out the invoices
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function process()
    {
        /** @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $this->entityManager->getRepository('CSBillInvoiceBundle:Invoice');

        $invoices = $invoiceRepository->getRecurringInvoices();

        foreach ($invoices as $invoice) {
            $recurringInfo = $invoice->getRecurringInfo();
            if (null !== ($recurringInfo->getDateEnd()) && Carbon::instance($recurringInfo->getDateEnd())->isFuture()) {
                continue;
            }

            $cron = CronExpression::factory($recurringInfo->getFrequency());

            if (true === $cron->isDue(Carbon::now())) {
                $newInvoice = $this->invoiceManager->duplicate($invoice);
                $this->setItemsDescription($newInvoice);

                $this->invoiceManager->accept($newInvoice);
            }
        }
    }

    /**
     * @param Invoice $invoice
     */
    private function setItemsDescription(Invoice $invoice)
    {
        $now = Carbon::now();

        /** @var Item $item */
        foreach ($invoice->getItems() as $item) {
            $description = $item->getDescription();

            $description = str_replace(
                [
                    '{day}',
                    '{day_name}',
                    '{month}',
                    '{year}',
                ],
                [
                    $now->day,
                    $now->format('l'),
                    $now->format('F'),
                    $now->year,
                ],
                $description
            );

            $item->setDescription($description);
        }

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();
    }
}
