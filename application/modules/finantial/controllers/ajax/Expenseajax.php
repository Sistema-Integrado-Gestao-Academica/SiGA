<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/finantial/constants/ExpenseNatureConstants.php");
require_once(MODULESPATH."/finantial/controllers/Expense.php");

class ExpenseAjax extends MX_Controller {

    public function newExpenseNatureFromModal(){

        $valid = $this->validateExpenseNatureData();

        if($valid){
            $success = $this->createExpenseNature();
            if($success){
                $divalert = "<div class='alert alert-success'> ";
                $enddiv = "</div>";

                $this->load->model('finantial/expense_model');
                $data = $this->expense_model->getLastExpenseType();

                $message = $divalert.ExpenseNatureConstants::EXPENSE_NATURE_SUCCESS.$enddiv;

                $json = array (
                    'status' => "success",
                    'id'=> $data['id'],
                    'code' => $data['code'],
                    'description' => $data['description'],
                    'message' => $message
                    );
                echo json_encode($json);
            }
            else{
                $divalert = "<div class='alert alert-danger'> ";
                $enddiv = "<\/div>";
                $message = $divalert.ExpenseNatureConstants::EXPENSE_NATURE_FAIL.$enddiv;
                
                $json = array (
                    'status' => "failed",
                    'message' => $message
                );
                echo json_encode($json);
            }
        }
        else{
            $divalert = "<div class='alert alert-danger'> ";
            $errors = validation_errors(); 
            $enddiv = "</div>";
            $message = $divalert.$errors.$enddiv;
                
            $json = array (
                'status' => "failed",
                'message' => $message
            );
            echo json_encode($json);
        }

    }

    private function validateExpenseNatureData($checkCode=TRUE){

        $this->load->library("form_validation");

        $this->form_validation->set_rules("description", "Descrição da despesa", "required");
        if($checkCode){
            $this->form_validation->set_rules("code", "Código", "verify_if_code_no_exists");
        }
        $this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");

        $success = $this->form_validation->run();

        return $success;
    }

    private function createExpenseNature(){
        
        $code = $this->input->post("code");
        $description = $this->input->post("description");

        if(empty($code)){
            $code = NULL;
        }

        $data = array(
            'code' => $code,
            'description' => $description,
            'status' => ExpenseNatureConstants::ACTIVE
        );
        
        $this->load->model('finantial/expense_model');
        $success = $this->expense_model->createExpenseType($data);

        return $success;
    }

}
