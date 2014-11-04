<?php


namespace Dothiv\AdminBundle\Controller;

use Dothiv\APIBundle\Controller\Traits\CreateJsonResponseTrait;
use Dothiv\BusinessBundle\Service\AttachmentServiceInterface;
use Guzzle\Http\Message\Response;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AttachmentController
{
    use CreateJsonResponseTrait;

    /**
     * @var AttachmentServiceInterface
     */
    private $attachmentService;

    public function __construct(AttachmentServiceInterface $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    /**
     * Downloads an attachment
     *
     * @param string $handle
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function getAttachmentAction($handle)
    {
        $optionalAttachment = $this->attachmentService->getAttachment($handle);
        if ($optionalAttachment->isEmpty()) {
            throw new NotFoundHttpException(sprintf('Attachment "%s" not found.', $handle));
        }

        $optionalFile = $this->attachmentService->getFile($optionalAttachment->get());
        if ($optionalFile->isEmpty()) {
            throw new NotFoundHttpException(sprintf('Attachment file "%s" not found.', $handle));
        }

        BinaryFileResponse::trustXSendfileTypeHeader();
        $response = new BinaryFileResponse($optionalFile->get()->getPathname());
        return $response;
    }
}
