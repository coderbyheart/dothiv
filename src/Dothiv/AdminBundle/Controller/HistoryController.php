<?php

namespace Dothiv\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\BusinessBundle\Repository\EntityChangeRepositoryInterface;
use Dothiv\APIBundle\Transformer\EntityTransformerInterface;
use Dothiv\APIBundle\Transformer\PaginatedListTransformer;
use Dothiv\APIBundle\Controller\Traits\CreateJsonResponseTrait;
use Dothiv\BusinessBundle\Repository\PaginatedQueryOptions;
use Dothiv\BusinessBundle\Service\FilterQueryParser;
use Dothiv\ValueObject\IdentValue;
use JMS\Serializer\SerializerInterface;
use PhpOption\Option;
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
     * @var ArrayCollection
     */
    protected $entityMap;

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
        $this->entityMap                = new ArrayCollection();
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
        $options = new PaginatedQueryOptions();
        Option::fromValue($request->query->get('sortDir'))->map(function ($sortDir) use ($options) {
            $options->setSortDir($sortDir);
        });
        Option::fromValue($request->query->get('offsetKey'))->map(function ($offsetKey) use ($options) {
            $options->setOffsetKey($offsetKey);
        });
        $filterQueryParser = new FilterQueryParser();
        $paginatedResult   = $this->entityChangeRepo->getPaginated(
            $this->getEntityClass($entity),
            new IdentValue($identifier),
            $options,
            $filterQueryParser->parse($request->get('q'))
        );
        $paginatedList     = $this->paginatedListTransformer->transform($paginatedResult, $request->attributes->get('_route'));
        foreach ($paginatedResult->getResult() as $reg) {
            $paginatedList->addItem($this->itemTransformer->transform($reg, null, true));
        }
        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($paginatedList, 'json'));
        return $response;
    }

    /**
     * @param string $entityName
     *
     * @return string
     */
    protected function getEntityClass($entityName)
    {
        if ($this->entityMap->containsKey($entityName)) {
            return $this->entityMap->get($entityName);
        }
        return 'Dothiv\BusinessBundle\Entity\\' . ucfirst($entityName);
    }

    /**
     * @param string $entityName
     * @param string $entityClass
     */
    public function addEntityClass($entityName, $entityClass)
    {
        $this->entityMap->set($entityName, $entityClass);
    }
}
