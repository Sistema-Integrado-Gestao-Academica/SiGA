<?php

		/**
	 *	Staff Labels
	 */
	$submitBtn = array(
			"class" => "btn bg-olive btn-block",
			"content" => "Salvar",
			"type" => "submit"
	);

	$pisNumber = array(
			"name" => "pis",
			"id" => "pis",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "20",
			"value" => $staff['pisPasep']
	);

	$registration = array(
			"name" => "registration",
			"id" => "registration",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "10",
			"placeholder" => "Opcional",
			"value" => $staff['registration']
	);

	$landingDate = array(
			"name" => "landingDate",
			"id" => "arrivalInBrazil",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"placeholder" => "Opcional",
			"value" => $staff['brazil_landing']
	);

	$address = array(
			"name" => "address",
			"id" => "address",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "50",
			"value" => $staff['address']
	);

	$phone = array(
			"name" => "telephone",
			"id" => "telephone",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "15",
			"value" => $staff['telephone']
	);

	/**
	 *	Bank labels
	 */

	$bank = array(
			"name" => "bank",
			"id" => "bank",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "25",
			"value" => $staff['bank']
	);

	$agency = array(
			"name" => "agency",
			"id" => "agency",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "10",
			"value" => $staff['agency']
	);

	$checkingAccount = array(
			"name" => "accountNumber",
			"id" => "accountNumber",
			"type" => "text",
			"class" => "form-campo",
			"class" => "form-control",
			"maxlength" => "15",
			"value" => $staff['account_number']
	);

	$hidden = array('user_id' => $user['id'], 'staff_id'=>$staff['id_staff']);

	echo "<div class='form-box' id='login-box'>";
		echo "<div class='header'> Alterar dados de Funcionário</div>";

		echo form_open('program/staff/updateStaff','',$hidden);
		echo "<div class='body bg-gray'>";
			echo "<div class='form-group'>";
				echo form_label("PIS/INSS", "pis_number");
				echo form_input($pisNumber);
				echo form_error("pis_number");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Nome do Funcionário", "staff");
				echo "<h4>".$user['name']."</h4>";
				echo form_error("staff");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Matrícula", "registration");
				echo form_input($registration);
				echo form_error("registration");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Chegada ao Brasil", "landingDate");
				echo form_input($landingDate);
				echo form_error("landingDate");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Endereço", "address");
				echo form_input($address);
				echo form_error("address");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Telefone", "telephone");
				echo form_input($phone);
				echo form_error("telephone");
			echo "</div>";

			echo "<hr>";
			echo "<h3>Dados Bancários</h3> (Opcionais)";

			echo "<div class='form-group'>";
				echo form_label("Banco", "bank");
				echo form_input($bank);
				echo form_error("bank");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Agência", "agency");
				echo form_input($agency);
				echo form_error("agency");
			echo "</div>";
			echo "<div class='form-group'>";
				echo form_label("Conta Corrente", "accountNumber");
				echo form_input($checkingAccount);
				echo form_error("accountNumber");
			echo "</div>";

		echo "</div>";

		echo "<div class='footer body bg-gray'>";
		echo form_button($submitBtn);
		echo "</div>";

		echo form_close();
	echo "</div>";
?>
