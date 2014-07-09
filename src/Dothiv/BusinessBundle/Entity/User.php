<?php

namespace Dothiv\BusinessBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertORM;

/**
 * @ORM\Entity(repositoryClass="Dothiv\BusinessBundle\Repository\UserRepository")
 * @AssertORM\UniqueEntity("email")
 * @AssertORM\UniqueEntity("handle")
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="email",columns={"email"}),@ORM\UniqueConstraint(name="handle",columns={"handle"})})
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    protected $handle;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Serializer\Expose
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    protected $email;

    /**
     * The token used to login.
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $token;

    /**
     * The lifetime of the token
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $tokenLifetime;

    /**
     * The bearer token for easier lookup
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $bearerToken;

    /**
     * First name
     *
     * @ORM\Column(type="string")
     * @Serializer\Expose
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * Last name
     *
     * @ORM\Column(type="string")
     * @Serializer\Expose
     * @Assert\NotBlank
     */
    protected $surname;

    /**
     * A list of domains owned by this user
     *
     * @ORM\OneToMany(targetEntity="Domain",mappedBy="owner")
     */
    protected $domains;

    public function __construct()
    {
        $this->domains = new ArrayCollection();
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection|Domain[]
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * Adds a new domain to this user. Ownership of the domain
     * will be transfered.
     *
     * @param Domain $newDomain
     */
    public function addDomain(Domain $newDomain)
    {
        // let the domain class take care of everything
        $newDomain->setOwner($this);
    }

    /**
     * Removes the given domain from the user, if it was previously
     * owned by him/her.
     *
     * @param Domain $domain
     */
    public function removeDomain(Domain $domain)
    {
        if ($this->domains->contains($domain))
            $domain->setOwner(null);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param string $token
     */
    public function setToken($token = null)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @ORM\PrePersist
     */
    public function updateBearerToken()
    {
        if (empty($this->token)) {
            $this->bearerToken = null;
        } else {
            $this->bearerToken = sha1($this->email . ':' . $this->token);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->bearerToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        // pass.
    }

    /**
     * @param string $handle
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return mixed
     */
    public function getBearerToken()
    {
        return $this->bearerToken;
    }

    /**
     * @return \DateTime
     */
    public function getTokenLifetime()
    {
        return $this->tokenLifetime;
    }

    /**
     * @param \DateTime $tokenLifetime
     */
    public function setTokenLifetime($tokenLifetime = null)
    {
        $this->tokenLifetime = $tokenLifetime instanceof \DateTime ? $tokenLifetime : ($tokenLifetime === null ? null : new \DateTime($tokenLifetime));
    }
}
