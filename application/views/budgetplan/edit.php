<h2 class="principal">Plano orçamentário</h2>

<div class="form-box-logged" id="login-box">
<div class="header">Cadastrar um novo P.O.</div>
		<?php form_open("budgetplan/update");
			  echo form_hidden("budgetplan_id", $budgetplan['id']);
			  echo form_hidden("confirm"); 
		?>
	<div class="body bg-gray">
		<div class="form-group">	
			<?php
				echo form_label("Curso", "course");
				echo form_dropdown('courses', $courses, $budgetplan['course_id']-1);
			?>
		</div>
		<div class="form-group">	
			<?php
				echo form_label("Montante", "amount");
				echo form_input(array(
					"name" => "amount",
					"id" => "amount",
					"type" => "number",
					"class" => "form-campo",
					"value" => $budgetplan['amount'],
					$disable_amount => $disable_amount
				));
			?>
		</div>
		<div class="form-group">	
			<?php
				echo form_label("Gasto", "spending");
				echo form_input(array(
					"name" => "spending",
					"id" => "spending",
					"type" => "number",
					"class" => "form-campo",
					"value" => $budgetplan['spending'],
					$disable_spending => $disable_spending
				));
			?>
		</div>
		<?php if ($budgetplan['status'] != 4) {?>
			<div class="form-group">	
				<?php
					echo form_label("Status", "status");
					echo "<br>";
					echo form_dropdown('status', $status, $budgetplan['status']-1, 'id="status"');
				?>
			</div>
			<div class="footer">	
			<?php	
			echo form_button(array(
					"class" => "btn bg-olive btn-block",
					"type" => "sumbit",
					"content" => "Salvar",
					"onclick" => "confirmation()"
			));
			?>
			</div>
		<?php }else{
				echo "<br><a href='".base_url('plano%20orcamentario')."' class='btn btn-primary'>Voltar</a>";
				}
				echo form_close();
			?>
	</div>
</div>

<script>
	function confirmation() {
		var status = document.getElementById("status").value;
		if (status == 2) {
			if (confirm("ATENÇÃO! Com status \"Em execução\", não será mais possível alterar o valor do montante.")) {
				document.getElementsByName("confirm")[0].value = false;
			}
		} else if (status == 3) {
			if (confirm("ATENÇÃO! Com status \"Finalizado\", não será mais possível fazer alterações.")) {
				document.getElementsByName("confirm")[0].value = false;
			}
		}
	}
</script>