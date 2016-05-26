<?php

require_once(MODULESPATH."auth/controllers/Login.php");
require_once(MODULESPATH."auth/controllers/UserPermission.php");
require_once(MODULESPATH."auth/controllers/Module.php");
require_once(MODULESPATH."auth/controllers/SessionManager.php");

function getSession() {

	$session = SessionManager::getInstance();

	return $session;
}

/**
 * Load a given view if the logged user have the required permission
 * @param $requiredPermission - String with the permission route required to access the asked view
 * @param $template - String with the view to be loaded
 * @param $data - Data to pass along the view
 */
function loadTemplateSafelyByPermission($requiredPermission, $template, $data = array()){

	$permission = new UserPermission();
	$ci = get_instance();

	$userHasPermission = $permission->checkUserPermission($requiredPermission);

	if($userHasPermission){
		$ci->load->template($template, $data);
	}else{
		logoutUser();
	}
}

/**
 * Load a given view if the logged user have the required group
 * @param $requiredGroup - String with the group required to access the asked view
 * @param $template - String with the view to be loaded
 * @param $data - Data to pass along the view
 */
function loadTemplateSafelyByGroup($requiredGroup, $template, $data = array()){

	$group = new Module();

	$ci = get_instance();

	$userHasGroup = FALSE;

	if(is_string($requiredGroup)){
		$userHasGroup = $group->checkUserGroup($requiredGroup);
	}
	else{
		foreach ($requiredGroup as $g) {
			$userHasGroup = $group->checkUserGroup($g);
			if($userHasGroup == TRUE){
				break;
			}
		}
		
	}

	if($userHasGroup){
		$ci->load->template($template, $data);
	}else{
		logoutUser();
	}
}

/**
 * Logout the current user for unauthorized access to the page
 */
function logoutUser(){
	$login = new Login();
	$login->logout("Você deve ter permissão para acessar essa página.
			      Você foi deslogado por motivos de segurança.", "danger", '/');
}