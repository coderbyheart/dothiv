<?php

namespace Dothiv\AdminBundle\Stats;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\AdminBundle\Model\Report;
use Dothiv\AdminBundle\Model\ReportEvent;

interface ReporterInterface
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return Report[]|ArrayCollection
     */
    public function getReports();

    /**
     * @param string $id
     *
     * @return ReportEvent[]|ArrayCollection
     */
    public function getEvents($id);
}
