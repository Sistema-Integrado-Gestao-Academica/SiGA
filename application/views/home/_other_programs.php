<!--Set the information to the tabs-->
	<a class="nav-tabs-dropdown btn btn-block btn-primary"><h3>Outros Programas</h3></a>
	<?php 
		for($i = MAX_QUANTITY_OF_TABS; $i < $quantityOfPrograms; $i++){ 

			$program = $programs[$i]; ?>

			<ul id="nav-tabs-wrapper" class="nav nav-tabs nav-pills nav-stacked well">
				<li class="active">
					<a href="#vtab1" data-toggle="collapse" data-target=<?="#resumo".$program['id_program']?>>
					<p>O <?php	echo $program['acronym']?></p>
					</a>
				</li>
				
				<div id=<?="resumo".$program['id_program']?> class="collapse">
					<p><?php echo $program['summary']?></p>
					<b>Histórico</b>
					<p><?php echo $program['history']?></p>
					<b>Contato</b>
					<p><?php echo $program['contact']?></p>
					<b>Linha de Pesquisa</b>
					<p><?php echo $program['research_line']?></p>
				</div>

			</ul>
			<?php } ?>
	</div>
		
