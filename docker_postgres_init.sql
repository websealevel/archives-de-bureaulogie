CREATE TABLE roles(
    role_id INT PRIMARY KEY,
    role VARCHAR (255) UNIQUE NOT NULL,
    role_label VARCHAR (255) NOT NULL,
    ancestor INT -- l'id du role dont le role hérite
);

CREATE TABLE capabilities (
    cap_id INT PRIMARY KEY,
    cap VARCHAR (255) UNIQUE NOT NULL,
    cap_label VARCHAR (255) NOT NULL,
    require_authentification_check BOOLEAN NOT NULL
);

CREATE TABLE accounts (
    id serial PRIMARY KEY,
    role_id INT NOT NULL,
    pseudo VARCHAR (50) UNIQUE NOT NULL,
    password VARCHAR (255) NOT NULL,
    email VARCHAR (255) UNIQUE NOT NULL,
    created_on TIMESTAMP NOT NULL,
    last_login TIMESTAMP,
    has_reached_majority BOOLEAN NOT NULL,
    has_accepted_the_chart BOOLEAN NOT NULL,
    heard_about_bureaulogy VARCHAR(255),
    CONSTRAINT fk_role FOREIGN KEY (role_id) REFERENCES roles (role_id)
);

-- Table des marqueurs sur des clips
CREATE TABLE clip_markers (
    id SERIAL PRIMARY KEY,
    -- le nom de fichier de la source
    source_name VARCHAR (255) NOT NULL,
    account_id INT NOT NULL,
    timecode_start_in_sec float,
    timecode_end_in_sec float,
    title VARCHAR (255) NOT NULL,
    -- le partager avec les autres ou non (par défaut faux)
    is_shareable BOOLEAN NOT NULL,
    require_authentification_check BOOLEAN NOT NULL,
    CONSTRAINT fk_account FOREIGN KEY (account_id) REFERENCES accounts (id)
);

-- Table de jointure roles-capabilities.
CREATE TABLE roles_capabilities (
    role_id INT NOT NULL,
    cap_id INT NOT NULL,
    PRIMARY KEY (role_id, cap_id),
    CONSTRAINT fk_cap FOREIGN KEY (cap_id) REFERENCES capabilities(cap_id),
    CONSTRAINT fk_role FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

CREATE TABLE downloads (
    id serial PRIMARY KEY,
    url VARCHAR(300) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    format VARCHAR(300) NOT NULL,
    account_id INT NOT NULL,
    process_pid INT,
    -- l'id du processus (si on veut l'arreter)
    progression VARCHAR(20),
    -- progression instantanée de téléchargement
    speed VARCHAR(50),
    -- vitesse instantannée de téléchargement
    totaltime VARCHAR(50),
    -- temps de téléchargement
    state VARCHAR(30) NOT NULL,
    -- pending, downloading, downloaded, failed
    created_on TIMESTAMP NOT NULL,
    CONSTRAINT fk_account FOREIGN KEY (account_id) REFERENCES accounts(id)
);

--reference biblio
CREATE TABLE reference (
    --commun a toutes les refs
    id serial PRIMARY KEY,
    title VARCHAR(300) NOT NULL,
    reference_type_id INT NOT NULL,
    CONSTRAINT fk_type FOREIGN KEY (reference_type_id) REFERENCES reference_type(id),
    path_cover VARCHAR(255) NOT NULL,
    -- champs livre
    editor_id INT NOT NULL,
    CONSTRAINT fk_editor FOREIGN KEY (editor_id) REFERENCES editor(id),
    ISBN INT,
    year_of_publication TIMESTAMP,
    nb_pages INT,
    back_cover VARCHAR(500),
    -- site web
    url VARCHAR(300),
    -- podcast
    show_name VARCHAR(250),
    radio_name VARCHAR(250) -- film
    director_name VARCHAR(250),
    year_of_production VARCHAR(250),
    produceur_name VARCHAR(250),
    -- serie
    nb_seasons INT NOT NULL,
    nb_episodes INT,
    -- chaine YT
    year_of_creation TIMESTAMP NOT NULL,
    nb_followers INT,
    --article scientifique
    journal_name VARCHAR(250) NOT NULL,
    volume INT NOT NULL,
    --magazine, journal
);

-- l'éditeur d'une reference
CREATE TABLE editor(id serial PRIMARY KEY);

CREATE TABLE author(
    id serial PRIMARY KEY,
    first_name VARCHAR(200) NOT NULL,
    last_name VARCHAR(200) NOT NULL,
    biography VARCHAR(800)
);

-- journal, magazine
CREATE TABLE journal(
    id serial PRIMARY KEY,
    name VARCHAR(250) NOT NULL,
    year_of_creation INT NOT NULL
);

-- critique (table de jointure)
-- Contrainte a écrire: il faut journal_id ou author_id mais pas les deux
--                              PK (author_id ou reference_id, year_of_publication, reference_id)
CREATE TABLE critics(
    author_id INT,
    journal_id INT,
    reference_id INT NOT NULL,
    CONSTRAINT fk_author FOREIGN KEY (author_id) REFERENCES author(id),
    CONSTRAINT fk_journal FOREIGN KEY (journal_id) REFERENCES journal(id),
    CONSTRAINT fk_reference FOREIGN KEY (reference_id) REFERENCES reference(id),
    text VARCHAR(800) NOT NULL,
    year_of_publication TIMESTAMP NOT NULL,
);

-- le type de reference
CREATE TABLE reference_type(
    id serial PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
);

-- Inserer les roles.
INSERT INTO
    roles(role_id, role, role_label, ancestor)
VALUES
    (1, 'superadmin', 'super administrateur', 2),
    (2, 'admin', 'administrateur', 3),
    (3, 'moderateur', 'modérateur', 4),
    (4, 'contributeur', 'contributeur', NULL);

-- Inserer les capabilites.
INSERT INTO
    capabilities(
        cap_id,
        cap,
        cap_label,
        require_authentification_check
    )
VALUES
    (
        1,
        'add_admin',
        'Ajouter un compte administrateur',
        true
    ),
    (
        2,
        'add_moderator',
        'Ajouter un compte modérateur',
        true
    ),
    (
        3,
        'edit_all_references',
        'Éditer les références bibliographiques de tout le monde',
        false
    ),
    (
        4,
        'list_all_references',
        'Lister les références bibliographiques de tout le monde',
        false
    ),
    (
        5,
        'list_all_clips',
        'Lister les extraits vidéos de tout le monde',
        false
    ),
    (
        6,
        'add_source',
        'Ajouter une vidéo source',
        true
    ),
    (
        7,
        'remove_source',
        'Supprimer une vidéo source',
        true
    ),
    (
        8,
        'remove_clip',
        'Supprimer un clip',
        false
    ),
    (
        9,
        'mod_references',
        'Modérer les références',
        false
    ),
    (
        10,
        'mod_clips',
        'Modérer les extraits vidéos',
        false
    ),
    (
        11,
        'submit_clip',
        'Proposer un extrait vidéo',
        false
    ),
    (
        12,
        'submit_reference',
        'Proposer une référence',
        false
    ),
    (
        13,
        'list_my_references',
        'Lister toutes ses références soumises (approuvées ou non)',
        false
    ),
    (
        14,
        'list_my_clips',
        'Lister tous ses extraits vidéos approuvés',
        false
    ),
    (
        15,
        'list_all_sources',
        'Lister toutes les sources vidéos',
        false
    ),
    (
        16,
        'ban_contributor',
        'Bannir un compte contributeur',
        true
    ),
    (
        17,
        'ban_moderator',
        'Bannir un compte modérateur',
        true
    ),
    (
        18,
        'ban_admin',
        'Bannir un compte administrateur',
        true
    ),
    (
        19,
        'downgrade_admin',
        'Changer le role d un admin à un role plus bas',
        true
    ),
    (
        20,
        'downgrade_moderator',
        'Changer le role d un modérateur à un role plus bas',
        true
    );

-- Voir documentation.
-- Roles et capabilities
-- - superadmin
--   - ajouter un admin
--   - changer le role de admin à modérateur
-- - admin 
--   - ajouter un modérateur
--   - éditer ressources bilbio de tout le monde
--   - lister toutes les ressources biblio
--   - lister tous les extraits
--   - ajouter une source
--   - supprimer une source
--   - bannir le compte modérateur/contributeur
--   - changer le role de modérateur vers contributeur
-- - modérateur
--   - modérer une ressource biblio
--   - modérer un extrait vidéo
-- - contributeur
--   - proposer un extrait vidéo
--   - proposer une ressource biblio
--   - voir ses extraits vidéos par source
--   - voir ses ressources biblios
INSERT INTO
    roles_capabilities(role_id, cap_id)
VALUES
    -- Contributeur
    (4, 11),
    (4, 12),
    (4, 13),
    (4, 14),
    (4, 15),
    -- Modérateur
    (3, 9),
    (3, 10),
    (3, 16),
    -- Admin
    (2, 2),
    (2, 3),
    (2, 4),
    (2, 5),
    (2, 6),
    (2, 7),
    (2, 8),
    (2, 16),
    (2, 17),
    (2, 20),
    -- superadmin
    (1, 1),
    (1, 17),
    (1, 18),
    (1, 19);