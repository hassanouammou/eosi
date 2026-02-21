--*** Scheme ***--
DROP SCHEMA public CASCADE;
CREATE SCHEMA public;

CREATE TABLE "User" (
    id                     INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    email                  VARCHAR(100) NOT NULL UNIQUE,
    password               VARCHAR(100) NOT NULL,
    phone_number           VARCHAR(100) NOT NULL UNIQUE,
    role                   VARCHAR(100)  NOT NULL
);

CREATE TABLE "Administrator" (
    id                     INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    firstname              VARCHAR(100) NOT NULL,
    lastname               VARCHAR(100) NOT NULL,
    birth_date             DATE        NOT NULL,
    gender                 VARCHAR(100) NOT NULL,
    photo_name             VARCHAR(100) NOT NULL,
    user_id                INTEGER     NOT NULL,
    CONSTRAINT fk_user     FOREIGN KEY(user_id) REFERENCES "User"(id)
);

CREATE TABLE "Division" (
    id                     INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name                   VARCHAR(100) NOT NULL UNIQUE,
    user_id                INTEGER     NOT NULL,
    CONSTRAINT fk_user     FOREIGN KEY(user_id) REFERENCES "User"(id)
);

CREATE TABLE "Employee" (
    id                     INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    firstname              VARCHAR(100) NOT NULL,
    lastname               VARCHAR(100) NOT NULL,
    birth_date             DATE        NOT NULL, 
    gender                 VARCHAR(100) NOT NULL,
    photo_name             VARCHAR(100) NOT NULL,
    division_id            INTEGER     NOT NULL,
    user_id                INTEGER     NOT NULL,
    CONSTRAINT fk_user     FOREIGN KEY(user_id) REFERENCES "User"(id),
    CONSTRAINT fk_division FOREIGN KEY(division_id) REFERENCES "Division"(id)
);

CREATE TABLE "OutgoingMail" (
    id                     INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    transmitter            VARCHAR(100)  NOT NULL,
    receiver               VARCHAR(100)  NOT NULL,
    number                 VARCHAR(100)  NOT NULL UNIQUE,
    subject                TEXT         NOT NULL,
    transmission_date      DATE         NOT NULL,
    electronic_mail_name   VARCHAR(100) NOT NULL
);

CREATE TABLE "IncomingMail" (
    id                     INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    transmitter            VARCHAR(100)  NOT NULL,
    receiver               VARCHAR(100)  NOT NULL,
    number                 VARCHAR(100)  NOT NULL UNIQUE,
    subject                TEXT         NOT NULL,
    transmission_date      DATE         NOT NULL,
    electronic_mail_name   VARCHAR(100) NOT NULL
);

CREATE TABLE "Service" (
    id                     INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name                   VARCHAR(100)  NOT NULL UNIQUE
);

CREATE TABLE "Notification" (
    id                     INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    transmitter            VARCHAR(100)  NOT NULL,
    receiver               VARCHAR(100)  NOT NULL,
    topic                  VARCHAR(100)  NOT NULL,
    subject                VARCHAR(100)  NOT NULL,
    topic_id               INTEGER      NOT NULL,
    created_at             TIMESTAMP    NOT NULL
);

CREATE TABLE "UserAuth" (
    id                     INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    email                  VARCHAR(100)  NOT NULL UNIQUE,
    hashed_password        VARCHAR(500) NOT NULL,
    begin_reset_password   BOOLEAN      NOT NULL,
    reset_begin_time       TIMESTAMP        NULL,
    reset_end_time         TIMESTAMP        NULL,
    user_id                INTEGER      NOT NULL,
    CONSTRAINT fk_user     FOREIGN KEY(user_id) REFERENCES "User"(id)
);

--*** Triggers ***--
CREATE FUNCTION create_user_authentification_account_function()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO "UserAuth" (email, hashed_password, begin_reset_password, reset_begin_time, reset_end_time, user_id)
	VALUES (NEW.email, MD5(NEW.password), FALSE, NULL, NULL, NEW.id);
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER create_user_authentification_account_trigger
AFTER   INSERT ON "User" FOR EACH ROW
EXECUTE FUNCTION create_user_authentification_account_function();

--*** Inserts ***--
INSERT INTO "User"          (email, password, phone_number, role) VALUES 
('hassanouammou01@gmail.com', 'adminpwd', '+212664618329', 'administrator');
INSERT INTO "Administrator" (firstname, lastname, birth_date, gender, photo_name, user_id) VALUES 
('hassan', 'ouammou', '03-07-2004', 'homme','administrator.svg', 01);

INSERT INTO "User"  (email, password, phone_number, role) VALUES 
('division01@gmail.com', 'divpwd', '+212611223344', 'division'),
('division02@gmail.com', 'divpwd', '+212555667788', 'division'), 
('division03@gmail.com', 'divpwd', '+212999101122', 'division'), 
('division04@gmail.com', 'divpwd', '+212333445566', 'division'), 
('division05@gmail.com', 'divpwd', '+212111223344', 'division'), 
('division06@gmail.com', 'divpwd', '+212777889900', 'division'); 
INSERT INTO "Division" (name, user_id)                    VALUES 
('Division de l''action sociale'                          , 02),
('Division des affaires rurales'                          , 03),
('Division des equipements'                               , 04),
('Division des collectivites locales'                     , 05),
('Division du budget et marches'                          , 06),
('Division des affaires interieures'                      , 07);

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

INSERT INTO "Employee" (firstname, lastname, birth_date, gender, photo_name, division_id, user_id) VALUES 
('Amal'     , 'Ait Taleb'  , '15-02-1990', 'femme', 'user01.svg', 05, 08),
('Hassan'   , 'Boussaid'   , '22-08-1985', 'homme', 'user02.svg', 02, 09),
('Fatima'   , 'Moha'       , '11-04-1992', 'femme', 'user03.svg', 03, 10),
('Mohamed'  , 'Ziani'      , '01-03-1988', 'homme', 'user04.svg', 04, 11),
('Nadia'    , 'Ben Slimane', '18-06-1995', 'femme', 'user05.svg', 05, 12),
('Abdelaziz', 'Moumen'     , '10-01-1980', 'homme', 'user06.svg', 01, 13),
('Rachid'   , 'Bouabdellah', '25-09-1993', 'homme', 'user07.svg', 01, 14),
('Youssef'  , 'Driouch'    , '16-07-1982', 'homme', 'user08.svg', 04, 15),
('Lahcen'   , 'El Bakkali' , '05-11-1991', 'homme', 'user09.svg', 06, 16),
('Oumaima'  , 'Fatihi'     , '22-03-1996', 'femme', 'user10.svg', 06, 17);

INSERT INTO "Service" (name) VALUES 
('Service juridique et du contentieux'),
('Service du Budjet'),
('Service de la formation continue'),
('Service des terres collectives'),
('Service de la logistique et des archives'),
('Service de l''environnement');

INSERT INTO "OutgoingMail" (transmitter, receiver, number, subject, transmission_date, electronic_mail_name)
VALUES
(
    'Division des affaires interieures', 
    'Division des affaires rurales', 
    'NR2024C001', 
    'Attestation de Stage', 
    '15-02-2024', 
    'outgoing-mail-NR2024C001.png'
),
(
    'Division des affaires rurales', 
    'Division des equipements'     , 
    'NR2024C002', 
    'Attestation de Stage', 
    '01-03-2024', 
    'outgoing-mail-NR2024C002.png'
),
(
    'Division des affaires interieures', 
    'Division des collectivites locales', 
    'NR2024C003', 
    'Attestation de Stage', 
    '10-04-2024', 
    'outgoing-mail-NR2024C003.png'
),
(
    'Division des equipements', 
    'Division du budget et marches', 
    'NR2024C004', 
    'Attestation de Stage', 
    '15-05-2024', 
    'outgoing-mail-NR2024C004.png'
),
(
    'Division des collectivites locales', 
    'Division des affaires interieures', 
    'NR2024C005', 
    'Attestation de Stage', 
    '20-06-2024', 
    'outgoing-mail-NR2024C005.png'
),
(
    'Division des affaires rurales', 
    'Division de l''action sociale', 
    'NR2024C006', 
    'Notification of project completion', 
    '01-07-2024', 
    'outgoing-mail-NR2024C006.png'
),
(
    'Division des equipements', 
    'Division des collectivites locales', 
    'NR2024C007', 
    'Attestation de Stage', 
    '10-08-2024', 
    'outgoing-mail-NR2024C007.png'
),
(
    'Division du budget et marches', 
    'Division des affaires interieures', 
    'NR2024C008', 
    'Attestation de Stage', 
    '15-09-2024', 
    'outgoing-mail-NR2024C008.png'
),
(
    'Division des collectivites locales', 
    'Division des affaires rurales', 
    'NR2024C009', 
    'Attestation de Stage', 
    '20-10-2024', 
    'outgoing-mail-NR2024C009.png'
),
(
    'Division de l''action sociale', 
    'Division du budget et marches', 
    'NR2024C010', 
    'Attestation de Stage', 
    '15-11-2024', 
    'outgoing-mail-NR2024C010.png'
),
(
    'Division de l''action sociale', 
    'Division du budget et marches', 
    'NR2024C011', 
    'Attestation de Stage', 
    '15-11-2024', 
    'outgoing-mail-NR2024C011.png'
),
(
    'Division de l''action sociale', 
    'Division des equipements', 
    'NR2024C012', 
    'Attestation de Stage', 
    '15-11-2024', 
    'outgoing-mail-NR2024C012.png'
),
(
    'Division de l''action sociale', 
    'Division des equipements', 
    'NR2024C013', 
    'Attestation de Stage', 
    '15-11-2024', 
    'outgoing-mail-NR2024C013.png'
),
-- 1 Year Ago
(
    'Division des affaires interieures', 
    'Division du budget et marches', 
    'NR2023C001', 
    'Attestation de Stage', 
    '15-02-2023', 
    'outgoing-mail-NR2023C001.png'
),
(
    'Division du budget et marches', 
    'Division des equipements'     , 
    'NR2023C002', 
    'Attestation de Stage', 
    '01-03-2023', 
    'outgoing-mail-NR2023C002.png'
),
(
    'Division des affaires interieures', 
    'Division des collectivites locales', 
    'NR2023C003', 
    'Attestation de Stage', 
    '10-04-2023', 
    'outgoing-mail-NR2023C003.png'
),
(
    'Division des equipements', 
    'Division du budget et marches', 
    'NR2023C004', 
    'Attestation de Stage', 
    '15-05-2023', 
    'outgoing-mail-NR2023C004.png'
),
(
    'Division des collectivites locales', 
    'Division des affaires interieures', 
    'NR2023C005', 
    'Attestation de Stage', 
    '20-06-2023', 
    'outgoing-mail-NR2023C005.png'
),
(
    'Division des affaires rurales', 
    'Division des affaires interieures', 
    'NR2023C006', 
    'Notification of project completion', 
    '01-07-2023', 
    'outgoing-mail-NR2023C006.png'
),
(
    'Division des equipements', 
    'Division des collectivites locales', 
    'NR2023C007', 
    'Attestation de Stage', 
    '10-08-2023', 
    'outgoing-mail-NR2023C007.png'
),
(
    'Division du budget et marches', 
    'Division des affaires interieures', 
    'NR2023C008', 
    'Attestation de Stage', 
    '15-09-2023', 
    'outgoing-mail-NR2023C008.png'
),
(
    'Division des collectivites locales', 
    'Division des affaires rurales', 
    'NR2023C009', 
    'Attestation de Stage', 
    '20-10-2023', 
    'outgoing-mail-NR2023C009.png'
),
(
    'Division de l''action sociale', 
    'Division du budget et marches', 
    'NR2023C010', 
    'Attestation de Stage', 
    '15-11-2023', 
    'outgoing-mail-NR2023C010.png'
),
-- 2 Years Ago
(
    'Division de l''action sociale', 
    'Division des affaires rurales', 
    'NR2022C001', 
    'Attestation de Stage', 
    '15-02-2022', 
    'outgoing-mail-NR2022C001.png'
),
(
    'Division des affaires rurales', 
    'Division des equipements'     , 
    'NR2022C002', 
    'Attestation de Stage', 
    '01-03-2022', 
    'outgoing-mail-NR2022C002.png'
),
(
    'Division de l''action sociale', 
    'Division des collectivites locales', 
    'NR2022C003', 
    'Attestation de Stage', 
    '10-04-2022', 
    'outgoing-mail-NR2022C003.png'
),
(
    'Division des equipements', 
    'Division du budget et marches', 
    'NR2022C004', 
    'Attestation de Stage', 
    '15-05-2022', 
    'outgoing-mail-NR2022C004.png'
),
(
    'Division des collectivites locales', 
    'Division des affaires interieures', 
    'NR2022C005', 
    'Attestation de Stage', 
    '20-06-2022', 
    'outgoing-mail-NR2022C005.png'
),
(
    'Division des affaires rurales', 
    'Division de l''action sociale', 
    'NR2022C006', 
    'Notification of project completion', 
    '01-07-2022', 
    'outgoing-mail-NR2022C006.png'
),
(
    'Division des equipements', 
    'Division des collectivites locales', 
    'NR2022C007', 
    'Attestation de Stage', 
    '10-08-2022', 
    'outgoing-mail-NR2022C007.png'
),
(
    'Division du budget et marches', 
    'Division des affaires interieures', 
    'NR2022C008', 
    'Attestation de Stage', 
    '15-09-2022', 
    'outgoing-mail-NR2022C008.png'
),
(
    'Division des collectivites locales', 
    'Division des affaires rurales', 
    'NR2022C009', 
    'Attestation de Stage', 
    '20-10-2022', 
    'outgoing-mail-NR2022C009.png'
),
(
    'Division de l''action sociale', 
    'Division du budget et marches', 
    'NR2022C010', 
    'Attestation de Stage', 
    '15-11-2022', 
    'outgoing-mail-NR2022C010.png'
),
-- 3 Years Ago
(
    'Division de l''action sociale', 
    'Division des affaires rurales', 
    'NR2021C001', 
    'Attestation de Stage', 
    '15-02-2021', 
    'outgoing-mail-NR2021C001.png'
),
(
    'Division des affaires rurales', 
    'Division des equipements'     , 
    'NR2021C002', 
    'Attestation de Stage', 
    '01-03-2021', 
    'outgoing-mail-NR2021C002.png'
),
(
    'Division de l''action sociale', 
    'Division des collectivites locales', 
    'NR2021C003', 
    'Attestation de Stage', 
    '10-04-2021', 
    'outgoing-mail-NR2021C003.png'
),
(
    'Division des equipements', 
    'Division du budget et marches', 
    'NR2021C004', 
    'Attestation de Stage', 
    '15-05-2021', 
    'outgoing-mail-NR2021C004.png'
),
(
    'Division des collectivites locales', 
    'Division des affaires interieures', 
    'NR2021C005', 
    'Attestation de Stage', 
    '20-06-2021', 
    'outgoing-mail-NR2021C005.png'
),
(
    'Division des affaires rurales', 
    'Division de l''action sociale', 
    'NR2021C006', 
    'Notification of project completion', 
    '01-07-2021', 
    'outgoing-mail-NR2021C006.png'
),
(
    'Division des equipements', 
    'Division des collectivites locales', 
    'NR2021C007', 
    'Attestation de Stage', 
    '10-08-2021', 
    'outgoing-mail-NR2021C007.png'
),
(
    'Division du budget et marches', 
    'Division des affaires interieures', 
    'NR2021C008', 
    'Attestation de Stage', 
    '15-09-2021', 
    'outgoing-mail-NR2021C008.png'
),
(
    'Division des collectivites locales', 
    'Division des affaires rurales', 
    'NR2021C009', 
    'Attestation de Stage', 
    '20-10-2021', 
    'outgoing-mail-NR2021C009.png'
),
(
    'Division de l''action sociale', 
    'Division du budget et marches', 
    'NR2021C010', 
    'Attestation de Stage', 
    '15-11-2021', 
    'outgoing-mail-NR2021C010.png'
),
-- 4 Years Ago
(
    'Division de l''action sociale', 
    'Division des affaires rurales', 
    'NR2020C001', 
    'Attestation de Stage', 
    '15-02-2020', 
    'outgoing-mail-NR2020C001.png'
),
(
    'Division des affaires rurales', 
    'Division des equipements'     , 
    'NR2020C002', 
    'Attestation de Stage', 
    '01-03-2020', 
    'outgoing-mail-NR2020C002.png'
),
(
    'Division de l''action sociale', 
    'Division des collectivites locales', 
    'NR2020C003', 
    'Attestation de Stage', 
    '10-04-2020', 
    'outgoing-mail-NR2020C003.png'
),
(
    'Division des equipements', 
    'Division du budget et marches', 
    'NR2020C004', 
    'Attestation de Stage', 
    '15-05-2020', 
    'outgoing-mail-NR2020C004.png'
),
(
    'Division des collectivites locales', 
    'Division des affaires interieures', 
    'NR2020C005', 
    'Attestation de Stage', 
    '20-06-2020', 
    'outgoing-mail-NR2020C005.png'
),
(
    'Division des affaires rurales', 
    'Division de l''action sociale', 
    'NR2020C006', 
    'Notification of project completion', 
    '01-07-2020', 
    'outgoing-mail-NR2020C006.png'
),
(
    'Division des equipements', 
    'Division des collectivites locales', 
    'NR2020C007', 
    'Attestation de Stage', 
    '10-08-2020', 
    'outgoing-mail-NR2020C007.png'
),
(
    'Division du budget et marches', 
    'Division des affaires interieures', 
    'NR2020C008', 
    'Attestation de Stage', 
    '15-09-2020', 
    'outgoing-mail-NR2020C008.png'
),
(
    'Division des collectivites locales', 
    'Division des affaires rurales', 
    'NR2020C009', 
    'Attestation de Stage', 
    '20-10-2020', 
    'outgoing-mail-NR2020C009.png'
),
(
    'Division de l''action sociale', 
    'Division du budget et marches', 
    'NR2020C010', 
    'Attestation de Stage', 
    '15-11-2020', 
    'outgoing-mail-NR2020C010.png'
);


INSERT INTO "IncomingMail" (transmitter, receiver, number, subject, transmission_date, electronic_mail_name)
VALUES
(
    'Service de la formation continue', 
    'Division des affaires rurales', 
    'NR2024C001', 
    'Attestation de Stage', 
    '15-02-2024', 
    'incoming-mail-NR2024C001.png'
),
(
    'Service des terres collectives', 
    'Division des equipements'     , 
    'NR2024C002', 
    'Attestation de Stage', 
    '01-03-2024', 
    'incoming-mail-NR2024C002.png'
),
(
    'Service de la logistique et des archives', 
    'Division des collectivites locales', 
    'NR2024C003', 
    'Attestation de Stage', 
    '10-04-2024', 
    'incoming-mail-NR2024C003.png'
),
(
    'Service de l''environnement', 
    'Division du budget et marches', 
    'NR2024C004', 
    'Attestation de Stage', 
    '15-05-2024', 
    'incoming-mail-NR2024C004.png'
),
(
    'Service de la formation continue', 
    'Division des affaires interieures', 
    'NR2024C005', 
    'Attestation de Stage', 
    '20-06-2024', 
    'incoming-mail-NR2024C005.png'
),
(
    'Service des terres collectives', 
    'Division de l''action sociale', 
    'NR2024C006', 
    'Notification of project completion', 
    '01-07-2024', 
    'incoming-mail-NR2024C006.png'
),
(
    'Service de la logistique et des archives', 
    'Division des collectivites locales', 
    'NR2024C007', 
    'Attestation de Stage', 
    '10-08-2024', 
    'incoming-mail-NR2024C007.png'
),
(
    'Service de l''environnement', 
    'Division des affaires interieures', 
    'NR2024C008', 
    'Attestation de Stage', 
    '15-09-2024', 
    'incoming-mail-NR2024C008.png'
),
(
    'Service de la formation continue', 
    'Division des affaires rurales', 
    'NR2024C009', 
    'Attestation de Stage', 
    '20-10-2024', 
    'incoming-mail-NR2024C009.png'
),
(
    'Service des terres collectives', 
    'Division du budget et marches', 
    'NR2024C010', 
    'Attestation de Stage', 
    '15-11-2024', 
    'incoming-mail-NR2024C010.png'
),
-- 1 Year Ago
(
    'Service de la logistique et des archives', 
    'Division du budget et marches', 
    'NR2023C001', 
    'Attestation de Stage', 
    '15-02-2023', 
    'incoming-mail-NR2023C001.png'
),
(
    'Service de la formation continue', 
    'Division des equipements'     , 
    'NR2023C002', 
    'Attestation de Stage', 
    '01-03-2023', 
    'incoming-mail-NR2023C002.png'
),
(
    'Service de l''environnement', 
    'Division de l''action sociale', 
    'NR2023C003', 
    'Attestation de Stage', 
    '10-04-2023', 
    'incoming-mail-NR2023C003.png'
),
(
    'Service des terres collectives', 
    'Division du budget et marches', 
    'NR2023C004', 
    'Attestation de Stage', 
    '15-05-2023', 
    'incoming-mail-NR2023C004.png'
),
(
    'Service de la logistique et des archives', 
    'Division des affaires interieures', 
    'NR2023C005', 
    'Attestation de Stage', 
    '20-06-2023', 
    'incoming-mail-NR2023C005.png'
),
(
    'Service de l''environnement', 
    'Division des affaires interieures', 
    'NR2023C006', 
    'Notification of project completion', 
    '01-07-2023', 
    'incoming-mail-NR2023C006.png'
),
(
    'Service de la formation continue', 
    'Division des collectivites locales', 
    'NR2023C007', 
    'Attestation de Stage', 
    '10-08-2023', 
    'incoming-mail-NR2023C007.png'
),
(
    'Service des terres collectives', 
    'Division des affaires interieures', 
    'NR2023C008', 
    'Attestation de Stage', 
    '15-09-2023', 
    'incoming-mail-NR2023C008.png'
),
(
    'Service de la logistique et des archives', 
    'Division de l''action sociale', 
    'NR2023C009', 
    'Attestation de Stage', 
    '20-10-2023', 
    'incoming-mail-NR2023C009.png'
),
(
    'Service de l''environnement', 
    'Division de l''action sociale', 
    'NR2023C010', 
    'Attestation de Stage', 
    '15-11-2023', 
    'incoming-mail-NR2023C010.png'
),
-- 2 Years Ago
(
    'Service de la formation continue', 
    'Division des affaires rurales', 
    'NR2022C001', 
    'Attestation de Stage', 
    '15-02-2022', 
    'incoming-mail-NR2022C001.png'
),
(
    'Service des terres collectives', 
    'Division des equipements'     , 
    'NR2022C002', 
    'Attestation de Stage', 
    '01-03-2022', 
    'incoming-mail-NR2022C002.png'
),
(
    'Service de la logistique et des archives', 
    'Division des collectivites locales', 
    'NR2022C003', 
    'Attestation de Stage', 
    '10-04-2022', 
    'incoming-mail-NR2022C003.png'
),
(
    'Service de l''environnement', 
    'Division du budget et marches', 
    'NR2022C004', 
    'Attestation de Stage', 
    '15-05-2022', 
    'incoming-mail-NR2022C004.png'
),
(
    'Service de la formation continue', 
    'Division de l''action sociale', 
    'NR2022C005', 
    'Attestation de Stage', 
    '20-06-2022', 
    'incoming-mail-NR2022C005.png'
),
(
    'Service des terres collectives', 
    'Division de l''action sociale', 
    'NR2022C006', 
    'Notification of project completion', 
    '01-07-2022', 
    'incoming-mail-NR2022C006.png'
),
(
    'Service de la logistique et des archives', 
    'Division des collectivites locales', 
    'NR2022C007', 
    'Attestation de Stage', 
    '10-08-2022', 
    'incoming-mail-NR2022C007.png'
),
(
    'Service de l''environnement', 
    'Division des affaires interieures', 
    'NR2022C008', 
    'Attestation de Stage', 
    '15-09-2022', 
    'incoming-mail-NR2022C008.png'
),
(
    'Service juridique et du contentieux', 
    'Division des affaires rurales', 
    'NR2022C009', 
    'Attestation de Stage', 
    '20-10-2022', 
    'incoming-mail-NR2022C009.png'
),
(
    'Service du Budjet', 
    'Division du budget et marches', 
    'NR2022C010', 
    'Attestation de Stage', 
    '15-11-2022', 
    'incoming-mail-NR2022C010.png'
),
-- 3 Years Ago
(
    'Service juridique et du contentieux', 
    'Division des affaires rurales', 
    'NR2021C001', 
    'Attestation de Stage', 
    '15-02-2021', 
    'incoming-mail-NR2021C001.png'
),
(
    'Service du Budjet', 
    'Division des equipements'     , 
    'NR2021C002', 
    'Attestation de Stage', 
    '01-03-2021', 
    'incoming-mail-NR2021C002.png'
),
(
    'Service juridique et du contentieux', 
    'Division des collectivites locales', 
    'NR2021C003', 
    'Attestation de Stage', 
    '10-04-2021', 
    'incoming-mail-NR2021C003.png'
),
(
    'Service du Budjet', 
    'Division du budget et marches', 
    'NR2021C004', 
    'Attestation de Stage', 
    '15-05-2021', 
    'incoming-mail-NR2021C004.png'
),
(
    'Service juridique et du contentieux', 
    'Division des affaires interieures', 
    'NR2021C005', 
    'Attestation de Stage', 
    '20-06-2021', 
    'incoming-mail-NR2021C005.png'
),
(
    'Service du Budjet', 
    'Division de l''action sociale', 
    'NR2021C006', 
    'Notification of project completion', 
    '01-07-2021', 
    'incoming-mail-NR2021C006.png'
),
(
    'Service juridique et du contentieux', 
    'Division des collectivites locales', 
    'NR2021C007', 
    'Attestation de Stage', 
    '10-08-2021', 
    'incoming-mail-NR2021C007.png'
),
(
    'Service du Budjet', 
    'Division des affaires interieures', 
    'NR2021C008', 
    'Attestation de Stage', 
    '15-09-2021', 
    'incoming-mail-NR2021C008.png'
),
(
    'Service juridique et du contentieux', 
    'Division des affaires rurales', 
    'NR2021C009', 
    'Attestation de Stage', 
    '20-10-2021', 
    'incoming-mail-NR2021C009.png'
),
(
    'Service du Budjet', 
    'Division du budget et marches', 
    'NR2021C010', 
    'Attestation de Stage', 
    '15-11-2021', 
    'incoming-mail-NR2021C010.png'
),
-- 4 Years Ago
(
    'Service juridique et du contentieux', 
    'Division des affaires rurales', 
    'NR2020C001', 
    'Attestation de Stage', 
    '15-02-2020', 
    'incoming-mail-NR2020C001.png'
),
(
    'Service du Budjet', 
    'Division des equipements'     , 
    'NR2020C002', 
    'Attestation de Stage', 
    '01-03-2020', 
    'incoming-mail-NR2020C002.png'
),
(
    'Service des terres collectives', 
    'Division des collectivites locales', 
    'NR2020C003', 
    'Attestation de Stage', 
    '10-04-2020', 
    'incoming-mail-NR2020C003.png'
),
(
    'Service du Budjet', 
    'Division du budget et marches', 
    'NR2020C004', 
    'Attestation de Stage', 
    '15-05-2020', 
    'incoming-mail-NR2020C004.png'
),
(
    'Service de la formation continue', 
    'Division des affaires interieures', 
    'NR2020C005', 
    'Attestation de Stage', 
    '20-06-2020', 
    'incoming-mail-NR2020C005.png'
),
(
    'Service du Budjet', 
    'Division de l''action sociale', 
    'NR2020C006', 
    'Notification of project completion', 
    '01-07-2020', 
    'incoming-mail-NR2020C006.png'
),
(
    'Service juridique et du contentieux', 
    'Division des collectivites locales', 
    'NR2020C007', 
    'Attestation de Stage', 
    '10-08-2020', 
    'incoming-mail-NR2020C007.png'
),
(
    'Service juridique et du contentieux', 
    'Division des affaires interieures', 
    'NR2020C008', 
    'Attestation de Stage', 
    '15-09-2020', 
    'incoming-mail-NR2020C008.png'
),
(
    'Service de la formation continue', 
    'Division des affaires rurales', 
    'NR2020C009', 
    'Attestation de Stage', 
    '20-10-2020', 
    'incoming-mail-NR2020C009.png'
),
(
    'Service juridique et du contentieux', 
    'Division du budget et marches', 
    'NR2020C010', 
    'Attestation de Stage', 
    '15-11-2020', 
    'incoming-mail-NR2020C010.png'
);
