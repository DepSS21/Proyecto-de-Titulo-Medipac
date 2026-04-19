-- ============================================================
-- Script de creación de tablas e inserción de datos de prueba
-- Base de datos: proyecto (SQL Server LocalDB)
-- ============================================================

-- -----------------------------------------------
-- 1. TABLAS PRINCIPALES
-- -----------------------------------------------

CREATE TABLE Paciente (
    id_paciente     INT IDENTITY(1,1) PRIMARY KEY,
    rut_paciente    VARCHAR(20)  NOT NULL,
    nombre          VARCHAR(100) NOT NULL,
    apellido        VARCHAR(100) NOT NULL,
    edad            INT          NOT NULL,
    fecha_nacimiento DATE        NULL,
    condicion_medica VARCHAR(255) NULL,
    sexo            CHAR(1)      NOT NULL CHECK (sexo IN ('M','F')),
    numero_serie    VARCHAR(20)  NOT NULL
);

CREATE TABLE Medico (
    id_medico    INT IDENTITY(1,1) PRIMARY KEY,
    rut          VARCHAR(20)  NOT NULL,
    nombre       VARCHAR(100) NOT NULL,
    especialidad VARCHAR(100) NOT NULL
);

CREATE TABLE Farmaceutico (
    id_farmaceutico INT IDENTITY(1,1) PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    rut_farmaceutico VARCHAR(20) NOT NULL
);

CREATE TABLE Receta (
    id_receta      INT IDENTITY(1,1) PRIMARY KEY,
    fecha_creacion DATETIME     NOT NULL,
    Diagnostico    VARCHAR(100) NOT NULL,
    comentarios    VARCHAR(500) NULL,
    id_medico      INT          NOT NULL REFERENCES Medico(id_medico),
    id_paciente    INT          NOT NULL REFERENCES Paciente(id_paciente)
);

-- Registro de recetas generadas por el médico
CREATE TABLE registro_receta_generada (
    id             INT IDENTITY(1,1) PRIMARY KEY,
    id_receta      INT         NOT NULL REFERENCES Receta(id_receta),
    estado_receta  VARCHAR(50) NOT NULL DEFAULT 'Generada'
);

-- Recetas seleccionadas por el paciente para retiro (con módulo asignado por ML)
CREATE TABLE registro_receta_pendiente (
    id             INT IDENTITY(1,1) PRIMARY KEY,
    id_receta      INT         NOT NULL REFERENCES Receta(id_receta),
    fecha_registro DATETIME    NOT NULL,
    estado_receta  VARCHAR(50) NOT NULL DEFAULT 'Pendiente',
    modulo         CHAR(1)     NOT NULL CHECK (modulo IN ('A','B','C'))
);

-- Recetas entregadas por el farmacéutico
CREATE TABLE registro_receta_entregada (
    id               INT IDENTITY(1,1) PRIMARY KEY,
    id_receta        INT         NOT NULL REFERENCES Receta(id_receta),
    fecha_registro   DATETIME    NOT NULL,
    estado_receta    VARCHAR(50) NOT NULL DEFAULT 'Entregada',
    id_farmaceutico  INT         NOT NULL REFERENCES Farmaceutico(id_farmaceutico)
);


-- -----------------------------------------------
-- 2. DATOS DE PRUEBA
-- -----------------------------------------------

-- Pacientes
-- numero_serie: los últimos 3 caracteres son el código que ingresa el paciente
INSERT INTO Paciente (rut_paciente, nombre, apellido, edad, fecha_nacimiento, condicion_medica, sexo, numero_serie)
VALUES
    ('12345678-9', 'Juan',    'Pérez',    45, '1979-03-15', 'hipertension',  'M', 'ABC123'),
    ('98765432-1', 'María',   'González', 62, '1962-07-22', 'diabetes',      'F', 'XYZ456'),
    ('11111111-1', 'Carlos',  'López',    35, '1989-11-05', 'asma',          'M', 'DEF789'),
    ('22222222-2', 'Ana',     'Martínez', 70, '1954-01-30', 'artritis',      'F', 'GHI012'),
    ('33333333-3', 'Pedro',   'Soto',     55, '1969-06-18', 'colesterol alto','M', 'JKL345');

-- Médicos
INSERT INTO Medico (rut, nombre, especialidad)
VALUES
    ('15000000-0', 'Dr. Roberto Silva',   'Medicina General'),
    ('16000000-0', 'Dra. Carmen Rojas',   'Cardiología'),
    ('17000000-0', 'Dr. Andrés Muñoz',    'Endocrinología');

-- Farmacéuticos
INSERT INTO Farmaceutico (nombre, rut_farmaceutico)
VALUES
    ('Luis Farma',   '18000000-0'),
    ('Paula Campos', '19000000-0');

-- Recetas
INSERT INTO Receta (fecha_creacion, Diagnostico, comentarios, id_medico, id_paciente)
VALUES
    (GETDATE(), 'hipertension',    'Tomar con agua, 1 vez al día',    1, 1),
    (GETDATE(), 'diabetes',        'Control en 30 días',              3, 2),
    (GETDATE(), 'asma',            'Usar inhalador según necesidad',  1, 3),
    (GETDATE(), 'artritis',        'Reposo y medicación adjunta',     2, 4),
    (GETDATE(), 'colesterol alto', 'Dieta baja en grasas',            2, 5);

-- Registro de recetas generadas (una por cada receta creada)
INSERT INTO registro_receta_generada (id_receta, estado_receta)
VALUES (1, 'Generada'),
       (2, 'Generada'),
       (3, 'Generada'),
       (4, 'Generada'),
       (5, 'Generada');
