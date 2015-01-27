<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\AdminBundle\Model\DomainWhoisModel;
use Dothiv\APIBundle\Transformer\AbstractTransformer;
use Dothiv\APIBundle\Transformer\EntityTransformerInterface;
use Dothiv\BusinessBundle\Entity\DomainWhois;
use Dothiv\BusinessBundle\Entity\EntityInterface;
use Dothiv\BusinessBundle\Service\Traits\UserServiceTrait;
use Dothiv\ValueObject\EmailValue;
use Dothiv\ValueObject\HivDomainWhoisValue;
use Dothiv\ValueObject\URLValue;
use Dothiv\ValueObject\W3CDateTimeValue;
use PhpOption\Option;
use Symfony\Component\Routing\RouterInterface;

class DomainWhoisTransformer extends AbstractTransformer implements EntityTransformerInterface
{
    use Traits\AttachmentTransformerTrait;
    use Traits\RegistrarTransformerTrait;
    use UserServiceTrait;

    /**
     * {@inheritdoc}
     */
    public function transform(EntityInterface $entity, $route = null, $listing = false)
    {
        /** @var DomainWhois $entity */
        $model = new DomainWhoisModel($entity->getDomain(), $entity->getWhois());
        $model->setCreated(new W3CDateTimeValue($entity->getCreated()));
        $model->setJsonLdId(new URLValue(
            $this->router->generate(
                Option::fromValue($route)->getOrElse($this->route),
                array('identifier' => $entity->getPublicId()),
                RouterInterface::ABSOLUTE_URL
            )
        ));
        return $model;
    }
}
