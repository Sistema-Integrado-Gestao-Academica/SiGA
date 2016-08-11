<!--Set the information to the tabs-->
<?php 
	if($isFirst){
		echo "<div class='tab-pane fade in active' id='".$tabId."'>";
	}
	else{
		echo "<div class='tab-pane fade' id='".$tabId."'>";
	}

	if($tabId != "program".MAX_QUANTITY_OF_TABS){

	$programId = $program->getId();
	
?>
    <a class="nav-tabs-dropdown btn btn-block btn-gray"><h3>Sobre o <?php echo $program->getName()
	?></h3></a>
	<div class="box-body">
      <div class="box-group" id=<?="accordion".$programId?>>
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            <div class="panel box box-default">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent=<?="#accordion".$programId?> href=<?="#summary".$programId?> aria-expanded="false">
					O <?php	echo $program->getAcronym()?> <i class=" fa fa-caret-down"></i>
                  </a>
                </h4>
              </div>
              <div id=<?="summary".$programId?> class="panel-collapse collapse" aria-expanded="false">
                <div class="box-body">			

				<?php $programSummary = $program->getSummary();

					if (!empty($programSummary)) {?>

							<p><?php echo $programSummary?></p>
						
				<?php
				} ?>

                </div>
              </div>
            </div>


	
<?php		$programHistory = $program->getHistory();

			if (!empty($programHistory)) {?>
	
            <div class="panel box box-default">
              <div class="box-header with-border">
                <h4 class="box-title">
                  <a data-toggle="collapse" data-parent=<?="#accordion".$programId?> href=<?="#history".$programId?> aria-expanded="false">
					Histórico <i class=" fa fa-caret-down"></i>
                  </a>
                </h4>
              </div>
              	<div id=<?="history".$programId?> class="panel-collapse collapse" aria-expanded="false">
                <div class="box-body">			
					<p><?php echo $programHistory?></p>
					</div>
				</div>
			</div>

		<?php
			}

		$programContact = $program->getContact();

			if (!empty($programContact) || !empty($program->getCourses())) {?>
	
				<div class="panel box box-default">
              		<div class="box-header with-border">
	                <h4 class="box-title">
	                  <a data-toggle="collapse" data-parent=<?="#accordion".$programId?> href=<?="#contact".$programId?> aria-expanded="false" class="collapsed"> Secretaria e Contato <i class=" fa fa-caret-down"></i>
	                  </a>
	                </h4>
	              </div>
	              	<div id=<?="contact".$programId?> class="panel-collapse collapse" aria-expanded="false">
	                	<div class="box-body">			
	                <?php if(!empty($programContact)){ ?>
						<p><b>Contatos</b></p>
							<p><?php echo $program->getContact()?></p>
					<?php
	                	}
			}
			if (!empty($secretaries)) {
				$programSecretaries = $secretaries[$programId];
				if(!empty($programSecretaries) && $programSecretaries !== FALSE){

					foreach ($programSecretaries as $secretary) {

						if($secretary !== FALSE){
							
							echo "<p><b>Secretários</b></p>";
							foreach ($secretary as $courseSecretary) {
								echo "<p>{$courseSecretary['name']}</p>";							
							}
						}
						
					}?>
					</div>
				</div>
			</div>
			<?php
				}
			} 

			if(!empty($researchLines)){
				$researchLiness = $researchLines[$programId];
				if(!empty($researchLiness)){ 
					?>
					<div class="panel box box-default">
		              <div class="box-header with-border">
		                <h4 class="box-title">
		                  <a data-toggle="collapse" data-parent=<?="#accordion".$programId?> href=<?="#research".$programId?> aria-expanded="false" >
							Linhas de Pesquisa <i class=" fa fa-caret-down"></i>
		                  </a>
		                </h4>
		              </div>
		              <div id=<?="research".$programId?> class="panel-collapse collapse" aria-expanded="false" >
		                <div class="box-body">			
						<?php
							foreach ($researchLiness as $researchLine) {
								foreach ($researchLine as $researchLineName) {

									echo "<p>{$researchLineName}</p>";							
								}
							}
						?> 

						</div>
					 </div>
					</div>	

				<?php 
				}
			}

	   		include ('_courses_information.php'); ?>
        </div>
    </div>
	<?php }
		else { 
			include("_other_programs.php"); 

		} ?>
</div>
