<h2 class="principal"><b>Lista de ofertas</b></h2>

<?=	form_open('program/semester/saveSemester') ?>
	<?= form_hidden('current_semester_id', $current_semester['id_semester']) ?>
	<?= form_hidden('password') ?>
	<h4><span class="fa fa-calendar-o"> <?= form_label(' Semestre atual')?></span></h4>
	<h4><?=$current_semester['description']?></h4>
	<?php if ($isAdmin): ?>
		<?= form_button(array(
			'type' => 'password',
			'content' => 'Avançar semestre',
			'onClick' => "passwordRequest()"
		)) ?>
	<?php endif ?>
<?= form_close() ?>

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