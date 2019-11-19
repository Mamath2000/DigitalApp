
CREATE TABLE `user` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `email` varchar(64),
  `password` varchar(512) NOT NULL,
  `profile` BIGINT NOT NULL COMMENT '1=Associé,2=invité,9=Admin',
  `status` BIGINT NOT NULL COMMENT '1=actif,2=inactif,3=register,9=archive',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` BIGINT,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` BIGINT,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Users of application';


INSERT INTO `user` (`id`, `name`, `email`, `password`, `profile`, `status`, `createdBy`, `modifiedBy`)
       VALUES (1, 'admin', 'admin', '$2y$10$WKvBgTudIHFqCc6D2Zhe8evCVDWWs8b3EJky4JI1jPT4v5M4j74U.', '9', '1', 1, 1);

INSERT INTO `user` ( `name`, `email`, `password`, `profile`, `status`, `createdBy`, `modifiedBy`)
      VALUES ('Mathieu', 'mathieu.beau@gmail.com', '$2y$10$WKvBgTudIHFqCc6D2Zhe8evCVDWWs8b3EJky4JI1jPT4v5M4j74U.', '9', '1', 1, 1);

      CREATE TABLE `associate` (
               `id` BIGINT NOT NULL AUTO_INCREMENT,
               `firstname` varchar(32) NOT NULL,
               `lastname` varchar(32) NOT NULL,
               `trig` varchar(4) NOT NULL,
               `idUser` varchar(32) NULL,
               `email` varchar(64),
               `contactNumber` varchar(64),
               `address` text,
               `idStatusType` BIGINT NOT NULL COMMENT '1=actif/employee,2=inactif,9=archive',
               `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
               `createdBy` BIGINT,
               `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
               `modifiedBy` BIGINT,
               PRIMARY KEY (id)
             ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Accosiates of Digital Associate';

      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Anne','Delort','ADE',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Anthony','Michaud','AMI',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Alexandre','Oblet','AOB',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Aicha','Revel','ARE',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Constance','Bronchain','CBR',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Céline','Broussan','CBN',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Céline','César','CCE',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Christophe','Marraillac','CMA',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Clémentine','Martin','CMN',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Claire','Savariaud','CSA',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Dorian','Dezanneau','DDE',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Damien','Maurel','DMA',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('David','Vellar','DVE',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Eric','Pochon','EPO',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Gaël','Rouzic','GRO',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Germain','Zoltek','Gzo',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Isabelle','Orven','IOR',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Léa','Baillard','LBA',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Mathieu','Beau','MBE',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Myriam','Bonzon','MBO',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Myriam','Djemiai','MDJ',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Michaël','KARAMAN','MKA',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Maxence','Lothe','MLO',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Nathalie','Rialland','NRI',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Olivier','DATT','ODA',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Olivier','Dubois','ODU',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Pierrick','Merlet','PME',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Renan','Beaufrère','RBE',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Renaud','Gaudin','RGA',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Sébastien','Normand','SNO',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Samuel','Rambaud','SRA',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Thomas','Litrol','TLI',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Tanguy','RALLON','TRA',1,1,1);
      INSERT INTO `associate` (`firstname`,`lastname`,`trig`,`idStatusType`,`createdBy`,`modifiedBy`) VALUES ('Vincent','Dalgé','VDA',1,1,1);


CREATE TABLE `type` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `scope` varchar(100) NOT NULL,
  `refId` int(12) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `sortOrder` int(3) unsigned DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `typeScope` (`scope`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;


INSERT INTO `type` (`scope`, `refId`, `name`, `sortOrder`, `color`) VALUES ('UserStatus', '1', 'Actif', '10', 'bg-success');
INSERT INTO `type` (`scope`, `refId`, `name`, `sortOrder`, `color`) VALUES ('UserStatus', '2', 'Inactif', '20', 'bg-secondary');
INSERT INTO `type` (`scope`, `refId`, `name`, `sortOrder`, `color`) VALUES ('UserStatus', '3', 'Entregistré', '30', 'bg-warning');
INSERT INTO `type` (`scope`, `refId`, `name`, `sortOrder`) VALUES ('UserStatus', '9', 'Archivé', '90');
INSERT INTO `type` (`scope`, `refId`, `name`, `sortOrder`, `color`) VALUES ('AssociateStatus', '1', 'Actif/Employé', '10', 'bg-success');
INSERT INTO `type` (`scope`, `refId`, `name`, `sortOrder`, `color`) VALUES ('AssociateStatus', '2', 'Inactif', '20', 'bg-secondary');
INSERT INTO `type` (`scope`, `refId`, `name`, `sortOrder`) VALUES ('AssociateStatus', '9', 'Archivé', '90');
INSERT INTO `type` (`scope`, `refId`, `name`, `color`) VALUES ('UserProfile', '1', 'Associé', 'bg-light');
INSERT INTO `type` (`scope`, `refId`, `name`) VALUES ('UserProfile', '2', 'Invité');
INSERT INTO `type` (`scope`, `refId`, `name`, `color`) VALUES ('UserProfile', '9', 'Admin', 'bg-info');

CREATE TABLE IF NOT EXISTS `taux` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `year` int(4) NOT NULL,
  `name` varchar(256) NOT NULL,
  `code` varchar(20) NOT NULL,
  `value` DECIMAL(10,3) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` BIGINT,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` BIGINT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;







CREATE TABLE `codeType` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `description` varchar(4000),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Code used to regroup type account line with a sam calculation process';


CREATE TABLE `element` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `idAssociate` BIGINT(20) NOT NULL,
  `value` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `Source` VARCHAR(45) NULL,
  `dateValue` DATE NOT NULL,
  `dateReal` DATE NOT NULL,
  `isReadonly` TINYINT NOT NULL DEFAULT 0,
  `isCalculated` TINYINT NULL DEFAULT 0,
  `calculationRule` VARCHAR(45) NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdBy` BIGINT,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modifiedBy` BIGINT,
  PRIMARY KEY (`id`),
  INDEX `dateValIdx` (`dateValue` ASC) INVISIBLE,
  INDEX `dateRealIdx` (`dateReal` ASC) VISIBLE) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

  ALTER TABLE `api`.`element`
  CHANGE COLUMN `Source` `source` VARCHAR(45) NULL DEFAULT NULL ;


  ALTER TABLE `api`.`element`
  ADD COLUMN `idGrpCodeDefinition` BIGINT(20) NOT NULL AFTER `id`,
  ADD COLUMN `idLineCodeDefinition` BIGINT(20) NOT NULL AFTER `idGrpCodeDefinition`;

  ALTER TABLE `api`.`element`
  CHANGE COLUMN `created` `creationDateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  CHANGE COLUMN `modified` `modificationDateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ;



-- Exemple

CREATE TABLE `linedefinition` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `idGrpDefinition` BIGINT(20) NOT NULL,
  `code` VARCHAR(45) NOT NULL,
  `label` VARCHAR(100) NOT NULL,
  `description` VARCHAR(500) NULL,
  `order` BIGINT(20) NOT NULL,
  `isCalculate` TINYINT NULL DEFAULT 0,
  `calculationRule` VARCHAR(100) NULL,
  `hasAutoSum` TINYINT NOT NULL DEFAULT 1,
  `isReadonly` TINYINT NOT NULL DEFAULT 0,
  `isHidden` TINYINT NOT NULL DEFAULT 0,
  `validityEndDate` DATETIME NOT NULL DEFAULT '2099-12-31',
  PRIMARY KEY (`id`));

  ALTER TABLE `linedefinition`
  ADD UNIQUE INDEX `lineCode_UNIQUE` (`lineCode` ASC) VISIBLE;


  CREATE TABLE `grpdefinition` (
    `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(45) NOT NULL,
    `label` VARCHAR(100) NOT NULL,
    `description` VARCHAR(500) NULL,
    `order` BIGINT(20) NOT NULL,
    PRIMARY KEY (`id`));

    ALTER TABLE `grpdefinition`
    ADD UNIQUE INDEX `grpCode_UNIQUE` (`grpCode` ASC) VISIBLE;


    INSERT INTO `api`.`grpdefinition` (`code`, `label`, `description`, `order`) VALUES ('FACTU', 'Facturation', 'Elements relatifs à la facturation', '10');
    INSERT INTO `api`.`grpdefinition` (`code`, `label`, `description`, `order`) VALUES ('FRAIS', 'Frais', 'Frais de l\'associé. (IK, frais de repas, autres...)', '20');
    INSERT INTO `api`.`grpdefinition` (`code`, `label`, `description`, `order`) VALUES ('PAIE', 'Paie', 'Elements de la paie.', '30');



INSERT INTO `linedefinition` (`lineCode`, `libelle`, `description`, `order`, `isCalculate`, `idGrpDefinition`) VALUES ('WORK', 'Jours', 'Nombre de jours facturable', '100', '0', '1');
INSERT INTO `linedefinition` (`lineCode`, `libelle`, `description`, `order`, `isCalculate`, `calculationRule`, `idGrpDefinition`) VALUES ('TJ', 'TJM', 'Prix de vente. (calculé sur la base de la fac et des jours travaillés)', '140', '1', 'FAC/WORK', '1');
INSERT INTO `linedefinition` (`lineCode`, `libelle`, `description`, `order`, `isCalculate`, `idGrpDefinition`) VALUES ('FAC', 'Facturé', 'Montant facturé', '180', '0', '1');
INSERT INTO `linedefinition` (`idGrpDefinition`, `code`, `label`, `description`, `order`, `isCalculate`, `validityEndDate`, `hasAutoSum`, `isReadonly`, `isHidden`) VALUES ('1', 'INCOME', 'Encaiss. HT', 'Montant réellement encaissés.', '220', '0', '2099-12-31 00:00:00', '1', '1', '0');
INSERT INTO `linedefinition` (`idGrpDefinition`, `code`, `label`, `description`, `order`, `isCalculate`, `validityEndDate`, `hasAutoSum`, `isReadonly`, `isHidden`) VALUES ('1', 'CACHE', 'Test Caché', 'Ligne normalement cachée', '250', '0', '2099-12-31 00:00:00', '0', '1', '1');
INSERT INTO `linedefinition` (`idGrpDefinition`,`code`,`label`,`description`,`order`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (1,'TRJ', 'nbre trajets', "", 200,0,'',1,0);
INSERT INTO `linedefinition` (`idGrpDefinition`,`code`,`label`,`description`,`order`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (1,'KM/T', 'nbre km/trajets', "", 210,0,'',0,0);
INSERT INTO `linedefinition` (`idGrpDefinition`,`code`,`label`,`description`,`order`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (1,'Tx/km', 'Taux', "", 220,0,'',0,0);
INSERT INTO `linedefinition` (`idGrpDefinition`,`code`,`label`,`description`,`order`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (1,'IK', 'IK', "", 230,1,'(Tx/km)*(TRJ)*(KM/T)',1,1);
INSERT INTO `linedefinition` (`idGrpDefinition`,`code`,`label`,`description`,`order`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (1,'FRAIS', 'frais', "", 240,0,'',1,0);
INSERT INTO `linedefinition` (`idGrpDefinition`,`code`,`label`,`description`,`order`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (1,'REMB', 'frais versé', "", 250,0,'',1,1);


UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '1' WHERE (`id` = '1');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '1' WHERE (`id` = '2');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '1' WHERE (`id` = '3');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '1' WHERE (`id` = '4');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '1' WHERE (`id` = '5');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '1' WHERE (`id` = '6');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '2' WHERE (`id` = '7');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '2' WHERE (`id` = '8');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '2' WHERE (`id` = '9');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '2' WHERE (`id` = '10');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '3' WHERE (`id` = '11');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '3' WHERE (`id` = '12');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '3' WHERE (`id` = '13');
UPDATE `api`.`element` SET `idGrpCodeDefinition` = '1', `idLineCodeDefinition` = '3' WHERE (`id` = '14');
