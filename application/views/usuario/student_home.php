
<br>
<br>
<h2 align="center">Bem vindo estudante!</h2>
<br>

<h4>Semestre atual: <?php echo $currentSemester['description'];?></h4>

<p>
    <h3>Cursos:</h3>
    <?php
    if($courses !== FALSE){

    	foreach ($courses as $course) {
    		echo "<b>".$course['course_name']."</b>";
    		echo "<br>";
    		echo "Data matrícula: ".$course['enroll_date'];
    		echo "<br>";
    	}
    }else{
    	echo "Usuário não matriculado em nenhum curso.";
    }
    ?>
    <h3>Status: <?php echo $status;?></h3>
</p>

<br><br><br>

<?php 
	require_once APPPATH.'controllers/offer.php';
	$offer = new Offer();
	$disciplines = $offer->displayOfferedDisciplines($course['id_course']);
?>

<div class="col-lg-12 callout callout-info">
	<h3>Lista de Ofertas</h3>
	
	<?php if($disciplines !== FALSE){ ?>
	<table class="table table-striped table-bordered">
		<tr>
			<td><h3 class="text-center">Nome da disciplina</h3></td>
			<td><h3 class="text-center">Código da disciplina</h3></td>
			<td><h3 class="text-center">Créditos</h3></td>
		</tr>
		<?php

			foreach ($disciplines as $discipline){
				echo "<tr>";	
					echo "<td>".$discipline['discipline_name']."</td>";
					echo "<td>".$discipline['discipline_code']."</td>";
					echo "<td>".$discipline['credits']."</td>";
				echo "</tr>";
			}
		?>
	</table>
	<?php }else{?>
	
	<div class="callout callout-warning">
		<h4>A lista de ofertas do seu curso ainda não foi produzida.</h4>
	</div>
	
	<?php }?>
</div>