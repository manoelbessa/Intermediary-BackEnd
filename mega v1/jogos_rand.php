<?php
// números que não aparecem a 6 jogos.
// Configurações do banco de dados
$host = 'localhost'; // Endereço do servidor MySQL
$db = 'mega'; // Nome do banco de dados
$user = 'bessa'; // Nome de usuário do banco de dados
$pass = 'pegasus'; // Senha do banco de dados

// Criação da conexão
$conn = new mysqli($host, $user, $pass, $db);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta para obter os últimos 6 concursos
$sql = "SELECT numero_1, numero_2, numero_3, numero_4, numero_5, numero_6 
        FROM megasena_resultados 
        ORDER BY concurso DESC 
        LIMIT 6";
$result = $conn->query($sql);

// Array para armazenar os números sorteados
$sorteados = [];

// Processa os resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        for ($i = 1; $i <= 6; $i++) {
            $sorteados[] = $row["numero_$i"];
        }
    }
}

// Define o intervalo de números da Mega-Sena
$numerosMegaSena = range(1, 60);

// Filtra os números que não foram sorteados
$naoSorteados = array_diff($numerosMegaSena, $sorteados);

// Exibe os números que não saíram
echo "<h2>Números que não saíram nos últimos 6 concursos:</h2>";
echo "<ul>";
foreach ($naoSorteados as $numero) {
    echo "<li>Número $numero</li>";
}
echo "</ul>";

// Função para gerar jogos aleatórios
function gerarJogosAleatorios($numerosDisponiveis, $qtdJogos, $qtdNumerosPorJogo) {
    $jogos = [];

    for ($i = 0; $i < $qtdJogos; $i++) {
        // Embaralha os números disponíveis
        shuffle($numerosDisponiveis);

        // Seleciona os primeiros $qtdNumerosPorJogo números embaralhados
        $jogos[] = array_slice($numerosDisponiveis, 0, $qtdNumerosPorJogo);
    }

    return $jogos;
}

// Gera 8 jogos com 5 números cada
$jogosAleatorios = gerarJogosAleatorios($naoSorteados, 8, 5);

// Exibe os jogos gerados
echo "<h2>Jogos Gerados Aleatoriamente:</h2>";
echo "<ul>";
foreach ($jogosAleatorios as $indice => $jogo) {
    echo "<li>Jogo " . ($indice + 1) . ": " . implode(', ', $jogo) . "</li>";
}
echo "</ul>";

// Fecha a conexão
$conn->close();
?>
