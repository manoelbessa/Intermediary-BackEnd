<?php
// Números disponíveis para criar os jogos
$numerosDisponiveis = [18, 49, 28, 60, 12, 41, 56, 23, 6, 16, 24, 1, 4, 26, 38, 44, 25, 27];

// Função para criar um jogo de 6 números aleatórios, sem repetição dentro e entre os jogos
function criarJogo(&$numerosDisponiveis) {
    // Embaralha os números disponíveis
    shuffle($numerosDisponiveis);
    // Seleciona os 6 primeiros números embaralhados
    $jogo = array_slice($numerosDisponiveis, 0, 6);
    // Remove os números selecionados da lista de disponíveis
    $numerosDisponiveis = array_diff($numerosDisponiveis, $jogo);
    return $jogo;
}

// Gerar 3 jogos
echo "<h2>3 Jogos Aleatórios (sem repetições):</h2>";
echo "<ul>";
for ($j = 1; $j <= 3; $j++) {
    $jogo = criarJogo($numerosDisponiveis);
    sort($jogo); // Ordena os números para exibição
    echo "<li>Jogo $j: " . implode(", ", $jogo) . "</li>";
}
echo "</ul>";
?>
