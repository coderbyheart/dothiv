<?php

namespace Dothiv\AdminBundle\Model;

use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\APIBundle\JsonLd\JsonLdEntityTrait;
use Dothiv\ValueObject\EmailValue;
use Dothiv\ValueObject\HivDomainValue;
use Dothiv\ValueObject\URLValue;
use JMS\Serializer\Annotation as Serializer;

class RegistrarModel implements JsonLdEntityInterface
{
    use JsonLdEntityTrait;
    use Traits\W3CCreatedTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $notification;

    /**
     * @var string
     */
    protected $notificationType;

    /**
     * @var string
     */
    protected $extId;

    public function __construct()
    {
        $this->setJsonLdContext(new URLValue('http://jsonld.click4life.hiv/Registrar'));
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param bool $notification
     *
     * @return self
     */
    public function setNotification($notification)
    {
        $this->notification = (bool)$notification;
        return $this;
    }

    /**
     * @return bool
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param string $extId
     *
     * @return self
     */
    public function setExtId($extId)
    {
        $this->extId = $extId;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtId()
    {
        return $this->extId;
    }

    /**
     * @param string $notificationType
     *
     * @return self
     */
    public function setNotificationType($notificationType)
    {
        $this->notificationType = $notificationType;
        return $this;
    }

    /**
     * @return string
     */
    public function getNotificationType()
    {
        return $this->notificationType;
    }
}
