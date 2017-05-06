<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Secretary_model extends CI_Model {

    public function isSecretaryOfCourse($userId, $courseId){
        $secretaries = $this->db->get_where(
            'secretary_course',
            ['id_course' => $courseId, 'id_user' => $userId]
        )->row_array();

        $secretaries = checkArray($secretaries);

        return $secretaries !== FALSE;
    }

}
