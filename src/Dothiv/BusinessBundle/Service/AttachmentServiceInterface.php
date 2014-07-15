<?php

namespace Dothiv\BusinessBundle\Service;

use Dothiv\BusinessBundle\Entity\Attachment;
use Dothiv\BusinessBundle\Entity\User;
use Dothiv\BusinessBundle\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface AttachmentServiceInterface
{
    /**
     * @param User         $user
     * @param UploadedFile $file
     *
     * @return Attachment
     */
    public function createAttachment(User $user, UploadedFile $file);

    /**
     * @param UploadedFile $file
     *
     * @throws InvalidArgumentException
     */
    public function validateUpload(UploadedFile $file);
}