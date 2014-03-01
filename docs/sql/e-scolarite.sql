CREATE TABLE demandes (id INT AUTO_INCREMENT NOT NULL, etudiant_id VARCHAR(255) DEFAULT NULL, type_demande_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, remarque VARCHAR(255) DEFAULT NULL, status TINYINT(1) DEFAULT NULL, date_reponce DATE DEFAULT NULL, notified TINYINT(1) DEFAULT NULL, INDEX IDX_BD940CBBDDEAB1A3 (etudiant_id), INDEX IDX_BD940CBB9DEA883D (type_demande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE etat_demandes (id INT AUTO_INCREMENT NOT NULL, demande_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, justification VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_2041A42B80E95E18 (demande_id), INDEX IDX_2041A42B642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE type_demandes (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) DEFAULT NULL, max_autorise INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE semestres (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE modules (id INT AUTO_INCREMENT NOT NULL, filiere_id INT DEFAULT NULL, semestre_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) DEFAULT NULL, INDEX IDX_2EB743D7180AA129 (filiere_id), INDEX IDX_2EB743D75577AFDB (semestre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE elements (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) DEFAULT NULL, INDEX IDX_444A075DAFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE etudiants (id VARCHAR(255) NOT NULL, filiere_id INT DEFAULT NULL, cne VARCHAR(255) NOT NULL, cin VARCHAR(255) NOT NULL, date_naissance DATETIME NOT NULL, ville_naissance VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom_ar VARCHAR(255) NOT NULL, prenom_ar VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, annee_inscription INT NOT NULL, annee_depart INT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, INDEX IDX_227C02EB180AA129 (filiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE admins (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, salt VARCHAR(255) DEFAULT NULL, expired TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE filieres (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, effective INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE type_reclamations (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) DEFAULT NULL, max_autorise INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE documents (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, etudiant_id VARCHAR(255) DEFAULT NULL, element_id INT DEFAULT NULL, note DOUBLE PRECISION NOT NULL, remarques VARCHAR(255) DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, annee INT DEFAULT NULL, INDEX IDX_11BA68CDDEAB1A3 (etudiant_id), INDEX IDX_11BA68C1F1F2A24 (element_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE reclamations (id INT AUTO_INCREMENT NOT NULL, etudiant_id VARCHAR(255) DEFAULT NULL, type_reclamation_id INT DEFAULT NULL, objet VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, reponse LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, consulted_at DATETIME DEFAULT NULL, status INT DEFAULT NULL, INDEX IDX_1CAD6B76DDEAB1A3 (etudiant_id), INDEX IDX_1CAD6B767BA88B4D (type_reclamation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE demandes ADD CONSTRAINT FK_BD940CBBDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiants (id);
ALTER TABLE demandes ADD CONSTRAINT FK_BD940CBB9DEA883D FOREIGN KEY (type_demande_id) REFERENCES type_demandes (id);
ALTER TABLE etat_demandes ADD CONSTRAINT FK_2041A42B80E95E18 FOREIGN KEY (demande_id) REFERENCES demandes (id);
ALTER TABLE etat_demandes ADD CONSTRAINT FK_2041A42B642B8210 FOREIGN KEY (admin_id) REFERENCES admins (id);
ALTER TABLE modules ADD CONSTRAINT FK_2EB743D7180AA129 FOREIGN KEY (filiere_id) REFERENCES filieres (id);
ALTER TABLE modules ADD CONSTRAINT FK_2EB743D75577AFDB FOREIGN KEY (semestre_id) REFERENCES semestres (id);
ALTER TABLE elements ADD CONSTRAINT FK_444A075DAFC2B591 FOREIGN KEY (module_id) REFERENCES modules (id);
ALTER TABLE etudiants ADD CONSTRAINT FK_227C02EB180AA129 FOREIGN KEY (filiere_id) REFERENCES filieres (id);
ALTER TABLE notes ADD CONSTRAINT FK_11BA68CDDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiants (id);
ALTER TABLE notes ADD CONSTRAINT FK_11BA68C1F1F2A24 FOREIGN KEY (element_id) REFERENCES elements (id);
ALTER TABLE reclamations ADD CONSTRAINT FK_1CAD6B76DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiants (id);
ALTER TABLE reclamations ADD CONSTRAINT FK_1CAD6B767BA88B4D FOREIGN KEY (type_reclamation_id) REFERENCES type_reclamations (id);
