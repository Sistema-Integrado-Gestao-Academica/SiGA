<?php if($subscriptions !== FALSE): ?>

  <div align='right'>
    <i class='fa fa-eye'> Visualizar informações</i> &nbsp&nbsp
    <i class='fa fa-thumbs-o-up'> Homologar inscrição</i> &nbsp&nbsp
    <i class='fa fa-users'> Docentes vinculados</i> &nbsp&nbsp
  </div>

  <?php
    buildTableDeclaration();

    buildTableHeaders([
      'Nº Candidato',
      'Nome completo',
      'Sexo',
      'E-mail',
      'Ações'
    ]);
  ?>

  <?php foreach($subscriptions as $subscription): ?>
    <tr>
      <td><?= $subscription['candidate_id'] ?></td>
      <td><?= $subscription['full_name'] ?></td>
      <td><?= lang($subscription['sex']) ?></td>
      <td><?= $subscription['email'] ?></td>
      <td>
        <?php $actions($subscription); ?>
      </td>
    </tr>
  <?php endforeach ?>

  <?php buildTableEndDeclaration(); ?>

  <?php $postList(); ?>

<?php else: ?>
  <?php callout('info', 'Nenhuma inscrição nessa situação no momento para este processo seletivo.'); ?>
<?php endif ?>