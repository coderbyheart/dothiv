<?php

namespace Dothiv\AdminBundle\Service;


use Dothiv\BusinessBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserService extends \Dothiv\BusinessBundle\Service\UserService
{
    /**
     * @var string
     */
    private $domain;

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        if (!preg_match('/' . preg_quote($this->getDomain()) . '$/', $username)) {
            throw new UsernameNotFoundException($username);
        }
        try {
            $user = parent::loadUserByUsername($username);
        } catch (UsernameNotFoundException $e) {
            $user = new User();
            $user->setEmail($username);
            $user->setHandle($this->generateToken());
            $user->setFirstname('');
            $user->setSurname('');
            $this->userRepo->persist($user)->flush();
        }
        $user->setRoles(array('ROLE_USER', 'ROLE_ADMIN'));
        return $user;
    }

    /**
     * @param string $domain
     *
     * @return self
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }
} 
