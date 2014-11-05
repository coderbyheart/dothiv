<?php

namespace Dothiv\AdminBundle\Transformer\Traits;

use Symfony\Component\Security\Core\SecurityContext;

trait SecurityContextTrait
{

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @param SecurityContext $securityContext
     *
     * @return self
     */
    public function setSecurityContext(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
        return $this;
    }

    /**
     * @return SecurityContext
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }
}
