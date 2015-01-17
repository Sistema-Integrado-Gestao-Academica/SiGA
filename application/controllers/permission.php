<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission extends CI_Controller {

	public function checkUserPermission($requiredPermission){

		$permissionExists = $this->checkIfPermissionRouteExists($requiredPermission);

		if($permissionExists){

			$loggedUserData = $this->session->userdata('current_user');
			$userPermissionsRoutes = $loggedUserData['user_permissions']['route'];

			$userPermissions = array();
			$i = 0;
			foreach ($userPermissionsRoutes as $idGroup => $groupPermissions){
				foreach ($groupPermissions as $permissionRoute){
					$userPermissions[$i] = $permissionRoute;
					$i++;
				}
			}

			$havePermission = FALSE;
			foreach($userPermissions as $permissionRoute){
				if($permissionRoute === $requiredPermission){
					$havePermission = TRUE;
					break;
				}
			}
		}else{
			$havePermission = FALSE;
		}

		return $havePermission;
	}

	private function checkIfPermissionRouteExists($permission){
		$allPermissions = $this->getAllPermissionsRoutes();

		$permissionExists = FALSE;
		foreach($allPermissions as $idPermission => $permissionRoute){
			if($permissionRoute === $permission){
				$permissionExists = TRUE;
				break;
			}
		}

		return $permissionExists;
	}

	private function getAllPermissionsRoutes(){
		$this->load->model('permission_model');
		$permissionsRoutes = $this->permission_model->getAllPermissionsRoutes();

		return $permissionsRoutes;
	}

}
