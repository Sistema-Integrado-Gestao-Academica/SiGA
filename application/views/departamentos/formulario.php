<h2 class="principal">Departamentos</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Departamentos cadastrados</h3></td>
		<?php if ($departamentos): ?>
			<td><h3 class="text-center">Ações</h3></td>
		<?php endif ?>
	</tr>

	<?php if ($departamentos): ?>
		<?php foreach ($departamentos as $departamento): ?>
			<tr>
				<td><?=$departamento['nome']?></td>
		
				<td>
					<?= anchor("departamentos/{$departamento['id']}", "<span class='glyphicon glyphicon-edit'></span>", "class='btn btn-primary btn-editar btn-sm'") ?>

					<?= form_open('departamento/remove') ?>
						<?= form_hidden('departamento_id', $departamento['id']) ?>
						<button type="submit" class="btn btn-danger btn-remover btn-sm" style="margin: -20px auto auto 100px;">
							<span class="glyphicon glyphicon-remove"></span>
						</button>
					<?= form_close() ?>
				</td>
			</tr>
		<?php endforeach ?>
	<?php else: ?>
		<tr>
			<td><h3><label class="label label-default"> Não existem departamentos cadastrados</label></h3></td>
		</tr>
	<?php endif ?>
</table>

<div class="form-box-logged" id="login-box"> 
	<div class="header">Cadastrar um novo departamento</div>
	<?= form_open("departamento/novo") ?>
		<div class="body bg-gray">
			<div class="form-group">	
				<?= form_label("Cadastrar um novo departamento", "nome") ?>
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
