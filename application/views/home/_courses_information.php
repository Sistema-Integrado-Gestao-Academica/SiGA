<?php            				
	
	$coursesName = $coursesProgram['coursesName'];

	if(!empty($coursesName)){ ?>

		<div class="panel box box-primary">
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
	$teachers = $coursesProgram['teachers'];

	if(!empty($teachers)){ ?>
		
		<div class="panel box box-primary">
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
						foreach ($teachers as $teacher) { ?>

						<ul id="nav-tabs-wrapper" class="nav nav-tabs nav-pills nav-stacked well">
							<li class="active">
								<a href="#vtab1" data-toggle="collapse" data-target=<?="#teacher".$teacher['teacher']?>>
								<h4><?= $teacher['name']?></h4> 
								</a>
							</li>
							<?php 
							
							$isSummaryBlank = empty($teacher['summary']) || is_null($teacher['summary']);
							$isLattesBlank = empty($teacher['lattes']) ||  is_null($teacher['lattes']);
							
							if (!$isSummaryBlank || !$isLattesBlank){ ?>

								<div id=<?="teacher".$teacher['teacher']?> class="collapse">
									<?php if (!$isSummaryBlank){ ?>
										<b>Resumo:</b><br><?= $teacher['summary']?>
									<?php } ?>
									<br><br>
									<?php if (!$isLattesBlank){ ?>
										<a class="btn btn-primary btn-flat btn-xs" href=<?= $teacher['lattes']?>>Currículo Lattes</a>
									<?php } ?>
								</div>
							<?php
							}	?>					

			           </ul>
				<?php	}
					?>							
				</div>
            </div>
          </div>
<?php
	} ?>
                

