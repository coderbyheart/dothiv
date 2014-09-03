<?php

namespace Dothiv\AdminBundle\Features\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dothiv\BusinessBundle\Entity\Attachment;
use Dothiv\BusinessBundle\Entity\NonProfitRegistration;
use Dothiv\BusinessBundle\Entity\Registrar;
use Dothiv\BusinessBundle\Entity\User;

class LoadNonProfitRegistrationData implements FixtureInterface
{
    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setHandle('userhandle');
        $user->setEmail('someone@example.com');
        $user->setFirstname('John');
        $user->setSurname('Doe');
        $manager->persist($user);

        $proof = new Attachment();
        $proof->setUser($user);
        $proof->setHandle('ad54af9f3a2e137d04588712e3d98e0d');
        $proof->setMimeType('application/pdf');
        $proof->setExtension('pdf');
        $manager->persist($proof);

        for ($i = 1; $i <= 50; $i++) {
            $npr = new NonProfitRegistration();
            $npr->setUser($user);
            $npr->setDomain(sprintf('example-%d.hiv', $i));
            $npr->setPersonFirstname('Jill');
            $npr->setPersonSurname('Jones');
            $npr->setPersonEmail('jill@example.com');
            $npr->setOrganization('ACME Inc.');
            $npr->setProof($proof);
            $npr->setAbout('ACME Stuff');
            $npr->setField('prevention');
            $npr->setPostcode('12345');
            $npr->setLocality('Big City');
            $npr->setCountry('United States');
            $npr->setWebsite('http://example.com/');
            $npr->setForward('1');
            $npr->setPersonPhone('+49178451');
            $npr->setPersonFax('+49178452');
            $npr->setOrgPhone('+49178453');
            $npr->setOrgFax('+49178454');
            $manager->persist($npr);
        }
        $manager->flush();
    }
}
