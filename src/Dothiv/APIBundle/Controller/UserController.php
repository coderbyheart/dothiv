<?php

namespace Dothiv\APIBundle\Controller;

use Dothiv\APIBundle\Controller\Traits\CreateJsonResponseTrait;
use Dothiv\BusinessBundle\Entity\User;
use Dothiv\BusinessBundle\Repository\DomainRepositoryInterface;
use Dothiv\BusinessBundle\Repository\UserRepositoryInterface;
use Dothiv\BusinessBundle\Repository\UserTokenRepositoryInterface;
use Dothiv\BusinessBundle\Service\Clock;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\SecurityContext;


class UserController
{
    use CreateJsonResponseTrait;

    /**
     * @var DomainRepositoryInterface
     */
    private $domainRepo;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepo;

    /**
     * @var UserTokenRepositoryInterface
     */
    private $userTokenRepo;

    /**
     * @var SecurityContext
     */
    private $securityContext;

    /**
     * @var Clock
     */
    private $clock;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(
        SecurityContext $securityContext,
        DomainRepositoryInterface $domainRepo,
        UserRepositoryInterface $userRepo,
        UserTokenRepositoryInterface $userTokenRepo,
        Serializer $serializer,
        Clock $clock
    )
    {
        $this->domainRepo      = $domainRepo;
        $this->userRepo        = $userRepo;
        $this->userTokenRepo   = $userTokenRepo;
        $this->securityContext = $securityContext;
        $this->serializer      = $serializer;
        $this->clock           = $clock;
    }

    /**
     * Gets this user's domains.
     *
     * @param string $handle
     *
     * @return Response
     */
    public function domainsAction($handle)
    {
        $user     = $this->verifyUserHandle($handle);
        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($user->getDomains(), 'json'));
        return $response;
    }

    /**
     * Returns the users profile.
     *
     * @param string $handle
     *
     * @return Response
     */
    public function profileAction($handle)
    {
        $user     = $this->verifyUserHandle($handle);
        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($user, 'json'));
        return $response;
    }

    /**
     * @param $handle
     *
     * @return User
     * @throws AccessDeniedHttpException If tries to fetch domains for another user.
     */
    protected function verifyUserHandle($handle)
    {
        /* @var User $user */
        $user = $this->securityContext->getToken()->getUser();
        if ($user->getHandle() !== $handle) {
            throw new AccessDeniedHttpException();
        }
        return $user;
    }

    /**
     * Revokes the token for the user.
     *
     * @param $handle
     *
     * @return Response
     */
    public function revokeTokenAction($handle)
    {
        $this->verifyUserHandle($handle);
        $token = $this->userTokenRepo->getTokenByBearerToken($this->securityContext->getToken()->getBearerToken())->get();
        $token->revoke($this->clock->getNow());
        $this->userTokenRepo->persist($token)->flush();
        return $this->createResponse();
    }
}
