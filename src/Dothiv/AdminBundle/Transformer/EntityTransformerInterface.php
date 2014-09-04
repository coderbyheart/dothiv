<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\APIBundle\JsonLd\JsonLdEntityInterface;
use Dothiv\BusinessBundle\Entity\Entity;

interface EntityTransformerInterface
{
    /**
     * @param Entity  $entity
     * @param string  $route
     * @param boolean $listing
     *
     * @return JsonLdEntityInterface
     */
    public function transform(Entity $entity, $route = null, $listing = false);
} 
