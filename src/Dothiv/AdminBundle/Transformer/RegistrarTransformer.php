<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\AdminBundle\Model\RegistrarModel;
use Dothiv\BusinessBundle\Entity\EntityInterface;
use Dothiv\BusinessBundle\Entity\Registrar;
use Dothiv\ValueObject\URLValue;
use Dothiv\ValueObject\W3CDateTimeValue;
use PhpOption\Option;
use Symfony\Component\Routing\RouterInterface;

class RegistrarTransformer extends AbstractTransformer implements EntityTransformerInterface
{
    use Traits\AttachmentTransformerTrait;
    use Traits\UserTransformerTrait;

    /**
     * {@inheritdoc}
     */
    public function transform(EntityInterface $entity, $route = null, $listing = false)
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
