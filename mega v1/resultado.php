<?php
// Configurações de conexão com o banco de dados
$host = 'localhost';
$dbname = 'mega';
$user = 'bessa';
$pass = 'pegasus';

try {
    // Conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter os últimos 20 concursos ordenados do mais recente para o mais antigo
    $sql = "SELECT concurso, data_sorteio, numero_1, numero_2, numero_3, numero_4, numero_5, numero_6 
            FROM megasena_resultados
            ORDER BY concurso DESC
            LIMIT 30";
    
    $stmt = $pdo->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Exibir os resultados
    echo "<h2>Últimos 30 resultados da Mega-Sena</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
            <th>Concurso</th>
            <th>Data do Sorteio</th>
            <th>Números Sorteados</th>
          </tr>";

    foreach ($resultados as $resultado) {
        echo "<tr>";
        echo "<td>" . $resultado['concurso'] . "</td>";
        echo "<td>" . $resultado['data_sorteio'] . "</td>";
        echo "<td>" . $resultado['numero_1'] . ", " . $resultado['numero_2'] . ", " . $resultado['numero_3'] . ", " . 
                      $resultado['numero_4'] . ", " . $resultado['numero_5'] . ", " . $resultado['numero_6'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
