<?php

namespace Dothiv\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Dothiv\AdminBundle\Entity\EntityChange;
use Dothiv\BusinessBundle\Repository\PaginatedResult;
use Dothiv\BusinessBundle\Repository\Traits;
use Dothiv\ValueObject\IdentValue;
use PhpOption\Option;

class EntityChangeRepository extends EntityRepository implements EntityChangeRepositoryInterface
{
    use Traits\ValidatorTrait;
    use Traits\PaginatedQueryTrait;

    /**
     * @param EntityChange $change
     *
     * @return self
     */
    public function persist(EntityChange $change)
    {
        $this->getEntityManager()->persist($this->validate($change));
        return $this;
    }

    /**
     * @return self
     */
    public function flush()
    {
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaginated($entity, IdentValue $identifier, $offsetKey = null, $offsetDir = null)
    {
        $qb = $this->createQueryBuilder('i');
        $qb->andWhere('i.entity = :entity')->setParameter('entity', $entity);
        $qb->andWhere('i.identifier = :identifier')->setParameter('identifier', $identifier->toScalar());
        return $this->buildPaginatedResult($qb, $offsetKey);
    }

} 
