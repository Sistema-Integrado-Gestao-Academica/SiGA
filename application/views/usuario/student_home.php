<?php   $session = $this->session->userdata("current_user");?>
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
<?php 
	require_once APPPATH.'controllers/offer.php';
	$offer = new Offer();
	$disciplines = $offer->displayOfferedDisciplines($course['id_course']);
?>

<div class="col-lg-12 callout callout-info">
	<h3>Lista de Ofertas</h3>
	
	<?php if($disciplines){ ?>
	<table class="table table-striped table-bordered">
		<tr>
			<td><h3 class="text-center">Nome da disciplina</h3></td>
			<td><h3 class="text-center">Código da disciplina</h3></td>
			<td><h3 class="text-center">Créditos</h3></td>
		</tr>
		<?php 
			foreach ($disciplines as $rows){
				foreach ($rows as $discipline){	
					echo "<tr>";	
					echo "<td>".$discipline['discipline_name']."</td>";
					echo "<td>".$discipline['discipline_code']."</td>";
					echo "<td>".$discipline['credits']."</td>";
					echo "</tr>";
				}
			}
			?>
	</table>
	<?php }else{?>
	
	<h3><label class="label label-default"> A lista de ofertas do seu curso ainda não foi produzida.</label></h3>
	
	<?php }?>
</div>

