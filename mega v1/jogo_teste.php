<?php
// números que não aparecem a 6 jogos. 
// Configurações do banco de dados (utilizando variáveis de ambiente para maior segurança)
$host = 'localhost'; // Endereço do servidor MySQL
$db = 'mega'; // Nome do banco de dados
$user = 'bessa'; // Nome de usuário do banco de dados
$pass = 'pegasus'; // Senha do banco de dados

// Criação da conexão usando MySQLi
$conn = new mysqli($host, $user, $pass, $db);

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta para obter os concursos do 8º ao 13º, ignorando os 7 primeiros
$sql = "SELECT numero_1, numero_2, numero_3, numero_4, numero_5, numero_6 
        FROM megasena_resultados 
        ORDER BY concurso DESC 
        LIMIT 6 OFFSET 7"; // Ignora os primeiros 7 sorteios (OFFSET 7)

// Preparando e executando a consulta
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

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

// Função para gerar jogos aleatórios
function gerarJogosAleatorios($quantidadeJogos, $numerosDisponiveis) {
    $jogos = [];
    
    for ($j = 0; $j < $quantidadeJogos; $j++) {
        $jogo = [];

        while (count($jogo) < 6) {
            $numeroAleatorio = $numerosDisponiveis[array_rand($numerosDisponiveis)];

            // Garante que não haja repetição no mesmo jogo
            if (!in_array($numeroAleatorio, $jogo)) {
                $jogo[] = $numeroAleatorio;
            }
        }

        // Adiciona o jogo gerado à lista de jogos
        $jogos[] = $jogo;
    }

    return $jogos;
}

// Gera os 10 jogos aleatórios
$jogosGerados = gerarJogosAleatorios(10, $sorteados);

// Exibe os jogos gerados
echo "<h2>Jogos Aleatórios Gerados:</h2>";
echo "<ul>";
foreach ($jogosGerados as $jogo) {
    echo "<li>" . implode(", ", $jogo) . "</li>";
}
echo "</ul>";

// Fecha a conexão
$conn->close();
?>
