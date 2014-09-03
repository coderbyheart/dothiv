<?php

namespace Dothiv\AdminBundle\Controller;

use Dothiv\AdminBundle\Transformer\NonProfitRegistrationTransformer;
use Dothiv\AdminBundle\Transformer\PaginatedListTransformer;
use Dothiv\AdminBundle\Transformer\EntityTransformerInterface;
use Dothiv\APIBundle\Controller\Traits\CreateJsonResponseTrait;
use Dothiv\BusinessBundle\Repository\CRUDRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CRUDController
{
    use CreateJsonResponseTrait;

    /**
     * @var CRUDRepository
     */
    protected $itemRepo;

    /**
     * @var NonProfitRegistrationTransformer
     */
    protected $itemTransformer;

    /**
     * @var PaginatedListTransformer
     */
    protected $paginatedListTransformer;

    /**
     * @param CRUDRepository             $itemRepo
     * @param EntityTransformerInterface $itemTransformer
     * @param PaginatedListTransformer   $paginatedListTransformer
     * @param SerializerInterface        $serializer
     */
    public function __construct(
        CRUDRepository $itemRepo,
        EntityTransformerInterface $itemTransformer,
        PaginatedListTransformer $paginatedListTransformer,
        SerializerInterface $serializer
    )
    {
        $this->itemRepo                 = $itemRepo;
        $this->itemTransformer          = $itemTransformer;
        $this->paginatedListTransformer = $paginatedListTransformer;
        $this->serializer               = $serializer;
    }

    /**
     * Returns the paginated list of nonprofit registrations.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listItemsAction(Request $request)
    {
        $paginatedList = $this->createListing(
            $this->itemRepo,
            $request->query->get('offsetKey'),
            $request->query->get('offsetDir'),
            $this->paginatedListTransformer,
            $request->attributes->get('_route'),
            $this->itemTransformer
        );

        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($paginatedList, 'json'));
        return $response;
    }

    /**
     * @param CRUDRepository             $repo
     * @param string                     $offsetKey
     * @param string                     $offsetDir
     * @param PaginatedListTransformer   $listTransformer
     * @param string                     $route
     * @param EntityTransformerInterface $itemTransformer
     *
     * @return \Dothiv\AdminBundle\Model\PaginatedList
     */
    protected function createListing(
        CRUDRepository $repo,
        $offsetKey,
        $offsetDir,
        PaginatedListTransformer $listTransformer,
        $route,
        EntityTransformerInterface $itemTransformer
    )
    {
        $paginatedResult = $repo->getPaginated($offsetKey, $offsetDir);
        $paginatedList   = $listTransformer->transform($paginatedResult, $route);
        foreach ($paginatedResult->getResult() as $reg) {
            $paginatedList->addItem($itemTransformer->transform($reg, null, true));
        }
        return $paginatedList;
    }

    /**
     * Returns a single registration
     *
     * @param string $identifier
     *
     * @return Response
     */
    public function getItemAction($identifier)
    {
        $item = $this->itemRepo->getItemByIdentifier($identifier)->getOrCall(function () use ($identifier) {
            throw new NotFoundHttpException(
                sprintf('No item with identifier "%s" found!', $identifier)
            );
        });

        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($this->itemTransformer->transform($item), 'json'));
        return $response;
    }
}
