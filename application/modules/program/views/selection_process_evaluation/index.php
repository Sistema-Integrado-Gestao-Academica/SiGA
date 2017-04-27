<h2 class="principal">Seus processos seletivos abertos</h2>

<div class="col-md-10 col-md-offset-1">
  <?php
    alert(function(){
      echo '<p>Aqui você pode ver os processos seletivos abertos aos quais você está vinculado.</p>';
    });
  ?>
</div>
<br>

<?php if ($openSelectiveProcesses){ 

  buildTableDeclaration();

  buildTableHeaders(array(
    'Edital',
    'Status',
    'Ações'
  ));

  foreach($openSelectiveProcesses as $process){ 
      $id = $process->getId(); 
      $phaseId = $processesPhase[$id]['phaseId']?>
      <tr>
        <td>
          <?= $process->getName() ?>
        </td>
        <td>
          <?= $processesPhase[$id]['status']?>
        </td>
        <td>
          <?php if($processesPhase[$id]['canEvaluate']){
            echo anchor("teacher_candidates/{$id}/{$phaseId}", "Iniciar Avaliação", "class='btn btn-primary'");
          }
          else {?>
            <p class='text text-warning'>Nenhuma ação disponível no momento.</p>
          <?php }?>
        </td>
      </tr>
  <?php }
} 
buildTableEndDeclaration();?>

 