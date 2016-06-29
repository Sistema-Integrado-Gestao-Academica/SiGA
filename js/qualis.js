$(document).ready(function(){

    $("#upload_qualis_btn").click(function(event){

        var fileName = $("#qualis_file").val();

        if(fileName){
            var loadingBtn = "<i class='fa fa-spinner fa-spin fa-fw'></i> Importando dados...";
            $("#upload_qualis_btn").html(loadingBtn);
        }
    });

});