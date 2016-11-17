CREATE TABLE IF NOT EXISTS `#__magicslideshow_config` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `profile` VARCHAR(128) NOT NULL DEFAULT '',
    `name` VARCHAR(64) NOT NULL DEFAULT '',
    `value` TEXT,
    `default` TEXT,
    `disabled` CHAR(1) DEFAULT '0',
    PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__magicslideshow_images` (
    `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(256) NOT NULL,
    `title` VARCHAR(256) DEFAULT '',
    `description` TEXT,
    `link` VARCHAR(256) DEFAULT '',
    `order` INTEGER UNSIGNED DEFAULT 0,
    `exclude` CHAR(1) DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
