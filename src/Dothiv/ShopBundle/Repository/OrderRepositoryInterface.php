<?php

namespace Dothiv\ShopBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\ShopBundle\Entity\Order;
use Dothiv\BusinessBundle\Repository\CRUD;
use Dothiv\ShopBundle\Exception\EntityNotFoundException;
use Dothiv\ValueObject\HivDomainValue;
use PhpOption\Option;

interface OrderRepositoryInterface extends CRUD\CreateEntityRepositoryInterface
{
    /**
     * Persist the entity.
     *
     * @param Order $Order
     *
     * @return self
     */
    public function persist(Order $Order);

    /**
     * Flush the entity manager.
     *
     * @return self
     */
    public function flush();

    /**
     * @param HivDomainValue $domain
     *
     * @return Option of Order
     */
    public function findLatestByDomain(HivDomainValue $domain);

    /**
     * Returns orders which have not been processed.
     *
     * @return ArrayCollection|Order[]
     */
    function findNew();

    /**
     * @param int $id
     *
     * @return Order
     *
     * @throws EntityNotFoundException if order is not found.
     */
    public function getById($id);
}
