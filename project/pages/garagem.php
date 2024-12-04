<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'garagem') {
    header('Location: ../index.php');
    exit;
}

// Buscar solicitações existentes
$conn = conectar();
$stmt = $conn->prepare("SELECT * FROM solicitacoes ORDER BY created_at DESC");
$stmt->execute();
$solicitacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISMOV - Garagem</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SISMOV - Garagem</h1>
            <a href="../auth/logout.php" class="logout-btn">Sair</a>
        </div>

        <?php if(isset($_SESSION['sucesso'])): ?>
            <div class="sucesso"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['erro'])): ?>
            <div class="erro"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
        <?php endif; ?>

        <div class="content">
            <div class="form-section">
                <h2>Nova Solicitação de Viatura</h2>
                <form id="solicitacaoForm" action="../actions/criar_solicitacao.php" method="POST">
                    <div class="input-group">
                        <label for="solicitante">Solicitante:</label>
                        <input type="text" id="solicitante" name="solicitante" required>
                    </div>
                    <div class="input-group">
                        <label for="viatura">Viatura:</label>
                        <input type="text" id="viatura" name="viatura" required>
                    </div>
                    <div class="input-group">
                        <label for="data_hora">Data e Hora:</label>
                        <input type="datetime-local" id="data_hora" name="data_hora" required>
                    </div>
                    <div class="input-group">
                        <label for="odometro_saida">Odômetro de Saída (Km):</label>
                        <input type="number" id="odometro_saida" name="odometro_saida" required min="0">
                    </div>
                    <div class="input-group">
                        <label for="motivo">Motivo:</label>
                        <textarea id="motivo" name="motivo" required></textarea>
                    </div>
                    <button type="submit">Enviar Solicitação</button>
                </form>
            </div>

            <div class="list-section">
                <h2>Solicitações Recentes</h2>
                <div class="solicitacoes-list">
                    <?php if (count($solicitacoes) > 0): ?>
                        <?php foreach ($solicitacoes as $solicitacao): ?>
                            <div class="solicitacao-card">
                                <div class="solicitacao-header">
                                    <span class="viatura"><?php echo htmlspecialchars($solicitacao['viatura']); ?></span>
                                    <span class="status <?php echo strtolower($solicitacao['status']); ?>">
                                        <?php echo htmlspecialchars($solicitacao['status']); ?>
                                    </span>
                                </div>
                                <div class="solicitacao-body">
                                    <p><strong>Solicitante:</strong> <?php echo htmlspecialchars($solicitacao['solicitante']); ?></p>
                                    <p><strong>Data/Hora Saída:</strong> <?php echo date('d/m/Y H:i', strtotime($solicitacao['data_hora'])); ?></p>
                                    <p><strong>Odômetro Saída:</strong> <?php echo number_format($solicitacao['odometro_saida'], 0, ',', '.'); ?> Km</p>
                                    <?php if ($solicitacao['data_hora_chegada']): ?>
                                        <p><strong>Data/Hora Chegada:</strong> <?php echo date('d/m/Y H:i', strtotime($solicitacao['data_hora_chegada'])); ?></p>
                                        <p><strong>Odômetro Chegada:</strong> <?php echo number_format($solicitacao['odometro_chegada'], 0, ',', '.'); ?> Km</p>
                                        <p><strong>Distância Percorrida:</strong> <?php echo number_format($solicitacao['odometro_chegada'] - $solicitacao['odometro_saida'], 0, ',', '.'); ?> Km</p>
                                    <?php endif; ?>
                                    <p><strong>Motivo:</strong> <?php echo htmlspecialchars($solicitacao['motivo']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-results">Nenhuma solicitação encontrada.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>