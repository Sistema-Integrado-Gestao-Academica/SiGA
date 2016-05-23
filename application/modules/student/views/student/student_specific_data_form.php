<h2 class="principal">Dados do(a) estudante <b><i><?php echo $userData->getName();?></i></b></h2>

<h3><i class='fa fa-user'></i> Dados Pessoais</h3>

<?php
	buildTableDeclaration();

	buildTableHeaders(array(
		'Matrícula',
		'E-mail',
		'Telefone residencial)',
		'Telefone celular'
	));

	if($studentData !== FALSE){
		echo "<tr>";
			echo "<td>";
				foreach ($studentData['enrollment'] as $registration){
					echo $registration;
					echo "<br>";
				}
			echo "</td>";
			echo "<td>";
				echo $studentData['email'];
			echo "</td>";
			echo "<td>";
				echo $studentData['home_phone'];
			echo "</td>";
			echo "<td>";
				echo $studentData['cell_phone'];
			echo "</td>";
		echo "</tr>";
	}else{

		echo "<tr>";
		echo "<td colspan=5>";
			callout("warning", "Você ainda não atualizou seus dados.");
		echo "</td>";
		echo "</tr>";
	}

	buildTableEndDeclaration();

echo "<br>";

if($studentData['home_phone'] == NULL){
	callout("warning", "Você ainda não atualizou o seu Telefone Residencial.", "Atualize logo abaixo!");
}

if($studentData['cell_phone'] == NULL){
	callout("warning", "Você ainda não atualizou o seu Telefone Celular.", "Atualize logo abaixo!");
}

echo "<br>";

// Update data form

$hidden = array(
	'id_user' => $userData->getId()
);

if($studentData !== FALSE){
	echo "<h3><i class='fa fa-paste'></i> Mantenha-nos atualizados</h3>";
	echo "<div class='form-box' id='login-box'>";
		echo "<div class='header'>Informações Básicas</div>";
		studentBasicInfoForm("student/student/saveBasicInfo", $hidden, $studentData);
}else{
	echo "<h3><i class='fa fa-save'></i> Cadastre aqui os seus dados</h3>";
	echo "<div class='form-box' id='login-box'>";
		echo "<div class='header'>Informações Básicas</div>";
		studentBasicInfoForm("student/student/saveBasicInfo", $hidden);
}

echo "</div>";