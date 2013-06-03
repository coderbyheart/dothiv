<?php

namespace DotHiv\WebsiteCharityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartialController extends Controller
{
    public function homeAction()
    {
        return $this->render('DotHivWebsiteCharityBundle:Partials:home.html.twig');
    }

    public function loginAction()
    {
        return $this->render('DotHivWebsiteCharityBundle:Partials:login.html.twig');
    }

    public function profileAction()
    {
        return $this->render('DotHivWebsiteCharityBundle:Partials:profile.html.twig');
    }

    public function registerAction()
    {
        return $this->render('DotHivWebsiteCharityBundle:Partials:register.html.twig');
    }
}
