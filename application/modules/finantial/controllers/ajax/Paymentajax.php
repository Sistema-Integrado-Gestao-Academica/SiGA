<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."finantial/constants/PaymentConstants.php");

class PaymentAjax extends MX_Controller {

    public function newStaffPaymentForm(){

        $employeeName = $this->input->post("employeeName");
        $budgetplanId = $this->input->post("budgetplanId");
        $expenseId = $this->input->post("expenseId");

        $this->load->model("program/staffs_model");
        $employees = $this->staffs_model->getEmployeeByPartialName($employeeName);

        if($employees !== FALSE){

            echo "<h4><i class='fa fa-list'></i> Funcionários encontrados:</h4>";

            buildTableDeclaration("employees_list_to_payment");

            buildTableHeaders(array(
                'Nome',
                'CPF',
                'E-mail',
                'Ações'
            ));

            foreach ($employees as $employee){
                echo "<tr>";
                    echo "<td>";
                        echo $employee['name'];
                    echo "</td>";

                    echo "<td>";
                        echo $employee['cpf'];
                    echo "</td>";

                    echo "<td>";
                        echo $employee['email'];
                    echo "</td>";

                    echo "<td>";
                        $submitBtn = array(
                            "class" => "btn btn-primary",
                            "content" => "<i class='fa fa-plus-circle'></i> Pagamento para <b>".$employee['name']."</b>",
                            "type" => "submit"
                        );
                        echo form_open("finantial/payment/employeePayment");
                            echo form_hidden("employee", $employee);
                            echo form_hidden("budgetplanId", $budgetplanId);
                            echo form_hidden("expenseId", $expenseId);
                            echo form_button($submitBtn);
                        echo form_close();
                    echo "</td>";

                echo "</tr>";
            }

            buildTableEndDeclaration();
        }else{
            callout("info", "Não foram encontrados funcionários com o nome '".$employeeName."'.");
        }
    }

    // Used in an ajax post
    public function checkInstallmentQuantity(){

        $totalValue = (float) $this->input->post("totalValue");
        $installments = (float) $this->input->post("installments");

        if($totalValue <= PaymentConstants::MAX_TOTAL_VALUE){

            if($installments != 0){

                // Max of installments is 5
                if($installments > PaymentConstants::MAX_INSTALLMENTS){
                    $installments = PaymentConstants::MAX_INSTALLMENTS;
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

        if($totalValue <= PaymentConstants::MAX_TOTAL_VALUE){

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
