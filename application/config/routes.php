<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['conta'] = 'auth/userController/conta';
$route['profile'] = 'auth/userController/profile';
$route['logout'] = 'auth/login/logout';
$route['register'] = 'auth/userController/register';
$route['register_user'] = 'auth/userController/newUser';
$route['confirm_register'] = 'useractivation/confirm';
$route['resent_confirmation_email/(:num)'] = 'useractivation/resentEmail/$1/';
$route['configuracoes'] = 'settings';
$route['cursos'] = 'program/course/index';
$route['departamentos'] = 'departamento/formulario';
$route['departamentos/(:num)'] = 'departamento/formulario_altera/$1';
$route['discipline'] = 'program/discipline/discipline_index';
$route['discipline/(:num)'] = 'program/discipline/formToEditDiscipline/$1';
$route['enrollStudent/(:num)'] = 'enrollment/enrollStudentToCourse/$1';
$route['staffs'] = 'staff/staffsLoadPage';
$route['staffs/(:num)'] = 'staff/editStaff/$1';
$route['funcoes'] = 'funcao/formulario';
$route['funcoes/(:num)'] = 'funcao/formulario_altera/$1';
$route['guest_home'] = 'secretary/secretary/guest_index';
$route['planoorcamentario'] = 'budgetplan';
$route['planoorcamentario/(:num)'] = 'budgetplan/edit/$1';
$route['planoorcamentario/(:num)/novadespesa'] = 'expense/index/$1';
$route['secretaria'] = 'utils/loadSecretaria';
$route['secretary_home'] = 'secretary/secretary/index';
$route['setores'] = 'setor/formulario';
$route['setores/(:num)'] = 'setor/formulario_altera/$1';
$route['updateDoctorateCourse/(:num)'] = 'course/formToUpdateDoctorateCourse/$1';
$route['user_report'] = 'auth/userController/usersReport';
$route['enrollMastermind/(:num)'] = 'mastermind/enrollMastermindToStudent/$1';
$route['checkMastermind/(:num)'] = 'mastermind/displayMastermindPage/$1';
$route['mastermind_home'] = 'mastermind/index';
$route['coordinator_home'] = 'coordinator/index';
$route['course_report'] = 'coordinator/course_report';
$route['program'] = 'program/index';
$route['research_lines'] = 'program/course/research_lines';

/*
 * Secretary functionalities routes
 */
$route['enroll_student'] = "secretary/secretary/enrollStudent";
$route['student_list'] = 'secretary/secretary/coursesStudents';
$route['request_report'] = 'secretary/secretary/requestReport';
$route['offer_list'] = 'secretary/secretary/offerList';
$route['course_syllabus'] = 'secretary/secretary/courseSyllabus';
$route['enroll_mastermind'] = 'secretary/secretary/enrollMasterMinds';
$route['enroll_teacher'] = 'secretary/secretary/enrollTeacher';
$route['documents_report'] = 'documentrequest/documentRequestSecretary';
$route['secretary_programs'] = 'secretary/secretary/secretaryPrograms';

/*
 * Mastermind functionalities routes
 */
$route['mastermind'] = 'mastermind/displayMastermindStudents';
$route['titling_area'] = 'mastermind/titlingArea';
$route['update_profile'] = 'teacher/updateProfile';

/*
 * Student functionalities routes
 */
$route['student'] = 'student/index';
$route['student_information'] = 'student/studentInformation';
$route['documents_request'] = "documentrequest/index";

/*
 * Selection Process functionalities routes
 */
$route['selection_process'] = 'selectiveprocess/index';

/*
 * Test report routes
 * To display the tests results of a class type in the url: ../class_test
 */
$route['user_test'] = 'tests/user_test';
$route['department_test'] = 'tests/department_test';
$route['employee_test'] = 'tests/employee_test';
$route['function_test'] = 'tests/function_test';
$route['login_test'] = 'tests/login_test';
$route['sector_test'] = 'tests/sector_test';
$route['course_test'] = 'tests/course_test';
$route['classHour_test'] = 'tests/ClassHour_test';
$route['StudentRegistration_test'] = 'tests/StudentRegistration_Test';

$route['selection_process_test'] = 'tests/SelectionProcess_Test';
$route['process_settings_test'] = 'tests/ProcessSettings_Test';
$route['process_phase_test'] = 'tests/ProcessPhase_Test';
$route['email_notification_test'] = 'tests/EmailNotification_Test';
$route['restore_password_email_test'] = 'tests/RestorePasswordEmail_Test';
$route['enrolled_student_email_test'] = 'tests/EnrolledStudentEmail_Test';
$route['secretary_email_notification_test'] = 'tests/SecretaryEmailNotification_Test';
$route['phone_test'] = 'tests/Phone_Test';
$route['bar_notification_test'] = 'tests/BarNotification_Test';

$route['permission_test'] = 'tests/Permission_test';
$route['group_test'] = 'tests/Group_test';