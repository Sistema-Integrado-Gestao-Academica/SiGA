<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budgetplan extends CI_Controller {

	public function index() {
		session();
		$this->load->model("budgetplan_model");
		$this->load->model("course_model");
		$budgetplans = $this->budgetplan_model->all();
		foreach ($budgetplans as $key => $b) {
			$budgetplans[$key]['status'] = $this->budgetplan_model->getBudgetplanStatus($b['status']);
			$course = $this->course_model->getCourseById($b['course_id']);
			$budgetplans[$key]['course'] = $course ? $course->course_name : "Nenhum";
		}

		$status_options = $this->db->get("budgetplan_status")->result_array();
		$status = array();
		foreach ($status_options as $s) {
			array_push($status, $s['description']);
		}

		$this->load->helper(array("currency"));
		$data = array("budgetplans" => $budgetplans, "status" => $status);
		$this->load->template('budgetplan/index', $data);
	}

	public function save() {
		session();
		$amount = $this->input->post("amount");
		$status = (int) $this->input->post("status") + 1;

		$budgetplan = array('amount' => $amount, 'status' => $status, 'balance' => $amount);
		$this->load->model('budgetplan_model');

		if ($this->budgetplan_model->save($budgetplan)) {
			$this->session->set_flashdata("success", "Novo Plano orçamentário cadastrado");
		} else {
			$this->session->set_flashdata("danger", "Houve algum erro. Plano orçamentário não cadastrado");
		}

		redirect("planoorcamentario");
	}

	public function edit($id) {
		session();
		$this->load->model('budgetplan_model');
		$budgetplan = $this->budgetplan_model->get('id', $id);

		$status_options = $this->db->get("budgetplan_status")->result_array();
		$status = array();
		foreach ($status_options as $s) {
			array_push($status, $s['description']);
		}

		$this->load->model('course_model');
		$courses_options = $this->course_model->getAllCourses();
		$courses = array("Nenhum");
		foreach ($courses_options as $c) {
			array_push($courses, $c['course_name']);
		}

		$disable_amount   = $budgetplan['status'] == 3 || $budgetplan['status'] == 4 ? "readonly" : "";
		$disable_spending = $budgetplan['status'] == 4 ? "readonly" : "";

		$expenses = $this->budgetplan_model->getExpenses($budgetplan);

		$this->load->helper(array("currency"));
		$data = array(
			'budgetplan' => $budgetplan,
			'status' => $status,
			'courses' => $courses,
			'disable_amount' => $disable_amount,
			'disable_spending' => $disable_spending,
			'expenses' => $expenses
		);
		$this->load->template("budgetplan/edit", $data);
	}

	public function update() {
		session();
		$id = $this->input->post("budgetplan_id");
		$course = $this->input->post("course");
		$amount = $this->input->post("amount");
		$status = $this->input->post("status") + 1;
		$spending = $this->input->post("spending");
		$continue = $this->input->post("continue");

		if (!$continue) {
			redirect("planoorcamentario/{$id}");
		}

		$budgetplan = array(
			'id' => $id,
			'amount' => $amount,
			'status' => $status,
			'spending' => $spending,
			'balance' => $amount - $spending
		);

		if ($course) {
			$budgetplan['course_id'] = $course;
		}

		$this->load->model('budgetplan_model');
		if ($this->budgetplan_model->update($budgetplan)) {
			$this->session->set_flashdata("success", "Plano orçamentário alterado");
			redirect("planoorcamentario");
		} else {
			$this->session->set_flashdata("danger", "Houve algum erro. Tente novamente");
			redirect("planoorcamentario/{$id}");
		}
	}

	public function delete() {
		session();
		$id = $this->input->post("budgetplan_id");
		$this->load->model('budgetplan_model');

		if ($this->budgetplan_model->delete($id)) {
			$this->session->set_flashdata("danger", "Plano orçamentário foi removido");
		}

		redirect("planoorcamentario");
	}

	public function newExpense($id) {
		session();
		$this->load->model('budgetplan_model');
		$budgetplan = $this->budgetplan_model->get('id', $id);

		$months = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
				'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

		$data = array('budgetplan' => $budgetplan, 'months' => $months);
		$this->load->template("budgetplan/expense", $data);
	}

	public function saveExpense() {
		session();
		$months = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
				'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

		$expense = array(
			'year' => $this->input->post("year"),
			'nature' => $this->input->post("nature"),
			'value' => $this->input->post("value"),
			'budgetplan_id' => $this->input->post("budgetplan_id")
		);

		$id = $expense['budgetplan_id'];
		$expense['month'] = $months[$this->input->post("month")];

		$this->load->model('budgetplan_model');
		$budgetplan = $this->budgetplan_model->get('id', $id);
		$budgetplan['spending'] += $expense['value'];
		$budgetplan['balance'] = $budgetplan['amount'] - $budgetplan['spending'];

		if ($this->budgetplan_model->saveExpense($expense) && $this->budgetplan_model->update($budgetplan)) {
			$this->session->set_flashdata("success", "Nova despesa adicionada com sucesso");
			redirect("planoorcamentario/{$id}");
		} else {
			$this->session->set_flashdata("danger", "Houve algum erro. Tente novamente");
			redirect("planoorcamentario/{$id}/novadespesa");
		}
	}

}

/* End of file budgetplan.php */
/* Location: ./application/controllers/budgetplan.php */ ?>