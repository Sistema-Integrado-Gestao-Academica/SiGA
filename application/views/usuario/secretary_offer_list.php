<br>
<h4 align="left"><b>Lista de ofertas</b></h4>
<br>

<?=	form_open('semester/saveSemester') ?>
	<?= form_hidden('current_semester_id', $current_semester['id_semester']) ?>
	<?= form_hidden('password') ?>
	<?= form_label('Semestre corrente') ?>
	<h4><?=$current_semester['description']?></h4>
	<?php if ($isAdmin): ?>
		<?= form_button(array(
			'type' => 'password',
			'content' => 'Avançar semestre',
			'onClick' => "passwordRequest()"
		)) ?>
	<?php endif ?>
<?= form_close() ?>

<script>
	function passwordRequest() {
		var password = prompt("Digite sua senha para continuar")
		document.getElementsByName("password")[0].value = password;
	}
</script>