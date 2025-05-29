USE Zava;


CREATE TABLE Datos_entrega (
    id_dato INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    calle VARCHAR(50) NOT NULL,
    numero TEXT NOT NULL,
    piso VARCHAR(10),
    detalle_extra TEXT,
);

CREATE TABLE Metodos_de_pago (
    id_metodo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT, FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    calle VARCHAR(50) NOT NULL,
    numero TEXT NOT NULL,
    piso VARCHAR(10),
    detalle_extra TEXT,
);