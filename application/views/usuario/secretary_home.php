<?php 
	$session = $this->session->userdata("current_user");
	$userIsAcademicSecretary = array_key_exists('courseSecretaryAcademic', $session['user_permissions']);
?>
<br>
<br>
<br>
<?php  if ($userIsAcademicSecretary):?>
<div class="col-lg-12 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
		    <h2 align="center">Bem vindo a Secretaria Acadêmica!</h2>

		    <p>

		    <div class="list-group">
				<?=anchor('usuario/secretary_enrollStudent', "Matricular Alunos", "class='list-group-item' style='width:20%;'");?>
				<?=anchor('usuario/secretary_offerList', "Listas de Oferta", "class='list-group-item' style='width:20%;'");?>
				<?=anchor('usuario/secretary_courseSyllabus', "Currículos de cursos", "class='list-group-item' style='width:20%;'");?>
			</div>
        	</p>

		</div>
		<div class="icon">
		    <i class="fa fa-book"></i>
		</div>
	</div>
</div>
<?php else:?>
<div class="col-lg-12 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
		    <h2 align="center">Bem vindo a Secretaria Financeira!</h2>

		    <p>

		    <div class="list-group">
				<?=anchor('planoorcamentario', "Cadastrar Plano Orçmentário", "class='list-group-item' style='width:30%;'");?>
			</div>
        	</p>

		</div>
		<div class="icon">
		    <i class="fa fa-book"></i>
		</div>
	</div>
</div>
<?php endif;?>