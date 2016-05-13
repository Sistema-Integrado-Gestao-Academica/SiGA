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
    <a class="nav-tabs-dropdown btn btn-block btn-gray"><h3>Sobre o <?php echo $program['program_name']
	?></h3></a>
	<!-- <ul class='nav nav-tabs nav-justified'>
		<li class='active'>
			<a href="#secretary" data-toggle="tab" aria-expanded="true">Secretaria e Contato</a>
		</li>
		<li>
			<a href="#secretary" data-toggle="tab" aria-expanded="true">Cursos</a>
		</li>
		<li class='active'>
			<a href="#secretary" data-toggle="tab" aria-expanded="true">Coordenador</a>
		</li>
		<li class='active'>
			<a href="#secretary" data-toggle="tab" aria-expanded="true">Docentes</a>
		</li> -->
	<div class="box-body">
      <div class="box-group" id=<?="accordion".$program['id_program']?>>
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-default">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent=<?="#accordion".$program['id_program']?> href=<?="#summary".$program['id_program']?> aria-expanded="false">
					O <?php	echo $program['acronym']?> <i class=" fa fa-caret-down"></i>
                  </a>
                </h4>
              </div>
              <div id=<?="summary".$program['id_program']?> class="panel-collapse collapse" aria-expanded="false">
                <div class="box-body">			

				<?php $programSummary = $program['summary'];

					if (!empty($programSummary)) {?>

							<p><?php echo $programSummary?></p>
						
				<?php
				} ?>

                </div>
              </div>
            </div>


	
<?php		$programHistory = $program['history'];

			if (!empty($programHistory)) {?>
	
            <div class="panel box box-default">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent=<?="#accordion".$program['id_program']?> href=<?="#history".$program['id_program']?> aria-expanded="false">
					Histórico <i class=" fa fa-caret-down"></i>
                  </a>
                </h4>
              </div>
              	<div id=<?="history".$program['id_program']?> class="panel-collapse collapse" aria-expanded="false">
                <div class="box-body">			
					<p><?php echo $programHistory?></p>
					</div>
				</div>
			</div>

		<?php
			}

		$programContact = $program['contact'];

			if (!empty($programContact) || $coursesPrograms !== FALSE) {?>
	
				<div class="panel box box-default">
              		<div class="box-header with-border">
	                <h4 class="box-title">
	                  <a data-toggle="collapse" data-parent=<?="#accordion".$program['id_program']?> href=<?="#contact".$program['id_program']?> aria-expanded="false" class="collapsed"> Secretaria e Contato <i class=" fa fa-caret-down"></i>
	                  </a>
	                </h4>
	              </div>
	              	<div id=<?="contact".$program['id_program']?> class="panel-collapse collapse" aria-expanded="false">
	                	<div class="box-body">			
	                <?php if(!empty($programContact)){ ?>
						<p><b>Contatos</b></p>
							<p><?php echo $program['contact']?></p>
					<?php
	                	}
			}
			if ($coursesPrograms !== FALSE) {
							$coursesProgram = $coursesPrograms[$program['id_program']];
							
							$secretaries = $coursesProgram['secretaries']; 
							
							if(!empty($secretaries)){ 
								
								echo "<p><b>Secretários</b></p>";

								foreach ($secretaries as $secretary) {

									echo "<p>{$secretary['name']}</p>";							
								}
							}
							?> 
							</div>
						</div>
					</div>
		<?php

				$researchLines = $coursesProgram['researchLines']; 
				
				if(!empty($researchLines)){ ?>

					<div class="panel box box-default">
		              <div class="box-header with-border">
		                <h4 class="box-title">
		                  <a data-toggle="collapse" data-parent=<?="#accordion".$program['id_program']?> href=<?="#research".$program['id_program']?> aria-expanded="false" >
							Linhas de Pesquisa <i class=" fa fa-caret-down"></i>
		                  </a>
		                </h4>
		              </div>
		              <div id=<?="research".$program['id_program']?> class="panel-collapse collapse" aria-expanded="false" >
		                <div class="box-body">			
						<?php
							foreach ($researchLines as $researchLine) {

								echo "<p>{$researchLine}</p>";							
							}
						?> 

						</div>
					 </div>
					</div>	

				<?php 
				}

			} ?>
	   		<?php include ('_courses_information.php'); ?>
        </div>
    </div>
	<?php }
		else { 
			include("_other_programs.php"); 

		} ?>
</div>
