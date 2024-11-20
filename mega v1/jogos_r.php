<?php


// números que repetiram em sequência nos últimos 7 concursos 
// Configurações de conexão com o banco de dados
$host = 'localhost';
$dbname = 'mega';
$user = 'bessa';
$pass = 'pegasus';

try {
    // Conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obter os últimos 7 concursos
    $sql = "SELECT concurso, numero_1, numero_2, numero_3, numero_4, numero_5, numero_6 
            FROM megasena_resultados
            ORDER BY concurso DESC
            LIMIT 10";
    
    $stmt = $pdo->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Variável para armazenar a contagem de aparições dos números
    $numerosRepetidos = [];

    // Comparar os números de concursos consecutivos
    for ($i = 0; $i < count($resultados) - 1; $i++) {
        $concursoAtual = $resultados[$i];
        $concursoAnterior = $resultados[$i + 1];

        // Verificar cada número sorteado no concurso atual
        for ($j = 1; $j <= 6; $j++) {
            $numeroAtual = $concursoAtual["numero_$j"];

            // Verificar se o número atual apareceu no concurso anterior
            if (in_array($numeroAtual, [
                $concursoAnterior['numero_1'], $concursoAnterior['numero_2'],
                $concursoAnterior['numero_3'], $concursoAnterior['numero_4'],
                $concursoAnterior['numero_5'], $concursoAnterior['numero_6']
            ])) {
                // Incrementar a contagem de aparições do número
                if (isset($numerosRepetidos[$numeroAtual])) {
                    $numerosRepetidos[$numeroAtual]++;
                } else {
                    $numerosRepetidos[$numeroAtual] = 2; // Considera 2 porque já apareceu em 2 concursos seguidos
                }
            }
        }
    }

    // Exibir os números que apareceram no mínimo 2 vezes seguidas
    echo "Números que apareceram no mínimo 2 vezes seguidas nos últimos 10 concursos:\n";
    echo "<br>";
    foreach ($numerosRepetidos as $numero => $contagem) {
        echo "Número: $numero, Aparições: $contagem vezes\n";
        echo "<br>";
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
