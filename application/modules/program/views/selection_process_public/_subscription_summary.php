<?php
  $gender = lang($userSubscription['sex']);
  $birthDate = convertDateTimeToDateBR($userSubscription['birth_date']);
  $addressCountry = $countries[$userSubscription['address_country']];

  echo "<h4>Dados informados:</h4>";
  echo "<div class='row'>";
    echo "<div class='col-md-6'>";
      echo "<h4><i class='fa fa-user'></i> Dados pessoais</h4>";
      echo "<p><b>Nome completo:</b> {$userSubscription['full_name']}</p>";
      echo "<p><b>Data de nascimento:</b> {$birthDate}</p>";
      echo "<p><b>Sexo:</b> {$gender}</p>";
      echo "<p><b>Nacionalidade:</b> {$userSubscription['nationality']}</p>";
      echo "<p><b>E-mail:</b> {$userSubscription['email']}</p>";
    echo "</div>";
    echo "<div class='col-md-6'>";
      echo "<h4><i class='fa fa-phone-square'></i> Contato</h4>";
      echo "<p><b>Telefone Residencial:</b> {$userSubscription['contact_ddd_home']} - {$userSubscription['contact_number_home']}</p>";
      echo "<p><b>Telefone Celular:</b> {$userSubscription['contact_ddd_mobile']} - {$userSubscription['contact_number_mobile']}</p>";
    echo "</div>";
  echo "</div>";

  echo "<h4 class='text-center'><i class='fa fa-home'></i> Endereço</h4>";
  echo "<div class='row'>";
    echo "<div class='col-md-6'>";
      echo "<p><b>Logradouro:</b> {$userSubscription['address_place']}</p>";
      echo "<p><b>Código postal:</b> {$userSubscription['address_cep']}</p>";
      echo "<p><b>Cidade:</b> {$userSubscription['address_city']}</p>";
    echo "</div>";
    echo "<div class='col-md-6'>";
      echo "<p><b>Estado:</b> {$userSubscription['address_state']}</p>";
      echo "<p><b>País:</b> {$addressCountry}</p>";
    echo "</div>";
  echo "</div>";

  echo "<div class='row'>";
    echo "<div class='col-md-12'>";
      echo "<h4 class='text-center'><i class='fa fa-wheelchair'></i> Necessidades especiais</h4>";
      $specialNeeds = !empty($userSubscription['special_needs'])
        ? $userSubscription['special_needs']
        : 'Não possui.';
        echo "<p>{$specialNeeds}</p>";
    echo "</div>";
  echo "</div>";

  echo "<h4 class='text-center'><i class='fa fa-files-o'></i> Documentos</h4>";
  echo "<div class='row'>";
    echo "<div class='col-md-6'>";
      echo "<h4 class='text-left'><i class='fa fa-files-o'></i> Documentos requeridos <br><small>Documentos necessários.</small></h4>";
      foreach($requiredDocs as $doc){
        $notTotallyRequired = !$doc['totally_required'] ? '*' : '';
        echo "<p>{$doc['doc_name']} {$notTotallyRequired}</p>";
      }
    echo "</div>";
    echo "<div class='col-md-6'>";
      echo "<h4 class='text-left'><i class='fa fa-cloud-upload'></i> Documentos enviados <br><small>Documentos que você enviou.</small></h4>";
      foreach($subscriptionDocs as $doc){
        echo "<p>";
        echo anchor(
          "selection_process/download/doc/{$doc['id_doc']}/{$doc['id_subscription']}",
          "<i class='fa fa-cloud-download'></i> {$doc['doc_name']}"
        );
        echo "</p>";

      }
    echo "</div>";
  echo "</div>";
  echo "<small><b>*</b> - Documentos não obrigatórios em alguns casos.</small>";
?>