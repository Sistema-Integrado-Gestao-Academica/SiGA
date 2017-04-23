<?php
    return [
      [
        'field' => 'candidate_full_name',
        'label' => 'Nome completo',
        'rules' => ['required', 'trim', 'valid_name', 'max_length[70]'],
        'errors' => [
          'required' => 'Informe o seu nome completo.',
          'max_length' => 'Informe até 70 caracteres.',
          'valid_name' => 'Informe apenas caracteres alfabéticos no seu nome.'
        ]
      ],
      [
        'field' => 'candidate_sex',
        'label' => 'Sexo',
        'rules' => ['required']
      ],
      [
        'field' => 'candidate_birth_date',
        'label' => 'Data de nascimento',
        'rules' => ['required', 'trim', 'valid_date_DMY']
      ],
      [
        'field' => 'candidate_email',
        'label' => 'e-mail',
        'rules' => ['required', 'trim', 'valid_email', 'max_length[60]']
      ],
      [
        'field' => 'candidate_nationality',
        'label' => 'Nacionalidade',
        'rules' => ['required', 'trim', 'valid_name', 'max_length[30]']
      ],
      [
        'field' => 'candidate_address_place',
        'label' => 'Logradouro',
        'rules' => ['required', 'trim', 'valid_address', 'max_length[80]']
      ],
      [
        'field' => 'candidate_address_city',
        'label' => 'Cidade',
        'rules' => ['required', 'trim', 'valid_name', 'max_length[40]']
      ],
      [
        'field' => 'candidate_address_state',
        'label' => 'Estado',
        'rules' => ['required', 'trim', 'valid_name', 'max_length[40]']
      ],
      [
        'field' => 'candidate_address_cep',
        'label' => 'Código postal',
        'rules' => ['required', 'trim', 'numeric', 'is_natural', 'max_length[10]']
      ],
      [
        'field' => 'candidate_address_country',
        'label' => 'País do endereço',
        'rules' => ['required', 'max_length[2]']
      ],
      [
        'field' => 'candidate_contact_ddd_home',
        'label' => 'Código de área - Residencial',
        'rules' => ['required', 'numeric', 'is_natural', 'max_length[4]'],
      ],
      [
        'field' => 'candidate_contact_number_home',
        'label' => 'Número de telefone residencial',
        'rules' => ['required', 'numeric', 'is_natural', 'max_length[15]'],
      ],
      [
        'field' => 'candidate_contact_ddd_mobile',
        'label' => 'Código de área - Celular',
        'rules' => ['required', 'numeric', 'is_natural', 'max_length[4]']
      ],
      [
        'field' => 'candidate_contact_number_mobile',
        'label' => 'Número de telefone celular',
        'rules' => ['required', 'numeric', 'is_natural', 'max_length[15]']
      ],
      [
        'field' => 'candidate_special_needs',
        'label' => 'Necessidades especiais',
        'rules' => ['trim']
      ],
      [
        'field' => 'candidate_research_line',
        'label' => 'Linha de pesquisa',
        'rules' => ['required']
      ]
    ];
?>