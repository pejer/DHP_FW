<?php
namespace DHP_FW;
require_once __DIR__.'/../lib/DHP_FW/App.php';
/**
 * Generated by PHPUnit_SkeletonGenerator on 2013-01-01 at 06:21:34.
 */
class AppTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var App
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->request = new Request('GET','/');
        $this->DI = new dependencyInjection\DI(new Event());
        $this->object = new App($this->request, $this->DI);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }

    public function testGet() {
        $this->object->get('/test', function () {
            return TRUE;
        });
        $routes = $this->object->routes();
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_GET, $routes);
        \PHPUnit_Framework_Assert::assertArrayHasKey('/test', $routes[App::HTTP_METHOD_GET]);
        \PHPUnit_Framework_Assert::assertEquals(function () {
            return TRUE;
        }, $routes[App::HTTP_METHOD_GET]['/test']);
    }
    public function testHead() {
        $this->object->head('/test', function () {
            return TRUE;
        });
        $routes = $this->object->routes();
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_HEAD, $routes);
        \PHPUnit_Framework_Assert::assertArrayHasKey('/test', $routes[App::HTTP_METHOD_HEAD]);
        \PHPUnit_Framework_Assert::assertEquals(function () {
            return TRUE;
        }, $routes[App::HTTP_METHOD_HEAD]['/test']);
    }

    public function testVerb(){
        $this->object->verb(array(App::HTTP_METHOD_HEAD,App::HTTP_METHOD_GET),'/test', function () {
            return TRUE;
        });
        $routes = $this->object->routes();
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_HEAD, $routes);
        \PHPUnit_Framework_Assert::assertArrayHasKey('/test', $routes[App::HTTP_METHOD_HEAD]);
        \PHPUnit_Framework_Assert::assertEquals(function () {
            return TRUE;
        }, $routes[App::HTTP_METHOD_HEAD]['/test']);
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_GET, $routes);
        \PHPUnit_Framework_Assert::assertArrayHasKey('/test', $routes[App::HTTP_METHOD_GET]);
        \PHPUnit_Framework_Assert::assertEquals(function () {
            return TRUE;
        }, $routes[App::HTTP_METHOD_GET]['/test']);


    }

    public function testPost() {
        $this->object->post('/test', function () {
            return TRUE;
        });
        $routes = $this->object->routes();
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_POST, $routes);
        \PHPUnit_Framework_Assert::assertArrayHasKey('/test', $routes[App::HTTP_METHOD_POST]);
        \PHPUnit_Framework_Assert::assertEquals(function () {
            return TRUE;
        }, $routes[App::HTTP_METHOD_POST]['/test']);
    }

    public function testDelete() {
        $this->object->delete('/test', function () {
            return TRUE;
        });
        $routes = $this->object->routes();
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_DELETE, $routes);
        \PHPUnit_Framework_Assert::assertArrayHasKey('/test', $routes[App::HTTP_METHOD_DELETE]);
        \PHPUnit_Framework_Assert::assertEquals(function () {
            return TRUE;
        }, $routes[App::HTTP_METHOD_DELETE]['/test']);
    }

    public function testPut() {
        $this->object->put('/test', function () {
            return TRUE;
        });
        $routes = $this->object->routes();
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_PUT, $routes);
        \PHPUnit_Framework_Assert::assertArrayHasKey('/test', $routes[App::HTTP_METHOD_PUT]);
        \PHPUnit_Framework_Assert::assertEquals(function () {
            return TRUE;
        }, $routes[App::HTTP_METHOD_PUT]['/test']);
    }

    public function testAll() {
        $this->request->setMethod(App::HTTP_METHOD_GET);
        $this->request->setUri('/test');
        $this->object = new App($this->request, $this->DI);
        
        $this->object->any('test', function () {
            return TRUE;
        });
        $routes = $this->object->routes();
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_ANY, $routes);
        \PHPUnit_Framework_Assert::assertArrayHasKey('test', $routes[App::HTTP_METHOD_ANY]);        
        \PHPUnit_Framework_Assert::assertTrue($this->object->start());
    }

    public function testRoutes() {
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_GET,$this->object->routes());
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_POST,$this->object->routes());
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_PUT,$this->object->routes());
        \PHPUnit_Framework_Assert::assertArrayHasKey(App::HTTP_METHOD_DELETE,$this->object->routes());
    }

    public function testEnabledDisbled(){
        \PHPUnit_Framework_Assert::assertFalse($this->object->enabled('Henrik'));
        $this->object->enable('Henrik');
        \PHPUnit_Framework_Assert::assertTrue($this->object->enabled('Henrik'));
        $this->object->disable('Henrik');
        \PHPUnit_Framework_Assert::assertFalse($this->object->enabled('Henrik'));
    }

    public function testStart(){
        \PHPUnit_Framework_Assert::assertNull($this->object->start());
        $this->object->get('',function(){
           return 'working';
        });
        \PHPUnit_Framework_Assert::assertEquals('working',$this->object->start());
        eval('namespace app\\controllers;class Blog extends \\DHP_FW\Controller{ function index(){ return "working from controller";}}');
        $this->object->get('',function (){return array('controller'=>'Blog','method'=>'index');});
        \PHPUnit_Framework_Assert::assertEquals('working from controller',$this->object->start());
        $this->object->request->setMethod('NONEXISTING');
        \PHPUnit_Framework_Assert::assertNull($this->object->start());
    }

    public function testParam(){
        $this->object->param('user',function($userId){
            return $userId == 1?TRUE:FALSE;
        });
        \PHPUnit_Framework_Assert::assertAttributeEquals(
            array('user' => function($userId){
                        return $userId == 1?TRUE:FALSE;
                    })

        ,'customParamTypes',$this->object);
    }
}
