<h2 class="principal">Processo Seletivo <b><i><?=$processName?></i></b> </h2>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Resultado da fase de <b><?=$phaseName?></b></h3>
    </div>
    <div class="box-body">
        <ul class="list-unstyled">
        	<h4><center> Candidatos aprovados </center></h4>
        	<?php
        	foreach ($candidates->candidates as $candidateId) {
    			echo "<h4><li><center><b>{$candidateId}</b></center></li></h4>";
        	}?>
        </ul>
    </div><!-- /.box-body -->
    <div class="box-footer no-print">
        <button id= "print_report_btn" class= "btn btn-primary btn-flat" type= "submit"><i class='fa fa-print'></i> Imprimir Resultado</button>
        <?= anchor(
        "selection_process/results/{$processId}",
        "Voltar",
        "class='btn btn-danger pull-right'"
    ); ?>
    </div><!-- /.box-footer-->
</div>

<script>
    $(document).ready(function(){
        window.print();

        $("#print_report_btn").click(function(){
            window.print();
        });
    });
</script>