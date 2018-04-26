use libadmin;

DROP TABLE IF EXISTS `kontakt`;
CREATE TABLE `kontakt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `vorname` varchar(100) DEFAULT NULL,
  /* anrede: [frau|herr] */
  `anrede` tinyint(1) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `adresse`;
CREATE TABLE `adresse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_organisation_rechnung` varchar(200) DEFAULT NULL,
  `strasse` varchar(150) DEFAULT NULL,
  `nummer` varchar(20) DEFAULT NULL,
  `zusatz` text DEFAULT NULL,
  `plz` mediumint(9) DEFAULT NULL,
  `ort` varchar(150) DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  `canton` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `kostenbeitrag`;
CREATE TABLE `kostenbeitrag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `2018` DECIMAL(7,2)  DEFAULT NULL,
  `2019` DECIMAL(7,2)  DEFAULT NULL,
  `2020` DECIMAL(7,2)  DEFAULT NULL,
  `2021` DECIMAL(7,2)  DEFAULT NULL,
  `2022` DECIMAL(7,2)  DEFAULT NULL,
  `2023` DECIMAL(7,2)  DEFAULT NULL,
  `2024` DECIMAL(7,2)  DEFAULT NULL,
  `2025` DECIMAL(7,2)  DEFAULT NULL,
  `2026` DECIMAL(7,2)  DEFAULT NULL,
  `2027` DECIMAL(7,2)  DEFAULT NULL,
  `2028` DECIMAL(7,2)  DEFAULT NULL,
  `2029` DECIMAL(7,2)  DEFAULT NULL,
  `2030` DECIMAL(7,2)  DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



ALTER TABLE institution
  ADD COLUMN `id_kontakt` int(11),
/*korrespondenzsprache: [deutsch|französisch|italienisch] */
  ADD COLUMN `korrespondezsprache` varchar(10)  DEFAULT NULL,
  ADD COLUMN `adresszusatz` text   DEFAULT NULL AFTER address,
  ADD COLUMN `twitter` varchar(30)  DEFAULT NULL AFTER facebook,
  ADD COLUMN `url_web_de` varchar(255)  DEFAULT NULL AFTER url_en,
  ADD COLUMN `url_web_fr` varchar(255)  DEFAULT NULL AFTER url_en,
  ADD COLUMN `url_web_it` varchar(255)  DEFAULT NULL AFTER url_en,
  ADD COLUMN `url_web_en` varchar(255)  DEFAULT NULL AFTER url_en,
  ADD COLUMN `notes_public` text  DEFAULT NULL AFTER notes,
  ADD COLUMN `bfscode` varchar(50)  DEFAULT NULL,
  ADD COLUMN `worldcat_ja_nein` tinyint(1)  DEFAULT NULL,
  ADD COLUMN `worldcat_symbol` varchar(30)  DEFAULT NULL,
  ADD COLUMN `cbslibrarycode` varchar(50)  DEFAULT NULL,
/* Verrechnungsbeitrag: über Institution, über Anzahl Aufnahmen, über BFS Zahlen,
  über recherchierte BFS Zahlen, keine Verrechnung */
  ADD COLUMN `verrechnungbeitrag` varchar(50)  DEFAULT NULL,
/* zusage_beitrag: [ja|nein] */
  ADD COLUMN `zusage_beitrag` TINYINT(1)  DEFAULT NULL,
  ADD COLUMN `id_kostenbeitrag` int(11)  DEFAULT NULL,
  ADD COLUMN `bemerkung_kostenbeitrag` text  DEFAULT NULL,
  ADD COLUMN `kostenbeitrag_basiert_auf` varchar(50) DEFAULT NULL;
/* adresse_rechnung_gleich_post [ja|nein] */
  ADD COLUMN `adresse_rechnung_gleich_post` TINYINT(1)  DEFAULT NULL,
  ADD COLUMN `id_rechnungsadresse` int(11)  DEFAULT NULL,
  ADD COLUMN `id_kontakt_rechnung` int(11)  DEFAULT NULL,
/* mwst: [ja|nein] */
  ADD COLUMN `mwst` TINYINT(1)  DEFAULT NULL,
/*grund_mwst_frei: [bfk|gemeinwesen] */
  ADD COLUMN `grund_mwst_frei` VARCHAR(20)  DEFAULT NULL,
/* e_rechnung: [ja|nein] */
  ADD COLUMN `e_rechnung` TINYINT(1)  DEFAULT NULL,
  ADD COLUMN `bemerkung_rechnung` text  DEFAULT NULL,

  ADD CONSTRAINT `fk_kontakt` FOREIGN KEY (`id_kontakt`) REFERENCES `kontakt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_kostenbeitrag` FOREIGN KEY (`id_kostenbeitrag`) REFERENCES `kostenbeitrag` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rechnungsadresse` FOREIGN KEY (`id_rechnungsadresse`) REFERENCES `adresse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_rechnung_kontakt` FOREIGN KEY (`id_kontakt_rechnung`) REFERENCES `kontakt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;


DROP TABLE IF EXISTS `admininstitution`;
CREATE TABLE `admininstitution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200)  DEFAULT NULL,
  `id_adresse` INT(11)  DEFAULT NULL,
  /* mail: entspricht institutioneller email adresse */
  `mail` INT(11)  DEFAULT NULL,
  `id_kontakt` INT(11)  DEFAULT NULL,
  /*korrespondenzsprache: [deutsch|französisch|italienisch] */
  `korrespondezsprache` varchar(10)  DEFAULT NULL,
  `bfscode` varchar(50)  DEFAULT NULL,
  `ipadresse` VARCHAR(30) DEFAULT NULL,
  /* zusage_beitrag: [ja|nein] */
  `zusage_beitrag` TINYINT(1)  DEFAULT NULL,
  `id_kostenbeitrag` int(11)  DEFAULT NULL,
  `bemerkung_kostenbeitrag` text  DEFAULT NULL,
  `kostenbeitrag_basiert_auf` varchar(50) DEFAULT NULL,
  `adresse_rechnung_gleich_post` TINYINT(1)  DEFAULT NULL,
  `id_rechnungsadresse` int(11)  DEFAULT NULL,
  `id_kontakt_rechnung` int(11)  DEFAULT NULL,
  /* mwst: [ja|nein] */
  `mwst` TINYINT(1)  DEFAULT NULL,
  /*grund_mwst_frei: [bfk|gemeinwesen] */
  `grund_mwst_frei` VARCHAR(20)  DEFAULT NULL,
  /* e_rechnung: [ja|nein] */
  `e_rechnung` TINYINT(1)  DEFAULT NULL,
  `bemerkung_rechnung` text  DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_kontakt` (`id_kontakt`),
  KEY `fk_adresse` (`id_adresse`),
  KEY `fk_adresse_rechnung` (`id_rechnungsadresse`),
  KEY `fk_kontakt_rechnung` (`id_kontakt_rechnung`),


  CONSTRAINT `fk_kontakt1` FOREIGN KEY (`id_kontakt`) REFERENCES `kontakt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_adresse1` FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_adresse_rechnung1` FOREIGN KEY (`id_rechnungsadresse`) REFERENCES `adresse` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_kontakt_rechnung1` FOREIGN KEY (`id_kontakt_rechnung`) REFERENCES `kontakt` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION

) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `mn_institution_admininstitution`;
CREATE TABLE `mn_institution_admininstitution` (
  `id_institution` int(11) NOT NULL,
  `id_admininstitution` int(11) NOT NULL,
  /*relation_type: Diskussion

    ich gehe im Moment davon aus, dass das nicht möglich ist. Eine Institution kann somit nur einer Admininstitution zugeordnet sein.
    auf relationaler Datenbankebene modelliere ich deshalb eine 1:n und keine n:m Beziehung
    @Silvia: ok?
    wir nehmen n:m - es ist möglich, dass die Entity admininstitution in Zukunft nicht nur für Zecke fundrising verwendet wird, sondern dass z.B. Aspekte der jetzigen Entity group Teil von admininstitution werden können. Für diese Zwecke werde ich in der n-m Beziehung noch einw erweiterndes Attribut aufnehmen [is_funding | is_other | is_???] default jetzt: is_funding
    [is_funding | is_other]
   */
  `relation_type` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`id_institution`,`id_admininstitution`),
  KEY `instititution` (`id_institution`),
  KEY `admininstitution` (`id_admininstitution`),
  CONSTRAINT `mn_inst_admin_institution1` FOREIGN KEY (`id_institution`) REFERENCES `institution` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `mn_inst_admin_admininstitution1` FOREIGN KEY (`id_admininstitution`) REFERENCES `admininstitution` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


