<!DOCTYPE html>
<?php
    require_once(APPPATH."/constants/GroupConstants.php");
	require_once(APPPATH."/controllers/security/session/SessionManager.php");

	$session = SessionManager::getInstance();

    if($session->isLogged()){

        $userData = $session->getUserData();

        $userName = $userData->getName();
        $userEmail = $userData->getEmail();

        $userGroups = $session->getUserGroups();
    }

?>
<html>
<head>
	<meta charset="UTF-8">

	<title>SiGA</title>

	<link rel="stylesheet" href=<?=base_url("css/bootstrap.css")?>>
	<link rel="stylesheet" href=<?=base_url("css/estilo.css")?>>
	<link rel="stylesheet" href=<?=base_url("font-awesome-4.2/css/font-awesome.min.css")?>>
	<link rel="stylesheet" href=<?=base_url("css/AdminLTE.css")?>>
	<link rel="stylesheet" href=<?=base_url("css/jquery-ui.min.css")?>>
	<link rel="stylesheet" href=<?=base_url("css/jquery-ui.structure.min.css")?>>
	<link rel="stylesheet" href=<?=base_url("css/jquery-ui.theme.min.css")?>>

	<script src=<?=base_url("js/jquery-2.1.1.min.js")?>></script>
	<script src=<?=base_url("js/bootstrap.min.js")?>></script>
	<script src=<?=base_url("js/AdminLTE/app.js")?>></script>
	<script src=<?=base_url("js/functions.js")?>></script>
	<script src=<?=base_url("js/jquery.inputmask.js")?>></script>
	<script src=<?=base_url("js/jquery.inputmask.numeric.extensions.js")?>></script>
	<script src=<?=base_url("js/jquery.inputmask.date.extensions.js")?>></script>
	<script src=<?=base_url("js/jquery.mask.min.js")?>></script>
	<script src=<?=base_url("js/jquery.tablesorter.min.js")?>></script>
	<script src=<?=base_url("js/jquery-ui.min.js")?>></script>
	<script src=<?=base_url("js/datepicker-pt-BR.js")?>></script>
	<link rel="icon" href="<?=base_url()?>/favicon.ico" type="image/ico">
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

                    <?php if ($session->isLogged()) {?>

				</ul>
				<ul class="nav navbar-nav navbar-right">
						<li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?=ucfirst($userName)?><i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header ">
                                    <p>
                                        <?php
                                        	echo ucfirst($userName);

                                        	echo "<br><br><small><b>	Grupos cadastrados:</b></small>";
                                        	foreach($userGroups as $group){
                                        		switch ($group->getName()) {
                                        			case GroupConstants::ACADEMIC_SECRETARY_GROUP:
                                        				$groupNameToDisplay = "Secretaria acadêmica";
                                        				break;
                                        			case GroupConstants::FINANCIAL_SECRETARY_GROUP:
                                        				$groupNameToDisplay = "Secretaria financeira";
                                        				break;
                                        			default:
                                        				$groupNameToDisplay = $group->getName();
                                        				break;
                                        		}
                                        		echo ucfirst($groupNameToDisplay);
                                        		echo "<br>";
                                        	}
                                        ?>
                                        <br>
                                        <small><?php echo $userEmail?></small>
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

						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown user user-menu">
								<a href="#" class="dropdow	n-toggle" data-toggle="dropdown">
									<i class="glyphicon glyphicon-user"></i>
									<span>Login<i class="caret"></i></span>
								</a>
								<ul class="dropdown-menu">
									<div class="bg-gray">
									<?php
									echo form_open("login/autenticar");
									?>
									<div class="header">
										<div class="bg-olive">
										<h4 align="center">Login</h4>
									</div>
									</div>
									<li class="user-header">
										<p>
											<?php
											echo form_label("Login", "login");
											echo form_input(array(
												"name" => "login",
												"id" => "login",
												"type" => "text",
												"class" => "form-campo",
												"maxlength" => "255",
												"value" => set_value("login", ""),
												"class" => "form-control",
												"placeholder" => "Login de Usuário"
											));
											?>
										</p>
										<p>
											<?php
												echo form_label("Senha", "senha");
												echo form_input(array(
													"name" => "senha",
													"id" => "senha",
													"type" => "password",
													"class" => "form-campo",
													"maxlength" => "255",
													"class" => "form-control",
													"placeholder" => "Senha"
												));
											?>
										</p>
										<div class="footer">
											<?php
												echo form_button(array(
													"id" => "login_btn",
													"class" => "btn bg-olive btn-block",
													"content" => "Entrar",
													"type" => "submit"
												));

												echo form_close();
												?>
											</div>
										</div>

								</ul>
							</li>
						</ul>


				<?php }?>

				</div>
			</div>
		</div>

	</header>
	<div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <?php if($session->isLogged()){?>
	            <aside class="left-side sidebar-offcanvas sidebar-fixed">
	                <!-- sidebar: style can be found in sidebar.less -->
	                <section class="sidebar">
	                    <!-- Sidebar user panel -->
	                    <div class="user-panel">
	                        <div class="pull-left info">
	                            <br>
	                            <p>Olá, <?=ucfirst($userName)?></p>

	                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>

	                            <br><br>
	                            <div class="input-group-btn">
		                            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
		                            Perfil <span class="fa fa-caret-down"></span></button>

		                            <ul class="dropdown-menu">
		                            	<?php
		                            		foreach($userGroups as $group){
		                            			echo "<li>";
		                            			switch ($group->getName()) {
                                        			case GroupConstants::ACADEMIC_SECRETARY_GROUP:
                                        				$groupNameToDisplay = "Secretaria acadêmica";
                                        				break;
                                        			case GroupConstants::FINANCIAL_SECRETARY_GROUP:
                                        				$groupNameToDisplay = "Secretaria financeira";
                                        				break;
                                        			default:
                                        				$groupNameToDisplay = $group->getName();
                                        				break;
                                        		}
		                            			if($group->getName() == GroupConstants::SECRETARY_GROUP){
													continue;
												}else{
		                            				echo anchor($group->getProfileRoute(), ucfirst($groupNameToDisplay));
		                            			}
		                            			echo "</li>";
		                            		}
		                            	?>
		                            </ul>
	                            </div>
	                        </div>
	                    </div>
	                    <!-- search form -->
<!-- 	                    <form action="#" method="get" class="sidebar-form">
	                        <div class="input-group">
	                            <input type="text" name="q" class="form-control" placeholder="Search..."/>
	                            <span class="input-group-btn">
	                                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
	                            </span>
	                        </div>
	                    </form> -->
	                    <!-- /.search form -->
	                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                    <?php

                        foreach($userGroups as $group){
                            $groupName = $group->getName();
                            if($groupName == GroupConstants::SECRETARY_GROUP){
                                continue;
                            }else{
                                echo "<li class='treeview'>";

                                switch ($groupName) {
                                    case GroupConstants::ACADEMIC_SECRETARY_GROUP:
                                        $groupNameToShow = "Secretaria acadêmica";
                                        break;
                                    case GroupConstants::FINANCIAL_SECRETARY_GROUP:
                                        $groupNameToShow = "Secretaria financeira";
                                        break;
                                    default:
                                        $groupNameToShow = $groupName;
                                        break;
                                }
                                echo anchor("", ucfirst($groupNameToShow),"class='fa fa-folder-o'");

                                    echo "<ul class='treeview-menu'>";

                                    $groupPermissions = $group->getPermissions();
                                    foreach($groupPermissions as $permission){

                                        echo "<li>";
                                            if ($permission->getFunctionality() == PermissionConstants::RESEARCH_LINES_PERMISSION){
                                                continue;
                                            }else{
                                                echo anchor($permission->getFunctionality(), $permission->getName(), "class='fa fa-caret-right'");
                                            }
                                        echo "</li>";
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

<?php
if ($this->session->flashdata("success")) : ?>
	<p class="alert alert-success text-center"><?= $this->session->flashdata("success") ?></p>
<?php endif;
if ($this->session->flashdata("danger")) : ?>
	<p class="alert alert-danger text-center"><?= $this->session->flashdata("danger") ?></p>
<?php endif; ?>
