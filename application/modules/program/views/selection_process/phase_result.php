<br>
<br>
<br>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Resultado da fase de <b><?=$phaseName?></b></h3>
    </div>
    <div class="box-body">
        <ul class="list-unstyled">
        	<h4><center> Candidatos aprovados </center></h4>
        	<?php
        	foreach ($candidates->candidates as $candidateId) {
    			echo "<h4><li><center><b>{$candidateId}</b></center></li></h4>";
        	}?>
        </ul>
    </div><!-- /.box-body -->
    <div class="box-footer">
    </div><!-- /.box-footer-->
</div>

<script>
    $(document).ready(function(){
        window.print();
    });
</script>