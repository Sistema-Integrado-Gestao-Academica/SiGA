<!--Set the information to the tabs-->
	<a class="nav-tabs-dropdown btn btn-block btn-primary"><h3>Outros Programas</h3></a>
	<?php
		$i = 0; 
		foreach ($programs as $program){ 

			if($i >= MAX_QUANTITY_OF_TABS){
				$coursesProgram = $coursesPrograms[$program['id_program']];?>

				<ul id="nav-tabs-wrapper" class="nav nav-tabs nav-pills nav-stacked well">
					<li class="active">
						<a href="#vtab1" data-toggle="collapse" data-target=<?="#resumo".$program['id_program']?>>
						<p>O <?php	echo $program['acronym']?></p>
						</a>
					</li>
					
					<div id=<?="resumo".$program['id_program']?> class="collapse">
						<?php 
						if(!empty($program['summary'])){ 
					
							echo "<p>{$program['summary']}</p>";							
						
						}?>				
												
						<?php 
						if(!empty($program['history'])){ 
							
							echo "<b>Histórico</b>";
							echo "<p>{$program['history']}</p>";							
						}?>				
								

						<?php 
						if(!empty($program['contact'])){ 
							
							echo "<b>Contato</b>";							
							echo "<p>{$program['contact']}</p>";							
						}?>				
						

						<?php
						
						$researchLines = $coursesProgram['researchLines']; 
						if(!empty($researchLines)){ 
							
							echo "<b>Linhas de Pesquisa</b>";

							foreach ($researchLines as $researchLine) {

								echo "<p>{$researchLine}</p>";							
							}
						}?>				
						
						<?php
						$coursesName = $coursesProgram['coursesName'];

						if(!empty($coursesName)){  
							echo "<b>Lista de Cursos</b>";
							foreach ($coursesName as $course) { 
								echo "<p>{$course}</p>";					
							}
						}?>		


						<?php
						$teachers = $coursesProgram['teachers'];

						if(!empty($teachers)){  
							echo "<b>Corpo Docente</b>";
							foreach ($teachers as $teacher) { 
								echo "<p>{$teacher}</p>";					
							}
						}?>				
					</div>

				</ul>
			<?php 
			}			
			
			$i++;
		} ?>
	</div>
		
