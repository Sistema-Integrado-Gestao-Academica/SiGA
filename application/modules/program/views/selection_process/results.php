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
<?php if ($resultCandidatesByPhase):
		foreach ($resultCandidatesByPhase as $phaseName => $candidates) {
            $resultLabel = $phaseName == "Final" ? "<b>Resultado Final</b><br>Candidatos selecionados" : "Resultado da fase de <b>{$phaseName}</b>";?>
				<div class='row'>
					<div class='col-lg-10 col-lg-offset-1'>
						<div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title"><?=$resultLabel?></h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-primary btn-xs" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="box-body">
                            <?php if($phaseName == SelectionProcessConstants::HOMOLOGATION_PHASE){
                                createHomologationTable($candidates);
                            }
                            elseif($phaseName == "Final"){
                                createFinalTable($candidates);
                            }else{
                                createTableByPhase($candidates);
                            } ?>
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

    function createHomologationTable($candidates){
        buildTableDeclaration(); 
        buildTableHeaders(array('Candidatos homologados'));
        foreach ($candidates as $candidateId => $result) {
            echo "<tr>";
            echo "<td><h4><center>{$candidateId}</center></h4></td>";
            echo "</tr>";
        }
        buildTableEndDeclaration();
    }

    function createTableByPhase($candidates){
        buildTableDeclaration(); 
        buildTableHeaders(array('Candidato', 'Nota', 'Resultado'));
        foreach ($candidates as $candidateId => $result) {
            $average = $result['average'];
            $labelResult = $result['label'];
            echo "<tr>";
            echo "<td><h4><center>{$candidateId}</center></h4></td>";
            echo "<td><center>{$average}</center></td>";
            echo "<td><center>{$labelResult}</center></td>";
            echo "</tr>";
        }
        buildTableEndDeclaration();
    }

    function createFinalTable($candidates){
        buildTableDeclaration(); 
        $headers = array('Classificação', 'Candidato');
        if($candidates){
            $first = key($candidates);
            $candidatesHeaders = $candidates[$first];
            unset($candidatesHeaders['final_average']);
            unset($candidatesHeaders['selected']);
            foreach ($candidatesHeaders as $header) {
                $headers[] = "Nota da Fase ".$header['phaseName']."- Peso ".$header['phaseWeight'];
            }
            $headers[] = "Nota Final";
            buildTableHeaders($headers);
            $classificacao = 1;
            foreach ($candidates as $candidateId => $result) {
                $finalAverage = number_format($result['final_average'], 2, ',', ' ');
                unset($result['final_average']);
                echo "<tr>";
                echo "<td><center>{$classificacao}º</center></td>";
                echo "<td><h4><center>{$candidateId}</center></h4></td>";
                foreach ($result as $phaseResult) {
                    $average = $phaseResult['average'];
                    echo "<td><center>{$average}</center></td>";
                }
                echo "<td><center>{$finalAverage}</center></td>";
                echo "</tr>";
                $classificacao++;
            }
        }
        buildTableEndDeclaration();
    }
?>