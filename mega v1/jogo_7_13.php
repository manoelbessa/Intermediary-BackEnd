<?php
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

// Consulta para obter os concursos do 7º ao 13º
$sql = "SELECT numero_1, numero_2, numero_3, numero_4, numero_5, numero_6 
        FROM megasena_resultados 
        ORDER BY concurso DESC 
        LIMIT 7 OFFSET 6"; // Pular os 6 primeiros e pegar do 7º ao 13º
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

// Gerar 10 jogos com 6 números aleatórios
echo "<h2>10 Jogos Aleatórios:</h2>";
echo "<ul>";
for ($j = 1; $j <= 10; $j++) {
    // Embaralha os números que não foram sorteados
    shuffle($naoSorteados);
    // Seleciona os 6 primeiros números para o jogo
    $jogo = array_slice($naoSorteados, 0, 6);
    sort($jogo); // Ordena os números para exibição
    echo "<li>Jogo $j: " . implode(", ", $jogo) . "</li>";
}
echo "</ul>";

// Fecha a conexão
$conn->close();
?>
