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


function mapRaridade($raridade, $map_raridade) {
return $map_raridade[$raridade];
}

function mapParte($parte, $map_parte) {
return $map_parte[$parte];
}

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

          $map_raridade = [
            'Comum' => 1,
            'Incomum' => 2,
            'Raro' => 3,
            'Epico' => 4,
            'Lendario' => 5,
            ];

            $map_parte = [
            'p3' => "../../img/JoJoPrts/jojopt3",
            'p4' => "../../img/JoJoPrts/jojopt4",
            'p5' => "../../img/JoJoPrts/jojopt5",
            'p6' => "../../img/JoJoPrts/jojopt6",
            'p7' => "../../img/JoJoPrts/jojopt7",
            'p8' => "../../img/JoJoPrts/jojopt8",
            'p9' => "../../img/JoJoPrts/jojopt9",
            ];

        
        $modalId = "Modal" . htmlspecialchars($carta["codigo"]);
        $labelId = "ModalLabel" . htmlspecialchars($carta["codigo"]);
        $canvasId = "Chart" . $carta["codigo"];
        $quantStar = mapRaridade($carta["Raridade"], $map_raridade);
        $Parte_stand = mapParte($carta["universo"], $map_parte);

        
 echo '<div class="mb-2 p-2">
    <!-- Botão que abre o modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">
        <img src="../../' . htmlspecialchars($carta["Imagem"]) . '" class="img-fluid img-stand-button"> <br>' . htmlspecialchars($carta["Nome"]) . '
    </button>

    <!-- Modal -->
    <form class="form_editar" id="form_editar_' . $modalId . '" method="post" enctype="multipart/form-data" action="../../php/verificar_stands_existentes/salvar_edicao.php">
        <div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="' . $labelId . '" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content carta-modal">
                    <div class="modal-header">
                        <input type="hidden" name="id" value="' . htmlspecialchars($carta["codigo"]) . '">
                        <input type="text" class="form-control editable-title" name="nome" value="' . htmlspecialchars($carta["Nome"]) . '">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <hr>
                    <div class="modal-body">

                        <!-- Imagem e Upload -->
                        <img id="previewImagem_' . $modalId . '" src="../../' . htmlspecialchars($carta["Imagem"]) . '" class="img-fluid img_stand mb-2">


                        <!-- Tipo -->
                        <span class="tipo_stand">Tipo de Stand:  </span>
                  <select id="meuSelect" class="selectpicker" name="tipoStand" required>
                        <option value="" disabled>Selecione o tipo</option>
                        <option value="Curto_Alcance" ' . ($carta["Tipo"] === "Curto_Alcance" ? "selected" : "") . '>Curto Alcance</option>
                        <option value="Longo_Alcance" ' . ($carta["Tipo"] === "Longo_Alcance" ? "selected" : "") . '>Longo Alcance</option>
                        <option value="Automatico" ' . ($carta["Tipo"] === "Automatico" ? "selected" : "") . '>Automático</option>
                        <option value="Materializado" ' . ($carta["Tipo"] === "Materializado" ? "selected" : "") . '>Materializado</option>
                    </select>


                        <!-- Atributos -->
                        <div class="row">
                            <div class="col">
                                <label>Poder Destrutivo:</label>
                                <input type="number" min="0" max="6" name="PoderDestrutivo" class="form-control" value="' . htmlspecialchars($carta["PoderDestrutivo"]) . '">
                            </div>
                            <div class="col">
                                <label>Velocidade:</label>
                                <input type="number" min="0" max="6" name="Velocidade" class="form-control" value="' . htmlspecialchars($carta["Velocidade"]) . '">
                            </div>
                            <div class="col">
                                <label>Alcance:</label>
                                <input type="number" min="0" max="6" name="Alcance" class="form-control" value="' . htmlspecialchars($carta["Alcance"]) . '">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <label>Persistência:</label>
                                <input type="number" min="0" max="6" name="Persistencia_Poder" class="form-control" value="' . htmlspecialchars($carta["Persistencia_Poder"]) . '">
                            </div>
                            <div class="col">
                                <label>Precisão:</label>
                                <input type="number" min="0" max="6" name="Precisao" class="form-control" value="' . htmlspecialchars($carta["Precisao"]) . '">
                            </div>
                            <div class="col">
                                <label>Potencial:</label>
                                <input type="number" min="0" max="6"name="Potencial" class="form-control" value="' . htmlspecialchars($carta["Potencial"]) . '">
                            </div>
                        </div>

                        <!-- Habilidades -->
                        <br><span class="titulohab hab">Habilidades do Stand:</span><br><br>';

                        if (count($habsInfo) > 0) {
                            foreach ($habsInfo as $index => $hab) {
                                echo '
                                <div class="mb-2">
                                    <label>Nome da Habilidade:</label>
                                    <input type="text" class="form-control" name="habilidades[' . $index . '][nome]" value="' . htmlspecialchars($hab["Nome"]) . '">
                                    <label>Descrição:</label>
                                    <textarea class="form-control" name="habilidades[' . $index . '][descricao]">' . htmlspecialchars($hab["Descricao"]) . '</textarea>
                                </div>';
                            }
                        } else {
                            echo '<p><em>Sem habilidades cadastradas.</em></p>';
                        }
                        echo '
                        <!-- Raridade -->
                        <div class="mt-3">
                            <label>Raridade:</label>
                            <select name="Raridade" class="form-control">
                                <option value="1">1 Estrela</option>
                                <option value="2">2 Estrelas</option>
                                <option value="3">3 Estrelas</option>
                                <option value="4">4 Estrelas</option>
                                <option value="5">5 Estrelas</option>
                            </select>
                        </div>
                        <hr>
                    </div> 
                    <div class="modal-footer">
                        <img class="parte" src="' . $Parte_stand . '">
                        <button type="submit" class="btn btn-success">Salvar alterações</button>
                        <button type="submit" name="excluir" value="1" class="btn btn-danger">Excluir Carta</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>';
    }
}
?>