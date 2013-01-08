<?php
declare(encoding = "UTF8") ;
namespace DHP_FW;
/**
 * User: Henrik Pejer mr@henrikpejer.com
 * Date: 2013-01-01 05:34
 */
class Request {

    private $uri = NULL;
    private $method = NULL;
    private $headers = NULL;

    public function __construct($method = NULL, $uri = NULL) {
        $this->method = $method === NULL ? $this->generateMethod() : $method;
        $this->uri    = $uri === NULL ? $this->generateUri() : ltrim($uri, '/');
        $this->parseRequestHeaders();
    }

    public function getMethod() {
        return $this->method;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function setUri($uri) {
        $this->uri = $uri;
    }

    public function getUri() {
        return $this->uri;
    }

    public function header($name){
        return isset($this->headers[$name])?$this->headers[$name]:NULL;
    }

    public function getHeaders(){
        return $this->headers;
    }

    protected function generateMethod() {
        $method = 'GET';
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $method = $_SERVER['REQUEST_METHOD'];
        }
        return $method;
    }

    protected function generateUri() {
        $uri = NULL;
        if (isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
        }
        $uri = ltrim($uri, '/');
        return $uri;
    }

    protected function parseRequestHeaders() {
        $this->headers = array();
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $header           = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $this->headers[$header] = $value;
            }
        }
    }
}