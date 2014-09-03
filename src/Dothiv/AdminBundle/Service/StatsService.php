<?php

namespace Dothiv\AdminBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\AdminBundle\Exception\InvalidArgumentException;
use Dothiv\AdminBundle\Stats\ReporterInterface;

class StatsService implements StatsServiceInterface
{
    /**
     * @var ArrayCollection|ReporterInterface[]
     */
    private $reporters;

    public function __construct()
    {
        $this->reporters = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|ReporterInterface[]
     */
    public function getReporters()
    {
        return $this->reporters;
    }

    /**
     * {@inheritdoc}
     */
    public function addReporter($id, ReporterInterface $report)
    {
        if ($this->reporters->containsKey($id)) {
            throw new InvalidArgumentException(sprintf('A report with the id "%s" is already registered.', $id));
        }
        $this->reporters->set($id, $report);
        return $this;
    }
}
