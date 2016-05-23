<?php

class Group_model extends CI_Model {

    public function getUserGroups($userId){
        $this->db->select('group.*');
        $this->db->from('group');
        $this->db->join('user_group', 'group.id_group = user_group.id_group');
        $this->db->where('user_group.id_user', $userId);

        $foundGroups = $this->db->get()->result_array();

        $foundGroups = checkArray($foundGroups);

        return $foundGroups;
    }
}