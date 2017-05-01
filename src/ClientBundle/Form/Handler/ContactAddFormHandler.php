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

namespace CSBill\ClientBundle\Form\Handler;

use CSBill\ClientBundle\Entity\Contact;
use CSBill\ClientBundle\Form\Type\ContactType;
use CSBill\CoreBundle\Templating\Template;
use CSBill\CoreBundle\Traits\SaveableTrait;
use CSBill\CoreBundle\Traits\SerializeTrait;
use SolidWorx\FormHandler\FormHandlerInterface;
use SolidWorx\FormHandler\FormHandlerResponseInterface;
use SolidWorx\FormHandler\FormHandlerSuccessInterface;
use SolidWorx\FormHandler\FormRequest;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ContactAddFormHandler implements FormHandlerInterface, FormHandlerResponseInterface, FormHandlerSuccessInterface
{
    use SaveableTrait,
        SerializeTrait;

    /**
     * {@inheritdoc}
     */
    public function getResponse(FormRequest $formRequest)
    {
        if ($formRequest->getForm()->isValid()) {
            return $this->serialize($formRequest->getForm()->getData());
        }

        return new Template(
            $this->getTemplate(),
            [
                'form' => $formRequest->getForm()->createView(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(FormFactoryInterface $factory = null, ...$options)
    {
        return $factory->create(ContactType::class, $options[0] ?? null, ['allow_delete' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function onSuccess($data, FormRequest $form): ?Response
    {
        /* @var Contact $data */
        $this->save($data);

        return null;
    }

    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        return '@CSBillClient/Ajax/contact_add.html.twig';
    }
}
