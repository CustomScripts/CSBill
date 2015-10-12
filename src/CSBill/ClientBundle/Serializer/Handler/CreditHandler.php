<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2015 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\ClientBundle\Serializer\Handler;

use CSBill\MoneyBundle\Formatter\MoneyFormatter;
use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use Money\Money;

class CreditHandler
{
    /**
     * @var MoneyFormatter
     */
    private $formatter;

    /**
     * @param MoneyFormatter $formatter
     */
    public function __construct(MoneyFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param Money                    $money
     * @param array                    $type
     * @param Context                  $context
     *
     * @return float
     */
    public function serializeMoney(JsonSerializationVisitor $visitor, Money $money, array $type, Context $context)
    {
        return $this->formatter->toFloat($money);
    }
}
