<?php

// Habilitar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



function converterDataParaAmericano($dataBr) {
    // Cria um objeto DateTime a partir da data no formato brasileiro
    $data = DateTime::createFromFormat('d/m/Y', $dataBr);
    
    // Verifica se a data foi criada corretamente
    if ($data) {
        // Retorna a data no formato americano (yyyy-mm-dd)
        return $data->format('Y-m-d');
    } else {
        return "Data inválida!";
    }
}



// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'mega';
$username = 'bessa';
$password = 'pegasus';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// URL da API da Caixa para Mega Sena (números mais recentes)
$url = "https://servicebus2.caixa.gov.br/portaldeloterias/api/megasena";

// Fazendo requisição para buscar os dados
$response = file_get_contents($url);
if ($response === FALSE) {
    die("Erro ao acessar a API.");
}

// Decodifica o JSON retornado
$resultados = json_decode($response, true);

// Verifica se obteve os dados corretamente
if (isset($resultados['listaDezenas'])) {
    $concurso = $resultados['numero'];
    $dataSorteio = $resultados['dataApuracao'];
    $dataSorteio = converterDataParaAmericano($dataSorteio);
    $numeros = $resultados['listaDezenas'];




      // Verificar se o concurso já foi inserido no banco
    $verificaSql = "SELECT COUNT(*) FROM megasena_resultados WHERE concurso = :concurso";
    $verificaStmt = $pdo->prepare($verificaSql);
    $verificaStmt->execute([':concurso' => $concurso]);
    $concursoExiste = $verificaStmt->fetchColumn();

    if ($concursoExiste > 0) {
        echo "Concurso $concurso já foi inserido no banco de dados.";
    } else {
        // Inserir os números no banco de dados se o concurso não existir
        $sql = "INSERT INTO megasena_resultados (concurso, data_sorteio, numero_1, numero_2, numero_3, numero_4, numero_5, numero_6)
                VALUES (:concurso, :data_sorteio, :numero_1, :numero_2, :numero_3, :numero_4, :numero_5, :numero_6)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':concurso' => $concurso,
            ':data_sorteio' => $dataSorteio,
            ':numero_1' => $numeros[0],
            ':numero_2' => $numeros[1],
            ':numero_3' => $numeros[2],
            ':numero_4' => $numeros[3],
            ':numero_5' => $numeros[4],
            ':numero_6' => $numeros[5]
        ]);

        echo "Números do concurso $concurso inseridos com sucesso!";
    }
} else {
    echo "Erro ao processar os dados.";
}
?>