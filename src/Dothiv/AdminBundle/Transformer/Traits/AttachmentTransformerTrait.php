<?php

namespace Dothiv\AdminBundle\Transformer\Traits;

use Dothiv\AdminBundle\Transformer\AttachmentTransformer;

trait AttachmentTransformerTrait
{

    /**
     * @var AttachmentTransformer
     */
    protected $attachmentTransformer;

    /**
     * @param AttachmentTransformer $attachmentTransformer
     *
     * @return self
     */
    public function setAttachmentTransformer(AttachmentTransformer $attachmentTransformer)
    {
        $this->attachmentTransformer = $attachmentTransformer;
        return $this;
    }

    /**
     * @return AttachmentTransformer
     */
    public function getAttachmentTransformer()
    {
        return $this->attachmentTransformer;
    }
}
