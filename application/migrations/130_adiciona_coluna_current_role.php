<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."/secretary/constants/EnrollmentConstants.php");

class Migration_Adiciona_coluna_current_role extends CI_Migration {

    public function up() {
        $this->dbforge->add_column('student_request', array(
            'current_role' => array('type' => 'varchar(10)')
        ));

        // $this->fix();
    }

    public function down(){
        $this->dbforge->drop_column('student_request', "current_role");
    }

    const SEMESTER = 6;

    /**
     * Fix the vacancy quantities
     */
    public function fix(){

        $this->load->model('secretary/request_model');
        $this->load->model('secretary/offer_model');

        // Get all requests for the 2/2016 semester
        $requests = $this->request_model->getRequest(array('id_semester' => self::SEMESTER), FALSE);

        foreach ($requests as $request){
            $this->fixCurrentRoles($request);
            $this->fixVacancies($request);
        }
        $this->fixCurrentVacancies($requests);
    }

    private function fixVacancies($request){

        $this->filterRepeatedRequests($request['id_request']);

        $disciplines = $this->request_model->getRequestDisciplinesById($request['id_request']);

        // If the secretary has already finalized the request, the vacancies are right
        if(!$request['secretary_approval']){

            if($disciplines !== FALSE && !empty($disciplines)){

                $this->db->trans_start();
                foreach ($disciplines as $discipline){
                    if($discipline['status'] === EnrollmentConstants::PRE_ENROLLED_STATUS
                        || $discipline['status'] === EnrollmentConstants::APPROVED_STATUS){

                        if($discipline['is_update']){
                            $this->offer_model->subtractOneVacancy($discipline['discipline_class']);
                        }
                    }elseif($discipline['status'] === EnrollmentConstants::REFUSED_STATUS){
                        $this->offer_model->addOneVacancy($discipline['discipline_class']);
                    }elseif($discipline['status'] === EnrollmentConstants::NO_VACANCY_STATUS){
                        // May have a discipline with the status 'no_vacancy', which means that the student is not enrolled in this discipline, so delete it from the request
                        $this->db->delete('request_discipline', $discipline);
                    }
                }
                $this->db->trans_complete();
            }
        }
    }

    private function fixCurrentVacancies(){

        $this->db->select('*');
        $this->db->from("student_request");
        $this->db->join("request_discipline", "request_discipline.id_request = student_request.id_request");
        $where = "((student_request.id_semester = '".self::SEMESTER."') AND ((request_discipline.status =
                '".EnrollmentConstants::PRE_ENROLLED_STATUS."') OR (request_discipline.status =
                '".EnrollmentConstants::APPROVED_STATUS."')))";
        $this->db->where($where);
        $this->db->order_by("request_discipline.discipline_class", "asc");
        $requests = $this->db->get()->result_array();

        $requests = checkArray($requests);

        $filledVacancies = array();
        if($requests !== FALSE){
            foreach ($requests as $request) {
                $id = $request['discipline_class'];
                if(isset($filledVacancies[$id])){
                    $filledVacancies[$id] += 1;
                }
                else{
                    $filledVacancies[$id] = 1;
                }
            }
        }

        foreach ($filledVacancies as $id => $vacancies) {
            $discipline = $this->offer_model->getOfferDisciplineById($id);
            $disciplineFilledVacancies = $discipline['total_vacancies'] - $discipline['current_vacancies'];
            if($disciplineFilledVacancies != $vacancies){
                if($discipline['total_vacancies'] >= $vacancies){
                    $newCurrentVacancies = $discipline['total_vacancies'] - $vacancies;
                    $this->db->where("id_offer_discipline", $id);
                    $this->db->update("offer_discipline", array('current_vacancies' => $newCurrentVacancies));
                }
            }
        }
    }

    private function filterRepeatedRequests($requestId){

        $requests = $this->request_model->getRequestDisciplinesById($requestId);

        $filteredRequests = array();
        foreach ($requests as $request){

            $requestId = $request['id_request'];
            $idOfferDiscipline = $request['discipline_class'];
            $date = $request['requested_on'] !== NULL ? new DateTime($request['requested_on']) : NULL;

            if(isset($filteredRequests[$requestId][$idOfferDiscipline])){

                $storedRequest = $filteredRequests[$requestId][$idOfferDiscipline];

                $currentDate = new DateTime($storedRequest['requested_on']);

                if($date > $currentDate){
                    $this->db->delete('request_discipline', $storedRequest);
                    $filteredRequests[$requestId][$idOfferDiscipline] = $request;
                }

            }else{
                $filteredRequests[$requestId][$idOfferDiscipline] = $request;
            }
        }
    }

    private function fixCurrentRoles($request){
        $needMastermindApproval = $this->offer_model->needMastermindApproval(self::SEMESTER, $request['id_course']);

        if($needMastermindApproval){

            if($request['mastermind_approval']){
                $role = EnrollmentConstants::REQUEST_TO_SECRETARY;
            }else{
                $role = EnrollmentConstants::REQUEST_TO_MASTERMIND;
            }
        }else{
            $role = EnrollmentConstants::REQUEST_TO_SECRETARY;
        }

        $this->request_model->updateCurrentRole($request['id_request'], $role);
    }
}