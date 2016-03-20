<?php            				
	
	$coursesName = $coursesProgram['coursesName'];

	if(!empty($coursesName)){ ?>

		<div class="panel box box-primary">
			<div class="box-header with-border">
	    		<h4 class="box-title">
	      		<a data-toggle="collapse" data-parent="#accordion" href=<?="#collapseTwo".$program['id_program']?> class="collapsed" aria-expanded="false" >
	      		Lista de Cursos
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
              <a data-toggle="collapse" data-parent="#accordion" href=<?="#collapseThree".$program['id_program']?> class="collapsed" aria-expanded="false">
                Corpo Docente
              </a>
            </h4>
          </div>
          <div id=<?="collapseThree".$program['id_program']?> class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="box-body">
					<?php 
						foreach ($teachers as $teacher) { 
							echo "<p>{$teacher['name']}</p>";	
				
						}
					?>							
            </div>
          </div>
        </div>					
<?php
	} ?>
                

