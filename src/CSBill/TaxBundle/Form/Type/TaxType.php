<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2015 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\TaxBundle\Form\Type;

use CSBill\TaxBundle\Entity\Tax;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class Tax.
 */
class TaxType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('rate', 'percent', array('precision' => 2));

        $builder->add(
            'type',
            'select2',
            array(
                'choices' => Tax::getTypes(),
                'help' => 'tax.rates.explanation',
                'placeholder' => 'tax.rates.type.select',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'CSBill\TaxBundle\Entity\Tax'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tax';
    }
}
