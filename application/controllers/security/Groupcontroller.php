<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Permissioncontroller.php");
require_once(APPPATH."/data_types/security/Group.php");
require_once(APPPATH."/exception/security/GroupException.php");

class GroupController extends CI_Controller {

    const MODEL_NAME = "security/group_model";

    public function __construct(){
        parent::__construct();
        $this->load->model(self::MODEL_NAME);
    }

    /**
     * Get the groups of an user
     * @param $user - The user to get the groups of
     * @return An array of Group objects or FALSE if none groups is found for the user
     */
    public function getUserGroups($user){

        $permissionController = new PermissionController();

        $foundGroups = $this->group_model->getUserGroups($user);

        if($foundGroups !== FALSE){

            $groups = array();
            foreach($foundGroups as $foundGroup){
                try{

                    $groupId = $foundGroup['id_group'];
                    $groupName = $foundGroup['group_name'];
                    $groupProfileRoute = $foundGroup['profile_route'];

                    $permissions = $permissionController->getGroupPermissions($groupId);

                    $group = new Group($groupId, $groupName, $groupProfileRoute, $permissions);

                    $groups[] = $group;

                }catch(GroupException $e){
                    GroupException::handle($e);
                }
            }
        }else{
            $groups = FALSE;
        }

        return $groups;
    }

}
