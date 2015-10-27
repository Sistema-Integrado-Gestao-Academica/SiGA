<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/data_types/StudentRegistration.php");
require_once(APPPATH."/exception/StudentRegistrationException.php");

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

}
