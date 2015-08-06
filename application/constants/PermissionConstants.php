<?php

require_once('constants.php');

class PermissionConstants extends Constants{

	// Secretary functionalities permissions
	const ENROLL_STUDENT_PERMISSION = "enroll_student";
	const STUDENT_LIST_PERMISSION = "student_list";
	const REQUEST_REPORT_PERMISSION = "request_report";
	const OFFER_LIST_PERMISSION = "offer_list";
	const COURSE_SYLLABUS_PERMISSION = "course_syllabus";
	const DEFINE_MASTERMIND_PERMISSION = "enroll_mastermind";
	const ENROLL_TEACHER_PERMISSION = "enroll_teacher";
	const DOCUMENT_REQUEST_REPORT_PERMISSION = "documents_report";

	// Student functionalities permissions
	const DOCUMENT_REQUEST_PERMISSION = "documents_request";

	const MASTERMIND_PERMISSION = "mastermind";

}