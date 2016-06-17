<?php

require_once 'UnitCaseTest.php';

class PhoneTest extends UnitCaseTest { 

	public function setUp(){
		parent::setUp();
	}

	public function testArg(){

		$this->assertEquals(TRUE, TRUE);
	}
}