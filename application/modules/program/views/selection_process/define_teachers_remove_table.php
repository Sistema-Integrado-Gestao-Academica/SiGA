<?= "<h4><i class='fa fa-plus-square'></i> Docentes adicionados ao processo:</h4>" ?>


<?php if(!empty($processTeachers)): ?>

    <?php buildTableDeclaration("teachers_added_to_process_table");
    buildTableHeaders([
        'Nome',
        'E-mail',
        'Ações'
    ]);
    ?>

    <?php foreach ($processTeachers as $teacher): ?>

    <tr>
        <td><?= $teacher['name'] ?></td>
        <td><?= $teacher['email'] ?></td>
        <td>
            <?= anchor(
                "#",
                "<i class='fa fa-minus'></i> Desvincular docente",
                "class='btn btn-danger' onClick='removeTeacherFromProcess(event, {$processId}, {$teacher['id']}, {$programId});'"
            ) ?>
        </td>
    </tr>

    <?php endforeach ?>

<?php buildTableEndDeclaration(); ?>

<?php else: ?>
    <?= callout('info', 'Nenhum docente vinculado a este processo.') ?>
<?php endif ?>