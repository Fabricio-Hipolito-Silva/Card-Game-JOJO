<?php
require "../conecta.php";

// Verifica se a variável foi enviada corretamente
$partes = $_POST["parte"] ?? null;
if (!$partes) {
    http_response_code(400);
    echo "Inválido";
    exit;
}

// Prepara e executa a query
$sql = "SELECT * FROM `heroi` WHERE `universo` = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$partes]); 

// Pega os resultados
$cartas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica e imprime os resultados
if (count($cartas) === 0) {
    echo "Nenhuma Carta Encontrada";
} else {
    foreach ($cartas as $carta) {
        echo "<div class='mb-2 p-2 border rounded'>
               Achei uma carta
              </div>";
    }
}
