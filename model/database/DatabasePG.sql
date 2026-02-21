--*** Scheme ***--
DROP SCHEMA public CASCADE;
CREATE SCHEMA public;

--*** Tables ***
CREATE TABLE "User" (
    id           INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    email        VARCHAR(100) NOT NULL UNIQUE,
    password     VARCHAR(100) NOT NULL,
    phone_number VARCHAR(100) NOT NULL UNIQUE,
    role         VARCHAR(100) NOT NULL
);

CREATE TABLE "Administrator" (
    id         INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    firstname  VARCHAR(100) NOT NULL,
    lastname   VARCHAR(100) NOT NULL,
    birth_date DATE        NOT NULL,
    gender     VARCHAR(100) NOT NULL,
    photo_name VARCHAR(100) NOT NULL,
    user_id    INTEGER     NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY(user_id) REFERENCES "User"(id)
);

CREATE TABLE "Division" (
    id      INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name    VARCHAR(100) NOT NULL UNIQUE,
    user_id INTEGER     NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY(user_id) REFERENCES "User"(id)
);

CREATE TABLE "Employee" (
    id          INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    firstname   VARCHAR(100) NOT NULL,
    lastname    VARCHAR(100) NOT NULL,
    birth_date  DATE        NOT NULL,
    gender      VARCHAR(100) NOT NULL,
    photo_name  VARCHAR(100) NOT NULL,
    division_id INTEGER     NOT NULL,
    user_id     INTEGER     NOT NULL,
    CONSTRAINT fk_user     FOREIGN KEY(user_id) REFERENCES "User"(id),
    CONSTRAINT fk_division FOREIGN KEY(division_id) REFERENCES "Division"(id)
);

CREATE TABLE "OutgoingMail" (
    id                   INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    transmitter          VARCHAR(100) NOT NULL,
    receiver             VARCHAR(100) NOT NULL,
    number               VARCHAR(100) NOT NULL UNIQUE,
    subject              TEXT        NOT NULL,
    transmission_date    DATE        NOT NULL,
    electronic_mail_name VARCHAR(100) NOT NULL
);

CREATE TABLE "IncomingMail" (
    id                   INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    transmitter          VARCHAR(100) NOT NULL,
    receiver             VARCHAR(100) NOT NULL,
    number               VARCHAR(100) NOT NULL UNIQUE,
    subject              TEXT        NOT NULL,
    transmission_date    DATE        NOT NULL,
    electronic_mail_name VARCHAR(100) NOT NULL
);

CREATE TABLE "Service" (
    id   INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE "Notification" (
    id         INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    transmitter VARCHAR(100) NOT NULL,
    receiver    VARCHAR(100) NOT NULL,
    topic       VARCHAR(100) NOT NULL,
    subject     VARCHAR(100) NOT NULL,
    topic_id    INTEGER NOT NULL,
    created_at  TIMESTAMP NOT NULL
);

CREATE TABLE "UserAuth" (
    id                   INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    email                VARCHAR(100) NOT NULL UNIQUE,
    hashed_password      VARCHAR(500) NOT NULL,
    begin_reset_password BOOLEAN NOT NULL,
    reset_begin_time     TIMESTAMP NULL,
    reset_end_time       TIMESTAMP NULL,
    user_id              INTEGER NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY(user_id) REFERENCES "User"(id)
);

--*** Triggers ***
CREATE FUNCTION create_user_authentification_account_function()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO "UserAuth" (email, hashed_password, begin_reset_password, reset_begin_time, reset_end_time, user_id)
      VALUES (NEW.email, MD5(NEW.password), FALSE, NULL, NULL, NEW.id);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER create_user_authentification_account_trigger
AFTER INSERT ON "User" FOR EACH ROW
EXECUTE FUNCTION create_user_authentification_account_function();

--*** Inserts ***

-- Users
INSERT INTO "User" (email, password, phone_number, role) VALUES
('hassanouammou01@gmail.com', 'adminpwd', '+212664618329', 'administrator');

-- Administrators
INSERT INTO "Administrator" (firstname, lastname, birth_date, gender, photo_name, user_id) VALUES
('hassan', 'ouammou', '2004-07-03', 'homme','administrator.svg', 1);

-- Division users
INSERT INTO "User" (email, password, phone_number, role) VALUES
('division01@gmail.com', 'divpwd', '+212611223344', 'division'),
('division02@gmail.com', 'divpwd', '+212555667788', 'division'),
('division03@gmail.com', 'divpwd', '+212999101122', 'division'),
('division04@gmail.com', 'divpwd', '+212333445566', 'division'),
('division05@gmail.com', 'divpwd', '+212111223344', 'division'),
('division06@gmail.com', 'divpwd', '+212777889900', 'division');

-- Divisions
INSERT INTO "Division" (name, user_id) VALUES
('Division de l''action sociale', 2),
('Division des affaires rurales', 3),
('Division des equipements', 4),
('Division des collectivites locales', 5),
('Division du budget et marches', 6),
('Division des affaires interieures', 7);

-- Employee users
INSERT INTO "User" (email, password, phone_number, role) VALUES
('empluser01@gmail.com', 'emppwd', '+212111213141', 'employee'),
('empluser02@gmail.com', 'emppwd', '+212222324252', 'employee'),
('empluser03@gmail.com', 'emppwd', '+212333435363', 'employee'),
('empluser04@gmail.com', 'emppwd', '+212444546474', 'employee'),
('empluser05@gmail.com', 'emppwd', '+212555657585', 'employee'),
('empluser06@gmail.com', 'emppwd', '+212666768696', 'employee'),
('empluser07@gmail.com', 'emppwd', '+212777879700', 'employee'),
('empluser08@gmail.com', 'emppwd', '+212888980818', 'employee'),
('empluser09@gmail.com', 'emppwd', '+212999192939', 'employee'),
('empluser10@gmail.com', 'emppwd', '+212000102030', 'employee');

-- Employees
INSERT INTO "Employee" (firstname, lastname, birth_date, gender, photo_name, division_id, user_id) VALUES
('Amal'     , 'Ait Taleb'  , '1990-02-15', 'femme', 'user01.svg', 5, 8),
('Hassan'   , 'Boussaid'   , '1985-08-22', 'homme', 'user02.svg', 2, 9),
('Fatima'   , 'Moha'       , '1992-04-11', 'femme', 'user03.svg', 3, 10),
('Mohamed'  , 'Ziani'      , '1988-03-01', 'homme', 'user04.svg', 4, 11),
('Nadia'    , 'Ben Slimane', '1995-06-18', 'femme', 'user05.svg', 5, 12),
('Abdelaziz', 'Moumen'     , '1980-01-10', 'homme', 'user06.svg', 1, 13),
('Rachid'   , 'Bouabdellah', '1993-09-25', 'homme', 'user07.svg', 1, 14),
('Youssef'  , 'Driouch'    , '1982-07-16', 'homme', 'user08.svg', 4, 15),
('Lahcen'   , 'El Bakkali' , '1991-11-05', 'homme', 'user09.svg', 6, 16),
('Oumaima'  , 'Fatihi'     , '1996-03-22', 'femme', 'user10.svg', 6, 17);

-- Services
INSERT INTO "Service" (name) VALUES
('Service juridique et du contentieux'),
('Service du Budjet'),
('Service de la formation continue'),
('Service des terres collectives'),
('Service de la logistique et des archives'),
('Service de l''environnement');

-- OutgoingMail complet (exemple toutes lignes jusqu'à la dernière fournie)
INSERT INTO "OutgoingMail" (transmitter, receiver, number, subject, transmission_date, electronic_mail_name) VALUES
('Division des affaires interieures', 'Division des affaires rurales', 'NR2020C001', 'Attestation de Stage', '2020-02-15', 'outgoing-mail-NR2020C001.png'),
('Division des affaires rurales', 'Division des equipements', 'NR2020C002', 'Attestation de Stage', '2020-03-01', 'outgoing-mail-NR2020C002.png'),
('Division des affaires interieures', 'Division des affaires rurales', 'NR2021C001', 'Attestation de Stage', '2021-02-15', 'outgoing-mail-NR2021C001.png'),
('Division des affaires rurales', 'Division des equipements', 'NR2021C002', 'Attestation de Stage', '2021-03-01', 'outgoing-mail-NR2021C002.png'),
('Division des affaires interieures', 'Division des affaires rurales', 'NR2022C001', 'Attestation de Stage', '2022-02-15', 'outgoing-mail-NR2022C001.png'),
('Division des affaires rurales', 'Division des equipements', 'NR2022C002', 'Attestation de Stage', '2022-03-01', 'outgoing-mail-NR2022C002.png'),
('Division des affaires interieures', 'Division des affaires rurales', 'NR2023C001', 'Attestation de Stage', '2023-02-15', 'outgoing-mail-NR2023C001.png'),
('Division des affaires rurales', 'Division des equipements', 'NR2023C002', 'Attestation de Stage', '2023-03-01', 'outgoing-mail-NR2023C002.png'),
('Division des affaires interieures', 'Division des affaires rurales', 'NR2024C001', 'Attestation de Stage', '2024-02-15', 'outgoing-mail-NR2024C001.png'),
('Division des affaires rurales', 'Division des equipements', 'NR2024C002', 'Attestation de Stage', '2024-03-01', 'outgoing-mail-NR2024C002.png');

-- IncomingMail complet
INSERT INTO "IncomingMail" (transmitter, receiver, number, subject, transmission_date, electronic_mail_name) VALUES
('Service de la formation continue', 'Division des affaires rurales', 'NR2020C001', 'Attestation de Stage', '2020-02-15', 'incoming-mail-NR2020C001.png'),
('Service des terres collectives', 'Division des equipements', 'NR2020C002', 'Attestation de Stage', '2020-03-01', 'incoming-mail-NR2020C002.png'),
('Service de la formation continue', 'Division des affaires rurales', 'NR2021C001', 'Attestation de Stage', '2021-02-15', 'incoming-mail-NR2021C001.png'),
('Service des terres collectives', 'Division des equipements', 'NR2021C002', 'Attestation de Stage', '2021-03-01', 'incoming-mail-NR2021C002.png'),
('Service de la formation continue', 'Division des affaires rurales', 'NR2022C001', 'Attestation de Stage', '2022-02-15', 'incoming-mail-NR2022C001.png'),
('Service des terres collectives', 'Division des equipements', 'NR2022C002', 'Attestation de Stage', '2022-03-01', 'incoming-mail-NR2022C002.png'),
('Service de la formation continue', 'Division des affaires rurales', 'NR2023C001', 'Attestation de Stage', '2023-02-15', 'incoming-mail-NR2023C001.png'),
('Service des terres collectives', 'Division des equipements', 'NR2023C002', 'Attestation de Stage', '2023-03-01', 'incoming-mail-NR2023C002.png'),
('Service de la formation continue', 'Division des affaires rurales', 'NR2024C001', 'Attestation de Stage', '2024-02-15', 'incoming-mail-NR2024C001.png'),
('Service des terres collectives', 'Division des equipements', 'NR2024C002', 'Attestation de Stage', '2024-03-01', 'incoming-mail-NR2024C002.png');