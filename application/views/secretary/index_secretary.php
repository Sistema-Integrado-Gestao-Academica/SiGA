<?php  $session = $this->session->userdata("current_user");?>
<h2 align="center">Bem vindo à página de secretarias</h2>

<h3>Bem vindo secretário <?=ucfirst($session['user']['name'])?></h3>
<br><br>
<h4 align='center'> As secretarias designadas a você são: </h4>
