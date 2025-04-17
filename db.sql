CREATE DATABASE usuarios_gb;

USE usuarios_db;

CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    google_id VARCHAR(255) NOT NULL,
    nombre VARCHAR(255),
    correo VARCHAR(255)
);