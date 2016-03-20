<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("secretary.php");
require_once("course.php");
require_once("program.php");

require_once(APPPATH."/constants/PermissionConstants.php");

class SelectiveProcess extends CI_Controller {

    public function index() {
        
        $secretary = new Secretary();

        $programsAndCourses = $secretary->getSecretaryPrograms();
        $programs = $programsAndCourses['programs'];

        $data = array(
            'programs' => $programs
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "selection_process/index", $data);
    }

    public function programCourses($programId){

        $session = $this->session->userdata("current_user");
        $userData = $session['user'];
        $secretaryId = $userData['id'];

        $courseController = new Course();
        $secretaryCourses = $courseController->getCoursesOfSecretary($secretaryId);

        $program = new Program();
        $programCourses = $program->getProgramCourses($programId);

        $courses = array();
        foreach($secretaryCourses as $secretaryCourse){
            
            foreach($programCourses as $programCourse){
                
                if($secretaryCourse == $programCourse){
                    $courses[] = $programCourse;
                }
            }
        }

        $data = array(
            'courses' => $courses
        );

        loadTemplateSafelyByPermission(PermissionConstants::SELECTION_PROCESS_PERMISSION, "selection_process/program_courses", $data);
    }

    public function openSelectiveProcess(){

    }

}
