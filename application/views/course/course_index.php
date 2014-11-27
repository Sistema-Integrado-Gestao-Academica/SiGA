<h2 align="center">Menu de cursos</h2>

<?=anchor("course/formToRegisterNewCourse", "Cadastrar Curso", array(
	"class" => "btn btn-primary",
	"type" => "submit",
	"content" => "newCourse"
))?>

<?php echo "<br>";?>
<?php echo "<br>";?>

<?=anchor("course/formToEditCourse", "Editar Curso", array(
	"class" => "btn btn-primary",
	"type" => "submit",
	"content" => "editCourse"
))?>

<?php echo "<br>";?>
<?php echo "<br>";?>

<?=anchor("course/formToDeleteCourse", "Remover Curso", array(
	"class" => "btn btn-primary",
	"type" => "submit",
	"content" => "deleteCourse"
))?>