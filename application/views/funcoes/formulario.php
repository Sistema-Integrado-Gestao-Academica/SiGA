<h2 class="principal">Funções</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Funções cadastradas</h3></td>
	</tr>

<?php 
	if($funcoes){
		foreach ($funcoes as $funcao) { ?>
			<tr>
				<td>
				<?=$funcao['nome']?>
				</td>
		
				<td>
				<?=anchor("funcoes/{$funcao['id']}", "Editar", array(
					"class" => "btn btn-primary btn-editar",
					"type" => "sumbit",
					"content" => "Editar"
				))?>
				
				<?php 
				echo form_open("funcao/remove");
				echo form_hidden("funcao_id", $funcao['id']);
				echo form_button(array(
					"class" => "btn btn-danger btn-remover",
					"type" => "sumbit",
					"content" => "Remover"
				));
				echo form_close();
				?>
				</td>
			</tr>
<?php } 
	}else{ ?>
		<tr>
			<td>
				<h3>
					<label class="label label-default"> Não existem funções cadastrados</label>
				</h3>
			</td>
		</tr>
	<?php }?>
</table>

<div class="form-box-logged" id="login-box"> 
	<div class="header">Cadastrar uma nova função</div>
		<?= form_open("funcao/novo") ?>
	<div class="body bg-gray">
		<div class="form-group">	
		<?php
		echo form_label("Cadastrar uma nova função", "nome");
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