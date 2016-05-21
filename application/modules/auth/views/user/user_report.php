
<br>
<?php 
	echo "<h3>Lista de Usuários:</h3>";
	echo "<br>";

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código',
		'Nome',
		'CPF',
		'E-mail',
		'Ações'
	));

    if($allUsers !== FALSE){

	    foreach($allUsers as $user){

	    	echo "<tr>";

		    	echo "<td>";
		    		echo $user['id'];
		    	echo "</td>";

		    	echo "<td>";
		    		echo $user['name'];
		    	echo "</td>";

		    	echo "<td>";
		    		echo $user['cpf'];
		    	echo "</td>";

		    	echo "<td>";
		    	 	echo $user['email'];
		    	echo "</td>";

		    	echo "<td>";
		    		echo anchor("auth/userController/manageGroups/{$user['id']}", "<i class='fa fa-group'></i> Gerenciar Grupos", "class='btn btn-primary'");
		    	echo "</td>";

	    	echo "</tr>";
	    }

    }else{

    	echo "<tr>";
    	echo "<td colspan=5>";
    		callout("warning", "Não há usuários cadastradas no momento.");
    	echo "</td>";
		echo "</tr>";
    }

	buildTableEndDeclaration();	

	echo "<br>";
	
	echo "<h3>Grupos Cadastrados:</h3>";
	echo "<br>";

	buildTableDeclaration();

	buildTableHeaders(array(
		'Grupo',
		'Ações'
	));

    if($allGroups !== FALSE){

	    foreach($allGroups as $idGroup => $groupName){

	    	echo "<tr>";

		    	echo "<td>";
		    		echo $groupName;
		    	echo "</td>";

		    	echo "<td>";
		    		echo anchor("auth/userController/listUsersOfGroup/{$idGroup}", "<i class='fa fa-list-ol'></i> Listar usuários", "class='btn btn-primary' style='margin-right:5%;'");
		    		echo anchor("auth/userController/removeAllUsersOfGroup/{$idGroup}", "<i class='fa fa-eraser'></i> Remover todos usuários do grupo", "class='btn btn-danger'");
		    	echo "</td>";

	    	echo "</tr>";
	    }

    }else{

    	echo "<tr>";
    	echo "<td colspan=2>";
    		callout("warning", "Não há grupos cadastrados no sistema no momento.");
    	echo "</td>";
		echo "</tr>";
    }

	buildTableEndDeclaration();?>