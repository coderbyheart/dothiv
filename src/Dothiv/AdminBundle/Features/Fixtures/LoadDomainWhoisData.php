<?php

namespace Dothiv\AdminBundle\Features\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dothiv\BusinessBundle\Entity\DomainWhois;
use Dothiv\BusinessBundle\Service\WhoisReportParser;
use Dothiv\ValueObject\HivDomainValue;

class LoadDomainWhoisData implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $whoisParser = new WhoisReportParser();
        $report      = $whoisParser->parse(file_get_contents(__DIR__ . '/../../../BusinessBundle/Tests/Service/data/tld.hiv.whois'));
        $domainWhois = DomainWhois::create(new HivDomainValue('example.hiv'), $report);
        $manager->persist($domainWhois);
        $manager->flush();
    }
}
