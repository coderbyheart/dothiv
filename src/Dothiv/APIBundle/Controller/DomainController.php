<?php

namespace Dothiv\APIBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Dothiv\BusinessBundle\Form\DomainType;
use Dothiv\BusinessBundle\Entity\Domain;
use Dothiv\BusinessBundle\Entity\User;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;

class DomainController extends FOSRestController {
    /**
     * Returns one specific domain.
     *
     * @ApiDoc(
     *   section="domain",
     *   resource=true,
     *   description="Returns a domain",
     *   statusCodes={
     *     200="Returned when successful",
     *   },
     *   output="Dothiv\BusinessBundle\Form\DomainType"
     * )
     */
    public function getDomainAction($slug) {
        // TODO: security concern: who is allowed to GET domain information?

        // retrieve domain from database
        $domain = $this->getDoctrine()->getManager()->getRepository('DothivBusinessBundle:Domain')->findOneBy(array('id' => $slug));
        return $this->createForm(new DomainType(), $domain);
    }

    /**
     * Returns a list of all domains.
     *
     * @QueryParam(name="token", nullable=true, description="Claiming token")
     *
     * @ApiDoc(
     *   section="domain",
     *   resource=true,
     *   description="Returns a list of all domains",
     *   filters={{"name"="token", "dataType"="string"}},
     *   statusCodes={
     *     200="Returned when successful",
     *     400="Token unknown"
     *   }
     * )
     */
    public function getDomainsAction(ParamFetcher $paramFetcher) {
        // TODO: security concern: who is allowed to GET domain information?

        // get query parameter and entity manager
        $token = $paramFetcher->get('token');
        $em = $this->getDoctrine()->getManager();

        if ($token === null) {
            // retrieve list of domains from database
            $list = $em->getRepository('DothivBusinessBundle:Domain')->findAll();
            return $list;
        } else {
            // retrieve requested domain from database
            $domain = $em->getRepository('DothivBusinessBundle:Domain')->findOneBy(array('claimingToken' => $token));
            if ($domain === null)
                throw new HttpException(Codes::HTTP_BAD_REQUEST, 'Invalid token.'); // TODO: better error handling!
            return $this->createForm(new DomainType(), $domain);
        }
    }

    /**
     * Creates a new domain.
     *
     * @ApiDoc(
     *   section="domain",
     *   resource=true,
     *   description="Creates a new domain",
     *   statusCodes={
     *     201="Successfully created"
     *   },
     *   output="Dothiv\BusinessBundle\Form\DomainType"
     * )
     */
    public function postDomainsAction() {
        // TODO: this function is debug only and should be protected or removed.
        // TODO: security concern: who is allowed to create new domains?
        $domain = new Domain();

        $form = $this->createForm(new DomainType(), $domain);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $domain = $this->get('registration')->registered($domain->getName(), $domain->getEmailAddressFromRegistrar());

            // prepare response
            $response = $this->redirectView($this->generateUrl('get_domain', array('slug' => $domain->getId())), Codes::HTTP_CREATED);
            $response->setData($this->createForm(new DomainType(), $domain));
            return $response;
        }

        return array('form' => $form);
    }

    /**
     * Updates the domain.
     *
     * @ApiDoc(
     *   section="domain",
     *   resource=true,
     *   description="Updates the domain.",
     *   statusCodes={
     *     200="Successful",
     *     403="Access denied"
     *   },
     *   output="Dothiv\BusinessBundle\Entity\Domain"
     * )
     *
     * @Secure(roles="ROLE_USER")
     */
    public function putDomainAction($slug) {
        $context = $this->get('security.context');

        // fetch domain from database
        $em = $this->getDoctrine()->getManager();
        $domain = $em->getRepository('DothivBusinessBundle:Domain')->findOneBy(array('id' => $slug));

        if ($context->isGranted('ROLE_ADMIN') || $context->getToken()->getUsername() == $domain->getOwner()->getUsername()) {

            // apply form
            $oldForward = $domain->getDnsForward();
            $form = $this->createForm(new DomainType(), $domain);
            $form->bind($this->getRequest());

            if ($form->isValid()) {
                $em->persist($domain);
                $em->flush();

                // check if we need to update the DNS
                if ($oldForward != $domain->getDnsForward()) {
                    // DNS forward configuration changed
                    if ($domain->getDnsForward())
                        $this->get('dns')->forward($domain);
                    else
                        $this->get('dns')->reset($domain);
                }

                return null;
            }

            return array('form' => $form);
        }
        throw new HttpException(403);
    }

    /**
     * Gets the banners of this domain.
     *
     * @ApiDoc(
     *   section="domain",
     *   resource=true,
     *   description="Gets a list of banners of this domain",
     *   statusCodes={
     *     200="Successful",
     *     403="Access denied"
     *   },
     *   output="Dothiv\BusinessBundle\Entity\Banner"
     * )
     */
     public function getDomainBannersAction($id) {
        // TODO: security concern: who is allowed to get domain banners?

        // retrieve domain from database
        $domain = $this->getDoctrine()->getManager()->getRepository('DothivBusinessBundle:Domain')->findOneBy(array('id' => $id));

        // return list of banners
        return $domain->getBanners();
    }

    // ----------- private functions go here -----------

    /**
     * Generates a 32 digit random code
     *
     * Used pool of characters: a-zA-Z0-9
     */
    private function newRandomCode() {
        $pool = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWWXY0123456789";
        $code = "";
        while (strlen($code) < 32) {
            $code .= substr($pool, rand(0, 61), 1);
        }
        return $code;
    }
}