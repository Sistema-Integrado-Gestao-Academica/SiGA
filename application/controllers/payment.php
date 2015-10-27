<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/constants/PermissionConstants.php");
require_once(APPPATH."/data_types/ServicePayment.php");

class Payment extends CI_Controller {

	const MAX_INSTALLMENTS = 5;
	const MAX_TOTAL_VALUE = 8000;

	public function expensePayments($expenseId, $budgetplanId){

		$this->load->model('payment_model');

		$payments = $this->payment_model->getExpensePayments($expenseId);

		$data = array(
			'payments' => $payments,
			'budgetplanId' => $budgetplanId,
			'expenseId' => $expenseId
		);

		loadTemplateSafelyByPermission(PermissionConstants::BUDGETPLAN_PERMISSION, 'payment/payments', $data);
	}

	public function newPayment($budgetplanId, $expenseId){

		$data = array(
			'budgetplanId' => $budgetplanId,
			'expenseId' => $expenseId
		);

		loadTemplateSafelyByPermission(PermissionConstants::BUDGETPLAN_PERMISSION, 'payment/new_payment', $data);
	}

	public function registerPayment(){

		$expense = $this->input->post("expenseId");
		$budgetplan = $this->input->post("budgetplanId");

		$userType = $this->input->post("userType");
		$legalSupport = $this->input->post("legalSupport");

		$resourseSource = $this->input->post("resourseSource");
		$costCenter = $this->input->post("costCenter");
		$dotationNote = (string) $this->input->post("dotationNote");

		$name = $this->input->post("name");
		$id = $this->input->post("id");
		$pisPasep = $this->input->post("pisPasep");
		$cpf = (string) $this->input->post("cpf");
		$enrollmentNumber = $this->input->post("enrollmentNumber");
		$arrivalInBrazil = $this->input->post("arrivalInBrazil");
		$phone = $this->input->post("phone");
		$email = $this->input->post("email");
		$address = $this->input->post("address");
		$projectDenomination = $this->input->post("projectDenomination");
		$bank = $this->input->post("bank");
		$agency = (string) $this->input->post("agency");
		$accountNumber = (string) $this->input->post("accountNumber");
		$installment_date_5 = $this->input->post("installment_date_5");

		$totalValue = $this->input->post("totalValue");
		$period = $this->input->post("start_period");
		$endPeriod = $this->input->post("end_period");
		$weekHours = $this->input->post("weekHours");
		$weeks = $this->input->post("weeks");
		$totalHours = $this->input->post("totalHours");
		$serviceDescription = $this->input->post("serviceDescription");

		$installment_date_1 = $this->input->post("installment_date_1");
		$installment_date_2 = $this->input->post("installment_date_2");
		$installment_date_3 = $this->input->post("installment_date_3");
		$installment_date_4 = $this->input->post("installment_date_4");
		$installment_date_5 = $this->input->post("installment_date_5");

		$installment_value_1 = $this->input->post("installment_value_1");
		$installment_value_2 = $this->input->post("installment_value_2");
		$installment_value_3 = $this->input->post("installment_value_3");
		$installment_value_4 = $this->input->post("installment_value_4");
		$installment_value_5 = $this->input->post("installment_value_5");

		$installment_hour_1 = $this->input->post("installment_hour_1");
		$installment_hour_2 = $this->input->post("installment_hour_2");
		$installment_hour_3 = $this->input->post("installment_hour_3");
		$installment_hour_4 = $this->input->post("installment_hour_4");
		$installment_hour_5 = $this->input->post("installment_hour_5");

		$installment1 = array(
			'date' => $installment_date_1,
			'value' => $installment_value_1,
			'hour' => $installment_hour_1
		);

		$installment2 = array(
			'date' => $installment_date_2,
			'value' => $installment_value_2,
			'hour' => $installment_hour_2
		);

		$installment3 = array(
			'date' => $installment_date_3,
			'value' => $installment_value_3,
			'hour' => $installment_hour_3
		);

		$installment4 = array(
			'date' => $installment_date_4,
			'value' => $installment_value_4,
			'hour' => $installment_hour_4
		);

		$installment5 = array(
			'date' => $installment_date_5,
			'value' => $installment_value_5,
			'hour' => $installment_hour_5
		);

		$payment = new ServicePayment(
			$userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
			$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $email, $address, $projectDenomination, $bank,
			$agency, $accountNumber, $totalValue, $period, $endPeriod, $weekHours, $weeks, $totalHours, $serviceDescription,
			$installment1, $installment2, $installment3, $installment4, $installment5
		);

		$this->load->model("payment_model");
		$wasSaved = $this->payment_model->savePayment($expense, $payment);

		if($wasSaved){
			$status = "success";
			$message = "Pagamento registrado com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível registrar o pagamento informado.";
		}

		$this->session->set_flashdata($status, $message);
		redirect("payment/expensePayments/{$expense}/{$budgetplan}");
	}

	public function generateSpreadsheet($paymentId){

		$this->load->model("payment_model");
		$paymentData = $this->payment_model->getPayment($paymentId);

		$userType = $paymentData["userType"];
		$legalSupport = $paymentData["legalSupport"];

		$resourseSource = $paymentData["resourseSource"];
		$costCenter = $paymentData["costCenter"];
		$dotationNote = $paymentData["dotationNote"];

		$name = $paymentData["name"];
		$id = $paymentData["id"];
		$pisPasep = $paymentData["pisPasep"];
		$cpf = $paymentData["cpf"];
		$enrollmentNumber = $paymentData["enrollmentNumber"];
		$arrivalInBrazil = $paymentData["arrivalInBrazil"];
		$phone = $paymentData["phone"];
		$email = $paymentData["email"];
		$address = $paymentData["address"];
		$projectDenomination = $paymentData["projectDenomination"];
		$bank = $paymentData["bank"];
		$agency = $paymentData["agency"];
		$accountNumber = $paymentData["accountNumber"];

		$totalValue = $paymentData["totalValue"];
		$period = $paymentData["period"];
		$endPeriod = $paymentData["end_period"];
		$weekHours = $paymentData["weekHours"];
		$weeks = $paymentData["weeks"];
		$totalHours = $paymentData["totalHours"];
		$serviceDescription = $paymentData["serviceDescription"];

		$installment_date_1 = $paymentData["installment_date_1"];
		$installment_date_2 = $paymentData["installment_date_2"];
		$installment_date_3 = $paymentData["installment_date_3"];
		$installment_date_4 = $paymentData["installment_date_4"];
		$installment_date_5 = $paymentData["installment_date_5"];

		$installment_value_1 = $paymentData["installment_value_1"];
		$installment_value_2 = $paymentData["installment_value_2"];
		$installment_value_3 = $paymentData["installment_value_3"];
		$installment_value_4 = $paymentData["installment_value_4"];
		$installment_value_5 = $paymentData["installment_value_5"];

		$installment_hour_1 = $paymentData["installment_hour_1"];
		$installment_hour_2 = $paymentData["installment_hour_2"];
		$installment_hour_3 = $paymentData["installment_hour_3"];
		$installment_hour_4 = $paymentData["installment_hour_4"];
		$installment_hour_5 = $paymentData["installment_hour_5"];

		$installment1 = array(
			'date' => $installment_date_1,
			'value' => $installment_value_1,
			'hour' => $installment_hour_1
		);

		$installment2 = array(
			'date' => $installment_date_2,
			'value' => $installment_value_2,
			'hour' => $installment_hour_2
		);

		$installment3 = array(
			'date' => $installment_date_3,
			'value' => $installment_value_3,
			'hour' => $installment_hour_3
		);

		$installment4 = array(
			'date' => $installment_date_4,
			'value' => $installment_value_4,
			'hour' => $installment_hour_4
		);

		$installment5 = array(
			'date' => $installment_date_5,
			'value' => $installment_value_5,
			'hour' => $installment_hour_5
		);

		$payment = new ServicePayment(
			$userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
			$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $email, $address, $projectDenomination, $bank,
			$agency, $accountNumber, $totalValue, $period, $endPeriod, $weekHours, $weeks, $totalHours, $serviceDescription,
			$installment1, $installment2, $installment3, $installment4, $installment5
		);

		$payment->downloadSheet();
	}

	// Used in an ajax post
	public function checkInstallmentQuantity(){

		$totalValue = (float) $this->input->post("totalValue");
		$installments = (float) $this->input->post("installments");

		if($totalValue <= self::MAX_TOTAL_VALUE){

			if($installments != 0){

				// Max of installments is 5
				if($installments > self::MAX_INSTALLMENTS){
					$installments = self::MAX_INSTALLMENTS;
				}

				$installmentsValue = $totalValue / $installments;
				$installmentsValue = round($installmentsValue, 2);
			}else{
				$installmentsValue = $totalValue;
				$installmentsValue = round($installmentsValue, 2);
			}

			echo "<div class='box-body table-responsive no-padding'>";
				echo "<table class='table table-bordered table-hover'>";
					echo "<tbody>";
						echo "<tr>";
					        echo "<th class='text-center'>Nº da parcela</th>";
					        echo "<th class='text-center'>Data</th>";
					        echo "<th class='text-center'>Valor</th>";
					        echo "<th class='text-center'>Horas trabalhadas</th>";
					    echo "</tr>";

					for($installment = 1; $installment <= $installments; $installment++){

						echo "<tr>";

				    		echo "<td>";
				    		echo $installment;
				    		echo "</td>";

							$installmentDate = array(
								"name" => "installment_date_".$installment,
								"id" => "installment_date_".$installment,
								"type" => "text",
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
								"value" => $installmentsValue,
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

			    	echo "</tbody>";
				echo "</table>";
			echo "</div>";
		}else{

		}
	}

	// Used in an ajax post
	public function checkInstallmentValues(){

		$totalValue = (float) $this->input->post("totalValue");
		$totalValue = round($totalValue, 2);

		$installment1 = (float) $this->input->post("installment1");
		$installment2 = (float) $this->input->post("installment2");
		$installment3 = (float) $this->input->post("installment3");
		$installment4 = (float) $this->input->post("installment4");
		$installment5 = (float) $this->input->post("installment5");

		$installmentTotal = $installment1 + $installment2 + $installment3 + $installment4 + $installment5;

		$installmentTotal = round($installmentTotal, 2);

		if($totalValue <= self::MAX_TOTAL_VALUE){

			if($installmentTotal > $totalValue){

				$result = "<div class='callout callout-danger'>";
				$result .= "<h4>";
				$result .= "O total das parcelas <b>está excendo</b> o valor total do serviço.";
				$result .= "</h4>";
				$result .= "<p>Valor total das parcelas atual: <b> R$ ".$installmentTotal."</b></p>";
				$result .= "<p>Valor total atual do serviço: <b> R$ ".$totalValue."</b></p>";
				$result .= "</div>";
			}elseif($installmentTotal < $totalValue){

				$result = "<div class='callout callout-danger'>";
				$result .= "<h4>";
				$result .= "O total das parcelas <b>está menor</b> que o valor total do serviço.";
				$result .= "</h4>";
				$result .= "<p>Valor total das parcelas atual: <b> R$ ".$installmentTotal."</b></p>";
				$result .= "<p>Valor total atual do serviço: <b> R$ ".$totalValue."</b></p>";
				$result .= "</div>";
			}else{

				$submitBtn = array(
					"id" => "new_payment",
					"class" => "btn bg-olive btn-block",
					"content" => "Cadastrar pagamento",
					"type" => "submit"
				);

				$result = "<div class='callout callout-info'>";
				$result .= "<h4>";
				$result .= "O valor das parcelas estão OK!";
				$result .= "</h4>";
				$result .= "</div>";

				$result .= "<div class='row'>";
					$result .= "<div class='col-lg-9'>";
					$result .= "</div>";
					$result .= "<div class='col-lg-3'>";
						$result .= form_button($submitBtn);
					$result .= "</div>";
				$result .= "</div>";
			}
		}else{

			$result = "<div class='callout callout-danger'>";
			$result .= "<h4>";
			$result .= "O valor total não pode exceder R$ 8000,00.";
			$result .= "</h4>";
			$result .= "<p>Valor total atual: <b>R$ ".$totalValue."</b></p>";
			$result .= "</div>";
		}

		echo $result;
	}

}
