<?php

namespace Dothiv\AdminBundle\Service\Mailer;

use Dothiv\BusinessBundle\Entity\UserToken;
use Dothiv\BusinessBundle\Event\UserTokenEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class LoginLinkMailer
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $emailFromAddress;

    /**
     * @var string
     */
    private $emailFromName;

    /**
     * @var string
     */
    private $route;

    /**
     * @param \Swift_Mailer   $mailer
     * @param RouterInterface $router
     * @param string          $route
     * @param string          $emailFromAddress
     * @param string          $emailFromName
     */
    public function __construct(
        \Swift_Mailer $mailer,
        RouterInterface $router,
        $route,
        $emailFromAddress,
        $emailFromName)
    {
        $this->mailer           = $mailer;
        $this->router           = $router;
        $this->route            = $route;
        $this->emailFromAddress = $emailFromAddress;
        $this->emailFromName    = $emailFromName;
    }

    /**
     * @param UserToken $token
     * @param string    $locale
     *
     * @return void
     */
    public function sendLoginMail(UserToken $token, $locale)
    {
        $userToken = $token->getBearerToken();
        $user      = $token->getUser();

        $link = $this->router->generate(
            $this->route,
            array(),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $link .= sprintf('#!/auth/%s/%s', $user->getHandle(), $userToken);
        $link = preg_replace('%^http://%', 'https://', $link);

        $to     = $user->getEmail();
        $toName = $user->getFirstname() . ' ' . $user->getSurname();

        // send email
        $message = \Swift_Message::newInstance();
        $message
            ->setSubject('dotHIV initiative admin login')
            ->setFrom($this->emailFromAddress, $this->emailFromName)
            ->setTo($to, $toName)
            ->setBody($link);

        $this->mailer->send($message);

    }

    public function onLoginLinkRequested(UserTokenEvent $event)
    {
        $this->sendLoginMail($event->getUserToken(), $event->getLocale());
    }
} 
