CREATE TABLE post ( 
    id_post BIGINT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(255), descricao VARCHAR(500),
    requisitos VARCHAR(500),
    beneficios VARCHAR(500), 
    localizacao VARCHAR(255),
    cnpj_empresa VARCHAR(20) NOT NULL,
    PRIMARY KEY (id_post),
    FOREIGN KEY (cnpj_empresa) REFERENCES empresa(cnpj) 
);