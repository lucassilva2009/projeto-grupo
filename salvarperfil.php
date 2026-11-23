<?php


if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: criacao de perfil.html");
    exit;
}

function limpar($valor) {
    return htmlspecialchars(trim($valor), ENT_QUOTES, "UTF-8");
}

$nome = isset($_POST["nome"]) ? trim($_POST["nome"]) : "";
$idade = isset($_POST["idade"]) ? intval($_POST["idade"]) : 0;
$sexo = isset($_POST["sexo"]) ? $_POST["sexo"] : "";
$peso = isset($_POST["peso"]) ? floatval($_POST["peso"]) : 0;
$altura = isset($_POST["altura"]) ? floatval($_POST["altura"]) : 0;
$atividade = isset($_POST["atividade"]) ? $_POST["atividade"] : "";
$objetivo = isset($_POST["objetivo"]) ? $_POST["objetivo"] : "";

if ($nome == "" || $idade < 1 || $peso <= 0 || $altura <= 0 || $altura > 2.5) {
    die("Dados inválidos. <a href='criacao de perfil.html'>Voltar ao formulário</a>");
}

$nomeHTML = limpar($nome);
$imc = $peso / ($altura * $altura);
$imcFormatado = number_format($imc, 1, ",", ".");

if ($idade < 18) {
    $classificacao = "Avaliação específica necessária";
    $mensagem = "Para menores de 18 anos, o IMC deve ser interpretado considerando idade e sexo.";
} elseif ($imc < 18.5) {
    $classificacao = "Abaixo do peso";
    $mensagem = "Resultado abaixo da faixa de referência para adultos.";
} elseif ($imc < 25) {
    $classificacao = "Peso adequado";
    $mensagem = "Resultado dentro da faixa de referência para adultos.";
} elseif ($imc < 30) {
    $classificacao = "Sobrepeso";
    $mensagem = "Resultado acima da faixa de referência para adultos.";
} else {
    $classificacao = "Obesidade";
    $mensagem = "Resultado acima da faixa de referência para adultos.";
}


if ($objetivo == "ganhar_massa") {
    $tituloExercicio = "Sugestões para ganho de massa muscular";
    $exercicios = array("Agachamento", "Supino ou flexão adaptada", "Remada", "Desenvolvimento de ombros", "Fortalecimento abdominal");
    $tituloAlimentacao = "Sugestões de alimentação";
    $alimentos = array("Ovos, frango, peixe ou outras fontes de proteína", "Arroz, batata e aveia", "Feijão e outras leguminosas", "Leite ou iogurte, se tolerados", "Frutas e vegetais variados");
} elseif ($objetivo == "reduzir_peso") {
    $tituloExercicio = "Sugestões para redução de peso";
    $exercicios = array("Caminhada", "Bicicleta ergométrica", "Agachamento", "Exercícios de fortalecimento muscular", "Atividades aeróbicas leves");
    $tituloAlimentacao = "Sugestões de alimentação equilibrada";
    $alimentos = array("Verduras e legumes variados", "Frutas inteiras", "Frango, peixe, ovos ou outras proteínas", "Feijão e cereais integrais", "Água no lugar de bebidas açucaradas");
} elseif ($objetivo == "condicionamento") {
    $tituloExercicio = "Sugestões para melhorar o condicionamento";
    $exercicios = array("Caminhada rápida", "Bicicleta", "Corrida leve, se apropriada", "Circuitos funcionais adaptados", "Exercícios de resistência");
    $tituloAlimentacao = "Sugestões de alimentação";
    $alimentos = array("Frutas variadas", "Arroz, aveia e batata", "Ovos, frango ou peixe", "Feijão e outras leguminosas", "Água ao longo do dia");
} else {
    $tituloExercicio = "Sugestões para manutenção";
    $exercicios = array("Caminhada", "Agachamento", "Flexões adaptadas", "Exercícios de fortalecimento", "Alongamentos");
    $tituloAlimentacao = "Sugestões de alimentação equilibrada";
    $alimentos = array("Frutas e verduras variadas", "Arroz e feijão", "Ovos, frango ou peixe", "Legumes e cereais integrais", "Água ao longo do dia");
}

if ($atividade == "sedentario") {
    $frequencia = "Sugestão: iniciar com atividades leves e curtas, aumentando gradualmente conforme a adaptação.";
} elseif ($atividade == "leve") {
    $frequencia = "Sugestão: manter uma rotina leve e regular, aumentando a duração aos poucos.";
} elseif ($atividade == "moderado") {
    $frequencia = "Sugestão: distribuir as atividades durante a semana e reservar tempo para recuperação.";
} else {
    $frequencia = "Sugestão: alternar sessões mais intensas com recuperação adequada.";
}

$objetivos = array(
    "ganhar_massa" => "Ganhar massa muscular",
    "manter" => "Manter o peso",
    "condicionamento" => "Melhorar o condicionamento",
    "reduzir_peso" => "Reduzir peso"
);
$objetivoTexto = isset($objetivos[$objetivo]) ? $objetivos[$objetivo] : "Não informado";

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FitCal - Meu resultado</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box}
body{margin:0;background:#f4f6fb;font-family:'Montserrat',sans-serif;color:#202124}
header{background:white;min-height:75px;display:flex;align-items:center;justify-content:space-between;padding:0 7%;border-bottom:1px solid #e8e8e8}
.logo{font-size:30px;font-weight:800;color:#2D31FA}
main{width:86%;max-width:1200px;margin:40px auto}
h1{font-size:28px;margin-bottom:8px}
.subtitulo{color:#777;margin-bottom:30px}
.card{background:white;padding:30px;border-radius:18px;margin-bottom:20px;box-shadow:0 5px 20px rgba(0,0,0,.05)}
h2{color:#2D31FA;margin-top:0}
.imc{font-size:48px;font-weight:800;color:#2D31FA;margin:15px 0}
.classificacao{display:inline-block;padding:8px 15px;border-radius:20px;background:#eef0ff;color:#2D31FA;font-weight:700;font-size:13px}
ul{line-height:2;padding-left:22px}
.dados{line-height:1.9;color:#555}
.aviso{font-size:12px;color:#777;line-height:1.6}
.botao{display:inline-block;background:#2D31FA;color:white;text-decoration:none;padding:14px 22px;border-radius:10px;font-weight:700}
@media(max-width:600px){main{width:90%}header{padding:0 5%}.card{padding:22px}}
</style>
</head>
<body>
<header><div class="logo">FitCal</div><strong>Meu painel</strong></header>
<main>
<h1>Olá, <?php echo $nomeHTML; ?>!</h1>
<p class="subtitulo">Confira seu resultado e sugestões iniciais.</p>

<section class="card">
<h2>Seu cálculo de IMC</h2>
<div class="imc"><?php echo $imcFormatado; ?></div>
<span class="classificacao"><?php echo limpar($classificacao); ?></span>
<p><?php echo limpar($mensagem); ?></p>
<div class="dados">
Peso: <?php echo number_format($peso, 1, ",", "."); ?> kg<br>
Altura: <?php echo number_format($altura, 2, ",", "."); ?> m<br>
Idade: <?php echo $idade; ?> anos<br>
Objetivo: <?php echo limpar($objetivoTexto); ?>
</div>
</section>

<section class="card">
<h2><?php echo limpar($tituloExercicio); ?></h2>
<ul>
<?php foreach ($exercicios as $exercicio) { ?>
<li><?php echo limpar($exercicio); ?></li>
<?php } ?>
</ul>
<p><?php echo limpar($frequencia); ?></p>
</section>

<section class="card">
<h2><?php echo limpar($tituloAlimentacao); ?></h2>
<ul>
<?php foreach ($alimentos as $alimento) { ?>
<li><?php echo limpar($alimento); ?></li>
<?php } ?>
</ul>
<p class="aviso">Estas são sugestões gerais para fins informativos. Não constituem prescrição de treino ou dieta. Procure profissionais de educação física e nutrição para orientações individuais. Fique atento as alimentações indicadas caso possua alguma alergia!</p>
</section>

<a href="criacao de perfil.html" class="botao">Refazer perfil</a>
</main>
</body>
</html>
