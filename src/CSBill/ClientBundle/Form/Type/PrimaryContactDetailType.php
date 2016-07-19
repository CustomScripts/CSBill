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

use CSBill\ClientBundle\Entity\ContactType as Entity;
use CSBill\ClientBundle\Form\ConstraintBuilder;
use CSBill\ClientBundle\Form\ViewTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PrimaryContactDetailType extends AbstractType
{
    /**
     * @var Entity
     */
    private $item;

    /**
     * @param Entity $item
     */
    public function __construct(Entity $item)
    {
        $this->item = $item;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $itemOptions = $this->item->getOptions();

        $builder->add('id', HiddenType::class);

        $builder->add(
            'value',
            $this->item->getType(),
            [
                'constraints' => array_merge(
                    ConstraintBuilder::build(
                        isset($itemOptions['constraints']) ? $itemOptions['constraints'] : []
                    ),
                    [new NotBlank()]
                ),
                'label' => $this->item->getName(),
            ]
        );

        $type = $builder->create(
            'type',
            HiddenType::class,
            [
                'data' => $this->item->getType(),
            ]
        );

        $type->addViewTransformer(new ViewTransformer\ContactTypeTransformer($this->item));

        $builder->add($type);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'CSBill\ClientBundle\Entity\PrimaryContactDetail']);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'primary_contact_details';
    }
}
