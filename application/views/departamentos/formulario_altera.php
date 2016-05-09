<div class="form-box-logged" id="login-box"> 
	<div class="header">Alterar Departamento</div>
	<?= form_open("departamento/altera") ?>
		<?= form_hidden("departamento_id", $departamento['id']) ?>
		<div class="body bg-gray">
			<div class="form-group">	
			<?= form_label("Nome do departamento", "nome") ?>
			<?= form_input(array(
				"name" => "nome",
				"id" => "nome",
				"type" => "text",
				"class" => "form-campo",
				"class" => "form-control",
				"maxlength" => "255",
				"value" => set_value("nome", $departamento['nome'])
			)) ?>
			<?= form_error("nome") ?>
			</div>
		</div>

		<div class="footer">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button(array(
						"class" => "btn bg-olive btn-block",
						"content" => "Alterar",
						"type" => "sumbit"
					)) ?>
				</div>
				<div class="col-xs-6">
					<?= anchor('departamentos', 'Voltar', "class='btn bg-olive btn-block'") ?>
				</div>
			</div>
		</div>
	<?= form_close() ?>
</div>
