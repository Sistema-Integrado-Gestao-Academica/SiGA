<?php

  $docFileError = function ($doc) use($filesErrors, $subscriptionDocs){
    $docId = $doc['id'];
    if(!empty($filesErrors) && isset($filesErrors[$docId])){
      $error = $filesErrors[$docId];
      echo "<p class='alert-danger'>{$error}</p>";
    }

    if(!$doc['totally_required']){
      echo "<p class='alert-warning'>Este arquivo não é obrigatório em alguns casos. Leia atentamente a descrição.</p>";
    }

    if(isset($subscriptionDocs[$docId])){
      echo "<p class='alert-success'>Este arquivo já foi enviado com sucesso.</p>";
      echo anchor(
        "selection_process/download/doc/{$docId}/{$subscriptionDocs[$docId]['id_subscription']}",
        "<i class='fa fa-cloud-download'></i> Baixar arquivo enviado",
        "class='btn btn-sm btn-info btn-block'"
      );
    }
  };

  $docFileInput = function($doc){
    $docFieldId = 'doc_'.$doc['id'];
    return [
      'name' => $docFieldId,
      'id' => $docFieldId,
      'type' => 'file',
      'class' => 'filestyle',
      'data-buttonBefore' => 'true',
      'data-buttonText' => '',
      'data-placeholder' => $doc['doc_name'],
      'data-iconName' => 'fa fa-file',
      'data-buttonName' => 'btn-default'
    ];
  };

  $userFullName = $userSubscription !== FALSE
    ? $userSubscription['full_name']
    : $userData->getName();
  $fullName = array(
    'name' => 'candidate_full_name',
    'id' => 'candidate_full_name',
    'type' => 'text',
    'class' => 'form-campo form-control',
    'placeholder' => 'Informe o seu nome completo',
    'maxlength' => '70',
    'required' => true,
    'value' => set_value('candidate_full_name', $userFullName, false)
  );

  $selectedMaleSex = $userSubscription !== FALSE
    ? $userSubscription['sex'] == 'male' ? " checked='checked'" : ''
    : set_radio('candidate_sex', 'male');
  $maleSex = array(
    'id' => 'candidate_male_sex',
    'name' => 'candidate_sex',
    'type' => 'radio',
    'class' => 'form-campo form-control',
    'value' => 'male'
  );

  $selectedFemaleSex = $userSubscription !== FALSE
    ? $userSubscription['sex'] == 'female' ? " checked='checked'" : ''
    : set_radio('candidate_sex', 'female');
  $femaleSex = array(
    'id' => 'candidate_female_sex',
    'name' => 'candidate_sex',
    'type' => 'radio',
    'class' => 'form-campo form-control',
    'value' => 'female'
  );

  $userBirthDate = $userSubscription !== FALSE
    ? convertDateTimeToDateBR($userSubscription['birth_date'])
    : '';
  $birthDate = array(
    'id' => 'candidate_birth_date',
    'name' => 'candidate_birth_date',
    'type' => 'text',
    'placeholder' => 'Informe sua data de nascimento',
    'class' => 'form-campo',
    'class' => 'form-control',
    'required' => true,
    'value' => set_value('candidate_birth_date', $userBirthDate, false)
  );

  $userEmail = $userSubscription !== FALSE
    ? $userSubscription['email']
    : $userData->getEmail();
  $email = array(
    'id' => 'candidate_email',
    'name' => 'candidate_email',
    'type' => 'text',
    'class' => 'form-campo form-control',
    'placeholder' => '* Evite informar emails institucionais.',
    'maxlength' => '60',
    'required' => true,
    'value' => set_value('candidate_email', $userEmail, false)
  );

  $userNationality = $userSubscription !== FALSE
    ? $userSubscription['nationality']
    : '';
  $nationality = array(
    'id' => 'candidate_nationality',
    'name' => 'candidate_nationality',
    'type' => 'text',
    'class' => 'form-campo form-control',
    'placeholder' => 'Informe sua nacionalidade',
    'maxlength' => '30',
    'required' => true,
    'value' => set_value('candidate_nationality', $userNationality, false)
  );

  // Check if there is a selected country from previous request
  $selectedCountry = $userSubscription !== FALSE
    ? $userSubscription['address_country']
    : $this->input->post('candidate_address_country');
  $selectedCountry = $selectedCountry ? $selectedCountry : 'BR';

  $userAddressPlace = $userSubscription !== FALSE
    ? $userSubscription['address_place']
    : '';
  $userAddressCity = $userSubscription !== FALSE
    ? $userSubscription['address_city']
    : '';
  $userAddressState = $userSubscription !== FALSE
    ? $userSubscription['address_state']
    : '';
  $userAddressCep = $userSubscription !== FALSE
    ? $userSubscription['address_cep']
    : '';
  $address = array(
    'place' => array(
      'id' => 'candidate_address_place',
      'name' => 'candidate_address_place',
      'type' => 'text',
      'class' => 'form-campo form-control',
      'placeholder' => 'Informe seu endereço e complemento, se houver.',
      'maxlength' => '80',
      'required' => true,
      'value' => set_value('candidate_address_place', $userAddressPlace, false)
    ),
    'city' => array(
      'id' => 'candidate_address_city',
      'name' => 'candidate_address_city',
      'type' => 'text',
      'class' => 'form-campo form-control',
      'placeholder' => 'Informe sua cidade',
      'maxlength' => '40',
      'required' => true,
      'value' => set_value('candidate_address_city', $userAddressCity, false)
    ),
    'state' => array(
      'id' => 'candidate_address_state',
      'name' => 'candidate_address_state',
      'type' => 'text',
      'class' => 'form-campo form-control',
      'placeholder' => 'Informe seu estado',
      'maxlength' => '40',
      'required' => true,
      'value' => set_value('candidate_address_state', $userAddressState, false)
    ),
    'cep' => array(
      'id' => 'candidate_address_cep',
      'name' => 'candidate_address_cep',
      'type' => 'number',
      'class' => 'form-campo form-control',
      'placeholder' => 'Código postal/CEP',
      'maxlength' => '10',
      'min' => 0,
      'required' => true,
      'value' => set_value('candidate_address_cep', $userAddressCep, false)
    ),
    'countries' => getAllCountries()
  );

  $userContactHomeDDD = $userSubscription !== FALSE
    ? $userSubscription['contact_ddd_home']
    : '';
  $userContactHomeNumber = $userSubscription !== FALSE
    ? $userSubscription['contact_number_home']
    : '';
  $userContactMobileDDD = $userSubscription !== FALSE
    ? $userSubscription['contact_ddd_mobile']
    : '';
  $userContactMobileNumber = $userSubscription !== FALSE
    ? $userSubscription['contact_number_mobile']
    : '';
  $contact = array(
    'home' => array(
      'ddd' => array(
        'id' => 'candidate_contact_ddd_home',
        'name' => 'candidate_contact_ddd_home',
        'type' => 'number',
        'min' => '0',
        'class' => 'form-campo form-control',
        'placeholder' => 'Código de área (DDD)',
        'maxlength' => '4',
        'required' => true,
        'value' => set_value('candidate_contact_ddd_home', $userContactHomeDDD, false)
      ),
      'number' => array(
        'id' => 'candidate_contact_number_home',
        'name' => 'candidate_contact_number_home',
        'type' => 'number',
        'min' => '0',
        'class' => 'form-campo form-control',
        'placeholder' => 'Número do telefone',
        'maxlength' => '15',
        'required' => true,
        'value' => set_value('candidate_contact_number_home', $userContactHomeNumber, false)
      )
    ),
    'mobile' => array(
      'ddd' => array(
        'id' => 'candidate_contact_ddd_mobile',
        'name' => 'candidate_contact_ddd_mobile',
        'type' => 'number',
        'min' => '0',
        'class' => 'form-campo form-control',
        'placeholder' => 'Código de área (DDD)',
        'maxlength' => '4',
        'required' => true,
        'value' => set_value('candidate_contact_ddd_mobile', $userContactMobileDDD, false)
      ),
      'number' => array(
        'id' => 'candidate_contact_number_mobile',
        'name' => 'candidate_contact_number_mobile',
        'type' => 'number',
        'min' => '0',
        'class' => 'form-campo form-control',
        'placeholder' => 'Número do telefone',
        'maxlength' => '15',
        'required' => true,
        'value' => set_value('candidate_contact_number_mobile', $userContactMobileNumber, false)
      )
    )
  );

  $userSpecialNeeds = $userSubscription !== FALSE
    ? $userSubscription['special_needs']
    : '';
  $specialNeeds = array(
    'id' => 'candidate_special_needs',
    'name' => 'candidate_special_needs',
    'placeholder' => 'Descreva aqui as suas necessidades especiais, se houver.',
    'rows' => '4',
    'class' => 'form-control',
    'value' => set_value('candidate_special_needs', $userSpecialNeeds, false)
  );

  $selectedResearchLine = $userSubscription !== FALSE
    ? $userSubscription['research_line']
    : $this->input->post('candidate_research_line');
  $selectedResearchLine = $selectedResearchLine ? $selectedResearchLine : '';

  $subscribeBtn = array(
    'id' => 'subscribe_process_btn',
    'class' => 'btn btn-success btn-lg btn-block',
    'content' => $userSubscription !== FALSE ? "<i class='fa fa-edit'></i> Atualizar inscrição" : 'Inscrever-se!',
    'type' => 'submit'
  );
?>

<h2 class="principal">Inscrição no processo <b><i><?= $process->getName() ?></i></b></h2>
<?php if($userSubscription !== FALSE): ?>

  <?php
    alert(function(){
      echo "<p>Seus dados básicos <b>já foram salvos</b>, mas você <b>ainda pode alterar suas informações e documentos</b>.</p>";
      echo "<p>Quando estiver tudo certo, <b>confirme sua inscrição</b> para efetivá-la.</p>";
    }, 'success', FALSE, 'fa fa-check');
  ?>
<?php endif ?>

<div class="row">
  <?= anchor(
    "selection_process/public",
    "Voltar",
    "class='pull-right btn btn-danger'"
  ); ?>
</div>

<?= form_open_multipart(
  "selection_process/subscribe_to/{$process->getId()}",
  ['id' => 'candidate_subscription_form']
)?>
  <h3>
    <i class="fa fa-user"></i> Dados pessoais <br>
    <small>
      <p>Informe aqui seus dados pessoais.</p>
      <p>
        <i class="fa fa-exclamation-triangle"></i>
        Nós recuperamos alguns dados do seu cadastro inicial no sistema.
        Por favor, confira os dados e preencha o restante das informações.
      </p>
    </small>
  </h3>
  <br>

  <div class="row">
    <div class="col-md-6 form-group">
      <?= form_label('Nome completo', $fullName['id']) ?>
      <?= newInputField($fullName) ?>
      <?= form_error($fullName['id']) ?>
    </div>
    <div class="col-md-4 form-group">
      <?= form_label('Data de nascimento', $birthDate['id']) ?>
      <?= newInputField($birthDate) ?>
      <?= form_error($birthDate['id']) ?>
    </div>
    <div class="col-md-2 form-group">
      <?= form_label('Sexo') ?>
      <?= form_error($maleSex['name']) ?>

      <div class="radio">
        <label id="candidate_female_sex_div">
          <?= form_radio($femaleSex, '', '', $selectedFemaleSex) ?>
          Feminino
        </label>
      </div>
      <div class="radio">
        <label id="candidate_male_sex_div">
          <?= form_radio($maleSex, '', '', $selectedMaleSex) ?>
          Masculino
        </label>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 form-group">
      <?= form_label('Nacionalidade', $nationality['id']) ?>
      <?= newInputField($nationality) ?>
      <?= form_error($nationality['id']) ?>
    </div>
    <div class="col-md-6 form-group">
      <?= form_label('E-mail', $email['id']) ?>
      <?= newInputField($email) ?>
      <?= form_error($email['id']) ?>
    </div>
  </div>
  <hr>
  <!-- Address info -->
  <div class="row">
    <h4><i class="fa fa-home"></i> Endereço </h4>
    <div class="col-md-8 form-group">
      <?= form_label('Logradouro', $address['place']['id']) ?>
      <?= newInputField($address['place']) ?>
      <?= form_error($address['place']['id']) ?>
    </div>
    <div class="col-md-4 form-group">
      <?= form_label('Código Postal/CEP', $address['cep']['id']) ?>
      <?= newInputField($address['cep']) ?>
      <?= form_error($address['cep']['id']) ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4 form-group">
      <?= form_label('Cidade', $address['city']['id']) ?>
      <?= newInputField($address['city']) ?>
      <?= form_error($address['city']['id']) ?>
    </div>
    <div class="col-md-4 form-group">
      <?= form_label('Estado', $address['state']['id']) ?>
      <?= newInputField($address['state']) ?>
      <?= form_error($address['state']['id']) ?>
    </div>
    <div class="col-md-4 form-group">
      <?= form_label('País', 'candidate_address_country') ?>
      <?=
        form_dropdown(
          'candidate_address_country',
          $address['countries'],
          $selectedCountry,
          "class='form-control' required='true'"
        )
      ?>
      <?= form_error('candidate_address_country') ?>
    </div>
  </div>

  <hr>
  <!-- Contact info -->
  <h4><i class="fa fa-phone-square"></i> Dados para contato </h4>
  <br>

  <div class="row">

    <div class="col-md-6">
      <h5><i class="fa fa-phone"></i> Telefone Residencial </h5>
      <div class="row form-group">
        <div class="col-md-4 form-group">
          <?= form_label('Código de área', $contact['home']['ddd']['id']) ?>
          <?= newInputField($contact['home']['ddd']) ?>
          <?= form_error($contact['home']['ddd']['id']) ?>
        </div>
        <div class="col-md-8 form-group">
          <?= form_label('Número', $contact['home']['number']['id']) ?>
          <?= newInputField($contact['home']['number']) ?>
          <?= form_error($contact['home']['number']['id']) ?>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <h5><i class="fa fa-mobile-phone"></i> Telefone Celular </h5>
      <div class="row form-group">
        <div class="col-md-4 form-group">
          <?= form_label('Código de área', $contact['mobile']['ddd']['id']) ?>
          <?= newInputField($contact['mobile']['ddd']) ?>
          <?= form_error($contact['mobile']['ddd']['id']) ?>
        </div>
        <div class="col-md-8 form-group">
          <?= form_label('Número', $contact['mobile']['number']['id']) ?>
          <?= newInputField($contact['mobile']['number']) ?>
          <?= form_error($contact['mobile']['number']['id']) ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Special needs info -->
  <h4>
    <i class="fa fa-wheelchair"></i> Candidato(a) Portador(a) de Necessidades Especiais?
    <br><small>Em caso positivo, explicitar os recursos especiais de que necessita para a realização das etapas da Seleção.</small>
  </h4>
  <div class="form-group">
    <?= form_label('Descrição das necessidades especiais', $specialNeeds['id']) ?>
    <?= form_textarea($specialNeeds) ?>
    <?= form_error($specialNeeds['id']) ?>
  </div>

  <hr>
  <div class="row">
    <h3>
      <i class="fa fa-book"></i> Linha de pesquisa <br>
      <small>
        <p>Escolha entre uma das linhas de pesquisa do seu curso.</p>
      </small>
    </h3>
    <?= form_label('Linha de pesquisa', 'candidate_research_line') ?>
    <?=
      form_dropdown(
        'candidate_research_line',
        $researchLines,
        $selectedResearchLine,
        "class='form-control' required='true'"
      )
    ?>
  </div>
  <hr>

  <div id="required_docs" class="row">
    <h3>
      <i class="fa fa-files-o"></i> Documentos necessários <br>
      <small>
        <p>Os documentos abaixo são necessários para realizar
           a inscrição no processo seletivo.</p>
        <p>Clique no ícone <i class="fa fa-file"></i>
           para selecionar o respectivo arquivo.</p>
        <p>Submeta apenas arquivos PDF (.pdf).</p>
      </small>
    </h3>
    <br>

    <p class="text-warning">
      <i class="fa fa-warning"></i> Candidatos estrangeiros, confira se há documentos específicos para sua situação, como Carteira de Identidade de Estrangeiros.</p>
    <div id="documents_info_message"></div>
    <br>

    <?php if(!empty($requiredDocs)):
      $index = 0;
      $row = "<div class='row'>";
      $endrow = "</div>";
      echo $row;
    ?>
        <?php
          foreach($requiredDocs as $doc):
            $text = $index % 3 == 0 ? $row : "";
            echo $text;
        ?>
          <div class="col-md-4 form-group">
            <?= form_label($doc['doc_name'], $doc['id']) ?>
            <br><small><?= $doc['doc_desc'] ?></small>
            <?= form_input($docFileInput($doc)) ?>
            <?php $docFileError($doc); ?>
          </div>
        <?php
          $index++;
          $text = $index % 3 == 0 ? $endrow : "";
          echo $text;
          endforeach
        ?>

    <?php else: ?>
      <?= callout('info', 'Não são necessários documentos para inscrição.') ?>
    <?php endif ?>

  </div>
  <br>
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <?= form_button($subscribeBtn) ?>
    </div>
  </div>
<?= form_close() ?>
<br>

<?= anchor(
  "selection_process/public",
  "Voltar",
  "class='pull-left btn btn-danger btn-lg'"
); ?>

<script>
  $(document).ready(function(){

    checkCandidateGender();

    $("#candidate_birth_date"). datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
      yearRange: "1900:"
    });
    $("#candidate_birth_date").datepicker($.datepicker.regional["pt-BR"]);
  });

  function checkCandidateGender(){
    var candidateGender = $('input[name=candidate_sex]:checked', '#candidate_subscription_form').val();

    var documentWarning = "<p class='text-warning'><i class='fa fa-warning'></i> Candidato do sexo masculino, confira se há documentos obrigatórios como o Certificado de Reservista.</p>";

    if(candidateGender == 'male'){
      $("#documents_info_message").html(documentWarning);
    }

    $('#candidate_male_sex_div').click(function(){
      $("#documents_info_message").html(documentWarning);
    });
  }
</script>