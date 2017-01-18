<script src=<?=base_url("js/program.js")?>></script>
<?php $programName = $program['acronym'];?>
<h2 class="principal">Informações no portal para o <?= $programName?></h2>
<?php

$programId = $program['id_program'];
echo "<h4> <a href='#add_field_form' data-toggle='collapse'><i class = 'fa fa-plus-circle'></i> Adicionar informação </a></h4>";

echo "<div id='add_field_form' class='collapse'>";

?>
	<h4>A informação será exibida no portal de acordo com o exemplo abaixo:</h4>
	<div class="panel box box-default">
      <div class="box-header with-border">
        <h4 class="box-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#extraInfo" aria-expanded="false" >
			Título <i class=" fa fa-caret-down"></i>
          </a>
        </h4>
      </div>
      <div id="extraInfo" class="panel-collapse collapse" aria-expanded="false">
        <div class="box-body">			
		Detalhes/Descrição
		<?php 
			echo "<br>";
			echo anchor(
				"#",
				"<i class='fa fa-cloud-download'></i> Baixar 'nome_do_arquivo'",
				"class='btn bg-olive'"
			);
		?>
		</div>
	 </div>
	</div>
<?php

echo "<hr>";
	displayFormToAddField($programId, "Adicionar informação", "add_info_btn");
	
echo "</div>";

echo "<br>";
echo "<hr>";

echo "<div id='add_result'>";

showExtraInfo($extraInfo, $programId);
