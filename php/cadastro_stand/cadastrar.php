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

//# IMPORTANTE # - Provavelmente vai ter que arrumar esse caminho posteriormente


//IMPORTA O RESTO DAS INFOS DO STAND------------------------------------------------------------------

    $nome = $_POST['nome_stand'];
    $tipo = $_POST['tipoStand'];


//ATRIBUTOS STAND-------------------------------------------------------------------------------------
    $map_atr = [
    'nulo' => 0,
    'e' => 1,
    'd' => 2,
    'c' => 3,
    'b' => 4,
    'a' => 5,   
    'infinito' => 6, 
    ];

    function mapValor($valor, $map_atr) {
    return $map_atr[$valor];
    }

    $poder = mapValor($_POST['poderDestrutivo'], $map_atr);
    $velocidade = mapValor($_POST['velocidade'], $map_atr);
    $alcance = mapValor($_POST['alcance'], $map_atr);
    $persistencia = mapValor($_POST['persistencia'], $map_atr);
    $precisao = mapValor($_POST['precisao'], $map_atr);
    $potencial = mapValor($_POST['potencial'], $map_atr);
 
//HABILIDADES---------------------------------------------------------------------------------------------------------

    $nmhab = $_POST['nmhab'];
    $hab = $_POST['hab'];
    $nvl = $_POST['nvlhab'];

    $raridade = $_POST['raridade'];
    $parte = $_POST['parte'];


//ECHO DE DEBUG---------------------------------------------------------------------------------------------------------
  echo "<span class='response'>". $nome . " cadastrado com sucesso, <a href='../ver_cartas_existentes/ver_cartas_existentes.html'>ver Stand.</a></span>";
//INSERÇÃO NO BANCO---------------------------------------------------------------------------------------------------------
      
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
          `Potencial`,
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
          :potencial, 
          :universo
      )";

      $sqlhabs = "INSERT INTO `habilidade`(`Nome`, `Descricao`, `Lv_habilidade`) VALUES (:nmhab,:hab,:nvl)";

    
    $cadastrar_stand = $conn->prepare($sql);
    $cadastrar_habilidade = $conn->prepare($sqlhabs);

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
      "potencial" => $potencial,
      "universo" => $parte
      ));
    } catch (PDOException $e) {
      if ($e->getCode() == 23000) {
      echo "Erro: Um Stand de mesmo nome já existe no banco de dados.";
    } else {
      echo "Erro ao cadastrar Stand: " . $e->getMessage();
    }
    exit;
    }

    try {
      for ($i=0; $i < count($nmhab); $i++) { 
      $cadastrar_habilidade->execute(array(  
      "nmhab" => $nmhab[$i],
      "hab" => $hab[$i],
      "nvl" => $nvl[$i]
      ));
      }
    } catch (PDOException $e) {
      
      if ($e->getCode() == 23000) {
      echo "Erro: A habilidade '{$nmhab[$i]}' já existe no banco de dados.";
    } else {
      echo "Erro ao cadastrar habilidade: " . $e->getMessage();
    }
    exit;
    }

//INSERÇÃO TAB HABILIDADEHEROI------------------------------------------------------------------------------------------------

  $sql_cod_stand = "SELECT `codigo` FROM `heroi` WHERE `Nome` = :nome";
  $cod_stand = $conn->prepare($sql_cod_stand);
  $cod_stand->execute(['nome' => $nome]);
  $codigo_heroi_stand_true = $cod_stand->fetchColumn(); 

  $codigos_habilidades = [];
  foreach ($nmhab as $nome_hab) {
      $sql_cod_habilidade = "SELECT `Codigo` FROM `habilidade` WHERE `Nome` = :nome";
      $cod_habilidade = $conn->prepare($sql_cod_habilidade);
      $cod_habilidade->execute(['nome' => $nome_hab]);
      $codigos_habilidades[] = $cod_habilidade->fetchColumn(); 
  };

  $sql_vinculo = "INSERT INTO habilidadeheroi (CD_habilidade, CD_heroi) VALUES (:cd_habilidade, :cd_heroi)";
  $inserir_vinculo = $conn->prepare($sql_vinculo);

  foreach ($codigos_habilidades as $cod_hab) {
      $inserir_vinculo->execute([
          'cd_habilidade' => $cod_hab,
          'cd_heroi' => $codigo_heroi_stand_true
      ]);
  }








}

