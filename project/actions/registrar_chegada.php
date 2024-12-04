<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'sala_estado') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $solicitacao_id = filter_input(INPUT_POST, 'solicitacao_id', FILTER_SANITIZE_NUMBER_INT);
    $data_hora_chegada = filter_input(INPUT_POST, 'data_hora_chegada', FILTER_SANITIZE_STRING);
    $odometro_chegada = filter_input(INPUT_POST, 'odometro_chegada', FILTER_SANITIZE_NUMBER_INT);
    
    if (!$solicitacao_id || !$data_hora_chegada || !$odometro_chegada) {
        $_SESSION['erro'] = 'Todos os campos são obrigatórios';
        header('Location: ../pages/sala_estado.php');
        exit;
    }

    try {
        $conn = conectar();
        
        // Verificar se a solicitação existe e está aprovada
        $stmt = $conn->prepare("SELECT status, odometro_saida FROM solicitacoes WHERE id = ?");
        $stmt->execute([$solicitacao_id]);
        $solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$solicitacao) {
            $_SESSION['erro'] = 'Solicitação não encontrada';
            header('Location: ../pages/sala_estado.php');
            exit;
        }
        
        if ($solicitacao['status'] !== 'APROVADO') {
            $_SESSION['erro'] = 'Apenas solicitações aprovadas podem ter chegada registrada';
            header('Location: ../pages/sala_estado.php');
            exit;
        }

        if ($odometro_chegada < $solicitacao['odometro_saida']) {
            $_SESSION['erro'] = 'O odômetro de chegada não pode ser menor que o de saída';
            header('Location: ../pages/sala_estado.php');
            exit;
        }
        
        // Atualizar a solicitação com os dados de chegada
        $stmt = $conn->prepare("
            UPDATE solicitacoes 
            SET data_hora_chegada = ?, odometro_chegada = ? 
            WHERE id = ?
        ");
        $stmt->execute([$data_hora_chegada, $odometro_chegada, $solicitacao_id]);
        
        $_SESSION['sucesso'] = 'Chegada registrada com sucesso!';
        header('Location: ../pages/sala_estado.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['erro'] = 'Erro ao registrar chegada: ' . $e->getMessage();
        header('Location: ../pages/sala_estado.php');
        exit;
    }
} else {
    header('Location: ../pages/sala_estado.php');
    exit;
}
?>