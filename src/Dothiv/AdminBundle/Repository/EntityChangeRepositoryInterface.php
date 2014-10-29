<?php

namespace Dothiv\AdminBundle\Repository;

use Dothiv\AdminBundle\Entity\EntityChange;
use Dothiv\BusinessBundle\Repository\PaginatedResult;
use Dothiv\ValueObject\IdentValue;

interface EntityChangeRepositoryInterface
{
    /**
     * @param EntityChange $change
     *
     * @return self
     */
    public function persist(EntityChange $change);

    /**
     * @return self
     */
    public function flush();

    /**
     * Creates a paginated result of changes for the entity of type $entity with identifier $identifier
     *
     * @param            $entity
     * @param IdentValue $identifier
     * @param mixed|null $offsetKey
     * @param mixed|null $offsetDir
     *
     * @return PaginatedResult
     */
    public function getPaginated($entity, IdentValue $identifier, $offsetKey = null, $offsetDir = null);
} 
