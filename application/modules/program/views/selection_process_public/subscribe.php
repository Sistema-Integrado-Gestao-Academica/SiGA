<h2 class="principal">Inscrição no processo <b><i><?= $process->getName() ?></i></b></h2>

<?php
  $hidden = array(

  );

  $docFileInput = function($docId, $docName){
    return [
      'name' => $docId,
      'id' => $docId,
      'type' => 'file',
      'class' => 'filestyle',
      'data-buttonBefore' => 'true',
      'data-buttonText' => '',
      'data-placeholder' => $docName,
      'data-iconName' => 'fa fa-file',
      'data-buttonName' => 'btn-default',
    ];
  };

  $fullName = array(
    'name' => 'candidate_full_name',
    'id' => 'candidate_full_name',
    'type' => 'text',
    'class' => 'form-campo form-control',
    'placeholder' => 'Informe o seu nome completo',
    'maxlength' => '70',
    'required' => true,
    'value' => set_value('candidate_full_name', '', false)
  );

  $maleSex = array(
    'id' => 'candidate_male_sex',
    'name' => 'candidate_sex',
    'type' => 'radio',
    'class' => 'form-campo form-control',
    'value' => 'male'
  );

  $femaleSex = array(
    'id' => 'candidate_female_sex',
    'name' => 'candidate_sex',
    'type' => 'radio',
    'class' => 'form-campo form-control',
    'value' => 'female'
  );

  $birthDate = array(
    'id' => 'candidate_birth_date',
    'name' => 'candidate_birth_date',
    'type' => 'text',
    'placeholder' => 'Informe sua data de nascimento',
    'class' => 'form-campo',
    'class' => 'form-control',
    'required' => true,
    'value' => set_value('candidate_birth_date', '', false)
  );

  $email = array(
    'id' => 'candidate_email',
    'name' => 'candidate_email',
    'type' => 'text',
    'class' => 'form-campo form-control',
    'placeholder' => '* Evite informar emails institucionais.',
    'maxlength' => '60',
    'required' => true,
    'value' => set_value('candidate_email', '', false)
  );

  $nationality = array(
    'id' => 'candidate_nationality',
    'name' => 'candidate_nationality',
    'type' => 'text',
    'class' => 'form-campo form-control',
    'placeholder' => 'Informe sua nacionalidade',
    'maxlength' => '30',
    'required' => true,
    'value' => set_value('candidate_nationality', '', false)
  );

  // Check if there is a selected country from previous request
  $selectedCountry = $this->input->post('candidate_address_country');
  $selectedCountry = $selectedCountry ? $selectedCountry : 'BR';

  $address = array(
    'place' => array(
      'id' => 'candidate_address_place',
      'name' => 'candidate_address_place',
      'type' => 'text',
      'class' => 'form-campo form-control',
      'placeholder' => 'Informe seu endereço e complemento, se houver.',
      'maxlength' => '80',
      'required' => true,
      'value' => set_value('candidate_address_place', '', false)
    ),
    'city' => array(
      'id' => 'candidate_address_city',
      'name' => 'candidate_address_city',
      'type' => 'text',
      'class' => 'form-campo form-control',
      'placeholder' => 'Informe sua cidade',
      'maxlength' => '40',
      'required' => true,
      'value' => set_value('candidate_address_city', '', false)
    ),
    'state' => array(
      'id' => 'candidate_address_state',
      'name' => 'candidate_address_state',
      'type' => 'text',
      'class' => 'form-campo form-control',
      'placeholder' => 'Informe seu estado',
      'maxlength' => '40',
      'required' => true,
      'value' => set_value('candidate_address_state', '', false)
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
      'value' => set_value('candidate_address_cep', '', false)
    ),
    'countries' => getAllCountries()
  );

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
        'value' => set_value('candidate_contact_ddd_home', '', false)
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
        'value' => set_value('candidate_contact_number_home', '', false)
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
        'value' => set_value('candidate_contact_ddd_mobile', '', false)
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
        'value' => set_value('candidate_contact_number_mobile', '', false)
      )
    )
  );

  $specialNeeds = array(
    'id' => 'candidate_special_needs',
    'name' => 'candidate_special_needs',
    'placeholder' => 'Descreva aqui as suas necessidades especiais, se houver.',
    'rows' => '4',
    'class' => 'form-control',
    'value' => set_value('candidate_special_needs', '', false)
  );

  $subscribeBtn = array(
    'id' => 'subscribe_process_btn',
    'class' => 'btn btn-success btn-lg btn-block',
    'content' => 'Inscrever-se!',
    'type' => 'submit'
  );
?>

<div class="row">
  <?= anchor(
    "selection_process/public",
    "Voltar",
    "class='pull-right btn btn-danger btn-lg'"
  ); ?>
</div>

<h3>
  <i class="fa fa-user"></i> Dados pessoais <br>
  <small>Informe seus dados pessoais.</small>
</h3>
<br>

<?= form_open_multipart("selection_process/subscribe_to/{$process->getId()}") ?>
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
        <label>
          <?= form_radio($femaleSex, '', '', set_radio('candidate_sex', 'female')) ?>
          Feminino
        </label>
      </div>
      <div class="radio">
        <label>
          <?= form_radio($maleSex, '', '', set_radio('candidate_sex', 'male')) ?>
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

  <div class="row">
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

    <?php if(!empty($requiredDocs)): ?>
      <div class="row">
        <?php foreach($requiredDocs as $doc): ?>
          <div class="col-md-4 form-group">
            <?= form_label($doc['doc_name'], $doc['id']) ?>
            <br><small><?= $doc['doc_desc'] ?></small>
            <?= form_input($docFileInput($doc['id'], $doc['doc_name'])) ?>
          </div>
        <?php endforeach ?>
      </div>
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
    $("#candidate_birth_date").datepicker($.datepicker.regional["pt-BR"], {
      dateFormat: "dd-mm-yy"
    });
  });
</script>