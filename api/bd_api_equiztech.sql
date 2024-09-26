create database equiztech_api;

use equiztech_api;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE perguntas(
	id INT AUTO_INCREMENT PRIMARY KEY,
    texto_pergunta TEXT NOT NULL,
    explicacao TEXT,
    ativo CHAR(1) NOT NULL,
    id_categoria INT NULL,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id)
);

CREATE TABLE respostas(
	id INT AUTO_INCREMENT PRIMARY KEY,
    texto_alternativa TEXT NOT NULL,
    correta CHAR(1) NOT NULL,
    id_pergunta INT,
    FOREIGN KEY (id_pergunta) REFERENCES perguntas(id)
);

CREATE TABLE nivel_acesso(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

INSERT INTO nivel_acesso (nome) VALUES ('Administrador');
INSERT INTO nivel_acesso (nome) VALUES ('Jogador');

CREATE TABLE dados_pessoais(
	id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    sobrenome VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    biografia VARCHAR(2000),
    nome_foto VARCHAR(255)
);

CREATE TABLE usuarios(
	id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    termos_condicoes CHAR(1) NOT NULL,
    id_dados_pessoais INT NOT NULL,
    id_nivel_acesso INT NOT NULL,
    FOREIGN KEY (id_dados_pessoais) REFERENCES dados_pessoais(id),
    FOREIGN KEY (id_nivel_acesso) REFERENCES nivel_acesso(id)
);

-- INSERT USUARIO PADRÂO 
insert into dados_pessoais (nome, sobrenome, data_nascimento)
values ('Rafael', 'Briet', '1991-12-14'); 
   
insert into usuarios (email, senha, termos_condicoes, id_dados_pessoais, id_nivel_acesso)
values ('rafael@mail.com', '$2y$10$fC8ldodiZQ.LlPmqKDGWjORKxC5Jesok93UKB8OeilET1aUq0fTey', 1, 1, 1);