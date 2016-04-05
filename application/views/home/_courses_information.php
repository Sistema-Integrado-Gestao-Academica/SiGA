<?php            				

if ($coursesPrograms !== FALSE) {
	$coursesName = $coursesProgram['coursesName'];

	if(!empty($coursesName)){ ?>

		<div class="panel box box-default">
			<div class="box-header with-border">
	    		<h4 class="box-title">
	      		<a data-toggle="collapse" data-parent=<?="#accordion".$program['id_program']?> href=<?="#collapseTwo".$program['id_program']?> class="collapsed" aria-expanded="false" >
	      		 Lista de Cursos <i class=" fa fa-caret-down"></i>
	      		</a>
	    	</h4>
	  	</div>
	  <div id=<?="collapseTwo".$program['id_program']?> class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
	    <div class="box-body">
	    	<?php 
					foreach ($coursesName as $course) { 
						echo "<p>{$course}</p>";					
					} ?>
						
					</div>				
    			</div>
  		</div>								
<?php
		} 
} ?>

<div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent=<?="#accordion".$program['id_program']?> href=<?="#coordinator".$program['id_program']?> class="collapsed" aria-expanded="false">
                Coordenador <i class=" fa fa-caret-down"></i>
                <?php $coordinatorBasicData = $coordinators[$program['id_program']]['basic_data'][0];?>
                <?php $coordinatorExtraData = $coordinators[$program['id_program']]['extra_data'][0];?>
              </a>
            </h4>
          </div>
          <div id=<?="coordinator".$program['id_program']?> class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="box-body"> 
						<h4><?= $coordinatorBasicData['name']?></h4> 
									<b>Email:</b><br><?= $coordinatorBasicData['email']?> <br>
							<?php 
							
							$isSummaryBlank = empty($coordinatorExtraData['summary']) || is_null($coordinatorExtraData['summary']);
							$isLattesBlank = empty($coordinatorExtraData['lattes_link']) ||  is_null($coordinatorExtraData['lattes_link']);

							$isResearchLineBlank = empty($coordinatorExtraData['research_line']) ||  is_null($coordinatorExtraData['research_line']);
									
							if (!$isSummaryBlank || !$isLattesBlank || !$isResearchLineBlank){ ?>
									<?php if (!$isSummaryBlank){ ?>
										<b>Resumo:</b><br><?= $coordinatorExtraData['summary']?>
									<?php } ?>
									<br>	
									<?php if (!$isResearchLineBlank){ ?>
										<b>Linha de Pesquisa:</b><br><?= $coordinatorExtraData['research_line']?>
									<?php } ?>
									<br><br>
									<?php if (!$isLattesBlank){ ?>
										<a class="btn btn-primary btn-flat btn-xs" href=<?= $coordinatorExtraData['lattes_link']?>>Currículo Lattes</a>
									<?php } ?>
							<?php
							}	?>					
				</div>
            </div>
 </div>

<?php
if ($coursesPrograms !== FALSE) {
	$teachers = $coursesProgram['teachers'];
	if(!empty($teachers)){ ?>
		
		<div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent=<?="#accordion".$program['id_program']?> href=<?="#collapseThree".$program['id_program']?> class="collapsed" aria-expanded="false">
                Corpo Docente <i class=" fa fa-caret-down"></i>
              </a>
            </h4>
          </div>
          <div id=<?="collapseThree".$program['id_program']?> class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="box-body">
					<?php 
						foreach ($teachers as $teachersCourse) { 

							if(!empty($teachersCourse)){

								foreach ($teachersCourse as $teacher) {
									
									if($teacher['id'] != $program['coordinator']){?>
									<div class="panel box box-default">
									          <div class="box-header with-border">
									            <h4 class="box-title">
									              
		  										<a data-toggle="collapse" href=<?="#teacher".$program['id_program'].$teacher['id']?> class="collapsed" aria-expanded="false">
		                						<?= $teacher['name']?> <i class=" fa fa-caret-down"></i></a>
									            </h4>
									          </div>
										<?php 
										
										$isSummaryBlank = empty($teacher['summary']) || is_null($teacher['summary']);
										$isLattesBlank = empty($teacher['lattes_link']) ||  is_null($teacher['lattes_link']);

										$isResearchLineBlank = empty($teacher['research_line']) ||  is_null($teacher['research_line']);
										
										if (!$isSummaryBlank || !$isLattesBlank || $isResearchLineBlank){ ?>

											<div id=<?="teacher".$program['id_program'].$teacher['id']?> class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
											    <div class="box-body">
													<b>Email:</b><br><?= $teacher['email']?> <br>
													<?php if (!$isSummaryBlank){ ?>
														<b>Resumo:</b><br><?= $teacher['summary']?>
													<?php } ?>
													<br>	
													<?php if (!$isResearchLineBlank){ ?>
														<b>Linha de Pesquisa:</b><br><?= $teacher['research_line']?>
													<?php } ?>
													<br><br>
													<?php if (!$isLattesBlank){ ?>
														<a class="btn btn-primary btn-flat btn-xs" href=<?= $teacher['lattes_link']?>>Currículo Lattes</a>
													<?php } ?>
												</div>
											</div>
										<?php
										}	?>	

										</div>							         				
						<?php	
									}
								}
							}
						}
					?>							
				</div>
            </div>
          </div>
<?php
	} 
}?>
                

