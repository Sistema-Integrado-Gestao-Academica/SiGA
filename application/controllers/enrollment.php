<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/data_types/StudentRegistration.php");
require_once(APPPATH."/exception/StudentRegistrationException.php");
require_once("course.php");
require_once("module.php");
require_once(APPPATH."/constants/GroupConstants.php");

class Enrollment extends CI_Controller {

    public function newStudentEnrollmentNumber(){

        $enrollmentNumberExists = TRUE;

        while($enrollmentNumberExists){

            $studentRegistration = new StudentRegistration();
            $registration = $studentRegistration->getRegistration();

            $this->load->model('enrollment_model');
            $enrollmentNumberExists = $this->enrollment_model->checkIfEnrollmentExists($registration);
        }

        return $studentRegistration;
    }

    /*
     * Public method to enroll a student in active course course
     *
    */
    public function enrollStudent($courseId, $userId){

      $this->load->model('course_model');

      try{
        $studentRegistration = $this->newStudentEnrollmentNumber();
        $registration = $studentRegistration->getRegistration();


        $enrollment = "INSERT INTO course_student (id_course, id_user,
           enroll_date, enrollment)
           VALUES ({$courseId}, {$userId}, NOW(), {$registration})";

        $this->course_model->enrollStudentIntoCourse($enrollment);

        $course = new Course();
        $this->addStudentGroupToNewStudent($userId);

        $status = "success";
        $message = "Aluno matriculado com sucesso. Matrícula Nº <b>".$studentRegistration->getFormattedRegistration()."</b>.";
      }catch(StudentRegistrationException $e){
        $status = "danger";
        $message = $e->getMessage();
      }

      $this->session->set_flashdata($status, $message);
      redirect("secretary_home");
    }

    private function addStudentGroupToNewStudent($userId){

  		$group = new Module();

  		$studentGroup = GroupConstants::STUDENT_GROUP;
  		$group->addGroupToUser($studentGroup, $userId);

  		$guestGroup = GroupConstants::GUEST_GROUP;
  		$group->deleteGroupOfUser($guestGroup, $userId);
  	}

}
