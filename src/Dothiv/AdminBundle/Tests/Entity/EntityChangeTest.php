<?php

namespace Dothiv\AdminBundle\Entity\Tests;

use Dothiv\AdminBundle\Entity\EntityChange;
use Dothiv\ValueObject\IdentValue;

class EntityChangeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group Entity
     * @group AdminBundle
     * @group EntityChange
     */
    public function itShouldBeInstantiateable()
    {
        $this->assertInstanceOf('\Dothiv\AdminBundle\Entity\EntityChange', $this->getTestObject());
    }

    /**
     * @test
     * @group   Entity
     * @group   AdminBundle
     * @group   EntityChange
     * @depends itShouldBeInstantiateable
     */
    public function ensureThatICanAddChanges()
    {
        $entityHistory = $this->getTestObject();
        $entityHistory->addChange(new IdentValue('someProperty'), 0, 1);
        $changes = $entityHistory->getChanges();
        $this->assertInstanceOf('\Doctrine\Common\Collections\ArrayCollection', $changes);
        $this->assertEquals(0, $changes->get('someProperty')->getOldValue());
        $this->assertEquals(1, $changes->get('someProperty')->getNewValue());
        $this->assertEquals(new IdentValue('someProperty'), $changes->get('someProperty')->getProperty());
    }

    /**
     * @test
     * @group                    Entity
     * @group                    AdminBundle
     * @group                    EntityChange
     * @depends                  itShouldBeInstantiateable
     * @expectedException \Dothiv\AdminBundle\Exception\InvalidArgumentException
     * @expectedExceptionMessage newValue "0" does not differ from oldValue
     */
    public function ensureThatIMustNotAddUnchangedValues()
    {
        $entityHistory = $this->getTestObject();
        $entityHistory->addChange(new IdentValue('someProperty'), 0, 0);
    }

    /**
     * @return EntityChange
     */
    protected function getTestObject()
    {
        return new EntityChange();
    }
} 
