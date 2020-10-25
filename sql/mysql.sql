CREATE TABLE bxbbs_message (
    id        INT(10)      NOT NULL AUTO_INCREMENT,
    serial    INT(10)      NOT NULL,
    bbs_id    TINYINT(3)   NOT NULL DEFAULT '1',
    uid       MEDIUMINT(5) NOT NULL DEFAULT '0',
    name      VARCHAR(64)           DEFAULT NULL,
    email     VARCHAR(64)           DEFAULT NULL,
    url       VARCHAR(64)           DEFAULT NULL,
    title     VARCHAR(64)           DEFAULT NULL,
    message   TEXT,
    passwd    VARCHAR(34)  NOT NULL DEFAULT '',
    inputdate INT(10)      NOT NULL DEFAULT '0',
    ip        VARCHAR(22)  NOT NULL DEFAULT '',
    PRIMARY KEY (id)
)
    ENGINE = ISAM;

CREATE TABLE bxbbs_bbs (
    bbs_id     TINYINT(3)  DEFAULT '1' NOT NULL AUTO_INCREMENT,
    serial     INT(10)                 NOT NULL,
    page_limit TINYINT(2)  DEFAULT '5' NOT NULL,
    title      VARCHAR(64) DEFAULT ''  NOT NULL,
    ex         TEXT        DEFAULT ''  NOT NULL,
    howto      TEXT        DEFAULT ''  NOT NULL,
    priority   TINYINT(2)  DEFAULT 0   NOT NULL,
    status     TINYINT(1)  DEFAULT 0   NOT NULL,
    PRIMARY KEY (bbs_id)
)
    ENGINE = ISAM;
