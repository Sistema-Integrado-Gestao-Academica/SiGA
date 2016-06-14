<h2 class="principal">Natureza de Despesa: <b><?= $expenseType['description'] ?></b></h2>

<div class="form-box"> 
	<div class="header">
		Editar 
	</div>
		<?= form_open("update_expense_nature/{$expenseType['id']}") ?>
    	<div class="body bg-gray">
        <div class="form-group">
            <?= form_label("Código", "code") ?>
            <?= form_input(array(
                "name" => "code",
                "id" => "code",
                "type" => "text",
                "class" => "form-campo",
                "maxlength" => "50",
                "class" => "form-control",
                "value" => set_value("code", $expenseType['code'])
            )) ?>
            <?= form_error("code") ?>
            
			<?= form_label("Descrição da despesa", "description") ?>
			<?= form_input(array(
				"name" => "description",
				"id" => "description",
                "type" => "text",
				"class" => "form-campo",
				"class" => "form-control",
				"maxlength" => "255",
                "value" => set_value("description", $expenseType['description'])
			)) ?>
			<?= form_error("description") ?>
            <?= form_hidden("old_code", $expenseType['code']) ?>
        </div>
		</div>
    <div class="footer">
        <?= form_button(array(
            "class" => "btn bg-olive btn-block",
            "content" => "Salvar",
            "type" => "submit"
        )) ?>
    </div>
	<?= form_close() ?>
</div>