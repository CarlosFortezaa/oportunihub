USE defaultdb;

CREATE TABLE users (
    user_id       VARCHAR(50) PRIMARY KEY,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('admin','contributor') NOT NULL DEFAULT 'admin',
    email         VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;


CREATE TABLE opportunities (
    opp_id          INT AUTO_INCREMENT PRIMARY KEY,
    title           VARCHAR(255) NOT NULL,
    description     TEXT NOT NULL,
    sponsor         VARCHAR(255) NOT NULL,
    type            VARCHAR(50) NOT NULL DEFAULT 'Other',
    url             VARCHAR(255),
    attachment_path VARCHAR(255),
    deadline        DATE NULL,
    date_posted     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    posted_by       VARCHAR(50) NOT NULL,
    CONSTRAINT fk_opportunities_users
        FOREIGN KEY (posted_by) REFERENCES users(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE distribution_list (
    email         VARCHAR(100) PRIMARY KEY,
) ENGINE=InnoDB;
