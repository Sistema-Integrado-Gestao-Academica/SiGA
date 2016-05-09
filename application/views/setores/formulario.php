<h2 class="principal">Setores</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Setores cadastrados</h3></td>
		<?php if ($setores): ?>
			<td><h3 class="text-center">Ações</h3></td>
		<?php endif ?>
	</tr>

	<?php if ($setores): ?>
		<?php foreach ($setores as $setor): ?>
			<tr>
				<td><?=$setor['nome']?></td>
		
				<td>
					<?= anchor("setores/{$setor['id']}", "<span class='glyphicon glyphicon-edit'></span>", "class='btn btn-primary btn-editar btn-sm'") ?>

					<?= form_open('setor/remove') ?>
						<?= form_hidden('setor_id', $setor['id']) ?>
						<button type="submit" class="btn btn-danger btn-remover btn-sm" style="margin: -20px auto auto 100px;">
							<span class="glyphicon glyphicon-remove"></span>
						</button>
					<?= form_close() ?>
				</td>
			</tr>
		<?php endforeach ?>
	<?php else: ?>
		<tr>
			<td><h3><label class="label label-default"> Não existem setores cadastrados</label></h3></td>
		</tr>
	<?php endif ?>
</table>

<div class="form-box-logged" id="login-box"> 
	<div class="header">Cadastrar um novo setor</div>
	<?= form_open("setor/novo") ?>
		<div class="body bg-gray">
			<div class="form-group">
				<?= form_label("Cadastrar um novo setor", "nome") ?>
				<?= form_input(array(
					"name" => "nome",
					"id" => "nome",
					"type" => "text",
					"class" => "form-campo",
					"class" => "form-control",
					"maxlength" => "255"
				)) ?>
				<?= form_error("nome") ?>
			</div>
		</div>

		<div class="footer">
			<?= form_button(array(
				"class" => "btn bg-olive btn-block",
				"type" => "sumbit",
				"content" => "Cadastrar"
			)) ?>
		</div>
	<?= form_close() ?>
</div>
