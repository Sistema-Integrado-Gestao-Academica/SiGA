<?php
/**
 *  Contains the basic data to the payment form
 */
require_once(MODULESPATH."finantial/constants/PaymentConstants.php");

$userTypes = array(
    "Interno" => "Interno",
    "Externo" => "Externo"
);

$legalSupport = array(
    'name' => 'legalSupport',
    'id' => 'legalSupport',
    'placeholder' => 'Discriminar aqui, se houver, amparo legal.',
    'rows' => '20',
    "class" => "form-control",
    'style' => 'height: 70px;',
    "maxlength" => "200"
);

$resourseSource = array(
    "name" => "resourseSource",
    "id" => "resourseSource",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "40"
);

$costCenter = array(
    "name" => "costCenter",
    "id" => "costCenter",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "40"
);

$dotationNote = array(
    "name" => "dotationNote",
    "id" => "dotationNote",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "40"
);

$name = array(
    "name" => "name",
    "id" => "name",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "70"
);

$cpf = array(
    "name" => "cpf",
    "id" => "cpf",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "11"
);

$id = array(
    "name" => "id",
    "id" => "id",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "20"
);

$pisPasep = array(
    "name" => "pisPasep",
    "id" => "pisPasep",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "15"
);

$enrollmentNumber = array(
    "name" => "enrollmentNumber",
    "id" => "enrollmentNumber",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "10"
);

$arrivalInBrazil = array(
    "name" => "arrivalInBrazil",
    "id" => "arrivalInBrazil",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control"
);

$address = array(
    "name" => "address",
    "id" => "address",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "50"
);

$phone = array(
    "name" => "phone",
    "id" => "phone",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "15"
);

$email = array(
    "name" => "email",
    "id" => "email",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "50"
);

$projectDenomination = array(
    "name" => "projectDenomination",
    "id" => "projectDenomination",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "70"
);

$bank = array(
    "name" => "bank",
    "id" => "bank",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "20"
);

$agency = array(
    "name" => "agency",
    "id" => "agency",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "10"
);

$accountNumber = array(
    "name" => "accountNumber",
    "id" => "accountNumber",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "15"
);

$totalValue = array(
    "name" => "totalValue",
    "id" => "totalValue",
    "type" => "number",
    "class" => "form-campo",
    "class" => "form-control",
    "min" => 0,
    "step" => 0.01,
    "max" => PaymentConstants::MAX_TOTAL_VALUE
);

$installmentsQuantity = array(
    "name" => "installments_quantity",
    "id" => "installments_quantity",
    "type" => "number",
    "class" => "form-campo",
    "class" => "form-control",
    "min" => 1,
    "step" => 1,
    "max" => PaymentConstants::MAX_INSTALLMENTS
);

$start_period = array(
    "name" => "start_period",
    "id" => "start_period",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control"
);

$end_period = array(
    "name" => "end_period",
    "id" => "end_period",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control"
);

$weekHours = array(
    "name" => "weekHours",
    "id" => "weekHours",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "10"
);

$weeks = array(
    "name" => "weeks",
    "id" => "weeks",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "10"
);

$totalHours = array(
    "name" => "totalHours",
    "id" => "totalHours",
    "type" => "text",
    "class" => "form-campo",
    "class" => "form-control",
    "maxlength" => "10"
);

$serviceDescription = array(
    'name' => 'serviceDescription',
    'id' => 'serviceDescription',
    'placeholder' => 'Descreva aqui os serviços prestados, detalhadamente.',
    'rows' => '20',
    "class" => "form-control",
    'style' => 'height: 100px;',
    "maxlength" => "300"
);
