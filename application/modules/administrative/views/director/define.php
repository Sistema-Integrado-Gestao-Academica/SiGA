<h2 class="principal"> Diretor(a) do departamento</h2>

<?php
	
	$currentDirectorId = NULL;
	if (!is_null($currentDirector)){
		echo "<h3>Diretor(a) atual: <b>".$currentDirector->name."</b></h3>";
		$currentDirectorId = $currentDirector->id;
	}
	
?>
<div class="row" align="center">
	<div class="col-lg-6 col-md-offset-3">
		<?= form_open("save_director") ?>
			<i class="fa fa-group"></i> <?= form_label("Lista de Docentes", "teachers_list") ?>
			<?= form_dropdown("new_director", $teachers, '', ['class' => "form-control", 'id' => "teachers"]) ?>
		 	<br>
		<div class="col-lg-7 col-md-offset-3">
			<?php 
			if(!is_null($currentDirector)){

				$footer = function(){
					echo "<div class='row'>";
					echo "<div class='col-lg-6'>";

		        		echo form_button(array(
						    "class" => "btn btn-danger btn-block",
						    "content" => "Cancelar",
						    "type" => "button",
						    "data-dismiss"=>'modal'
						));
					echo "</div>";
					echo "<div class='col-lg-6'>";
					 	echo form_button(array(
						    "class" => "btn bg-olive btn-block",
						    "content" => "Confirmar",
						    "type" => "submit"
						));
					echo "</div>";
					echo "</div>";
				};

				
				if($isDirector){
					$principalMessage = "Você irá perder todos os acessos às funcionalidades de Diretor. 
					Confirmar definição de novo diretor?";
				}
				else{
					$principalMessage = $currentDirector->name. " irá perder todos os acessos às funcionalidades de Diretor. Confirmar definição de novo diretor?";
				}
				$body = function() use ($principalMessage){
					echo $principalMessage;
				};
				newModal("defineDirectorModal", "Confirmar definição de novo diretor", $body, $footer);
				echo "<a href='#defineDirectorModal' data-toggle='modal' class='btn bg-olive'>Definir como novo(a) diretor(a)</a>";
			}
			else{
				echo form_button(array(
				    "class" => "btn bg-olive btn-block",
				    "content" => "Confirmar",
				    "type" => "submit"
				));
			}
			?>
		</div>
            <?= form_hidden("current_director", $currentDirectorId) ?>
		<?= form_close() ?>
	</div>
</div>