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
                            <h1 class="modal-title" id="' . $labelId . '">' . htmlspecialchars($carta["Nome"]) . '</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <hr>

                        <div class="modal-body">
                            <img src="../../' . htmlspecialchars($carta["Imagem"]) . '" class="img-fluid img_stand">
                            <span class="tipo"><span class="tipo_stand">Tipo de Stand:  </span>'.htmlspecialchars($carta["Tipo"]) . '</span>  

                            <canvas id="' . $canvasId . '"
                                data-poder="' . $carta["PoderDestrutivo"] . '"
                                data-velocidade="' . $carta["Velocidade"] . '"
                                data-alcance="' . $carta["Alcance"] . '"
                                data-persistencia="' . $carta["Persistencia_Poder"] . '"
                                data-precisao="' . $carta["Precisao"] . '"
                                data-potencial="' . $carta["Potencial"] . '"
                            ></canvas>
                            
                            '; 
                            
                            echo '<br> <span class="titulohab hab">Habilidades do Stand:</span> <br><br>';
                            if (count($habsInfo) > 0) {
                                foreach ($habsInfo as $index => $hab) {
                                    echo '<span class="habs"><span class="titulohab">'. htmlspecialchars($hab["Nome"]) . '<br> </span>';
                                    echo '<em>' . htmlspecialchars($hab["Descricao"]) . '</em><br><br></span>';
                                }
                            } else {
                                echo '<p><em>Sem habilidades cadastradas.</em></p>';
                            };
                            
                            echo '';
                                for ($i = 0; $i < $quantStar; $i++) {
                                    echo '<i class="fa-solid fa-star fa-2xl" style="color: #FFD43B;"></i>';
                                }'
                            <hr>';  
                                echo'
                                            </div>
                                            <div class="modal-footer">
                                              <img class="parte" src="'.$Parte_stand.'">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';
    }
}
?>
