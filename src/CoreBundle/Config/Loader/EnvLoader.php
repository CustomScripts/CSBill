<?php

declare(strict_types=1);

/*
 * This file is part of SolidInvoice project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SolidInvoice\CoreBundle\Config\Loader;

use Symfony\Component\DependencyInjection\EnvVarLoaderInterface;

final class EnvLoader implements EnvVarLoaderInterface
{
    /**
     * @var string
     */
    private $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function loadEnvVars(): array
    {
        $envFile = $this->projectDir.'/app/config/env.php';

        if (!\file_exists($envFile)) {
            return [];
        }

        return require $envFile;
    }
}
