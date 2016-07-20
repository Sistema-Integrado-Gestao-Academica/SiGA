<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProductionAjax extends MX_Controller {

	public function getISSNAndQualis(){

		$periodic = $this->input->post("periodic");

		$this->load->model("program/production_model");
		$qualis = $this->production_model->getQualisByPeriodicName($periodic);

		$json = array();
		if($qualis !== FALSE){

	        $json = array (
	            'qualis'=> $qualis[0]['qualis'],
	            'issn' => $qualis[0]['issn']
	        );
	        echo json_encode($json);
		}
		else{
	        echo json_encode($json);
		}

	}

	public function getPeriodicNameAndQualis(){

		$issn = $this->input->post("issn");

		$this->load->model("program/production_model");
		$qualis = $this->production_model->getQualisByISSN($issn);

		$json = array();
		if($qualis !== FALSE){

	        $json = array (
	            'qualis'=> $qualis[0]['qualis'],
	            'periodic' => $qualis[0]['periodic']
	        );
	        echo json_encode($json);
		}
		else{
	        echo json_encode($json);
		}

	}

	public function getAuthorByCpf($cpfs){
		
	}

	public function saveAuthor(){

		$valid = $this->validateAuthor();

        if($valid){
            $success = $this->createAuthor();
            if($success){
                $divalert = "<div class='alert alert-success'> ";
                $enddiv = "</div>";

                $productionId = $this->input->post("production_id");
       			$name = $this->input->post("name");

                $this->load->model('program/production_model');
                $data = $this->production_model->getAuthorByProductionAndName($productionId, $name);

                $message = $divalert."Autor adicionado com sucesso".$enddiv;

                $json = array (
                    'status' => "success",
                    'cpf' => $data[0]['cpf'],
                    'name' => $data[0]['author_name'],
                    'message' => $message
                );
                echo json_encode($json);
            }
            else{
                $divalert = "<div class='alert alert-danger'> ";
                $enddiv = "<\/div>";
                $message = $divalert."Não foi possível adicionar o autor".$enddiv;
                
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

    private function validateAuthor(){

        $this->load->library("form_validation");

        $this->form_validation->set_rules("name", "Nome", "required");
 
        $this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");

        $success = $this->form_validation->run();

        return $success;
    }

    private function createAuthor(){

        $productionId = $this->input->post("production_id");
        $name = $this->input->post("name");
        $cpf = $this->input->post("cpf");

        $data = array(
            'production_id' => $productionId,
            'author_name' => $name,
            'cpf' => $cpf
        );
        
		$this->load->model("program/production_model");
        $success = $this->production_model->saveAuthors($data);
       

        return $success;
    }

}