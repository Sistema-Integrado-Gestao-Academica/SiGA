<? php require_once(MODULESPATH."/finantial/constants/ExpenseNatureConstants.php"); 
?>

<h2 class="principal">Adicionar uma despesa</h2>

<div class="row">

<div class="form-box">
	<div class="header">Adicionar despesa</div>

	<div class="body bg-gray">
		<?= form_open("save_expense") ?>
			<?= form_hidden("budgetplan_id", $budgetplan['id']) ?>
			<?= form_hidden("continue", "ok") ?>

			<div class="form-group">
				<?= form_label("Saldo disponível", "balance") ?>
				<?= form_input(array(
					"name" => "balance",
					"id" => "balance",
					"type" => "number",
					"class" => "form-campo",
					"value" => $budgetplan['balance'],
					"readonly" => "readonly"
				)) ?>
			</div>

			<div class="form-group">
				<?= form_label("Valor", "value") ?>
				<?= form_input(array(
					"name" => "value",
					"id" => "value",
					"type" => "number",
					"step" => 0.01, 
					"class" => "form-campo",
					"required" => "required"
				)) ?>
			</div>

			<div class="form-group">
				<?= form_label("Natureza da despesa  ", "nature") ?>
				 <a href="#myModal" data-toggle="modal">  <i class="fa fa-plus-circle"></i></a>	
				<?= form_dropdown('type', $types, "", ['class' => "form-control", 'id' => "types"]) ?>

				    <!-- Modal HTML -->
			    <div id="myModal" class="modal fade">
			        <div class="modal-dialog">
			            <div class="modal-content">
			                <div class="modal-header">
			                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			                    <h4 class="modal-title">
			                    	Nova Natureza de Despesa
			                    </h4>
			                </div>
			                <div class="modal-body">
			                    <?php include MODULESPATH."finantial/views/expense/_form_expense_nature.php";?>
			                    <div id="alert-msg">
			                    <p class="text-warning"><medium>Não se esqueça de clicar em salvar para que a nova natureza de despesa seja criada.</medium></p>
			                </div>
			                <div class="modal-footer">
			                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>



			<div class="form-group">
				<?= form_label("Ano", "year") ?>
				<?= form_input(array(
					"name" => "year",
					"id" => "year",
					"type" => "number",
					"class" => "form-campo",
					"value" => getCurrentYear()
				)) ?>
			</div>

			<div class="form-group">
				<?= form_label("Mês da liberação", "month") ?><br>
				<?= form_dropdown('month', $months, 'class="form-control"') ?>
			</div>

			<div class="footer body bg-gray">
				<div class="row">
					<div class="col-xs-6">
						<?= form_button(array(
							"class" => "btn bg-olive btn-block",
							"type" => "sumbit",
							"content" => "Salvar",
							"onclick" => "confirmation()"
						)) ?>
					</div>
					<div class="col-xs-6">
						<?= anchor("budgetplan/{$budgetplan['id']}", 'Voltar', "class='btn bg-olive btn-block'") ?>
					</div>
				</div>
			</div>
		<?= form_close() ?>
	</div>
</div>

</div>

<script>
	function confirmation() {
		var value = parseInt(document.getElementById("value").value);
		var balance = parseInt(document.getElementById("balance").value);

		if (value >= balance) {
			if (!confirm("Atenção! Este plano não tem saldo suficiente para esta despesa. Deseja continuar?")) {
				document.getElementsByName("continue")[0].value = "";
			}
		}
	}

	$('#new_expense_nature').click(function() {
    var form_data = {
        code: $('#code').val(),
        description: $('#description').val(),
    };
    $.ajax({
        url: "<?php echo site_url('finantial/expense/newExpenseNatureFromModal'); ?>",
        type: 'POST',
        data: form_data,
        success: function(data) {
        	var expense_nature = JSON.parse(data);
        	var status = expense_nature.status;
            $('#alert-msg').html(expense_nature.message);
            if(expense_nature.code == null){
            	expense_nature.code = "Sem código";
            }
        	if(status == "success"){	
	            $('#types').append($('<option>', {
			        value: expense_nature.id,
			        text: expense_nature.code + " - " + expense_nature.description
			    }));
        	}
        }
    });
    return false;
	});
</script>