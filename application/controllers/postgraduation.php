<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('masterdegree.php');
require_once('doctorate.php');
require_once('graduation.php');
require_once('ead.php');
require_once(APPPATH."/exception/CourseNameException.php");
require_once(APPPATH."/exception/CourseException.php");
require_once(APPPATH."/exception/MasterDegreeException.php");

class PostGraduation extends CI_Controller {

	public function savePostGraduationCourse($post_graduation_type, $commonAttributes, $specificsAttributes, $secretary){
		
		define("ACADEMIC_PROGRAM", "academic_program");
		define("PROFESSIONAL_PROGRAM", "professional_program");

		switch($post_graduation_type){
			// In this case, if it is an academic program, it is a master_degree.
			case ACADEMIC_PROGRAM:
				$master_degree = new MasterDegree();
				$insertionStatus = $master_degree->saveMasterDegreeAcademicCourse($commonAttributes, $specificsAttributes, $secretary);
				break;

			case PROFESSIONAL_PROGRAM:
				$master_degree = new MasterDegree();
				$insertionStatus = $master_degree->saveMasterDegreeProfessionalCourse($commonAttributes, $specificsAttributes, $secretary);
				break;

			default:
				
				break;
		}

		return $insertionStatus;
	}

	public function updatePostGraduationCourse($idCourseToUpdate, $post_graduation_type, $commonAttributes, $specificsAttributes, $secretary){
		
		define("ACADEMIC_PROGRAM", "academic_program");
		define("PROFESSIONAL_PROGRAM", "professional_program");

		switch($post_graduation_type){
			
			case ACADEMIC_PROGRAM:

				try{
					$this->updateMasterDegreeAcademicCourse(
						$idCourseToUpdate, $commonAttributes,
						$specificsAttributes, $secretary
					);
					
				}catch(CourseNameException $caughtException){
					throw $caughtException;
				}catch(CourseException $caughtException){
					throw $caughtException;
				}

				break;

			case PROFESSIONAL_PROGRAM:
				
				try{
				
					$master_degree = new MasterDegree();
					$insertionStatus = $master_degree->updateMasterDegreeProfessionalCourse(
						$idCourseToUpdate, $commonAttributes,
						$specificsAttributes, $secretary
					);
				}catch(CourseNameException $caughtException){
					throw $caughtException;
				}catch(CourseException $caughtException){
					throw $caughtException;
				}
				
				break;

			default:
				
				break;
		}
	}

	private function updateMasterDegreeAcademicCourse($idCourseToUpdate, $commonAttributes, $specificsAttributes, $secretary){
		
		try{
			$master_degree = new MasterDegree();
			$master_degree->updateMasterDegreeAcademicCourse(
				$idCourseToUpdate, $commonAttributes,
				$specificsAttributes, $secretary
		);
			
		}catch(CourseNameException $caughtException){
			throw $caughtException;
		}catch(CourseException $caughtException){
			throw $caughtException;
		}
	}
}
