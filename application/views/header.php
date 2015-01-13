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
		<?php 
			$this->load->helper('url');
			$site_url = site_url();

			echo "<input id='site_url' name='site_url' type='hidden' value=\"$site_url\"></input>";
		?>
		<div class="navbar navbar-fixed-top" role="navigation">
			<div class="navbar-btn sidebar-toggle" role="button">
				<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">
					<li><?=anchor("/", "Home", "class='navbar-brand'")?></li>
					<?php if ($session) { ?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
						<li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?=ucfirst($session['user']['name'])?><i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header ">
                                    <p>
                                        <?php
                                        	echo ucfirst($session['user']['name']);

                                        	foreach($session['user_groups'] as $key => $type) {
                                        		echo "<br>";
                                        		echo ucfirst($type);
                                        	}
                                        ?>
                                        <small><?php echo $session['user']['email']?></small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?=anchor("conta", "Conta", "class='btn btn-default btn-flat'")?>
                                    </div>
                                    <div class="pull-right">
                                        <?=anchor("logout", "Sair", "class='btn btn-default btn-flat'")?>
                                    </div>
                                </li>
                            </ul>
                        </li>
					</ul>
				<?php } else { ?>
						<li><?=anchor("usuario/novo", "Cadastro", "class='navbar-brand'")?></li>
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
	                            <p>Olá, <?=ucfirst($session['user']['name'])?></p>
	
	                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>

	                            <br><br>
	                            <div class="input-group-btn">
		                            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
		                            Perfil <span class="fa fa-caret-down"></span></button>

		                            <ul class="dropdown-menu">
		                            	<?php 
		                            		foreach ($session['user_groups'] as $group_name => $profile_route) {
		                            			echo "<li>";
		                            			echo "<a href=\"{$profile_route}\">".ucfirst($profile_route)."</a>";
		                            			echo "</li>";
		                            		}
		                            	?>
		                            </ul>
	                            </div>
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
	                define('SECRETARIA', 7);
				 
	                if(@$session['user_groups'][SECRETARIA]){
						$secretario = $session['user_groups'][SECRETARIA];
						echo "<li class='treeview'>";
							echo anchor($secretario, ucfirst($secretario),"class='fa fa-folder-open-o'");
							echo "<ul class='treeview-menu'>";
							
							foreach($session['user_groups'] as $userGroupId => $userGroupName){
								switch ($userGroupName){

									case 'secretario': //nothing to do
														break;
									default: 
										echo "<li class='treeview'>". anchor($userGroupName, ucfirst($userGroupName),"class='fa fa-folder-open-o'");
										
											echo "<ul class='treeview-menu'>";
											$permissions_names = $session['user_permissions']['name'];
							            	$permissions_routes = $session['user_permissions']['route'];
							            	foreach($permissions_names as $id_group => $permission_name){
							            		foreach ($permissions_routes as $id_group2 => $permission_route){
													switch($id_group2){
														case 7:
															break;
														default: 
															for($i=1;$i<sizeof($permission_name); $i++){
																echo "<li>" . anchor($permission_route[$i], $permission_name[$i]) . " </li>";
															} 
							            			 }
							            		}
							            	}
							            	echo "</ul>";
											echo "</li>";
												  break;
								}
							}
							
							echo "</ul>";
						echo "</li>";
					}else{
							foreach($session['user_groups'] as $userGroupId => $userGroupName){
				                    	echo "<li class='treeview'>";
				                    		echo anchor($userGroupName, ucfirst($userGroupName),"class='fa fa-folder-open-o'");
				                    		echo "<ul class='treeview-menu'>";
				                    		$permissions_names = $session['user_permissions']['name'];
							            	$permissions_routes = $session['user_permissions']['route'];
							            	foreach($permissions_names as $id_group => $permission_name){
							            		foreach ($permissions_routes as $id_group2 => $permission_route){
													switch($id_group2){
														case 7:
															break;
														default: 
															for($i=1;$i<sizeof($permission_name); $i++){
																echo "<li>" . anchor($permission_route[$i], $permission_name[$i]) . " </li>";
															} 
							            			 }
							            		}
							            	}
				                    		echo "</ul>";
				                    	echo "</li>";
							} 
					}
							?>
	                  	</ul>
	                  	</section>
	                <!-- /.sidebar -->
	            </aside>
            <?php }?>
            <aside class="right-side">
            	<div class="container">
            	<?php  var_dump($session);?>
	
<?php
if ($this->session->flashdata("success")) : ?>
	<p class="alert alert-success text-center"><?= $this->session->flashdata("success") ?></p>
<?php endif;
if ($this->session->flashdata("danger")) : ?>
	<p class="alert alert-danger text-center"><?= $this->session->flashdata("danger") ?></p>
<?php endif ?>
