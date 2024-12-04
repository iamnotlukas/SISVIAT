<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'sala_estado') {
    header('Location: ../index.php');
    exit;
}

// Buscar todas as solicitações
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
    <title>SISMOV - Sala de Estado</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SISMOV - Sala de Estado</h1>
            <a href="../auth/logout.php" class="logout-btn">Sair</a>
        </div>

        <?php if(isset($_SESSION['sucesso'])): ?>
            <div class="sucesso"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['erro'])): ?>
            <div class="erro"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
        <?php endif; ?>

        <div class="content single-column">
            <div class="list-section full-width">
                <h2>Todas as Solicitações</h2>
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
                                    <?php elseif ($solicitacao['status'] === 'APROVADO'): ?>
                                        <div class="acoes">
                                            <form action="../actions/registrar_chegada.php" method="POST">
                                                <input type="hidden" name="solicitacao_id" value="<?php echo $solicitacao['id']; ?>">
                                                <div class="input-group">
                                                    <label for="data_hora_chegada_<?php echo $solicitacao['id']; ?>">Data/Hora Chegada:</label>
                                                    <input type="datetime-local" id="data_hora_chegada_<?php echo $solicitacao['id']; ?>" 
                                                           name="data_hora_chegada" required>
                                                </div>
                                                <div class="input-group">
                                                    <label for="odometro_chegada_<?php echo $solicitacao['id']; ?>">Odômetro Chegada (Km):</label>
                                                    <input type="number" id="odometro_chegada_<?php echo $solicitacao['id']; ?>" 
                                                           name="odometro_chegada" required min="<?php echo $solicitacao['odometro_saida']; ?>">
                                                </div>
                                                <button type="submit" class="btn-registrar">Registrar Chegada</button>
                                            </form>
                                        </div>
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