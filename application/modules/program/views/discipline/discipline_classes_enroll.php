
<br>
<br>

<h3 align="center"> Turmas cadastradas para a disciplina <i><b><?php echo $disciplineData['discipline_name']; ?></b></i>:</h3>

<br>
<?php displayDisciplineClasses($disciplineClasses); ?>

<?php echo anchor("student/student_offerList/{$courseId}", "Voltar", "class='btn btn-danger'"); ?>
