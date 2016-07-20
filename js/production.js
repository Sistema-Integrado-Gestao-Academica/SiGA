$(document).ready(function(){

	$("#periodic").change(function(){
		getISSNAndQualis();
	});

	$("#identifier").change(function(){
		getPeriodicAndQualis();
	});


	$('#add_coauthor').click(function() {
		addAuthor();		
	});

	$("#cpf").change(function(){
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


function addAuthor(){

    var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/productionajax/saveAuthor";

	var cpf = $('#cpf').val();
	var name = $('#name').val();
	var productionId = $('#production_id').val();

    $.post(
		urlToPost,
		{
	        cpf: cpf,
	        name: name,
	        production_id: productionId
		},
		function(data){
            $('#alert-msg').html(data);

        	var author = JSON.parse(data);
        	var status = author.status;
            $('#alert-msg').html(author.message);
        	if(status == "success"){	
            	var newRow = $("<tr>");

			    var colCpf = '<td>' + author.cpf + '</td>';
			    var colName = '<td>' + author.name + '</td>';

			    newRow.append(colCpf);
			    newRow.append(colName);
			    $("#authors_table").append(newRow);
			    return false;
			}
		}
	);
}