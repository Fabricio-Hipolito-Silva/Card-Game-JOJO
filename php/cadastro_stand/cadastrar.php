<?php
require '../conecta.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $dirUp = __DIR__ . '/../../uploads/';  //Caminho do Diretório de UPLOAD
    $extStand = pathinfo($_FILES['imgStand']['name'],PATHINFO_EXTENSION); //Captura a Extensão Original da IMG
    $nomeImgStand = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_POST['nome_stand']); //Tira uma panca de Caracter Especial se tiver
    $novoNome = $nomeImgStand . "_" .time() .".". $extStand; //Crie o nome da imagem, com um _ do tempo atual e o muda
    $caminhoImagem = $dirUp.$novoNome; //Gera o arquivo da Imagem
    move_uploaded_file( //Move a imagem do local temporário para a pasta certinha
        $_FILES['imgStand']['tmp_name'], 
        $caminhoImagem
      );
    $camImgBD= 'uploads/' . $novoNome; //Caminho pro banco ler flagra



    //IMPORTA O RESTO DAS INFOS DO STAND

    $nome = $_POST['nome_stand'];
    $tipo = $_POST['tipoStand'];

    $poder = $_POST['poderDestrutivo'];
    $velocidade = $_POST['velocidade'];
    $alcance = $_POST['alcance'];
    $persistencia = $_POST['persistencia'];
    $precisao = $_POST['precisao'];
    $potencial = $_POST['potencial'];

    $nmhab1 = $_POST['nmhab1'];
    $hab1 = $_POST['hab1'];
    $nvl1 = $_POST['nvlhab1'];
    $nmhab2 = $_POST['nmhab2'];
    $hab2 = $_POST['hab2'];
    $nvl2 = $_POST['nvlhab2'];

    $raridade = $_POST['raridade'];
    $parte = $_POST['parte'];

    echo "
        <strong>Stand cadastrado com sucesso!</strong><br>
        <strong>Nome:</strong> $nome <br>
        <strong>Tipo:</strong> $tipo <br>
        <strong>Poder Destrutivo:</strong> $poder <br>
        <strong>Velocidade:</strong> $velocidade <br>
        <strong>Alcance:</strong> $alcance <br>
        <strong>Persistência:</strong> $persistencia <br>
        <strong>Precisão:</strong> $precisao <br>
        <strong>Potencial:</strong> $potencial <br><br>

        <strong>Habilidade 1:</strong> $nmhab1 (Nível: $nvl1)<br>
        <em>$hab1</em><br><br>

        <strong>Habilidade 2:</strong> $nmhab2 (Nível: $nvl2)<br>
        <em>$hab2</em><br><br>

        <strong>Raridade:</strong> $raridade <br>
        <strong>Parte:</strong> $parte <br>
        <strong>Imagem salva como:</strong> $camImgBD
    ";

    
      $sql = "INSERT INTO `heroi`(
          `Nome`, 
          `Imagem`, 
          `Tipo`, 
          `Raridade`, 
          `PoderDestrutivo`, 
          `Velocidade`, 
          `Alcance`, 
          `Persistencia_Poder`, 
          `Precisao`, 
          `universo`
      ) VALUES (
          :nome, 
          :imagem, 
          :tipo, 
          :raridade, 
          :poder_destrutivo, 
          :velocidade, 
          :alcance, 
          :persistencia_poder, 
          :precisao, 
          :universo
      )";

    

    
    $cadastrar_stand = $conn->prepare($sql);


    $cadastrar_hab_stand = $conn->prepare($sql2);


    try {
      $cadastrar_stand->execute(array(  
      "nome" => $nome,
      "imagem" => $camImgBD,
      "tipo" => $tipo,
      "raridade" => $raridade,
      "poder_destrutivo" => $poder,
      "velocidade" => $velocidade,
      "alcance" => $alcance,
      "persistencia_poder" => $persistencia,
      "precisao" => $precisao,
      "universo" => $parte
    ))
    } catch (\Throwable $th) {
      //throw $th;
    };

}