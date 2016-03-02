<?php
$sessao = $this->session->userdata("current_user");
require_once APPPATH.'controllers/capesavaliation.php';

const MAX_QUANTITY_OF_TABS = 10;

if ($sessao != NULL) { ?>
	<p class="alert alert-success text-center">Logado como "<?=$sessao['user']['login']?>"</p>
	<h1 class="bemvindo">Bem vindo!</h1>

	<?php

	 if ($sessao['user']['login'] == 'admin'){
	 	$admin = new CapesAvaliation();

	 	$atualizations = $admin->getCapesAvaliationsNews();

	 	showCapesAvaliationsNews($atualizations);
	 }
		?>



<?php } 
else { 
	if ($programs !== FALSE) { 
		$firstProgram = $programs[0]; ?>


		<?php include("_program_information.php");
	}
}?>

		</div>
	</div>
