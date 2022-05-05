CREATE TABLE roles(
    role_id serial PRIMARY KEY,
    role_name VARCHAR (255) UNIQUE NOT NULL
);

CREATE TABLE capabilities (
    cap_id serial PRIMARY KEY,
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
    FOREIGN KEY (role_id) REFERENCES roles (role_id)
);

-- Table de jointure roles-capabilities.
CREATE TABLE roles_capabilities (
    role_id INT NOT NULL,
    cap_id INT NOT NULL,
    PRIMARY KEY (role_id, cap_id),
    FOREIGN KEY (role_id) REFERENCES roles (role_id)
);

-- Inserer les roles.
INSERT INTO
    roles(role_name)
VALUES
    ('superadmin'),
    ('admin'),
    ('moderateur'),
    ('contributeur');

-- Inserer les capabilites.
INSERT INTO
    capabilities(cap, cap_label)
VALUES
    ('add_admin', 'Ajouter un compte administrateur'),
    ('add_moderator', 'Ajouter un compte modérateur'),
    (
        'edit_all_references',
        'Éditer les références bibliographiques de tout le monde'
    ),
    (
        'list_all_references',
        'Lister les références bibliographiques de tout le monde'
    ),
    (
        'list_all_clips',
        'Lister les extraits vidéos de tout le monde'
    ),
    ('add_source', 'Ajouter une vidéo source'),
    ('remove_source', 'Supprimer une vidéo source'),
    ('remove_clip', 'Supprimer un clip'),
    ('mod_references', 'Modérer les références'),
    ('mod_clips', 'Modérer les extraits vidéos'),
    ('submit_clip', 'Proposer un extrait vidéo'),
    ('submit_reference', 'Proposer une référence'),
    (
        'list_my_references',
        'Lister toutes ses références soumises (approuvées ou non'
    ),
    (
        'list_my_clips',
        'Lister tous ses extraits vidéos approuvés'
    ),
    (
        'list_all_sources',
        'Lister toutes les sources vidéos'
    );

-- - superadmin
--   - tous les droits admin
--   - ajouter un admin
-- - admin 
--   - tous les droits modérateur
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