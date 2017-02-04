<h2 class="principal">Vincular docentes ao processo seletivo </h2>

<div id="define_teachers_tables" class="row">
    <?php include('define_teachers_tables.php'); ?>;
</div>

<style type="text/css">
    #add_teachers_to_process_table, #teachers_added_to_process_table {
        height: 400px;
        overflow-y: auto;
    }
</style>

<script>
    function addTeacherToProcess(event, processId, teacherId, programId){
        event.preventDefault();

        var siteUrl = $("#site_url").val();
        var urlToPost = siteUrl + "/selection_process/define_teacher"
        $.post(
            urlToPost,
            {
                processId: processId,
                teacherId: teacherId,
                programId: programId
            },
            function(data){
                $("#define_teachers_tables").html(data);
            }
        );
    }

    function removeTeacherFromProcess(event, processId, teacherId, programId){
        event.preventDefault();

        var siteUrl = $("#site_url").val();
        var urlToPost = siteUrl + "/selection_process/remove_teacher"
        $.post(
            urlToPost,
            {
                processId: processId,
                teacherId: teacherId,
                programId: programId
            },
            function(data){
                $("#define_teachers_tables").html(data);
            }
        );
    }
</script>