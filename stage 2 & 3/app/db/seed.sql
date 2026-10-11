DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id     INTEGER PRIMARY KEY AUTOINCREMENT,
    username    TEXT NOT NULL UNIQUE,
    password    TEXT NOT NULL,
    full_name   TEXT NOT NULL,
    role        TEXT NOT NULL DEFAULT 'guest',
    bio         TEXT,
    notes       TEXT
);

INSERT INTO users (user_id, username, password, full_name, role, bio, notes)
VALUES (
    1,
    'Javinesh_dev',
    '$2y$10$9vVZz5m9QwkYV1jE1aQkPeB3n8s1m1234567890abcdefghijklm',
    'Javinesh',
    'developer',
    'Lead developer for the CTF platform.',
    'obsidian{1d0r_p3r1m3t3r_byp4ss3d}' 
);

DELETE FROM sqlite_sequence WHERE name = 'users';
INSERT INTO sqlite_sequence (name, seq) VALUES ('users', 104);

DROP TABLE IF EXISTS sysadmins;

CREATE TABLE sysadmins (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    notes    TEXT
);

INSERT INTO sysadmins (id, username, password, notes)
VALUES (
    1,
    'sysadmin_01',
    'Sup3rS3cr3tSSH!',
    'obsidian{un10n_s3l3ct_d4t4_dump}'
);