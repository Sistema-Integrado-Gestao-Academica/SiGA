$(document).ready(function(){
	
	(function($) {

	  saveCandidateGrade = function(teacherId, subscriptionId, phaseprocessId) {
	  	saveGrade(teacherId, subscriptionId, phaseprocessId);
		
	  };
	})(jQuery);
	
});

function saveGrade(teacherId, subscriptionId, phaseprocessId){

	var siteUrl = $("#site_url").val();
	var grade = $("#candidate_grade_" + teacherId + "_" + subscriptionId + "_" + phaseprocessId).val();
	var data = {
		grade: grade,
		teacherId: teacherId,
		subscriptionId: subscriptionId,
		phaseprocessId: phaseprocessId
	}

	var urlToPost = siteUrl + "/program/selectiveprocessevaluation/saveCandidateGrade";
	$.post(
		urlToPost,
		data,
		function(response){
			$(save_candidate_grade_status).html(response);
		}
	);
}