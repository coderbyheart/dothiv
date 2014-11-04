<?php

namespace Dothiv\AdminBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\APIBundle\JsonLd\JsonLdEntityTrait;
use Dothiv\ValueObject\EmailValue;
use Dothiv\ValueObject\IdentValue;
use Dothiv\ValueObject\URLValue;
use JMS\Serializer\Annotation as Serializer;

class EntityChangeModel implements JsonLdEntityInterface
{
    use JsonLdEntityTrait;

    /**
     * @var IdentValue
     */
    private $entity;

    /**
     * @var IdentValue
     */
    private $identifier;

    /**
     * @var EmailValue
     */
    private $author;

    /**
     * @var ArrayCollection
     */
    private $changes;

    public function __construct()
    {
        $this->setJsonLdContext(new URLValue('http://jsonld.click4life.hiv/EntityChange'));
    }

    /**
     * @return EmailValue
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param EmailValue $author
     *
     * @return self
     */
    public function setAuthor(EmailValue $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChanges()
    {
        return $this->changes;
    }

    /**
     * @param ArrayCollection $changes
     *
     * @return self
     */
    public function setChanges(ArrayCollection $changes)
    {
        $this->changes = $changes;
        return $this;
    }

    /**
     * @return IdentValue
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param IdentValue $entity
     *
     * @return self
     */
    public function setEntity(IdentValue $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return IdentValue
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param IdentValue $identifier
     *
     * @return self
     */
    public function setIdentifier(IdentValue $identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }
}
