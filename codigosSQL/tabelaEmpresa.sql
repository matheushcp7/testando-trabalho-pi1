CREATE TABLE empresa (
    cnpj VARCHAR(20) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    endereco VARCHAR(200) NOT NULL,
    descricao TEXT,
    beneficios TEXT,
    cidade VARCHAR(100) NOT NULL,
    telefone VARCHAR(15)
);
