<?php
require 'infobanco.php';
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $pe) {
    die("Não conectou:" . $pe
>getMessage());
}