<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    
    $conn = conectar();
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        $_SESSION['usuario_tipo'] = $user['tipo'];
        
        switch($user['tipo']) {
            case 'garagem':
                header('Location: ../pages/garagem.php');
                break;
            case 'chefe':
                header('Location: ../pages/chefe.php');
                break;
            case 'sala_estado':
                header('Location: ../pages/sala_estado.php');
                break;
        }
        exit;
    } else {
        $_SESSION['erro'] = 'Usuário ou senha inválidos';
        header('Location: ../index.php');
        exit;
    }
}
?>