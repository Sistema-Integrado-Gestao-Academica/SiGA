<?php

require_once('constants.php');

class EnrollmentConstants extends Constants{
	
	const MIN_VACANCY_QUANTITY_TO_ENROLL = 1;
	
	const PRE_ENROLLED_STATUS = "pre_enrolled";
	const ENROLLED_STATUS = "enrolled";
	const NO_VACANCY_STATUS = "no_vacancy";

	// Request status
	const REQUEST_INCOMPLETE_STATUS = "incomplete";
	const REQUEST_ALL_APPROVED_STATUS = "all_approved";

}