<h3 class="principal"> Editar Autor <?= $author['author_name']?> </h3>
<script src=<?=base_url("js/production.js")?>></script>

<?php

$name = array(
	"name" => "name",
	"id" => "name",	
	"type" => "text",
	"class" => "form-control",
	"value" => $author['author_name']
);	

$cpf = array(
	"name" => "cpf",
	"id" => "cpf",	
	"type" => "text",
	"class" => "form-control",
	"value" => $author['cpf'],
);	

$order = array(
	"name" => "order",
	"id" => "order",	
	"type" => "number",
	"class" => "form-control",
	"min" => 2,
	"value" => $author['order'],
);	

$hidden = array(
	"id" => "production_id",
	"name" => "production_id",
	"type" => "hidden",
	"value" => $productionId
);

?>
<?= form_open("update_coauthor/{$productionId}/{$author['order']}") ?>
	<?php include '_coauthor_modal.php'; ?>
	<button type="submit" class="btn btn-success">Editar</button>
<?= form_close() ?>

