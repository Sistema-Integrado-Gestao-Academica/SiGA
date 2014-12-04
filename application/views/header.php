<!DOCTYPE html>
<?php  $session = $this->session->userdata("usuario_logado"); ?>
<html>
<head>
	<meta charset="UTF-8">

	<title>SiGA</title>

	<link rel="stylesheet" href=<?=base_url("css/bootstrap.css")?>>
	<link rel="stylesheet" href=<?=base_url("css/estilo.css")?>>
	<link rel="stylesheet" href=<?=base_url("font-awesome-4.2/css/font-awesome.min.css")?>>
	<script src=<?=base_url("js/funcoes.js")?>></script>
</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<?=anchor("/", "Home", "class='navbar-brand'")?>
		</div>
		<div class="collapse navbar-collapse navbar-ex1-collapse">
		<?php if ($session) { ?>
			<ul class="nav navbar-nav side-nav">
               	<?php
               		/** 
               		 * Variable to start the for counter in the exact middle of array user_type
               		 * It would be in the middle because its where starts the names of user_types
               		 */
               		$counter = sizeof($session['user_type'])/2; 
               		for ($i= $counter; $i < sizeof($session['user_type']) ; $i++) {
                    	echo "<li>";
                    		echo anchor($session['user_type'][$i],ucfirst($session['user_type'][$i]),"class='fa fa-folder-open-o'");
                    	echo "</li>";
					}?>
            </ul>
            <ul class="nav navbar-nav">
			<?php  
				foreach($session["user_permissions"] as $id => $permission_name){
					echo "<li>" . anchor($permission_name, ucfirst($permission_name)) . " </li>";
				}
				?>
				<li><?=anchor("conta", "Conta")?></li>
				<li><?=anchor("logout", "Sair")?></li>
			</ul>
		<?php } else { ?>
			<li><?=anchor("usuario/novo", "Cadastro")?></li>
		<?php }?>
			
		</div>
	</div>
</div>
<div class="container">

<?php
if ($this->session->flashdata("success")) : ?>
	<p class="alert alert-success text-center"><?= $this->session->flashdata("success") ?></p>
<?php endif;
if ($this->session->flashdata("danger")) : ?>
	<p class="alert alert-danger text-center"><?= $this->session->flashdata("danger") ?></p>
<?php endif ?>
