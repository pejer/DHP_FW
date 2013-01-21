<?php
declare( encoding = "UTF8" ) ;
namespace DHP_FW;
/**
 *
 * Created by: Henrik Pejer, mr@henrikpejer.com
 * Date: 2013-01-17 16:44
 *
 */ 
interface ParameterBagInterface {
    /**
     * A bag with values. or, parameters
     *
     * @param array $values initial values
     */
    function __construct(array $values, \DHP_FW\EventInterface $event);

    /**
     * Use this to get the value
     *
     * @param $name The name of the parameter we want
     * @return mixed Returns value or NULL if not found
     */
    function __get($name);

    /**
     * We use this to set values in the parameter bag
     *
     * @param $name name of the parameter to set
     * @param $value new value of the parameter
     * @return mixed returns the value set
     */
    function __set($name,$value);
}
