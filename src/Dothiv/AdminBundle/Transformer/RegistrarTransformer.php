<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\AdminBundle\Model\RegistrarModel;
use Dothiv\BusinessBundle\Entity\Entity;
use Dothiv\BusinessBundle\Entity\Registrar;
use Dothiv\BusinessBundle\ValueObject\URLValue;
use Dothiv\BusinessBundle\ValueObject\W3CDateTimeValue;
use PhpOption\Option;
use Symfony\Component\Routing\RouterInterface;

class RegistrarTransformer extends AbstractTransformer implements EntityTransformerInterface
{
    use Traits\AttachmentTransformerTrait;
    use Traits\UserTransformerTrait;

    /**
     * @param Entity  $entity
     * @param string  $route
     * @param boolean $listing
     *
     * @return RegistrarModel
     */
    public function transform(Entity $entity, $route = null, $listing = false)
    {
        /** @var Registrar $entity */
        $model = new RegistrarModel();
        $model->setName($entity->getName());
        $model->setNotification($entity->canSendRegistrationNotification());
        $model->setNotificationType($entity->getRegistrationNotification());
        $model->setExtId($entity->getExtId());
        $model->setCreated(new W3CDateTimeValue($entity->getCreated()));
        $model->setJsonLdId(new URLValue(
            $this->router->generate(
                Option::fromValue($route)->getOrElse($this->route),
                array('identifier' => $entity->getExtId()),
                RouterInterface::ABSOLUTE_URL
            )
        ));
        return $model;
    }
}
