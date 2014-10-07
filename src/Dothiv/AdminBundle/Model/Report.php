<?php

namespace Dothiv\AdminBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\APIBundle\JsonLd\JsonLdEntityTrait;
use Dothiv\ValueObject\URLValue;
use JMS\Serializer\Annotation as Serializer;

class Report implements JsonLdEntityInterface
{
    use JsonLdEntityTrait;

    const RESOLUTION_TOTAL = 'total';

    /**
     * @var string
     */
    protected $resolution;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var ReportEvent[]|ArrayCollection
     */
    protected $events;

    public function __construct()
    {
        $this->setJsonLdContext(new URLValue('http://jsonld.click4life.hiv/Report'));
        $this->events = new ArrayCollection();
    }

    /**
     * @param ArrayCollection|ReportEvent[] $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
     * @return ArrayCollection|ReportEvent[]
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @return int
     * @Serializer\SerializedName("total")
     * @Serializer\Type("integer")
     * @Serializer\VirtualProperty
     */
    public function getTotal()
    {
        return array_reduce($this->getEvents()->toArray(), function ($total, ReportEvent $e) {
            return $total + $e->getCount();
        });
    }
}
