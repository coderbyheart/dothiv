<?php

namespace Dothiv\AdminBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\AdminBundle\Stats\ReporterInterface;

interface StatsServiceInterface
{
    /**
     * @return ReporterInterface[]|ArrayCollection
     */
    public function getReporters();

    /**
     * Adds a report.
     *
     * @param string            $id
     * @param ReporterInterface $report
     *
     * @return self
     */
    public function addReporter($id, ReporterInterface $report);
}
