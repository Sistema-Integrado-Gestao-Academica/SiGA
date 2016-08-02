<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Form_validation Class
 *
 * Extends Form_Validation library
 *
 */

require_once(APPPATH."data_types/basic/StartEndDate.php");
require_once(APPPATH."exception/DateException.php");

class MY_Form_validation extends CI_Form_validation {

	function __construct()
	{
	    parent::__construct();
	}

	// --------------------------------------------------------------------

    function valid_date_interval(){

        $CI =& get_instance();

        $startDate = $CI->input->post('project_start_date');
        $endDate = $CI->input->post('project_end_date');

        try{
            $dates = new StartEndDate($startDate, $endDate);
            $validDate = TRUE;
        }catch(DateException $e){
            $CI->form_validation->set_message('valid_date_interval', $e->getMessage());
            $validDate = FALSE;
        }

        return $validDate;
    }

    function valid_name($str){

        $CI =& get_instance();

        $CI->form_validation->set_message('valid_name', 'A {field} deve conter apenas caracteres alfabéticos.');

        return ( ! preg_match("/^[a-zA-ZáéíóúàâêôãõüçÁÉÍÓÚÀÂÊÔÃÕÜÇ ]*$/i", $str)) ? FALSE : TRUE;
    }

	/**
     *
     * valid_cpf
     *
     * Verify if the inserted CPF is valid
     * @access	public
     * @param	string
     * @return	bool
     */
    function valid_cpf($cpf)
    {
        $CI =& get_instance();

        $CI->form_validation->set_message('valid_cpf', 'O {field} informado não é válido.');

        $cpf = preg_replace('/[^0-9]/','',$cpf);

        if(strlen($cpf) != 11 || preg_match('/^([0-9])\1+$/', $cpf))
        {
            return false;
        }

        // 9 primeiros digitos do cpf
        $digit = substr($cpf, 0, 9);

        // calculo dos 2 digitos verificadores
        for($j=10; $j <= 11; $j++)
        {
            $sum = 0;
            for($i=0; $i< $j-1; $i++)
            {
                $sum += ($j-$i) * ((int) $digit[$i]);
            }

            $summod11 = $sum % 11;
            $digit[$j-1] = $summod11 < 2 ? 0 : 11 - $summod11;
        }

        return $digit[9] == ((int)$cpf[9]) && $digit[10] == ((int)$cpf[10]);
    }

    function valid_multiple_emails($emails){

        $CI =& get_instance();

        $CI->form_validation->set_message('valid_multiple_emails', 'Os emails informados não são válidos. Verifique os emails digitados.');
        $validEmail = TRUE;

        $emails = explode(";", $emails);
        foreach ($emails as $email) {
            $validEmail = $this->valid_email($email);
            if(!$validEmail){
                $validEmail = FALSE;
                break;
            }
        }

        return $validEmail;
    }


    function valid_order_coauthor($order){

        $CI =& get_instance();

        $CI->form_validation->set_message('valid_order_coauthor', 'O {field} deve ser maior ou igual a 2.');

        if($order >= 2){
            $valid = TRUE;
        }
        else{
            $valid = FALSE;
        }

        return $valid;
    }

    function verify_if_cpf_no_exists($cpf){

        $CI =& get_instance();

        $CI->form_validation->set_message('verify_if_cpf_no_exists', 'Já existe um usuário com o {field} informado.');

        $CI->db->select('cpf');
        $foundUsers = $CI->db->get('users')->result_array();

        $foundUsers = checkArray($foundUsers);

        $cpfNoExists = TRUE;

        if($foundUsers !== FALSE){

            foreach ($foundUsers as $user) {

                $userCpf = $user['cpf'];
                if($userCpf == $cpf){
                    $cpfNoExists = FALSE;
                    break;
                }

            }
        }

        return $cpfNoExists;

    }

    function verify_if_login_no_exists($login){

        $CI =& get_instance();

        $CI->form_validation->set_message('verify_if_login_no_exists', 'Já existe um usuário com o {field} informado.');

        $CI->db->select('login');
        $foundUsers = $CI->db->get('users')->result_array();

        $foundUsers = checkArray($foundUsers);

        $loginNoExists = TRUE;

        if($foundUsers !== FALSE){

            foreach ($foundUsers as $user) {

                $userLogin = $user['login'];
                if($userLogin == $login){
                    $loginNoExists = FALSE;
                    break;
                }

            }
        }

        return $loginNoExists;
    }

    function verify_if_email_no_exists($email){

        $CI =& get_instance();

        $CI->form_validation->set_message('verify_if_email_no_exists', 'Já existe um usuário com o {field} informado.');

        $CI->db->select('email');
        $foundUsers = $CI->db->get('users')->result_array();

        $foundUsers = checkArray($foundUsers);

        $emailNoExists = TRUE;

        if($foundUsers !== FALSE){


            foreach ($foundUsers as $user) {

                $userEmail = $user['email'];
                if($userEmail == $email){
                    $emailNoExists = FALSE;
                    break;
                }

            }
        }

        return $emailNoExists;
    }

    function verify_if_code_no_exists($code){

        $CI =& get_instance();

        $CI->form_validation->set_message('verify_if_code_no_exists', 'Já existe uma natureza de despesa com o {field} informado.');

        $CI->db->select('code');
        $expenseTypes = $CI->db->get('expense_type')->result_array();

        $expenseTypes = checkArray($expenseTypes);

        $codeNoExists = TRUE;

        if($expenseTypes !== FALSE){

            foreach ($expenseTypes as $expenseType) {

                if($expenseType['code'] == $code){
                    $codeNoExists = FALSE;
                    break;
                }

            }
        }

        return $codeNoExists;
    }
}