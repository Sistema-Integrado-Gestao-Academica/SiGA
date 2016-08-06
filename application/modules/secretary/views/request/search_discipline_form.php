<?php
    $disciplineSearch = array(
        "name" => "discipline_name_search",
        "id" => "discipline_name_search",
        "type" => "text",
        "class" => "form-campo",
        "class" => "form-control"
    );

    $courseHidden = array(
        "id" => "courseId",
        "name" => "courseId",
        "type" => "hidden",
        "value" => $courseId
    );

    $userHidden = array(
        "id" => "userId",
        "name" => "userId",
        "type" => "hidden",
        "value" => $userId
    );
?>

<h3><i class='fa fa-search-plus'> </i> Adicionar disciplinas</h3>
<br>

<div class='row'>
    <div class='col-md-6 col-sm-6'>
        <div class='input-group input-group-sm'>
            <?= form_label("Nome da disciplina", "discipline_name_search");?>
            <?= form_input($disciplineSearch) ?>
            <?= form_input($courseHidden);?>
            <?= form_input($userHidden);?>
        </div>
    </div>
</div>

<br>
<div id='discipline_search_result'>