<h3 class="principal">Atualização de informações do processo <i><?= bold($process->getName()) ?></i></h3>

<div class="row">
    <?php $courseId = $process->getCourse(); ?>
    <?= anchor(
        "program/selectiveprocess/courseSelectiveProcesses/{$courseId}",
        "Voltar",
        "class='btn btn-danger pull-right'"
    ); ?>
</div>

<div class="alert alert-warning" id="warning_message" style="display:none;"></div>

<link rel="stylesheet" href=<?=base_url("css/form_wizard.css")?>>
<div class="wizard">
<div class="wizard-inner">
    <div class="connecting-line"></div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="active">
            <a href='#edit_process_tab' class='btn btn-tab' data-toggle='tab' id='edit_process_link'>
                <span class='round-tab'>
                    <i class='fa fa-edit'></i>
                </span>
            </a>
            <h5> <center class="tab_description"> Dados básicos</center></h5>
        </li>
        <li class="">
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
    </div>
    </ul>
</div>

<form role="form">
    <div class="tab-content">
      <div class='tab-pane fade in active' id="edit_process_tab">
            <?php
                call_user_func(function () use ($process, $phasesNames, $phasesWeights, 
                                                $canNotEdit, $phasesGrades){
                    include('edit_process.php');
                });
             ?>
        </div>
        <div class='tab-pane fade' id="dates_tab">
            <?php
                $backButton = "<button class='btn btn-danger' type='button' id='back_to_edit_process'>Voltar</button>";
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
                call_user_func(function() use($process, $allDocs, $processDocs, $course, $courseResearchLines, $btn, $canNotEdit){
                    include(MODULESPATH.'program/views/selection_process_config/subscription_config.php');
                });
            ?>
        </div>
    </div>  
</form>
<br>
<br>
