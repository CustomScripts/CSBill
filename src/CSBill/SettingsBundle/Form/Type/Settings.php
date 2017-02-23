<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\SettingsBundle\Form\Type;

use CSBill\CoreBundle\Form\Type\ImageUpload;
use CSBill\CoreBundle\Form\Type\Select2;
use CSBill\NotificationBundle\Form\Type\NotificationType;
use CSBill\SettingsBundle\Model\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Settings.
 */
class Settings extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['section'] as $key => $setting) {
            if (is_array($setting)) {
                $builder->add($key, Settings::class, ['section' => $setting]);
                continue;
            }

            /* @var Setting $setting */
            $options = [
                'help' => $setting->getDescription(),
                'required' => false,
            ];

            $builder->add(...$this->getFieldType($setting, $options));
        }
    }

    /**
     * @param Setting $setting
     * @param array   $options
     *
     * @return string
     */
    protected function getFieldType(Setting $setting, array $options)
    {
        $type = $setting->getType();

        switch (strtolower($type)) {
            case 'select2':
                $type = Select2::class;
                $settingOptions = $setting->getOptions();
                $options['choices'] = array_flip($settingOptions);
                $options['choices_as_values'] = true;
                break;

            case 'choice':
                $type = ChoiceType::class;
                $settingOptions = $setting->getOptions();
                $options['choices'] = array_flip($settingOptions);
                $options['choices_as_values'] = true;
                break;

            case 'radio':
                $type = ChoiceType::class;
                $options['expanded'] = true;
                $options['multiple'] = false;
                $settingOptions = $setting->getOptions();
                $options['choices'] = array_flip($settingOptions);
                $options['choices_as_values'] = true;
                break;

            case '':
            case 'text':
                $type = TextType::class;
                break;

            case 'email':
                $type = EmailType::class;
                break;

            case 'checkbox':
                $type = CheckboxType::class;
                break;

            case 'notification':
                $type = NotificationType::class;
                break;

            case 'image_upload':
                $type = ImageUpload::class;
                break;

            case 'password':
                $type = PasswordType::class;
                break;
        }

        return [$setting->getKey(), $type, $options];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('section');
        $resolver->setAllowedTypes('section', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'settings';
    }
}
