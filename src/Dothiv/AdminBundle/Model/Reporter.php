<?php

namespace Dothiv\AdminBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\APIBundle\JsonLd\JsonLdEntityTrait;
use Dothiv\ValueObject\URLValue;

class Reporter implements JsonLdEntityInterface
{
    use JsonLdEntityTrait;

    /**
     * @var Report[]
     */
    protected $reports;

    /**
     * @var string
     */
    protected $title;

    public function __construct()
    {
        $this->setJsonLdContext(new URLValue('http://jsonld.click4life.hiv/Reporter'));
    }

    /**
     * @param Report[]|ArrayCollection $reports
     */
    public function setReports($reports)
    {
        $this->reports = $reports;
    }

    /**
     * @param string $title
     *
     * @return self;
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
