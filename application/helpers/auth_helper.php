<?php 
function session() {
	$ci = get_instance();
	$user = $ci->session->userdata("current_user");
	if (!$user) {
		$ci->session->set_flashdata("danger", "Você não está logado!");
		redirect("/");
	}
	return $user;
}