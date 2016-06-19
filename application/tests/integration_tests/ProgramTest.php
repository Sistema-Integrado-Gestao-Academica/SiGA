<?php

require 'IntegrationTestCase.php';
require_once APPPATH."/controllers/course.php";

class CourseTest extends IntegrationTestCase{
	
	protected $ci;
	private $course;

	public function setUp(){
		parent::setUp();
		$this->course = new Course();
	}

	public function testProgram(){

		$course = $this->course->getCourseById(1);
		
		$this->assertEquals($course['course_name'], "Engenharia de Software");
	}

	public function testProgram2(){

		$this->course->deleteCourseFromDb(1);

		$foundCourse = $this->course->getCourseById(1);
		
		$this->assertEquals($foundCourse, FALSE);
	}

	public function testProgram3(){
		
		$foundCourses = $this->course->listAllCourses();

		$this->assertEquals(count($foundCourses), 1);
	}
}