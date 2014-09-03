<?php

namespace Dothiv\AdminBundle\Controller;

use Dothiv\AdminBundle\Model\NonProfitRegistrationModel;
use Dothiv\AdminBundle\Model\PaginatedList;
use Dothiv\AdminBundle\Transformer\NonProfitRegistrationTransformer;
use Dothiv\AdminBundle\Transformer\PaginatedListTransformer;
use Dothiv\APIBundle\Controller\Traits\CreateJsonResponseTrait;
use Dothiv\BusinessBundle\Entity\NonProfitRegistration;
use Dothiv\BusinessBundle\Repository\NonProfitRegistrationRepositoryInterface;
use Dothiv\BusinessBundle\Service\NonprofitRegistrationServiceInterface;
use Dothiv\BusinessBundle\ValueObject\HivDomainValue;
use Dothiv\BusinessBundle\ValueObject\URLValue;
use Dothiv\BusinessBundle\ValueObject\W3CDateTimeValue;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NonProfitRegistrationController
{
    use CreateJsonResponseTrait;

    /**
     * @var NonprofitRegistrationServiceInterface
     */
    protected $nonProfitService;

    /**
     * @var NonProfitRegistrationRepositoryInterface
     */
    protected $nonProfitRepo;

    /**
     * @var NonProfitRegistrationTransformer
     */
    protected $nonProfitTransformer;

    /**
     * @var PaginatedListTransformer
     */
    protected $paginatedListTransformer;

    /**
     * @param NonprofitRegistrationServiceInterface    $nonProfitService
     * @param NonProfitRegistrationRepositoryInterface $nonProfitRepo
     * @param NonProfitRegistrationTransformer         $nonProfitTransformer
     * @param PaginatedListTransformer                 $paginatedListTransformer ,
     * @param SerializerInterface                      $serializer
     */
    public function __construct(
        NonprofitRegistrationServiceInterface $nonProfitService,
        NonProfitRegistrationRepositoryInterface $nonProfitRepo,
        NonProfitRegistrationTransformer $nonProfitTransformer,
        PaginatedListTransformer $paginatedListTransformer,
        SerializerInterface $serializer
    )
    {
        $this->nonProfitService         = $nonProfitService;
        $this->nonProfitRepo            = $nonProfitRepo;
        $this->nonProfitTransformer     = $nonProfitTransformer;
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
    public function listRegistrationsAction(Request $request)
    {
        $paginatedResult = $this->nonProfitRepo->getActivePaginated(
            $request->query->get('offsetKey'),
            $request->query->get('offsetDir')
        );
        $paginatedList   = $this->paginatedListTransformer->transform($paginatedResult, $request->attributes->get('_route'));
        foreach ($paginatedResult->getResult() as $reg) {
            $paginatedList->addItem($this->nonProfitTransformer->transform($reg, null, true));
        }

        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($paginatedList, 'json'));
        return $response;
    }

    /**
     * Returns a single registration
     *
     * @param Request $request
     * @param string  $domain
     *
     * @return Response
     */
    public function getRegistrationAction(Request $request, $domain)
    {
        $reg = $this->nonProfitRepo->getNonProfitRegistrationByDomainName($domain)->getOrCall(function () use ($domain) {
            throw new NotFoundHttpException(
                sprintf('No registration for domain "%s" found!', $domain)
            );
        });

        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($this->nonProfitTransformer->transform($reg), 'json'));
        return $response;
    }

}
