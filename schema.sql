-- ============================================================
--  SPORTS ENCYCLOPEDIA — FULL DATABASE SCHEMA v2
--  Includes: roles, statements (chat), reactions, contributions
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

-- ────────────────────────────────────────────────────────────
-- USERS & AUTH
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(200)    NOT NULL,
    email           VARCHAR(255)    NOT NULL UNIQUE,
    password_hash   VARCHAR(255)    NOT NULL,
    role            ENUM('user','admin') NOT NULL DEFAULT 'user',
    avatar          VARCHAR(255),
    remember_token  VARCHAR(64),
    token_expires   DATETIME,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- SPORTS (pivot)
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS sports (
    id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)    NOT NULL UNIQUE,
    header_image    VARCHAR(255),
    logo_image      VARCHAR(255),
    description     TEXT,
    history         TEXT,
    rules           TEXT,
    equipment       TEXT,
    fact            VARCHAR(500),
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sport_stats (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    sport_id    INT UNSIGNED    NOT NULL,
    stat_name   VARCHAR(100)    NOT NULL,
    stat_value  TINYINT UNSIGNED NOT NULL,
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sport_gallery (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    sport_id    INT UNSIGNED    NOT NULL,
    image_path  VARCHAR(255)    NOT NULL,
    sort_order  TINYINT UNSIGNED DEFAULT 0,
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sport_popularity_chart (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    sport_id    INT UNSIGNED    NOT NULL,
    chart_year  SMALLINT        NOT NULL,
    chart_value SMALLINT        NOT NULL,
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sport_skills (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    sport_id    INT UNSIGNED    NOT NULL,
    skill_name  VARCHAR(100)    NOT NULL,
    skill_level TINYINT UNSIGNED NOT NULL,
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- CHAMPIONSHIPS
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS championships (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    sport_id    INT UNSIGNED    NOT NULL,
    name        VARCHAR(200)    NOT NULL,
    image       VARCHAR(255),
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- COUNTRIES
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS countries (
    id          SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)    NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- ATHLETES
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS athletes (
    id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(100)    NOT NULL UNIQUE,
    name            VARCHAR(200)    NOT NULL,
    sport_id        INT UNSIGNED    NOT NULL,
    country_id      SMALLINT UNSIGNED,
    image           VARCHAR(255),
    banner          VARCHAR(255),
    chart_about     VARCHAR(200),
    is_egyptian_champion BOOLEAN   DEFAULT FALSE,
    champion_year   SMALLINT,
    achievements    TEXT,
    created_at      TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sport_id)   REFERENCES sports(id)    ON DELETE RESTRICT,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS athlete_stats (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    athlete_id  INT UNSIGNED    NOT NULL,
    stat_label  VARCHAR(100)    NOT NULL,
    stat_value  INT             NOT NULL,
    FOREIGN KEY (athlete_id) REFERENCES athletes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS athlete_chart (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    athlete_id  INT UNSIGNED    NOT NULL,
    chart_year  SMALLINT        NOT NULL,
    chart_value SMALLINT        NOT NULL,
    FOREIGN KEY (athlete_id) REFERENCES athletes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS athlete_timeline (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    athlete_id  INT UNSIGNED    NOT NULL,
    event_year  SMALLINT        NOT NULL,
    event_text  VARCHAR(500)    NOT NULL,
    FOREIGN KEY (athlete_id) REFERENCES athletes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- CLUBS
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS clubs (
    id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(200)    NOT NULL,
    sport_id        INT UNSIGNED    NOT NULL,
    governorate     VARCHAR(100),
    image           VARCHAR(255),
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- RECORDS
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS records (
    id                  INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    sport_id            INT UNSIGNED    NOT NULL,
    specialty           VARCHAR(200),
    athlete_name        VARCHAR(200)    NOT NULL,
    record_text         VARCHAR(300)    NOT NULL,
    record_date         DATE,
    country_id          SMALLINT UNSIGNED,
    age                 TINYINT UNSIGNED,
    team                VARCHAR(200),
    height              VARCHAR(20),
    weight              VARCHAR(20),
    career_wins         SMALLINT,
    world_ranking       SMALLINT,
    olympic_golds       TINYINT,
    world_championships TINYINT,
    olympic_medals      TINYINT,
    world_cup_wins      SMALLINT,
    is_retired          BOOLEAN         DEFAULT FALSE,
    extra_json          JSON,
    FOREIGN KEY (sport_id)   REFERENCES sports(id)    ON DELETE RESTRICT,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- DASHBOARD
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS dashboard_overview (
    id            INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    year          SMALLINT        NOT NULL UNIQUE,
    records_count SMALLINT        NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS dashboard_ranking (
    id           INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    rank         TINYINT UNSIGNED NOT NULL,
    athlete_name VARCHAR(200)    NOT NULL,
    sport_id     INT UNSIGNED,
    metric       VARCHAR(100),
    metric_year  SMALLINT,
    country_id   SMALLINT UNSIGNED,
    FOREIGN KEY (sport_id)   REFERENCES sports(id)    ON DELETE SET NULL,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS dashboard_sport_stats (
    id                   INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    sport_id             INT UNSIGNED    NOT NULL UNIQUE,
    popularity_score     TINYINT UNSIGNED,
    country              VARCHAR(100),
    stat_year            SMALLINT,
    total_players        BIGINT,
    professional_leagues SMALLINT,
    world_cup_years      TINYINT,
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS dashboard_top_players (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    sport_id    INT UNSIGNED    NOT NULL,
    rank        TINYINT UNSIGNED NOT NULL,
    player_name VARCHAR(200)    NOT NULL,
    country_id  SMALLINT UNSIGNED,
    age         TINYINT,
    stat_year   SMALLINT,
    goals       SMALLINT,
    assists     SMALLINT,
    matches     SMALLINT,
    FOREIGN KEY (sport_id)   REFERENCES sports(id)    ON DELETE CASCADE,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- ADMIN STATEMENTS (Chat page)
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS statements (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    admin_id    INT UNSIGNED    NOT NULL,
    body        TEXT            NOT NULL,
    image       VARCHAR(255),               -- optional image upload
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Emoji reactions by normal users on statements
CREATE TABLE IF NOT EXISTS statement_reactions (
    id           INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    statement_id INT UNSIGNED    NOT NULL,
    user_id      INT UNSIGNED    NOT NULL,
    emoji        VARCHAR(10)     NOT NULL,   -- e.g. "👍","❤️","🔥"
    created_at   TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_stmt (statement_id, user_id),   -- one reaction per user per statement
    FOREIGN KEY (statement_id) REFERENCES statements(id)  ON DELETE CASCADE,
    FOREIGN KEY (user_id)      REFERENCES users(id)        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- CONTRIBUTIONS (user PDF submissions)
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS contributions (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED    NOT NULL,
    title       VARCHAR(300)    NOT NULL,
    description TEXT,
    file_path   VARCHAR(255)    NOT NULL,    -- stored path relative to UPLOAD_DIR
    status      ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    admin_note  TEXT,
    reviewed_at DATETIME,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- FEEDBACK
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS feedback (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED,
    name        VARCHAR(200)    NOT NULL,
    email       VARCHAR(255)    NOT NULL,
    message     TEXT            NOT NULL,
    rating      TINYINT UNSIGNED DEFAULT 0,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ────────────────────────────────────────────────────────────
-- CONTACT MESSAGES
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS contact_messages (
    id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED,
    name        VARCHAR(200)    NOT NULL,
    email       VARCHAR(255)    NOT NULL,
    subject     VARCHAR(300)    NOT NULL,
    message     TEXT            NOT NULL,
    is_read     BOOLEAN         DEFAULT FALSE,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ────────────────────────────────────────────────────────────
-- DEFAULT ADMIN ACCOUNT
-- Password: admin123  (bcrypt)
-- ────────────────────────────────────────────────────────────
INSERT IGNORE INTO users (name, email, password_hash, role)
VALUES (
    'Admin',
    'admin@sports.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
);
