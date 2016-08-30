<?php

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
	
	$permissionsRoutes = formArrayWithPermissions($programPermissions, $secretaryPermissions);
	$programPermissions = $permissionsRoutes['categoryPermissions'];
	
	$permissionsRoutes = formArrayWithPermissions($enrollmentPermissions, $permissionsRoutes['secretaryPermissions']);
	$enrollmentPermissions = $permissionsRoutes['categoryPermissions'];
	
	$permissionsRoutes = formArrayWithPermissions($studentPermissions, $permissionsRoutes['secretaryPermissions']);
	$studentPermissions = $permissionsRoutes['categoryPermissions'];

	$permissionsRoutes = formArrayWithPermissions($otherPermissions, $permissionsRoutes['secretaryPermissions']);
	$otherPermissions = $permissionsRoutes['categoryPermissions'];

	$secretaryPermissions = $permissionsRoutes['secretaryPermissions'];
	$permissions = array(
		'programPermissions' => $programPermissions, 
		'enrollmentPermissions' => $enrollmentPermissions,
		'studentPermissions' => $studentPermissions, 
		'otherPermissions' => $otherPermissions,
		'secretaryPermissions' => $secretaryPermissions
	);

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

function showGroupPermissions($groupPermissions){

	foreach($groupPermissions as $permission){
        echo "<li>";
            if ($permission->getFunctionality() == PermissionConstants::RESEARCH_LINES_PERMISSION){
                continue;
            }
            else{
                echo anchor($permission->getFunctionality(), " ".$permission->getName(), " class='fa fa-caret-right'");
            }
        echo "</li>";
    }

}