<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\ClientBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ContactCollectionType extends AbstractType
{
    /**
     * @return string
     */
    public function getParent()
    {
	return CollectionType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
	return 'contacts';
    }
}