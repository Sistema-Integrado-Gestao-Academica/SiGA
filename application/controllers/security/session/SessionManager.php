<?php

require_once(APPPATH."/controllers/security/groupcontroller.php");
require_once(APPPATH."/exception/security/GroupException.php");

class SessionManager extends CI_Controller{

    const USER_WITH_NO_GROUPS_EXCEPTION = "Usuário sem grupos no sistema. Contate o administrador.";

    const CURRENT_USER_LABEL = "current_user";

    private static $instance = NULL;

    public static function getInstance(){

        if(!isset(self::$instance)){
            self::$instance = new SessionManager();
        }

        $instance = self::$instance;

        return $instance;
    }

    public function __construct(){
        parent::__construct();
    }

    public function login($user){

        $groupController = new GroupController();

        $userId = $user->getId();

        $userGroups = $groupController->getUserGroups($userId);

        if($userGroups !== FALSE){

            foreach($userGroups as $group){
                $user->addGroup($group);
            }

            $this->saveData(self::CURRENT_USER_LABEL, $user);

        }else{
            // User with no groups situation
            // It doesn't (cannot) happen on the system
            // The users is at least guests

            $e = new GroupException(self::USER_WITH_NO_GROUPS_EXCEPTION);
            GroupException::handle($e);
        }

    }

    public function isLogged(){
        $userData = $this->getUserData();

        $isLogged = $userData !== FALSE;

        return $isLogged;
    }

    public function getUserData(){
        $data = $this->session->userdata(self::CURRENT_USER_LABEL);

        return $data;
    }

    public function getUserGroups(){

        $currentUser = $this->getUserData();

        $groups = $currentUser->getGroups();

        return $groups;
    }

    public function getUserPermissions(){
        $groups = $this->getUserGroups();

        $permissions = array();

        foreach($groups as $group){
            $permissions[$group->getName()] = $group->getPermissions();
        }

        return $permissions;
    }

    public function showFlashMessage($type, $message){
        $this->session->set_flashdata($type, $message);
    }

    private function saveData($label, $data){
        $this->session->set_userdata($label, $data);
    }

    public function unsetUserData(){
        $this->session->unset_userdata(self::CURRENT_USER_LABEL);
    }

    public function logout(){
        $this->destroyCurrentSession();
    }

    private function destroyCurrentSession(){
        $this->session->sess_destroy();
        self::$instance = NULL;
    }
}