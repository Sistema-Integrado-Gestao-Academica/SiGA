<br>
<br>
<?php displayOfferDisciplines($idOffer, $course, $disciplines); ?>
<br>
<div class="row">
	<div class="col-xs-3">
		<?php echo anchor("offer/approveOfferList/{$idOffer}", "Aprovar lista de oferta", "id='approve_offer_list_btn' class='btn btn-primary' data-container=\"body\"
             data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\"
             data-content=\"OBS.: Ao aprovar a lista de oferta não é possível adicionar ou retirar disciplinas.\""); ?>
	</div>
	<div class="col-xs-3">
		<?php echo anchor("usuario/secretary_offerList", "Voltar", "class='btn btn-danger'"); ?>
	</div>
</div>