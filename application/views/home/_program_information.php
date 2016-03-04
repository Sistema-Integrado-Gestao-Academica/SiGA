<!--Set the information to the tabs-->
<?php 
	if($isFirst){
		echo "<div class='tab-pane fade in active' id='".$tabId."'>";
	}
	else{
		echo "<div class='tab-pane fade' id='".$tabId."'>";
	}


	if($tabId != "program".MAX_QUANTITY_OF_TABS){

?>
	<a class="nav-tabs-dropdown btn btn-block btn-primary"><h3>Sobre o <?php echo $program['program_name']
			?></h3></a>


		<ul id="nav-tabs-wrapper" class="nav nav-tabs nav-pills nav-stacked well">
			<li class="active">
				<a href="#vtab1" data-toggle="collapse" data-target=<?="#resumo".$program['id_program']?>>
				O <?php	echo $program['acronym']?>
				</a>
			</li>
			
			<div id=<?="resumo".$program['id_program']?> class="collapse">
				<p><?php echo $program['summary']?></p>
			</div>

			<li>
				<a href="#vtab2" data-toggle="collapse" data-target=<?="#historico".$program['id_program']?>><b>Histórico</b></a>
			</li>

			<div id=<?="historico".$program['id_program']?> class="collapse">
				<p><?php echo $program['history']?></p>
			</div>

			<li>
				<a href="#vtab3" data-toggle="collapse" data-target=<?="#contato".$program['id_program']?>><b>Contato</b></a>
			</li>

			<div id=<?="contato".$program['id_program']?> class="collapse">
				<p><?php echo $program['contact']?></p>
			</div>

			<li>
				<a href="#vtab4" data-toggle="collapse" data-target=<?="#linhasPesquisa".$program['id_program']?>><b>Linhas de Pesquisa</b>
				</a>
			</li>

			<div id=<?="linhasPesquisa".$program['id_program']?> class="collapse">
				<p><?php echo $program['research_line']?></p>
			</div>
		</ul>

	<?php }
		else { 
			include("_other_programs.php"); 

		} ?>
</div>
	