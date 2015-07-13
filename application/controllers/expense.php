<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expense extends CI_Controller {

	public function index($budgetplan_id) {
		session();
		$this->load->model('budgetplan_model');
		$this->load->model('expense_model');
		$budgetplan = $this->budgetplan_model->get('id', $budgetplan_id);

		$months = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
				'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

		$types = $this->expense_model->getAllExpenseTypes();
		$expenseTypes = array();
		foreach ($types as $type) {
			$expenseTypes[$type['id']] = $type['id'] . " - " . $type['description'];
		}

		$data = array('budgetplan' => $budgetplan, 'months' => $months, 'types' => $expenseTypes);
		$this->load->template("budgetplan/expense", $data);
	}

	public function save() {
		session();
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
			redirect("planoorcamentario/{$expense['budgetplan_id']}/novadespesa");
		}

		$id = $expense['budgetplan_id'];
		$expense['month'] = $months[$this->input->post("month")];

		$this->load->model('budgetplan_model');
		$budgetplan = $this->budgetplan_model->get('id', $id);
		$budgetplan['spending'] += $expense['value'];
		$budgetplan['balance'] = $budgetplan['amount'] - $budgetplan['spending'];

		$this->load->model('expense_model');

		if ($this->expense_model->save($expense) && $this->budgetplan_model->update($budgetplan)) {
			$this->session->set_flashdata("success", "Nova despesa adicionada com sucesso.");
			redirect("budgetplan/budgetplanExpenses/{$id}");
		} else {
			$this->session->set_flashdata("danger", "Houve algum erro. Tente novamente.");
			redirect("planoorcamentario/{$id}/novadespesa");
		}
	}

	public function delete() {
		session();
		$expense_id = $this->input->post('expense_id');
		$budgetplan_id = $this->input->post('budgetplan_id');

		$this->load->model('expense_model');
		$this->load->model('budgetplan_model');

		$expense = $this->expense_model->get('id', $expense_id);
		$budgetplan = $this->budgetplan_model->get('id', $budgetplan_id);

		$budgetplan['spending'] -= $expense['value'];
		$budgetplan['balance'] = $budgetplan['amount'] - $budgetplan['spending'];

		if ($this->expense_model->delete($expense_id) && $this->budgetplan_model->update($budgetplan)) {
			$this->session->set_flashdata("danger", "Despesa foi removida");
		} else {
			$this->expense_model->save($expense);
			$this->session->set_flashdata("danger", "Houve algum erro. Tente novamente");
		}

		redirect("budgetplan/budgetplanExpenses/{$budgetplan_id}");
	}

}

/* End of file expense.php */
/* Location: ./application/controllers/expense.php */