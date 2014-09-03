<?php

namespace Dothiv\AdminBundle\Model;

use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\APIBundle\JsonLd\JsonLdEntityTrait;
use Dothiv\BusinessBundle\ValueObject\EmailValue;
use Dothiv\BusinessBundle\ValueObject\HivDomainValue;
use Dothiv\BusinessBundle\ValueObject\URLValue;
use JMS\Serializer\Annotation as Serializer;

class NonProfitRegistrationModel implements JsonLdEntityInterface
{
    use JsonLdEntityTrait;
    use W3CCreatedTrait;

    /**
     * @var HivDomainValue
     */
    protected $domain;

    /**
     * @var string
     */
    protected $domainUTF8;

    /**
     * @var UserModel
     */
    protected $user;

    /**
     * @var string
     */
    protected $personFirstname;

    /**
     * @var string
     */
    protected $personSurname;

    /**
     * @var EmailValue
     */
    protected $personEmail;

    /**
     * @var string
     */
    protected $personPhone;

    /**
     * @var string
     */
    protected $personFax;

    /**
     * @var string
     */
    protected $organization;

    /**
     * @var string
     */
    protected $orgPhone;

    /**
     * @var string
     */
    protected $orgFax;

    /**
     * The attached proof
     *
     * @var AttachmentModel
     */
    protected $proof;

    /**
     * @var string
     */
    protected $about;

    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $postcode;

    /**
     * @var string
     */
    protected $locality;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var URLValue
     */
    protected $website;

    /**
     * @var bool
     */
    protected $forward;

    public function __construct()
    {
        $this->setJsonLdContext(new URLValue('http://jsonld.click4life.hiv/NonProfitRegistration'));
    }

    /**
     * @param HivDomainValue $domain
     *
     * @return self
     */
    public function setDomain(HivDomainValue $domain)
    {
        $this->domain = $domain;
        $this->domainUTF8 = $domain->toUTF8();
        return $this;
    }

    /**
     * @return HivDomainValue
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $about
     *
     * @return self
     */
    public function setAbout($about)
    {
        $this->about = $about;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param string $country
     *
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $field
     *
     * @return self
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param boolean $forward
     *
     * @return self
     */
    public function setForward($forward)
    {
        $this->forward = $forward;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getForward()
    {
        return $this->forward;
    }

    /**
     * @param string $locality
     *
     * @return self
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param string $orgFax
     *
     * @return self
     */
    public function setOrgFax($orgFax)
    {
        $this->orgFax = $orgFax;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrgFax()
    {
        return $this->orgFax;
    }

    /**
     * @param string $orgPhone
     *
     * @return self
     */
    public function setOrgPhone($orgPhone)
    {
        $this->orgPhone = $orgPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrgPhone()
    {
        return $this->orgPhone;
    }

    /**
     * @param string $organization
     *
     * @return self
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param EmailValue $personEmail
     *
     * @return self
     */
    public function setPersonEmail(EmailValue $personEmail)
    {
        $this->personEmail = $personEmail;
        return $this;
    }

    /**
     * @return  EmailValue
     */
    public function getPersonEmail()
    {
        return $this->personEmail;
    }

    /**
     * @param string $personFax
     *
     * @return self
     */
    public function setPersonFax($personFax)
    {
        $this->personFax = $personFax;
        return $this;
    }

    /**
     * @return string
     */
    public function getPersonFax()
    {
        return $this->personFax;
    }

    /**
     * @param string $personFirstname
     *
     * @return self
     */
    public function setPersonFirstname($personFirstname)
    {
        $this->personFirstname = $personFirstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getPersonFirstname()
    {
        return $this->personFirstname;
    }

    /**
     * @param string $personPhone
     *
     * @return self
     */
    public function setPersonPhone($personPhone)
    {
        $this->personPhone = $personPhone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPersonPhone()
    {
        return $this->personPhone;
    }

    /**
     * @param string $personSurname
     *
     * @return self
     */
    public function setPersonSurname($personSurname)
    {
        $this->personSurname = $personSurname;
        return $this;
    }

    /**
     * @return string
     */
    public function getPersonSurname()
    {
        return $this->personSurname;
    }

    /**
     * @param string $postcode
     *
     * @return self
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param AttachmentModel $proof
     *
     * @return self
     */
    public function setProof(AttachmentModel $proof)
    {
        $this->proof = $proof;
        return $this;
    }

    /**
     * @return AttachmentModel
     */
    public function getProof()
    {
        return $this->proof;
    }

    /**
     * @param UserModel $user
     *
     * @return self
     */
    public function setUser(UserModel $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return UserModel
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param URLValue $website
     *
     * @return self
     */
    public function setWebsite(URLValue $website)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * @return  URLValue
     */
    public function getWebsite()
    {
        return $this->website;
    }
}
