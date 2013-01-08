<?php
declare(encoding = "UTF8") ;
namespace DHP_FW;
use DHP_FW\Response;
use DHP_FW\Request;
/**
 * User: Henrik Pejer mr@henrikpejer.com
 * Date: 2013-01-06 10:20
 */
class Controller {

    protected $request,$response;

    public function __construct(Request $request,Response $response){
        $this->request = $request;
        $this->response = $response;
    }
}