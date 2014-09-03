<?php

namespace Dothiv\AdminBundle\Model;

use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\APIBundle\JsonLd\JsonLdEntityTrait;
use Dothiv\BusinessBundle\ValueObject\EmailValue;
use Dothiv\BusinessBundle\ValueObject\HivDomainValue;
use JMS\Serializer\Annotation as Serializer;

class DomainModel implements JsonLdEntityInterface
{
    use JsonLdEntityTrait;
    use W3CCreatedTrait;

    /**
     * @var HivDomainValue
     */
    protected $domain;

    /**
     * @var string
     */
    protected $domainUTF8;

    /**
     * @var string
     */
    protected $ownerName;

    /**
     * @var EmailValue
     */
    protected $ownerEmail;

    /**
     * @var bool
     */
    protected $clickCounterConfigured;

    /**
     * @var bool
     */
    protected $tokenSent;

    /**
     * @var int
     */
    protected $clickCount;

    /**
     * @param HivDomainValue $domain
     *
     * @return self
     */
    public function setDomain(HivDomainValue $domain)
    {
        $this->domain     = $domain;
        $this->domainUTF8 = $domain->toUTF8();
        return $this;
    }

    /**
     * @return HivDomainValue
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param EmailValue $ownerEmail
     *
     * @return self
     */
    public function setOwnerEmail(EmailValue $ownerEmail)
    {
        $this->ownerEmail = $ownerEmail;
        return $this;
    }

    /**
     * @return EmailValue
     */
    public function getOwnerEmail()
    {
        return $this->ownerEmail;
    }

    /**
     * @param string $ownerName
     *
     * @return self
     */
    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * @param boolean $clickCounterConfigured
     *
     * @return self
     */
    public function setClickCounterConfigured($clickCounterConfigured)
    {
        $this->clickCounterConfigured = (bool)$clickCounterConfigured;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getClickCounterConfigured()
    {
        return $this->clickCounterConfigured;
    }

    /**
     * @param boolean $tokenSent
     *
     * @return self
     */
    public function setTokenSent($tokenSent)
    {
        $this->tokenSent = (bool)$tokenSent;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getTokenSent()
    {
        return $this->tokenSent;
    }

    /**
     * @param int $clickCount
     *
     * @return self
     */
    public function setClickCount($clickCount)
    {
        $this->clickCount = (int)$clickCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getClickCount()
    {
        return $this->clickCount;
    }

}
