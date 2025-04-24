<?php
require 'conecta.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $dirUp = __DIR__ . '/../uploads/';  //Caminho do Diretório de UPLOAD
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

    echo "Stand cadastrado com sucesso: $nmhab1"; 
}