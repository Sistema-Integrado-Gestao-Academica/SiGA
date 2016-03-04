<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/security/session/SessionManager.php");

class PermissionOld extends CI_Controller {

	public function checkUserPermission($requiredPermission){

		$permissionExists = $this->checkIfPermissionRouteExists($requiredPermission);

		if($permissionExists){

			$session = SessionManager::getInstance();
			$userPermissions = $session->getUserPermissions();

			$havePermission = FALSE;
			foreach($userPermissions as $group => $permissions){

				foreach($permissions as $permission){

					if($permission->getFunctionality() === $requiredPermission){
						$havePermission = TRUE;
						break;
					}
				}

				if($havePermission){
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
