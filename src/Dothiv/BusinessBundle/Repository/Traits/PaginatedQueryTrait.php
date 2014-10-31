<?php

namespace Dothiv\BusinessBundle\Repository\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Dothiv\BusinessBundle\Entity\EntityInterface;
use Dothiv\BusinessBundle\Repository\PaginatedQueryOptions;
use Dothiv\BusinessBundle\Repository\PaginatedResult;

trait PaginatedQueryTrait
{
    /**
     * Builds a paginated result.
     *
     * @param QueryBuilder          $qb
     * @param PaginatedQueryOptions $options
     *
     * @return PaginatedResult
     */
    protected function buildPaginatedResult(QueryBuilder $qb, PaginatedQueryOptions $options)
    {
        $sortField = $options->getSortField()->getOrElse('id');
        if (strpos($sortField, '.') === false) {
            $sortField = 'i.' . $sortField;
        }
        $statsQb = clone $qb;
        list(, $total, $minKey, $maxKey)
            = $statsQb->select(sprintf('COUNT(i), MAX(%s), MIN(%s)', $sortField, $sortField))
            ->getQuery()->getScalarResult()[0];
        $paginatedResult = new PaginatedResult(10, $total);
        $sortDir         = $options->getSortDir()->getOrElse('desc');
        if (strtolower($sortDir) == 'desc') {
            $qb->orderBy(sprintf('%s', $sortField), 'DESC');
            if ($options->getOffsetKey()->isDefined()) {
                $qb->andWhere(sprintf('%s < :offsetKey', $sortField))->setParameter('offsetKey', $options->getOffsetKey()->get());
            }
        } else { // forward
            $qb->orderBy(sprintf('%s', $sortField), 'ASC');
            if ($options->getOffsetKey()->isDefined()) {
                $qb->andWhere(sprintf('%s > :offsetKey', $sortField))->setParameter('offsetKey', $options->getOffsetKey()->get());
            }
        }
        $qb->setMaxResults($paginatedResult->getItemsPerPage());

        $items  = $qb
            ->getQuery()
            ->getResult();
        $result = new ArrayCollection($items);
        if ($result->count() == 0) {
            return $paginatedResult;
        }
        $paginatedResult->setResult($result);
        $offsetGetter = 'get' . ucfirst($options->getSortField()->getOrElse('id'));
        if ($result->count() == $paginatedResult->getItemsPerPage()) {
            $paginatedResult->setNextPageKey(function (EntityInterface $item) use ($maxKey, $offsetGetter) {
                $offsetValue = $item->$offsetGetter();
                return $offsetValue != $maxKey ? $offsetValue : null;
            });
        }
        if ($options->getOffsetKey()->isDefined()) {
            $paginatedResult->setPrevPageKey(function (EntityInterface $item) use ($minKey, $offsetGetter) {
                $offsetValue = $item->$offsetGetter();
                return $offsetValue != $minKey ? $offsetValue : null;
            });
        }

        return $paginatedResult;
    }
} 
