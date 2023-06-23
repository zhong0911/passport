CREATE DATABASE antx;

USE antx;

CREATE TABLE IF NOT EXISTS account
(
    uid      INT  NOT NULL
        PRIMARY KEY,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    email    TEXT NOT NULL,
    phone    TEXT NULL
);

create table if not exists api
(
    id            int auto_increment
        primary key,
    name          text             null,
    address       text             null,
    request_times bigint default 0 null,
    introduction  text             null
);


CREATE TABLE IF NOT EXISTS info
(
    uid            INT  NOT NULL,
    ranking        INT auto_increment
        PRIMARY KEY,
    username       TEXT NOT NULL,
    avatar         TEXT NULL,
    signature      TEXT NULL,
    nickname       TEXT NULL,
    gender         TEXT NULL,
    birthday       date NULL,
    job            TEXT NULL,
    primary_school TEXT NULL,
    middle_school  TEXT NULL,
    university     TEXT NULL,
    company        TEXT NULL,
    location       TEXT NULL,
    hometown       TEXT NULL,
    email          TEXT NULL
);

CREATE TABLE IF NOT EXISTS link
(
    id              INT auto_increment
        PRIMARY KEY,
    short_link      TEXT       NOT NULL,
    long_link       TEXT       NOT NULL,
    status          TINYINT(1) NOT NULL,
    generation_time DATETIME   NOT NULL,
    expiration_time DATETIME   NOT NULL,
    request_times   INT        NOT NULL
);


CREATE TABLE IF NOT EXISTS mail
(
    id       INT auto_increment
        PRIMARY KEY,
    email    TEXT    NOT NULL,
    password TEXT    NOT NULL,
    server   TEXT    NOT NULL,
    port     INT     NULL,
    status   TINYINT NOT NULL
);

CREATE TABLE IF NOT EXISTS privacy
(
    uid            INT                  NOT NULL
        PRIMARY KEY,
    username       TEXT                 NOT NULL,
    gender         TINYINT(1) DEFAULT 1 NULL,
    birthday       TINYINT(1) DEFAULT 1 NULL,
    job            TINYINT(1) DEFAULT 1 NULL,
    primary_school TINYINT(1) DEFAULT 1 NULL,
    middle_school  TINYINT(1) DEFAULT 1 NULL,
    university     TINYINT(1) DEFAULT 1 NULL,
    company        TINYINT(1) DEFAULT 1 NULL,
    location       TINYINT(1) DEFAULT 1 NULL,
    hometown       TINYINT(1) DEFAULT 1 NULL,
    email          TINYINT(1) DEFAULT 1 NULL,
    phone          TINYINT(1) DEFAULT 1 NULL
);

CREATE TABLE IF NOT EXISTS status
(
    uid      INT                  NOT NULL
        PRIMARY KEY,
    username TEXT                 NOT NULL,
    status   TINYINT(1) DEFAULT 1 NULL,
    reason   TEXT                 NULL,
    ip_1     TEXT                 NULL,
    ip_2     TEXT                 NULL,
    ip_3     TEXT                 NULL,
    ip_4     TEXT                 NULL,
    ip_5     TEXT                 NULL,
    ip_6     TEXT                 NULL,
    ip_7     TEXT                 NULL,
    ip_8     TEXT                 NULL,
    ip_9     TEXT                 NULL,
    ip_10    TEXT                 NULL
);


INSERT INTO antx.account (uid, username, password, email, phone)
VALUES (10000, 'zhong',
        '30b056a1f3e831ff3824a199bfa20d7ce2bb64f4ee2ce424f4e85bea1f069653489eeac668dd9867e71094e84af6f55c9ac5f6c885f2d8958cd2d83718564172',
        'zhong@antx.cc', '18779737112');
INSERT INTO antx.info (uid, ranking, username, avatar, signature, nickname, gender, birthday, job, primary_school,
                       middle_school, university, company, location, hometown, email)
VALUES (10000, 1, 'zhong', '1', 'Without personality, no signature', 'Adisaint', 'M', '2009-09-01', 'Develop',
        'Quannan County Second Primary School', 'Quannan County Second Middle School', 'null', 'antx', 'china', 'china',
        'zhong@antx.cc');
INSERT INTO antx.privacy (uid, username, gender, birthday, job, primary_school, middle_school, university, company,
                          location, hometown, email, phone)
VALUES (10000, 'zhong', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
INSERT INTO antx.status (uid, username, status, reason, ip_1, ip_2, ip_3, ip_4, ip_5, ip_6, ip_7, ip_8, ip_9, ip_10)
VALUES (10000, 'zhong', 1, null, null, null, null, null, null, null, null, null, null, null);

INSERT INTO antx.mail (id, email, password, server, port, status)
VALUES (1, 'passport@antx.cc', 'zhong0911AntxMail', 'smtp.antx.cc', 25, 1);
INSERT INTO antx.mail (id, email, password, server, port, status)
VALUES (2, 'adisaint@163.com', 'ZETWBSBHCWTOHBMC', 'smtp.163.com', 465, 1);
INSERT INTO antx.mail (id, email, password, server, port, status)
VALUES (3, 'adisaint@qq.com', 'nwfheoidgkihbhjf', 'smtp.qq.com', 465, 1);
INSERT INTO antx.mail (id, email, password, server, port, status)
VALUES (4, 'antxcc@163.com', 'XRBYGAZWRCMCAOMQ', 'smtp.163.com', 465, 1);

INSERT INTO antx.link (id, short_link, long_link, status, generation_time, expiration_time, request_times)
VALUES (1, '0', '0', 0, '2023-05-27 14:23:50', '2023-05-27 14:22:34', 0);
INSERT INTO antx.link (id, short_link, long_link, status, generation_time, expiration_time, request_times)
VALUES (2, 'www', 'https://www.antx.cc/', 1, '2023-04-29 15:43:01', '2099-12-31 23:59:59', 0);
INSERT INTO antx.link (id, short_link, long_link, status, generation_time, expiration_time, request_times)
VALUES (3, 'cdn', 'https://cdn.antx.cc/', 1, '2023-04-29 15:43:37', '2099-12-31 23:59:59', 0);
INSERT INTO antx.link (id, short_link, long_link, status, generation_time, expiration_time, request_times)
VALUES (4, 'api', 'https://api.antx.cc/', 1, '2023-04-29 15:43:51', '2099-12-31 23:59:59', 0);
INSERT INTO antx.link (id, short_link, long_link, status, generation_time, expiration_time, request_times)
VALUES (5, 'login', 'https://passport.antx.cc/login/', 1, '2023-04-29 15:44:25', '2099-12-31 23:59:59', 0);
INSERT INTO antx.link (id, short_link, long_link, status, generation_time, expiration_time, request_times)
VALUES (6, 'register', 'https://passport.antx.cc/register/', 1, '2023-04-29 15:44:55', '2099-12-31 23:59:59',
        0);

INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (1, '0', '0', 4, '0');
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (2, 'myaddr', 'myaddr', 2, 'myaddr');
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (3, 'qrcode', 'qrcode', 2, 'qrcode');
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (4, 'qqcard', 'qqcard', 0, 'qqcard');
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (5, 'phone', 'phone', 0, 'phone');
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (6, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (7, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (8, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (9, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (10, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (11, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (12, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (13, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (14, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (15, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (16, null, null, 0, null);
INSERT INTO antx.api (id, name, address, request_times, introduction) VALUES (17, null, null, 0, null);



# CREATE USER 'root'@'%' IDENTIFIED BY 'zhong0911MySQL';
# GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';