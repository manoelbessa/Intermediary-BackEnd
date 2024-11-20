<?php

// Habilitar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Conexão com o banco de dados (ajuste as credenciais conforme necessário)
$host = 'localhost'; // Endereço do servidor MySQL
$database = 'mega'; // Nome do banco de dados
$user = 'bessa'; // Nome de usuário do banco de dados
$password = 'pegasus'; // Senha do banco de dados


$conn = new mysqli($host, $user, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Consulta para obter os 25 jogos mais recentes, ordenados do mais recente para o mais antigo
$sql = "SELECT * FROM megasena_resultados ORDER BY concurso DESC LIMIT 50";
$result = $conn->query($sql);

// Verificar se a consulta trouxe resultados
if ($result->num_rows > 0) {
    $jogos = [];

    // Armazenar os resultados em um array
    while ($row = $result->fetch_assoc()) {
        $jogos[] = [
            'concurso' => $row['concurso'],
            'numeros' => [
                $row['numero_1'],
                $row['numero_2'],
                $row['numero_3'],
                $row['numero_4'],
                $row['numero_5'],
                $row['numero_6']
            ]
        ];
    }

    // Comparar os jogos para encontrar números repetidos
    for ($i = 0; $i < count($jogos) - 1; $i++) {
        $jogoAtual = $jogos[$i];
        $jogoAnterior = $jogos[$i + 1];

        // Encontrar números repetidos entre o jogo atual e o jogo anterior
        $numerosRepetidos = array_intersect($jogoAtual['numeros'], $jogoAnterior['numeros']);

        // Se houver números repetidos, exibi-los
        if (!empty($numerosRepetidos)) {
            echo $jogoAnterior['concurso'] . " -> " . $jogoAtual['concurso'] . ": " . implode(', ', $numerosRepetidos) . "<br>";
        }
    }
} else {
    echo "Nenhum resultado encontrado.";
}

$conn->close();


?>
