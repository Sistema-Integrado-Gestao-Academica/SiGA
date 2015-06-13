<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission extends CI_Controller {

	public function checkUserPermission($requiredPermission){

		$permissionExists = $this->checkIfPermissionRouteExists($requiredPermission);

		if($permissionExists){

			$loggedUserData = $this->session->userdata('current_user');
			$userPermissions = $loggedUserData['user_permissions'];

			$havePermission = FALSE;
			foreach($userPermissions as $group => $groupPermissions){

				if($groupPermissions !== FALSE){

					foreach($groupPermissions as $permission){
						
						if($permission['route'] === $requiredPermission){
							$havePermission = TRUE;
							break;
						}
					}
				}else{
					$havePermission = FALSE;
				}

				if($havePermission){
					break;
				}else{
					continue;
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
