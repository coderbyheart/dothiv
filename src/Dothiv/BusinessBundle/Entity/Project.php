<?php

namespace Dothiv\BusinessBundle\Entity;

use Gedmo\Translatable\Translatable;
use Dothiv\BusinessBundle\Enum\ProjectStatus;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @Serializer\ExclusionPolicy("all")
 */
class Project extends Entity implements Translatable
{

    /**
     * short name
     *
     * @Gedmo\Translatable
     * @Assert\NotBlank
     * @ORM\Column(type="string",length=255)
     * @Serializer\Expose
     */
    protected $name;

    /**
     * Project status, as defined in enum 'ProjectStatus'
     * 
     * @ORM\Column(type="integer")
     * @Serializer\Expose
     */
    protected $status = ProjectStatus::DRAFT;
    
    /**
     * @Gedmo\Locale
     */
    private $locale;    

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        // define the allowed state transitions
        $transitions = array(
                ProjectStatus::DRAFT . "->" . ProjectStatus::SUBMITTED,
                ProjectStatus::DRAFT . "->" . ProjectStatus::CLOSED,
                ProjectStatus::SUBMITTED . "->" . ProjectStatus::DRAFT,
                ProjectStatus::SUBMITTED . "->" . ProjectStatus::CLOSED,
                ProjectStatus::SUBMITTED . "->" . ProjectStatus::ACCEPTED,
                ProjectStatus::ACCEPTED . "->" . ProjectStatus::PUBLISHED,
                ProjectStatus::PUBLISHED . "->" . ProjectStatus::FUNDED,
                ProjectStatus::PUBLISHED . "->" . ProjectStatus::FAILED,
                ProjectStatus::FUNDED . "->" . ProjectStatus::COMPLETED
                );
        
        // check for illegal state transition
        if ($this->status != $status && (array_search(($this->status . "->" . $status), $transitions) === false)) {
            // TODO: Gib Bad Request zurück
            throw new \InvalidArgumentException("Illegal state transition requested: $this->status -> $status");
        }
        
        $this->status = $status;
    }
    
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }    

}