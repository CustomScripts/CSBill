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

namespace CSBill\SettingsBundle;

use CSBill\SettingsBundle\Exception\InvalidSettingException;
use CSBill\SettingsBundle\Repository\SettingsRepository;
use SolidWorx\Util\ArrayUtil;

final class SystemConfig
{
    /**
     * @var SettingsRepository
     */
    private $repository;

    private static $settings;

    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get(string $key)
    {
        $this->load();

        if (array_key_exists($key, self::$settings)) {
            return self::$settings[$key];
        }

        throw new InvalidSettingException($key);
    }

    public function getAll()
    {
        $this->load();

        return self::$settings;
    }

    private function load()
    {
        if (!self::$settings) {
            $settings = $this->repository
                    ->createQueryBuilder('c')
                    ->select('c.key', 'c.value')
                    ->orderBy('c.key')
                    ->getQuery()
                    ->getArrayResult();

            self::$settings = array_combine(ArrayUtil::column($settings, 'key'), ArrayUtil::column($settings, 'value', false));
        }
    }
}
