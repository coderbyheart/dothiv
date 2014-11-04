<?php

namespace Dothiv\AdminBundle\Service\Manipulator\Tests;

use Dothiv\AdminBundle\Model\EntityPropertyChange;
use Dothiv\AdminBundle\Service\Manipulator\NonProfitRegistrationEntityManipulator;
use Dothiv\BusinessBundle\Entity\NonProfitRegistration;
use Dothiv\BusinessBundle\Entity\User;
use Dothiv\ValueObject\ClockValue;
use Dothiv\ValueObject\IdentValue;
use Dothiv\ValueObject\W3CDateTimeValue;

class NonProfitRegistrationEntityManipulatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group Entity
     * @group AdminBundle
     * @group Manipulator
     */
    public function itShouldBeInstantiable()
    {
        $this->assertInstanceOf('\Dothiv\AdminBundle\Service\Manipulator\NonProfitRegistrationEntityManipulator', $this->createTestObject());
    }

    /**
     * @return array
     */
    public function propertyData()
    {
        $w3cNow = new W3CDateTimeValue($this->getClock()->getNow());
        return array(
            array('approved', null, '1', $w3cNow),
            array('approved', $w3cNow, '0', null),
            array('registered', null, '1', $w3cNow),
            array('registered', $w3cNow, '0', null),
        );
    }

    /**
     * @test
     * @group        Entity
     * @group        AdminBundle
     * @group        Manipulator
     * @depends      itShouldBeInstantiable
     * @dataProvider propertyData
     *
     * @param string $property
     * @param mixed  $oldValue
     * @param string $propertyValue
     * @param mixed  $newValue
     */
    public function itShouldManipulateAnEntity($property, $oldValue, $propertyValue, $newValue)
    {
        $registration = new NonProfitRegistration();
        $getter       = 'get' . ucfirst($property);
        $setter       = 'set' . ucfirst($property);
        $registration->$setter($oldValue);
        $properties = array(
            $property => $propertyValue
        );
        $changes    = $this->createTestObject()->manipulate($registration, $properties);

        $this->assertEquals($newValue, $registration->$getter());
        $this->assertEquals(1, count($changes));
        $this->assertInstanceOf('Dothiv\AdminBundle\Model\EntityPropertyChange', $changes[0]);
        /** @var EntityPropertyChange $change */
        $change = $changes[0];
        $this->assertEquals($oldValue, $change->getOldValue());
        $this->assertEquals($newValue, $change->getNewValue());
        $this->assertEquals(new IdentValue($property), $change->getProperty());
    }

    /**
     * @return NonProfitRegistrationEntityManipulator
     */
    protected function createTestObject()
    {
        return new NonProfitRegistrationEntityManipulator($this->getClock());
    }

    /**
     * @return ClockValue
     */
    protected function getClock()
    {
        $clock = new ClockValue(new \DateTime('2014-08-01T12:34:56Z'));
        return $clock;
    }
}
