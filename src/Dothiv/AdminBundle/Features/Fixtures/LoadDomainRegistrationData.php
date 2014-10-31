<?php

namespace Dothiv\AdminBundle\Features\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dothiv\BusinessBundle\Entity\Domain;
use Dothiv\BusinessBundle\Entity\Registrar;

class LoadDomainRegistrationData implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $registrar1 = new Registrar();
        $registrar1->setExtId("1061-EM");
        $registrar1->setName("Example Registrar");
        $manager->persist($registrar1);

        $registrar2 = new Registrar();
        $registrar2->setExtId("1062-EM");
        $registrar2->setName("Example Registrar 2");
        $manager->persist($registrar2);

        $domainA = new Domain();
        $domainA->setName("bcme.hiv");
        $domainA->setOwnerName("Domain Administrator");
        $domainA->setOwnerEmail("domain@bcme.com");
        $domainA->setRegistrar($registrar1);
        $domainA->setToken('domaintokenb');
        $domainA->setTokenSent(new \DateTime());
        $manager->persist($domainA);

        $domainB = new Domain();
        $domainB->setName("acme.hiv");
        $domainB->setOwnerName("Domain Administrator");
        $domainB->setOwnerEmail("ccops@acme.com");
        $domainB->setRegistrar($registrar2);
        $manager->persist($domainB);

        $manager->flush();
    }
}
