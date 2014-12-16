<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Budgetplan extends CI_Controller {

	public function index() {
		$this->load->model("budgetplan_model");
		$this->load->model("course_model");
		$budgetplans = $this->budgetplan_model->all();
		foreach ($budgetplans as $key => $b) {
			$budgetplans[$key]['status'] = $this->budgetplan_model->getBudgetplanStatus($b['status']);
			$course = $this->course_model->getCourseById($b['course_id']);
			$budgetplans[$key]['course'] = $course ? $course->course_name : NULL;
		}

		$status_options = $this->db->get("budgetplan_status")->result_array();
		$options = array();
		foreach ($status_options as $s) {
			array_push($options, $s['description']);
		}

		$this->load->helper(array("currency"));
		$data = array("budgetplans" => $budgetplans, "options" => $options);
		$this->load->template('budgetplan/index', $data);
	}

	public function save() {
		$current_user = autoriza();
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

}

/* End of file budgetplan.php */
/* Location: ./application/controllers/budgetplan.php */ ?>