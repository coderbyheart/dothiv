<?php

namespace Dothiv\BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Represents a config setting.
 *
 * @ORM\Entity(repositoryClass="Dothiv\BusinessBundle\Repository\ConfigRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Config
{
    use Traits\CreateUpdateTime;

    /**
     * Name of the config setting.
     *
     * @ORM\Id
     * @ORM\Column(type="string",length=255,nullable=false)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Assert\Length(max=255)
     * @var string
     */
    protected $name;

    /**
     * Value of the config setting.
     *
     * @ORM\Column(type="text",nullable=false)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @var string
     */
    protected $value;

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

} 
