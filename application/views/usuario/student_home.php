<br><br><br>
<div class="col-lg-12 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
		    
		    <font size="6">Perfil Estudante</font>
		    
		    <p>
		        <h4><b>Curso:</b></h4>
		        <font color="black">
		        <?php
		        	foreach ($courses as $course) {
		        		echo $course['course_name'];
		        		echo "<br>";
		        		echo "Data matrícula: ".$course['enroll_date'];
		        		echo "<br>";
		        		echo "<br>";
		        	}
		        ?>
		        </font>
		        <h4><b>Status:</b> <font color="black"> <?php echo $status;?> </font></h4>
		    </p>
		</div>
		<div class="icon">
		    <i class="fa fa-tags"></i>
		</div>
		<a href="#" class="small-box-footer">
		    Mais informações <i class="fa fa-arrow-circle-right"></i>
		</a>
	</div>
</div>

