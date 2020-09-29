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

namespace SolidInvoice\UserBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use SolidInvoice\UserBundle\Entity\User;

/**
 * @codeCoverageIgnore
 */
class LoadData extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $user1 = (new User())
            ->setUsername('test1')
            ->setEmail('test1@test.com')
            ->setPassword('test1')
            ->setConfirmationToken(base64_encode(bin2hex(random_bytes(24))))
            ->setPasswordRequestedAt(new \DateTime());

        $user2 = (new User())
            ->setUsername('test2')
            ->setEmail('test2@test.com')
            ->setPassword('test2')
            ->setEnabled(true);

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();

        $this->setReference('user1', $user1);
        $this->setReference('user2', $user2);
    }
}
