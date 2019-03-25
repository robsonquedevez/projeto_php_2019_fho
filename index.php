	<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<title>Projeto 1 - Paradigma</title>
 	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<h1>Calcular média</h1>
				<div class="card border-info">
					<div class="card-header bg-info">
						<h3>Inserir</h3>
					</div>
					<form >
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<input type="text" name="nome_aluno" class="form-control" placeholder="Nome Aluno" required="">
								</div>			
							</div>
							<div class="row">
								<div class="col-md-12">
									<hr>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>P1</label>
									<input type="number" name="p1" class="form-control" placeholder="Nota P1" required="">
								</div>
								<div class="col-md-4">
									<label>Ma1</label>
									<input type="number" name="ma1" class="form-control" placeholder="Nota Ma1" required="">
								</div>
								<div class="col-md-4">
									<label>Mb1</label>
									<input type="number" name="mb1" class="form-control" placeholder="Nota Mb1" required="">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>P2</label>
									<input type="number" name="p2" class="form-control" placeholder="Nota P2" required="">
								</div>
								<div class="col-md-4">
									<label>Ma2</label>
									<input type="number" name="ma2" class="form-control" placeholder="Nota Ma2" required="">
								</div>
								<div class="col-md-4">
									<label>Mb2</label>
									<input type="number" name="mb2" class="form-control" placeholder="Nota Mb2" required="">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<hr>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label id="faltas">Faltas</label>									
								</div>
								<div class="col-md-4">
									<input type="number" name="faltas" class="form-control" required="">
								</div>
								
							</div>						
						</div>
						<div class="card-footer">
							<button class="btn btn-danger" type="reset">Limpar</button>
							<button class="btn btn-success" type="submit">Salvar</button>
						</div>
					</form>					
				</div>
			</div>
			<?php
				session_start();

				$aluno = isset($_GET['nome_aluno']) ? $_GET['nome_aluno'] : NULL;
				$p1 = isset($_GET['p1']) ? $_GET['p1'] : NULL;
				$ma1 = isset($_GET['ma1']) ? $_GET['ma1'] : NULL;
				$mb1 = isset($_GET['mb1']) ? $_GET['mb1'] : NULL;
				$p2 = isset($_GET['p2']) ? $_GET['p2'] : NULL;
				$ma2 = isset($_GET['ma2']) ? $_GET['ma2'] : NULL;
				$mb2 = isset($_GET['mb2']) ? $_GET['mb2'] : NULL;
				$faltas = isset($_GET['faltas']) ? $_GET['faltas'] : NULL;
				$alunos = array();

				if (isset($_SESSION['bd'])) {					
					$alunos = $_SESSION['bd'];
				}

				if ($aluno != NULL) {					
					function calculaFalta($faltas) {
						$result = ($faltas*100)/76;					
						return 100 - $result;
					}				

					function calculaMedia($p1, $ma1, $mb1, $p2, $ma2, $mb2){

						$soma1 = (($p1*0.70) + ($ma1*0.20) + ($mb1*0.10));
						$soma2 = (($p2*0.70) + ($ma2*0.20) + ($mb2*0.10));

						$mediaFinal = (int)($soma1 + ($soma2*2))/3;

						return array(
							'nota1' => (float)substr($soma1, 0, 3), 
							'nota2' => (float)substr($soma2, 0, 3), 
							'media' => (float)substr($mediaFinal, 0, 3)
						);
					}

					function statusAluno($media, $faltas){
						if ($faltas >= 75) {
							if ($media >= 5) {
									return array (
									"status" => "Aprovado",
									"estilo" => "table-success"
								);
							}
							elseif($media >= 3 && $media <= 5){
									return array (
									"status" => "Recuperação",
									"estilo" => "table-warning"
								);
							}
							else {
								return array (
									"status" => "Reprovado",
									"estilo" => "table-danger"
								);
							}
						}else {
							return array (
								"status" => "Reprovado por falta",
								"estilo" => "table-danger"
							);
						}
					}

					$resultFaltas = (int)calculaFalta($faltas);

					$resultMedia = calculaMedia($p1, $ma1, $mb1, $p2, $ma2, $mb2);

					$resultStatus = statusAluno($resultMedia['media'], $resultFaltas);	
					
					array_push($alunos, [
						"aluno" => $aluno, 
						"faltas" => $faltas, 
						"status" => $resultStatus['status'], 
						"estilo" => $resultStatus['estilo'], 
						"nota1" => $resultMedia['nota1'], 
						"nota2" => $resultMedia['nota2'], 
						"media" => $resultMedia['media']
						]
					);

					$_SESSION['bd'] = $alunos;
				}
			?>
			<div class="col-md-8">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Aluno</th>
							<th>Nota1</th>
							<th>Nota2</th>
							<th>Média</th>
							<th>Faltas</th>
							<th class="text-center">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($alunos as $key => $value) {
								?>
								<tr>
									<td>
									<?php
									echo $value['aluno'];
									?>
									</td>
									<td>
									<?php
									echo $value['nota1'];
									?>
									</td>
									<td>
									<?php
									echo $value['nota2'];
									?>
									</td>
									<td>
									<?php
									echo $value['media'];
									?>
									</td>
									<td>
									<?php
									echo $value['faltas'];
									?>
									</td>
									<td class="<?php echo $value['estilo'];?> text-center">
									<?php
									echo $value['status'];
									?>
									</td>
								</tr>
								<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>