<?php

// Habilitar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'mega';
$username = 'bessa';
$password = 'pegasus';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Passo 1: Obter os últimos 6 concursos
$sqlUltimosConcursos = "SELECT concurso FROM megasena_resultados ORDER BY concurso DESC LIMIT 6";
$stmtConcursos = $pdo->query($sqlUltimosConcursos);
$ultimosConcursos = $stmtConcursos->fetchAll(PDO::FETCH_COLUMN);

if (empty($ultimosConcursos)) {
    die("Nenhum concurso encontrado.");
}

// Transformar o array de concursos em uma lista separada por vírgula
$concursosStr = implode(',', $ultimosConcursos);

// Passo 2: Recuperar os números que não foram sorteados nos últimos 6 concursos
$sqlNaoSorteados = "
    SELECT DISTINCT numero
    FROM (
        SELECT numero_1 AS numero FROM megasena_resultados WHERE concurso IN ($concursosStr)
        UNION ALL
        SELECT numero_2 AS numero FROM megasena_resultados WHERE concurso IN ($concursosStr)
        UNION ALL
        SELECT numero_3 AS numero FROM megasena_resultados WHERE concurso IN ($concursosStr)
        UNION ALL
        SELECT numero_4 AS numero FROM megasena_resultados WHERE concurso IN ($concursosStr)
        UNION ALL
        SELECT numero_5 AS numero FROM megasena_resultados WHERE concurso IN ($concursosStr)
        UNION ALL
        SELECT numero_6 AS numero FROM megasena_resultados WHERE concurso IN ($concursosStr)
    ) AS ultimos_sorteados
    WHERE numero NOT IN (
        SELECT numero_1 FROM megasena_resultados WHERE concurso IN ($concursosStr)
        UNION ALL
        SELECT numero_2 FROM megasena_resultados WHERE concurso IN ($concursosStr)
        UNION ALL
        SELECT numero_3 FROM megasena_resultados WHERE concurso IN ($concursosStr)
        UNION ALL
        SELECT numero_4 FROM megasena_resultados WHERE concurso IN ($concursosStr)
        UNION ALL
        SELECT numero_5 FROM megasena_resultados WHERE concurso IN ($concursosStr)
        UNION ALL
        SELECT numero_6 FROM megasena_resultados WHERE concurso IN ($concursosStr)
    );
";
$stmtNaoSorteados = $pdo->query($sqlNaoSorteados);
$naoSorteados = $stmtNaoSorteados->fetchAll(PDO::FETCH_ASSOC);

// Exibir os números que não foram sorteados nos últimos 6 jogos
echo "Números que não foram sorteados nos últimos 6 jogos:<br>";
foreach ($naoSorteados as $numero) {
    echo $numero['numero'] . "<br>";
}
?>