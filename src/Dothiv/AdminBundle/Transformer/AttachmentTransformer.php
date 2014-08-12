<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\AdminBundle\Model\AttachmentModel;
use Dothiv\BusinessBundle\Entity\Attachment;
use Dothiv\BusinessBundle\ValueObject\URLValue;
use Dothiv\BusinessBundle\ValueObject\W3CDateTimeValue;
use PhpOption\Option;
use Symfony\Component\Routing\RouterInterface;

class AttachmentTransformer extends AbstractTransformer
{
    /**
     * @param Attachment $entity
     * @param string     $route
     *
     * @return AttachmentModel
     */
    public function transform(Attachment $entity, $route = null)
    {
        $model = new AttachmentModel();
        $model->setCreated(new W3CDateTimeValue($entity->getCreated()));
        $model->setJsonLdId(new URLValue(
            $this->router->generate(
                Option::fromValue($route)->getOrElse($this->route),
                array('handle' => $entity->getHandle()),
                RouterInterface::ABSOLUTE_URL
            )
        ));
        $model->setUrl(new URLValue($this->router->generate(
            Option::fromValue($route)->getOrElse($this->route),
            array('handle' => $entity->getHandle(), 'download' => '1'),
            RouterInterface::ABSOLUTE_URL
        )));
        $model->setMimeType($entity->getMimeType());
        return $model;
    }
}
