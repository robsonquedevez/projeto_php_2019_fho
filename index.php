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
					<form method="POST">
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<input type="text" name="aluno" class="form-control" placeholder="RA Aluno" required="" autofocus="" tabindex="1">
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
									<input type="number" name="p1" class="form-control" placeholder="Nota P1" required="" max="10" min="0" tabindex="2">
								</div>
								<div class="col-md-4">
									<label>Ma1</label>
									<input type="number" name="ma1" class="form-control" placeholder="Nota Ma1" required="" max="10" min="0" tabindex="3">
								</div>
								<div class="col-md-4">
									<label>Mb1</label>
									<input type="number" name="mb1" class="form-control" placeholder="Nota Mb1" required="" max="10" min="0" tabindex="4">
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<label>P2</label>
									<input type="number" name="p2" class="form-control" placeholder="Nota P2" required="" max="10" min="0" tabindex="5">
								</div>
								<div class="col-md-4">
									<label>Ma2</label>
									<input type="number" name="ma2" class="form-control" placeholder="Nota Ma2" required="" max="10" min="0" tabindex="6">
								</div>
								<div class="col-md-4">
									<label>Mb2</label>
									<input type="number" name="mb2" class="form-control" placeholder="Nota Mb2" required="" max="10" min="0" tabindex="7">
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
									<input type="number" name="faltas" class="form-control" required="" tabindex="8">
								</div>
								<div class="col-md-2">
									<label id="qtd_aulas">Qtd Aulas</label>									
								</div>
								<div class="col-md-4">
									<input type="number" name="qtd_aulas" class="form-control" required="" min="2" tabindex="9">
								</div>								
							</div>						
						</div>
						<div class="card-footer">
							<button class="btn btn-danger" type="reset">Limpar</button>
							<button class="btn btn-success" type="submit" tabindex="10">Salvar</button>
						</div>
					</form>					
				</div>
			</div>
			<?php
				// Inicia sessão do navegador
				session_start(); 

				// Elimina variável de sessão 
				if (isset($_GET['reset'])? $_GET['reset'] : false) {
					session_destroy();
					header("Location: index.php");
				}

				// Verifica se foi passado os valores via GET
				$aluno = isset($_POST['aluno']) ? (int)$_POST['aluno'] : NULL;
				$p1 = isset($_POST['p1']) ? $_POST['p1'] : NULL;
				$ma1 = isset($_POST['ma1']) ? $_POST['ma1'] : NULL;
				$mb1 = isset($_POST['mb1']) ? $_POST['mb1'] : NULL;
				$p2 = isset($_POST['p2']) ? $_POST['p2'] : NULL;
				$ma2 = isset($_POST['ma2']) ? $_POST['ma2'] : NULL;
				$mb2 = isset($_POST['mb2']) ? $_POST['mb2'] : NULL;
				$faltas = isset($_POST['faltas']) ? $_POST['faltas'] : NULL;
				$qtdAulas = isset($_POST['qtd_aulas']) ? $_POST['qtd_aulas'] : NULL;

				// Cria array para armazenar os registros inseridos
				$alunos = array();

				// Verifica se existe a variável de sessão 'bd'
				if (isset($_SESSION['bd'])) {					
					$alunos = $_SESSION['bd']; // Carrega array 'alunos' com conteúdo da 'bd'
				}

				// Verifica se existe a variálvel de sessão 'remove'
				if (isset($_GET['remove'])) {
					$aux = array(); // Cria array 'aux' auxiliar a remoção
					foreach ($alunos as $key => $value) { // Passa por todos os indices do array				
						if ($key != $_GET['remove']) { // Ignora o indice passado pela variável 'remove'
							array_push($aux, $alunos[$key]); // Inseri o conteúdo do array 'alunos' no array 'aux'
						}
					}
					$alunos = $aux;// Carrega o array 'alunos'
					header("Location: index.php"); // Carrega página index.php para limpar passagens GET
				}

				if ($aluno != NULL) { // Verifica se a variável 'aluno' tem conteúdo

					// Para cada registro do array "alunos" verifica se já está inserido o RA
					foreach ($alunos as $key => $value) { 						
						if ($aluno === $value['aluno']) {
							header("Location: index.php");
							exit;
						}
					}
					// Função para calcular a % de falta - retorna um inteiro
					function calculaFalta($faltas, $qtdAulas) {
						$result = ($faltas*100)/$qtdAulas;					
						return round(100 - $result, 0);
					}				
					// Função para calcular a médio - retorna um array com a nota 1, nota 2 e média
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
					// Função para validar o status do aluno - retorna um array com status e stilo para aplicar na coluna 
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

					$resultFaltas = (int)calculaFalta($faltas, $qtdAulas); // Chama e recebe a função calculaFalta

					$resultMedia = calculaMedia($p1, $ma1, $mb1, $p2, $ma2, $mb2); // Chama e recebe a função calculaMedia

					$resultStatus = statusAluno($resultMedia['media'], $resultFaltas);	// Chama a recebe a função statusAluno
					
					// Inserir os resultados no array 'alunos'
					array_push($alunos, [
						"aluno" => (int)$aluno, 
						"faltas" => (int)$faltas, 
						"status" => $resultStatus['status'], 
						"estilo" => $resultStatus['estilo'], 
						"nota1" => $resultMedia['nota1'], 
						"nota2" => $resultMedia['nota2'], 
						"media" => $resultMedia['media']
						]
					);					
				}				
				$_SESSION['bd'] = $alunos; // Variável de sessão 'bd' recebe array 'alunos'
				//unset($_POST[]);
				//$aluno = NULL; // reseta variável 'aluno' para não repetir o insert se recarregar a página
			?>
			<div class="col-md-8">
				<table class="table table-hover">
					<thead>
						<tr>
							<th></th>
							<th>RA</th>
							<th>Nota 1</th>
							<th>Nota 2</th>
							<th>Média</th>
							<th>Faltas</th>
							<th class="text-center">Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
							// Preenhimento da Tabela
							foreach ($alunos as $key => $value) {
								?>
								<tr>
									<td>									
										<a class="btn btn-danger" id="btn-remover" href="index.php?remove=<?php echo $key; ?>"><ion-icon name="trash"></ion-icon></a>					
									</td>
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
				<hr>
				<a href="index.php?reset=true" class="btn btn-warning">Reiniciar</a>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://unpkg.com/ionicons@4.5.5/dist/ionicons.js"></script>
</body>
</html>