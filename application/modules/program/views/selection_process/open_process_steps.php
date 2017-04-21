
<div class="row">
    <ul class="nav nav-tabs nav-justified">
        <li class="active">
            <?=anchor(
                "#basic_data_tab",
                "<b><i class='fa fa-file'></i> Dados básicos</b>",
                "class='btn btn-tab' data-toggle='tab'")
            ?>
        </li>
        <li class="">
            <?=anchor(
                "#dates_tab",
                "<b><i class='fa fa-calendar-o'></i> Datas</b>",
                "class='btn btn-tab' data-toggle='tab'")
            ?>
        </li>
        <li class="">
            <?=anchor(
                "#define_teachers_tab",
                "<b><i class='fa fa-group'></i> Comissão de Seleção</b>",
                "class='btn btn-tab' data-toggle='tab'")
            ?>
        </li>
        <li class="">
            <?=anchor(
                "#informations_tab",
                "<b><i class='fa fa-info'></i> Informações </b>",
                "class='btn btn-tab' data-toggle='tab'")
            ?>
        </li>
    </ul>

    <div class="tab-content">
        <div class='tab-pane fade in active' id="basic_data_tab">
            <?php
                call_user_func(function () use ($course, $phases){
                    include('new.php');
                });
             ?>
        </div>
        <div class='tab-pane fade' id="dates_tab">
        </div>
    </div>
</div>