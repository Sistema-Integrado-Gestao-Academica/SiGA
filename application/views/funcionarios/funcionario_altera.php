<div class="form-box-logged" id="login-box"> 
	<div class="header">Alterar Funcionário</div>
		<?php 
			echo form_open("funcionario/altera");
			echo form_hidden("setor_id", $funcionario['id']);
		?>
	<div class="body bg-gray">
		<div class="form-group">	
		<?php
		echo form_label("Nome do Funcionário", "nome");
		echo form_input(array(
			"name" => "nome",
			"id" => "nome",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "255",
			"value" => set_value("nome", $funcionario['nome'])
			));

		echo form_error("nome");
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
