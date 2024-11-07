-- Création des tables
CREATE TABLE administrateur (
    id INT NOT NULL AUTO_INCREMENT,
    nomResponsable VARCHAR(30) NOT NULL,
    adresseResponsable VARCHAR(50) NOT NULL,
    cinResponsable VARCHAR(12) NOT NULL,
    contactResponsable VARCHAR(10) NOT NULL,
    compteId INT,
    PRIMARY KEY (id)
);

CREATE TABLE agence (
    id INT NOT NULL AUTO_INCREMENT,
    nomResponsable VARCHAR(30) NOT NULL,
    adresseResponsable VARCHAR(50) NOT NULL,
    cinResponsable VARCHAR(12) NOT NULL,
    contactResponsable VARCHAR(10) NOT NULL,
    communeId INT,
    compteId INT,
    PRIMARY KEY (id)
);

CREATE TABLE anor (
    id INT NOT NULL AUTO_INCREMENT,
    nomResponsable VARCHAR(30) NOT NULL,
    adresseResponsable VARCHAR(50) NOT NULL,
    cinResponsable VARCHAR(12) NOT NULL,
    contactResponsable VARCHAR(10) NOT NULL,
    compteId INT,
    PRIMARY KEY (id)
);

CREATE TABLE collecteurs (
    id INT NOT NULL AUTO_INCREMENT,
    numeroIdentification VARCHAR(30) NOT NULL,
    nom VARCHAR(30) NOT NULL,
    prenom VARCHAR(30) NOT NULL,
    adresse VARCHAR(50) NOT NULL,
    sexe VARCHAR(10) NOT NULL,
    cin VARCHAR(12) NOT NULL,
    dateCin DATE NOT NULL,
    lieuCin VARCHAR(30) NOT NULL,
    lieuOctroit VARCHAR(30) NOT NULL,
    dateOctroit DATE NOT NULL,
    validateAnnee VARCHAR(4) NOT NULL,
    photo VARCHAR(30) NOT NULL,
    attestation VARCHAR(30) NOT NULL,
    stockCollecteur INT NOT NULL,
    communeId INT,
    PRIMARY KEY (id)
);

CREATE TABLE commune (
    id INT NOT NULL AUTO_INCREMENT,
    nomCommune VARCHAR(30) NOT NULL,
    districtId INT,
    PRIMARY KEY (id)
);

CREATE TABLE compte (
    id INT NOT NULL AUTO_INCREMENT,
    utilisateur VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    motDePasse VARCHAR(100) NOT NULL,
    photo VARCHAR(20) NOT NULL,
    role VARCHAR(20) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE comptoir (
    id INT NOT NULL AUTO_INCREMENT,
    nomSociete VARCHAR(50) NOT NULL,
    adresse VARCHAR(50) NOT NULL,
    nifStat VARCHAR(50) NOT NULL,
    dateOuverture DATETIME NOT NULL,
    directeur VARCHAR(50) NOT NULL,
    validation VARCHAR(50) NOT NULL,
    stockComptoir INT NOT NULL,
    arrete VARCHAR(30) NOT NULL,
    compteId INT,
    PRIMARY KEY (id)
);

CREATE TABLE district (
    id INT NOT NULL AUTO_INCREMENT,
    nomDistrict VARCHAR(30) NOT NULL,
    regionId INT,
    PRIMARY KEY (id)
);

CREATE TABLE orpailleurs (
    id INT NOT NULL AUTO_INCREMENT,
    numeroIdentification VARCHAR(30) NOT NULL,
    nom VARCHAR(30) NOT NULL,
    prenom VARCHAR(30) NOT NULL,
    adresse VARCHAR(50) NOT NULL,
    sexe VARCHAR(10) NOT NULL,
    cin VARCHAR(12) NOT NULL,
    dateCin DATE NOT NULL,
    lieuCin VARCHAR(30) NOT NULL,
    lieuOctroit VARCHAR(30) NOT NULL,
    dateOctroit DATE NOT NULL,
    validateAnnee VARCHAR(4) NOT NULL,
    photo VARCHAR(30) NOT NULL,
    stockOrpailleur INT NOT NULL,
    communeId INT,
    PRIMARY KEY (id)
);

CREATE TABLE pays (
    id INT NOT NULL AUTO_INCREMENT,
    nomPays VARCHAR(40) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE production (
    id INT NOT NULL AUTO_INCREMENT,
    dateProduction DATETIME NOT NULL,
    quantite INT NOT NULL,
    orpailleurId INT,
    PRIMARY KEY (id)
);

CREATE TABLE province (
    id INT NOT NULL AUTO_INCREMENT,
    nomProvince VARCHAR(20) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE region (
    id INT NOT NULL AUTO_INCREMENT,
    nomRegion VARCHAR(20) NOT NULL,
    provinceId INT,
    PRIMARY KEY (id)
);

CREATE TABLE registreCollecteur (
    id INT NOT NULL AUTO_INCREMENT,
    date DATETIME NOT NULL,
    quantite INT NOT NULL,
    prix INT NOT NULL,
    lieu VARCHAR(30) NOT NULL,
    comptoirId INT,
    collecteurId INT,
    agenceId INT,
    PRIMARY KEY (id)
);

CREATE TABLE registreComptoir (
    id INT NOT NULL AUTO_INCREMENT,
    date DATETIME NOT NULL,
    quantite INT NOT NULL,
    prix INT NOT NULL,
    paysId INT,
    comptoirId INT,
    anorId INT,
    PRIMARY KEY (id)
);

CREATE TABLE registreOrpailleur (
    id INT NOT NULL AUTO_INCREMENT,
    date DATETIME NOT NULL,
    quantite INT NOT NULL,
    prix INT NOT NULL,
    lieu VARCHAR(30) NOT NULL,
    orpailleurId INT,
    collecteurId INT,
    agenceId INT,
    PRIMARY KEY (id)
);
ALTER TABLE orpailleurs ADD CONSTRAINT FK_commune FOREIGN KEY (communeId) REFERENCES commune(id);
ALTER TABLE region ADD CONSTRAINT FK_province FOREIGN KEY (provinceId) REFERENCES province(id);
ALTER TABLE administrateur ADD CONSTRAINT FK_compte FOREIGN KEY (compteId) REFERENCES compte(id);
ALTER TABLE commune ADD CONSTRAINT FK_district FOREIGN KEY (districtId) REFERENCES district(id);
ALTER TABLE comptoir ADD CONSTRAINT FK_compte FOREIGN KEY (compteId) REFERENCES compte(id);
ALTER TABLE agence ADD CONSTRAINT FK_commune FOREIGN KEY (communeId) REFERENCES commune(id);
ALTER TABLE registreOrpailleur ADD CONSTRAINT FK_collecteur FOREIGN KEY (collecteurId) REFERENCES collecteurs(id);
ALTER TABLE registreCollecteur ADD CONSTRAINT FK_comptoir FOREIGN KEY (comptoirId) REFERENCES comptoir(id);
ALTER TABLE district ADD CONSTRAINT FK_region FOREIGN KEY (regionId) REFERENCES region(id);
ALTER TABLE registreCollecteur ADD CONSTRAINT FK_agence FOREIGN KEY (agenceId) REFERENCES agence(id);
ALTER TABLE registreComptoir ADD CONSTRAINT FK_comptoir FOREIGN KEY (comptoirId) REFERENCES comptoir(id);
ALTER TABLE registreComptoir ADD CONSTRAINT FK_pays FOREIGN KEY (paysId) REFERENCES pays(id);
ALTER TABLE registreCollecteur ADD CONSTRAINT FK_collecteur FOREIGN KEY (collecteurId) REFERENCES collecteurs(id);
ALTER TABLE agence ADD CONSTRAINT FK_compte FOREIGN KEY (compteId) REFERENCES compte(id);
ALTER TABLE collecteurs ADD CONSTRAINT FK_commune FOREIGN KEY (communeId) REFERENCES commune(id);
ALTER TABLE anor ADD CONSTRAINT FK_compte FOREIGN KEY (compteId) REFERENCES compte(id);
ALTER TABLE registreOrpailleur ADD CONSTRAINT FK_orpailleur FOREIGN KEY (orpailleurId) REFERENCES orpailleurs(id);
ALTER TABLE production ADD CONSTRAINT FK_orpailleur FOREIGN KEY (orpailleurId) REFERENCES orpailleurs(id);
ALTER TABLE registreComptoir ADD CONSTRAINT FK_anor FOREIGN KEY (anorId) REFERENCES anor(id);
ALTER TABLE registreOrpailleur ADD CONSTRAINT FK_agence FOREIGN KEY (agenceId) REFERENCES agence(id);
