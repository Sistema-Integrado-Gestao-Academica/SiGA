<?php

require_once(MODULESPATH."auth/controllers/Login.php");
require_once(MODULESPATH."auth/controllers/UserPermission.php");
require_once(MODULESPATH."auth/controllers/Module.php");
require_once(MODULESPATH."auth/controllers/SessionManager.php");
require_once(MODULESPATH."auth/constants/PermissionConstants.php");

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

function orderAcademicSecretaryPermissions($permissions){

	$secretaryPermissions = array();
	foreach ($permissions as $permission) {
		$permission_route = $permission->getFunctionality();
		$secretaryPermissions[$permission_route] = $permission;
	}

	$permissions = array();

	$programPermissions = array(
		PermissionConstants::SECRETARY_PROGRAMS_PERMISSION,
		PermissionConstants::COURSES_PERMISSION,
		PermissionConstants::COURSE_SYLLABUS_PERMISSION,
		PermissionConstants::ENROLL_TEACHER_PERMISSION,
		PermissionConstants::DISCIPLINE_PERMISSION,
		PermissionConstants::SELECTION_PROCESS_PERMISSION,
	);

	$enrollmentPermissions = array(
		PermissionConstants::OFFER_LIST_PERMISSION,
		PermissionConstants::REQUEST_REPORT_PERMISSION,
		PermissionConstants::ENROLLMENT_REPORT_PERMISSION,
	);

	$studentPermissions = array(
		PermissionConstants::STUDENT_LIST_PERMISSION,
		PermissionConstants::ENROLL_STUDENT_PERMISSION,
		PermissionConstants::DEFINE_MASTERMIND_PERMISSION,
		PermissionConstants::DOCUMENT_REQUEST_REPORT_PERMISSION,
	);
	

	$otherPermissions = array(
		PermissionConstants::INVITE_USER_PERMISSION,
		PermissionConstants::IMPORT_QUALIS_PERMISSION,
	);

	$permissionsRoutes = array();
	$permissionsRoutes = array_merge($programPermissions, $enrollmentPermissions, $studentPermissions, $otherPermissions);

	$result = formArrayWithPermissions($permissionsRoutes, $secretaryPermissions);

	$permissions = array_merge($result['categoryPermissions'],$result['secretaryPermissions']);
	
	return $permissions;
}

function formArrayWithPermissions($permissionsRoutes, $secretaryPermissions){

	$permissions = array();

	if(!empty($permissionsRoutes)){

		foreach ($permissionsRoutes as $permissionRoute) {
			array_push($permissions, $secretaryPermissions[$permissionRoute]);
			unset($secretaryPermissions[$permissionRoute]);
		}
	}

	$result = array(

		'categoryPermissions' => $permissions,
		'secretaryPermissions' => $secretaryPermissions

	);
	
	return $result;
}