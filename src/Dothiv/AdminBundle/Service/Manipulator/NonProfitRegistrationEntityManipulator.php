<?php

namespace Dothiv\AdminBundle\Service\Manipulator;

use Dothiv\BusinessBundle\Entity\EntityInterface;
use Dothiv\BusinessBundle\Entity\NonProfitRegistration;
use Dothiv\ValueObject\ClockValue;
use Dothiv\ValueObject\W3CDateTimeValue;

class NonProfitRegistrationEntityManipulator extends GenericEntityManipulator implements EntityManipulatorInterface
{

    /**
     * @var ClockValue
     */
    protected $clock;

    /**
     * @param ClockValue $clock
     */
    public function __construct(ClockValue $clock)
    {
        $this->clock = $clock;
    }

    /**
     * {@inheritdoc}
     */
    protected function setValue(EntityInterface $entity, $property, $value)
    {
        /** @var NonProfitRegistration $entity */
        switch ($property) {
            case 'approved':
                if ($value) {
                    $entity->setApproved(new W3CDateTimeValue($this->clock->getNow()));
                } else {
                    $entity->setApproved(null);
                }
                break;
            case 'registered':
                if ($value) {
                    $entity->setRegistered(new W3CDateTimeValue($this->clock->getNow()));
                } else {
                    $entity->setRegistered(null);
                }
                break;
            default:
                parent::setValue($entity, $property, $value);
        }
    }
}
