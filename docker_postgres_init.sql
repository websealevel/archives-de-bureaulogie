CREATE TABLE accounts (
    id serial PRIMARY KEY,
    pseudo VARCHAR (50) UNIQUE NOT NULL,
    password VARCHAR (255) NOT NULL,
    email VARCHAR (255) UNIQUE NOT NULL,
    created_on TIMESTAMP NOT NULL,
    last_login TIMESTAMP,
    has_reached_majority BOOLEAN NOT NULL,
    has_accepted_the_chart BOOLEAN NOT NULL,
    heard_about_bureaulogy VARCHAR(255)
);

CREATE TABLE roles(
    role_id serial PRIMARY KEY,
    role_name VARCHAR (255) UNIQUE NOT NULL
);

CREATE TABLE account_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    grant_date TIMESTAMP,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (role_id) REFERENCES roles (role_id),
    FOREIGN KEY (user_id) REFERENCES accounts (user_id)
);
