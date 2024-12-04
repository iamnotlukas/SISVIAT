<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'garagem') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $solicitante = filter_input(INPUT_POST, 'solicitante', FILTER_SANITIZE_STRING);
    $viatura = filter_input(INPUT_POST, 'viatura', FILTER_SANITIZE_STRING);
    $data_hora = filter_input(INPUT_POST, 'data_hora', FILTER_SANITIZE_STRING);
    $motivo = filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_STRING);
    $odometro_saida = filter_input(INPUT_POST, 'odometro_saida', FILTER_SANITIZE_NUMBER_INT);

    if (!$solicitante || !$viatura || !$data_hora || !$motivo || !$odometro_saida) {
        $_SESSION['erro'] = 'Todos os campos são obrigatórios';
        header('Location: ../pages/garagem.php');
        exit;
    }

    try {
        $conn = conectar();
        $stmt = $conn->prepare("
            INSERT INTO solicitacoes (solicitante, viatura, data_hora, motivo, odometro_saida, status)
            VALUES (?, ?, ?, ?, ?, 'PENDENTE')
        ");
        
        $stmt->execute([$solicitante, $viatura, $data_hora, $motivo, $odometro_saida]);
        
        $_SESSION['sucesso'] = 'Solicitação criada com sucesso!';
        header('Location: ../pages/garagem.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['erro'] = 'Erro ao criar solicitação: ' . $e->getMessage();
        header('Location: ../pages/garagem.php');
        exit;
    }
} else {
    header('Location: ../pages/garagem.php');
    exit;
}
?>