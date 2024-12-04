<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'chefe') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $solicitacao_id = filter_input(INPUT_POST, 'solicitacao_id', FILTER_SANITIZE_NUMBER_INT);
    $acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);
    
    if (!$solicitacao_id || !in_array($acao, ['aprovar', 'rejeitar'])) {
        $_SESSION['erro'] = 'Parâmetros inválidos';
        header('Location: ../pages/chefe.php');
        exit;
    }

    try {
        $conn = conectar();
        
        // Verificar se a solicitação existe e está pendente
        $stmt = $conn->prepare("SELECT status FROM solicitacoes WHERE id = ?");
        $stmt->execute([$solicitacao_id]);
        $solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$solicitacao) {
            $_SESSION['erro'] = 'Solicitação não encontrada';
            header('Location: ../pages/chefe.php');
            exit;
        }
        
        if ($solicitacao['status'] !== 'PENDENTE') {
            $_SESSION['erro'] = 'Esta solicitação já foi avaliada';
            header('Location: ../pages/chefe.php');
            exit;
        }
        
        // Atualizar o status da solicitação
        $novo_status = $acao === 'aprovar' ? 'APROVADO' : 'REJEITADO';
        $stmt = $conn->prepare("UPDATE solicitacoes SET status = ? WHERE id = ?");
        $stmt->execute([$novo_status, $solicitacao_id]);
        
        $_SESSION['sucesso'] = 'Solicitação ' . ($acao === 'aprovar' ? 'aprovada' : 'rejeitada') . ' com sucesso!';
        header('Location: ../pages/chefe.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['erro'] = 'Erro ao avaliar solicitação: ' . $e->getMessage();
        header('Location: ../pages/chefe.php');
        exit;
    }
} else {
    header('Location: ../pages/chefe.php');
    exit;
}
?>