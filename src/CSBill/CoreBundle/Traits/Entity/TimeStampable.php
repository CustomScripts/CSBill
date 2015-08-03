<?php

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2015 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\CoreBundle\Traits\Entity;

use APY\DataGridBundle\Grid\Mapping as GRID;

trait TimeStampable
{
    /**
     * @var \DateTIme
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var \DateTIme
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     * @GRID\Column(visible=false)
     */
    protected $updated;

    /**
     * Sets created.
     *
     * @param \DateTime $created
     *
     * @return $this
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Returns created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Sets updated.
     *
     * @param \DateTime $updated
     *
     * @return $this
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Returns updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
