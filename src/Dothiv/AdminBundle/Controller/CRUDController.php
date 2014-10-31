<?php

namespace Dothiv\AdminBundle\Controller;

use Dothiv\AdminBundle\Entity\EntityChange;
use Dothiv\AdminBundle\Exception\BadRequestHttpException;
use Dothiv\AdminBundle\Exception\NotFoundHttpException;
use Dothiv\AdminBundle\Repository\EntityChangeRepositoryInterface;
use Dothiv\AdminBundle\Transformer\EntityTransformerInterface;
use Dothiv\AdminBundle\Transformer\PaginatedListTransformer;
use Dothiv\APIBundle\Controller\Traits\CreateJsonResponseTrait;
use Dothiv\BusinessBundle\Entity\EntityInterface;
use Dothiv\BusinessBundle\Model\FilterQuery;
use Dothiv\BusinessBundle\Repository\CRUDRepositoryInterface;
use Dothiv\BusinessBundle\Repository\PaginatedQueryOptions;
use Dothiv\BusinessBundle\Service\FilterQueryParser;
use Dothiv\ValueObject\EmailValue;
use Dothiv\ValueObject\IdentValue;
use Dothiv\ValueObject\ValueObjectInterface;
use JMS\Serializer\SerializerInterface;
use PhpOption\Option;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface;

class CRUDController
{
    use CreateJsonResponseTrait;

    /**
     * @var CRUDRepositoryInterface
     */
    protected $itemRepo;

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
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @param CRUDRepositoryInterface         $itemRepo
     * @param EntityTransformerInterface      $itemTransformer
     * @param PaginatedListTransformer        $paginatedListTransformer
     * @param SerializerInterface             $serializer
     * @param EntityChangeRepositoryInterface $entityChangeRepo
     * @param SecurityContextInterface        $securityContext
     */
    public function __construct(
        CRUDRepositoryInterface $itemRepo,
        EntityTransformerInterface $itemTransformer,
        PaginatedListTransformer $paginatedListTransformer,
        SerializerInterface $serializer,
        EntityChangeRepositoryInterface $entityChangeRepo,
        SecurityContextInterface $securityContext
    )
    {
        $this->itemRepo                 = $itemRepo;
        $this->itemTransformer          = $itemTransformer;
        $this->paginatedListTransformer = $paginatedListTransformer;
        $this->serializer               = $serializer;
        $this->entityChangeRepo         = $entityChangeRepo;
        $this->securityContext          = $securityContext;
    }

    /**
     * Returns the paginated list of items.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listItemsAction(Request $request)
    {
        $options = new PaginatedQueryOptions();
        Option::fromValue($request->query->get('sortField'))->map(function ($sortField) use ($options) {
            $options->setSortField($sortField);
        });
        Option::fromValue($request->query->get('sortDir'))->map(function ($sortDir) use ($options) {
            $options->setSortDir($sortDir);
        });
        Option::fromValue($request->query->get('offsetKey'))->map(function ($offsetKey) use ($options) {
            $options->setOffsetKey($offsetKey);
        });
        $filterQueryParser = new FilterQueryParser();
        $paginatedList     = $this->createListing(
            $this->itemRepo,
            $this->paginatedListTransformer,
            $this->itemTransformer,
            $options,
            $filterQueryParser->parse($request->query->get('q')),
            $request->attributes->get('_route')
        );

        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($paginatedList, 'json'));
        return $response;
    }

    /**
     * @param CRUDRepositoryInterface    $repo
     * @param PaginatedListTransformer   $listTransformer
     * @param EntityTransformerInterface $itemTransformer
     * @param PaginatedQueryOptions      $options
     * @param FilterQuery                $filterQuery
     * @param string                     $route
     *
     * @return \Dothiv\AdminBundle\Model\PaginatedList
     */
    protected function createListing(
        CRUDRepositoryInterface $repo,
        PaginatedListTransformer $listTransformer,
        EntityTransformerInterface $itemTransformer,
        PaginatedQueryOptions $options,
        FilterQuery $filterQuery,
        $route
    )
    {
        $paginatedResult = $repo->getPaginated($options, $filterQuery);
        $paginatedList   = $listTransformer->transform($paginatedResult, $route);
        foreach ($paginatedResult->getResult() as $reg) {
            $paginatedList->addItem($itemTransformer->transform($reg, null, true));
        }
        return $paginatedList;
    }

    /**
     * Returns a single item
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

    /**
     * Updates the item with the identifier $identifier
     *
     * @param Request $request
     * @param string  $identifier
     *
     * @return Response
     */
    public function updateItemAction(Request $request, $identifier)
    {
        /** @var EntityInterface $item */
        $item = $this->itemRepo->getItemByIdentifier($identifier)->getOrCall(function () use ($identifier) {
            throw new NotFoundHttpException(
                sprintf('No item with identifier "%s" found!', $identifier)
            );
        });

        $newPropertyValues = json_decode($request->getContent());
        $change            = $this->updateItem($item, $newPropertyValues);
        $this->entityChangeRepo->persist($change)->flush();

        return $this->createNoContentResponse();
    }

    /**
     * @param EntityInterface $item
     * @param array           $newPropertyValues
     *
     * @return EntityChange
     */
    protected function updateItem(EntityInterface $item, $newPropertyValues)
    {
        $change = new EntityChange();
        $change->setAuthor(new EmailValue($this->securityContext->getToken()->getUser()->getEmail()));
        $change->setEntity($this->itemRepo->getItemEntityName($item));
        $change->setIdentifier(new IdentValue($item->getPublicId()));

        foreach ($newPropertyValues as $property => $content) {
            $setter = 'set' . ucfirst($property);
            if (!method_exists($item, $setter)) {
                throw new BadRequestHttpException(sprintf('Unknown property "%s"!', $property));
            }

            $getter           = 'get' . ucfirst($property);
            $getPropertyValue = function () use ($item, $property, $getter) {
                $value = null;
                if (method_exists($item, $getter)) {
                    $value = $item->$getter();
                    if ($value instanceof ValueObjectInterface) {
                        $value = $value->toScalar();
                    }
                }
                return $value;
            };

            $oldValue = $getPropertyValue();
            $item->$setter($content);
            $this->itemRepo->persistItem($item)->flush();
            $newValue = $getPropertyValue();
            $change->addChange(new IdentValue($property), $oldValue, $newValue);
        }
        return $change;
    }
}
