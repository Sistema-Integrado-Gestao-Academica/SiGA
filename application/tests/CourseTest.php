<?php

require_once 'UnitCaseTest.php';

class CourseTest extends UnitCaseTest { 

	public function testCiInstance() {
		
		$expected =& get_instance();

		$this->assertEquals($this->ci, $expected);
	}

	public function testGetCourseById() {
		
		$expected = NULL;

		$course = $this->testClass->getCourseById(1);

		$this->assertEquals($course["course_name"], $expected);
	}
}