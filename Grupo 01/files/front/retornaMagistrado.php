<?php
    $pdo = new PDO("mysql:host=172.29.0.2; dbname=justicafacil; charset=utf8;", "jf", "Q8ScP6Pjq3NjatXz");
    $dados = $pdo->prepare("SELECT distinct magistrado FROM processos");
    $dados->execute();
    echo json_encode($dados->fetchAll(PDO::FETCH_ASSOC));
?>