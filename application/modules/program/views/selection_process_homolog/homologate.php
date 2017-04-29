<h2 class="principal">
  Homologação da inscrição <b><i><?= $subscription['candidate_id'] ?></i></b>
</h2>

<?php goBackBtn("selection_process/homolog/subscriptions/{$process->getId()}") ?>

<h3><i class="fa fa-users"></i> Definir dupla de docentes avaliadores</h3>
<br>

<div class="row">
  <div class="col-md-6">
    <?php if(!empty($teachers)): ?>
      <div align='right'>
        <i class='fa fa-plus-square'> Definir docente</i> &nbsp&nbsp
      </div>
      <?php
        buildTableDeclaration(
          "define_teacher_pair_box",
          "define_teacher_pair_table",
          "Docentes da Comissão de Seleção do processo <b>{$process->getName()}</b>"
        );
      ?>
      <?php
        buildTableHeaders([
          'Nome',
          'Ações'
        ]);
      ?>

      <?php foreach ($teachers as $teacher): ?>

      <tr>
          <td><?= $teacher['name'] ?></td>
          <td>
            <?=
              form_button([
                'class' => 'btn btn-primary',
                'content' => "<i class='fa fa-plus-square'></i>",
                'onClick' => "addTeacherToSubscription(event, {$teacher['id']}, '{$teacher['name']}');"
              ]);
            ?>
          </td>
      </tr>

      <?php endforeach ?>

      <?php buildTableEndDeclaration(); ?>
    <?php else: ?>
      <?php callout('info', 'Não há professores vinculados a este processo.') ?>
    <?php endif ?>
  </div>
  <div class="col-md-6">

      <div align='right'>
        <i class='fa fa-minus-square'> Remover docente</i> &nbsp&nbsp
      </div>
      <?php
        buildTableDeclaration(
          "defined_teacher_pair_box",
          "defined_teacher_pair_table",
          "Dupla de docentes definida para o candidato <b>{$subscription['candidate_id']}</b>"
        );

        buildTableHeaders([
          'Nome',
          'Ações'
        ]);
      ?>

      <?php buildTableEndDeclaration(); ?>
  </div>
</div>

<br>
<div class="row">
  <?=
    form_button([
      'id' => 'confirm_teacher_pair',
      'name' => 'confirm_teacher_pair',
      'class' => 'btn btn-success btn-lg btn-block',
      'content' => "<i class='fa fa-check'></i> Confirmar dupla de professores e homologar inscrição"
    ]);
  ?>
</div>
<br>

<style type="text/css">
  #define_teacher_pair_box, #defined_teacher_pairs_table {
    height: 400px;
    overflow-y: auto;
  }
</style>

<?php newInputField('hidden', 'subscriptionId', $subscription['id']); ?>
<?php newInputField('hidden', 'defined_teachers_json', $definedTeachers); ?>

<script src=<?=base_url("js/selection_process_homolog.js")?>></script>
