<?php
namespace DHP_FW;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-11 at 13:46:31.
 */
class ResponseTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var Response
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Response(new Event());
        # $this->object->supressHeaders();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }

    /**
     *
     */
    public function testSend() {
        // $this->markTestSkipped('must be revisited.');
        if($this->object->cacheAvailable()){
            \PHPUnit_Framework_Assert::assertFalse($this->object->checkCache());
            \PHPUnit_Framework_Assert::assertFalse($this->object->cacheSent());
        }
        $output    = 'this worked';
        $arrayData = array(1, 2, 3);
        $output .= json_encode($arrayData, \JSON_FORCE_OBJECT | \JSON_NUMERIC_CHECK);


        $arrayWithNamedKeys = array(
            'Henrik' => 'Pejer',
            'Age'    => '32',
        );
        $output .= json_encode($arrayWithNamedKeys, \JSON_FORCE_OBJECT | \JSON_NUMERIC_CHECK);
        $object            = new \stdClass();
        $object->something = 'something';
        $output .= json_encode($object, \JSON_FORCE_OBJECT | \JSON_NUMERIC_CHECK);
        if($this->object->cacheAvailable()){
            $output .= "array(2) {
      'headers' =>
      array(2) {
        [0] =>
        string(15) \"HTTP/1.1 200 OK\"
        [1] =>
        string(14) \"Status: 200 OK\"
      }
      'data' =>
      string(0) \"\"
    }
    ";

        }else{
            $output .= 'bool(false)
';
        }

        $this->expectOutputString($output);

        $this->object->send('this worked');
        $this->object->send(123,$arrayData);
        \PHPUnit_Framework_Assert::assertAttributeEquals(array('Status'=>'123'),'headers',$this->object);
        $this->object->send($arrayWithNamedKeys);
        $this->object->send($object);
        $this->object->send(fopen(__FILE__, 'r'));
        var_dump(\app\DI()->get('DHP_FW\\cache\\Cache')->bucket('app')->get('uri__data'));
        if($this->object->cacheAvailable()){
            \PHPUnit_Framework_Assert::assertTrue($this->object->checkCache());
        }

    }

    public function testHeader() {
        $this->object->header('Henrik', 'Pejer')->header('Something kind of usefull');
        $this->object->supressHeaders(TRUE);
        $this->object->send(200);
        \PHPUnit_Framework_Assert::assertAttributeEquals(array('Henrik' => 'Pejer','Something-Kind-Of-Usefull'=> NULL,'Status'=>'200 OK'), 'headers', $this->object);
        
    }

    /**
     */
    public function testStatus() {
        $this->object->status(200);
        \PHPUnit_Framework_Assert::assertAttributeEquals(array('Status' => '200 OK'), 'headers', $this->object);
    }

    /**
     */
    public function testSendFile() {
        $fileToTest = file_get_contents(__FILE__);
        $this->expectOutputString($fileToTest . $fileToTest);
        $this->object->sendFile(__FILE__, 'text/x-c++', 'downloadFileName.php');
        $headers_list_test = array(
            #"Content-description: File Transfer",
            "Content-type: text/x-c++",
            #"Content-disposition: attachment; filename=\"downloadFileName.php\"",
            "Content-transfer-encoding: binary",
            "HTTP/1.1 200 OK",
            "Status: 200 OK"
        );
        \PHPUnit_Framework_Assert::assertAttributeEquals($headers_list_test, 'headerDataSent', $this->object);
        $this->object->sendFile(__FILE__);
        \PHPUnit_Framework_Assert::assertAttributeEquals($headers_list_test, 'headerDataSent', $this->object);
    }

    /**
     */
    public function testDownloadFile() {
        $fileToTest = file_get_contents(__FILE__);
        $this->expectOutputString($fileToTest);
        $this->object->downloadFile(__FILE__, 'text/x-c++', 'downloadFileName.php');
        $headers_list_test = array(
            "Content-type: text/x-c++",
            "Content-transfer-encoding: binary",
            "HTTP/1.1 200 OK",
            "Status: 200 OK",
            "Content-description: File Transfer",
          "Content-disposition: attachment; filename=\"downloadFileName.php\""

        );
        \PHPUnit_Framework_Assert::assertAttributeEquals($headers_list_test, 'headerDataSent', $this->object);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage File does not exist
     */
    public function testSendFileWithNoneExistingFile() {
        $this->object->sendFile('/File/Does/Not/Exist');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Unable to read file
     */
    public function testSendFileWithNotReadable() {
        $fileToTest = __DIR__.'/NotReadable.txt';
        touch($fileToTest);
        chmod($fileToTest,0000);
        \PHPUnit_Framework_Assert::assertFalse(is_readable($fileToTest));
        $this->object->sendFile($fileToTest);
    }

    /**
     * @depends testSendFileWithNotReadable
     */
    public function testDeleteFileAfterSend(){
        $fileToTest = __DIR__.'/NotReadable.txt';
        chmod($fileToTest,0777);
        unlink($fileToTest);
        \PHPUnit_Framework_Assert::assertFileNotExists($fileToTest);
    }
    
    public function testSurpressHeaders() {
        $this->object->supressHeaders();
        if (is_callable('xdebug_headers_list')) {
            \PHPUnit_Framework_Assert::assertEquals(array(), xdebug_headers_list());
        }
        else {
            \PHPUnit_Framework_Assert::assertAttributeEquals(TRUE, 'supressHeader', $this->object);
        }
    }

    /**
     */
    public function testRedirect() {
        $this->object->redirect('/something/something');
        \PHPUnit_Framework_Assert::assertAttributeEquals(array('Status' => '301 Moved Permanently', 'Location' => '/something/something'), 'headers', $this->object);
    }
}
 