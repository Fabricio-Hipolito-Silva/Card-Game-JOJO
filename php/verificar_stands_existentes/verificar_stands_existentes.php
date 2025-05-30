<?php
require "../conecta.php";

$partes = $_POST["parte"] ?? null;
if (!$partes) {
    http_response_code(400);
    echo "Inválido";
    exit;
}

$sql = "SELECT * FROM `heroi` WHERE `universo` = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$partes]);
$cartas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($cartas) === 0) {
    echo "Nenhuma Carta Encontrada";
} else {
    foreach ($cartas as $carta) {
        $sqlhabmtm = "SELECT CD_habilidade FROM habilidadeheroi WHERE CD_heroi = ?";
        $stmt2 = $conn->prepare($sqlhabmtm);
        $stmt2->execute([$carta["codigo"]]);
        $habilidadesCodigos = $stmt2->fetchAll(PDO::FETCH_COLUMN);

        $habsInfo = [];
        if ($habilidadesCodigos) {
            $placeholders = implode(',', array_fill(0, count($habilidadesCodigos), '?'));
            $sqlhab = "SELECT * FROM habilidade WHERE Codigo IN ($placeholders)";
            $stmt3 = $conn->prepare($sqlhab);
            $stmt3->execute($habilidadesCodigos);
            $habsInfo = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        }

        $modalId = "Modal" . htmlspecialchars($carta["codigo"]);
        $labelId = "ModalLabel" . htmlspecialchars($carta["codigo"]);
        $canvasId = "Chart" . $carta["codigo"];

        echo '<div class="mb-2 p-2 border rounded">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">
                 '. htmlspecialchars($carta["Nome"]) .'
            </button>

            <!-- Modal -->
            <div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="' . $labelId . '" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content carta-modal">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="' . $labelId . '">' . htmlspecialchars($carta["Nome"]) . '</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <hr>

                        <div class="modal-body">
                            <img src="../../' . htmlspecialchars($carta["Imagem"]) . '" class="img-fluid">
                            <span class="tipo_stand">Tipo de Stand:  </span>'.htmlspecialchars($carta["Tipo"]) . ' 

                            <canvas id="' . $canvasId . '"
                                data-poder="' . $carta["PoderDestrutivo"] . '"
                                data-velocidade="' . $carta["Velocidade"] . '"
                                data-alcance="' . $carta["Alcance"] . '"
                                data-persistencia="' . $carta["Persistencia_Poder"] . '"
                                data-precisao="' . $carta["Precisao"] . '"
                                data-potencial="' . $carta["Potencial"] . '"
                            ></canvas>

                            <hr>';

                            if (count($habsInfo) > 0) {
                                foreach ($habsInfo as $index => $hab) {
                                    echo '<strong>Habilidade ' . ($index + 1) . ':</strong> ' . htmlspecialchars($hab["Nome"]) . '<br>';
                                    echo '<em>' . htmlspecialchars($hab["Descricao"]) . '</em><br><br>';
                                }
                            } else {
                                echo '<p><em>Sem habilidades cadastradas.</em></p>';
                            }

                            echo '  
                                                Raridade da Carta: ' . htmlspecialchars($carta["Raridade"]) . '
                                            </div>
                                            <div class="modal-footer">
                                                Parte: ' . htmlspecialchars($carta["universo"]) .'
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
    }
}
?>
