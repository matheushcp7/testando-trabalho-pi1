CREATE TABLE candidato (
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_nasc DATE NOT NULL,
    estado VARCHAR(2) NOT NULL,
    cidade VARCHAR(255) NOT NULL
);
