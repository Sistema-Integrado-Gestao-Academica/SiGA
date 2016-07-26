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
		getAuthorByCPF();
	});

	$("#name").change(function(){
		getAuthorByName();
	});
	
	$("#author_modal").on('hide.bs.modal', function () {
    	$(this).find('form')[0].reset();
    	$('#alert-msg').empty();
	});

	(function($) {

	  RemoveTableRow = function(handler) {
	    var tr = $(handler).closest('tr');
	    var order = tr.find('td[data-order]').data('order');
	    var productionId = tr.find('td[data-id]').data('id');

	    deleteAuthor(productionId, order, tr);

	    return false;
	  };
	})(jQuery);
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
	var order = $('#order').val();
	var productionId = $('#production_id').val();

    $.post(
		urlToPost,
		{
	        cpf: cpf,
	        name: name,
	        order: order,
	        production_id: productionId
		},
		function(data){
        	var author = JSON.parse(data);
        	var status = author.status;
            $('#alert-msg').html(author.message);
        	if(status == "success"){	

            	var newRow = $("<tr>");

			    var colCpf = '<td data-cpf=' + author.cpf + '>' + author.cpf + '</td>';
			    var colName = '<td data-id=' + author.production_id +'>' + author.name + '</td>';
			    var colOrder = '<td data-order=' + author.order +'>' + author.order + '</td>';
			    var dataToRemove = author.production_id + '/' + author.name;
				var buttons = '<td>';
				buttons += "<a href='" + siteUrl + "/edit_coauthor/" + author.production_id + "/" + author.order + "' class='btn btn-primary'>Editar</a> "
				buttons += "<button onclick='RemoveTableRow(this)' type='button' class='btn btn-danger'>Remover</button>"; // Remove button
				buttons += '</td>';
				
			    newRow.append(colOrder);
			    newRow.append(colCpf);
			    newRow.append(colName);
			    newRow.append(buttons);

			    $("#authors_table").append(newRow);
			    return false;
			}
		}
	);
}

function getAuthorByCPF(){
	
	var cpf = $("#cpf").val(); 

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/productionajax/getAuthorNameByCPF";

	$.post(
		urlToPost,
		{
			cpf: cpf,
		},
		function(data){
        	var author = JSON.parse(data);
			if(author.name != null){
				document.getElementById('name').value = author.name;
			}
		}
	);
}

function getAuthorByName(){
	
	var name = $("#name").val(); 
	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/productionajax/getAuthorCPFByName";

	$.post(
		urlToPost,
		{
			name: name,
		},
		function(data){
        	var author = JSON.parse(data);
			if(author.cpf != null){
				document.getElementById('cpf').value = author.cpf;
			}
		}
	);
}

function deleteAuthor(productionId, order, tr){

	var siteUrl = $("#site_url").val();
	var urlToPost = siteUrl + "/program/ajax/productionajax/deleteAuthor";

	$.post(
		urlToPost,
		{
			production_id: productionId,
			order: order
		},
		function(data){
  			var success = 1;
			if(data == success){
			    tr.fadeOut(400, function(){ 
			      tr.remove(); 
			    }); 
			}
		}
	);
}