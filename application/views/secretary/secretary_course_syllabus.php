<?php  

require_once(APPPATH."/controllers/security/session/SessionManager.php");

$session = SessionManager::getInstance(); 
$user = $session->getUserData();

?>

<br>
<h4 align="center"><b>Currículos de cursos</b></h4>
<br>

<b>Semestre atual</b>
<h4><?=$current_semester['description']?></h4>
<br>

<?php 

	$userName = $user->getName();
	if($courses !== FALSE){
		
		echo "<h4>Cursos para o secretário <b>".$userName."</b>:</h4>";

		displayCourseSyllabus($syllabus);

	}else{
?>
		<div class="callout callout-warning">
            <h4>Nenhum curso cadastrado para o secretário <b><?php echo $userName;?></b>.<br><br>
            <small><b>OBS.: Você somente pode criar e alterar currículos dos cursos os quais é secretário.</b></small></h4>
        </div>

<?php } ?>

<?php echo anchor('secretary_home', "Voltar", "class='btn btn-primary'"); ?>
