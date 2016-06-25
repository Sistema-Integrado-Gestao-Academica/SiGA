<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/PermissionConstants.php");
require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."/finantial/constants/ExpenseNatureConstants.php");
require_once(MODULESPATH."/finantial/domain/ExpenseDetail.php");
require_once MODULESPATH.'finantial/exception/ExpenseException.php';

class Expense extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('finantial/expense_model');
		$this->load->model('finantial/budgetplan_model');
		
	}

	public function index($budgetplan_id) {
		
		$budgetplan = $this->budgetplan_model->get('id', $budgetplan_id);

		$months = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
				'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

		$activeTypes = $this->expense_model->getAllExpenseTypes($onlyActives=TRUE);
		$expenseTypes = array();
		foreach ($activeTypes as $type) {
			$code = $type['code'];
			if($code == NULL){
				$code = "Sem código";
			}
			$expenseTypes[$type['id']] = $code . " - " . $type['description'];
		}

		$data = array('budgetplan' => $budgetplan, 'months' => $months, 'types' => $expenseTypes);
		
		loadTemplateSafelyByPermission(PermissionConstants::BUDGETPLAN_PERMISSION, "finantial/budgetplan/expense", $data);
	}

	public function save() {
		$months = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
				'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

		$expense = array(
			'year' => $this->input->post("year"),
			'expense_type_id' => $this->input->post("type"),
			'value' => $this->input->post("value"),
			'budgetplan_id' => $this->input->post("budgetplan_id")
		);

		$continue = $this->input->post("continue");
		if (!$continue) {
			redirect("budgetplan/new_expense/{$expense['budgetplan_id']}");
		}

		$id = $expense['budgetplan_id'];
		$expense['month'] = $months[$this->input->post("month")];

		$budgetplan = $this->budgetplan_model->get('id', $id);
		$budgetplan['spending'] += $expense['value'];
		$budgetplan['balance'] = $budgetplan['amount'] - $budgetplan['spending'];

		$session = getSession();
		if ($this->expense_model->save($expense) && $this->budgetplan_model->update($budgetplan)) {
			$session->showFlashMessage("success", "Nova despesa adicionada com sucesso.");
			redirect("budgetplan_expenses/{$id}");
		} else {
			$session->showFlashMessage("danger", "Houve algum erro. Tente novamente.");
			redirect("budgetplan/new_expense/{$id}");
		}
	}

	public function delete() {
		$expense_id = $this->input->post('expense_id');
		$budgetplan_id = $this->input->post('budgetplan_id');

		$expense = $this->expense_model->get('id', $expense_id);
		$budgetplan = $this->budgetplan_model->get('id', $budgetplan_id);

		$budgetplan['spending'] -= $expense['value'];
		$budgetplan['balance'] = $budgetplan['amount'] - $budgetplan['spending'];

		$session = getSession();
		if ($this->expense_model->delete($expense_id) && $this->budgetplan_model->update($budgetplan)) {
			$session->showFlashMessage("danger", "Despesa foi removida");
		} else {
			$this->expense_model->save($expense);
			$session->showFlashMessage("danger", "Houve algum erro. Tente novamente");
		}

		redirect("budgetplan_expenses/{$budgetplan_id}");
	}
	
	public function expenseDetails($expenseId){

		$expense = $this->expense_model->get('id', $expenseId);

		$type = $this->expense_model->getExpenseType($expense['expense_type_id']);
		$expense['expense_type_id'] = $type['id'];
		$expense['expense_type_description'] = $type['description'];

		$this->load->helper(array("currency"));

		$expenses = $this->expense_model->getAllExpensesFromAExpense($expenseId);
		
		$data = array('expense' => $expense, 'expenses' => $expenses);

		loadTemplateSafelyByGroup(GroupConstants::FINANCIAL_SECRETARY_GROUP, 'finantial/expense/expense_details.php', $data);
	}

	public function saveExpenseDetail(){
		
		$expenseId = $this->input->post("id");
		$data = $this->getExpenseDetailData($expenseId);
		$session = getSession();

		if($data['valid']){

			$success = $this->expense_model->createExpenseDetail($data['expense']);
			$session = getSession();
			if($success){
				$status = "success";
				$message = "Despesa criada com sucesso.";
			}
			else{
				$status = "danger";
				$message = "Não foi possível criar a despesa.";
			}
			$session->showFlashMessage($status, $message);
			redirect('expense_details/'.$expenseId);

		}
		else{
			if(!empty($data['message'])){

				$session->showFlashMessage("danger", $data['message']);
			}
			redirect('expense_details/'.$expenseId);
		}
	}

	public function editExpenseDetails($expenseDetailId){

		$expense = $this->expense_model->getExpenseDetail($expenseDetailId);

		$data = array('expense' => $expense);

		loadTemplateSafelyByGroup(GroupConstants::FINANCIAL_SECRETARY_GROUP, 'finantial/expense/edit_expense_detail.php', $data);
	}

	public function updateExpenseDetails($expenseDetailId){
		
		$expenseId = $this->expense_model->getExpenseIdOfAExpenseDetail($expenseDetailId);
		$data = $this->getExpenseDetailData($expenseId);
		$session = getSession();

		if($data['valid']){

			$success = $this->expense_model->updateExpenseDetail($expenseDetailId, $data['expense']);
			$session = getSession();
			if($success){
				$status = "success";
				$message = "Despesa criada com sucesso.";
			}
			else{
				$status = "danger";
				$message = "Não foi possível criar a despesa.";
			}
			$session->showFlashMessage($status, $message);
			redirect('expense_details/'.$expenseId);

		}
		else{
			if(!empty($data['message'])){

				$session->showFlashMessage("danger", $data['message']);
			}
			redirect('expense_details/'.$expenseId);
		}
	}

	private function getExpenseDetailData($expenseId){

		$valid = $this->validateExpenseDetailData();
		
		if($valid){
			$note = $this->input->post("note");
			$emissionDate = $this->input->post("expense_detail_emission_date"); 
			$seiProcess = $this->input->post("sei_process");
			$value = $this->input->post("value");
			$description = $this->input->post("description");

			try{
				$expense = new ExpenseDetail($note, $emissionDate, $seiProcess, $value, $description);

				if(!empty($emissionDate) && !is_null($emissionDate)){
					$date = $expense->getYMDEmissionDate();
				}
				else{
					$date = "";
				}

				$expenseArray = array(
					'note' => $expense->getNote(),
					'emission_date' => $date,
					'sei_process' => $expense->getSEIProcess(),
					'value' => $expense->getValue(),
					'description' => $expense->getDescription(),
					'expense_id' => $expenseId
				);

				$data = array(
					'valid' => TRUE,
					'expense' => $expenseArray
				);
			}
			catch(ExpenseException $exception){
				$data = array(
					'valid' => FALSE,
					'message' => $exception->getMessage()
				);
			}
		}
		else{
			$data = array(
				'valid' => FALSE,
				'message' => ""
			);
		}

		return $data;
	}

	public function expensesNature(){

		$expensesTypes = $this->expense_model->getAllExpenseTypes();
		
		$data = array(

			'expensesTypes' => $expensesTypes

		);
		loadTemplateSafelyByGroup(GroupConstants::FINANCIAL_SECRETARY_GROUP, 'finantial/expense/expenses_nature.php', $data);

	}

	public function updateStatusExpenseNature($expenseId){

		$status = $this->input->post('status');
		
		$new_status = null;
		
		if($status == ExpenseNatureConstants::ACTIVE){
			$new_status = ExpenseNatureConstants::ACTIVE_INVERSE;
		}
		else if($status == ExpenseNatureConstants::INACTIVE){
			$new_status = ExpenseNatureConstants::INACTIVE_INVERSE;
		}
		
		$data = array('status' => $new_status);
		$success = $this->expense_model->updateExpenseType($expenseId, $data);

		$session = getSession();

		if($success){
			$session->showFlashMessage("success", "Natureza de despesa ".lang('toMessage'.$new_status)." com sucesso.");
		}
		else{
			$session->showFlashMessage("danger", "Natureza de despesa não foi ".lang('toMessage'.$new_status).". Tente novamente.");	
		}

		redirect('expense_nature');
	}

	public function editExpenseNature($expenseId){

		$expenseType = $this->expense_model->getExpenseType($expenseId);

		$data = array(

			'expenseType' => $expenseType

		);
		loadTemplateSafelyByGroup(GroupConstants::FINANCIAL_SECRETARY_GROUP, 'finantial/expense/edit_expense_nature.php', $data);
		
	}

	public function updateExpenseNature($expenseId){

		$code = $this->input->post("code");
		$oldCode = $this->input->post("old_code");
		$isToCheckCode = $this->checkCode($code, $oldCode);

		$valid = $this->validateExpenseNatureData($isToCheckCode);
		
		if($valid){

			$description = $this->input->post("description");
			
			if($code == ""){
				$code = NULL;
			}
			
			$data = array(
				'code' => $code,
				'description' => $description
			);
			
			$success = $this->expense_model->updateExpenseType($expenseId, $data);

			$session = getSession();
			if($success){
				$session->showFlashMessage("success", ExpenseNatureConstants::EXPENSE_NATURE_SUCCESS);
				redirect('expense_nature');
			}
			else{
				$session->showFlashMessage("danger", ExpenseNatureConstants::EXPENSE_NATURE_FAIL);	
				redirect('edit_expense_nature/{$expenseId}');
			}
		}
		else{
			$this->editExpenseNature($expenseId);
		}

	}

	private function checkCode($code, $oldCode){

		if($code == $oldCode){
			$checkCode = FALSE;
		}
		else{
			$checkCode = TRUE;
		}
		
		return $checkCode;
	}
	
	public function newExpenseNature(){

		$valid = $this->validateExpenseNatureData();

		if($valid){
			
			$success = $this->createExpenseNature();
			$session = getSession();
			if($success){
				$session->showFlashMessage("success", "Natureza de despesa criada com sucesso.");
				redirect('expense_nature');
			}
			else{
				$session->showFlashMessage("danger", "Não foi possível criar a natureza de despesa.");	
				redirect('new_expense_nature');
			}
		}
		else{
			loadTemplateSafelyByGroup(GroupConstants::FINANCIAL_SECRETARY_GROUP, 'finantial/expense/new_expense_nature.php');
		}
		
	}

	public function newExpenseNatureFromModal(){

		$valid = $this->validateExpenseNatureData();

		if($valid){
			$success = $this->createExpenseNature();
			if($success){
				$divalert = "<div class='alert alert-success'> ";
				$enddiv = "</div>";

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
		
		$success = $this->expense_model->createExpenseType($data);

		return $success;
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

	private function validateExpenseDetailData(){

		$this->load->library("form_validation");

		$this->form_validation->set_rules("value", "Valor", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");

		$success = $this->form_validation->run();

		return $success;
	}


}