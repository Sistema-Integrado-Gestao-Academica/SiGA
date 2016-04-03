<?php 

function setDefaultConfiguration(){
	
	$mail = new PHPMailer();
    $mail->IsSMTP(); 
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl"; 
    $mail->Host = ""; 
    $mail->Port = 465; 
    $mail->Username = ""; 
    $mail->Password = ""; 
    $instituteName = "";
    $instituteEmail = "";
    $mail->SetFrom($instituteEmail, $instituteName); 

   	
	return $mail;
}