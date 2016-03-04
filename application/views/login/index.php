<?php

require_once APPPATH.'controllers/capesavaliation.php';
require_once(APPPATH."/controllers/security/session/SessionManager.php");

$session = SessionManager::getInstance();

if ($session->isLogged()) {

    $userData = $session->getUserData();

?>
	<p class="alert alert-success text-center">Logado como "<?=$userData->getName()?>"</p>
	<h1 class="bemvindo">Bem vindo!</h1>

	<?php

	 if ($userData->getLogin() == 'admin'){
	 	$admin = new CapesAvaliation();

	 	$atualizations = $admin->getCapesAvaliationsNews();

	 	showCapesAvaliationsNews($atualizations);
	 }
		?>


<?php } else {
	if ($programs !== FALSE) {
		$program = $programs[0];
		$id = $program['id_program'];
		include(APPPATH.'views/home/_create_tabs.php');
		include(APPPATH.'views/home/_program_information.php');
	}
}?>
