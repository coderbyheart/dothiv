<?php

namespace Dothiv\AdminBundle\Model;

use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\APIBundle\JsonLd\JsonLdEntityTrait;
use Dothiv\ValueObject\EmailValue;
use Dothiv\ValueObject\HivDomainValue;
use Dothiv\ValueObject\URLValue;
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
     * @var string
     */
    protected $token;

    /**
     * @var int
     */
    protected $clickCount;

    /**
     * @var RegistrarModel
     */
    protected $registrar;

    public function __construct()
    {
        $this->setJsonLdContext(new URLValue('http://jsonld.click4life.hiv/Domain'));
    }

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

    /**
     * @param string $token
     *
     * @return self
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param RegistrarModel $registrar
     *
     * @return self
     */
    public function setRegistrar(RegistrarModel $registrar)
    {
        $this->registrar = $registrar;
        return $this;
    }

    /**
     * @return RegistrarModel
     */
    public function getRegistrar()
    {
        return $this->registrar;
    }
}
