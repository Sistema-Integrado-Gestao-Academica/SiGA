<h2 class="principal">Processo Seletivo <b><i><?=$process->getName()?></i></b> </h2>
<?php require_once (MODULESPATH."/program/constants/SelectionProcessConstants.php");  ?>
<div class="row">
    <?php $courseId = $process->getCourse(); ?>
    <?= anchor(
        "program/selectiveprocess/courseSelectiveProcesses/{$courseId}",
        "Voltar",
        "class='btn btn-danger pull-right'"
    ); ?>
</div>
<?php if ($approvedCandidates):
		foreach ($approvedCandidates as $phaseName => $candidates) {
			?>
				<div class='row'>
					<div class='col-lg-10 col-lg-offset-1'>
						<div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title">Resultado da fase de <b><?=$phaseName?></b></h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-primary btn-xs" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                                <ul class="list-unstyled">
                                	<h4><center> Candidatos aprovados </center></h4>
                                	<?php
                                	foreach ($candidates as $candidateId) {
                            			echo "<h4><li><center><b>{$candidateId}</b></center></li></h4>";
                                	}?>
                                </ul>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                            	<?php printButton($candidates, $phaseName, $process->getId());?>
                            </div><!-- /.box-footer-->
                        </div>
					</div>
				</div>

	<?php	}
	else:
        callout("info", "Ainda não há resultados para esse processo.");

    endif ?>

<?php
	function printButton($candidates, $phaseName, $processId){
        $data = [
            'candidates' => json_encode([
                'candidates' => $candidates
            ]),
            'phaseName' => $phaseName,
            'processId' => $processId
        ];

        echo form_open('selection_process/results/generatePDF/', [], $data);
            echo form_button([
                "id" => "print_report_btn",
                "class" => "btn btn-primary btn-flat",
                "content" => "<i class='fa fa-print'></i> Imprimir Resultado",
                "type" => "submit"
            ]);
        echo form_close();
    }
?>