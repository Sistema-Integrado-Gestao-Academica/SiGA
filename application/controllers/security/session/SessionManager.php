<?php

require_once(APPPATH."/controllers/security/groupcontroller.php");
require_once(APPPATH."/exception/security/GroupException.php");

class SessionManager{

    const USER_WITH_NO_GROUPS_EXCEPTION = "Usuário sem grupos no sistema. Contate o administrador.";

    const CURRENT_USER_LABEL = "current_user";
    const USER_GROUPS_LABEL = "user_groups";

    private static $instance = NULL;
    private $ci;
    private $session;

    private $sessionId;
    private $ipAddress;
    private $userAgent;
    private $lastActivity;

    public static function getInstance(){

        if(isset(self::$instance)){
            $instance = self::$instance;
        }else{
            $instance = new SessionManager();
        }

        return $instance;
    }

    public function __construct(){

        // Get CI object and Session object to local variables to use throughout the class
        $this->ci =& get_instance();
        $this->session = $this->ci->session;

        $sessionBasicData = $this->session->all_userdata();

        $this->sessionId = $sessionBasicData['session_id'];
        $this->ipAddress = $sessionBasicData['ip_address'];
        $this->userAgent = $sessionBasicData['user_agent'];
        $this->lastActivity = $sessionBasicData['last_activity'];
    }

    public function login($user){

        $groupController = new GroupController();

        // PROVISÓRIO ATÉ TRANSFORMAR USUARIO EM OBJETO
        $userId = $user['id'];
        // PROVISÓRIO

        $userGroups = $groupController->getUserGroups($userId);

        if($userGroups !== FALSE){
            $this->saveData(self::CURRENT_USER_LABEL, $user);
            $this->saveData(self::USER_GROUPS_LABEL, $userGroups);
        }else{
            // User with no groups situation
            // It doesn't (cannot) happen on the system
            // The users is at least guests

            $e = new GroupException(self::USER_WITH_NO_GROUPS_EXCEPTION);
            GroupException::handle($e);
        }

    }

    public function getUserData(){
        $data = $this->session->userdata(self::CURRENT_USER_LABEL);

        return $data;
    }

    public function getUserGroupsData(){
        $data = $this->session->userdata(self::USER_GROUPS_LABEL);

        return $data;
    }

    private function saveData($label, $data){
        $this->session->set_userdata($label, $data);
    }

    public function logout(){
        $this->destroyCurrentSession();
    }

    private function destroyCurrentSession(){
        $this->session->sess_destroy();
        self::$instance = NULL;
    }
}