
<?php 
	
$summary = array(
	"name" => "summary",
	"id" => "summary",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "4000",
	"placeholder" => "Máximo de 4000 caracteres",

);

$lattes = array(
	"name" => "lattes",
	"id" => "lattes",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control"
);

$submitBtn = array(
	"id" => "update_profile",
	"class" => "btn bg-olive btn-block",
	"content" => "Atualizar perfil",
	"type" => "submit"
);
?>

<div class="form-box" id="login-box">
	<div class="header">Atualizar Perfil</div>
	<?= form_open("teacher/saveProfile") ?>
	<?= form_hidden("teacher", $teacher) ?>


		<div class="body bg-gray">
			<div class="form-group">	
				<?= form_label("Resumo", "summary") ?>
				<?= form_textarea($summary) ?>
				<?= form_error("summary") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Link para o Lattes", "lattes") ?>
				<?= form_input($lattes) ?>
				<?= form_error("lattes") ?>
			</div>
		</div>

		<div class="footer">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button($submitBtn) ?>
				</div>
				<div class="col-xs-6">
					<?php 
						echo anchor('mastermind_home', 'Voltar', "class='btn bg-olive btn-block'");
					?>

				</div>
			</div>
		</div>
	<?= form_close() ?>
</div>
