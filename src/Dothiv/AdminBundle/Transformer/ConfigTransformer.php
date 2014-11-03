<?php

namespace Dothiv\AdminBundle\Transformer;

use Dothiv\AdminBundle\Model\ConfigModel;
use Dothiv\BusinessBundle\Entity\Config;
use Dothiv\BusinessBundle\Entity\EntityInterface;
use Dothiv\ValueObject\URLValue;
use Dothiv\ValueObject\W3CDateTimeValue;
use PhpOption\Option;
use Symfony\Component\Routing\RouterInterface;

class ConfigTransformer extends AbstractTransformer implements EntityTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(EntityInterface $entity, $route = null, $listing = false)
    {
        /** @var Config $entity */
        $model = new ConfigModel();
        $model->setName($entity->getName());
        $model->setValue($entity->getValue());
        $model->setCreated(new W3CDateTimeValue($entity->getCreated()));
        $model->setUpdated(new W3CDateTimeValue($entity->getUpdated()));
        $model->setJsonLdId(new URLValue(
            $this->router->generate(
                Option::fromValue($route)->getOrElse($this->route),
                array('identifier' => $entity->getName()),
                RouterInterface::ABSOLUTE_URL
            )
        ));
        return $model;
    }
}
