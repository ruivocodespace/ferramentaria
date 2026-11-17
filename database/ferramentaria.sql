CREATE DATABASE IF NOT EXISTS ferramentaria
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ferramentaria;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('tecnico', 'supervisor') NOT NULL DEFAULT 'tecnico'
) ENGINE=InnoDB;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE ferramentas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    categoria_id INT NOT NULL,
    quantidade_total INT NOT NULL DEFAULT 0,
    quantidade_disponivel INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_ferramentas_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    CONSTRAINT chk_ferramentas_quantidades
        CHECK (
            quantidade_total >= 0
            AND quantidade_disponivel >= 0
            AND quantidade_disponivel <= quantidade_total
        )
) ENGINE=InnoDB;

CREATE TABLE colaboradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    matricula VARCHAR(30) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE emprestimos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ferramenta_id INT NOT NULL,
    colaborador_id INT NOT NULL,
    usuario_id INT NOT NULL,
    quantidade INT NOT NULL,
    data_emprestimo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_emprestimos_ferramenta
        FOREIGN KEY (ferramenta_id) REFERENCES ferramentas(id),
    CONSTRAINT fk_emprestimos_colaborador
        FOREIGN KEY (colaborador_id) REFERENCES colaboradores(id),
    CONSTRAINT fk_emprestimos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    CONSTRAINT chk_emprestimos_quantidade
        CHECK (quantidade > 0)
) ENGINE=InnoDB;

CREATE TABLE devolucoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emprestimo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    quantidade INT NOT NULL,
    data_devolucao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_devolucoes_emprestimo
        FOREIGN KEY (emprestimo_id) REFERENCES emprestimos(id),
    CONSTRAINT fk_devolucoes_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    CONSTRAINT chk_devolucoes_quantidade
        CHECK (quantidade > 0)
) ENGINE=InnoDB;
