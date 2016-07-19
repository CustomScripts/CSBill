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

use CSBill\CoreBundle\Form\Type\Select2;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
            Select2::class,
            [
                'choices' => array_flip($transports),
                'choices_as_values' => true,
                'placeholder' => 'Choose Mail Transport',
                'constraints' => new Constraints\NotBlank(),
            ]
        );

        $builder->add(
            'host',
            null,
            [
                'constraints' => new Constraints\NotBlank(['groups' => 'smtp']),
            ]
        );

        $builder->add(
            'port',
            IntegerType::class,
            [
                'constraints' => new Constraints\Type(['groups' => ['smtp'], 'type' => 'integer']),
                'required' => false,
            ]
        );

        $builder->add(
            'encryption',
            Select2::class,
            [
                'placeholder' => 'None',
                'choices' => [
                    'SSL' => 'ssl',
                    'TLS' => 'tls',
                ],
                'choices_as_values' => true,
                'required' => false,
            ]
        );

        $builder->add(
            'user',
            null,
            [
                'constraints' => new Constraints\NotBlank(['groups' => 'gmail']),
                'required' => false,
            ]
        );

        $builder->add(
            'password',
            PasswordType::class,
            [
                'constraints' => new Constraints\NotBlank(['groups' => 'gmail']),
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
    public function getBlockPrefix()
    {
        return 'email_settings';
    }
}
