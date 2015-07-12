
<?php 
	
	$personType = array(
		'individuals' => 'Pessoa Física',
		'worker_gecc' => 'Servidor (GECC)'
	);

	$paymentValue = array(
		"id" => "payment_value",
		"name" => "payment_value",
		"type" => "number",
		"class" => "form-campo"
	);

	$submitBtn = array(
		"id" => "register_new_payment",
		"class" => "btn bg-olive btn-block",
		"content" => "Nova via de pagamento",
		"type" => "submit"
	);
?>

<div class="form-box" id="login-box">
	<div class="header">Nova via de pagamento</div>
	<?= form_open("") ?>
		<div class="body bg-gray">

			<div class="form-group">	
				<?= form_label("Pessoa", "payment_person") ?>
				<?= form_dropdown("payment_person", $personType);?>
				<?= form_error("payment_person") ?>
			</div>

			<div class="form-group">	
				<?= form_label("Valor do pagamento", "payment_value") ?>
				<?= form_input($paymentValue);?>
				<?= form_error("payment_value") ?>
			</div>

		</div>

		<div class="footer body bg-gray">
			<div class="row">
				<div class="col-xs-6">
					<?= form_button($submitBtn) ?>
				</div>
				<div class="col-xs-6">
					<?= anchor('', 'Voltar', "class='btn bg-olive btn-block'") ?>
				</div>
			</div>
		</div>
	<?= form_close() ?>

</div>