<?php

namespace Dothiv\AdminBundle\Features\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dothiv\BusinessBundle\Entity\Banner;
use Dothiv\BusinessBundle\Entity\Domain;
use Dothiv\BusinessBundle\Entity\Registrar;
use Dothiv\ValueObject\HivDomainValue;

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
        $domainA->setNonprofit(true);
        $manager->persist($domainA);

        $domainB = new Domain();
        $domainB->setName("acme.hiv");
        $domainB->setOwnerName("Domain Administrator");
        $domainB->setOwnerEmail("ccops@acme.com");
        $domainB->setRegistrar($registrar2);
        $domainB->transfer();
        $manager->persist($domainB);

        // Add a domain with clicks
        $banner = new Banner();
        $manager->persist($banner);
        $domainWithClickCounterAndClicks = new Domain();
        $domainWithClickCounterAndClicks->setName('domain-with-clickcounter.hiv');
        $domainWithClickCounterAndClicks->setOwnerName("Domain Administrator");
        $domainWithClickCounterAndClicks->setOwnerEmail("ccops@acme.com");
        $domainWithClickCounterAndClicks->setRegistrar($registrar1);
        $domainWithClickCounterAndClicks->setActiveBanner($banner);
        $domainWithClickCounterAndClicks->setClickcount(10);
        $manager->persist($domainWithClickCounterAndClicks);

        // Add a domain with clicks
        $banner2 = new Banner();
        $manager->persist($banner2);
        $domainWithClickCounterWithoutClicks = new Domain();
        $domainWithClickCounterWithoutClicks->setName('domain-with-clickcounter-but-no-clicks.hiv');
        $domainWithClickCounterWithoutClicks->setOwnerName("Domain Administrator");
        $domainWithClickCounterWithoutClicks->setOwnerEmail("ccops@acme.com");
        $domainWithClickCounterWithoutClicks->setRegistrar($registrar1);
        $domainWithClickCounterWithoutClicks->setActiveBanner($banner2);
        $manager->persist($domainWithClickCounterWithoutClicks);

        // Add an IDN domain
        $idnDOmain = new Domain();
        $idnDOmain->setName(HivDomainValue::createFromUTF8('zühlke.hiv')->toScalar());
        $idnDOmain->setOwnerName("Domain Administrator");
        $idnDOmain->setOwnerEmail("ccops@acme.com");
        $idnDOmain->setRegistrar($registrar1);
        $manager->persist($idnDOmain);

        $manager->flush();
    }
}
