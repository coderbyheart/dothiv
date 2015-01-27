<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\AdminBundle\Model\DeletedDomainModel;
use Dothiv\APIBundle\Transformer\AbstractTransformer;
use Dothiv\APIBundle\Transformer\EntityTransformerInterface;
use Dothiv\BusinessBundle\Entity\DeletedDomain;
use Dothiv\BusinessBundle\Entity\EntityInterface;
use Dothiv\BusinessBundle\Service\Traits\UserServiceTrait;
use Dothiv\ValueObject\URLValue;
use Dothiv\ValueObject\W3CDateTimeValue;
use PhpOption\Option;
use Symfony\Component\Routing\RouterInterface;

class DeletedDomainTransformer extends AbstractTransformer implements EntityTransformerInterface
{
    use Traits\AttachmentTransformerTrait;
    use Traits\RegistrarTransformerTrait;
    use UserServiceTrait;

    /**
     * {@inheritdoc}
     */
    public function transform(EntityInterface $entity, $route = null, $listing = false)
    {
        /** @var DeletedDomain $entity */
        $model = new DeletedDomainModel($entity->getDomain());
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
