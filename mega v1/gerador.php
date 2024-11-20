<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Combinações</title>
</head>
<body>

<h1>Gerar Combinações de Números</h1>

<form method="POST">
    <label for="numbers">Digite os números separados por vírgula:</label><br>
    <input type="text" id="numbers" name="numbers" required><br><br>

    <button type="submit" name="submit">Enviar</button>
    <button type="reset">Limpar</button>
</form>

<?php
if (isset($_POST['submit'])) {
    $input = $_POST['numbers'];
    // Convertendo a string de entrada em um array de números, removendo espaços extras
    $numbers = array_map('intval', array_filter(explode(',', str_replace(' ', '', $input))));

    // Verificar se há números suficientes para gerar combinações
    if (count($numbers) < 6) {
        echo "<p>Erro: É necessário pelo menos 6 números para gerar combinações.</p>";
        exit;
    }

    // Função para gerar combinações aleatórias
    function generateCombinations($numbers, $combSize, $numCombinations) {
        $combinations = [];
        $totalNumbers = count($numbers);

        for ($i = 0; $i < $numCombinations; $i++) {
            $combination = [];

            // Garante que os números sejam utilizados e evita sequência de números
            while (count($combination) < $combSize) {
                // Embaralha os números a cada ciclo
                $shuffledNumbers = $numbers;
                shuffle($shuffledNumbers);

                foreach ($shuffledNumbers as $num) {
                    if (!in_array($num, $combination)) {
                        // Verifica se o número não é sequencial ao último adicionado
                        if (count($combination) === 0 || abs(end($combination) - $num) > 1) {
                            $combination[] = $num;
                        }
                    }

                    // Quando já atingimos o número necessário para a combinação, saímos do loop
                    if (count($combination) === $combSize) {
                        break;
                    }
                }
            }

            // Ordenar a combinação em ordem crescente
            sort($combination);

            $combinations[] = $combination;
        }

        return $combinations;
    }

    // Função para verificar se todos os números foram utilizados nas combinações
    function checkMissingNumbers($numbers, $combinations) {
        $allUsedNumbers = [];

        // Coletar todos os números usados nas combinações
        foreach ($combinations as $combination) {
            $allUsedNumbers = array_merge($allUsedNumbers, $combination);
        }

        // Remover duplicatas
        $allUsedNumbers = array_unique($allUsedNumbers);

        // Verificar quais números faltaram
        $missingNumbers = array_diff($numbers, $allUsedNumbers);

        return $missingNumbers;
    }

    // Gerando 10 combinações com 6 números
    $numCombinations = 12;
    $combSize = 6;
    $combinations = generateCombinations($numbers, $combSize, $numCombinations);

    // Exibindo as combinações geradas
    echo "<h2>Combinações Geradas:</h2>";
    echo "<ul>";
    foreach ($combinations as $combination) {
        echo "<li>" . implode(', ', $combination) . "</li>";
    }
    echo "</ul>";

    // Verificar se algum número ficou de fora
    $missingNumbers = checkMissingNumbers($numbers, $combinations);

    if (!empty($missingNumbers)) {
        echo "<h3>Números que ficaram de fora:</h3>";
        echo "<p>" . implode(', ', $missingNumbers) . "</p>";
    } else {
        echo "<p>Todos os números foram utilizados nas combinações.</p>";
    }
}
?>

</body>
</html>
