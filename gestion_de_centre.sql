    CREATE DATABASE gestion_de_centre;

    CREATE TABLE Formateur (
    id_formateur INT PRIMARY KEY,
    nom VARCHAR(25),
    prenom VARCHAR(25),
    email VARCHAR(25),
    mot_de_passe VARCHAR(30)
    );

    CREATE TABLE Apprenant (
    id_apprenant INT PRIMARY KEY,
    nom VARCHAR(25),
    prenom VARCHAR(25),
    email VARCHAR(25),
    mot_de_passe VARCHAR(30)
    );

    CREATE TABLE Formation (
    id_formation INT PRIMARY KEY,
    categorie VARCHAR(50),
    description VARCHAR(100),
    masse_horaire INT,
    sujet VARCHAR(100)
    );

    CREATE TABLE Session (
    id_session INT PRIMARY KEY,
    date_debut DATE,
    date_fin DATE,
    nombre_de_places INT,
    etat VARCHAR(50),
    id_formation INT,
    id_formateur INT,
    FOREIGN KEY (id_formation)
        REFERENCES Formation(id_formation),
    FOREIGN KEY (id_formateur)
        REFERENCES Formateur(id_formateur)
    );

    CREATE TABLE Evaluation (
    id_session INT,
    id_apprenant INT,
    date_evaluation DATE,
    resultat VARCHAR(50),
    PRIMARY KEY (id_session, id_apprenant),
    FOREIGN KEY (id_session)
        REFERENCES Session(id_session),
    FOREIGN KEY (id_apprenant)
        REFERENCES Apprenant(id_apprenant)
    );

    Constraint