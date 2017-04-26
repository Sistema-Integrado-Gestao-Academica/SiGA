<br>
<br>
<div id="define_teachers_tables" class="row">
    <?php include('define_teachers_tables.php'); ?>;
</div>
<br>
<br>
<div class="col-sm-2 pull-left">
    <button class='btn btn-danger' type="button" id="back_to_define_dates">Voltar</button>
</div>
<div class="col-sm-2 pull-right">
    <?php $saveBtn = 'saveSelectedTeachers('.$processId.')'; ?>
    <button class='btn btn-primary' type="button" onclick=<?=$saveBtn?>>Continuar</button>
</div>

<br>
<br>

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