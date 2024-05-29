CREATE DATABASE kali;
CREATE TABLE Aluno (
    nome VARCHAR(100),
    email VARCHAR(100),
    senha VARCHAR(20),
    ID_aluno INT PRIMARY KEY
);
CREATE TABLE Paciente (
    nome VARCHAR(100),
    email VARCHAR(100),
    senha VARCHAR(20),
    ID_paciente INT PRIMARY KEY
);
CREATE TABLE Psicologo (
    nome VARCHAR(100),
    crp VARCHAR(20),
    email VARCHAR(100),
    senha VARCHAR(100),
    ID_psicologo INT PRIMARY KEY
);
ALTER TABLE paciente MODIFY COLUMN ID_paciente INT AUTO_INCREMENT;
ALTER TABLE aluno MODIFY COLUMN ID_aluno INT AUTO_INCREMENT;
ALTER TABLE psicologo MODIFY COLUMN ID_psicologo INT AUTO_INCREMENT;
ALTER TABLE psicologo MODIFY senha VARCHAR(255) NOT NULL;
ALTER TABLE paciente MODIFY senha VARCHAR(255) NOT NULL;
ALTER TABLE aluno MODIFY senha VARCHAR(255) NOT NULL;
CREATE TABLE Consulta (
    ID_consulta INT AUTO_INCREMENT PRIMARY KEY,
    horario DATETIME,
    dia DATE,
    local VARCHAR(100),
    fk_psicologo_ID_psicologo INT,
    fk_Paciente_ID_paciente INT,
    FOREIGN KEY (fk_psicologo_ID_psicologo) REFERENCES psicologo(ID_psicologo),
    FOREIGN KEY (fk_Paciente_ID_paciente) REFERENCES Paciente(ID_paciente)
);
CREATE TABLE Mentoria (
    ID_mentoria INT AUTO_INCREMENT PRIMARY KEY,
    horario TIME NOT NULL,
    dia DATE NOT NULL,
    local VARCHAR(255) NOT NULL,
    fk_psicologo_ID_psicologo INT NOT NULL,
    fk_aluno_ID_aluno INT NOT NULL,
    FOREIGN KEY (fk_psicologo_ID_psicologo) REFERENCES psicologo(ID_psicologo),
    FOREIGN KEY (fk_aluno_ID_aluno) REFERENCES Aluno(ID_aluno)
);
ALTER TABLE Consulta
MODIFY COLUMN horario TIME NOT NULL;

ALTER TABLE psicologo
ADD telefone VARCHAR(20),
ADD descricao TEXT;

ALTER TABLE psicologo
ADD foto_perfil VARCHAR(255);
