<br>
<br>

<div class="col-lg-12 ">
	<div class='panel panel-primary'>
		 
		<div class='panel-heading'><h4>Relatórios disponíveis :</h4></div>
			<div class='panel-body'>
				<div class='modal-info'>
					<div class='modal-content'>
						<div class='modal-header bg-news'>
							<h4 class='model-title'>Discentes</h4>
						</div>
						<div class='modal-body'>
							<h4>
								Relatório geral de alunos do programa
								<?=anchor("program/coordinator/students_report", "Relatório de discentes", "class='btn btn-success'");?>		
							</h4>
						</div>
						<div class='modal-header bg-news'>
							<h4 class='model-title'>Docentes</h4>
						</div>
						<div class='modal-body'>
							<h4>
							Relatório geral de professores do programa
								<?=anchor("program/coordinator/mastermind_report", "Relatório de docentes", "class='btn btn-success'");?>								
							</h4>
						</div>
						<div class='modal-header bg-news'>
							<h4 class='model-title'>Funcionários</h4>
						</div>
						<div class='modal-body'>
							<h4>
							Relatório geral de secretários do programa
								<?=anchor("program/coordinator/secretary_report", "Relatório de Funcionários", "class='btn btn-success'");?>		
							</h4>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>