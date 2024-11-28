CREATE TABLE vaga_candidato (
    cpf VARCHAR(14),
    cnpj VARCHAR(20),
    id_post BIGINT,
    PRIMARY KEY (cpf, cnpj, id_post),
    CONSTRAINT fk_vaga_candidato_cpf FOREIGN KEY (cpf)
        REFERENCES candidato(cpf) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_vaga_candidato_cnpj FOREIGN KEY (cnpj)
        REFERENCES empresa(cnpj) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_vaga_candidato_id_post FOREIGN KEY (id_post)
        REFERENCES post(id_post) ON DELETE CASCADE ON UPDATE CASCADE
);
