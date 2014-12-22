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
		
		$this->checkExistingCourseType($idCourseToUpdate);

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

	private function getCurrentCourseType($idCourse){
		$this->load->model('course_model');
		$currentCourseType = $this->course_model->getCourseTypeById($idCourse);

		return $currentCourseType;
	}

	private function checkExistingCourseType($idCourse){
		
		// define("GRADUATION", "graduation");
		// define("EAD", "ead");
		// define("ACADEMIC_PROGRAM", "academic_program");
		// define("PROFESSIONAL_PROGRAM", "professional_program");
		
		$currentCourseType = $this->getCurrentCourseType($idCourse);
		
		switch($currentCourseType){

			case GRADUATION:
				$graduation = new Graduation();
				// Erase the graduation data to create an academic_program one
				break;

			case EAD:
				$ead = new Ead();
				// Erase the EAD data to create an academic_program one
				break;

			case ACADEMIC_PROGRAM:
				// In this case, there is no updates to made.
				// The user want to update an academic_program to an academic_program
				break;

			case PROFESSIONAL_PROGRAM:
				// Erase the professional_program data to create an academic_program one
				$this->deleteExistingProfessionalProgram($idCourse);
				break;
	
			default:
				
				break;
		}
	}

	// Call this method to erase an existing professional_program course and its master degrees
	public function deleteExistingProfessionalProgram($idCourse){

	}

	// Call this method to erase an existing academic_program course and its master degrees and doctorates
	public function deleteExistingAcademicProgramForThisCourse($idCourse){
		$this->deleteExistingAcademicMasterDegree($idCourse);
		$this->deleteExistingDoctorate($idCourse);
		$this->deleteExistingAcademicProgram($idCourse);
	}

	private function deleteExistingAcademicMasterDegree($idCourse){

		try{
			$masterDegree = new MasterDegree();
			$masterDegree->deleteAcademicMasterDegree($idCourse);

		}catch(MasterDegreeException $caughtException){
			// Nothing to do because do not need to delete if not exists
		}
	}

	private function deleteExistingDoctorate($idCourse){

	}

	private function deleteExistingAcademicProgram($idCourse){

	}
}
