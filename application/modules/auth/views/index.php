<?php

require_once APPPATH.'controllers/Capesavaliation.php';
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

<?php } else { ?>

</div></aside>
<div class="container">
</br></br>
</br>
<img src="<?php echo base_url('img/base_logo_siga.png'); ?>" alt="Logo SiGA" class="img-responsive img-center" style="width:240px;height:110px;" />

</br><center><h4>
Sistema Integrado de Gestão Acadêmica
</h4></center></br>
</br>

<?php
	if ($programs !== FALSE) {

		include(APPPATH.'views/home/_create_tabs.php');

	?>
		<div class="tab-content">
	<?php
		for($i = 0; $i < $quantityOfTabs; $i++){
			$tabId = "program".$i;
			$isFirst = $i === 0;
			$program = $programs[$i];

			include(APPPATH.'views/home/_program_information.php');
		}
	}
	?>
	</div>
<?php }?>
	
