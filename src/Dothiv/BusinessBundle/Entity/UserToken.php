<?php

namespace Dothiv\BusinessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * Represents a user's login token
 *
 * @ORM\Entity(repositoryClass="Dothiv\BusinessBundle\Repository\UserTokenRepository")
 * @ORM\Table(
 *      uniqueConstraints={@ORM\UniqueConstraint(name="usertoken__user_token",columns={"user_id", "token"}), @ORM\UniqueConstraint(name="usertoken__bearerToken",columns={"bearerToken"})},
 *      indexes={…} TODO
 * )
 *
 * @Serializer\ExclusionPolicy("all")
 *
 * @ORM\HasLifecycleCallbacks
 */
class UserToken extends Entity
{
    /**
     * The domain that displays this banner
     *
     * @ORM\ManyToOne(targetEntity="User",inversedBy="tokens")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @var User
     */
    protected $user;

    /**
     * The token used to login.
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $token;

    /**
     * Scope of the token.
     *
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $scope;

    /**
     * The lifetime of the token
     *
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $lifeTime;

    /**
     * The time of revokation
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $revokedTime;

    /**
     * The bearer token for easier lookup
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $bearerToken;

    /**
     * @param string $token
     */
    public function setToken($token)
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
        $this->bearerToken = sha1($this->user->getEmail() . ':' . $this->token);
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
    public function getLifeTime()
    {
        return $this->lifeTime;
    }

    /**
     * @param \DateTime $lifeTime
     */
    public function setLifeTime(\DateTime $lifeTime)
    {
        $this->lifeTime = $lifeTime;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function revoke(\DateTime $revokeTime)
    {
        $this->revokedTime = $revokeTime;
    }

    /**
     * @return bool
     */
    public function isRevoked()
    {
        return !empty($this->revokedTime);
    }

    /**
     * @param \DateTime $revokedTime
     */
    public function setRevokedTime(\DateTime $revokedTime)
    {
        $this->revokedTime = $revokedTime;
    }

    /**
     * @return \DateTime
     */
    public function getRevokedTime()
    {
        return $this->revokedTime;
    }

    /**
     * @param string $scope
     * @return self
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }


}
