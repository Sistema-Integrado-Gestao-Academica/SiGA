<?php

abstract class UnitCaseTest extends PHPUnit_Framework_TestCase{

    protected $ci;
    protected $testClass;

    private function classUnderTest($child){
        $className = get_class($child);

        $name = str_replace("Test", "", $className);
        $lowerName = strtolower($name);

        require_once APPPATH."controllers/".$lowerName.".php";

        $this->testClass = new $name();
    }
    
    public function setUp() {   

        $this->classUnderTest($this);

        $this->ci =& get_instance();
    }
}
