<h2 class="principal">
  Inscrições realizadas no processo <b><i><?= $process->getName() ?></i></b>
</h2>

<?php if($finalizedSubscriptions !== FALSE): ?>
  <div align='right'>
    <i class='fa fa-eye'> Visualizar informações</i> &nbsp&nbsp
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

    <?php foreach($finalizedSubscriptions as $subscription): ?>
    <?php $candidateId = $subscription['candidate_id']; ?>
      <tr>
        <td><?= $candidateId ?></td>
        <td><?= $subscription['full_name'] ?></td>
        <td><?= lang($subscription['sex']) ?></td>
        <td><?= $subscription['email'] ?></td>
        <td>
          <?=
            form_button([
              'id' => "subscription_{$candidateId}_modal_btn",
              'class' => 'btn btn-primary',
              'content' => "<i class='fa fa-eye'></i>",
              'data-toggle' => 'modal',
              'data-target' => "#subscription_{$candidateId}_modal"
            ]);
          ?>
        </td>
      </tr>
    <?php endforeach ?>
  <?php buildTableEndDeclaration(); ?>

  <?php foreach($finalizedSubscriptions as $userSubscription): ?>
    <?php
      $subscriptionDocs = $getSubscriptionDocsService($subscription);
      $subscriptionData = function() use ($userSubscription, $subscriptionDocs, $requiredDocs, $countries){
        include(MODULESPATH.'program/views/selection_process_public/_subscription_summary.php');
      };
      newModal(
        "subscription_{$userSubscription['candidate_id']}_modal",
        "Dados da inscrição do candidato {$userSubscription['candidate_id']}",
        $subscriptionData
      );
    ?>
  <?php endforeach ?>

<?php else: ?>
  <?php callout('info', 'Nenhuma inscrição finalizada no momento neste processo seletivo.'); ?>
<?php endif ?>
