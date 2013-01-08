<?php
namespace DHP_FW;
require_once __DIR__ . '/../lib/DHP_FW/Event.php';
/**
 * Generated by PHPUnit_SkeletonGenerator on 2013-01-01 at 06:56:30.
 */
class EventTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var Event
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Event;
        if (!class_exists('\\testMe')) {
            /**
             * Yeah, I know, eval is evil but this works!
             */
            eval('class testMe{public function run(){return TRUE;}}');
        }
        $this->testObject = new \testMe();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }

    public function testTriggerError() {
        \PHPUnit_Framework_Assert::assertFalse($this->object->register('PHPUnit_test', 'notPossible'));
    }

    public function testTriggerNoArgument() {
        $this->object->register('PHPUnit_test', function () {
            return TRUE;
        });
        $ret = $this->object->trigger('PHPUnit_test');
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerWithObjectNoArgument() {
        $register = $this->object->register('PHPUnit_test', array($this->testObject, 'run'));
        \PHPUnit_Framework_Assert::assertTrue($register);
        $ret = $this->object->trigger('PHPUnit_test');
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerReturnEventAbort() {
        $this->object->register('PHPUnit_test', function () {
            return \DHP_FW\EVENT_ABORT;
        });
        $ret = $this->object->trigger('PHPUnit_test');
        \PHPUnit_Framework_Assert::assertNull($ret);
    }


    public function testTriggerOneArgument() {
        $this->object->register('PHPUnit_test', function (&$arg) {
            $arg = 'TEST OK!';
            return TRUE;
        });
        $argOne = 'NOT WORKING';
        $ret    = $this->object->trigger('PHPUnit_test', $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerObjectWithOneArgument() {
        eval('class testMeOne{public function run(&$arg){$arg = "TEST OK!";return TRUE;}}');
        $this->testObject = new \testMeOne();
        $this->object->register('PHPUnit_test', array($this->testObject, 'run'));
        $argOne = 'NOT WORKING';
        $ret    = $this->object->trigger('PHPUnit_test', $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerTwoArgument() {
        $this->object->register('PHPUnit_test', function ($nada, &$arg) {
            $arg = 'TEST OK!';
            return TRUE;
        });
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerObjectWithTwoArgument() {
        eval('class testMeTwo{public function run($nada,&$arg){$arg = "TEST OK!";return TRUE;}}');
        $this->testObject = new \testMeTwo();
        $this->object->register('PHPUnit_test', array($this->testObject, 'run'));
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerThreeArgument() {
        $this->object->register('PHPUnit_test', function ($nada, $nada, &$arg) {
            $arg = 'TEST OK!';
            return TRUE;
        });
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerFourArgument() {
        $this->object->register('PHPUnit_test', function ($nada, $nada, $nada, &$arg) {
            $arg = 'TEST OK!';
            return TRUE;
        });
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $nada, $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerFiveArgument() {
        $this->object->register('PHPUnit_test', function ($nada, $nada, $nada, $nada, &$arg) {
            $arg = 'TEST OK!';
            return TRUE;
        });
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $nada, $nada, $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerSixArgument() {
        $this->object->register('PHPUnit_test', function ($nada, $nada, $nada, $nada, $nada, &$arg) {
            $arg = 'TEST OK!';
            return TRUE;
        });
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $nada, $nada, $nada, $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerSevenArgument() {
        $this->object->register('PHPUnit_test', function ($nada, $nada, $nada, $nada, $nada, $nada, &$arg) {
            $arg = 'TEST OK!';
            return TRUE;
        });
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $nada, $nada, $nada, $nada, $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerObjectWithThreeArgument() {
        eval('class testMeThree{public function run($nada,$nada,&$arg){$arg = "TEST OK!";return TRUE;}}');
        $this->testObject = new \testMeThree();
        $this->object->register('PHPUnit_test', array($this->testObject, 'run'));
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }


    public function testTriggerObjectWithFourArgument() {
        eval('class testMeFour{public function run($nada,$nada,$nada,&$arg){$arg = "TEST OK!";return TRUE;}}');
        $this->testObject = new \testMeFour();
        $this->object->register('PHPUnit_test', array($this->testObject, 'run'));
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $nada, $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerObjectWithFiveArgument() {
        eval('class testMeFive{public function run($nada,$nada,$nada,$nada,&$arg){$arg = "TEST OK!";return TRUE;}}');
        $this->testObject = new \testMeFive();
        $this->object->register('PHPUnit_test', array($this->testObject, 'run'));
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $nada, $nada, $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerObjectWithSixArgument() {
        eval('class testMeSix{public function run($nada,$nada,$nada,$nada,$nada,&$arg){$arg = "TEST OK!";return TRUE;}}');
        $this->testObject = new \testMeSix();
        $this->object->register('PHPUnit_test', array($this->testObject, 'run'));
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $nada, $nada, $nada, $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }

    public function testTriggerObjectWithSevenArgument() {
        eval('class testMeSeven{public function run($nada,$nada,$nada,$nada,$nada,$nada,&$arg){$arg = "TEST OK!";return TRUE;}}');
        $this->testObject = new \testMeSeven();
        $this->object->register('PHPUnit_test', array($this->testObject, 'run'));
        $argOne = 'NOT WORKING';
        $nada   = '';
        $ret    = $this->object->trigger('PHPUnit_test', $nada, $nada, $nada, $nada, $nada, $nada, $argOne);
        \PHPUnit_Framework_Assert::assertEquals('TEST OK!', $argOne);
        \PHPUnit_Framework_Assert::assertTrue($ret);
    }


    /**
     */
    public function testRegister() {
        \PHPUnit_Framework_Assert::assertAttributeEmpty('events', $this->object);
        $this->object->register('PHPUnit_test', function () {
            return TRUE;
        });
        \PHPUnit_Framework_Assert::assertAttributeCount(1, 'events', $this->object);
        $eventsShouldEqual = array(
            'PHPUnit_test' => array(function () {
                return TRUE;
            })
        );
        \PHPUnit_Framework_Assert::assertAttributeEquals($eventsShouldEqual, 'events', $this->object);
    }
}