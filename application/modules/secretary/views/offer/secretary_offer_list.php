<br>
<h4 align="center"><b>Lista de ofertas</b></h4>
<br>

<?=	form_open('program/semester/saveSemester') ?>
	<?= form_hidden('current_semester_id', $current_semester['id_semester']) ?>
	<?= form_hidden('password') ?>
	<?= form_label('Semestre atual') ?>
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

		echo "<h4>Cursos para o secretário <b>".$userName."</b>:</h4>";

		displayOffersList($proposedOffers);

	}else{
?>
		<div class="callout callout-warning">
            <h4>Nenhum curso cadastrado para o secretário <b><?php echo $userName;?></b>.<br><br>
            <small><b>OBS.: Você somente pode criar e alterar listas de ofertas dos cursos os quais é secretário.</b></small></h4>
        </div>

<?php } ?>

<?php echo anchor('secretary_home', "Voltar", "class='btn btn-primary'"); ?>