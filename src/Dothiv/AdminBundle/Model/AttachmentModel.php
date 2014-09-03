<?php

namespace Dothiv\AdminBundle\Model;

use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\APIBundle\JsonLd\JsonLdEntityTrait;
use Dothiv\BusinessBundle\ValueObject\EmailValue;
use Dothiv\BusinessBundle\ValueObject\HivDomainValue;
use Dothiv\BusinessBundle\ValueObject\URLValue;
use Dothiv\BusinessBundle\ValueObject\W3CDateTimeValue;
use JMS\Serializer\Annotation as Serializer;

class AttachmentModel implements JsonLdEntityInterface
{
    use JsonLdEntityTrait;
    use W3CCreatedTrait;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var URLValue
     */
    protected $url;

    public function __construct()
    {
        $this->setJsonLdContext(new URLValue('http://jsonld.click4life.hiv/Attachment'));
    }

    /**
     * @param string $mimeType
     *
     * @return self
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param URLValue $url
     *
     * @return self
     */
    public function setUrl(URLValue $url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return URLValue
     */
    public function getUrl()
    {
        return $this->url;
    }
}
