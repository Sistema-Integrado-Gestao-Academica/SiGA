<script src=<?=base_url("js/production.js")?>></script>

<?php

$name = array(
	"name" => "name",
	"id" => "name",	
	"type" => "text",
	"class" => "form-control",
);	

$cpf = array(
	"name" => "cpf",
	"id" => "cpf",	
	"type" => "text",
	"class" => "form-control",
);	

$hidden = array(
	"id" => "production_id",
	"name" => "production_id",
	"type" => "hidden",
	"value" => $productionId
);

?>

<h3 class="principal"> Autores </h3>
<h4> <a href="#myModal" data-toggle="modal">  <i class="fa fa-plus-circle"></i>Adicionar Autor</a> </h4>
<?php 
echo "<div class=\"box-body table-responsive no-padding\">";
echo "<table class=\"table table-bordered table-hover\" id=\"authors_table\">";
echo "<tbody>";

buildTableHeaders(array(
	'CPF',
	'Nome',
));

echo "<tr>";
	echo "<td>";
	echo $author->getCpf();
	echo "</td>";

	echo "<td>";
	echo $author->getName();
	echo "</td>";

echo "</tr>";

buildTableEndDeclaration();
?>
	
<!-- Modal HTML -->
<div id="myModal" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
            	Novo autor
        </div>
        <div class="modal-body">
            
				<div class="col-lg-5">

					<?= form_input($hidden); ?>
					<div class="form-group">
						<?= form_label("CPF", "cpf") ?>
						<?= form_input($cpf)?>
					</div>

				</div>	

				<div class="col-lg-7">

					<div class="form-group">
						<?= form_label("Nome", "name") ?>
						<?= form_input($name)?>
					</div>

				</div>	
            <p class="text-warning"><medium>Não se esqueça de clicar em adicionar.</medium></p>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="add_coauthor">Adicionar</button>
			<?= form_close() ?>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            <div id="alert-msg">
        </div>
    </div>
</div>
</div>
</div>

<br>

<div class="col-lg-3" id="center_btn_form">

	<?= anchor("intellectual_production", 'Finalizar adição de autores', "class='btn btn-success btn-block'") ?>
</div>
