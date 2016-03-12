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
			

		<?php $programSummary = $program['summary'];

			if (!empty($programSummary)) {?>

				<div id=<?="resumo".$program['id_program']?> class="collapse">
					<p><?php echo $programSummary?></p>
				</div>
				
		<?php
		}
		$programHistory = $program['history'];

			if (!empty($programHistory)) {?>
	
				<li>
					<a href="#vtab2" data-toggle="collapse" data-target=<?="#historico".$program['id_program']?>><b>Histórico</b></a>
				</li>

				<div id=<?="historico".$program['id_program']?> class="collapse">
					<p><?php echo $programHistory?></p>
				</div>

		<?php
			}

		$programContact = $program['contact'];

			if (!empty($programContact)) {?>
	
				<li>
					<a href="#vtab3" data-toggle="collapse" data-target=<?="#contato".$program['id_program']?>><b>Contato</b></a>
				</li>

				<div id=<?="contato".$program['id_program']?> class="collapse">
					<p><?php echo $program['contact']?></p>
				</div>
		
		<?php
			}
			
			if ($coursesPrograms !== FALSE) {
			
				$coursesProgram = $coursesPrograms[$program['id_program']];
				$researchLines = $coursesProgram['researchLines']; 
				
				if(!empty($researchLines)){ ?>

					<li>
					<a href="#vtab4" data-toggle="collapse" data-target=<?="#linhasPesquisa".$program['id_program']?>><b>Linhas de Pesquisa</b>
					</a>
					</li>
					<div id=<?="linhasPesquisa".$program['id_program']?> class="collapse">


				<?php
					foreach ($researchLines as $researchLine) {

						echo "<p>{$researchLine}</p>";							
					}
				?> 

					</div>

				<?php 
				}
				
				$coursesName = $coursesProgram['coursesName'];

					if(!empty($coursesName)){ ?>
						<li>
							<a href="#vtab5" data-toggle="collapse" data-target=<?="#courses".$program['id_program']?>><b>Lista de Cursos</b>
							</a>
						</li>
						<div id=<?="courses".$program['id_program']?> class="collapse">	
					
					<?php 
						foreach ($coursesName as $course) { 

							echo "<p>{$course}</p>";					
						}
					?>				
						
						</div>

				<?php 
					}

					$teachers = $coursesProgram['teachers'];

					if(!empty($teachers)){ ?>

						<li>
							<a href="#vtab6" data-toggle="collapse" data-target=<?="#teachers".$program['id_program']?>><b>Corpo Docente</b>
							</a>
						</li>
						<div id=<?="teachers".$program['id_program']?> class="collapse">	
					<?php 
						foreach ($teachers as $teacher) { 
							echo "<p>{$teacher}</p>";					
						}
					?>				
					
						</div>
			
					
			<?php
					} 
				}

			?>
		</ul>

	<?php }
		else { 
			include("_other_programs.php"); 

		} ?>
</div>
	