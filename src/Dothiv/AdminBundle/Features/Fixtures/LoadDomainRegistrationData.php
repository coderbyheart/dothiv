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
        $registrar = new Registrar();
        $registrar->setExtId("1061-EM");
        $registrar->setName("Example Registrar");
        $manager->persist($registrar);

        $domainA = new Domain();
        $domainA->setName("bcme.hiv");
        $domainA->setOwnerName("Domain Administrator");
        $domainA->setOwnerEmail("domain@bcme.com");
        $domainA->setRegistrar($registrar);
        $domainA->setToken('domaintokenb');
        $domainA->setTokenSent(new \DateTime());
        $manager->persist($domainA);

        $domainB = new Domain();
        $domainB->setName("acme.hiv");
        $domainB->setOwnerName("Domain Administrator");
        $domainB->setOwnerEmail("ccops@acme.com");
        $domainB->setRegistrar($registrar);
        $manager->persist($domainB);

        $manager->flush();
    }
}
