<?php const MAX_QUANTITY_OF_TABS = 20; ?>

</div></aside>
<div class="container">
</br></br>
</br>
<img src="<?php echo base_url('img/base_logo_siga.png'); ?>" alt="Logo SiGA" class="img-responsive img-center" style="width:240px;height:110px;" />
</br></br>
</br>

<!--Creating the tabs-->
<ul class="nav nav-tabs">
	<?php
		for($i = 0; $i < $quantityOfPrograms; $i++){ 
			
			$programToTab = $programs[$i];

			if($i != MAX_QUANTITY_OF_TABS){  

				if ($program['id_program'] == $programToTab['id_program']){ ?>
					<li class=active>
				<?php 
				}
				else{ ?>
					<li>	
				<?php
				} 				 
				echo anchor(
								"program/{$programs[$i]['id_program']}",
								$programToTab['acronym'],
								"class='btn-lg'"); 	?>
				</li>
			<?php }	
			else { ?>
				<li> 
				<?php echo anchor(
									"/",
									"Outros"	,
									"class='btn-lg'"); ?>
				</li>
			<?php
				break;
			} ?>

		<?php
			
		}	
	?>	
</ul>

<!--Set the information to the tabs-->
	<a class="nav-tabs-dropdown btn btn-block btn-primary"><h3><?php echo $program['program_name']
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