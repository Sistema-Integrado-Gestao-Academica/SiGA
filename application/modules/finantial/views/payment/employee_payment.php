
<h2 class="principal">Novo pagamento para Prestação de Serviços</h2>
<hr>

<?php

include("__payment_form_data.php");

$name['value'] = $employee['name'];

$cpf['value'] = $employee['cpf'];

$pisPasep['value'] = $employee['pisPasep'];

$enrollmentNumber['value'] = $employee['registration'];

$arrivalInBrazil['value'] = $employee['brazil_landing'];

$address['value'] = $employee['address'];

$phone['value'] = $employee['telephone'];

$email['value'] = $employee['email'];

$bank['value'] = $employee['bank'];

$agency['value'] = $employee['agency'];

$accountNumber['value'] = $employee['account_number'];

$submitPath = "register_payment";
include("_payment_form.php");

?>

<br>
<br>

<?php
    $backBtn = array(
        "id" => "back_btn",
        "class" => "btn btn-danger btn-flat",
        "content" => "Voltar"
    );

    echo form_button($backBtn);
?>