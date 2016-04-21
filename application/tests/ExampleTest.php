<?php

class ExampleTest extends PHPUnit_Framework_TestCase { 	

    private $CI; 	
    
    public function setUp() { 	

		$this->CI =& get_instance();
	}

	public function testFALSE() {
		$this->assertFalse(FALSE);
	}

	public function testTRUE() {
		$this->assertTrue(TRUE);
	}
}