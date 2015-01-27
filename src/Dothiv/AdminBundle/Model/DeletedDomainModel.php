<?php

namespace Dothiv\AdminBundle\Model;

use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\APIBundle\JsonLd\JsonLdEntityTrait;
use Dothiv\APIBundle\Model\Traits;
use Dothiv\ValueObject\HivDomainValue;
use Dothiv\ValueObject\URLValue;
use JMS\Serializer\Annotation as Serializer;

class DeletedDomainModel implements JsonLdEntityInterface
{
    use JsonLdEntityTrait;
    use Traits\W3CCreatedTrait;

    /**
     * @var HivDomainValue
     */
    protected $domain;

    /**
     * @var string
     */
    protected $domainUTF8;

    public function __construct(HivDomainValue $domain)
    {
        $this->setJsonLdContext(new URLValue('http://jsonld.click4life.hiv/DeletedDomain'));
        $this->domain     = $domain;
        $this->domainUTF8 = $domain->toUTF8();
    }

}
