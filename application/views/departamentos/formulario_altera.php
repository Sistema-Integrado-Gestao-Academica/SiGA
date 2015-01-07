<div class="form-box" id="login-box"> 
	<div class="header">Alterar Departamento</div>
		<?php 
			form_open("departamento/altera");
			echo form_hidden("departamento_id", $departamento['id']);
		 ?>
	<div class="body bg-gray">
		<div class="form-group">	
		<?php
		echo form_label("Nome do departamento", "nome");
		echo form_input(array(
			"name" => "nome",
			"id" => "nome",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "255"
		));
		echo form_error("nome", $departamento['nome']);
		?>
		</div>
	</div>
	<div class="footer">
		<?php 
		echo form_button(array(
			"class" => "btn bg-olive btn-block",
			"content" => "Alterar",
			"type" => "sumbit"
		));
		
		echo form_close();
		?>
	</div>
		
</div>
