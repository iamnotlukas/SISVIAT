CREATE DATABASE IF NOT EXISTS sismov;
USE sismov;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo VARCHAR(50) NOT NULL
);

CREATE TABLE solicitacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitante VARCHAR(200) NOT NULL,
    viatura VARCHAR(100) NOT NULL,
    data_hora DATETIME NOT NULL,
    motivo VARCHAR(500) NOT NULL,
    odometro_saida INT NOT NULL,
    odometro_chegada INT NULL,
    data_hora_chegada DATETIME NULL,
    status VARCHAR(100) NOT NULL DEFAULT 'PENDENTE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir usuário padrão para cada tipo
INSERT INTO usuarios (nome, usuario, senha, tipo) VALUES
('Administrador Garagem', 'garagem', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'garagem'),
('Chefe Departamento', 'chefe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'chefe'),
('Sala de Estado', 'sala', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sala_estado');
-- Senha padrão para todos: 'password'