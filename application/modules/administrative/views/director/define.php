<h2 class="principal"> Diretor(a) do departamento</h2>

<?php
	
	$currentDirectorId = NULL;
	if (!is_null($currentDirector)){
		echo "<h3>Diretor(a) atual: <b>".$currentDirector->name."</b></h3>";
		$currentDirectorId = $currentDirector->id;
	}
	
?>
<div class="row" align="center">
	<div class="col-lg-6 col-md-offset-3">

		<?= form_open("save_director") ?>
			<i class="fa fa-group"></i> <?= form_label("Lista de Docentes", "teachers_list") ?>
			<?= form_dropdown("new_director", $teachers, '', ['class' => "form-control", 'id' => "teachers"]) ?>
		 	<br>
		<div class="col-lg-7 col-md-offset-3">
		 	<?= form_button(array(
			    "id" => "new_expense_nature",
			    "class" => "btn bg-olive btn-block",
			    "content" => "Definir como novo(a) diretor(a)",
			    "type" => "submit"
			)) ?>
		</div>
            <?= form_hidden("current_director", $currentDirectorId) ?>
		<?= form_close() ?>
	</div>
</div>