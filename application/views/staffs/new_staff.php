<h2 class="principal">Funcionários</h2>

<table class="table table-striped table-bordered">
	<tr>
		<td><h3 class="text-center">Funcionários cadastrados</h3></td>
		<?php if ($staffs){ ?>
			<td><h3 class="text-center">Ações</h3></td>
		<?php } ?>
	</tr>

	<?php if ($staffs !== FALSE){ ?>
		<?php foreach ($staffs as $staffId => $staff){ ?>
			<tr>
				<td><?=$staff['name']?></td>

				<td>
					<?= anchor("staffs/{$staffId}", "<span class='glyphicon glyphicon-edit'></span>", "class='btn btn-primary btn-editar btn-sm'") ?>

					<?= form_open('program/staff/remove') ?>
						<?= form_hidden(array('staff_id'=> $staffId, 'id_user'=>$staff['id'])) ?>
						<button type="submit" class="btn btn-danger btn-remover btn-sm" style="margin: -20px auto auto 100px;">
							<span class="glyphicon glyphicon-remove"></span>
						</button>
					<?= form_close() ?>
				</td>
			</tr>
		<?php } ?>
	<?php }else{ ?>
		<tr>
			<td><h3><label class="label label-default"> Não existem funcionários cadastrados</label></h3></td>
		</tr>
	<?php } ?>
</table>

<?php
	loadStaffRegistrationForm($guestUsers);
?>