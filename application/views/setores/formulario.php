<h2 class="principal">Setores</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Setores cadastrados</h3></td>
	</tr>

<?php 
	if($setores){
		foreach ($setores as $setor) { ?>
			<tr>
				<td>
				<?=$setor['nome']?>
				</td>
		
				<td>
				<?=anchor("setores/{$setor['id']}", "Editar", array(
					"class" => "btn btn-primary btn-editar",
					"type" => "sumbit",
					"content" => "Editar"
				))?>
		
				<?php 
				echo form_open("setor/remove");
				echo form_hidden("setor_id", $setor['id']);
				echo form_button(array(
					"class" => "btn btn-danger btn-remover",
					"type" => "sumbit",
					"content" => "Remover"
				));
				echo form_close();
				?>
				</td>
			</tr>
<?php 	} 
	}else{ ?>
		<tr>
			<td>
				<h3>
					<label class="label label-default"> Não existem setores cadastrados</label>
				</h3>
			</td>
		</tr>
	<?php }?>
</table>

<div class="form-box-logged" id="login-box"> 
	<div class="header">Cadastrar um novo setor</div>
		<?= form_open("setor/novo") ?>
	<div class="body bg-gray">
		<div class="form-group">	
		<?php
		echo form_label("Cadastrar um novo setor", "nome");
		echo form_input(array(
			"name" => "nome",
			"id" => "nome",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "255"
		));
		echo form_error("nome");
		
		?>
		</div>
	</div>
		<div class="footer">
			<?php 
			echo form_button(array(
				"class" => "btn bg-olive btn-block",
				"type" => "sumbit",
				"content" => "Cadastrar"
			));
			
			echo form_close();
			?>
		</div>
		
</div>