<?php

namespace Dothiv\AdminBundle\Transformer\Traits;

use Dothiv\AdminBundle\Transformer\RegistrarTransformer;

trait RegistrarTransformerTrait
{
    /**
     * @var RegistrarTransformer
     */
    protected $registrarTransformer;

    /**
     * @param RegistrarTransformer $registrarTransformer
     *
     * @return self
     */
    public function setRegistrarTransformer(RegistrarTransformer $registrarTransformer)
    {
        $this->registrarTransformer = $registrarTransformer;
        return $this;
    }

    /**
     * @return RegistrarTransformer
     */
    public function getRegistrarTransformer()
    {
        return $this->registrarTransformer;
    }
} 
