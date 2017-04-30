<?php

require_once(MODULESPATH."auth/controllers/Login.php");
require_once(MODULESPATH."auth/controllers/UserPermission.php");
require_once(MODULESPATH."auth/controllers/Module.php");
require_once(MODULESPATH."auth/controllers/SessionManager.php");
require_once(MODULESPATH."auth/constants/PermissionConstants.php");

function getSession() {
	return SessionManager::getInstance();
}

/*
 * Check if the user have the required permission and execute an additional check.
 *
 * @param $requiredPermission {string|array} - Permission(s) name(s) required to access
 * @param $extraCheck {callable} - Function to execute as extra check. MUST return a boolean.
 * @param $onSucess {callable} - Success callback
 * @param $onError {callable} - Failure callback
 * @param $logoutUser {boolean} -  Whether to logout current user or not
 * @return void
 */
function withPermissionAnd($requiredPermission, callable $extraCheck,
	callable $onSuccess, callable $onError=null, $logoutUser=TRUE){
	if($extraCheck()){
		withPermission($requiredPermission, $onSuccess, $onError, $logoutUser);
	}else{
		if(!is_null($onError)){
			$onError();
		}
		if($logoutUser){
			logoutUser();
		}
	}
}

/*
 * Check if the user have the required permission(s) before executing some action.
 *
 *	The current user is logged out if have no permission.
 *
 * @param $requiredPermission {string|array} - Permission(s) name(s) required to access
 * @param $onSucess {callable} - Action to be executed if user have the required permission(s)
 * @param $onError {callable} -  Action to be executed if user haven't the required permission(s)
 * @param $logoutUser {boolean} -  Whether to logout current user or not
 * @return void
 */
function withPermission($requiredPermission, callable $onSuccess, callable $onError=null, $logoutUser=TRUE){
	$permissionObj = new UserPermission();
	if(is_array($requiredPermission)){
		$hasPermission = FALSE;
		foreach ($requiredPermission as $permission) {
			$hasPermission = $permissionObj->checkUserPermission($permission);
			if($hasPermission){
				break;
			}
		}
	}else{
		$hasPermission = $permissionObj->checkUserPermission($requiredPermission);
	}
	if($hasPermission){
		$onSuccess();
	}else{
		if(!is_null($onError)){
			$onError();
		}

		if($logoutUser){
			logoutUser();
		}
	}
}

function userInGroup($requiredGroup, $user=FALSE){
	$group = new Module();
	return $group->checkUserGroup($requiredGroup, $user);
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

/**
*/
function getLoggedUserId(){
	$userData = getSession()->getUserData();
    $userId = $userData->getId();

    return $userId;
}