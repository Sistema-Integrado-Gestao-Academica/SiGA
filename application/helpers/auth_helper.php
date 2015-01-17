<?php 

require_once(APPPATH."/controllers/login.php");
require_once(APPPATH."/controllers/permission.php");

function session() {
	$ci = get_instance();
	$user = $ci->session->userdata("current_user");
	if (!$user) {
		$ci->session->set_flashdata("danger", "Você não está logado!");
		redirect("/");
	}
	return $user;
}

/**
 * Load a given view if the logged user have the required permission
 * @param $requiredPermission - String with the permission route required to access the asked view
 * @param $template - String with the view to be loaded
 * @param $data - Data to pass along the view
 */
function loadTemplateSafely($requiredPermission, $template, $data = array()){

	$permission = new Permission();

	$userHasPermission = $permission->checkUserPermission($requiredPermission);

	if($userHasPermission){
		$this->load->template($template, $data);
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