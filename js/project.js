$(document).ready(function(){

    $("#project_start_date").datepicker($.datepicker.regional["pt-BR"], {
        dateFormat: "dd-mm-yy"
    });

    $("#project_end_date").datepicker($.datepicker.regional["pt-BR"], {
        dateFormat: "dd-mm-yy"
    });

    $("#member_name").on('input', function(){
        searchMembers();
    });

});

function searchMembers(){
    var member = $("#member_name").val();
    var projectId = $("#project_id").val();
    var siteUrl = $("#site_url").val();

    var urlToPost = siteUrl + "/program/ajax/projectajax/searchMember";

    $.post(
        urlToPost,
        {member: member, project: projectId},
        function(data){
            $("#member_search_result").html(data);
        }
    );
}