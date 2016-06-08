<?php

require_once 'UnitCaseTest.php';

class CourseTest extends UnitCaseTest { 

	private $arg;

	public function setUp(){
		parent::setUp();
		$this->arg = array(1,2,3,4);
	}

	public function testCiInstance() {
		
		$expected =& get_instance();

		$this->assertEquals($this->ci, $expected);
	}

	public function testGetCourseById() {
		
		$expected = NULL;

		$course = $this->testClass->getCourseById(1);

		$this->assertEquals($course["course_name"], $expected);
	}

	public function testArg(){

		$this->assertEquals($this->arg, array(1,2,3,4));
	}
}