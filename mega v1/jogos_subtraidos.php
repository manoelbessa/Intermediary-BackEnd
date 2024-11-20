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
$sql = "SELECT * FROM megasena_resultados ORDER BY concurso DESC LIMIT 25";
$result = $conn->query($sql);

// Verificar se a consulta trouxe resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Obter os números do jogo
        $numeros = [
            $row['numero_1'],
            $row['numero_2'],
            $row['numero_3'],
            $row['numero_4'],
            $row['numero_5'],
            $row['numero_6']
        ];

        // Ordenar os números em ordem crescente
        sort($numeros);

        // Exibir o concurso e os números ordenados
        echo "Concurso: " . $row['concurso'] . " - Números ordenados: " . implode(', ', $numeros) . "<br>";

        // Calcular as subtrações do maior para o menor
        $subtracoes = [];
        for ($i = 5; $i > 0; $i--) {
            $subtracoes[] = $numeros[$i] - $numeros[$i - 1];
        }

        // Exibir as subtrações
        echo "Subtrações (maior -> menor): " . implode(', ', $subtracoes) . "<br><br>";
    }
} else {
    echo "Nenhum resultado encontrado.";
}

$conn->close();
?>
