<h2 class="principal"><b>Lista de ofertas</b></h2>
	
	<?php if ($isAdmin){
		include (MODULESPATH."/program/views/settings/_forward_semester.php");
	} 
	else{ ?>
		
		<h4><span class="fa fa-calendar-o"> <b> Semestre atual </b></span></h4>
		<h4><?=$current_semester['description']?></h4>
	
	<?php }?>

<br>
<br>

<?php 
	$userName = $user->getName();

	if($courses !== FALSE){

?>

	<div class='alert alert-info alert-dismissible' role='alert'>
		<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
		<i class="fa fa-info"></i> <strong>Lembre-se,</strong> aqui é possível montar a lista de oferta para o semestre atual e também planejar a lista de oferta para o semestre seguinte.
	</div>


<?php 
		echo "<h4><span class='fa fa-graduation-cap'> Cursos para o secretário <b>".$userName."</b>:</h4></span>";
		displayOffersList($proposedOffers, $current_semester, $next_semester);

	}else{
?>
		<div class="callout callout-warning">
            <h4>Nenhum curso cadastrado para o secretário <b><?php echo $userName;?></b>.<br><br>
            <small><b>OBS.: Você somente pode criar e alterar listas de ofertas dos cursos os quais é secretário.</b></small></h4>
        </div>

<?php } ?>

<?php echo anchor('secretary_home', "Voltar", "class='btn btn-primary'"); ?>