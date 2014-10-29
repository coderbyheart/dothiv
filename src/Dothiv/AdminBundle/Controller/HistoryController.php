<?php

namespace Dothiv\AdminBundle\Controller;

use Dothiv\AdminBundle\Repository\EntityChangeRepositoryInterface;
use Dothiv\AdminBundle\Transformer\EntityTransformerInterface;
use Dothiv\AdminBundle\Transformer\PaginatedListTransformer;
use Dothiv\APIBundle\Controller\Traits\CreateJsonResponseTrait;
use Dothiv\ValueObject\IdentValue;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HistoryController
{
    use CreateJsonResponseTrait;

    /**
     * @var EntityTransformerInterface
     */
    protected $itemTransformer;

    /**
     * @var PaginatedListTransformer
     */
    protected $paginatedListTransformer;

    /**
     * @var EntityChangeRepositoryInterface
     */
    protected $entityChangeRepo;

    /**
     * @param EntityChangeRepositoryInterface $entityChangeRepo
     * @param EntityTransformerInterface      $itemTransformer
     * @param PaginatedListTransformer        $paginatedListTransformer
     * @param SerializerInterface             $serializer
     */
    public function __construct(
        EntityChangeRepositoryInterface $entityChangeRepo,
        EntityTransformerInterface $itemTransformer,
        PaginatedListTransformer $paginatedListTransformer,
        SerializerInterface $serializer
    )
    {
        $this->itemTransformer          = $itemTransformer;
        $this->paginatedListTransformer = $paginatedListTransformer;
        $this->serializer               = $serializer;
        $this->entityChangeRepo         = $entityChangeRepo;
    }

    /**
     * Returns the paginated list of changes for the given $entity with identifier $identifier.
     *
     * @param Request $request
     * @param string  $entity
     * @param string  $identifier
     *
     * @return Response
     */
    public function listItemsAction(Request $request, $entity, $identifier)
    {
        $paginatedResult = $this->entityChangeRepo->getPaginated(
            'Dothiv\BusinessBundle\Entity\\' . ucfirst($entity),
            new IdentValue($identifier),
            $request->query->get('offsetKey'),
            $request->query->get('offsetDir')
        );
        $paginatedList   = $this->paginatedListTransformer->transform($paginatedResult, $request->attributes->get('_route'));
        foreach ($paginatedResult->getResult() as $reg) {
            $paginatedList->addItem($this->itemTransformer->transform($reg, null, true));
        }
        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($paginatedList, 'json'));
        return $response;
    }
}
