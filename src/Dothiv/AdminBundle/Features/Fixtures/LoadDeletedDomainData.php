<?php

namespace Dothiv\AdminBundle\Features\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dothiv\BusinessBundle\Entity\DeletedDomain;
use Dothiv\ValueObject\HivDomainValue;

class LoadDeletedDomainData implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $deletes = [
            ['barbie.hiv', '2014-08-29 12:04:38'],
            ['qs-swh-nl-0914-135502-17.hiv', '2014-09-15 18:20:13'],
            ['qs-swh-nl-0914-092440-19.hiv', '2014-09-15 18:21:43'],
            ['qs-swh-nl-0914-092507-17.hiv', '2014-09-15 18:21:53'],
            ['red2.hiv', '2014-10-01 07:13:23'],
            ['qs-swh-es-1014-105702-1.hiv', '2014-10-09 18:32:00'],
            ['tg-testet-promo2.hiv', '2014-10-15 13:19:07'],
            ['qs-swh-es-1014-071437-1.hiv', '2014-10-23 22:37:34'],
            ['qs-swh-es-1114-083205-8.hiv', '2014-11-04 19:32:47'],
            ['qs-swh-es-1114-083001-8.hiv', '2014-11-04 19:32:52'],
            ['hahayouhave.hiv', '2014-11-19 07:46:52'],
            ['domains-registration.hiv', '2014-12-29 10:32:08'],
            ['ott.hiv', '2015-01-07 09:35:59'],
            ['navinum.hiv', '2015-01-23 14:04:06'],
        ];
        foreach ($deletes as $data) {
            list($name, $date) = $data;
            $deletedDomain = new DeletedDomain(new HivDomainValue($name));
            $deletedDomain->setCreated(new \DateTime($date));
            $manager->persist($deletedDomain);
        }
        $manager->flush();
    }
}
