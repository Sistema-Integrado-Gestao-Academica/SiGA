<h2 class="principal">Vincular docentes ao processo seletivo </h2>

<?php buildTableDeclaration(); ?>
<?php
buildTableHeaders([
    'Nome',
    'E-mail',
    'Programa',
    'Ações'
]);
?>

<?php if(!empty($teachers)): ?>
    <?php foreach ($teachers as $teacher): ?>

    <tr>
        <td><?= $teacher['name'] ?></td>
        <td><?= $teacher['email'] ?></td>
        <td><?= $teacher['program_name'] ?></td>
        <td>
            <?= anchor('selection_process/define_teacher/', "<i class='fa fa-plus'></i> Vincular docente", "class='btn btn-primary'") ?>
        </td>
    </tr>

    <?php endforeach ?>
<?php else: ?>
    <?= callout('info', 'Nenhum docente cadastrado nos cursos deste programa.') ?>
<?php endif ?>

<?php buildTableEndDeclaration(); ?>

