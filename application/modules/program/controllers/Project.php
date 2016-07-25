<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/PermissionConstants.php");
require_once(MODULESPATH."program/exception/ProjectException.php");
require_once(APPPATH."data_types/basic/StartEndDate.php");

class Project extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('program/project_model');
    }

    public function index(){

        $session = getSession();
        $userId = $session->getUserData()->getId();
        $userGroups = $session->getUserData()->getGroups();

        $projects = $this->project_model->getProjects($userId, TRUE);

        $this->load->model('program/program_model');
        $programs = $this->program_model->getUserProgram($userId, $userGroups);
        $data = array(
            'projects' => $projects,
            'programs' => $programs
        );

        loadTemplateSafelyByPermission(
            PermissionConstants::ACADEMIC_PROJECT_PERMISSION,
            "program/project/index",
            $data
        );
    }

    public function projectTeam($projectId){

        $project = $this->project_model->getProject($projectId);
        $members = $this->project_model->getProjectMembers($projectId);

        $data = array(
            'project' => $project,
            'members' => $members
        );

        loadTemplateSafelyByPermission(
            PermissionConstants::ACADEMIC_PROJECT_PERMISSION,
            "program/project/team",
            $data
        );
    }

    public function addMemberToTeam(){

        $userId = $this->input->post("user");
        $projectId = $this->input->post("project");

        try{
            $this->project_model->addMemberToTeam($projectId, $userId);
            $status = "success";
            $message = "Membro adicionado com sucesso!";
        }catch(ProjectException $e){
            $status = "warning";
            $message = $e->getMessage();
        }

        getSession()->showFlashMessage($status, $message);
        redirect("project_team/{$projectId}");
    }

    public function newProject(){

        $dataIsOk = $this->validateProjectData();
        if($dataIsOk){

            $projectName = $this->input->post('project_name');
            $financing = $this->input->post('financing');
            $startDate = $this->input->post('project_start_date');
            $endDate = $this->input->post('project_end_date');
            $studyObject = $this->input->post('study_object');
            $justification = $this->input->post('justification');
            $procedures = $this->input->post('procedures');
            $results = $this->input->post('expected_results');
            $program = $this->input->post('programs');

            $dates = new StartEndDate($startDate, $endDate);

            $project = array(
                Project_model::NAME_COLUMN => $projectName,
                Project_model::START_DATE_COLUMN => $dates->getYMDStartDate(),
                Project_model::PROGRAM_COLUMN => $program
            );

            // Check is the attrs are empty and set them to NULL if so
            $project[Project_model::FINANCING_COLUMN] = empty($financing) ? NULL : $financing;
            $project[Project_model::END_DATE_COLUMN] = empty($endDate) ? NULL : $dates->getYMDEndDate();
            $project[Project_model::STUDY_OBJECT_COLUMN] = empty($studyObject) ? NULL : $studyObject ;
            $project[Project_model::JUSTIFICATION_COLUMN] = empty($justification) ? NULL : $justification;
            $project[Project_model::PROCEDURES_COLUMN] = empty($procedures) ? NULL : $procedures;
            $project[Project_model::EXPECTED_RESULTS_COLUMN] = empty($results) ? NULL : $results;

            $session = getSession();
            $userId = $session->getUserData()->getId();

            try{
                $this->project_model->save($project, $userId);
                $status = "success";
                $message = "Projeto '{$projectName}' salvo com sucesso!";
            }catch(ProjectException $e){
                $status = "danger";
                $message = $e->getMessage();
            }

            $session->showFlashMessage($status, $message);
            redirect('academic_projects');
        }else{
            $this->index();
        }
    }

    private function validateProjectData(){

        $this->load->library("form_validation");
        $this->form_validation->set_rules("project_start_date", "Data de início", "required|valid_date_interval");
        $this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
        $success = $this->form_validation->run();

        return $success;
    }
}