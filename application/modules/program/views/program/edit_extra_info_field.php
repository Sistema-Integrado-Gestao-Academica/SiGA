<script src=<?=base_url("js/program.js")?>></script>

<?php $infoTitle = $info['title'];?>
<h2 class="principal">Editando informação: <b><?= $infoTitle?></b></h2>
<?php
$programId = $info['id_program'];

$fileExists = !is_null($info['file_path']);
displayFormToAddField($programId, "Editar informação", "edit_info_btn", $info['id'], $infoTitle, $info['details'], $fileExists);

echo "<div id='add_result'>";
echo "</div>";
echo "<div align='left'>";
echo anchor(
	"program/program/defineNewFieldToShowInPortal/{$programId}",
	"Voltar",
	"class='btn btn-danger'"
);
echo "</div>";