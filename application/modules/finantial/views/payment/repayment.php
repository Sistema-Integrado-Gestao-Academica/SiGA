
<h2 class="principal">Novo pagamento para Prestação de Serviços</h2>
<hr>

<?php

include("__payment_form_data.php");

$legalSupport['value'] = $payment['legalSupport'];
$legalSupport['disabled'] = TRUE;

$resourseSource['value'] = $payment['resourseSource'];
$resourseSource['disabled'] = TRUE;

$costCenter['value'] = $payment['costCenter'];
$costCenter['disabled'] = TRUE;

$dotationNote['value'] = $payment['dotationNote'];
$dotationNote['disabled'] = TRUE;

$name['value'] = $payment['name'];
$name['disabled'] = TRUE;

$cpf['value'] = $payment['cpf'];
$cpf['disabled'] = TRUE;

$id['value'] = $payment['id'];
$id['disabled'] = TRUE;

$pisPasep['value'] = $payment['pisPasep'];
$pisPasep['disabled'] = TRUE;

$enrollmentNumber['value'] = $payment['enrollmentNumber'];
$enrollmentNumber['disabled'] = TRUE;

$arrivalInBrazil['value'] = $payment['arrivalInBrazil'];
$arrivalInBrazil['disabled'] = TRUE;

$address['value'] = $payment['address'];
$address['disabled'] = TRUE;

$phone['value'] = $payment['phone'];
$phone['disabled'] = TRUE;

$email['value'] = $payment['email'];
$email['disabled'] = TRUE;

$projectDenomination['value'] = $payment['projectDenomination'];
$projectDenomination['disabled'] = TRUE;

$bank['value'] = $payment['bank'];
$bank['disabled'] = TRUE;

$agency['value'] = $payment['agency'];
$agency['disabled'] = TRUE;

$accountNumber['value'] = $payment['accountNumber'];
$accountNumber['disabled'] = TRUE;

$totalValue['value'] = $payment['totalValue'];

$start_period['value'] = $payment['period'];

$end_period['value'] = $payment['end_period'];

$weekHours['value'] = $payment['weekHours'];

$weeks['value'] = $payment['weeks'];

$totalHours['value'] = $payment['totalHours'];

$serviceDescription['value'] = $payment['serviceDescription'];
$serviceDescription['disabled'] = TRUE;

$submitPath = "register_repayment";
include("_payment_form.php");

?>

<br>
<br>

<?php

    //echo anchor("expense_payments/{$expenseId}/{$budgetplanId}", "Voltar", "class='btn btn-danger'");

    $backBtn = array(
        "id" => "back_btn",
        "class" => "btn btn-danger btn-flat",
        "content" => "Voltar"
    );

    echo form_button($backBtn);
?>