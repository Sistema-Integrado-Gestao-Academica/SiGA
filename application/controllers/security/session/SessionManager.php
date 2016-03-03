<?php

class SessionManager{

    const CURRENT_USER_LABEL = "current_user";

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

    public static function login($user){

        // Carregar os grupos e permissões do usuário
    }

    public static function logout(){
        $this->destroyCurrentSession();
    }

    private function destroyCurrentSession(){
        $this->session->sess_destroy();
        self::$instance = NULL;
    }
}