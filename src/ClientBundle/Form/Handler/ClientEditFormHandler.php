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

use CSBill\CoreBundle\Templating\Template;
use SolidWorx\FormHandler\FormCollectionHandlerInterface;
use SolidWorx\FormHandler\FormRequest;

class ClientEditFormHandler extends ClientFormHandler implements FormCollectionHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getResponse(FormRequest $formRequest): Template
    {
        return new Template(
            '@CSBillClient/Default/edit.html.twig',
            [
                'form' => $formRequest->getForm()->createView(),
                'client' => $formRequest->getOptions()[0],
            ]
        );
    }
}
