CREATE TABLE roles(
    role_id INT PRIMARY KEY,
    role VARCHAR (255) UNIQUE NOT NULL,
    role_label VARCHAR (255) NOT NULL
);

CREATE TABLE capabilities (
    cap_id INT PRIMARY KEY,
    cap VARCHAR (255) UNIQUE NOT NULL,
    cap_label VARCHAR (255) NOT NULL
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

-- Table de jointure roles-capabilities.
CREATE TABLE roles_capabilities (
    role_id INT NOT NULL,
    cap_id INT NOT NULL,
    PRIMARY KEY (role_id, cap_id),
    CONSTRAINT fk_cap FOREIGN KEY (cap_id) REFERENCES capabilities(cap_id),
    CONSTRAINT fk_role FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

-- Inserer les roles.
INSERT INTO
    roles(role_id, role, role_label)
VALUES
    (1, 'superadmin', 'super administrateur'),
    (2, 'admin', 'administrateur'),
    (3, 'moderateur', 'modérateur'),
    (4, 'contributeur', 'contributeur');

-- Inserer les capabilites.
INSERT INTO
    capabilities(cap_id, cap, cap_label)
VALUES
    (
        1,
        'add_admin',
        'Ajouter un compte administrateur'
    ),
    (
        2,
        'add_moderator',
        'Ajouter un compte modérateur'
    ),
    (
        3,
        'edit_all_references',
        'Éditer les références bibliographiques de tout le monde'
    ),
    (
        4,
        'list_all_references',
        'Lister les références bibliographiques de tout le monde'
    ),
    (
        5,
        'list_all_clips',
        'Lister les extraits vidéos de tout le monde'
    ),
    (
        6,
        'add_source',
        'Ajouter une vidéo source'
    ),
    (
        7,
        'remove_source',
        'Supprimer une vidéo source'
    ),
    (
        8,
        'remove_clip',
        'Supprimer un clip'
    ),
    (
        9,
        'mod_references',
        'Modérer les références'
    ),
    (
        10,
        'mod_clips',
        'Modérer les extraits vidéos'
    ),
    (
        11,
        'submit_clip',
        'Proposer un extrait vidéo'
    ),
    (
        12,
        'submit_reference',
        'Proposer une référence'
    ),
    (
        13,
        'list_my_references',
        'Lister toutes ses références soumises (approuvées ou non)'
    ),
    (
        14,
        'list_my_clips',
        'Lister tous ses extraits vidéos approuvés'
    ),
    (
        15,
        'list_all_sources',
        'Lister toutes les sources vidéos'
    );

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
    -- Admin
    (2, 2),
    (2, 3),
    (2, 4),
    (2, 5),
    (2, 6),
    (2, 7),
    (2, 8),
    -- superadmin
    (1, 1);

-- Roles et capabilities
-- - superadmin
--   - ajouter un admin
-- - admin 
--   - ajouter un modérateur
--   - éditer ressources bilbio de tout le monde
--   - lister toutes les ressources biblio
--   - lister tous les extraits
--   - ajouter une source
--   - supprimer une source
--   - supprimer un extrait
-- - modérateur
--   - modérer une ressource biblio
--   - modérer un extrait vidéo
-- - contributeur
--   - proposer un extrait vidéo
--   - proposer une ressource biblio
--   - voir ses extraits vidéos par source
--   - voir ses ressources biblios
--   - voir toutes les vidéos sources