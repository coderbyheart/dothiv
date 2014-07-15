<?php

namespace Dothiv\APIBundle\Security\Firewall;

use Dothiv\APIBundle\Security\Authentication\Token\Oauth2BearerToken;
use PhpOption\Option;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

class Oauth2BearerListener implements ListenerInterface
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(
        SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $auth = Option::fromValue($request->headers->get('authorization'))
            ->orElse(Option::fromValue(isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null))
            ->orElse(Option::fromValue(isset($_SERVER['PHP_AUTH_DIGEST']) ? $_SERVER['PHP_AUTH_DIGEST'] : null));

        $token = new Oauth2BearerToken();
        $this->securityContext->setToken($token);

        if ($auth->isDefined()) {
            if (preg_match('/^Bearer (.+)/', $auth->get(), $matches) === 1) {
                $token->setBearerToken($matches[1]);
            }
        }
    }
}