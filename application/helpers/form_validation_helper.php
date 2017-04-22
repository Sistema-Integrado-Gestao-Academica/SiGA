<?php

function validateWithRule($ruleGroup){
    $CI =& get_instance();
    $CI->load->library("form_validation");

    $validationRules = getValidationFor($ruleGroup);
    $CI->form_validation->set_rules($validationRules);
    $CI->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
    $validationResult = $CI->form_validation->run();

    return $validationResult;
}

function getValidationFor($ruleGroup){
    $validations = include(APPPATH.'/config/form_validation.php');

    if(!isset($validations[$ruleGroup])){
        echo "Rule group '{$ruleGroup}' not found in application/config/form_validation.php";
        exit;
    }

    return $validations[$ruleGroup];
}