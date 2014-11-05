<?php

namespace Dothiv\AdminBundle\Event;

use Dothiv\BusinessBundle\Entity\EntityChange;
use Symfony\Component\EventDispatcher\Event;

class EntityChangeEvent extends Event
{

    /**
     * @var EntityChange
     */
    private $change;

    /**
     * @param EntityChange $change
     */
    public function __construct(EntityChange $change)
    {
        $this->change = $change;
    }

    /**
     * @return EntityChange
     */
    public function getChange()
    {
        return $this->change;
    }

    /**
     * @param EntityChange $change
     *
     * @return self
     */
    public function setChange(EntityChange $change)
    {
        $this->change = $change;
        return $this;
    }
}
