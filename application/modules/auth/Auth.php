<?php

require_once "AuthInterface.php";
require_once MODULESPATH."/ModuleInterface.php";
require_once MODULESPATH."auth/controllers/SessionManager.php";

class Auth extends ModuleInterface implements AuthInterface{
	
	public static function loadAuthComponents(){
		require_once MODULESPATH."auth/constants";
	}

	public static function getSession(){

		$session = SessionManager::getInstance();

		return $session;
	}

	public static function getGroupByName($name){

		$this->load->module("auth/module");
		$foundGroup = $this->module->getGroupByName($name);

		return $foundGroup;
	}

	public static function checkUserGroup($group){

		$this->load->module("auth/module");
		$userHaveGroup = $this->module->checkUserGroup($group);

		return $userHaveGroup;	
	}

	public static function usersToSecretary(){

		$this->load->module("auth/usercontroller");
		$users = $this->usercontroller->getUsersToBeSecretaries();

		return $users;
	}
}