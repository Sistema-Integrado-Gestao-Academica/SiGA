<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('masterdegree.php');

class PostGraduation extends CI_Controller {

	public function savePostGraduationCourse($post_graduation_type, $commonAttrs, $specificsAttrs, $secretary){
		
		define("ACADEMIC_PROGRAM", "academic_program");
		define("PROFESSIONAL_PROGRAM", "professional_program");

		switch($post_graduation_type){
			// In this case, if it is an academic program, it is a master_degree.
			case ACADEMIC_PROGRAM:
				$master_degree = new MasterDegree();
				$insertionStatus = $master_degree->saveMasterDegreeCourse($commonAttrs, $specificsAttrs, $secretary);
				break;

			case PROFESSIONAL_PROGRAM:
				break;

			default:
				
				break;
		}

		return $insertionStatus;
	}
}
