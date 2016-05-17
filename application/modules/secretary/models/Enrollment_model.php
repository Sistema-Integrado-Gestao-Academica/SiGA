<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enrollment_model extends CI_Model {

    const COURSE_STUDENT_TABLE = "course_student";
    const ID_COURSE_COLUMN = "id_course";
    const ID_STUDENT_COLUMN = "id_user";
    const ENROLLMENT_COLUMN = "enrollment";

    const COULDNT_UPDATE_ENROLLMENT = "Não foi possível atualizar a matrícula informada. Cheque os dados informados e tente novamente";
    const ENROLLMENT_ALREADY_IN_USE = "A matrícula informada já está sendo utilizada.";

    public function enrollStudentIntoCourse($courseId, $studentId){

        $enrollment = "INSERT INTO course_student (id_course, id_user, enroll_date)
                       VALUES ({$courseId}, {$studentId}, NOW())";

        $this->db->query($enrollment);
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
}
