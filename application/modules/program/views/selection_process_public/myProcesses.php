<h2 class="principal">Meus processos seletivos</h2>

<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <?php
      alert(function(){
        echo '<p>Aqui você pode ver os processos seletivos dos quais você está participando.</p>';
      });
    ?>
  </div>
</div>

<?php if(!empty($processes)): ?>

  <div class="row">
    <?php foreach($processes as $process): ?>
      <div class="col-md-6">
        <div class="box box-solid box-primary">
          <div class="box-header">
            <h3 class="box-title"><b><?= $process->getName() ?></b></h3>
            <div class="box-tools pull-right">
              <button class="btn btn-primary btn-sm" data-widget="collapse">
                <i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="box-body">
              <h4>Curso: <strong><?= $courses[$process->getId()] ?></strong></h4>

              <br>
              <h4 class="text-center">
                <i class="fa fa-calendar"></i> Período de inscrições
              </h4>

              <p><b>Data de início</b>: <?= $process->getSettings()->getFormattedStartDate() ?></p>
              <p><b>Data de fim</b>: <?= $process->getSettings()->getFormattedEndDate() ?></p>

              <h4 class="text-center"><?= lang($process->getStatus()) ?></h4>
              <h4 class="text-center"><?= warnInAppealPeriod($process) ?></h4>
          </div>
          <div class="box-footer">
            <div class="row">
              <div class="col-md-12">
                <?=
                  anchor(
                    "selection_process/subscription/{$process->getId()}",
                    "<i class='fa fa-arrow-circle-right'></i> Detalhes",
                    "class='btn btn-default btn-lg btn-block'"
                  );
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach ?>
  </div>

<?php else: ?>
  <?php callout('info', 'Você ainda não se inscreveu em nenhum processo seletivo.'); ?>
<?php endif ?>
