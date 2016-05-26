<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/domain/Group.php");
require_once(MODULESPATH."auth/exception/GroupException.php");

class GroupController extends MX_Controller {

    const MODEL_NAME = "group_model";

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

        $this->load->module("auth/userPermission");

        $foundGroups = $this->group_model->getUserGroups($user);    

        if($foundGroups !== FALSE){

            $groups = array();
            foreach($foundGroups as $foundGroup){
                try{

                    $groupId = $foundGroup['id_group'];
                    $groupName = $foundGroup['group_name'];
                    $groupProfileRoute = $foundGroup['profile_route'];

                    $permissions = $this->userpermission->getGroupPermissions($groupId);

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
