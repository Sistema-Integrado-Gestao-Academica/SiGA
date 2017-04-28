<h2 class="principal">
  Inscrições realizadas no processo <b><i><?= $process->getName() ?></i></b>
</h2>

<?php
  goBackBtn("program/selectiveprocess/courseSelectiveProcesses/{$process->getCourse()}");
?>

<div class="row">
  <h4><i class="fa fa-list"></i> Inscrições finalizadas pelo candidato:</h4>
  <br>
  <?php
    call_user_func(function() use($finalizedSubscriptions, $process, $getSubscriptionDocsService, $requiredDocs, $countries, $researchLines){
        $subscriptions = $finalizedSubscriptions;
        $actions = function($subscription) {
            echo form_button([
              'id' => "subscription_{$subscription['candidate_id']}_modal_btn",
              'class' => 'btn btn-primary',
              'content' => "<i class='fa fa-eye'></i>",
              'data-toggle' => 'modal',
              'data-target' => "#subscription_{$subscription['candidate_id']}_modal"
            ]);

             echo anchor(
              "selection_process/homolog/subscription/{$subscription['id']}",
              "<i class='fa fa-thumbs-o-up'></i>",
              "class='btn btn-success'"
            );
        };
        $postList = function() use ($subscriptions, $researchLines,
          $getSubscriptionDocsService, $requiredDocs, $countries) {
            foreach($subscriptions as $userSubscription){
              $subscriptionDocs = $getSubscriptionDocsService($userSubscription);
              $subscriptionData = function() use ($userSubscription, $subscriptionDocs, $requiredDocs, $countries, $researchLines){
                include(MODULESPATH.'program/views/selection_process_public/_subscription_summary.php');
              };
              newModal(
                "subscription_{$userSubscription['candidate_id']}_modal",
                "Dados da inscrição do candidato {$userSubscription['candidate_id']}",
                $subscriptionData
              );
            }
        };

        include('_subscriptions.php');
    });
  ?>
</div>

<br>
<div class="row">
  <h4><i class="fa fa-check-circle"></i> Inscrições homologadas:</h4>
  <br>
  <?php
    call_user_func(function() use($homologatedSubscriptions, $process, $getSubscriptionDocsService, $requiredDocs, $countries,
      $getSubscriptionTeachersService, $researchLines){
        $subscriptions = $homologatedSubscriptions;
        $actions = function($subscription){
            echo form_button([
              'id' => "subscription_homolog_{$subscription['candidate_id']}_modal_btn",
              'class' => 'btn btn-primary',
              'content' => "<i class='fa fa-eye'></i>",
              'data-toggle' => 'modal',
              'data-target' => "#subscription_homolog_{$subscription['candidate_id']}_modal"
            ]);

            echo form_button([
              'id' => "subscription_teachers_{$subscription['candidate_id']}_modal_btn",
              'class' => 'btn bg-navy',
              'content' => "<i class='fa fa-users'></i>",
              'data-toggle' => 'modal',
              'data-target' => "#subscription_teachers_{$subscription['candidate_id']}_modal"
            ]);
        };
        $postList = function() use ($subscriptions, $researchLines,
          $getSubscriptionDocsService, $requiredDocs,
          $countries, $getSubscriptionTeachersService) {
            foreach($subscriptions as $userSubscription){
              $subscriptionDocs = $getSubscriptionDocsService($userSubscription);
              $subscriptionData = function() use ($userSubscription, $subscriptionDocs, $requiredDocs, $countries, $researchLines){
                include(MODULESPATH.'program/views/selection_process_public/_subscription_summary.php');
              };
              newModal(
                "subscription_homolog_{$userSubscription['candidate_id']}_modal",
                "Dados da inscrição do candidato {$userSubscription['candidate_id']}",
                $subscriptionData
              );

              $subscriptionTeachersData = function() use ($userSubscription,
                $getSubscriptionTeachersService){
                  $teachers = $getSubscriptionTeachersService($userSubscription['id']);
                  $teachers = !empty($teachers) ? $teachers : [];
                  echo "<ul>";
                  foreach ($teachers as $teacher) {
                    echo "<li>";
                    echo $teacher['name'];
                    echo "</li>";
                  }
                  echo "</ul>";
              };

              newModal(
                "subscription_teachers_{$userSubscription['candidate_id']}_modal",
                "Docentes avaliadores do candidato {$userSubscription['candidate_id']}",
                $subscriptionTeachersData
              );
            }
        };
        include('_subscriptions.php');
    });
  ?>
</div>
