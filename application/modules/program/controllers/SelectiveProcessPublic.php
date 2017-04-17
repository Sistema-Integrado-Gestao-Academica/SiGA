
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/auth/constants/PermissionConstants.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");
require_once(MODULESPATH."/program/exception/SelectionProcessException.php");
require_once(MODULESPATH."/program/domain/selection_process/SelectionProcess.php");

class SelectiveProcessPublic extends MX_Controller {

    public function __construct(){
        $this->load->model('program/selectiveprocess_model', 'process_model');
        $this->load->model('program/selectiveprocessconfig_model', 'process_config_model');
    }

    // List all open selective processes
    public function index(){

        $this->load->model("program/selectiveprocess_model", "process_model");
        $openSelectiveProcesses = $this->process_model->getOpenSelectiveProcesses();
        $courses = $this->getCoursesName($openSelectiveProcesses);

        $data = [
            'openSelectiveProcesses' => $openSelectiveProcesses,
            'courses' => $courses
        ];

        loadTemplateSafelyByPermission(
            PermissionConstants::PUBLIC_SELECTION_PROCESS_PERMISSION,
            "program/selection_process_public/index",
            $data
        );
    }

    private function getCoursesName($openSelectiveProcesses){
        $courses = array();
        if(!empty($openSelectiveProcesses)){
            $this->load->model("program/course_model");
            foreach ($openSelectiveProcesses as $process) {
                $courseId = $process->getCourse();
                $course = $this->course_model->getCourseName($courseId);
                $processId = $process->getId();
                $courses[$processId] = $course;
            }
        }
        return $courses;
    }
}
