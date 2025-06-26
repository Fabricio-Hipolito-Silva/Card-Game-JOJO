<?php
require '../conecta.php'; // ou ajuste o caminho se estiver em outra pasta

if ($_SERVER["REQUEST_METHOD"] === "POST") {
$id = $_POST['id'];
$nome = $_POST['nome'];
$tipo = $_POST['tipoStand'];
$pd = $_POST['PoderDestrutivo'];
$vel = $_POST['Velocidade'];
$alc = $_POST['Alcance'];
$per = $_POST['Persistencia_Poder'];
$pre = $_POST['Precisao'];
$pot = $_POST['Potencial'];
$raridade = $_POST['Raridade'];
    if (isset($_POST['excluir'])) {
        // Ação: excluir carta
        try {
            // Apaga os vínculos primeiro
            $sql = "DELETE FROM habilidadeheroi WHERE CD_heroi = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $id]);

            // Depois apaga o herói (carta)
            $sql = "DELETE FROM heroi WHERE codigo = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['id' => $id]);

            header("Location: ../../html/ver_cartas_existentes/ver_cartas_existentes.html");
            exit;
        } catch (PDOException $e) {
            echo "Erro ao excluir: " . $e->getMessage();
        }

    }else{
try {
    $sql = "UPDATE heroi SET 
        Nome = :nome,
        Tipo = :tipo,
        PoderDestrutivo = :pd,
        Velocidade = :vel,
        Alcance = :alc,
        Persistencia_Poder = :per,
        Precisao = :pre,
        Potencial = :pot,
        Raridade = :raridade
        WHERE codigo = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':tipo' => $tipo,
        ':pd' => $pd,
        ':vel' => $vel,
        ':alc' => $alc,
        ':per' => $per,
        ':pre' => $pre,
        ':pot' => $pot,
        ':raridade' => $raridade,
        ':id' => $id
    ]);

    // Deleta os vínculos antigos
$deletar_vinculos = $conn->prepare("DELETE FROM habilidadeheroi WHERE CD_heroi = :id");
$deletar_vinculos->execute(['id' => $id]);

foreach ($_POST['habilidades'] as $hab) {
    $nomeHab = $hab['nome'];
    $descHab = $hab['descricao'];

    $sql_check = $conn->prepare("SELECT Codigo FROM habilidade WHERE Nome = :nome");
    $sql_check->execute(['nome' => $nomeHab]);
    $idHab = $sql_check->fetchColumn();

    if ($idHab) {
        $sql_update = $conn->prepare("UPDATE habilidade SET Descricao = :desc WHERE Codigo = :id");
        $sql_update->execute(['desc' => $descHab, 'id' => $idHab]);
    } else {
        $sql_insert = $conn->prepare("INSERT INTO habilidade (Nome, Descricao) VALUES (:nome, :desc)");
        $sql_insert->execute(['nome' => $nomeHab, 'desc' => $descHab]);
        $idHab = $conn->lastInsertId();
    }
$sql_vinc = $conn->prepare("INSERT INTO habilidadeheroi (CD_habilidade, CD_heroi) VALUES (:hab, :heroi)");
$sql_vinc->execute(['hab' => $idHab, 'heroi' => $id]);


}


echo "Atualização concluída!";
    header("Location: ../../html/ver_cartas_existentes/ver_cartas_existentes.html");
    exit;

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}}}