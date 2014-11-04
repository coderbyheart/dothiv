<?php

namespace Dothiv\AdminBundle\Model;

class ReportEvent
{

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var int
     */
    protected $count;

    /**
     * @param \DateTime $date
     * @param int       $count
     */
    public function __construct(\DateTime $date, $count)
    {
        $this->date  = $date;
        $this->count = (int)$count;
    }

    /**
     * @param int $count
     *
     * @return self
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param \DateTime $date
     *
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
