$(document).ready(function(){
	
	(function($) {

	  saveCandidateGrade = function(teacherId, subscriptionId, phaseprocessId) {
	  	saveGrade(teacherId, subscriptionId, phaseprocessId);
		
	  };
	})(jQuery);
	
});

function saveGrade(teacherId, subscriptionId, phaseprocessId){

	var siteUrl = $("#site_url").val();
	var fieldId = teacherId + "_" + subscriptionId + "_" + phaseprocessId;
	var grade = $("#candidate_grade_" + fieldId).val();
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
		function(responseJson){
			var response = JSON.parse(responseJson);
			var id = "#" + phaseprocessId + "_" + subscriptionId + "_label";
			var id = "#" + phaseprocessId + "_" + subscriptionId + "_label";
			if(response.type === "success" && typeof response.label !== 'undefined'){
				$(id).html("Resultado: " + response.label);
			}
			else if(typeof response.label !== 'undefined'){
				$(id).html("Resultado: " + response.label);
			}
			else{
				$(id).html("Resultado: -");
			}
			var message ="<p class='alert-" + response.type + "'>" + response.message + "</p>";
			$("#status_" + fieldId).html(message);
		}
	);
}