<h2 class="principal">Configurações do edital <b><i><?= $process->getName(); ?></i></b></h2>

<?php $courseId = $process->getCourse(); 
    $processId = $process->getId();
?>

<div class="alert alert-warning" id="warning_message" style="display: none"></div>
<link rel="stylesheet" href=<?=base_url("css/form_wizard.css")?>>
<div class="wizard">
<div class="wizard-inner">
    <div class="connecting-line"></div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="active">
            <a href='#dates_tab' class='btn btn-tab' data-toggle='tab' id='dates_link'>
                <span class='round-tab'>
                    <i class='fa fa-calendar-o'></i>
                </span>
            </a>
            <h5><center class="tab_description"> Datas </center></h5>
        </li>
        <li class="">
            <a href='#define_teachers_tab' class='btn btn-tab' data-toggle='tab' id='define_teachers_link'>
                <span class='round-tab'>
                    <i class='fa fa-group'></i>
                </span>
            </a>
            <h5><center class="tab_description"> Comissão de Seleção </center></h5>
        </li>
        <li class="">
            <a href='#config_subscription_tab' class='btn btn-tab' data-toggle='tab' id='config_subscription_link'>
                <span class='round-tab'>
                    <i class='fa fa-cogs'></i>
                </span>
            </a>
            <h5><center class="tab_description"> Configurações de inscrição do candidato </center></h5>
        </li>
    </ul>
</div>
</div>

<form role="form">
    <div class="tab-content">
        <div class='tab-pane fade in active' id="dates_tab">
            <?php
                $backButton = "";
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
</form>
