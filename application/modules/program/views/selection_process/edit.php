<h2 class="principal">Atualização de informações do processo <i><?= bold($selectiveprocess->getName()) ?></i></h2>

<div class="row">
    <?= anchor(
        "program/selectiveprocess/courseSelectiveProcesses/{$courseId}",
        "Voltar",
        "class='btn btn-danger btn-lg pull-right'"
    ); ?>
</div>
<br>

<div class="row">
    <ul class="nav nav-tabs nav-justified">
        <li class="active">
            <?=anchor(
                "#edit_process_tab",
                "<b><i class='fa fa-edit'></i> Editar dados do processo</b>",
                "class='btn btn-tab' data-toggle='tab'")
            ?>
        </li>
        <li class="">
            <?=anchor(
                "#define_teachers_tab",
                "<b><i class='fa fa-group'></i> Vincular docentes</b>",
                "class='btn btn-tab' data-toggle='tab'")
            ?>
        </li>
    </ul>

    <div class="tab-content">
        <div class='tab-pane fade in active' id="edit_process_tab">
            <?php
                call_user_func(function () use ($selectiveprocess, $courseId, $phasesNames,
                                                $phasesWeights, $noticeFileName, $divulgation){
                    include('edit_process.php');
                });
             ?>
        </div>
        <div class='tab-pane fade' id="define_teachers_tab">
            <?php
                call_user_func(function() use($teachers, $processTeachers, $processId, $programId){
                    include('define_teachers.php');
                });
            ?>
        </div>
    </div>
</div>

<br>
<br>
<div class="row">
<?= anchor(
    "program/selectiveprocess/courseSelectiveProcesses/{$courseId}",
    "Voltar",
    "class='btn btn-danger btn-lg btn-block'"
); ?>
</div>