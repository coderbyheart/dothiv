<?php

namespace Dothiv\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\AdminBundle\Model\Report;
use Dothiv\AdminBundle\Model\Reporter;
use Dothiv\AdminBundle\Service\StatsServiceInterface;
use Dothiv\AdminBundle\Stats\ReporterInterface;
use Dothiv\APIBundle\Controller\Traits\CreateJsonResponseTrait;
use Dothiv\BusinessBundle\ValueObject\URLValue;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class StatsController
{
    use CreateJsonResponseTrait;

    /**
     * @var StatsServiceInterface
     */
    private $statsService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param StatsServiceInterface $statsService
     * @param SerializerInterface   $serializer
     * @param RouterInterface       $router
     */
    public function __construct(StatsServiceInterface $statsService, SerializerInterface $serializer, RouterInterface $router)
    {
        $this->statsService = $statsService;
        $this->serializer   = $serializer;
        $this->router       = $router;
    }

    /**
     * @return Response
     */
    public function listReportersAction()
    {
        $data      = array();
        $reporters = $this->statsService->getReporters();
        foreach ($reporters->getKeys() as $reporterId) {
            /** @var ReporterInterface $reporter */
            $model = new Reporter();
            $model->setJsonLdId($this->getReporterUrl($reporterId));
            $data[] = $model;
        }
        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($data, 'json'));
        return $response;
    }

    /**
     * @param string $reporterId
     *
     * @return Response
     *
     * @throws NotFoundHttpException If report is not found.
     */
    public function getReporterAction($reporterId)
    {
        $reporters = $this->statsService->getReporters();
        if (!$reporters->containsKey($reporterId)) {
            throw new NotFoundHttpException(sprintf('Unknown reporter "%s"!', $reporterId));
        }
        /** @var ReporterInterface $reporter */
        $reporter = $reporters->get($reporterId);

        $reports = new ArrayCollection();
        foreach ($reporter->getReports()->getKeys() as $reportId) {
            $report = new Report();
            $report->setJsonLdId(new URLValue($this->getReportUrl($reporterId, $reportId)));
            $report->setTitle($reporter->getReports()->get($reportId)->getTitle());
            $reports->add($report);
        }

        $model = new Reporter();
        $model->setTitle($reporter->getTitle());
        $model->setJsonLdId($this->getReporterUrl($reporterId));
        $model->setReports($reports);
        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($model, 'json'));
        return $response;
    }

    /**
     * @param string $reporterId
     * @param string $reportId
     *
     * @return Response
     *
     * @throws NotFoundHttpException If report is not found.
     */
    public function getReportAction($reporterId, $reportId)
    {
        $reporters = $this->statsService->getReporters();
        if (!$reporters->containsKey($reporterId)) {
            throw new NotFoundHttpException(sprintf('Unknown reporter "%s"!', $reporterId));
        }
        /** @var ReporterInterface $reporter */
        $reporter = $reporters->get($reporterId);
        if (!$reporter->getReports()->containsKey($reportId)) {
            throw new NotFoundHttpException(sprintf('Unknown report "%s" for reporter "%s"!', $reportId, $reporterId));
        }
        /** @var Report $report */
        $report = $reporter->getReports()->get($reportId);
        $report->setEvents($reporter->getEvents($reportId));
        $report->setJsonLdId($this->getReportUrl($reporterId, $reportId));
        $response = $this->createResponse();
        $response->setContent($this->serializer->serialize($report, 'json'));
        return $response;
    }

    /**
     * @param $reporterId
     * @param $reportId
     *
     * @return URLValue
     */
    protected function getReportUrl($reporterId, $reportId)
    {
        return new URLValue(
            $this->router->generate(
                'dothiv_admin_stats_report',
                array('reporterId' => $reporterId, 'reportId' => $reportId),
                RouterInterface::ABSOLUTE_URL
            )
        );
    }

    /**
     * @param $reporterId
     *
     * @return URLValue
     */
    protected function getReporterUrl($reporterId)
    {
        return new URLValue(
            $this->router->generate(
                'dothiv_admin_stats_get_reporter',
                array('reporterId' => $reporterId),
                RouterInterface::ABSOLUTE_URL
            )
        );
    }
}
