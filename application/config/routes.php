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
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|   $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|       my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['migrate'] = 'utils/migrate';
$route['no_route'] = 'home';

/*
 * Administrative routes
 */
$route['director_home'] = 'administrative/director/index';
$route['define_director'] = 'administrative/director/defineDirector';
$route['save_director'] = 'administrative/director/saveDirector';
$route['production_report_director'] = 'administrative/director/productionReports';
$route['evaluation_report_director'] = 'administrative/director/evaluationsReports';
$route['productions_fill_report_direct'] = 'administrative/director/productionFillReport';

/*
 * Authentication routes
 */
$route['conta'] = 'auth/userController/conta';
$route['profile'] = 'auth/userController/profile';
$route['update_user_profile'] = 'auth/userController/updateProfile';
$route['logout'] = 'auth/login/logout';
$route['register'] = 'auth/userController/register';
$route['register_user'] = 'auth/userController/newUser';
$route['confirm_register'] = 'auth/useractivation/confirm';
$route['resent_confirmation_email/(:num)'] = 'auth/useractivation/resentEmail/$1/';
$route['reconfirm_register/(:num)'] = 'auth/useractivation/reconfirmRegister/$1/';
$route['cancel_register/(:num)'] = 'auth/useractivation/cancelRegister/$1/';
$route['user_report'] = 'auth/userController/usersReport';
$route['guest_home'] = 'auth/userController/guest_index';

// useless
$route['departamentos'] = 'departamento/formulario';
$route['departamentos/(:num)'] = 'departamento/formulario_altera/$1';
$route['funcoes'] = 'funcao/formulario';
$route['funcoes/(:num)'] = 'funcao/formulario_altera/$1';
$route['setores'] = 'setor/formulario';
$route['setores/(:num)'] = 'setor/formulario_altera/$1';

$route['configuracoes'] = 'program/settings';
$route['cursos'] = 'program/course/index';
$route['discipline'] = 'program/discipline/discipline_index';
$route['discipline/(:num)'] = 'program/discipline/formToEditDiscipline/$1';
$route['make_discipline_restrict/(:num)'] = 'program/discipline/makeRestrict/$1';

$route['staffs'] = 'program/staff/staffsLoadPage';
$route['staffs/(:num)'] = 'program/staff/editStaff/$1';
$route['enrollMastermind/(:num)'] = 'program/mastermind/enrollMastermindToStudent/$1';
$route['checkMastermind/(:num)'] = 'program/mastermind/displayMastermindPage/$1';
$route['mastermind_home'] = 'program/mastermind/index';
$route['coordinator_home'] = 'program/coordinator/index';
$route['course_report'] = 'program/coordinator/course_report';
$route['program'] = 'program/index';
$route['research_lines'] = 'program/course/research_lines';


/*
 * Budgetplan routes
 */
$route['budgetplan_expenses/(:num)'] = 'finantial/budgetplan/budgetplanExpenses/$1';
$route['budgetplan'] = 'finantial/budgetplan';
$route['budgetplan/(:num)'] = 'finantial/budgetplan/edit/$1';
$route['budgetplan/new_expense/(:num)'] = 'finantial/expense/index/$1';

/*
 * Expense routes
 */
$route['delete_expense'] = 'finantial/expense/delete';
$route['save_expense'] = 'finantial/expense/save';
$route['expense_nature'] = 'finantial/expense/expensesNature';
$route['update_status_expense_nature/(:num)'] = 'finantial/expense/updateStatusExpenseNature/$1';
$route['edit_expense_nature/(:num)'] = 'finantial/expense/editExpenseNature/$1';
$route['update_expense_nature/(:num)'] = 'finantial/expense/updateExpenseNature/$1';
$route['new_expense_nature'] = 'finantial/expense/newExpenseNature';
$route['create_expense_type'] = 'finantial/expense/createExpenseNature';
$route['expense_details/(:num)'] = 'finantial/expense/expenseDetails/$1';
$route['save_expense_detail'] = 'finantial/expense/saveExpenseDetail';
$route['edit_expense_detail/(:num)'] = 'finantial/expense/editExpenseDetails/$1';
$route['update_expense_detail/(:num)'] = 'finantial/expense/updateExpenseDetails/$1';
/*
 * Payment routes
 */
$route['expense_payments/(:num)/(:num)'] = 'finantial/payment/expensePayments/$1/$2';
$route['new_payment/(:num)/(:num)'] = 'finantial/payment/newPayment/$1/$2';
$route['register_payment'] = 'finantial/payment/registerPayment';
$route['register_repayment'] = 'finantial/payment/registerRepayment';
$route['repayment/(:num)/(:num)/(:num)'] = 'finantial/payment/repayment/$1/$2/$3';
$route['generate_spreadsheet/(:num)'] = 'finantial/payment/generateSpreadsheet/$1';

/*
 * Secretary functionalities routes
 */
$route['enrollStudent/(:num)'] = 'secretary/enrollment/enrollStudentToCourse/$1';
$route['secretaria'] = 'utils/loadSecretaria';
$route['secretary_home'] = 'secretary/secretary/index';
$route['enroll_student'] = "secretary/secretary/enrollStudent";
$route['student_list'] = 'secretary/secretary/coursesStudents';
$route['request_report'] = 'secretary/secretary/requestReport';
$route['course_syllabus'] = 'secretary/syllabus/secretaryCourseSyllabus';
$route['enroll_mastermind'] = 'secretary/secretary/enrollMasterMinds';
$route['enroll_teacher'] = 'secretary/secretary/enrollTeacher';
$route['documents_report'] = 'secretary/documentrequest/documentRequestSecretary';
$route['secretary_programs'] = 'secretary/secretary/secretaryPrograms';
$route['student_list_actions/(:num)/(:num)'] = 'secretary/enrollment/studentActions/$1/$2';

// Offer routes
$route['offer_list'] = 'secretary/offer/offerList';
$route['save_enrollment_period'] = 'secretary/offer/saveEnrollmentPeriod';
$route['enrollment_report'] = 'secretary/enrollment/showEnrollmentReport';

// Request routes
$route['update_enroll_request/(:num)'] = 'secretary/request/updateRequest/$1';
$route['add_discipline_to_request/(:num)/(:num)'] = 'secretary/request/addDisciplineToRequest/$1/$2';
$route['remove_from_request/(:num)/(:num)'] = 'secretary/request/removeDisciplineFromRequest/$1/$2';
$route['resend_request/(:num)'] = 'secretary/request/studentResendRequest/$1';

// Qualis routes
$route['import_qualis'] = 'program/importQualis/index';
$route['upload_qualis'] = 'program/importQualis/upload';

// User Invitation routes
$route['invite_user'] = 'secretary/userInvitation/index';
$route['invite'] = 'secretary/userInvitation/invite';
$route['invitation_register'] = 'secretary/userInvitation/register';
$route['join_group_invitation'] = 'secretary/userInvitation/joinGroup';

// Document requests routes
$route['secretary_doc_requests/(:num)'] = 'secretary/documentrequest/documentRequestReport/$1';
$route['provide_doc_online'] = 'secretary/documentrequest/provideOnline';
$route['download_doc/(:num)'] = 'secretary/documentrequest/downloadDoc/$1';

/*
 * Mastermind functionalities routes
 */
$route['mastermind'] = 'program/mastermind/displayMastermindStudents';
$route['titling_area'] = 'program/mastermind/titlingArea';
$route['update_profile'] = 'program/teacher/updateProfile';

/*
 * Student functionalities routes
 */
$route['student'] = 'student/student/index';
$route['student_information'] = 'student/student/studentInformation';
$route['documents_request'] = "student/documentrequestStudent/index";

/*
 * Selection Process functionalities routes
 */
$route['selection_process'] = 'program/selectiveprocess/index';
$route['edit_selection_process/(:num)/(:num)'] = 'program/selectiveprocess/edit/$1/$2';
$route['update_selection_process'] = 'program/selectiveprocess/updateSelectionProcess';
$route['download_notice/(:num)/(:num)'] = 'program/selectiveprocess/downloadNotice/$1/$2';
$route['define_dates_page/(:num)/(:num)'] = 'program/selectiveprocess/loadDefineDatesPage/$1/$2';
$route['define_dates/(:num)/(:num)'] = 'program/selectiveprocess/defineDates/$1/$2';
$route['selection_process/define_teacher'] = 'program/selectiveprocess/addTeacherToProcess';
$route['selection_process/remove_teacher'] = 'program/selectiveprocess/removeTeacherFromProcess';
$route['selection_process/divulgations/(:num)'] = 'program/selectiveprocess/divulgations/$1';
$route['selection_process/download_divulgation_file/(:num)'] = 'program/selectiveprocess/downloadDivulgationFile/$1';
$route['selection_process/guest/(:num)'] = 'program/selectiveprocess/showTimeline/$1';
$route['selection_process/config/(:num)'] = 'program/selectiveprocessconfig/index/$1';


/*
 * Enrollment routes
 */
$route['enroll_student/(:num)/(:num)'] = 'secretary/enrollment/enrollStudent/$1/$2';

/**
 * Project routes
 */
$route['academic_projects'] = 'program/project/index';
$route['new_project'] = 'program/project/newProject';
$route['project_team/(:num)'] = 'program/project/projectTeam/$1';
$route['add_to_team'] = 'program/project/addMemberToTeam';
$route['make_project_coordinator'] = 'program/project/makeCoordinator';
$route['accept_coordinator_invitation'] = 'program/project/acceptCoordinatorInvitation';

/**
 * Production routes
 */
$route['intellectual_production'] = 'program/production/index';
$route['save_production'] = 'program/production/save';
$route['edit_production/(:num)'] = 'program/production/edit/$1';
$route['update_production'] = 'program/production/update';
$route['delete_production'] = 'program/production/delete';
$route['edit_coauthors/(:num)'] = 'program/production/editCoauthors/$1';
$route['edit_coauthor/(:num)/(:num)'] = 'program/production/editCoauthor/$1/$2';
$route['update_coauthor/(:num)/(:num)'] = 'program/production/updateCoauthor/$1/$2';
$route['save_event_participation'] = 'program/production/saveEventParticipation';
$route['save_event_presentation'] = 'program/production/saveEventPresentation';
$route['edit_event_participation/(:num)'] = 'program/production/editEventParticipation/$1';
$route['edit_event_presentation/(:num)'] = 'program/production/editEventPresentation/$1';
$route['update_event_participation'] = 'program/production/updateEventParticipation';
$route['update_event_presentation'] = 'program/production/updateEventPresentation';
$route['delete_event_participation'] = 'program/production/deleteEventParticipation';
$route['delete_event_presentation'] = 'program/production/deleteEventPresentation';

/**
 * User comunication routes
 */
$route['notify_users'] = 'notification/userNotification/index';
$route['notify_specific_user'] = 'notification/userNotification/notifySpecificUser';
$route['notify_group_of_users'] = 'notification/userNotification/notifyGroupOfUsers';

/**
 * Coordinator routes
*/
$route['production_report'] = 'program/productionManagement/index';
$route['evaluation_report'] = 'program/coordinator/evaluationsReports';
$route['productions_fill_report'] = 'program/productionManagement/productionFillReport';
$route['print_fill_report'] = 'program/productionManagement/printFillReport';


$route['download_file/(:num)'] = 'program/program/downloadInfoFile/$1';

/*
 * Test report routes
 * To display the tests results of a class type in the url: ../class_test
 */
/* Auth tests */
$route['user_test'] = 'test/auth/User_test';
$route['login_test'] = 'test/auth/Login_test';
$route['permission_test'] = 'test/auth/Permission_test';
$route['group_test'] = 'test/auth/Group_test';
$route['module_test'] = 'test/auth/Module_test';

/* Program tests */
$route['department_test'] = 'test/program/department_test';
$route['employee_test'] = 'test/program/employee_test';
$route['function_test'] = 'test/program/function_test';
$route['sector_test'] = 'test/program/sector_test';
$route['course_test'] = 'test/program/course_test';
$route['selection_process_test'] = 'test/program/SelectionProcess_Test';
$route['process_settings_test'] = 'test/program/ProcessSettings_Test';
$route['process_phase_test'] = 'test/program/ProcessPhase_Test';
$route['intellectual_production_test'] = 'test/program/IntellectualProduction_Test';

/* Notification tests */
$route['email_notification_test'] = 'test/notification/EmailNotification_Test';
$route['restore_password_email_test'] = 'test/notification/RestorePasswordEmail_Test';
$route['enrolled_student_email_test'] = 'test/notification/EnrolledStudentEmail_Test';
$route['secretary_email_notification_test'] = 'test/notification/SecretaryEmailNotification_Test';
$route['bar_notification_test'] = 'test/notification/BarNotification_Test';

/* Student tests */
$route['student_registration_test'] = 'test/student/StudentRegistration_Test';
$route['phone_test'] = 'test/student/Phone_Test';

/* Secretary tests */
$route['classHour_test'] = 'test/secretary/ClassHour_test';
