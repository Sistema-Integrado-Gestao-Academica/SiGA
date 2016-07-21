$(document).ready(function(){

    $("#project_start_date").datepicker($.datepicker.regional["pt-BR"], {
        dateFormat: "dd-mm-yy"
    });

    $("#project_end_date").datepicker($.datepicker.regional["pt-BR"], {
        dateFormat: "dd-mm-yy"
    });

});