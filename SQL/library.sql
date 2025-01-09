-- Création de la table `livres`
CREATE TABLE `livres` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `titre` VARCHAR(255) NOT NULL,
    `auteur` VARCHAR(255) NOT NULL,
    `date_publication` DATE NOT NULL,
    `isbn` VARCHAR(13) NOT NULL,
    `description` TEXT NOT NULL,
    `photo_url` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- Insertion des données dans la table `livres`
INSERT INTO
    `livres` (
        `titre`,
        `auteur`,
        `date_publication`,
        `isbn`,
        `description`,
        `photo_url`
    )
VALUES
    (
        'Developpement Web mobile avec HTML, CSS et JavaScript Pour les Nuls',
        'William HARREL',
        '2023-11-09',
        'DHIDZH1374R',
        'Un livre indispensable pour les concepteurs de sites Web pour iPhone, iPad, smartphones et tablettes !',
        'https://cdn.cultura.com/cdn-cgi/image/width=180/media/pim/82_metadata-image-20983225.jpeg'
    ),
    (
        'PHP et MySql pour les Nuls',
        'Janet VALADE',
        '2023-11-14',
        '23R32R2R4',
        'Le livre best-seller sur PHP & MySQL !',
        'https://cdn.cultura.com/cdn-cgi/image/width=830/media/pim/66_metadata-image-20983256.jpeg'
    );

-- Création de la table `utilisateurs`
CREATE TABLE `utilisateurs` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nom` VARCHAR(255) NOT NULL,
    `prenom` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `date_inscription` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `role` VARCHAR(50) NOT NULL DEFAULT 'utilisateur',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- Insertion des données dans la table `utilisateurs`
INSERT INTO
    `utilisateurs` (`nom`, `prenom`, `email`, `mot_de_passe`, `role`)
VALUES
    (
        'Smith',
        'John',
        'john@smith.com',
        '$2y$10$xSEoJGdBwbJXMU3BIRD6xuLh0Be/Bz0D8FxbNbyqzHN3Ovuvfa1O2',
        'admin'
    ),
    (
        'Lord',
        'Marc',
        'marc@lord.com',
        '$2y$10$pY2UHGd6DcGYgmU1HgSgbOrp9g7fuFrAk1B7lIZi7anXzrFAlt5IG',
        'utilisateur'
    );

-- Création de la table `emprunts`
CREATE TABLE `emprunts` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_utilisateur` INT NOT NULL,
    `id_livre` INT NOT NULL,
    `date_emprunt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `date_retour_prevue` DATE NOT NULL,
    `date_retour_effectif` DATE DEFAULT NULL,
    `statut` ENUM ('en cours', 'retourné') NOT NULL DEFAULT 'en cours',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- Insertion des données dans la table `emprunts`
INSERT INTO
    `emprunts` (
        `id_utilisateur`,
        `id_livre`,
        `date_retour_prevue`
    )
VALUES
    (1, 1, '2023-11-16'),
    (2, 2, '2023-11-20');
