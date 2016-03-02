<?php
$sessao = $this->session->userdata("current_user");
require_once APPPATH.'controllers/capesavaliation.php';
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



<?php } else { ?>

	<h1 class="bemvindoLogin">SiGA</h1>
</br></br>
</br>

	<a class="nav-tabs-dropdown btn btn-block btn-primary"><h3>Sobre o Programa
		de Pós Graduação em Educação</h3></a>


	<ul id="nav-tabs-wrapper" class="nav nav-tabs nav-pills nav-stacked well">
		<li class="active"><a href="#vtab1" data-toggle="collapse" data-target="#resumo">O PPGE</a></li>
		<div id="resumo" class="collapse">
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,
			sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		</div>

		<li><a href="#vtab2" data-toggle="collapse" data-target="#historico">Histórico</a></li>
		<div id="historico" class="collapse">
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,
			sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		</div>

		<li><a href="#vtab3" data-toggle="collapse" data-target="#contato">Contato</a></li>
		<div id="contato" class="collapse">
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,
			sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		</div>


		<li><a href="#vtab4" data-toggle="collapse" data-target="#linhasPesquisa">Linhas de Pesquisa</a></li>
		<div id="linhasPesquisa" class="collapse">
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,
			sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
		</div>
	</ul>

			<?php
			}
			?>

		</div>
	</div>
