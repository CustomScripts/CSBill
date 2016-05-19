<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\TaxBundle\Form\Type;

use CSBill\TaxBundle\Entity\Tax;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $builder->add('rate', 'percent', ['precision' => 2]);

        $types = Tax::getTypes();

        array_walk($types, function (&$value) {
            $value = ucwords($value);
        });

        $builder->add(
            'type',
            'select2',
            [
                'choices' => $types,
                'choices_as_values' => false,
                'help' => 'tax.rates.explanation',
                'placeholder' => 'tax.rates.type.select',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'CSBill\TaxBundle\Entity\Tax']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tax';
    }
}
