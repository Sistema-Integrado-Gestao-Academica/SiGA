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
                <?php $coordinator = $coordinators[$program['id_program']][0];?>
              </a>
            </h4>
          </div>
          <div id=<?="coordinator".$program['id_program']?> class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="box-body"> 
						<ul id="nav-tabs-wrapper" class="nav nav-tabs nav-pills nav-stacked well">
							<li class="active">
								<a href="#vtab1" data-toggle="collapse" data-target=<?="#coordinator".$program['id_program'].$program['coordinator']?>>
								<h4><?= $coordinator['name']?></h4> 
								</a>
							</li>
							<?php 
							
							$isSummaryBlank = empty($coordinator['summary']) || is_null($coordinator['summary']);
							$isLattesBlank = empty($coordinator['lattes_link']) ||  is_null($coordinator['lattes_link']);

							$isResearchLineBlank = empty($coordinator['research_line']) ||  is_null($coordinator['research_line']);
									
							if (!$isSummaryBlank || !$isLattesBlank || !$isResearchLineBlank){ ?>
								<div id=<?="coordinator".$program['id_program'].$program['coordinator']?> class="collapse">
									<b>Email:</b><br><?= $coordinator['email']?> <br>
									<?php if (!$isSummaryBlank){ ?>
										<b>Resumo:</b><br><?= $coordinator['summary']?>
									<?php } ?>
									<br>	
									<?php if (!$isResearchLineBlank){ ?>
										<b>Linha de Pesquisa:</b><br><?= $coordinator['research_line']?>
									<?php } ?>
									<br><br>
									<?php if (!$isLattesBlank){ ?>
										<a class="btn btn-primary btn-flat btn-xs" href=<?= $coordinator['lattes_link']?>>Currículo Lattes</a>
									<?php } ?>
								</div>
							<?php
							}	?>					

			           </ul>						
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
						foreach ($teachers as $teacher) { 
							
							if($teacher['id'] != $program['coordinator']){?>

								<ul id="nav-tabs-wrapper" class="nav nav-tabs nav-pills nav-stacked well">
									<li class="active">
										<a href=<?="#teacher".$program['id_program'].$teacher['id']?> data-toggle="collapse" data-target=>
										<h4><?= $teacher['name']?></h4> 
										</a>
									</li>
									<?php 
									
									$isSummaryBlank = empty($teacher['summary']) || is_null($teacher['summary']);
									$isLattesBlank = empty($teacher['lattes_link']) ||  is_null($teacher['lattes_link']);

									$isResearchLineBlank = empty($teacher['research_line']) ||  is_null($teacher['research_line']);
									
									if (!$isSummaryBlank || !$isLattesBlank || $isResearchLineBlank){ ?>

										<div id=<?="teacher".$program['id_program'].$teacher['id']?> class="collapse">
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
									<?php
									}	?>					

					           </ul>
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
                

