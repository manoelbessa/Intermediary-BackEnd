<?php
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

// Passo 1: Obter os últimos 40 concursos
$sqlUltimosConcursos = "SELECT concurso FROM megasena_resultados ORDER BY concurso DESC LIMIT 40";
$stmtConcursos = $pdo->query($sqlUltimosConcursos);
$ultimosConcursos = $stmtConcursos->fetchAll(PDO::FETCH_COLUMN);

if (empty($ultimosConcursos)) {
    die("Nenhum concurso encontrado.");
}

// Transformar o array de concursos em uma lista separada por vírgula
$concursosStr = implode(',', $ultimosConcursos);

// Passo 2: Recuperar todos os números sorteados nos últimos 40 concursos
$sqlNumerosSorteados = "
    SELECT DISTINCT numero FROM (
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
    ) AS numeros_sorteados
";
$stmtNumerosSorteados = $pdo->query($sqlNumerosSorteados);
$numerosSorteados = $stmtNumerosSorteados->fetchAll(PDO::FETCH_COLUMN);

// Criar um array de números de 1 a 60
$todosNumeros = range(1, 60);

// Passo 3: Encontrar os números que não foram sorteados nos últimos 40 concursos
$numerosAtrasados = array_diff($todosNumeros, $numerosSorteados);

// Exibir os números mais atrasados
echo "Números que não foram sorteados nos últimos 40 concursos:<br>";
foreach ($numerosAtrasados as $numero) {
    echo $numero . "<br>";
}
?>
