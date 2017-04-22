<h2 class="principal">Atualização de informações do processo <i><?= bold($process->getName()) ?></i></h2>

<div class="row">
    <?php $courseId = $process->getCourse(); ?>
    <?= anchor(
        "program/selectiveprocess/courseSelectiveProcesses/{$courseId}",
        "Voltar",
        "class='btn btn-danger btn-lg pull-right'"
    ); ?>
</div>
<br>

<div class="alert alert-warning" id="warning_message" style="display:none;"></div>
<div class="row">
    <ul class="nav nav-tabs nav-justified">
        <li class="active">
            <?=anchor(
                "#edit_process_tab",
                "<b><i class='fa fa-edit'></i> Editar dados do processo</b>",
                "class='btn btn-tab' data-toggle='tab' id='edit_process_link'")
            ?>
        </li>
        <li class="">
            <?=anchor(
                "#dates_tab",
                "<b><i class='fa fa-calendar-o'></i> Datas</b>",
                "class='btn btn-tab' data-toggle='tab' id='dates_link'")
            ?>
        </li>
        <li class="">
            <?=anchor(
                "#define_teachers_tab",
                "<b><i class='fa fa-group'></i> Comissão de Seleção</b>",
                "class='btn btn-tab' data-toggle='tab' id='define_teachers_link'")
            ?>
        </li>
        <li class="">
            <?=anchor(
                "#config_subscription_tab",
                "<b><i class='fa fa-cogs'></i> Configurações de inscrição do candidato </b>",
                "class='btn btn-tab' data-toggle='tab' id='config_subscription_link'")
            ?>
        </li>
    </ul>

    <div class="tab-content">
        <div class='tab-pane fade in active' id="edit_process_tab">
            <?php
                call_user_func(function () use ($process, $phasesNames, $phasesWeights, 
                                                $noticeFileName, $divulgation, $phasesGrades){
                    include('edit_process.php');
                });
             ?>
        </div>
        <div class='tab-pane fade' id="dates_tab">
            <?php
                $backButton = "<button class='btn btn-danger' id='back_to_edit_process'>Voltar</button>";
                call_user_func(function() use($process, $phasesIds, $backButton){
                    include('define_dates.php');
                });
            ?>
        </div>
        <div class='tab-pane fade' id="define_teachers_tab">
            <?php
                call_user_func(function() use($teachers, $processTeachers, $processId, $programId, $courseId){
                    include('define_teachers.php');
                });
            ?>
        </div>
         <div class='tab-pane fade' id="config_subscription_tab">
            <?php
                $btn = "edição";
                call_user_func(function() use($process, $allDocs, $processDocs, $course, $courseResearchLines, $btn){
                    include(MODULESPATH.'program/views/selection_process_config/subscription_config.php');
                });
            ?>
        </div>
    </div>
</div>

<br>
<br>