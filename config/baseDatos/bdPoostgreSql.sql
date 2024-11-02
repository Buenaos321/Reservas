CREATE TABLE SALONES (
    ID_SALON SERIAL PRIMARY KEY,
    NOMBRE VARCHAR(255) NOT NULL,
    UBICACION VARCHAR(255) NOT NULL,
    RECURSOS_DISPONIBLES TEXT
);

CREATE TABLE USUARIOS (
    ID_USUARIO SERIAL PRIMARY KEY,
    NOMBRE VARCHAR(255) NOT NULL,
    CORREO VARCHAR(255) UNIQUE NOT NULL,
    PASSWORDHASH VARCHAR(255) NOT NULL
);

CREATE TABLE RESERVAS (
    ID_RESERVA SERIAL PRIMARY KEY,
    ID_SALON INT NOT NULL,
    ID_USUARIO INT NOT NULL,
    FECHA DATE NOT NULL,
    HORA_INICIO TIME NOT NULL,
    HORA_FIN TIME NOT NULL,
    ESTADO CHAR(1) CHECK (ESTADO IN ('A', 'C')) NOT NULL,
    FOREIGN KEY (ID_SALON) REFERENCES SALONES(ID_SALON) ON DELETE CASCADE,
    FOREIGN KEY (ID_USUARIO) REFERENCES USUARIOS(ID_USUARIO) ON DELETE CASCADE,
    UNIQUE (ID_SALON, FECHA, HORA_INICIO, HORA_FIN)
);
