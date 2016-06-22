 <div class="form-group">
            <?= form_label("Código", "code") ?>
            <?= form_input(array(
                "name" => "code",
                "id" => "code",
                "type" => "text",
                "class" => "form-campo",
                "maxlength" => "50",
                "class" => "form-control",
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
			)) ?>
			<?= form_error("description") ?>
        </div>
		</div>
        <div class="footer">
            <?= form_button(array(
                "id" => "new_expense_nature",
                "class" => "btn bg-olive btn-block",
                "content" => "Salvar",
                "type" => "submit"
            )) ?>
        </div>
	<?= form_close() ?>