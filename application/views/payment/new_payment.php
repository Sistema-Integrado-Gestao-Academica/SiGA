
<h2 class="principal">Novo pagamento para Prestação de Serviços</h2>
<hr>

<?php 

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
	"maxlength" => "200",
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
	"type" => "date",
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
	"step" => 0.01
);

$period = array(
	"name" => "period",
	"id" => "period",
	"type" => "text",
	"class" => "form-campo",
	"class" => "form-control",
	"maxlength" => "10"
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
	"maxlength" => "300",
);

$submitBtn = array(
	"id" => "new_payment",
	"class" => "btn bg-olive btn-block",
	"content" => "Cadastrar pagamento",
	"type" => "submit"
);

?>

	<?= form_open("payment/registerPayment") ?>
		
		<?= form_hidden("expenseId", $expenseId)?>
		<?= form_hidden("budgetplanId", $budgetplanId)?>

		<h3> <b>Proposta simplificada de prestação de serviços</b> </h3>
		<br>
		<br>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">	
					<?= form_label("Tipo do usuário:", "userType") ?>
					<?= form_dropdown("userType", $userTypes) ?>
					<?= form_error("userType") ?>
				</div>
			</div>

			<div class="col-lg-8">
				<div class="form-group">	
					<?= form_label("Amparo legal (Até 200 caracteres):", "legalSupport") ?>
					<?= form_textarea($legalSupport) ?>
					<?= form_error("legalSupport") ?>
				</div>
			</div>
		</div>

	<!-- Finantial source identification -->

		<h3> Identificação da fonte financiadora</h3>
		<br>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">	
					<?= form_label("Fonte de recurso", "resourseSource") ?>
					<?= form_input($resourseSource) ?>
					<?= form_error("resourseSource") ?>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">	
					<?= form_label("Centro de custo", "costCenter") ?>
					<?= form_input($costCenter) ?>
					<?= form_error("costCenter") ?>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">	
					<?= form_label("Nota de dotação", "dotationNote") ?>
					<?= form_input($dotationNote) ?>
					<?= form_error("dotationNote") ?>
				</div>
			</div>
		</div>

	<!-- User identification -->

		<h3> Identificação do Usuário</h3>
		<br>

		<div class="row">
			<div class="col-lg-8">
				<div class="form-group">	
					<?= form_label("Nome", "name") ?>
					<?= form_input($name) ?>
					<?= form_error("name") ?>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">	
					<?= form_label("CPF", "cpf") ?>
					<?= form_input($cpf) ?>
					<?= form_error("cpf") ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-3">
				<div class="form-group">	
					<?= form_label("Carteira de identidade", "id") ?>
					<?= form_input($id) ?>
					<?= form_error("id") ?>
				</div>
			</div>

			<div class="col-lg-3">
				<div class="form-group">	
					<?= form_label("PIS e/ou INSS", "pisPasep") ?>
					<?= form_input($pisPasep) ?>
					<?= form_error("pisPasep") ?>
				</div>
			</div>

			<div class="col-lg-3">
				<div class="form-group">	
					<?= form_label("Matrícula", "enrollmentNumber") ?>
					<?= form_input($enrollmentNumber) ?>
					<?= form_error("enrollmentNumber") ?>
				</div>
			</div>

			<div class="col-lg-3">
				<div class="form-group">	
					<?= form_label("Chegada ao Brasil", "arrivalInBrazil") ?>
					<?= form_input($arrivalInBrazil) ?>
					<?= form_error("arrivalInBrazil") ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-5">
				<div class="form-group">	
					<?= form_label("Endereço", "address") ?>
					<?= form_input($address) ?>
					<?= form_error("address") ?>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">	
					<?= form_label("E-mail", "email") ?>
					<?= form_input($email) ?>
					<?= form_error("email") ?>
				</div>
			</div>

			<div class="col-lg-3">
				<div class="form-group">	
					<?= form_label("Telefone", "phone") ?>
					<?= form_input($phone) ?>
					<?= form_error("phone") ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
			<?= form_label("Denominação do projeto", "projectDenomination") ?>
			<?= form_input($projectDenomination) ?>
			<?= form_error("projectDenomination") ?>
			</div>
		</div>

		<!-- User bank data -->

		<h4>Dados bancários</h4>

		<div class="row">
			<div class="col-lg-4">
				<div class="form-group">
					<?= form_label("Banco", "bank") ?>
					<?= form_input($bank) ?>
					<?= form_error("bank") ?>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">
					<?= form_label("Agência", "agency") ?>
					<?= form_input($agency) ?>
					<?= form_error("agency") ?>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="form-group">	
					<?= form_label("Número da conta", "accountNumber") ?>
					<?= form_input($accountNumber) ?>
					<?= form_error("accountNumber") ?>
				</div>
			</div>
		</div>

	<!-- Propose data -->

		<h3> Dados da proposta </h3>
		<br>

		<div class="row">
			<div class="col-lg-3">
				<div class="form-group">	
					<?= form_label("Valor total", "totalValue") ?>
					<?= form_input($totalValue) ?>
					<?= form_error("totalValue") ?>
				</div>
			</div>

			<div class="col-lg-2">
				<div class="form-group">	
					<?= form_label("Período", "period") ?>
					<?= form_input($period) ?>
					<?= form_error("period") ?>
				</div>
			</div>

			<div class="col-lg-2">
				<div class="form-group">	
					<?= form_label("Horas semanais", "weekHours") ?>
					<?= form_input($weekHours) ?>
					<?= form_error("weekHours") ?>
				</div>
			</div>

			<div class="col-lg-2">
				<div class="form-group">	
					<?= form_label("Semanas", "weeks") ?>
					<?= form_input($weeks) ?>
					<?= form_error("weeks") ?>
				</div>
			</div>

			<div class="col-lg-3">
				<div class="form-group">	
					<?= form_label("Total de horas", "totalHours") ?>
					<?= form_input($totalHours) ?>
					<?= form_error("totalHours") ?>
				</div>
			</div>
		</div>

		<h3>Parcelamento <p><small> Coloque o valor total na primeira parcela se for parcela única.</small></h3>

		<div class="row">

			<div class="box-body table-responsive no-padding">
			<table class="table table-bordered table-hover">
				<tbody>
					<tr>
				        <th class="text-center">Nº da parcela</th>
				        <th class="text-center">Data</th>
				        <th class="text-center">Valor</th>
				        <th class="text-center">Horas trabalhadas</th>
				    </tr>
				<?php
						for($installment = 1; $installment <= 5; $installment++){

							echo "<tr>";

					    		echo "<td>";
					    		echo $installment;
					    		echo "</td>";

								$installmentDate = array(
									"name" => "installment_date_".$installment,
									"id" => "installment_date_".$installment,
									"type" => "date",
									"class" => "form-campo",
									"class" => "form-control"
								);

					    		echo "<td>";
					    		echo form_input($installmentDate);
					    		echo "</td>";

								$installmentValue = array(
									"name" => "installment_value_".$installment,
									"id" => "installment_value_".$installment,
									"type" => "number",
									"class" => "form-campo",
									"class" => "form-control",
									"value" => 0,
									"min" => 0,
									"step" => 0.01
								);

					    		echo "<td>";
					    		echo form_input($installmentValue);
					    		echo "</td>";

					    		$installmentHours = array(
									"name" => "installment_hour_".$installment,
									"id" => "installment_hour_".$installment,
									"type" => "number",
									"class" => "form-campo",
									"class" => "form-control",
									"min" => 0,
									"step" => 1
								);

					    		echo "<td>";
					    		echo form_input($installmentHours);
					    		echo "</td>";
					    	
				    		echo "</tr>";
				    	}
				?>			    
				</tbody>
			</table>
			</div>
			
			<div id="check_installment_result"></div>

		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="form-group">	
					<?= form_label("Descrição detalhada dos serviços", "serviceDescription") ?>
					<?= form_textarea($serviceDescription) ?>
					<?= form_error("serviceDescription") ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-9">
			</div>
			<div class="col-lg-3">
				<?= form_button($submitBtn) ?>
			</div>
		</div>
	<?= form_close() ?>

<br>
<br>
<?= anchor("payment/expensePayments/{$expenseId}/{$budgetplanId}", "Voltar", "class='btn btn-danger'")?>