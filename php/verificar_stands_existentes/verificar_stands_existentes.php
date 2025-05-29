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
        foreach ($cartas as $i => $carta) {
        $modalId = "Modal" . htmlspecialchars($carta["codigo"]);
        $labelId = "ModalLabel" . htmlspecialchars($carta["codigo"]);

        echo '<div class="mb-2 p-2 border rounded">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">
                Ver Carta de Stand
            </button>

            <!-- Modal -->
            <div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="' . $labelId . '" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="' . $labelId . '">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            '.nl2br(print_r($carta, true)).'
                              Nome: ' . htmlspecialchars($carta["Nome"]) . '<br>
                              Raridade: ' . htmlspecialchars($carta["Raridade"]) . '
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }

    }
