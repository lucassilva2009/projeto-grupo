<?php

session_start();

/* =========================================================
   VERIFICAR SE O PERFIL EXISTE
   ========================================================= */

if (!isset($_SESSION["perfil"]) || !is_array($_SESSION["perfil"])) {
    header("Location: criacao de perfil.html");
    exit;
}

$perfil = $_SESSION["perfil"];


/* =========================================================
   RECEBER DADOS DO PERFIL
   ========================================================= */

$nome = trim($perfil["nome"] ?? "");
$idade = (int)($perfil["idade"] ?? 0);
$sexo = trim($perfil["sexo"] ?? "");
$peso = (float)($perfil["peso"] ?? 0);
$altura = (float)($perfil["altura"] ?? 0);
$atividade = trim($perfil["atividade"] ?? "");
$objetivo = trim($perfil["objetivo"] ?? "");


/* =========================================================
   VALORES PADRÃO
   ========================================================= */

if ($nome === "") {
    $nome = "Usuário";
}


/* =========================================================
   PRIMEIRA LETRA DO NOME
   ========================================================= */

$primeiraLetra = function_exists("mb_substr")
    ? mb_substr($nome, 0, 1, "UTF-8")
    : substr($nome, 0, 1);

$primeiraLetra = function_exists("mb_strtoupper")
    ? mb_strtoupper($primeiraLetra, "UTF-8")
    : strtoupper($primeiraLetra);


/* =========================================================
   CÁLCULO DO IMC
   ========================================================= */

$imc = 0;

if ($peso > 0 && $altura > 0) {
    $imc = $peso / ($altura * $altura);
}

$imcFormatado = number_format($imc, 1, ",", ".");


/* =========================================================
   CLASSIFICAÇÃO DO IMC
   ========================================================= */

$classificacao = "Não calculado";

$mensagem = "Informe seu peso e sua altura para calcular o IMC.";

if ($imc > 0) {

    if ($imc < 18.5) {

        $classificacao = "Abaixo do peso";
        $mensagem = "O resultado está abaixo da faixa de referência para adultos.";

    } elseif ($imc < 25) {

        $classificacao = "Peso adequado";
        $mensagem = "O resultado está dentro da faixa de referência para adultos.";

    } elseif ($imc < 30) {

        $classificacao = "Sobrepeso";
        $mensagem = "O resultado está acima da faixa de referência para adultos.";

    } else {

        $classificacao = "Obesidade";
        $mensagem = "O resultado está acima da faixa de referência para adultos.";
    }
}


/* =========================================================
   OBJETIVO
   ========================================================= */

$objetivos = [
    "ganhar_massa" => "Ganhar massa muscular",
    "manter" => "Manter o peso",
    "condicionamento" => "Melhorar o condicionamento",
    "reduzir_peso" => "Reduzir peso"
];

$objetivoTexto = $objetivos[$objetivo] ?? "Não informado";


/* =========================================================
   POSIÇÃO DO MARCADOR
   ========================================================= */

$posicao = 0;

if ($imc > 0) {

    $posicao = (($imc - 10) / 30) * 100;

    if ($posicao < 0) {
        $posicao = 0;
    }

    if ($posicao > 100) {
        $posicao = 100;
    }
}


/* =========================================================
   ESCAPAR DADOS PARA HTML
   ========================================================= */

$nomeHTML = htmlspecialchars($nome, ENT_QUOTES, "UTF-8");
$primeiraLetraHTML = htmlspecialchars($primeiraLetra, ENT_QUOTES, "UTF-8");
$idadeHTML = htmlspecialchars((string)$idade, ENT_QUOTES, "UTF-8");
$objetivoHTML = htmlspecialchars($objetivoTexto, ENT_QUOTES, "UTF-8");
$classificacaoHTML = htmlspecialchars($classificacao, ENT_QUOTES, "UTF-8");
$mensagemHTML = htmlspecialchars($mensagem, ENT_QUOTES, "UTF-8");


/* =========================================================
   POSIÇÃO FORMATADA PARA O CSS
   ========================================================= */

$posicaoCSS = number_format($posicao, 2, ".", "");

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FitIA - Meu painel</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6fb;
            font-family: 'Montserrat', sans-serif;
            color: #202124;
        }


        /* =================================================
           HEADER
           ================================================= */

        header {
            width: 100%;
            height: 75px;

            background: white;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 7%;

            border-bottom: 1px solid #e8e8e8;
        }


        .logo {
            font-size: 30px;
            font-weight: 800;
            color: #2D31FA;
        }


        .perfil {
            display: flex;
            align-items: center;
            gap: 12px;
        }


        .avatar {
            width: 42px;
            height: 42px;

            border-radius: 50%;

            background: #2D31FA;
            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: 700;
        }


        /* =================================================
           CONTEÚDO
           ================================================= */

        main {
            width: 86%;
            max-width: 1200px;

            margin: 40px auto;
        }


        .boas-vindas h1 {
            margin: 0;
            font-size: 30px;
        }


        .boas-vindas p {
            color: #777;
            margin-top: 8px;
        }


        /* =================================================
           CARDS
           ================================================= */

        .cards {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 20px;

            margin-top: 30px;
        }


        .card {
            background: white;

            border-radius: 18px;

            padding: 25px;

            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }


        .card-titulo {
            color: #777;

            font-size: 14px;

            margin-bottom: 12px;
        }


        .numero {
            font-size: 38px;

            font-weight: 800;

            color: #2D31FA;
        }


        .unidade {
            color: #777;

            font-size: 14px;
        }


        /* =================================================
           CARD DO IMC
           ================================================= */

        .imc-card {
            margin-top: 20px;

            background: white;

            border-radius: 18px;

            padding: 30px;

            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }


        .imc-topo {
            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 20px;
        }


        .imc-topo h2 {
            margin: 0;

            font-size: 22px;
        }


        .imc-valor {
            font-size: 48px;

            font-weight: 800;

            color: #2D31FA;

            white-space: nowrap;
        }


        .classificacao {
            display: inline-block;

            margin-top: 10px;

            padding: 8px 15px;

            border-radius: 20px;

            background: #eef0ff;

            color: #2D31FA;

            font-weight: 700;

            font-size: 13px;
        }


        .mensagem {
            color: #666;

            line-height: 1.6;

            margin-top: 20px;
        }


        /* =================================================
           BARRA
           ================================================= */

        .barra {
            width: 100%;

            height: 14px;

            background: linear-gradient(
                to right,
                #5b8def 0%,
                #5b8def 25%,
                #54c878 25%,
                #54c878 50%,
                #f4c542 50%,
                #f4c542 75%,
                #e85d5d 75%,
                #e85d5d 100%
            );

            border-radius: 10px;

            margin-top: 25px;

            position: relative;
        }


        .marcador {
            position: absolute;

            top: -5px;

            left: <?php echo $posicaoCSS; ?>%;

            width: 24px;

            height: 24px;

            transform: translateX(-50%);

            background: #202124;

            border: 4px solid white;

            border-radius: 50%;

            box-shadow: 0 2px 8px rgba(0, 0, 0, .2);
        }


        .legenda {
            display: flex;

            justify-content: space-between;

            margin-top: 10px;

            font-size: 11px;

            color: #777;
        }


        /* =================================================
           OBJETIVO
           ================================================= */

        .objetivo {
            margin-top: 20px;

            background: #2D31FA;

            color: white;

            border-radius: 18px;

            padding: 30px;

            display: flex;

            justify-content: space-between;

            align-items: center;
        }


        .objetivo h2 {
            margin: 0 0 8px 0;
        }


        .objetivo p {
            margin: 0;

            opacity: .9;
        }


        /* =================================================
           TABLET / CELULAR
           ================================================= */

        @media (max-width: 800px) {

            .cards {
                grid-template-columns: 1fr;
            }


            .imc-topo {
                flex-direction: column;

                align-items: flex-start;
            }


            .objetivo {
                flex-direction: column;

                align-items: flex-start;

                gap: 20px;
            }


            header {
                padding: 0 5%;
            }


            main {
                width: 90%;
            }

        }


        @media (max-width: 500px) {

            header {
                height: 65px;
            }


            .logo {
                font-size: 24px;
            }


            .perfil strong {
                display: none;
            }


            .boas-vindas h1 {
                font-size: 24px;
            }


            .imc-card {
                padding: 20px;
            }


            .imc-topo h2 {
                font-size: 18px;
            }


            .imc-valor {
                font-size: 40px;
            }

        }

    </style>

</head>


<body>


<header>

    <div class="logo">
        FitIA
    </div>


    <div class="perfil">

        <div class="avatar">
            <?php echo $primeiraLetraHTML; ?>
        </div>

        <strong>
            <?php echo $nomeHTML; ?>
        </strong>

    </div>

</header>


<main>


    <!-- =================================================
         BOAS-VINDAS
         ================================================= -->

    <section class="boas-vindas">

        <h1>
            Olá, <?php echo $nomeHTML; ?>!
        </h1>

        <p>
            Confira seus dados e acompanhe seu progresso.
        </p>

    </section>


    <!-- =================================================
         DADOS
         ================================================= -->

    <section class="cards">


        <!-- PESO -->

        <div class="card">

            <div class="card-titulo">
                Peso atual
            </div>

            <div class="numero">
                <?php echo number_format($peso, 1, ",", "."); ?>
            </div>

            <span class="unidade">
                kg
            </span>

        </div>


        <!-- ALTURA -->

        <div class="card">

            <div class="card-titulo">
                Altura
            </div>

            <div class="numero">
                <?php echo number_format($altura, 2, ",", "."); ?>
            </div>

            <span class="unidade">
                metros
            </span>

        </div>


        <!-- IDADE -->

        <div class="card">

            <div class="card-titulo">
                Idade
            </div>

            <div class="numero">
                <?php echo $idadeHTML; ?>
            </div>

            <span class="unidade">
                anos
            </span>

        </div>


    </section>


    <!-- =================================================
         IMC
         ================================================= -->

    <section class="imc-card">

        <div class="imc-topo">


            <div>

                <h2>
                    Seu índice de massa corporal
                </h2>

                <span class="classificacao">
                    <?php echo $classificacaoHTML; ?>
                </span>

            </div>


            <div class="imc-valor">
                <?php echo $imcFormatado; ?>
            </div>


        </div>


        <p class="mensagem">
            <?php echo $mensagemHTML; ?>
        </p>


        <div class="barra">

            <div class="marcador"></div>

        </div>


        <div class="legenda">

            <span>Abaixo</span>

            <span>Adequado</span>

            <span>Sobrepeso</span>

            <span>Obesidade</span>

        </div>


    </section>


    <!-- =================================================
         OBJETIVO
         ================================================= -->

    <section class="objetivo">

        <div>

            <h2>
                Seu objetivo
            </h2>

            <p>
                <?php echo $objetivoHTML; ?>
            </p>

        </div>

    </section>


</main>


</body>

</html>