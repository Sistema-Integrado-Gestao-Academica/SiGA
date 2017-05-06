<?php if(!empty($teachers)): ?>

    <?php
    buildTableDeclaration(
        "add_teachers_to_process_box",
        "add_teachers_to_process_table",
        "<h4><i class='fa fa-list'></i> Docentes do programa</h4>"
    );

    buildTableHeaders([
        'Nome',
        'Ações'
    ]);
    ?>

    <?php foreach ($teachers as $teacher): ?>

    <tr>
        <td><?= $teacher['name'] ?></td>
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

<script>
    $(function() {
        $('#add_teachers_to_process_table').dataTable();
    });
</script>