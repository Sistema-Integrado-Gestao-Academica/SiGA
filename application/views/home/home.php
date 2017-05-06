<?php
require_once(MODULESPATH."auth/constants/GroupConstants.php");

$session = getSession();

if ($session->isLogged()) {

    $userData = $session->getUserData();

?>
	<p class="alert alert-success text-center">Logado como "<?=$userData->getName()?>"</p>
	<h1 class="bemvindo">Bem vindo!</h1>

	<?php
	if ($userData->getLogin() == 'admin'){

		$this->load->module("program/capesAvaliation");
		$atualizations = $this->capesavaliation->getCapesAvaliationsNews();

		showCapesAvaliationsNews($atualizations);
	}

	?>

<?php } else { ?>

</div></aside>
<div class="container">
</br></br>

<div class="row">
	<div class="col-md-8 col-md-offset-2">
	<?php
		alert(function(){
			echo "<p class='text-center'>";
			echo anchor('register', "<span class='label label-primary'>Cadastre-se</span>")
				." no sistema para <b>se inscrever</b> nos processos seletivos do programa.";
			echo "</p>";
		}, 'info', "Bem-vindo ao SiGA!");
	?>
	</div>
</div>

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
<?php }

?>


