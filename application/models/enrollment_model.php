<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enrollment_model extends CI_Model {

    public function checkIfEnrollmentExists($enrollment){

        $this->db->select("enrollment");
        $foundEnrollment = $this->db->get_where("course_student", array("enrollment" => $enrollment))->row_array();

        $foundEnrollment = checkArray($foundEnrollment);

        if($foundEnrollment !== FALSE){
            $exists = TRUE;
        }else{
            $exists = FALSE;
        }

        return $exists;
    }
}
