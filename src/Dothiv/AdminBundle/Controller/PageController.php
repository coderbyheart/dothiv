<?php

namespace Dothiv\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController
{
    /**
     * @var EngineInterface
     */
    private $renderer;

    /**
     * @param EngineInterface $renderer
     */
    public function __construct(
        EngineInterface $renderer
    )
    {
        $this->renderer = $renderer;
    }

    /**
     * @param Request $request
     * @param string  $page
     *
     * @return Response
     */
    public function pageAction(Request $request, $page)
    {
        $response = new Response();
        $response->setPublic();
        $response = $this->renderer->renderResponse(
            sprintf('DothivAdminBundle:Page:%s.html.twig', $page),
            array(),
            $response
        );
        return $response;
    }

    /**
     * @param Request $request
     * @param string  $page
     *
     * @return Response
     */
    public function appPageAction(Request $request, $page)
    {
        return $this->pageAction($request, 'app/' . $page);
    }
}
