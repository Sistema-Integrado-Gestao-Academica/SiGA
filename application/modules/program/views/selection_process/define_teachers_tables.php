<div class="col-md-6">
    <?php
        call_user_func(function() use($teachers, $processTeachers, $processId, $programId){
            include('define_teachers_add_table.php');
        });
    ?>
</div>
<div class="col-md-6">
    <?php
        call_user_func(function() use($processTeachers, $processId, $programId){
            include('define_teachers_remove_table.php');
        });
    ?>
</div>