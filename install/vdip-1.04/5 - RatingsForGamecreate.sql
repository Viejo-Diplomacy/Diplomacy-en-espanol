ALTER TABLE `wD_Games` ADD `minRating` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Games` ADD `minPhases` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Games` ADD `maxLeft` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '99';
ALTER TABLE `wD_Backup_Games` ADD `minRating` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games` ADD `minPhases` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games` ADD `maxLeft` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '99';
