<h2 class="principal">Configurações do edital <b><i><?= $process->getName(); ?></i></b></h2>

<?php $courseId = $process->getCourse(); 
    $processId = $process->getId();
?>

<div class="alert alert-warning" id="warning_message" style="display: none"></div>
<div class="row">
    <ul class="nav nav-tabs nav-justified">
        <li class="active">
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
                "class='btn btn-tab disabled' data-toggle='tab' id='define_teachers_link'")
            ?>
        </li>
        <li class="">
            <?=anchor(
                "#config_subscription_tab",
                "<b><i class='fa fa-cogs'></i> Configurações de inscrição do candidato </b>",
                "class='btn btn-tab disabled' data-toggle='tab' id='config_subscription_link'")
            ?>
        </li>
    </ul>

    <div class="tab-content" id="config_tabs">
        <div class='tab-pane fade in active' id="dates_tab">
            <?php
                $backButton = anchor(
                    "program/selectiveprocess/courseSelectiveProcesses/{$courseId}",
                    "Voltar",
                    "class='btn btn-danger'"
                ); 
                call_user_func(function() use($process, $phasesIds, $backButton){
                    include(MODULESPATH.'program/views/selection_process/define_dates.php');
                });
            ?>
        </div>
        <div class='tab-pane fade' id="define_teachers_tab">
            <?php
                call_user_func(function() use($teachers, $processTeachers, $processId, $programId, $courseId){
                    include(MODULESPATH.'program/views/selection_process/define_teachers.php');
                });
            ?>
        </div>
         <div class='tab-pane fade' id="config_subscription_tab">
            <?php
                $btn = "abertura";
                call_user_func(function() use($process, $allDocs, $processDocs, $course, $courseResearchLines, $btn){
                    include('subscription_config.php');
                });
            ?>
        </div>
    </div>
</div>