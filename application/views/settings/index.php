<h1 class="principal">Configurações</h1>

<?=	form_open('settings/saveSemester') ?>
	<?= form_hidden('current_semester_id', $current_semester['id_semester']) ?>
	<?= form_hidden('password') ?>
	<?= form_label('Semestre corrente') ?>
	<h4><?=$current_semester['description']?></h4>
	<?php if ($edit): ?>
		<?= form_button(array(
			'type' => 'password',
			'content' => 'Avançar semestre',
			'onClick' => "passwordRequest()"
		)) ?>
	<?php endif ?>
<?= form_close() ?>
