<div class="form-box-logged" id="login-box"> 
	<div class="header">Alterar Função</div>
		<?php
			echo form_open("funcao/altera");
			echo form_hidden("funcao_id", $funcao['id']);
		?>
	<div class="body bg-gray">
		<div class="form-group">	
		<?php
		echo form_label("Alterar função", "nome");
		echo form_input(array(
			"name" => "nome",
			"id" => "nome",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "255",
			"value" => set_value("nome", $funcao['nome'])
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
