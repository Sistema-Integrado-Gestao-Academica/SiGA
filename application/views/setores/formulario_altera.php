<div class="form-box-logged" id="login-box"> 
	<div class="header">Alterar Setor</div>
	<?= form_open("setor/altera") ?>
		<?= form_hidden("setor_id", $setor['id']) ?>
		<div class="body bg-gray">
			<div class="form-group">	
				<?= form_label("Nome do setor", "nome") ?>
				<?= form_input(array(
					"name" => "nome",
					"id" => "nome",
					"type" => "text",
					"class" => "form-control",
					"class" => "form-campo",
					"maxlength" => "255",
					"value" => set_value("nome", $setor['nome'])
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
					<?= anchor('setores', 'Voltar', "class='btn bg-olive btn-block'") ?>
				</div>
			</div>
		</div>
	<?= form_close() ?>
</div>
