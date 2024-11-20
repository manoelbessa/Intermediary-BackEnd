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

    // Obter todos os resultados da Mega-Sena (pode limitar se preferir)
    $sql = "SELECT concurso, numero_1, numero_2, numero_3, numero_4, numero_5, numero_6 
            FROM megasena_resultados
            ORDER BY concurso DESC";

    $stmt = $pdo->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Inicializar o array para os 60 números (Mega-Sena vai de 1 a 60)
    $ultimasAparicoes = array_fill(1, 60, null);

    // Iterar sobre os concursos e registrar o último concurso em que cada número apareceu
    foreach ($resultados as $resultado) {
        for ($i = 1; $i <= 6; $i++) {
            $numero = $resultado["numero_$i"];
            if ($ultimasAparicoes[$numero] === null) {
                $ultimasAparicoes[$numero] = $resultado['concurso'];
            }
        }
    }

    // Obter o número do concurso mais recente
    $concursoMaisRecente = $resultados[0]['concurso'];

    // Calcular quantos concursos se passaram desde a última aparição de cada número
    $ausenciaNumeros = [];
    foreach ($ultimasAparicoes as $numero => $ultimoConcurso) {
        if ($ultimoConcurso === null) {
            $ausenciaNumeros[$numero] = $concursoMaisRecente; // Nunca apareceu
        } else {
            $ausenciaNumeros[$numero] = $concursoMaisRecente - $ultimoConcurso;
        }
    }

    // Ordenar os números pela quantidade de concursos desde a última aparição (maior para menor)
    arsort($ausenciaNumeros);

    // Exibir os resultados
    echo "Números da Mega-Sena ordenados pelo tempo que não aparecem:\n";
    echo "<br>";
    foreach ($ausenciaNumeros as $numero => $ausencia) {
        echo "Número: $numero, Concursos sem aparecer: $ausencia\n";
        echo "<br>";
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
