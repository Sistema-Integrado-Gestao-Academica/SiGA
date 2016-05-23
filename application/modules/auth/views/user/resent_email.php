<h2 class="principal">Olá, <b><i><?= $user['login']?>!</i></b></h2>

<h3>Parece que o e-mail que nós enviamos não chegou...</h3>

<br>

<h3><i class="fa fa-question-circle"></i> O que deseja fazer?</h3>

<ul>
	<h4>

	<li> 
		<a href='#resent_email_form' data-toggle="collapse"> Reenviar e-mail</a> 

	</li>

	</h4>
	
	<div id=<?="resent_email_form"?> class="panel-collapse collapse" aria-expanded="false">
		<div class="box-body">		

			<?php include(MODULESPATH.'auth/views/user/_resent_email_form.php'); ?>

		</div>
	</div>
	
	<h4>

	<li> 
		<a href='#cancel_register_form' data-toggle="collapse"> Cancelar cadastro</a> 

	</li>

	</h4>
	
	<div id=<?="cancel_register_form"?> class="panel-collapse collapse" aria-expanded="false">
		<div class="box-body">		

			<?php include(MODULESPATH.'auth/views/user/_cancel_register_form.php'); ?>

		</div>
	</div>

	</h4>
</ul>