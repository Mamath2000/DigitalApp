ALTER TABLE `user`
CHANGE COLUMN `id` `id` BIGINT(20) NOT NULL ,
CHANGE COLUMN `idProfileType` `idUserProfile` BIGINT(20) NOT NULL COMMENT '1=Associé,2=invité,9=Admin' ,
CHANGE COLUMN `idStatusType` `idUserStatus` BIGINT(20) NOT NULL COMMENT '1=actif,2=inactif,3=register,9=archive' ,
CHANGE COLUMN `created` `creationDateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
CHANGE COLUMN `createdBy` `createdBy` BIGINT(20) NULL DEFAULT NULL ,
CHANGE COLUMN `modified` `modificationDateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
CHANGE COLUMN `modifiedBy` `modifiedBy` BIGINT(20) NULL DEFAULT NULL ;


ALTER TABLE `associate`
CHANGE COLUMN `id` `id` BIGINT(20) NOT NULL ,
CHANGE COLUMN `lastname` `name` VARCHAR(32) NOT NULL ,
CHANGE COLUMN `idUser` `idUser` BIGINT(20) NULL DEFAULT NULL ,
CHANGE COLUMN `idStatusType` `idAssociateStatus` BIGINT(20) NOT NULL COMMENT '1=actif/employee,2=inactif,9=archive' ,
CHANGE COLUMN `created` `creationDateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
CHANGE COLUMN `createdBy` `createdBy` BIGINT(20) NULL DEFAULT NULL ,
CHANGE COLUMN `modified` `modificationDateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
CHANGE COLUMN `modifiedBy` `modifiedBy` BIGINT(20) NULL DEFAULT NULL ;


DELETE FROM `type` WHERE (`id` = '4');
DELETE FROM `type` WHERE (`id` = '7');


ALTER TABLE `type`
DROP COLUMN `refId`,
CHANGE COLUMN `id` `id` BIGINT(20) UNSIGNED NOT NULL ;


ALTER TABLE `grpdefinition`
CHANGE COLUMN `label` `name` VARCHAR(100) NOT NULL ,
CHANGE COLUMN `order` `sortOrder` INT(3) NOT NULL , RENAME TO  `groupdef` ;


ALTER TABLE `linedefinition`
CHANGE COLUMN `idGrpDefinition` `idGroupDef` BIGINT(20) NOT NULL ,
CHANGE COLUMN `label` `name` VARCHAR(100) NOT NULL ,
CHANGE COLUMN `order` `sortOrder` INT(3) NOT NULL , RENAME TO  `linedef` ;


ALTER TABLE `element`
DROP COLUMN `calculationRule`,
DROP COLUMN `isCalculated`,
CHANGE COLUMN `idGrpCodeDefinition` `idGroupDef` BIGINT(20) NOT NULL ,
CHANGE COLUMN `idLineCodeDefinition` `idLineDef` BIGINT(20) NOT NULL ,
CHANGE COLUMN `dateValue` `dateValueDate` DATE NOT NULL ,
CHANGE COLUMN `dateReal` `dateRealDate` DATE NOT NULL ;


ALTER TABLE `element`
ADD COLUMN `idHidden` TINYINT NULL DEFAULT 0 AFTER `modifiedBy`;

INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,1,3,'Import','2019-03-01','2019-03-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,1,16.5,'Import','2019-04-01','2019-04-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,1,16,'Import','2019-05-01','2019-05-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,1,17,'Import','2019-06-01','2019-06-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,2,735,'Import','2019-03-01','2019-03-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,2,735,'Import','2019-04-01','2019-04-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,2,735,'Import','2019-05-01','2019-05-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,2,735,'Import','2019-06-01','2019-06-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,3,2205,'Import','2019-03-01','2019-03-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,3,12127.5,'Import','2019-04-01','2019-04-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,3,11760,'Import','2019-05-01','2019-05-01');
INSERT INTO `element` (`idAssociate`, `idGroupDef`,`idLineDef`,`value`,`Source`,`dateValueDate`,`dateRealDate`) VALUES (19, 1,3,12495,'Import','2019-06-01','2019-06-01');

ALTER TABLE `user` CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user` CHANGE `modificationDateTime` `lastUpdateDateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `user` CHANGE `modifiedBy` `lastUpdateBy` BIGINT(20) NULL DEFAULT NULL;

ALTER TABLE `associate` CHANGE `modificationDateTime` `lastUpdateDateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `associate` CHANGE `modifiedBy` `lastUpdateBy` BIGINT(20) NULL DEFAULT NULL;

ALTER TABLE `element` CHANGE `modificationDateTime` `lastUpdateDateTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `element` CHANGE `modifiedBy` `lastUpdateBy` BIGINT(20) NULL DEFAULT NULL;



CREATE TABLE `history` (
  `id` BIGINT(20) unsigned NOT NULL AUTO_INCREMENT,
  `refType` varchar(100) NOT NULL,
  `refId` BIGINT(20) unsigned NOT NULL,
  `operation` varchar(10) DEFAULT NULL,
  `colName` varchar(200) DEFAULT NULL,
  `oldValue` mediumtext,
  `newValue` mediumtext,
  `operationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idUser` BIGINT(20) unsigned DEFAULT NULL,
  `isWorkHistory` int(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `historyUser` (`idUser`),
  KEY `historyRef` (`refType`,`refId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `element` 
CHANGE COLUMN `idGroupDef` `idGroupDef` BIGINT(20) NULL ;



ALTER TABLE `linedef` 
ADD COLUMN `type` VARCHAR(10) NOT NULL AFTER `idGroupDef`;


INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (12,3,'SAL_CHARGE','MENS','salaire chargé',300, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (13,3,'SAL_NET','MENS','salaire net',310, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (14,3,'SAL_IMP','MENS','salaire net Imposable',320, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (15,3,'SAL_VERS','MENS','versement mensuel (hors IR)',330, 0,'', 1, 0);

INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (16,4,'CT_GLOBAL','MENS','Cout global (Salaire + Frais)',400, 1,'SAL_CHARGE + REMB', 1, 1);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (17,4,'TOTAL','MENS','Total Intermédiaire',410, 1,'', 1, 1);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (18,4,'TOTAL_CUM','MENS','Total Inter. Cumulé',420, 1,'fx', 1, 1);


INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (19,5,'NB_SAL','MENS','Nb Salarié',500, 1,'fx_NB_SAL', 0, 1);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (20,5,'F_PAIE','ANN','Fiches paies',510, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (21,5,'F_COMPTA','ANN','Compta',520, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (22,5,'TAXE_CFE','ANN','Taxe CFE ',530, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (23,5,'TAXE_CVAE','ANN','Taxe CVAE',540, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (24,5,'TAXE_MED','ANN','Taxe Medecine T.',550, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (25,5,'RCP','ANN','RCP',560, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (26,5,'VOIT_F','ANN','Taxes Voiture f. (TVS+IS)',570, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (27,5,'CH_INTER','ANN','Charges S. Interessemt',580, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (28,5,'CH_ABON','ANN','Charges S. Abondmnt',590, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (29,5,'PROV_INTERCO','ANN','Prov. Interco',600, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (30,5,'RETO_CLIENT','ANN','Rétrocommission client',610, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (31,5,'COMMISSION','ANN','Commission',620, 0,'', 1, 0);
INSERT INTO `linedef` (`id`,`idGroupDef`,`code`,`type`,`name`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`) VALUES (32,5,'DOM_CCIALE','ANN','Domiciliation cciale',630, 0,'', 1, 0);




UPDATE `linedef` SET `type` = 'MENS' WHERE (`id` = '11');
UPDATE `linedef` SET `type` = 'MENS' WHERE (`id` = '10');
UPDATE `linedef` SET `type` = 'MENS' WHERE (`id` = '9');
UPDATE `linedef` SET `type` = 'MENS' WHERE (`id` = '8');
UPDATE `linedef` SET `type` = 'MENS' WHERE (`id` = '7');
UPDATE `linedef` SET `type` = 'MENS' WHERE (`id` = '6');
DELETE FROM `linedef` WHERE (`id` = '5');
UPDATE `linedef` SET `type` = 'MENS' WHERE (`id` = '4');
UPDATE `linedef` SET `type` = 'MENS' WHERE (`id` = '3');
UPDATE `linedef` SET `type` = 'MENS' WHERE (`id` = '2');
UPDATE `linedef` SET `type` = 'MENS' WHERE (`id` = '1');


UPDATE `linedef` SET `calculationRule` = '[FAC]/[WORK]' WHERE (`id` = '2');
UPDATE `linedef` SET `calculationRule` = '([Tx/km])*([TRJ])*([KM/T])' WHERE (`id` = '9');
UPDATE `linedef` SET `calculationRule` = '[SAL_CHARGE] + [REMB]' WHERE (`id` = '16');

UPDATE `linedef` SET `isCalculate` = '1', `calculationRule` = '[WORK]' WHERE (`id` = '6');
UPDATE `linedef` SET `isReadonly` = '1' WHERE (`id` = '2');

ALTER TABLE `element` 
ADD COLUMN `refLabel` VARCHAR(45) NOT NULL AFTER `idAssociate`;

UPDATE `element` SET `refLabel` = DATE_FORMAT(dateValueDate, '%Y%m');

UPDATE `linedef` SET `hasAutoSum` = '0' WHERE (`id` = '2');

UPDATE `linedef` SET `code` = 'TxKm' WHERE (`id` = '8');
UPDATE `linedef` SET `code` = 'KmT' WHERE (`id` = '7');
UPDATE `linedef` SET `calculationRule` = '([TxKm])*([TRJ])*([KmT])' WHERE (`id` = '9');

UPDATE `linedef` SET `type` = 'ANNR' WHERE (`id` = '32');

INSERT INTO `groupdef` (`id`, `code`, `name`, `description`, `sortOrder`) VALUES ('4', 'TOTAL', 'Total Inter', 'Sommes et totaux', '40');

INSERT INTO `groupdef` (`id`, `code`, `name`, `description`, `sortOrder`) VALUES (5, 'ETS', 'Frais D.A.', 'Frais et Taxes de entreprise', '50');
INSERT INTO `groupdef` (`id`, `code`, `name`, `description`, `sortOrder`) VALUES (6, 'OPT', 'Options Associé', 'Options pour les associés', '60');

INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','FRAIS_ASTR','Frais / astreintes','',700,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','PREV_FRAIS','Prévisionnel Frais','',710,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','REPAS_CLIENT','Repas clients - Materiels','',720,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','PERCO','PERCO','',730,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','PARTICIPATION','Participation','',740,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','INTERESSMT','Interessmt (brut)','',750,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','ABONDMT','Abondement','',760,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','TR','Tickets Resto','',770,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','CESU','CESU','',780,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','PROV_CONGES','Prov. Congés','',790,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','PRIME_FORM','Prime / Formation','',800,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','PRIME_AUTRE','Prime Macron','',810,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','FRAIS_TEL','Frais telephone','',820,0,'',1,0,0);
INSERT INTO `linedef` (`idGroupDef`,`type`,`code`,`name`,`description`,`sortOrder`,`isCalculate`,`calculationRule`,`hasAutoSum`,`isReadonly`, `isHidden`) VALUES (6,'ANNR','AVANT_VOIT','Avantage Voiture f.','',830,0,'',1,0,0);


ALTER TABLE `associate` 
ADD COLUMN `startDate` DATE NULL AFTER `idAssociateStatus`,
ADD COLUMN `endDate` DATE NOT NULL DEFAULT '2099-12-31' AFTER `startDate`;


UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '32');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '33');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '34');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '35');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '36');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '38');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '37');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '39');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '41');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '40');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '42');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '43');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '44');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '45');
UPDATE `api`.`linedef` SET `isCalculate` = '1', `calculationRule` = 'ANNR' WHERE (`id` = '46');


ALTER TABLE `api`.`element` 
ADD COLUMN `year` INT(4) NOT NULL AFTER `refLabel`;


UPDATE `element`  SET `year` =2018;

UPDATE `api`.`type` SET `name` = 'Enregistré' WHERE (`id` = '3');




ALTER TABLE `api`.`linedef` 
ADD UNIQUE INDEX `code_UNIQUE` (`code` ASC) VISIBLE;
;


ALTER TABLE `api`.`linedef` 
ADD COLUMN `canNull` TINYINT(4) NOT NULL DEFAULT 0 AFTER `validityEndDate`;

UPDATE `api`.`linedef` SET `canNull` = '1' WHERE (`id` = '1');
UPDATE `api`.`linedef` SET `canNull` = '1' WHERE (`id` = '2');
UPDATE `api`.`linedef` SET `canNull` = '1' WHERE (`id` = '3');
UPDATE `api`.`linedef` SET `canNull` = '1' WHERE (`id` = '5');

CREATE TABLE IF NOT EXISTS `report` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `reportsitem` (
  `idReport` BIGINT(20) NOT NULL,
  `idLineDef` BIGINT(20) NOT NULL,
  `sortOrder` int(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`idReport`, `idLineDef`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

ALTER TABLE `api`.`reportsitem` 
ADD COLUMN `id` BIGINT(20) NOT NULL AUTO_INCREMENT FIRST,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`),
ADD UNIQUE INDEX `index_Sec` (`idReport` ASC, `idLineDef` ASC) VISIBLE;
ALTER TABLE `api`.`reportsitem` ALTER INDEX `lindef_FK_idx` INVISIBLE;




ALTER TABLE `api`.`reportsitem` 
ADD INDEX `lindef_FK_idx` (`idLineDef` ASC) VISIBLE;
;
ALTER TABLE `api`.`reportsitem` 
ADD CONSTRAINT `report_FK`
  FOREIGN KEY (`idReport`)
  REFERENCES `api`.`report` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION,
ADD CONSTRAINT `lindef_FK`
  FOREIGN KEY (`idLineDef`)
  REFERENCES `api`.`linedef` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;


ALTER TABLE `api`.`reportsitem` 
DROP FOREIGN KEY `report_FK`;
ALTER TABLE `api`.`reportsitem` 
ADD CONSTRAINT `report_FK`
  FOREIGN KEY (`idReport`)
  REFERENCES `api`.`report` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

INSERT INTO `api`.`report` (`id`, `name`) VALUES ('1', 'Paye');
INSERT INTO `api`.`report` (`id`, `name`) VALUES ('2', 'Comptable');



ALTER TABLE `api`.`associate` 
CHANGE COLUMN `idUser` `idUsers` BIGINT(20) NULL DEFAULT NULL , RENAME TO  `api`.`associates` ;


ALTER TABLE `api`.`element` 
CHANGE COLUMN `idAssociate` `idAssociates` BIGINT(20) NOT NULL ;


ALTER TABLE `api`.`history` 
CHANGE COLUMN `idUser` `idUsers` BIGINT(20) UNSIGNED NULL DEFAULT NULL ;

ALTER TABLE `api`.`report` 
RENAME TO  `api`.`reports` ;

ALTER TABLE `api`.`reportsitem` 
DROP FOREIGN KEY `report_FK`;
ALTER TABLE `api`.`reportsitem` 
CHANGE COLUMN `idReport` `idReports` BIGINT(20) NOT NULL ;
ALTER TABLE `api`.`reportsitem` 
ADD CONSTRAINT `report_FK`
  FOREIGN KEY (`idReports`)
  REFERENCES `api`.`reports` (`id`)
  ON DELETE CASCADE;


ALTER TABLE `api`.`user` 
RENAME TO  `api`.`users` ;



ALTER TABLE `api`.`linedef` 
RENAME TO  `api`.`linesdef` ;

ALTER TABLE `api`.`element` 
CHANGE COLUMN `idLineDef` `idLinesDef` BIGINT(20) NOT NULL , RENAME TO  `api`.`cells` ;


ALTER TABLE `api`.`reportsitem` 
DROP FOREIGN KEY `report_FK`;
ALTER TABLE `api`.`reportsitem` 
ADD INDEX `FK1_idx` (`idReports` ASC) VISIBLE,
DROP INDEX `index_Sec` ;
;
ALTER TABLE `api`.`reportsitem` 
ADD CONSTRAINT `FK1`
  FOREIGN KEY (`idReports`)
  REFERENCES `api`.`reports` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
ADD CONSTRAINT `FK2`
  FOREIGN KEY (`idLinesDef`)
  REFERENCES `api`.`linesdef` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


ALTER TABLE `api`.`linesdef` 
ADD COLUMN `canNull` TINYINT(4) NOT NULL DEFAULT 0 AFTER `isHidden`;

ALTER TABLE `api`.`cells` 
CHANGE COLUMN `idHidden` `isHidden` TINYINT(4) NULL DEFAULT '0' ;
