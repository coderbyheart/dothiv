<?php


namespace Dothiv\AdminBundle\Service\Manipulator;

use Dothiv\AdminBundle\Exception\InvalidArgumentException;
use Dothiv\AdminBundle\Model\EntityPropertyChange;
use Dothiv\BusinessBundle\Entity\EntityInterface;

interface EntityManipulatorInterface
{
    /**
     * Set the properties $properties on the entity $entity.
     *
     * @param EntityInterface $entity     The entity to manipulate
     * @param array           $properties Array of properties to set
     *
     * @return EntityPropertyChange[]
     *
     * @throws InvalidArgumentException
     */
    public function manipulate(EntityInterface $entity, array $properties);
}
