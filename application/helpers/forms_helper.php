<?php

function postGraduationTypesSelect(){
	$post_graduation_types = array(
		'academic_program' => 'Programa Acadêmico',
		'professional_program' => 'Programa Profissional'
	);
	$courseDuration = "<br>Duração mínima - 18 meses<br>Regular - 24 meses<br>Máxima - 30 meses<br>";

	echo form_label('Escolha o tipo da Pós-graduação');
	echo form_dropdown('post_graduation_type', $post_graduation_types,
				       'academic_program', 'id=post_graduation_type');
	echo $courseDuration;
}

function academicProgramForm(){
	echo form_label('Programa Acadêmico');
	echo "<h2>Fazer o resto do form aqui (Acadêmico)</h2>";
}

function professionalProgramForm(){
	echo form_label('Programa Profissional');
	echo "<h2>Fazer o resto do form aqui (Profissional)</h2>";
}

function emptyDiv(){
	echo "";
}