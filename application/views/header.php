<!DOCTYPE html>
<?php  $session = $this->session->userdata("current_user");
?>
<html>
<head>
	<meta charset="UTF-8">

	<title>SiGA</title>

	<link rel="stylesheet" href=<?=base_url("css/bootstrap.css")?>>
	<link rel="stylesheet" href=<?=base_url("css/estilo.css")?>>
	<link rel="stylesheet" href=<?=base_url("font-awesome-4.2/css/font-awesome.min.css")?>>
	<link rel="stylesheet" href=<?=base_url("css/AdminLTE.css")?>>
	
	<script src=<?=base_url("js/jquery-2.1.1.min.js")?>></script>
	<script src=<?=base_url("js/bootstrap.min.js")?>></script>
	<script src=<?=base_url("js/AdminLTE/app.js")?>></script>
	<script src=<?=base_url("js/functions.js")?>></script>
	<script src=<?=base_url("js/jquery.inputmask.js")?>></script>
	<script src=<?=base_url("js/jquery.inputmask.numeric.extensions.js")?>></script>
	<script src=<?=base_url("js/jquery.inputmask.date.extensions.js")?>></script>
</head>

<body class="skin-blue">
	<header>
		<div class="navbar navbar-fixed-top" role="navigation">
			<div class="navbar-btn sidebar-toggle" role="button">
				<div class="">
					<?=anchor("/", "Home", "class='navbar-brand'")?>
				</div>
				<div class="collapse navbar-collapse navbar-ex1-collapse">
				<?php if ($session) { ?>
		            <ul class="nav navbar-nav">
						<li><?=anchor("conta", "Conta")?></li>
						<li><?=anchor("logout", "Sair")?></li>
					</ul>
				<?php } else { ?>
					<ul class="nav navbar-nav ">
						<li><?=anchor("usuario/novo", "Cadastro")?></li>
				    </ul>
				<?php }?>
					
				</div>
			</div>
		</div>
	
	</header>
	<div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <?php if($session){?>
	            <aside class="left-side sidebar-offcanvas">
	                <!-- sidebar: style can be found in sidebar.less -->
	                <section class="sidebar">
	                    <!-- Sidebar user panel -->
	                    <div class="user-panel">
	                        <div class="pull-left info">
	                            <br>
	                            <p>Hello, <?=ucfirst($session['user']['name'])?></p>
	
	                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
	                        </div>
	                    </div>
	                    <!-- search form -->
	                    <form action="#" method="get" class="sidebar-form">
	                        <div class="input-group">
	                            <input type="text" name="q" class="form-control" placeholder="Search..."/>
	                            <span class="input-group-btn">
	                                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
	                            </span>
	                        </div>
	                    </form>
	                    <!-- /.search form -->
	                    <!-- sidebar menu: : style can be found in sidebar.less -->
	                    <ul class="sidebar-menu">
	                        <?php
		               		/** 
		               		 * Variable to start the for counter in the exact middle of array user_type
		               		 * It would be in the middle because its where starts the names of user_types
		               		 */
		               		$counter = sizeof($session['user_type'])/2; 
		               		for ($i= $counter; $i < sizeof($session['user_type']) ; $i++) {
		                    	echo "<li class='treeview'>";
		                    		echo anchor($session['user_type'][$i],ucfirst($session['user_type'][$i]),"class='fa fa-folder-open-o'");
		                    		echo "<ul class='treeview-menu'>";
		                    		foreach($session["user_permissions"] as $route => $permission_name){
		                    			echo "<li>" . anchor($route, $permission_name) . " </li>";
		                    		}
		                    		echo "</ul>";
		                    	echo "</li>";
							}  
							?>
	                  
	                <!-- /.sidebar -->
	            </aside>
            <?php }?>
            <aside class="right-side">
            	<div class="container">
            	
	
<?php
if ($this->session->flashdata("success")) : ?>
	<p class="alert alert-success text-center"><?= $this->session->flashdata("success") ?></p>
<?php endif;
if ($this->session->flashdata("danger")) : ?>
	<p class="alert alert-danger text-center"><?= $this->session->flashdata("danger") ?></p>
<?php endif ?>
