<?= "<h4><i class='fa fa-list'></i> Docentes do programa:</h4>" ?>

<?php if(!empty($teachers)): ?>

    <?php buildTableDeclaration("add_teachers_to_process_table");

    buildTableHeaders([
        'Nome',
        'E-mail',
        'Ações'
    ]);
    ?>

    <?php foreach ($teachers as $teacher): ?>

    <tr>
        <td><?= $teacher['name'] ?></td>
        <td><?= $teacher['email'] ?></td>
        <td>
            <?php if(!$processTeachers || !in_array($teacher, $processTeachers)): ?>
            <?= anchor(
                "#",
                "<i class='fa fa-plus'></i> Vincular docente",
                "class='btn btn-primary' onClick='addTeacherToProcess(event, {$processId}, {$teacher['id']}, {$programId});'"
            ) ?>
            <?php else: ?>
                <?= "<span class='label label-success'>Docente vinculado!</span>" ?>
            <?php endif; ?>
        </td>
    </tr>

    <?php endforeach ?>

    <?php buildTableEndDeclaration(); ?>
<?php else: ?>
    <?= callout('info', 'Nenhum docente cadastrado nos cursos deste programa.') ?>
<?php endif ?>
