<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/PermissionConstants.php");

class Project extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('program/project_model');
    }

    public function index(){

        $session = getSession();
        $userId = $session->getUserData()->getId();

        $projects = $this->project_model->getProjects($userId, TRUE);

        $data = array(
            'projects' => $projects
        );

        loadTemplateSafelyByPermission(
            PermissionConstants::ACADEMIC_PROJECT_PERMISSION,
            "program/project/index",
            $data
        );
    }
}