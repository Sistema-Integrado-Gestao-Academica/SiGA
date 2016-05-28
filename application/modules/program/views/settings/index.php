<h1 class="principal">Configurações</h1>

	<h4><span class="fa fa-calendar-o"> <b> Semestre atual </b></span></h4>
	<h4><?=$current_semester['description']?></h4>

	<div class="col-md-2">	
		<a href='#admin_password' data-toggle="collapse" class="btn bg-olive btn-block"> Avançar semestre</a> 
	</div>
		
	<div id=<?="admin_password"?> class="panel-collapse collapse" aria-expanded="false">
		<div class="box-body">		
			<div class="form-box" id="login-box" align="center"> 
    			<div class="header"></div>
				<?=	form_open('program/settings/saveSemester') ?>
		        <div class="body bg-gray">
		            <div class="form-group">
					<?= form_hidden('current_semester_id', $current_semester['id_semester']) ?>
					<?= form_label('Digite sua senha', "password") ?>
					<?php if ($edit): ?>
				        <?= form_input(array(
				            "name" => "password",
				            "id" => "password",
				            "type" => "password",
				            "class" => "form-campo",
				            "maxlength" => "50",
				            "class" => "form-control",
				        )) ?>
				        <br>
	                </div>
				</div>
		        <div class="footer">
						<?= form_button(array(
				            "class" => "btn btn-success",
							'content' => 'Avançar',
				            "type" => "submit"
						)) ?>
					<?php endif ?>
		
				<?= form_close() ?>
				</div>				
			</div>
		</div>
	</div>
