
<h2 class="principal">Novo pagamento para Prestação de Serviços</h2>
<hr>

<?php
	include("__payment_form_data.php");

	$submitPath = "payment/registerPayment";

	include("_payment_form.php");
?>

<br>
<br>
<?= anchor("payment/expensePayments/{$expenseId}/{$budgetplanId}", "Voltar", "class='btn btn-danger'")?>