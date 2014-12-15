<?php  $session = $this->session->userdata("usuario_logado");?>
<h2 align="center">Bem vindo à página de secretarias</h2>

<?php echo"<h3>Bem vindo secretário ".ucfirst($session['user']['name'])."</h3>";
	echo "<br>";
	echo "<br>";
	echo"<h4 align='center'> As secretarias designadas a você são: </h4>";
	
	?>