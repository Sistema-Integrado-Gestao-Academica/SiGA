<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class SecretaryAjax extends MX_Controller {

    public function searchStudentsByEnrollment(){

        $enrollment = $this->input->post("enrollment");
        $course = $this->input->post("course");

        $this->load->model("student/student_model");
        $studentsIds = $this->student_model->getUserByEnrollment($enrollment, TRUE);
        
        $this->searchStudentsByIds($studentsIds, $course, TRUE);

    }

    public function searchStudentsByName(){
       
        $name = $this->input->post("name");
        $course = $this->input->post("course");

        $this->load->model("student/student_model");
        $studentsIds = $this->student_model->getStudentByName($name);
        
        $this->searchStudentsByIds($studentsIds, $course);
    }

    private function searchStudentsByIds($studentsIds, $course, $idIsEnrollment = FALSE){
       
        $students = array();
        if($studentsIds !== FALSE){

            foreach ($studentsIds as $studentId) {
                $id = $studentId['id'];
                $student = $this->student_model->getStudentById($id, $course);
                if($student !== FALSE){
                    if($idIsEnrollment){
                        $key = $student[0]['enrollment'];
                    }
                    else{
                        $key = $student[0]['name'];
                    }
                    $students[$key] = $student[0];                        
                }
            }
            $this->load->module("program/course");
            $students = $this->course->addStatusCourseStudents($students);
        }

        ksort($students);
        displayStudentsTable($students, $course);

    }
}
