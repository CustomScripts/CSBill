<?php

declare(strict_types=1);
/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\UserBundle\Entity;

use CSBill\CoreBundle\Traits\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="CSBill\UserBundle\Repository\UserRepository")
 * @Gedmo\Loggable()
 */
class User extends BaseUser
{
    use Entity\TimeStampable,
        Entity\SoftDeleteable;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", nullable=true)
     */
    protected $mobile;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ApiToken", mappedBy="user", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     */
    private $apiTokens;

    /**
     * Don't return the salt, and rely on password_hash to generate a salt.
     */
    public function getSalt()
    {
        return;
    }

    /**
     * @return ArrayCollection
     */
    public function getApiTokens(): ArrayCollection
    {
        return $this->apiTokens;
    }

    /**
     * @param ArrayCollection $apiTokens
     *
     * @return User
     */
    public function setApiTokens(\Doctrine\Common\Collections\ArrayCollection $apiTokens): User
    {
        $this->apiTokens = $apiTokens;

        return $this;
    }

    /**
     * @return string
     */
    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     *
     * @return User
     */
    public function setMobile(string $mobile): User
    {
        $this->mobile = $mobile;

        return $this;
    }
}
