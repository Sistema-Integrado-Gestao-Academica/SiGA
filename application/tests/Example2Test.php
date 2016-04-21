<?php

class Example2Test extends PHPUnit_Framework_TestCase { 		
    
	public function testFALSE() {
		$this->assertFalse(FALSE);
	}

	public function testTRUE() {
		$this->assertTrue(TRUE);
	}
}