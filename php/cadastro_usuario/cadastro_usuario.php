<?php
require "../conecta.php";
session_start();
$nm_cadastro = $_POST['nm_cadastro'];
$senha_cadastro = $_POST['senha_cadastro'];

$sql = "INSERT INTO `login`(`usuario`, `senha`) VALUES (:nm_cadastro, :senha_cadastro)";
$cadastrar = $conn->prepare($sql);
$cadastrar->bindParam(':nm_cadastro', $nm_cadastro);
$cadastrar->bindParam(':senha_cadastro', $senha_cadastro);
$cadastrar->execute();

    if ($cadastrar->rowCount() > 0) {
    echo "<script>
        alert('Cadastro bem-sucedido, faça o login.');
        window.location.href='../../index.html';
    </script>";
} else {
    echo "<script>
        alert('Erro ao cadastrar. Tente novamente.');
        location.reload();;
    </script>";
}
