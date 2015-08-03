<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('course.php');
require_once(APPPATH."/constants/PermissionConstants.php");

class Secretary extends CI_Controller {

	public function courseTeachers($courseId){

		$course = new Course();
		$courseData = $course->getCourseById($courseId);

		$teachers = $course->getCourseTeachers($courseId);

		$data = array(
			'course' => $courseData,
			'teachers' => $teachers
		);

		loadTemplateSafelyByPermission(PermissionConstants::ENROLL_TEACHER_PERMISSION, 'secretary/course_teachers', $data);
	}


}

