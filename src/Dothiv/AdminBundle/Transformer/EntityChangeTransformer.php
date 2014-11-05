<?php


namespace Dothiv\AdminBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Dothiv\APIBundle\Transformer\AbstractTransformer;
use Dothiv\APIBundle\Transformer\EntityTransformerInterface;
use Dothiv\BusinessBundle\Entity\EntityChange;
use Dothiv\AdminBundle\Model\EntityChangeModel;
use Dothiv\BusinessBundle\Entity\EntityInterface;
use Dothiv\ValueObject\IdentValue;
use Dothiv\ValueObject\URLValue;
use PhpOption\Option;
use Symfony\Component\Routing\RouterInterface;

class EntityChangeTransformer extends AbstractTransformer implements EntityTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform(EntityInterface $entity, $route = null, $listing = false)
    {
        /** @var EntityChange $entity */
        $entityClass = explode('\\', $entity->getEntity());
        $entityAlias = strtolower(array_pop($entityClass));
        $model       = new EntityChangeModel();
        $model->setJsonLdId(new URLValue(
            $this->router->generate(
                Option::fromValue($route)->getOrElse($this->route),
                array(
                    'id'         => $entity->getPublicId(),
                    'identifier' => $entity->getIdentifier()->toScalar(),
                    'entity'     => $entityAlias
                ),
                RouterInterface::ABSOLUTE_URL
            )
        ));
        $model->setEntity(new IdentValue($entityAlias));
        $model->setIdentifier(clone $entity->getIdentifier());
        $model->setAuthor(clone $entity->getAuthor());
        $changes = new ArrayCollection();
        foreach ($entity->getChanges()->toArray() as $property => $change) {
            $changes->set($property, clone $change);
        }
        $model->setChanges($changes);
        return $model;
    }
}
