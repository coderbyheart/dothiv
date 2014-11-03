<?php

namespace Dothiv\AdminBundle\Features\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dothiv\BusinessBundle\Entity\Config;
use Dothiv\BusinessBundle\Entity\Domain;
use Dothiv\BusinessBundle\Entity\Registrar;

class LoadConfigData implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $configA = new Config();
        $configA->setName('a_key');
        $configA->setValue('1.25');
        $manager->persist($configA);

        $configB = new Config();
        $configB->setName('b_key');
        $configB->setValue('some value');
        $manager->persist($configB);
        
        $manager->flush();
    }
}
