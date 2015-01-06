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

		redirect("plano%20orcamentario");
	}

	public function edit($id) {
		session();
		$this->load->model('budgetplan_model');
		$budgetplan = array('id' => $id);
		$budgetplan = $this->budgetplan_model->get('id', $budgetplan);

		$status_options = $this->db->get("budgetplan_status")->result_array();
		$status = array();
		foreach ($status_options as $s) {
			array_push($status, $s['description']);
		}

		$this->load->model('course_model');
		$courses_options = $this->course_model->getAllCourses();
		$courses = array('courses_name' => "Nenhum");
		foreach ($courses_options as $c) {
			array_push($courses, $c['course_name']);
		}

		$disable_amount   = $budgetplan['status'] == 3 || $budgetplan['status'] == 4 ? "readonly" : "";
		$disable_spending = $budgetplan['status'] == 4 ? "readonly" : "";

		$data = array(
			'budgetplan' => $budgetplan,
			'status' => $status,
			'courses' => $courses,
			'disable_amount' => $disable_amount,
			'disable_spending' => $disable_spending
		);
		$this->load->template("budgetplan/edit", $data);
	}

	public function update() {
		session();
		$id = $this->input->post("budgetplan_id");
		$course = $this->input->post("course") + 1;
		$amount = $this->input->post("amount");
		$status = (int) $this->input->post("status") + 1;
		$spending = $this->input->post("spending");
		$confirm = $this->input->post("confirm");

		if (!$confirm) {
			redirect("plano%20orcamentario/{$id}");
		}

		$budgetplan = array(
			'id' => $id,
			'course_id' => $course,
			'amount' => $amount,
			'status' => $status,
			'spending' => $spending,
			'balance' => $amount - $spending
		);

		$this->load->model('budgetplan_model');
		if ($this->budgetplan_model->update($id, $budgetplan)) {
			$this->session->set_flashdata("success", "Plano orçamentário alterado");
			redirect("plano%20orcamentario");
		} else {
			$this->session->set_flashdata("danger", "Houve algum erro tente novamente");
			redirect("plano%20orcamentario/{$id}");
		}
	}

}

/* End of file budgetplan.php */
/* Location: ./application/controllers/budgetplan.php */ ?>