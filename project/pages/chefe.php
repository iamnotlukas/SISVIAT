<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'chefe') {
    header('Location: ../index.php');
    exit;
}

// Buscar solicitações pendentes
$conn = conectar();
$stmt = $conn->prepare("SELECT * FROM solicitacoes WHERE status = 'PENDENTE' ORDER BY created_at DESC");
$stmt->execute();
$solicitacoes_pendentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar histórico de solicitações já avaliadas
$stmt = $conn->prepare("SELECT * FROM solicitacoes WHERE status IN ('APROVADO', 'REJEITADO') ORDER BY created_at DESC LIMIT 10");
$stmt->execute();
$historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISMOV - Chefe do Departamento</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SISMOV - Chefe do Departamento</h1>
            <a href="../auth/logout.php" class="logout-btn">Sair</a>
        </div>

        <?php if(isset($_SESSION['sucesso'])): ?>
            <div class="sucesso"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['erro'])): ?>
            <div class="erro"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
        <?php endif; ?>

        <div class="content">
            <div class="list-section">
                <h2>Solicitações Pendentes</h2>
                <div class="solicitacoes-list">
                    <?php if (count($solicitacoes_pendentes) > 0): ?>
                        <?php foreach ($solicitacoes_pendentes as $solicitacao): ?>
                            <div class="solicitacao-card">
                                <div class="solicitacao-header">
                                    <span class="viatura"><?php echo htmlspecialchars($solicitacao['viatura']); ?></span>
                                    <span class="status pendente">PENDENTE</span>
                                </div>
                                <div class="solicitacao-body">
                                    <p><strong>Solicitante:</strong> <?php echo htmlspecialchars($solicitacao['solicitante']); ?></p>
                                    <p><strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i', strtotime($solicitacao['data_hora'])); ?></p>
                                    <p><strong>Motivo:</strong> <?php echo htmlspecialchars($solicitacao['motivo']); ?></p>
                                    <div class="acoes">
                                        <form action="../actions/avaliar_solicitacao.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
                                            <input type="hidden" name="acao" value="aprovar">
                                            <button type="submit" class="btn-aprovar">Aprovar</button>
                                        </form>
                                        <form action="../actions/avaliar_solicitacao.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
                                            <input type="hidden" name="acao" value="rejeitar">
                                            <button type="submit" class="btn-rejeitar">Rejeitar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-results">Nenhuma solicitação pendente.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="list-section">
                <h2>Histórico de Avaliações</h2>
                <div class="solicitacoes-list">
                    <?php if (count($historico) > 0): ?>
                        <?php foreach ($historico as $solicitacao): ?>
                            <div class="solicitacao-card">
                                <div class="solicitacao-header">
                                    <span class="viatura"><?php echo htmlspecialchars($solicitacao['viatura']); ?></span>
                                    <span class="status <?php echo strtolower($solicitacao['status']); ?>">
                                        <?php echo htmlspecialchars($solicitacao['status']); ?>
                                    </span>
                                </div>
                                <div class="solicitacao-body">
                                    <p><strong>Solicitante:</strong> <?php echo htmlspecialchars($solicitacao['solicitante']); ?></p>
                                    <p><strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i', strtotime($solicitacao['data_hora'])); ?></p>
                                    <p><strong>Motivo:</strong> <?php echo htmlspecialchars($solicitacao['motivo']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-results">Nenhum histórico disponível.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>