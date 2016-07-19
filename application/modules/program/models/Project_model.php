<?php

class Project_model extends CI_Model {

    public $TABLE = "academic_project";
    public $TEAM_TABLE = "project_team";

    public function getProjects($memberId, $coordinator=FALSE){

        $this->db->select('*');
        $this->db->from($this->TABLE);
        $this->db->join($this->TEAM_TABLE, "academic_project.id = project_team.id_project");
        $this->db->where("project_team.member", $memberId);
        if($coordinator){
            $this->db->where("project_team.coordinator", TRUE);
        }
        $projects = $this->db->get()->result_array();

        $projects = checkArray($projects);

        return $projects;
    }
}