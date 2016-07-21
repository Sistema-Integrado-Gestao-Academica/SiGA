<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/domain/User.php");
require_once(MODULESPATH."auth/exception/UserException.php");

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

	public function getAuthorNameByCPF(){
		
		$cpf = $this->input->post("cpf");

		$this->load->model("auth/usuarios_model");
		$authorName = $this->usuarios_model->getUserByCpf($cpf);

		$json = array();
		if($authorName !== FALSE){

	        $json = array (
	            'name'=> $authorName['name']
	        );
		}
	    echo json_encode($json);
	}

	public function getAuthorCPFByName(){
		
		$name = $this->input->post("name");

		$this->load->model("auth/usuarios_model");
		$authorCPF = $this->usuarios_model->getCpfByName($name);

		$json = array();
		if($authorCPF !== FALSE){

	        $json = array (
	            'cpf'=> $authorCPF
	        );
		}
	    echo json_encode($json);
	}
	

	public function saveAuthor(){

		$valid = $this->validateAuthor();

        if($valid){
            try {
            	$data = $this->createAuthor();
                $divalert = "<div class='alert alert-success'> ";
                $enddiv = "</div>";

                $message = $divalert."Autor adicionado com sucesso".$enddiv;

                $data['message'] = $message;
                $data['status'] = "success";

                echo json_encode($data);
            } 
            catch (UserException $e) {
                $divalert = "<div class='alert alert-danger'> ";
                $enddiv = "</div>";
                $message = $divalert.$e->getMessage().$enddiv;
                
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

        $this->form_validation->set_rules("order", "Ordem de Coautoria", "valid_order_coauthor");
        $this->form_validation->set_rules("name", "Nome", "required");
        $this->form_validation->set_rules("cpf", "Cpf", "valid_cpf");
 
        $this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");

        $success = $this->form_validation->run();

        return $success;
    }

    private function createAuthor(){

        $productionId = $this->input->post("production_id");
        $name = $this->input->post("name");
        $cpf = $this->input->post("cpf");
        $order = $this->input->post("order");

		$this->load->model("auth/usuarios_model");
		$user = $this->usuarios_model->getUserByCpf($cpf);

        $id = FALSE;
		if($user !== NULL){
			$id = $user['id'];
		}
        $author = new User($id, $name, $cpf);

        $data = array();
		$this->load->model("program/production_model");
        $exists = $this->production_model->checkIfOrderExists($order, $productionId);
	
		if($exists){
			throw new UserException("Coautor existente na ordem informada");
		}
		else{

			$this->production_model->saveAuthors($author, $productionId, $order);

	        $data = array (
		        'cpf' => $cpf,
		        'name' => $name,
		        'order' => $order,
		        'production_id' => $productionId,
	    	);
	    	 
		}
    	return $data;
    }

    public function deleteAuthor(){
    	$productionId = $this->input->post("production_id");
        $name = $this->input->post("name");

		$this->load->model("program/production_model");
		$success = $this->production_model->deleteCoauthor($productionId, $name);
		echo $success;
    }

}