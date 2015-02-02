<br>
<h4 align="center"><b>Lista de ofertas</b></h4>
<br>

<?=	form_open('semester/saveSemester') ?>
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
	if($proposedOffers != FALSE){
		

	}else{
?>
		<div class="callout callout-info">
		    <h4>Nenhuma lista de ofertas proposta no momento.</h4>
		    <?php 

		    	$newOfferBtn = array(
				    'name' => 'new_offer_list_btn',
				    'id' => 'new_offer_list_btn',
				    'type' => 'submit',
				    'content' => 'Nova Lista de Ofertas',
				    'class' => 'btn btn-primary'
				);

		    	echo form_open('offer/newOffer');
					echo form_hidden('current_semester_id', $current_semester['id_semester']);
			    	echo form_button($newOfferBtn);
		    	echo form_close();
		    ?>
		    <p> <b><i>OBS.: A lista de oferta será criada para o semestre atual.</i><b/></p>
		</div>
<?php } ?>
