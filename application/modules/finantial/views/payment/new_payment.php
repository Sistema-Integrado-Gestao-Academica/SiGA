
<h2 class="principal">Novo pagamento para Prestação de Serviços</h2>
<hr>

<?php
	include("__payment_form_data.php");

	$submitPath = "register_payment";

	include("_payment_form.php");
?>

<br>
<br>
<?= anchor("expense_payments/{$expenseId}/{$budgetplanId}", "Voltar", "class='btn btn-danger'")?>