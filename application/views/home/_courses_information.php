<?php            				
	
	if(!empty($coursesName)){
		$courses = $coursesName[$programId];
		if(!empty($courses)){ ?>

		<div class="panel box box-default">
			<div class="box-header with-border">
	    		<h4 class="box-title">
	      		<a data-toggle="collapse" data-parent=<?="#accordion".$program->getId()?> href=<?="#collapseTwo".$program->getId()?> class="collapsed" aria-expanded="false" >
	      		 Lista de Cursos <i class=" fa fa-caret-down"></i>
	      		</a>
	    	</h4>
	  	</div>
	  <div id=<?="collapseTwo".$program->getId()?> class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
	    <div class="box-body">
	    		<?php

	    			foreach ($courses as $course) {
	    				echo "<p>$course</p>";
	    			}

	    		?>
			</div>				
			</div>
  		</div>
<?php
		}
	} ?>
<div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent=<?="#accordion".$program->getId()?> href=<?="#coordinator".$program->getId()?> class="collapsed" aria-expanded="false">
                Coordenador <i class=" fa fa-caret-down"></i>
                <?php $coordinator = $program->getCoordinatorData()?>
              </a>
            </h4>
          </div>
          <div id=<?="coordinator".$program->getId()?> class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="box-body"> 
						<h4><?= $coordinator->getName()?></h4> 
									<b>Email:</b><br><?= $coordinator->getEmail()?> <br>
							<?php 
							
							$summary = $coordinator->getSummary();
							$lattesLink = $coordinator->getLattesLink();
							$researchLine = $coordinator->getResearchLine();

							$isSummaryBlank = empty($summary) || is_null($summary);
							$isLattesBlank = empty($lattesLink) ||  is_null($lattesLink);

							$isResearchLineBlank = empty($researchLine) ||  is_null($researchLine);
							if (!$isSummaryBlank || !$isLattesBlank || !$isResearchLineBlank){ ?>
									<?php if (!$isSummaryBlank){ ?>
										<b>Resumo:</b><br><?= $coordinator->getSummary()?>
									<?php } ?>
									<br>
									<?php if (!$isResearchLineBlank){ ?>
										<b>Linha de Pesquisa:</b><br><?= $coordinator->getResearchLine()?>
									<?php } ?>
									<br><br>
									<?php if (!$isLattesBlank){ ?>
										<a class="btn btn-primary btn-flat btn-xs" href=<?= $coordinator->getLattesLink()?>>Currículo Lattes</a>
									<?php } ?>
							<?php
							}	?>
				</div>
            </div>
 </div>

<?php
	if(!empty($teachers)){

		$programTeachers = $teachers[$programId];
		if(!empty($programTeachers)){ ?>

			<div class="panel box box-default">
	          <div class="box-header with-border">
	            <h4 class="box-title">
	              <a data-toggle="collapse" data-parent=<?="#accordion".$program->getId()?> href=<?="#collapseThree".$program->getId()?> class="collapsed" aria-expanded="false">
	                Corpo Docente <i class=" fa fa-caret-down"></i>
	              </a>
	            </h4>
	          </div>
	          <div id=<?="collapseThree".$program->getId()?> class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
	            <div class="box-body">
						<?php
							foreach ($programTeachers as $teacher) {

								if($teacher->getId() != $program->getCoordinatorId()){?>
								<div class="panel box box-default">
								          <div class="box-header with-border">
								            <h4 class="box-title">

	  										<a data-toggle="collapse" href=<?="#teacher".$program->getId().$teacher->getId()?> class="collapsed" aria-expanded="false">
	                						<?= $teacher->getName()?> <i class=" fa fa-caret-down"></i></a>
								            </h4>
								          </div>
									<?php

									$summary = $teacher->getSummary();
									$lattesLink = $teacher->getLattesLink();
									$researchLine = $teacher->getResearchLine();


									$isSummaryBlank = empty($summary) || is_null($summary);
									$isLattesBlank = empty($lattesLink) ||  is_null($lattesLink);

									$isResearchLineBlank = empty($researchLine) ||  is_null($researchLine);

									if (!$isSummaryBlank || !$isLattesBlank || $isResearchLineBlank){ ?>

										<div id=<?="teacher".$program->getId().$teacher->getId()?> class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
										    <div class="box-body">
												<b>Email:</b><br><?= $teacher->getEmail()?> <br>
												<?php if (!$isSummaryBlank){ ?>
													<b>Resumo:</b><br><?= $teacher->getSummary()?>
												<?php } ?>
												<br>
												<?php if (!$isResearchLineBlank){ ?>
													<b>Linha de Pesquisa:</b><br><?= $teacher->getResearchLine()?>
												<?php } ?>
												<br><br>
												<?php if (!$isLattesBlank){ ?>
													<a class="btn btn-primary btn-flat btn-xs" href=<?= $teacher->getLattesLink()?>>Currículo Lattes</a>
												<?php } ?>
											</div>
										</div>
									<?php
									}	?>

									</div>
					<?php
							}
							}
						?>
					</div>
	            </div>
	          </div>
	<?php
		}
	}?>

