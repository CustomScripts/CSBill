<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\CoreBundle\Tests;

use CSBill\CoreBundle\Form\Extension;
use CSBill\CoreBundle\Form\Type;
use CSBill\CoreBundle\Security\Encryption;
use CSBill\MoneyBundle\Form\Extension\MoneyExtension;
use Faker\Factory;
use Faker\Generator;
use Money\Currency;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;

class FormTestCase extends TypeTestCase
{
    /**
     * @var Generator
     */
    protected $faker;

    protected function setUp()
    {
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addTypeExtensions($this->getTypedExtensions())
            ->addTypes($this->getTypes())
            ->getFormFactory();

        $this->dispatcher = \Mockery::mock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);

        $this->faker = Factory::create();
    }

    /**
     * Get registered form extensions.
     *
     * @return array
     */
    protected function getTypedExtensions()
    {
        $validator = \Mockery::mock('Symfony\Component\Validator\ValidatorInterface');

        $validator->shouldReceive('validate')->zeroOrMoreTimes()->andReturn([]);

        return [
            new Extension\FormHelpExtension(),
            new MoneyExtension(new Currency('USD')),
            new FormTypeValidatorExtension(
                $validator
            ),
        ];
    }

    /**
     * Get registered form types.
     *
     * @return array
     */
    protected function getTypes()
    {
        return [
            'select2' => new Type\Select2Type(),
            'image_upload' => new Type\ImageUploadType(
                \Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface'),
                new Encryption(rand())
            ),
        ];
    }

    protected function assertFormData($form, array $formData, $object)
    {
        if (!$form instanceof FormInterface) {
            $form = $this->factory->create($form);
        }

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
