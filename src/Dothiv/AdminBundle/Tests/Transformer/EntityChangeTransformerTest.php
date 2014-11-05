<?php

namespace Dothiv\AdminBundle\Tests\Transformer;

use Dothiv\AdminBundle\Entity\EntityChange;
use Dothiv\AdminBundle\Transformer\EntityChangeTransformer;
use Dothiv\BusinessBundle\Tests\ObjectManipulator;
use Dothiv\ValueObject\EmailValue;
use Dothiv\ValueObject\IdentValue;
use Symfony\Component\Routing\RouterInterface;

class EntityChangeTransformerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RouterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $mockRouter;

    /**
     * @test
     * @group AdminBundle
     * @group Transformer
     */
    public function itShouldBeInstantiable()
    {
        $this->assertInstanceOf('\Dothiv\AdminBundle\Transformer\EntityChangeTransformer', $this->createTestObject());
    }

    /**
     * @test
     * @group   AdminBundle
     * @group   Transformer
     * @depends itShouldBeInstantiable
     */
    public function itShouldTransformAnEntity()
    {
        $entityChange = new EntityChange();
        $entityChange->setAuthor(new EmailValue('john.doe@exmample.com'));
        $entityChange->setEntity('\Some\Namespace\Entity\SomeEntity');
        $entityChange->setIdentifier(new IdentValue('someIdent'));
        $entityChange->addChange(new IdentValue('someProperty'), 0, 1);
        ObjectManipulator::setProtectedProperty($entityChange, 'id', 17);

        $this->mockRouter->expects($this->once())->method('generate')
            ->with(
                'some_route',
                array('id' => 17, 'entity' => 'someentity', 'identifier' => 'someIdent'),
                RouterInterface::ABSOLUTE_URL
            )
            ->willReturn('http://example.com/');

        $model = $this->createTestObject()->transform($entityChange);
        $this->assertInstanceOf('\Dothiv\AdminBundle\Model\EntityChangeModel', $model);

        $this->assertEquals(new IdentValue("someentity"), $model->getEntity());
        $this->assertEquals(new IdentValue("someIdent"), $model->getIdentifier());
        $this->assertEquals(new EmailValue('john.doe@exmample.com'), $model->getAuthor());
        $this->assertEquals(0, $model->getChanges()->get('someProperty')->getOldValue());
        $this->assertEquals(1, $model->getChanges()->get('someProperty')->getNewValue());
    }

    /**
     * @return EntityChangeTransformer
     */
    public function createTestObject()
    {
        return new EntityChangeTransformer($this->mockRouter, 'some_route');
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->mockRouter = $this->getMock('\Symfony\Component\Routing\RouterInterface');
    }
}
