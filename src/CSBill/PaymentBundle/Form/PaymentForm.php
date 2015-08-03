<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2015 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\PaymentBundle\Form;

use CSBill\PaymentBundle\Repository\PaymentMethodRepository;
use Doctrine\ORM\Query\Expr;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'payment_method',
            'entity',
            array(
                'class' => 'CSBillPaymentBundle:PaymentMethod',
                'query_builder' => function (PaymentMethodRepository $repository) use ($options) {
                    $queryBuilder = $repository->createQueryBuilder('pm');
                    $expression = new Expr();
                    $queryBuilder->where($expression->eq('pm.enabled', 1));

                    // If user is not logged in, exclude internal payment methods
                    if (null === $options['user']) {
                        $queryBuilder->andWhere($expression->eq('pm.internal', 0));
                    }

                    $queryBuilder->orderBy($expression->asc('pm.name'));

                    return $queryBuilder;
                },
                'required' => true,
                'preferred_choices' => $options['preferred_choices'],
                'constraints' => new Assert\NotBlank(),
                'placeholder' => 'Choose Payment Method',
                'attr' => array(
                    'class' => 'select2',
                ),
            )
        );

        $builder->add(
            'amount',
            'money',
            array(
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\GreaterThan(0),
                ),
            )
        );

        if (null !== $options['user']) {
            $builder->add('capture_online', 'checkbox', array('data' => true));
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('user', 'preferred_choices'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'payment';
    }
}
