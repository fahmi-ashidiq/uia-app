-- ============================================================
-- P122 Database Schema
-- Run this FIRST, then import region.sql to populate tb_region
-- ============================================================

CREATE DATABASE IF NOT EXISTS master CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE master;

-- ------------------------------------------------------------
-- Table: tb_users
-- Replaces the old data.json flat file storage
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tb_users (
    user_id        CHAR(36)      NOT NULL PRIMARY KEY,
    firstname      VARCHAR(100)  NOT NULL,
    lastname       VARCHAR(100)  NOT NULL,
    gender         CHAR(1)       NOT NULL,
    birthday       DATE          NOT NULL,
    email          VARCHAR(150)  NOT NULL UNIQUE,
    phone          VARCHAR(30)   NOT NULL,
    password       VARCHAR(255)  NOT NULL,
    address        TEXT          NULL,
    image          VARCHAR(255)  NULL,

    -- Region reference (soal #3) - stores both code (source of truth) and
    -- a denormalized name so the profile page can render instantly
    province_code  VARCHAR(20)   NULL,
    province_name  VARCHAR(150)  NULL,
    city_code      VARCHAR(20)   NULL,
    city_name      VARCHAR(150)  NULL,
    district_code  VARCHAR(20)   NULL,
    district_name  VARCHAR(150)  NULL,
    village_code   VARCHAR(20)   NULL,
    village_name   VARCHAR(150)  NULL,

    is_active      BOOLEAN       NOT NULL DEFAULT TRUE,
    created_at     DATETIME      NOT NULL,
    created_by     VARCHAR(50)   NOT NULL DEFAULT 'SYSTEM',
    updated_at     DATETIME      NOT NULL,
    updated_by     VARCHAR(50)   NOT NULL DEFAULT 'SYSTEM',

    INDEX idx_province (province_code),
    INDEX idx_city (city_code),
    INDEX idx_district (district_code),
    INDEX idx_village (village_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table: tb_region (soal #2)
-- code / name / level / parent_code / parent_name
-- level: 1 = Provinsi, 2 = Kab/Kota, 3 = Kecamatan, 4 = Kelurahan/Desa
-- Data is loaded separately from region.sql (INSERT statements only)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tb_region (
    code          VARCHAR(20)   NOT NULL PRIMARY KEY,
    name          VARCHAR(150)  NOT NULL,
    level         TINYINT       NOT NULL,
    parent_code   VARCHAR(20)   NULL,
    parent_name   VARCHAR(150)  NULL,
    is_active     BOOLEAN       NOT NULL DEFAULT TRUE,
    created_by    VARCHAR(50)   NULL,
    created_at    DATETIME      NULL,
    updated_by    VARCHAR(50)   NULL,
    updated_at    DATETIME      NULL,

    INDEX idx_parent_code (parent_code),
    INDEX idx_level (level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table: tb_team (soal #4)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tb_team (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nim           VARCHAR(20)   NOT NULL,
    name          VARCHAR(150)  NOT NULL,
    photo         VARCHAR(255)  NULL,
    contribution  VARCHAR(255)  NOT NULL,
    sort_order    INT           NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample team data - REPLACE with your own group's real data
INSERT INTO tb_team (nim, name, photo, contribution, sort_order) VALUES
('12345678', 'Nama Anggota 1', NULL, 'Backend PHP & Integrasi Database', 1),
('12345679', 'Nama Anggota 2', NULL, 'Frontend, UI/UX & Dropdown Wilayah', 2);

-- ------------------------------------------------------------
-- Migrated seed data from the old data.json.
-- Password for BOTH rows below is: 12345
-- (hashed with bcrypt, verified to work with PHP's password_verify())
-- Feel free to delete these rows once you have registered your own account.
-- ------------------------------------------------------------
INSERT INTO tb_users
(user_id, firstname, lastname, gender, birthday, email, phone, password, is_active, created_at, created_by, updated_at, updated_by)
VALUES
('5d1fc3d7-0118-404d-9178-faa39d5e3b4c', 'DADA', 'SUHENDRA', 'M', '2005-05-28', 'dadasuhendra052805@gmail.com', '0895351205809', '$2b$10$pkYiYQ55KAadoapSORIEWeq2BuU.4ogscD2tnnSya4ase4C7.3eyu', TRUE, '2026-06-24 13:10:45', 'SYSTEM', '2026-06-24 13:10:45', 'SYSTEM'),
('35ee3bcd-c4a4-4596-af48-5494757ce729', 'Fahmi', 'Ashidiq', 'M', '2026-07-22', 'fahmiashidiq2608@gmail.com', '986289648962958', '$2b$10$pkYiYQ55KAadoapSORIEWeq2BuU.4ogscD2tnnSya4ase4C7.3eyu', TRUE, '2026-07-07 12:17:37', 'SYSTEM', '2026-07-07 12:17:37', 'SYSTEM');
