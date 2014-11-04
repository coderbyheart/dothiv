<?php

namespace Dothiv\AdminBundle\Service\Manipulator\Tests;

use Dothiv\AdminBundle\Model\EntityPropertyChange;
use Dothiv\AdminBundle\Service\Manipulator\NonProfitRegistrationEntityManipulator;
use Dothiv\BusinessBundle\Entity\NonProfitRegistration;
use Dothiv\BusinessBundle\Entity\User;
use Dothiv\ValueObject\ClockValue;
use Dothiv\ValueObject\IdentValue;

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
     * @test
     * @group   Entity
     * @group   AdminBundle
     * @group   Manipulator
     * @depends itShouldBeInstantiable
     */
    public function itShouldManipulateAnEntity()
    {
        $domain     = new NonProfitRegistration();
        $properties = array(
            'approved' => '1'
        );
        $changes    = $this->createTestObject()->manipulate($domain, $properties);
        $this->assertEquals($this->getClock()->getNow(), $domain->getApproved());
        $this->assertEquals(1, count($changes));
        $this->assertInstanceOf('Dothiv\AdminBundle\Model\EntityPropertyChange', $changes[0]);
        /** @var EntityPropertyChange $change */
        $change = $changes[0];
        $this->assertEquals(null, $change->getOldValue());
        $this->assertEquals($this->getClock()->getNow(), $change->getNewValue());
        $this->assertEquals(new IdentValue('approved'), $change->getProperty());
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
