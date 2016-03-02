
<h1 class="bemvindoLogin">SiGA</h1>
</br></br>
</br>

<!--Creating the tabs-->
<ul class="nav nav-tabs">
	<li class = "active"> 
		<?php echo anchor(
						"/",
						$firstProgram['acronym'],
						"class='btn-lg'"); 
		?>
	</li>
	<?php
		for($i = 1; $i < $quantityOfPrograms; $i++){ ?>
			<li> 
				<?php echo anchor(
							"program/showProgram/{$programs[$i]['id_program']}",
							$programs[$i]['acronym'],
							"class='btn-lg'"); 
				?>
			</li>
		<?php
			if($i == MAX_QUANTITY_OF_TABS){  ?>
				<li> 
				<?php echo anchor(
							"/",
							"Outros"	,
							"class='btn-lg'"); 
				?>
				</li>
		<?php
				break;
			}	
		}	
	?>	
</ul>
<!--Set the info the tabs-->
	<a class="nav-tabs-dropdown btn btn-block btn-primary"><h3><?php echo $firstProgram['program_name']
			?></h3></a>


		<ul id="nav-tabs-wrapper" class="nav nav-tabs nav-pills nav-stacked well">
			<li class="active"><a href="#vtab1" data-toggle="collapse" data-target="#resumo">
				O <?php	echo $firstProgram['acronym']?>
			</a></li>
			<div id="resumo" class="collapse">
				<p><?php echo $firstProgram['summary']?></p>
			</div>

			<li><a href="#vtab2" data-toggle="collapse" data-target="#historico">Histórico</a></li>
			<div id="historico" class="collapse">
				<p><?php echo $firstProgram['history']?></p>
			</div>

			<li><a href="#vtab3" data-toggle="collapse" data-target="#contato">Contato</a></li>
			<div id="contato" class="collapse">
				<p><?php echo $firstProgram['contact']?></p>
			</div>


			<li><a href="#vtab4" data-toggle="collapse" data-target="#linhasPesquisa">Linhas de Pesquisa</a></li>
			<div id="linhasPesquisa" class="collapse">
				<p><?php echo $firstProgram['research_line']?></p>
			</div>
		</ul>
	<?php 