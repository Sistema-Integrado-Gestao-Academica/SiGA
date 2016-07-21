<?php

require_once(MODULESPATH."program/exception/ProjectException.php");

class Project_model extends CI_Model {

    public $TABLE = "academic_project";
    const ID_COLUMN = "id";
    const FINANCING_COLUMN = "financing";
    const NAME_COLUMN = "name";
    const START_DATE_COLUMN = "start_date";
    const END_DATE_COLUMN = "end_date";
    const STUDY_OBJECT_COLUMN = "study_object";
    const JUSTIFICATION_COLUMN = "justification";
    const PROCEDURES_COLUMN = "procedures";
    const EXPECTED_RESULTS_COLUMN = "expected_results";
    const PROGRAM_COLUMN = "program_id";

    const TEAM_TABLE = "project_team";

    const PROJECT_NAME_ALREADY_EXISTS = "O nome do projeto informado já existe no sistema.";

    public function getProjects($memberId, $coordinator=FALSE){

        $this->db->select('*');
        $this->db->from($this->TABLE);
        $this->db->join(self::TEAM_TABLE, "academic_project.id = project_team.id_project");
        $this->db->where("project_team.member", $memberId);
        if($coordinator){
            $this->db->where("project_team.coordinator", TRUE);
        }
        $projects = $this->db->get()->result_array();

        $projects = checkArray($projects);

        return $projects;
    }

    public function getProjectById($projectId){

        $this->db->select('*');
        $this->db->from($this->TABLE);
        $this->db->where(self::ID_COLUMN, $projectId);

        $project = $this->db->get()->result_array();

        $project = checkArray($project);

        return $project;
    }

    public function save($project, $coordinatorId){

        $projectName = $project[self::NAME_COLUMN];
        $nameExists = $this->checkIfProjectNameExists($projectName);
        if(!$nameExists){
            $this->db->insert($this->TABLE, $project);

            $this->saveCoordinator($project, $coordinatorId);
        }else{
            throw new ProjectException(self::PROJECT_NAME_ALREADY_EXISTS." Projeto informado: '{$projectName}'.");
        }
    }

    public function getProjectByProgram($programId){
        
        $this->db->select('*');
        $this->db->from($this->TABLE);
        $this->db->where("program_id", $programId);

        $projects = $this->db->get()->result_array();

        $projects = checkArray($projects);

        return $projects;
    }

    private function saveCoordinator($project, $coordinatorId){

        $foundProject = $this->get($project);
        $projectId = $foundProject[self::ID_COLUMN];

        $teamCoordinator = array(
            "id_project" => $projectId,
            "member" => $coordinatorId,
            "coordinator" => TRUE
        );

        $this->db->insert(self::TEAM_TABLE, $teamCoordinator);
    }

    private function checkIfProjectNameExists($name){
        $project = $this->get(self::NAME_COLUMN, $name);
        return $project !== FALSE;
    }
}