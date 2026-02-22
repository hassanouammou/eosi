--*** Schema ***--
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
--*** Users ***
INSERT INTO "User" VALUES
(DEFAULT,'hassan.ouammou@province.ma','Adm!n#Prov2024@X9','+212664618329','administrator'),

(DEFAULT,'division.action.sociale@province.ma','Div@Soc!2024#A9','+212611223344','division'),
(DEFAULT,'division.rurale@province.ma','Rur@le#2024!B8','+212555667788','division'),
(DEFAULT,'division.equipements@province.ma','Eqp!2024@C7#','+212999101122','division'),
(DEFAULT,'division.collectivites@province.ma','Col!2024#D6@','+212333445566','division'),
(DEFAULT,'division.budget@province.ma','Budg@2024!E5#','+212111223344','division'),
(DEFAULT,'division.interieures@province.ma','Int@2024#F4!','+212777889900','division'),

(DEFAULT,'amal.aittaleb@province.ma','Emp!Ait#2024@01','+212611213141','employee'),
(DEFAULT,'hassan.boussaid@province.ma','Emp!Bous#2024@02','+212622324252','employee'),
(DEFAULT,'fatima.moha@province.ma','Emp!Moha#2024@03','+212633435363','employee'),
(DEFAULT,'mohamed.ziani@province.ma','Emp!Zian#2024@04','+212644546474','employee'),
(DEFAULT,'nadia.benslimane@province.ma','Emp!Nadi#2024@05','+212655657585','employee'),
(DEFAULT,'abdelaziz.moumen@province.ma','Emp!Moum#2024@06','+212666768696','employee'),
(DEFAULT,'rachid.bouabdellah@province.ma','Emp!Rach#2024@07','+212677879700','employee'),
(DEFAULT,'youssef.driouch@province.ma','Emp!Drio#2024@08','+212688980818','employee'),
(DEFAULT,'lahcen.elbakkali@province.ma','Emp!Bakk#2024@09','+212699192939','employee'),
(DEFAULT,'oumaima.fatihi@province.ma','Emp!Ouma#2024@10','+212600102030','employee');

-- Administrators
INSERT INTO "Administrator" (firstname, lastname, birth_date, gender, photo_name, user_id) VALUES
('Hassan', 'Ouammou', '2004-07-03', 'homme', 'administrator.jpg', 1);

-- Divisions
INSERT INTO "Division" (name, user_id) VALUES
('Division de l''action sociale', 2),
('Division des affaires rurales', 3),
('Division des equipements', 4),
('Division des collectivites locales', 5),
('Division du budget et marches', 6),
('Division des affaires interieures', 7);

-- Employees
INSERT INTO "Employee" (firstname, lastname, birth_date, gender, photo_name, division_id, user_id) VALUES
('Amal'     , 'Ait Taleb'  , '1990-02-15', 'femme', 'employee-amal-aittaleb.svg', 5, 8),
('Hassan'   , 'Boussaid'   , '1985-08-22', 'homme', 'employee-hassan-boussaid.svg', 2, 9),
('Fatima'   , 'Moha'       , '1992-04-11', 'femme', 'employee-fatima-moha.svg', 3, 10),
('Mohamed'  , 'Ziani'      , '1988-03-01', 'homme', 'employee-mohamed-ziani.svg', 4, 11),
('Nadia'    , 'Ben Slimane', '1995-06-18', 'femme', 'employee-nadia-benslimane.svg', 5, 12),
('Abdelaziz', 'Moumen'     , '1980-01-10', 'homme', 'employee-abdelaziz-moumen.svg', 1, 13),
('Rachid'   , 'Bouabdellah', '1993-09-25', 'homme', 'employee-rachid-bouabdellah.svg', 1, 14),
('Youssef'  , 'Driouch'    , '1982-07-16', 'homme', 'employee-youssef-driouch.svg', 4, 15),
('Lahcen'   , 'El Bakkali' , '1991-11-05', 'homme', 'employee-lahcen-elbakkali.svg', 6, 16),
('Oumaima'  , 'Fatihi'     , '1996-03-22', 'femme', 'employee-oumaima-fatihi.svg', 6, 17),
('Imane'    , 'El Idrissi' , '1994-10-12', 'femme', 'employee-imane-elidrissi.svg', 3, 18),
('Soufiane' , 'El Farissi' , '1987-01-20', 'homme', 'employee-soufiane-elfarissi.svg', 2, 19),
('Khadija'  , 'Ouhaddou'   , '1993-12-03', 'femme', 'employee-khadija-ouhaddou.svg', 1, 20),
('Mustapha' , 'Chaoui'     , '1989-09-14', 'homme', 'employee-mustapha-chaoui.svg', 4, 21);

-- Services
INSERT INTO "Service" (name) VALUES
('Service juridique et du contentieux'),
('Service du budget'),
('Service de la formation continue'),
('Service des terres collectives'),
('Service de la logistique et des archives'),
('Service de l''environnement');

-- OutgoingMail (déséquilibré: 12)
INSERT INTO "OutgoingMail" (transmitter, receiver, number, subject, transmission_date, electronic_mail_name) VALUES
('Division des affaires interieures', 'Division des affaires rurales', 'NR2020C001', 'Transmission de dossier administratif', '2020-02-15', 'outgoing-mail-NR2020C001.png'),
('Division des affaires rurales', 'Division des equipements', 'NR2020C002', 'Compte rendu de mission terrain', '2020-03-01', 'outgoing-mail-NR2020C002.png'),
('Division du budget et marches', 'Division des collectivites locales', 'NR2021C001', 'Validation engagement budgétaire', '2021-02-15', 'outgoing-mail-NR2021C001.png'),
('Division des equipements', 'Division des affaires interieures', 'NR2021C002', 'Planification travaux voirie', '2021-03-01', 'outgoing-mail-NR2021C002.png'),
('Division de l''action sociale', 'Division des affaires rurales', 'NR2022C001', 'Convention d''appui social', '2022-02-15', 'outgoing-mail-NR2022C001.png'),
('Division des affaires rurales', 'Division du budget et marches', 'NR2022C002', 'Besoin de crédits supplémentaires', '2022-03-01', 'outgoing-mail-NR2022C002.png'),
('Division des affaires interieures', 'Division des equipements', 'NR2023C001', 'Demande de maintenance parc auto', '2023-02-15', 'outgoing-mail-NR2023C001.png'),
('Division des collectivites locales', 'Division des affaires interieures', 'NR2023C002', 'Suivi des délibérations communales', '2023-03-01', 'outgoing-mail-NR2023C002.png'),
('Division du budget et marches', 'Division de l''action sociale', 'NR2024C001', 'Notification attribution marché', '2024-02-15', 'outgoing-mail-NR2024C001.png'),
('Division des equipements', 'Division des affaires rurales', 'NR2024C002', 'Ordre de service travaux', '2024-03-01', 'outgoing-mail-NR2024C002.png'),
('Division de l''action sociale', 'Division des collectivites locales', 'NR2024C003', 'Programme soutien associations', '2024-04-10', 'outgoing-mail-NR2024C003.png'),
('Division des affaires interieures', 'Division du budget et marches', 'NR2024C004', 'Synthèse mensuelle activités', '2024-05-05', 'outgoing-mail-NR2024C004.png');

-- IncomingMail (déséquilibré: 22)
INSERT INTO "IncomingMail" (transmitter, receiver, number, subject, transmission_date, electronic_mail_name) VALUES
('Service de la formation continue', 'Division des affaires rurales', 'NR2020C001', 'Attestation de stage', '2020-02-15', 'incoming-mail-NR2020C001.png'),
('Service des terres collectives', 'Division des equipements', 'NR2020C002', 'Demande de nivellement', '2020-03-01', 'incoming-mail-NR2020C002.png'),
('Service de l''environnement', 'Division des collectivites locales', 'NR2020C003', 'Contrôle déchets ménagers', '2020-04-12', 'incoming-mail-NR2020C003.png'),
('Service juridique et du contentieux', 'Division des affaires interieures', 'NR2020C004', 'Avis juridique', '2020-05-18', 'incoming-mail-NR2020C004.png'),
('Service de la logistique et des archives', 'Division du budget et marches', 'NR2020C005', 'Inventaire matériel', '2020-06-30', 'incoming-mail-NR2020C005.png'),
('Service de la formation continue', 'Division des affaires rurales', 'NR2021C001', 'Plan de formation', '2021-02-15', 'incoming-mail-NR2021C001.png'),
('Service des terres collectives', 'Division des equipements', 'NR2021C002', 'Mise à jour dossiers fonciers', '2021-03-01', 'incoming-mail-NR2021C002.png'),
('Service de l''environnement', 'Division des affaires rurales', 'NR2021C003', 'Suivi points noirs', '2021-03-21', 'incoming-mail-NR2021C003.png'),
('Service du budget', 'Division du budget et marches', 'NR2021C004', 'Arbitrage crédits', '2021-04-05', 'incoming-mail-NR2021C004.png'),
('Service juridique et du contentieux', 'Division des collectivites locales', 'NR2021C005', 'Contentieux communal', '2021-05-11', 'incoming-mail-NR2021C005.png'),
('Service de la formation continue', 'Division des affaires rurales', 'NR2022C001', 'Attestation de stage', '2022-02-15', 'incoming-mail-NR2022C001.png'),
('Service des terres collectives', 'Division des equipements', 'NR2022C002', 'Programme de bornage', '2022-03-01', 'incoming-mail-NR2022C002.png'),
('Service de la logistique et des archives', 'Division des affaires interieures', 'NR2022C003', 'Transfert archives', '2022-03-24', 'incoming-mail-NR2022C003.png'),
('Service de l''environnement', 'Division des collectivites locales', 'NR2022C004', 'Plan vert provincial', '2022-04-09', 'incoming-mail-NR2022C004.png'),
('Service du budget', 'Division du budget et marches', 'NR2022C005', 'Préparation budget primitif', '2022-05-20', 'incoming-mail-NR2022C005.png'),
('Service de la formation continue', 'Division des affaires rurales', 'NR2023C001', 'Attestation de stage', '2023-02-15', 'incoming-mail-NR2023C001.png'),
('Service des terres collectives', 'Division des equipements', 'NR2023C002', 'Suivi opérations foncières', '2023-03-01', 'incoming-mail-NR2023C002.png'),
('Service de la logistique et des archives', 'Division des affaires interieures', 'NR2023C003', 'Dotation mobilier', '2023-04-03', 'incoming-mail-NR2023C003.png'),
('Service de l''environnement', 'Division de l''action sociale', 'NR2024C001', 'Campagne sensibilisation', '2024-02-15', 'incoming-mail-NR2024C001.png'),
('Service des terres collectives', 'Division des equipements', 'NR2024C002', 'Demande d''expertise topographique', '2024-03-01', 'incoming-mail-NR2024C002.png'),
('Service juridique et du contentieux', 'Division des affaires interieures', 'NR2024C003', 'Mise en demeure', '2024-03-22', 'incoming-mail-NR2024C003.png'),
('Service du budget', 'Division du budget et marches', 'NR2024C004', 'Situation d''exécution budgétaire', '2024-04-12', 'incoming-mail-NR2024C004.png');