<?php

namespace Dothiv\AdminBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\APIBundle\JsonLd\JsonLdEntityTrait;
use Dothiv\APIBundle\Model\Traits;
use Dothiv\ValueObject\HivDomainValue;
use Dothiv\ValueObject\URLValue;
use JMS\Serializer\Annotation as Serializer;

class DomainWhoisModel implements JsonLdEntityInterface
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

    /**
     * @var ArrayCollection
     * @Serializer\Accessor(getter="whoisToArray")
     */
    protected $whois;

    public function __construct(HivDomainValue $domain, ArrayCollection $report)
    {
        $this->setJsonLdContext(new URLValue('http://jsonld.click4life.hiv/DomainWhois'));
        $this->domain     = $domain;
        $this->domainUTF8 = $domain->toUTF8();
        $this->whois      = $report;
    }

    /**
     * @return array
     */
    public function whoisToArray()
    {
        $whois = [
            'Domain'     => [],
            'Registrant' => [],
            'Admin'      => [],
            'Tech'       => [],
        ];
        foreach ($this->whois->toArray() as $k => $v) {
            $prefix    = $k;
            $remainder = null;
            if (stristr($k, ' ')) {
                list($prefix, $remainder) = explode(' ', $k, 2);
            }
            if (isset($whois[$prefix])) {
                $whois[$prefix][preg_replace('/[^A-Z]/i', '_', $remainder)] = $v;
            } else {
                $whois[preg_replace('/[^A-Z]/i', '_', $k)] = $v;
            }

        }
        return $whois;
    }
}
