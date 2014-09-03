<?php

namespace Dothiv\AdminBundle\Transformer\Traits;

use Dothiv\AdminBundle\Transformer\UserTransformer;

trait UserTransformerTrait
{
    /**
     * @var UserTransformer
     */
    protected $userTransformer;

    /**
     * @param UserTransformer $userTransformer
     *
     * @return self
     */
    public function setUserTransformer(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
        return $this;
    }

    /**
     * @return UserTransformer
     */
    public function getUserTransformer()
    {
        return $this->userTransformer;
    }
} 
