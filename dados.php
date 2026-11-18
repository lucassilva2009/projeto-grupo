<?php
// Endpoint simples para calcular IMC a partir de um formulário POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['calcular'])) {
    http_response_code(400);
    exit('Envie peso e altura pelo formulário de cálculo.');
}

$peso = filter_var($_POST['peso'] ?? null, FILTER_VALIDATE_FLOAT);
$altura = filter_var($_POST['altura'] ?? null, FILTER_VALIDATE_FLOAT);

if ($peso === false || $altura === false || $peso <= 0 || $altura <= 0) {
    http_response_code(422);
    exit('Informe peso e altura válidos.');
}

$imc = $peso / ($altura * $altura);
$imcFormatado = number_format($imc, 2, ',', '.');

if ($imc < 18.5) {
    $classificacao = 'Abaixo do peso';
} elseif ($imc < 25) {
    $classificacao = 'Peso adequado';
} elseif ($imc < 30) {
    $classificacao = 'Sobrepeso';
} else {
    $classificacao = 'Obesidade';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Resultado do IMC - FitIA</title></head>
<body>
  <main>
    <h1>Resultado do IMC</h1>
    <p>Seu IMC: <strong><?= htmlspecialchars($imcFormatado, ENT_QUOTES, 'UTF-8') ?></strong></p>
    <p><?= htmlspecialchars($classificacao, ENT_QUOTES, 'UTF-8') ?></p>
    <p><a href="criacao%20de%20perfil.html">Voltar</a></p>
  </main>
</body>
</html>
