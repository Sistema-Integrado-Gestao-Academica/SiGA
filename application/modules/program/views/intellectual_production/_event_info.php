<?php
  $place = $eventProduction['place'];
  if(empty($place)){
    $place = "Não informado";
  }

  $startDate = $eventProduction['start_date'];
  if($startDate == '0000-00-00' || is_null($startDate)){
    $period = "Não informado";
  }
  else{
    $startDate = convertDateTimeToDateBR($startDate);
    $endDate = convertDateTimeToDateBR($eventProduction['end_date']);
    $period = $startDate." a ".$endDate;
  }

  $promotingInstitution = $eventProduction['promoting_institution'];
  if(empty($promotingInstitution)){
    $promotingInstitution = "Não informada";
  }
?>


<strong><h4> Dados do evento</h4></strong>
<div class="box box-success">
    <div class="box-header with-border">
    </div>
    <!-- /.box-header -->
    <div class="box-body">

      <strong>Nome do Evento</strong>
      
      <p class="text">
        <?= $eventProduction['event_name']?>  
      </p>

      <strong>Natureza do Evento</strong>

      <p class="text">
        <?= $eventProduction['event_nature']?>  
      </p>

      <strong>Local de Realização</strong>

      <p class="text">
        <?= $place ?>  
      </p>

      <strong>Período de Realização</strong>

      <p class="text">
        <?= $period ?>
      </p>

      <strong>Instituição Promotora</strong>

      <p class="text">
        <?= $promotingInstitution?>  
      </p>

    </div>
    <!-- /.box-body -->
</div>
