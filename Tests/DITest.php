<?php
namespace DHP_FW\dependencyInjection;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-07 at 19:47:35.
 */
class DITest extends \PHPUnit_Framework_TestCase {
    /**
     * @var DI
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(){
        $this->object = new DI();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(){
    }

    /**
     */
    public function testAddObjectAlias(){
        #$o = new \DHP_FW\Event();
        $this->object->set('DHP_FW\EventInterface','DHP_FW\Event');
        #$this->object->addObject($o);
        $this->object->alias('Event', 'DHP_FW\EventInterface');
        $e = $this->object->get('Event');
        \PHPUnit_Framework_Assert::assertInstanceOf('DHP_FW\\Event', $e);
    }

    /**
     */
    public function testAddClassAlias(){
        $this->object->set('DHP_FW\EventInterface','DHP_FW\Event');
        $this->object->alias('Event','DHP_FW\EventInterface');
        $o = $this->object->get('Event');
        \PHPUnit_Framework_Assert::assertInstanceOf('DHP_FW\Event', $o);
    }

    public function testAddObject(){
        $o = new \DHP_FW\Event();
        $this->object->set('Event',$o);
        $o = $this->object->get('Event');
        \PHPUnit_Framework_Assert::assertInstanceOf('DHP_FW\\Event', $o);
    }

    /**
     * @covers DHP_FW\dependencyInjection\DI::instantiateObject
     */
    public function testInstantiateObject(){
        $this->object->set('DHP_FW\AppInterface','DHP_FW\App');
        $this->object->set('DHP_FW\RequestInterface','DHP_FW\Request')->setArguments( array('method' => 'GET', 1 => '/urlofrequest'));
        $this->object->set('DHP_FW\ControllerInterface','DHP_FW\\Controller');
        $this->object->alias('Request','DHP_FW\RequestInterface');
        #var_dump(array_keys($this->object->container['class']));
        
        $o = $this->object->get('DHP_FW\App');
        \PHPUnit_Framework_Assert::assertInstanceOf('DHP_FW\App', $o);
        
        $c = $this->object->get('DHP_FW\Controller');
        \PHPUnit_Framework_Assert::assertInstanceOf('DHP_FW\Controller', $c);
    }

    public function testSameObjectIsLoaded(){
        $e = $this->object->get('DHP_FW\Event');
        $ref = spl_object_hash($e);
        $this->object->set('event',$e);
        $this->object->alias('masta','event');
        \PHPUnit_Framework_Assert::assertEquals($ref,spl_object_hash($this->object->get('event')));
        \PHPUnit_Framework_Assert::assertEquals($ref,spl_object_hash($this->object->get('DHP_FW\Event')));
        \PHPUnit_Framework_Assert::assertEquals($ref,spl_object_hash($this->object->get('masta')));
    }
}
