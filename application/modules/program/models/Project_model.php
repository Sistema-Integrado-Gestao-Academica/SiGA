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
    const ALREADY_MEMBER = "Este usuário já é membro do projeto.";

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

    public function getProject($projectId){
        $project = $this->get(self::ID_COLUMN, $projectId);
        return $project;
    }

    public function getProjectMembers($projectId){
        $this->db->select('project_team.*, users.name, users.id');
        $this->db->from('users');
        $this->db->join(self::TEAM_TABLE, "project_team.member = users.id");
        $this->db->where("project_team.id_project", $projectId);
        $members = $this->db->get()->result_array();

        $members = checkArray($members);

        return $members;
    }

    public function addMemberToTeam($project, $member){

        $isMember = $this->checkIfIsAlreadyMember($project, $member);

        if(!$isMember){
            $this->saveMember($project, $member);
        }else{
            throw new ProjectException(self::ALREADY_MEMBER);
        }
    }

    private function checkIfIsAlreadyMember($project, $member){
        $search = array(
            "id_project" => $project,
            "member" => $member
        );

        $foundMember = $this->get($search, FALSE, TRUE, FALSE, self::TEAM_TABLE);

        return $foundMember !== FALSE;
    }

    public function save($project, $coordinatorId, $owner=FALSE, $isCoordinator=FALSE){

        $projectName = $project[self::NAME_COLUMN];
        $nameExists = $this->checkIfProjectNameExists($projectName);
        if(!$nameExists){
            $this->db->insert($this->TABLE, $project);

            $this->saveMember($project, $coordinatorId, $owner, $isCoordinator);
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

    private function saveMember($project, $memberId, $owner=FALSE, $isCoordinator=FALSE){

        if(is_array($project)){
            $foundProject = $this->get($project);
            $projectId = $foundProject[self::ID_COLUMN];
        }else{
            $projectId = $project;
        }

        $teamMember = array(
            "id_project" => $projectId,
            "member" => $memberId,
            "owner" => $owner,
            "coordinator" => $isCoordinator
        );

        $this->db->insert(self::TEAM_TABLE, $teamMember);
    }

    private function checkIfProjectNameExists($name){
        $project = $this->get(self::NAME_COLUMN, $name);
        return $project !== FALSE;
    }
}