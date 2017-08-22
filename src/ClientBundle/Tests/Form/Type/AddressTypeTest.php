<?php

declare(strict_types=1);

/*
 * This file is part of SolidInvoice project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SolidInvoice\ClientBundle\Tests\Form\Type;

use SolidInvoice\ClientBundle\Entity\Address;
use SolidInvoice\ClientBundle\Form\Type\AddressType;
use SolidInvoice\CoreBundle\Tests\FormTestCase;
use Faker\Factory;

class AddressTypeTest extends FormTestCase
{
    public function testSubmit()
    {
        $faker = Factory::create();

        $formData = [
            'street1' => $faker->buildingNumber.' '.$faker->streetName,
            'street2' => $faker->randomNumber(2).' '.$faker->streetName.' '.$faker->streetSuffix,
            'city' => $faker->city,
            'state' => $faker->state,
            'zip' => $faker->postcode,
            'country' => $faker->countryCode,
        ];

        $this->assertFormData(AddressType::class, $formData, $formData);
    }
}
