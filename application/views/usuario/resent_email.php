<h2 class="principal">Olá, <b><i><?= $user['login']?>!</i></b></h2>

<h3>Parece que o e-mail que nós enviamos não chegou...</h3>

<br>

<h4><i class="fa fa-question-circle"></i> O que deseja fazer?</h4>

<ul>
	<li data-toggle="collapse" href='#resent_email_form'> Reenviar e-mail</li>
	<div id=<?="resent_email_form"?> class="panel-collapse collapse in" aria-expanded="false">
		<div class="box-body">		

			<?php include(APPPATH.'views/usuario/_resent_email_form.php'); ?>

		</div>
	</div>
	<li> <?php echo anchor("cancel_register/{$user['id']}",'Cancelar cadastro')?></li>
</ul>