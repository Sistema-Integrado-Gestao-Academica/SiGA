<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

// Default for all users
$route['default_controller'] = "login";
$route['404_override'] = '';
$route['conta'] = 'usuario/conta';
$route['logout'] = 'login/logout';

$route['cadastro'] = 'usuario/formulario';
$route['configuracoes'] = 'settings';
$route['cursos'] = 'course/index';
$route['departamentos'] = 'departamento/formulario';
$route['departamentos/(:num)'] = 'departamento/formulario_altera/$1';
$route['discipline'] = 'discipline/discipline_index';
$route['discipline/(:num)'] = 'discipline/formToEditDiscipline/$1';
$route['enrollStudent/(:num)'] = 'course/enrollStudentToCourse/$1';
$route['staffs'] = 'staff/staffsLoadPage';
$route['staffs/(:num)'] = 'staff/editStaff/$1';
$route['funcoes'] = 'funcao/formulario';
$route['funcoes/(:num)'] = 'funcao/formulario_altera/$1';
$route['guest_home'] = 'usuario/guest_index';
$route['planoorcamentario'] = 'budgetplan';
$route['planoorcamentario/(:num)'] = 'budgetplan/edit/$1';
$route['planoorcamentario/(:num)/novadespesa'] = 'expense/index/$1';
$route['registerDoctorateCourse/(:num)'] = 'course/formToCreateDoctorateCourse/$1';
$route['secretaria'] = 'utils/loadSecretaria';
$route['secretary_home'] = 'usuario/secretary_index';
$route['setores'] = 'setor/formulario';
$route['setores/(:num)'] = 'setor/formulario_altera/$1';
$route['updateDoctorateCourse/(:num)'] = 'course/formToUpdateDoctorateCourse/$1';
$route['user_report'] = 'usuario/usersReport';
$route['enrollMastermind/(:num)'] = 'mastermind/enrollMastermindToStudent/$1';
$route['checkMastermind/(:num)'] = 'mastermind/displayMastermindPage/$1';
$route['mastermind_home'] = 'mastermind/index';
$route['coordinator_home'] = 'coordinator/index';
$route['course_report'] = 'coordinator/course_report';
$route['program'] = 'program/index';
$route['program/(:num)'] = 'program/showProgram/$1';
$route['program/others'] = 'program/showOtherPrograms';

/*
 * Secretary functionalities routes
 */
$route['enroll_student'] = "usuario/secretary_enrollStudent";
$route['student_list'] = 'usuario/secretary_coursesStudents';
$route['request_report'] = 'usuario/secretary_requestReport';
$route['offer_list'] = 'usuario/secretary_offerList';
$route['course_syllabus'] = 'usuario/secretary_courseSyllabus';
$route['enroll_mastermind'] = 'usuario/secretary_enrollMasterMinds';
$route['enroll_teacher'] = 'usuario/secretary_enrollTeacher';
$route['documents_report'] = 'documentrequest/documentRequestSecretary';
$route['research_lines'] = 'usuario/secretary_research_lines';

/*
 * Mastermind functionalities routes
 */
$route['mastermind'] = 'mastermind/displayMastermindStudents';
$route['titling_area'] = 'mastermind/titlingArea';

/*
 * Student functionalities routes
 */
$route['student'] = 'usuario/student_index';
$route['student_information'] = 'usuario/studentInformationsForm';
$route['documents_request'] = "documentrequest/index";

/*
 * Test report routes
 * To display the tests results of a class type in the url: ../class_test
 */
$route['user_test'] = 'tests/user_test';
$route['department_test'] = 'tests/department_test';
$route['employee_test'] = 'tests/employee_test';
$route['function_test'] = 'tests/function_test';
$route['login_test'] = 'tests/login_test';
$route['module_test'] = 'tests/module_test';
$route['permission_test'] = 'tests/permission_test';
$route['sector_test'] = 'tests/sector_test';
$route['course_test'] = 'tests/course_test';
$route['classHour_test'] = 'tests/ClassHour_test';
$route['StudentRegistration_test'] = 'tests/StudentRegistration_Test';
$route['selection_process_test'] = 'tests/SelectionProcess_Test';
$route['process_settings_test'] = 'tests/ProcessSettings_Test';
$route['process_phase_test'] = 'tests/ProcessPhase_Test';
// $route['test'] = 'test_report';

/* End of file routes.php */
/* Location: ./application/config/routes.php */