<?php

require_once APPPATH.'/tests/TestException.php';

abstract class UnitCaseTest extends PHPUnit_Framework_TestCase{

    protected $ci;
    protected $testClass;

    private function classUnderTest($child){
        $className = get_class($child);

        $name = str_replace("Test", "", $className);
        $capitalizeName = ucfirst($name);

        $it = new RecursiveDirectoryIterator(DOMAINPATH);
        $file_exists = FALSE;

        foreach (new RecursiveIteratorIterator($it) as $file) {
            $file_name = $file->getFileName();
            if($file_name == $capitalizeName.".php"){
                $file_exists = TRUE;
                $file_path = $file->getPathName();
                break; 
            }
        }

        if ($file_exists) {
            require_once $file_path;
        }
        else{
            throw new TestException("File could not be found", 0);
        }
    }
    
    public function setUp() {   

        $this->classUnderTest($this);
        $this->ci =& get_instance();
    }
}
