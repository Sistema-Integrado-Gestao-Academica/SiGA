<script src=<?=base_url("js/program.js")?>></script>

<?php $infoTitle = $info['title'];?>
<h2 class="principal">Editando informação: <b><?= $infoTitle?></b></h2>
<?php

$fileExists = !is_null($info['file_path']);
displayFormToAddField($info['id'], "Editar informação", "edit_info_btn", $infoTitle, $info['details'], $fileExists);

$programId = $info['id_program'];
echo "<div align='left'>";
echo anchor(
	"program/program/defineNewFieldToShowInPortal/{$programId}",
	"Voltar",
	"class='btn btn-danger'"
);
echo "</div>";