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

namespace CSBill\InstallBundle\Tests\Form\Type;

use CSBill\CoreBundle\Tests\FormTestCase;
use CSBill\InstallBundle\Form\Type\DatabaseConfigType;

class DatabaseConfigTypeTest extends FormTestCase
{
    public function testSubmit()
    {
        $drivers = [
            'pdo_mysql' => 'MySQL',
        ];

        $formData = [
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'port' => 1234,
            'user' => 'root',
            'password' => 'password',
            'name' => 'testdb',
        ];

        $this->assertFormData($this->factory->create(DatabaseConfigType::class, null, ['drivers' => $drivers]), $formData, $formData);
    }
}
