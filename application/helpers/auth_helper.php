<?php 

require_once(APPPATH."/controllers/login.php");

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
 * Logout the current user for unauthorized access to the page
 */
function logoutUser(){
	$login = new Login();
	$login->logout("Você deve ter permissão para acessar essa página.
			      Você foi deslogado por motivos de segurança.", "danger", '/');
}