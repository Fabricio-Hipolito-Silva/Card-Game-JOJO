<?php
require "../conecta.php";
session_start();
$nm_login = $_POST['nm_login'];
$senha = $_POST['senha_login'];

$sql = "SELECT * FROM `login` WHERE `usuario` = :nm_login AND `senha` = :senha AND `tipo` = 'admin'";
$validar = $conn->prepare($sql);
$validar->bindParam(':nm_login', $nm_login);
$validar->bindParam(':senha', $senha);
$validar->execute();

    if ($validar->rowCount() > 0) {
        $_SESSION['usuario_autenticado'] = true;  
        header("Location: ../../html/homepage_adm/homepage_adm.html");
        exit;
    }else {
        echo "<script>alert('Usuário ou Senha Inválidos'); window.location.href='../../html/login_adm/login_adm.html';</script>";
    };

