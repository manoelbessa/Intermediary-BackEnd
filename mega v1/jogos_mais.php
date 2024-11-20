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

// Consulta para obter os últimos 20 concursos
$sql = "SELECT numero_1, numero_2, numero_3, numero_4, numero_5, numero_6 
        FROM megasena_resultados 
        ORDER BY concurso DESC 
        LIMIT 20";
$result = $conn->query($sql);

// Array para contar a frequência dos números
$frequencias = [];

// Processa os resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        for ($i = 1; $i <= 6; $i++) {
            $numero = $row["numero_$i"];
            if (isset($frequencias[$numero])) {
                $frequencias[$numero]++;
            } else {
                $frequencias[$numero] = 1;
            }
        }
    }
}

// Ordena os números pela frequência
arsort($frequencias);

// Exibe os números e suas frequências
echo "<h2>Números que mais saíram nos últimos 20 concursos:</h2>";
echo "<ul>";
foreach ($frequencias as $numero => $frequencia) {
    echo "<li>Número $numero: $frequencia vez(es)</li>";
}
echo "</ul>";

// Fecha a conexão
$conn->close();
?>
