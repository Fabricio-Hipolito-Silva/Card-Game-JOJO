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

    // Pega os resultados
    $cartas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verifica e imprime os resultados
    if (count($cartas) === 0) {
        echo "Nenhuma Carta Encontrada";
    } else {
        foreach ($cartas as $i => $carta) {
        $modalId = "Modal" . htmlspecialchars($carta["codigo"]);
        $labelId = "ModalLabel" . htmlspecialchars($carta["codigo"]);
        $canvasId = "Chart" . $carta["codigo"];

        echo '<div class="mb-2 p-2 border rounded">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">
                Ver Carta de Stand
            </button>

            <!-- Modal -->
            <div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="' . $labelId . '" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content carta-modal">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="' . $labelId . '"> ' . htmlspecialchars($carta["Nome"]) . '</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="../../'. htmlspecialchars($carta["Imagem"]).'" class="img-fluid">
                            Tipo de Stand: '.htmlspecialchars($carta["Tipo"]).'
                             
                                <canvas id="' . $canvasId . '"
                                data-poder="' . ($carta["PoderDestrutivo"]) . '"
                                data-velocidade="' . ($carta["Velocidade"]) . '"
                                data-alcance="' . ($carta["Alcance"]) . '"
                                data-persistencia="' . ($carta["Persistencia_Poder"]) . '"
                                data-precisao="' . ($carta["Precisao"]) . '"
                                data-potencial="' . ($carta["Potencial"]) . '"
                                ></canvas>  

                            Raridade da Carta: '.htmlspecialchars($carta["Raridade"]).'

                            </div>
                            <div class="modal-footer">
                            Parte: '.htmlspecialchars($carta["universo"]).'
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
    }