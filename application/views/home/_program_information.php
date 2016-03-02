<!--Set the information to the tabs-->
	<a class="nav-tabs-dropdown btn btn-block btn-primary"><h3>Sobre o <?php echo $program['program_name']
			?></h3></a>


		<ul id="nav-tabs-wrapper" class="nav nav-tabs nav-pills nav-stacked well">
			<li class="active"><a href="#vtab1" data-toggle="collapse" data-target="#resumo">
				O <?php	echo $program['acronym']?>
			</a></li>
			<div id="resumo" class="collapse">
				<p><?php echo $program['summary']?></p>
			</div>

			<li><a href="#vtab2" data-toggle="collapse" data-target="#historico">Histórico</a></li>
			<div id="historico" class="collapse">
				<p><?php echo $program['history']?></p>
			</div>

			<li><a href="#vtab3" data-toggle="collapse" data-target="#contato">Contato</a></li>
			<div id="contato" class="collapse">
				<p><?php echo $program['contact']?></p>
			</div>


			<li><a href="#vtab4" data-toggle="collapse" data-target="#linhasPesquisa">Linhas de Pesquisa</a></li>
			<div id="linhasPesquisa" class="collapse">
				<p><?php echo $program['research_line']?></p>
			</div>
		</ul>
	<?php 