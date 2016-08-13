<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OfferAjax extends MX_Controller {

    public function newEnrollmentPeriod(){

        $valid = $this->validatePeriodData();
        if($valid){
            $result = $this->createEnrollmentPeriod();
            if($result['status']){
                $divalert = "<div class='alert alert-success'> ";
            }
            else{
                $divalert = "<div class='alert alert-danger'> ";
            }
            $enddiv = "</div>";
            $message = $divalert.$result['message'].$enddiv;

            $json = array(
                'status' => $result['status'],
                'message' => $message
            );
            echo json_encode($json);
        }
        else{
            $divalert = "<div class='alert alert-danger'> ";
            $errors = validation_errors(); 
            $enddiv = "</div>";
            $message = $divalert.$errors.$enddiv;
                
            $json = array(
                'status' => FALSE,
                'message' => $message
            );
            echo json_encode($json);
        }

    }

    private function validatePeriodData(){

        $this->load->library("form_validation");

        $this->form_validation->set_rules("enrollment_start_date", "Data de início", "required");
        $this->form_validation->set_rules("enrollment_end_date", "Data de Fim", "required");
        $this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");

        $success = $this->form_validation->run();

        return $success;
    }

    private function createEnrollmentPeriod(){
        
        $oldStartDate = $this->input->post("old_start_date");
        $startDate = $this->input->post("enrollment_start_date");
        $endDate = $this->input->post("enrollment_end_date");
        $offerId = $this->input->post("offerId");

        $oldStartDate = convertDateToDateTime($oldStartDate);
        $startDate = convertDateToDateTime($startDate);
        $endDate = convertDateToDateTime($endDate);

        $validNewStartDate = $this->checkStartDates($oldStartDate, $startDate);

        if($validNewStartDate){

            if(!is_null($startDate) && !is_null($endDate)){

                $data = array(
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                );
                
                $validDates = validateDatesDiff($startDate, $endDate);

                if($validDates){
                    $this->load->model("secretary/offer_model");
                    $status = $this->offer_model->saveEnrollmentPeriod($data, $offerId);    
                    if($status){
                        $message = "Período definido com sucesso";
                    }
                    else{
                        $message = "Não foi possível definir o período. Tente novamente.";
                    }
                }
                else{
                    $status = FALSE;
                    $message = "A Data Final deve ser maior que a Data Inicial.";   
                }
            }
            else{
                $status = FALSE;
                $message = "Data inválida.";   
            }
        }
        else{
            $status = FALSE;
            $message = "A data de início não pode mais ser modificada.";   
        }
        
        $result = array(
            'status' => $status,
            'message' => $message
        );

        return $result;
    }

    private function checkStartDates($oldStartDate, $startDate){

        $now = new Datetime();
        $now = $now->format('Y/m/d');

        // If the start date already passed, the field is not editable  
        if(!empty($oldStartDate) && $now >= $oldStartDate){
            if($oldStartDate != $startDate){
                $validNewStartDate = FALSE;
            }
            else{
                $validNewStartDate = TRUE;
            }
        }
        else{
            $validNewStartDate = TRUE;
        }

        return $validNewStartDate;
    }
}