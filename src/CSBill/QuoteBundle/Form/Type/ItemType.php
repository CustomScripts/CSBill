<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\QuoteBundle\Form\Type;

use CSBill\TaxBundle\Form\Type\Tax;
use CSBill\TaxBundle\Repository\TaxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    /**
     * @var TaxRepository
     */
    private $taxRepo;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->taxRepo = $entityManager->getRepository('CSBillTaxBundle:Tax');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'description',
            'textarea',
            [
                'attr' => [
                    'class' => 'input-medium quote-item-name',
                ],
            ]
        );

        $builder->add(
            'price',
            'money',
            [
                'attr' => [
                    'class' => 'input-small quote-item-price',
                ],
            ]
        );

        $builder->add(
            'qty',
            'number',
            [
                'empty_data' => 1,
                'attr' => [
                    'class' => 'input-mini quote-item-qty',
                ],
            ]
        );

        if ($this->taxRepo->taxRatesConfigured()) {
            $builder->add(
                'tax',
                new Tax(),
                [
                    'class' => 'CSBill\TaxBundle\Entity\Tax',
                    'placeholder' => 'Choose Tax Type',
                    'attr' => [
                        'class' => 'input-mini quote-item-tax',
                    ],
                    'required' => false,
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'quote_item';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'CSBill\QuoteBundle\Entity\Item']);
    }
}
