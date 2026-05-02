CREATE TABLE IF NOT EXISTS users (
    id              CHAR(36)     NOT NULL PRIMARY KEY,
    username        VARCHAR(100) NOT NULL,
    phone_number    VARCHAR(20)  NOT NULL,
    link_token      CHAR(64)     NOT NULL,
    link_expires_at DATETIME     NOT NULL,
    link_active     TINYINT(1)   NOT NULL DEFAULT 1,
    created_at      DATETIME     NOT NULL,
    UNIQUE KEY uq_link_token (link_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS game_results (
    id             CHAR(36)       NOT NULL PRIMARY KEY,
    user_id        CHAR(36)       NOT NULL,
    rolled_number  SMALLINT       NOT NULL,
    outcome        ENUM('win','lose') NOT NULL,
    win_amount     DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
    played_at      DATETIME       NOT NULL,
    CONSTRAINT fk_game_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_played (user_id, played_at DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
