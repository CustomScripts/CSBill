<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\InstallBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class EmailSettingsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transports = $options['transports'];

        $builder->add(
            'transport',
            'select2',
            [
                'choices' => $transports,
                'choices_as_values' => false,
                'placeholder' => 'Choose Mail Transport',
                'constraints' => [
                     new Constraints\NotBlank(),
                ],
            ]
        );

        $builder->add(
            'host',
            null,
            [
                'constraints' => [
                    new Constraints\NotBlank(['groups' => 'smtp']),
                ],
            ]
        );

        $builder->add(
            'port',
            'integer',
            [
                'constraints' => [
                    new Constraints\Type(['groups' => ['smtp'], 'type' => 'integer']),
                ],
                'required' => false,
            ]
        );

        $builder->add(
            'encryption',
            'select2',
            [
                'placeholder' => 'None',
                'choices' => [
                    'ssl' => 'SSL',
                    'tls' => 'TLS',
                ],
                'choices_as_values' => false,
                'required' => false,
            ]
        );

        $builder->add(
            'user',
            null,
            [
                'constraints' => [
                    new Constraints\NotBlank(['groups' => 'gmail']),
                ],
                'required' => false,
            ]
        );

        $builder->add(
            'password',
            'password',
            [
                'constraints' => [
                    new Constraints\NotBlank(['groups' => 'gmail']),
                ],
                'required' => false,
            ]
        );

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            if ('gmail' === $data['transport']) {
                $data['host'] = null;
                $data['port'] = null;
                $data['encryption'] = null;
            } elseif ('sendmail' === $data['transport'] || 'mail' === $data['transport']) {
                $data['host'] = null;
                $data['port'] = null;
                $data['encryption'] = null;
                $data['user'] = null;
                $data['password'] = null;
            }

            $event->setData($data);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['transports']);

        $resolver->setDefaults(
            [
                'validation_groups' => function (FormInterface $form) {
                    $data = $form->getData();

                    if ('smtp' === $data['transport']) {
                        return ['Default', 'smtp'];
                    }

                    if ('gmail' === $data['transport']) {
                        return ['Default', 'gmail'];
                    }

                    return ['Default'];
                },
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'email_settings';
    }
}
