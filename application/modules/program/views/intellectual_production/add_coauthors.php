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

$order = array(
	"name" => "order",
	"id" => "order",	
	"type" => "number",
	"class" => "form-control",
	"min" => 2
);	

$hidden = array(
	"id" => "production_id",
	"name" => "production_id",
	"type" => "hidden",
	"value" => $productionId
);

?>

<h3 class="principal"> Autores </h3>
<h4> <a href="#author_modal" data-toggle="modal">  <i class="fa fa-plus-circle"></i>Adicionar Autor</a> </h4>
<?php 
echo "<div class=\"box-body table-responsive no-padding\">";
echo "<table class=\"table table-bordered table-hover\" id=\"authors_table\">";
echo "<tbody>";

buildTableHeaders(array(
	'Ordem',
	'CPF',
	'Nome',
	'',
));

if($authors !== FALSE){
	foreach ($authors as $coauthor) {
		
		echo "<tr>";

		echo "<td data-order={$coauthor['order']}>";
		echo $coauthor['order'];
		echo '</td>';

		echo "<td data-cpf={$coauthor['cpf']}>";
		echo $coauthor['cpf'];
		echo "</td>";


		echo "<td data-id='{$productionId}'>";
		echo $coauthor['author_name'];
		echo "</td>";
				
		echo '<td>';
		if($coauthor['first_author'] !== TRUE){
			echo anchor("edit_coauthor/{$productionId}/{$coauthor['order']}","Editar", "class='btn btn-primary'");
			echo " <button onclick='RemoveTableRow(this)' type='button' class='btn btn-danger'>Remover</button>";

		}
		echo '</td>';

		echo "</tr>";
	}
}

buildTableEndDeclaration();
?>
	
<!-- Modal HTML -->
<div id="author_modal" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
            	Novo autor
        </div>
        <div class="modal-body">
		    <form id='form'> 
		        <?php 
			        include '_coauthor_modal.php'; 
			    ?>
	       </form>
		    <br>
		    <br>
		</div>
    	<p class="text-warning"><medium>Não se esqueça de clicar em adicionar.</medium></p>
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