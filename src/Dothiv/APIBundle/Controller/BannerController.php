<?php

namespace Dothiv\APIBundle\Controller;

use FOS\RestBundle\Util\Codes;
use Dothiv\BusinessBundle\Entity\Banner;
use Dothiv\BusinessBundle\Form\BannerType;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class BannerController extends FOSRestController {
    /**
     * Returns a specific banner.
     *
     * @ApiDoc(
     *   section="banner",
     *   resource=true,
     *   description="Returns a banner",
     *   statusCodes={
     *     200="Returned when successful",
     *   },
     *   output="Dothiv\BusinessBundle\Form\BannerType"
     * )
     */
    public function getBannerAction($id) {
        // TODO: security concern: who is allowed to GET banner information?

        // retrieve banner from database
        $banner = $this->getDoctrine()->getManager()->getRepository('DothivBusinessBundle:Banner')->findOneBy(array('id' => $id));
        return $this->createForm(new BannerType(), $banner);
    }

    /**
     * Creates a new banner.
     *
     * @ApiDoc(
     *   section="banner",
     *   resource=true,
     *   description="Creates a new banner",
     *   statusCodes={
     *     201="Successfully created"
     *   },
     *   output="Dothiv\BusinessBundle\Form\BannerType"
     * )
     */
    public function postBannersAction() {
        // TODO: security concern: who is allowed to create new banners?
        $banner = new Banner();

        $form = $this->createForm(new BannerType(), $banner);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            // push configuration
            if ($banner->getDomain())
                $this->get('clickcounter')->setup($banner->getDomain(), $banner);

            // persist the new banner
            $em = $this->getDoctrine()->getManager();
            $em->persist($banner);
            $em->flush();

            // prepare response
            $response = $this->redirectView($this->generateUrl('get_banner', array('id' => $banner->getId())), Codes::HTTP_CREATED);
            $response->setData($this->createForm(new BannerType(), $banner));
            return $response;
        }

        return array('form' => $form);
    }

    /**
     * Updates the banner.
     *
     * @ApiDoc(
     *   section="banner",
     *   resource=true,
     *   description="Updates the banner.",
     *   statusCodes={
     *     200="Successful"
     *   }
     * )
     */
    public function putBannerAction($id) {
        // fetch banner from database
        $em = $this->getDoctrine()->getManager();
        $banner = $em->getRepository('DothivBusinessBundle:Banner')->findOneBy(array('id' => $id));

        // apply form
        $form = $this->createForm(new BannerType(), $banner);
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            // push configuration
            if ($banner->getDomain())
                $this->get('clickcounter')->setup($banner->getDomain(), $banner);

            // persist the updated banner
            $em->persist($banner);
            $em->flush();
            return null;
        }

        return array('form' => $form);
    }
}