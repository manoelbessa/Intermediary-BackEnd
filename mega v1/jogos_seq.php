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

    // Consulta para obter os últimos 20 concursos
    $sql = "SELECT concurso, numero_1, numero_2, numero_3, numero_4, numero_5, numero_6 
            FROM megasena_resultados
            ORDER BY concurso DESC
            LIMIT 20";
    
    $stmt = $pdo->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Comparar os números de concursos consecutivos para verificar sequência aritmética
    echo "Sequências aritméticas nos últimos 20 concursos:\n";
    echo "<br>";
    for ($i = 0; $i < count($resultados) - 1; $i++) {
        $concursoAtual = $resultados[$i];
        $concursoAnterior = $resultados[$i + 1];

        // Verificar cada número sorteado no concurso atual
        for ($j = 1; $j <= 6; $j++) {
            $numeroAtual = $concursoAtual["numero_$j"];

            // Verificar se o número do concurso anterior forma uma sequência aritmética com o atual
            for ($k = 1; $k <= 6; $k++) {
                $numeroAnterior = $concursoAnterior["numero_$k"];

                if ($numeroAtual == $numeroAnterior + 1) {
                    // Sequência crescente, adiciona "+"
                    echo "Concurso " . $concursoAnterior['concurso'] . " -> Concurso " . $concursoAtual['concurso'] .
                         ": " . $numeroAnterior . " -> " . $numeroAtual . " +\n";
                         echo "<br>";
                } elseif ($numeroAtual == $numeroAnterior - 1) {
                    // Sequência decrescente, adiciona "-"
                    echo "Concurso " . $concursoAnterior['concurso'] . " -> Concurso " . $concursoAtual['concurso'] .
                         ": " . $numeroAnterior . " -> " . $numeroAtual . " -\n";
                         echo "<br>";
                }
            }
        }
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>