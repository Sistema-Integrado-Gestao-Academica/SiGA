<script src=<?=base_url("js/program.js")?>></script>

<h2 class="principal">Programas para o coordenador <i><?php echo $user['name']; ?></i> </h2>
<br>

<?php displayCoordinatorPrograms($coordinatorPrograms); ?>