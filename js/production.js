$(document).ready(function(){

	$("#periodic").change(function(){
		getISSNAndQualis();
	});

	$("#identifier").change(function(){
		getPeriodicAndQualis();
	});

});

function getISSNAndQualis(){

	var periodic = $("#periodic").val(); 

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/productionajax/getISSNAndQualis";

	$.post(
		urlToPost,
		{
			periodic: periodic,
		},
		function(data){

			var emptyJsonLength = 3;
        	var periodicInfo = JSON.parse(data);
			if(data.length > emptyJsonLength){
				document.getElementById('identifier').value = periodicInfo.issn;
				document.getElementById('qualis').value = periodicInfo.qualis;
			}
			else{
				document.getElementById('identifier').value = "";
				document.getElementById('qualis').value = "";
			}
		}
	);
}

function getPeriodicAndQualis(){

	var issn = $("#identifier").val(); 

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/productionajax/getPeriodicNameAndQualis";

	$.post(
		urlToPost,
		{
			issn: issn,
		},
		function(data){

			var emptyJsonLength = 3;
        	var periodicInfo = JSON.parse(data);
			if(data.length > emptyJsonLength){
				document.getElementById('periodic').value = periodicInfo.periodic;
				document.getElementById('qualis').value = periodicInfo.qualis;
			}
			else{
				document.getElementById('periodic').value = "";
				document.getElementById('qualis').value = "";
			}
		}
	);
}


	
