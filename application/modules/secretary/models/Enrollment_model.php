<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enrollment_model extends CI_Model {

    const COURSE_STUDENT_TABLE = "course_student";
    const ID_COURSE_COLUMN = "id_course";
    const ID_STUDENT_COLUMN = "id_user";
    const ENROLLMENT_COLUMN = "enrollment";

    const COULDNT_UPDATE_ENROLLMENT = "Não foi possível atualizar a matrícula informada. Cheque os dados informados e tente novamente";
    const ENROLLMENT_ALREADY_IN_USE = "A matrícula informada já está sendo utilizada.";

    public function enrollStudentIntoCourse($courseId, $studentId){
        
        $this->load->model("program/semester_model");
        $current_semester = $this->semester_model->getCurrentSemester();
        $enroll_semester = $current_semester['id_semester']; 

        $enrollment = "INSERT INTO course_student (id_course, id_user, enroll_date, enroll_semester)
                       VALUES ({$courseId}, {$studentId}, NOW(), {$enroll_semester})";

        $success = $this->db->query($enrollment);

        return $success;
    }

    public function saveRegistration($registration, $course, $student){
        
        $foundRegistration = $this->getRegistration($registration->getRegistration());

        if($foundRegistration === FALSE){

            $this->db->trans_start();

            $this->db->where(self::ID_COURSE_COLUMN, $course);
            $this->db->where(self::ID_STUDENT_COLUMN, $student);
            $this->db->update(self::COURSE_STUDENT_TABLE, array(
                self::ENROLLMENT_COLUMN => $registration->getRegistration()
            ));

            $this->db->trans_complete();

            if($this->db->trans_status() === FALSE){
                throw new StudentRegistrationException(self::COULDNT_UPDATE_ENROLLMENT);
            }
        }else{
            
            throw new StudentRegistrationException(self::ENROLLMENT_ALREADY_IN_USE);
        }
    }

    public function checkIfRegistrationExists($registration){

        $foundEnrollment = $this->getRegistration($registration);

        if($foundEnrollment !== FALSE){
            $exists = TRUE;
        }else{
            $exists = FALSE;
        }

        return $exists;
    }

    private function getRegistration($registration){

        $registration = $this->db->get_where(self::COURSE_STUDENT_TABLE, array(self::ENROLLMENT_COLUMN => $registration))->row_array();

        $registration = checkArray($registration);

        return $registration;
    }

    public function getProgramCoursesOfSecretary($programId, $userId){

        $this->db->distinct();
        $this->db->select("course.id_course");
        $this->db->from("course");
        $this->db->join("secretary_course", 'secretary_course.id_course = course.id_course');
        $this->db->where("course.id_program", $programId);
        $this->db->where("secretary_course.id_user", $userId);
        $courses = $this->db->get()->result_array();

        $courses = checkArray($courses);

        return $courses;
    }   
}
