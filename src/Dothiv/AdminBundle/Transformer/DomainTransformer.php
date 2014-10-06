<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\AdminBundle\Model\DomainModel;
use Dothiv\BusinessBundle\Entity\Domain;
use Dothiv\BusinessBundle\Entity\Entity;
use Dothiv\BusinessBundle\Service\Traits\UserServiceTrait;
use Dothiv\ValueObject\EmailValue;
use Dothiv\ValueObject\HivDomainValue;
use Dothiv\ValueObject\URLValue;
use Dothiv\ValueObject\W3CDateTimeValue;
use PhpOption\Option;
use Symfony\Component\Routing\RouterInterface;

class DomainTransformer extends AbstractTransformer implements EntityTransformerInterface
{
    use Traits\AttachmentTransformerTrait;
    use Traits\RegistrarTransformerTrait;
    use UserServiceTrait;

    /**
     * @param Entity  $entity
     * @param string  $route
     * @param boolean $listing
     *
     * @return DomainModel
     */
    public function transform(Entity $entity, $route = null, $listing = false)
    {
        /** @var Domain $entity */
        $model = new DomainModel();
        $model->setDomain(new HivDomainValue($entity->getName()));
        $model->setCreated(new W3CDateTimeValue($entity->getCreated()));
        $model->setJsonLdId(new URLValue(
            $this->router->generate(
                Option::fromValue($route)->getOrElse($this->route),
                array('identifier' => $entity->getName()),
                RouterInterface::ABSOLUTE_URL
            )
        ));
        $model->setOwnerName($entity->getOwnerName());
        $model->setOwnerEmail(new EmailValue($entity->getOwnerEmail()));
        $model->setClickCounterConfigured(
            Option::fromValue($entity->getActiveBanner())->isDefined()
        );
        $model->setClickCount($entity->getClickcount());
        $model->setTokenSent(Option::fromValue($entity->getTokenSent())->isDefined());
        $model->setToken($entity->getToken());
        $model->setRegistrar($this->getRegistrarTransformer()->transform($entity->getRegistrar()));
        return $model;
    }
}
