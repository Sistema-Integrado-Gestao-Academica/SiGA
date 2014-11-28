<!DOCTYPE html>
<?php  $session = $this->session->userdata("usuario_logado"); ?>
<html>
<head>
	<meta charset="UTF-8">

	<title>SiGA</title>

	<link rel="stylesheet" href=<?=base_url("css/bootstrap.css")?>>
	<link rel="stylesheet" href=<?=base_url("css/estilo.css")?>>
	<script src=<?=base_url("js/funcoes.js")?>></script>
</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top">
<div class="container">
<div class="navbar-header">
	<?=anchor("/", "Home", "class='navbar-brand'")?>
</div>
	<div>
		<ul class="nav navbar-nav">
			<?php if ($session) { 
				foreach($session["user_permissions"] as $id => $permission_name){
					echo "<li>" . anchor($permission_name, ucfirst($permission_name)) . " </li>";
				}
				?>
				<li><?=anchor("conta", "Conta")?></li>
				<li><?=anchor("logout", "Sair")?></li>
			<?php } else { ?>
				<li><?=anchor("usuario/novo", "Cadastro")?></li>
			<?php }?>
		</ul>
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
