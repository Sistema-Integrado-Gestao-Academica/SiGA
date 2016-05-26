<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions_model extends CI_Model {
    
    public function getAllPermissionsRoutes(){
      $allPermissions = $this->getAllPermissions();

      $permissions = array();
      foreach($allPermissions as $permission){

        $permissions[$permission['id_permission']] = $permission['route'];
      }
      $permissions = checkArray($permissions);
      return $permissions;
    }

    private function getAllPermissions(){
      $searchResult = $this->db->get('permission');

      $permissions = $searchResult->result_array();
      $permissions = checkArray($permissions);
      return $permissions;
    }
    /**
      * Search on database for the permissions of a group
      * @param $groupId - The group id to get the permissions
      * @return an array with the permissions of the given group
      */
    public function getGroupPermissions($groupId){

        $this->db->select('permission.*');
        $this->db->from("permission");
        $this->db->join("group_permission", "permission.id_permission = group_permission.id_permission");
        $this->db->where("group_permission.id_group", $groupId);
        $groupPermissions = $this->db->get()->result_array();

        $groupPermissions = checkArray($groupPermissions);

        return $groupPermissions;
    }
}