<?php

namespace DotHiv\BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use LogicException;

/**
 * The dotHIV banner to be shown on websites
 * 
 * @ORM\Entity
 * @Serializer\ExclusionPolicy("all")
 * 
 * @author Benedikt Budig <bb@dothiv.org>
 */
class Banner extends Entity {

    /**
     * Domain to redirect to, if desired
     *
     * @ORM\Column(type="string",nullable=true)
     * @Serializer\Expose
     */
    protected $redirectDomain;

    /**
     * Language of banner texts
     *
     * @ORM\Column(type="string",nullable=true)
     * @Serializer\Expose
     */
    protected $language;

    /**
     * Position for banner placement on website
     *
     * @ORM\Column(type="string",nullable=true)
     * @Serializer\Expose
     */
    protected $position;

    /**
     * Alternative position for banner placement on website, used for returning
     * visitors
     *
     * @ORM\Column(type="string",nullable=true)
     * @Serializer\Expose
     */
    protected $positionAlternative;

    /**
     * The domain that displays this banner
     *
     * @ORM\ManyToOne(targetEntity="Domain",inversedBy="banners")
     * @Serializer\Expose
     */
    protected $domain;

    /**
     * Returns the FQDN of the redirect domain.
     *
     * @return string FQDN for redirect
     */
    public function getRedirectDomain() {
        return $this->redirectDomain;
    }

    /**
     * Sets the FQDN of the redirect domain.
     *
     * @param string $redirectDomain FQDN for redirect
     */
    public function setRedirectDomain($redirectDomain) {
        $this->redirectDomain = $redirectDomain;
    }

    /**
     * Returns the banner language.
     *
     * @return string language
     */
    public function getLanguage() {
        return $this->language;
    }

    /**
     * Sets the banner language.
     *
     * @param string $language
     */
    public function setLanguage($language) {
        $this->language = $language;
    }

    /**
     * Returns the display position.
     *
     * @return string display position
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * Sets the display position.
     *
     * @param string $position
     */
    public function setPosition($position) {
        $this->position = $position;
    }

    /**
     * Returns the alternative display position.
     *
     * @return string alternative display position
     */
    public function getPositionAlternative() {
        return $this->positionAlternative;
    }

    /**
     * Sets the alternative display position.
     *
     * @param string $positionAlternative alternative display position
     */
    public function setPositionAlternative($positionAlternative) {
        $this->positionAlternative = $positionAlternative;
    }

    /**
     * Return the domain that displays this banner.
     *
     * @return Domain domain that displays the banner
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * Sets the domain of this banner (if previously not assigned),
     * transfers the banner to new domain (if previously assigned),
     * removes any possible domain association (if called with 'NULL').
     *
     * @param Domain $newDomain
     */
    public function setDomain($newDomain = null) {
        // remove this banner from current domain's list, if any
        if ($this->domain !== null) {
            $this->domain->getDomains()->removeElement($this);
            if ($this->domain->getActiveBanner() === $this)
                $this->domain->setActiveBanner(null);
        }
        // set new domain
        $this->domain = $newDomain;

        // add this domain to new domain's banners, if new domain exists
        if ($newDomain !== null)
            $newDomain->getBanners()->add($this);
    }

    /**
     * Activates this banner for the associated domain. The previously 
     * activated banner will be deactivated (if any).
     */
    public function activate() {
        if ($this->domain === null)
            throw new LogicException('Banner cannot be activated with no domain associated');
        $this->domain->setActiveBanner($this);
    }
}