<?php

abstract class UnitCaseTest extends PHPUnit_Framework_TestCase{

    protected $ci;
    protected $testClass;

    private function classUnderTest($child){
        $className = get_class($child);

        $name = str_replace("Test", "", $className);
        $lowerName = strtolower($name);

        $controllerfile = APPPATH."controllers/".$lowerName.".php";

        if(file_exists($controllerfile)){

            require_once $controllerfile;
        }else{
            // show_error
        }

        $this->testClass = new $name();
    }
    
    public function setUp() {   

        $this->classUnderTest($this);

        $this->ci =& get_instance();
    }
}
