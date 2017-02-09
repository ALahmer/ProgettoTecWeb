CREATE TABLE amministratori(
    nome varchar(15) primary key,
    email varchar(50) UNIQUE not null,
    password varchar(50) not null,
    domanda_sicurezza varchar(50),
    risposta_sicurezza varchar(50),
    cookie varchar(30)
);

CREATE TABLE servizi(
    id tinyint unsigned auto_increment primary key,
    nome varchar(30),
    costo int,
    unita ENUM('persona','appartamento','giornaliero')
);

CREATE TABLE utenti(
    id int unsigned auto_increment primary key,
    nome varchar(20) not null,
    cognome varchar(20) not null,
    cf varchar(20) not null,
    piva varchar(50) default "",
    password varchar(50) not null,
    cookie varchar(30),
    email varchar(50) UNIQUE not null
);

CREATE TABLE appartamenti(
    id varchar(4) primary key,
    max_persone tinyint,
    dimensione int unsigned,
    descrizione TEXT
);

CREATE TABLE prenotazioni(
    id int unsigned auto_increment primary key,
    utente int unsigned not null,
    data_partenza date,
    data_arrivo date,
    stato ENUM('sospeso','arrivo','partenza'),
    numPersone tinyint unsigned,
    appartamento varchar(4) not null,
    FOREIGN KEY (appartamento) REFERENCES appartamenti(id) ON DELETE CASCADE,
    FOREIGN KEY (utente) REFERENCES utenti(id) ON DELETE CASCADE
);

CREATE TABLE prezzi_appartamenti(
    appartamento varchar(4),
    da date,
    a date,
    costo_giornaliero int unsigned,
    primary key(appartamento,da,a),
    FOREIGN KEY (appartamento) REFERENCES appartamenti(id) ON DELETE CASCADE
);
CREATE TABLE servizi_prenotazioni(
    id_prenotazione int unsigned,
    id_servizio tinyint unsigned,
    primary key(id_prenotazione,id_servizio),
    FOREIGN KEY (id_prenotazione) REFERENCES prenotazioni(id) ON DELETE CASCADE,
    FOREIGN KEY (id_servizio) REFERENCES servizi(id) ON DELETE CASCADE
);

SET FOREIGN_KEY_CHECKS=0;

/*INSERT INTO prenotazioni(utente,data_arrivo,data_partenza,appartamento) VALUES 
('1','2017-02-10','2017-02-15','1A'),
('2','2017-02-11','2017-02-15','1B'),
('3','2017-02-10','2017-02-15','1C'),
('2','2017-02-10','2017-02-15','2A'),
('3','2017-02-10','2017-02-15','3D');*/

INSERT INTO utenti(id,nome,cognome,cf,password,email) VALUES 
('1','Abdelilah','Lahmer','LHMBLL94E03Z330S','7bb3f4d62f8dae47fa0ce42502a1ae66','ab-94@outlook.it'),/*USERNAME=ab-94@outlook.it PASSWORD=abdelilah*/
('2','Matteo','Maran','MRNMTT95M27L840Q','150be5b860e60a7fc7c7d9b9815e93d1','teo@gmail.com'),/*USERNAME=maran@gmail.com PASSWORD=matteo*/
('3','Edoardo','Zanon','ZNNDRD95D07C743U','83c44bd267f169a6f37be25bb49d35dc','edo@gmail.com');/*USERNAME=edo@gmail.com PASSWORD=edoardo*/

/*INSERT INTO appartamenti(id,max_persone,dimensione) VALUES
('1A','3','70'),
('1B','4','80'),
('1C','5','90'),
('2A','7','100'),
('3D','10','200');*/

/*INSERT INTO prezzi_appartamenti(appartamento, da, a, costo_giornaliero) VALUES 
('1A','2016-01-01','2016-06-15','10'),
('1A','2016-06-16','2016-12-31','15'),
('1B','2016-12-12','2017-12-12','20'),
('1C','2016-12-12','2017-12-12','30');*/

/*USERNAME=admin@gmail.com PASSWORD=administrator*/
INSERT INTO amministratori(nome,email,password) VALUES
('Admin','admin@gmail.com','200ceb26807d6bf99fd6f4f0d1ca54d4');

SET FOREIGN_KEY_CHECKS=1;