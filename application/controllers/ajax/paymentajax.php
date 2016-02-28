<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/controllers/staff.php");

class PaymentAjax extends CI_Controller {

    public function newStaffPaymentForm(){

        $employeeName = $this->input->post("employeeName");
        $budgetplanId = $this->input->post("budgetplanId");
        $expenseId = $this->input->post("expenseId");

        $staff = new Staff();
        $employees = $staff->getEmployeeByPartialName($employeeName);

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
                        echo form_open("payment/employeePayment");
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

}
