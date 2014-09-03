<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\AdminBundle\Model\PaginatedList;
use Dothiv\BusinessBundle\Repository\PaginatedResult;
use Dothiv\BusinessBundle\ValueObject\URLValue;
use Symfony\Component\Routing\RouterInterface;

class PaginatedListTransformer extends AbstractTransformer
{
    /**
     * @param PaginatedResult $result
     * @param string          $route
     *
     * @return PaginatedList
     */
    public function transform(PaginatedResult $result, $route)
    {
        $paginatedList = new PaginatedList();
        $paginatedList->setItemsPerPage($result->getItemsPerPage());
        $paginatedList->setTotal($result->getTotal());
        if ($result->getNextPageKey()->isDefined()) {
            $paginatedList->setNextPageUrl(
                new URLValue(
                    $this->router->generate(
                        $route,
                        array('offsetKey' => $result->getNextPageKey()->get()),
                        RouterInterface::ABSOLUTE_URL
                    )
                )
            );
        }
        if ($result->getPrevPageKey()->isDefined()) {
            $paginatedList->setPrevPageUrl(
                new URLValue(
                    $this->router->generate(
                        $route,
                        array('offsetKey' => $result->getPrevPageKey()->get(), 'offsetDir' => 'back'),
                        RouterInterface::ABSOLUTE_URL
                    )
                )
            );
        }
        return $paginatedList;
    }
}
