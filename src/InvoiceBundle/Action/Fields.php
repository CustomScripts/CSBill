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

namespace CSBill\InvoiceBundle\Action;

use CSBill\CoreBundle\Form\FieldRenderer;
use CSBill\CoreBundle\Traits\JsonTrait;
use CSBill\InvoiceBundle\Form\Type\InvoiceType;
use Money\Currency;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class Fields
{
    use JsonTrait;

    /**
     * @var FormFactoryInterface
     */
    private $factory;

    /**
     * @var FieldRenderer
     */
    private $renderer;

    public function __construct(FormFactoryInterface $factory, FieldRenderer $renderer)
    {
        $this->factory = $factory;
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request)
    {
        $form = $this->factory->create(InvoiceType::class, null, ['currency' => new Currency($request->get('currency'))]);

        return $this->json($this->renderer->render($form->createView(), 'children[items].vars[prototype]'));
    }
}