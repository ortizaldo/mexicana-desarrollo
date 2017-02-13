--
-- Table structure for table `user`
--
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `user`(
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastName` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastNameOp` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` VARCHAR(120) COLLATE utf8_unicode_ci NOT NULL,
  `birthdate` DATE DEFAULT NULL,
  `gender` VARCHAR(45) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` blob,
  `phoneNumber` VARCHAR(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activation_token` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` TINYINT DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `photoUrl` VARCHAR(350) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photoName` VARCHAR(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_nickname_unique` (`nickname`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

CREATE TABLE IF NOT EXISTS `rol`(
  id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  type VARCHAR(50) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_rol`(
  id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  idUser INT(10) UNSIGNED NOT NULL,
  idRol INT(10) UNSIGNED NOT NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX (idUser),
  INDEX (idRol),
  FOREIGN KEY (idUser) REFERENCES user(id),
  FOREIGN KEY (idRol) REFERENCES rol(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `country`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `state`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL,
  idCountry INT(10) UNSIGNED NOT NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX (idCountry),
  FOREIGN KEY (idCountry) REFERENCES country(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `country_state`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) DEFAULT NULL,
  idCountry INT(10) UNSIGNED NOT NULL,
  idState INT(10) UNSIGNED NOT NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX (idCountry),
  INDEX (idState),
  FOREIGN KEY (idCountry) REFERENCES country(id),
  FOREIGN KEY (idState) REFERENCES state(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `city`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) DEFAULT NULL,
  idState INT(10) UNSIGNED NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX(idState),
  FOREIGN KEY (idState) REFERENCES state(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `city_state`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  idCity INT(10) UNSIGNED NULL,
  idState INT(10) UNSIGNED NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX(idCity),
  INDEX(idState),
  FOREIGN KEY (idCity) REFERENCES city(id),
  FOREIGN KEY (idState) REFERENCES state(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `agency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `plazo` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `idUser` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX (`idUser`),
  FOREIGN KEY (`idUser`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `agency_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `idAgency` int(10) unsigned NOT NULL,
  `idProfile` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX (`idAgency`),
  INDEX (`idProfile`),
  FOREIGN KEY (`idAgency`) REFERENCES `agency` (`id`),
  FOREIGN KEY (`idProfile`) REFERENCES `profile` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idUser` int(10) unsigned NOT NULL,
  `idProfile` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active` TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX (`idUser`),
  INDEX (`idProfile`),
  FOREIGN KEY (`idUser`) REFERENCES `user` (`id`),
  FOREIGN KEY (`idProfile`) REFERENCES `profile` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `agency_employee`
--
CREATE TABLE IF NOT EXISTS `agency_employee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idAgency` int(10) unsigned NOT NULL,
  `idemployee` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX (`idAgency`),
  INDEX (`idemployee`),
  FOREIGN KEY (`idAgency`) REFERENCES `agency` (`id`),
  FOREIGN KEY (`idemployee`) REFERENCES `employee` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `taskList`
--
CREATE TABLE IF NOT EXISTS `taskList` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `idAgency` int(10) unsigned NOT NULL,
  `idUserCreator` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX (`idAgency`),
  INDEX (`idUserCreator`),
  FOREIGN KEY (`idAgency`) REFERENCES `agency` (`id`),
  FOREIGN KEY (`idUserCreator`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `folio` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `colonia` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `annotations` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zipCode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateVisit` DATE DEFAULT NULL,
  `clientName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idUserCreator` int(10) unsigned NOT NULL,
  `idUserAssigned` int(10) unsigned NOT NULL,
  `idCity` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX (`idUserCreator`),
  INDEX (`idCity`),
  FOREIGN KEY (`idUserCreator`) REFERENCES `user` (`id`),
  FOREIGN KEY (`idCity`) REFERENCES `city` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `task_status`
--

CREATE TABLE IF NOT EXISTS `task_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `task_AssignedStatus`
--

CREATE TABLE IF NOT EXISTS `task_AssignedStatus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idTask` int(10) unsigned NOT NULL,
  `idStatus` int(10) unsigned NOT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meeting` DATE DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX (`idTask`),
  INDEX (`idStatus`),
  FOREIGN KEY (`idTask`) REFERENCES `task` (`id`),
  FOREIGN KEY (`idStatus`) REFERENCES `task_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `taskList_task`
--

CREATE TABLE IF NOT EXISTS `taskList_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idTaskList` int(10) unsigned NOT NULL,
  `idTask` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX (`idTaskList`),
  INDEX (`idTask`),
  FOREIGN KEY (`idTaskList`) REFERENCES `taskList` (`id`),
  FOREIGN KEY (`idTask`) REFERENCES `task` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agency_task`
--

CREATE TABLE IF NOT EXISTS `agency_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `idAgencyCreator` int(10) unsigned NOT NULL,
  `idAssignedAgency` int(10) unsigned NOT NULL,
  `idTask` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX (`idAgencyCreator`),
  INDEX (`idAssignedAgency`),
  INDEX (`idTask`),
  FOREIGN KEY (`idAgencyCreator`) REFERENCES `agency` (`id`),
  FOREIGN KEY (`idAssignedAgency`) REFERENCES `agency` (`id`),
  FOREIGN KEY (`idTask`) REFERENCES `task` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `agency_task_UserAsigned`
--

CREATE TABLE IF NOT EXISTS `agency_task_CencistaAssigned` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idAgencyCreator` int(10) unsigned NOT NULL,
  `idTask` int(10) unsigned NOT NULL,
  `idEmployee` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX (`idAgencyCreator`),
  INDEX (`idTask`),
  INDEX (`idEmployee`),
  FOREIGN KEY (`idAgencyCreator`) REFERENCES `agency` (`id`),
  FOREIGN KEY (`idTask`) REFERENCES `task` (`id`),
  FOREIGN KEY (`idEmployee`) REFERENCES `employee` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `reportType`
--

CREATE TABLE IF NOT EXISTS `reportType` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportType`
--

LOCK TABLES `reportType` WRITE;
/*!40000 ALTER TABLE `reportType` DISABLE KEYS */;
/*!40000 ALTER TABLE `reportType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report`(
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `agreementNumber` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `clientName` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `innerNumber` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `outterNumber` VARCHAR(250) DEFAULT NULL,
  `street` VARCHAR(250) DEFAULT NULL,
  `idCountry` INT(10) UNSIGNED DEFAULT NULL,
  `idState` INT(10) UNSIGNED DEFAULT NULL,
  `idCity` INT(10) UNSIGNED DEFAULT NULL,
  `cp` INT(8) DEFAULT NULL,
  `idEmployee` int(10) unsigned NOT NULL,
  `idReportType` int(10) unsigned NOT NULL,
  `dot_latitude` DOUBLE DEFAULT NULL,
  `dot_longitude` DOUBLE DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(idEmployee),
  INDEX(idReportType),
  FOREIGN KEY (`idEmployee`) REFERENCES `employee` (`id`),
  FOREIGN KEY (`idReportType`) REFERENCES `reportType` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `multimedia` (
  `id` BIGINT unsigned NOT NULL AUTO_INCREMENT,
  `content` blob NOT NULL,
  `name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `extension` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `reportMultimedia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idReport` BIGINT unsigned NOT NULL,
  `idMultimedia` BIGINT unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idReport`),
  INDEX(`idMultimedia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `task_status_attachments`
--

CREATE TABLE IF NOT EXISTS `task_status_attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IdTaskStatus` int(10) unsigned NOT NULL,
  `idMultimedia` BIGINT unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`IdTaskStatus`),
  INDEX(`idMultimedia`),
  FOREIGN KEY (`IdTaskStatus`) REFERENCES `task_AssignedStatus` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE TABLE IF NOT EXISTS `agreement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `agreementNumber` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `clientName` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `innerNumber` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `outterNumber` VARCHAR(250) DEFAULT NULL,
  `street` VARCHAR(250) DEFAULT NULL,
  `idCountry` INT(10) UNSIGNED DEFAULT NULL,
  `idState` INT(10) UNSIGNED DEFAULT NULL,
  `idCity` INT(10) UNSIGNED DEFAULT NULL,
  `cp` INT(8) DEFAULT NULL,
  `dot_latitude` DOUBLE DEFAULT NULL,
  `dot_longitude` DOUBLE DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idCountry`),
  INDEX(`idState`),
  INDEX(`idCity`),
  FOREIGN KEY (`idCountry`) REFERENCES `country` (`id`),
  FOREIGN KEY (`idState`) REFERENCES `state` (`id`),
  FOREIGN KEY (`idCity`) REFERENCES `city` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reportAgreement`
--

/*CREATE TABLE IF NOT EXISTS `reportAgreement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idReport` BIGINT unsigned DEFAULT NULL,
  `idAgreement` int(10) unsigned DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idReport`),
  INDEX(`idAgreement`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;*/
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE TABLE IF NOT EXISTS place(
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  place_name VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  place_latitude DOUBLE DEFAULT NULL,
  place_longitude DOUBLE DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS dot(
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  dot_latitude DOUBLE DEFAULT NULL,
  dot_longitude DOUBLE DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS track(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  idEmployee INT(10) UNSIGNED NOT NULL,
  idDot BIGINT UNSIGNED NOT NULL,
  idPlace_from BIGINT UNSIGNED NOT NULL,
  idPlace_to BIGINT UNSIGNED NOT NULL,
  start_latitude DOUBLE DEFAULT NULL,
  start_longitude DOUBLE DEFAULT NULL,
  finish_latitude DOUBLE DEFAULT NULL,
  finish_longitude DOUBLE DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX(idEmployee),
  INDEX(idDot),
  INDEX(idPlace_from),
  INDEX(idPlace_to),
  FOREIGN KEY (idEmployee) REFERENCES employee(id),
  FOREIGN KEY (idDot) REFERENCES dot(id),
  FOREIGN KEY (idPlace_from) REFERENCES place(id),
  FOREIGN KEY (idPlace_to) REFERENCES place(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS history(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  idEmployee INT(10) UNSIGNED NOT NULL,
  idTrack INT(10) UNSIGNED NOT NULL,
  hour timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX(idEmployee),
  INDEX(idTrack),
  FOREIGN KEY (idEmployee) REFERENCES employee(id),
  FOREIGN KEY (idTrack) REFERENCES track(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `workflow`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  description VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `status`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  description VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `workflow_status_report`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  idWorkflow INT(10) UNSIGNED NOT NULL,
  idStatus INT(10) UNSIGNED NOT NULL,
  idReport BIGINT UNSIGNED NOT NULL,
  name VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  description VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX(idWorkflow),
  INDEX(idStatus),
  FOREIGN KEY (idWorkflow) REFERENCES workflow(id),
  FOREIGN KEY (idStatus) REFERENCES status(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `workflow_status_agreement`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  idWorkflow INT(10) UNSIGNED NOT NULL,
  idStatus INT(10) UNSIGNED NOT NULL,
  idAgreement INT(10) UNSIGNED NOT NULL,
  name VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  description VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX(idWorkflow),
  INDEX(idStatus),
  INDEX(idAgreement),
  FOREIGN KEY (idWorkflow) REFERENCES workflow(id),
  FOREIGN KEY (idStatus) REFERENCES status(id),
  FOREIGN KEY (idAgreement) REFERENCES agreement(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `form_census`
--

CREATE TABLE IF NOT EXISTS `form_census`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  lote VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  nivel VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  giro VARCHAR(5) COLLATE utf8_unicode_ci NULL,
  acometida TINYINT DEFAULT NULL,
  observacion LONGTEXT COLLATE utf8_unicode_ci NULL,
  tapon TINYINT DEFAULT NULL,
  medidor TINYINT DEFAULT NULL,
  marca VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  tipo VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  NoSerie VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  niple TINYINT DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `form_census_multimedia`
--

CREATE TABLE IF NOT EXISTS `form_census_multimedia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idFormCensus` INT(10) UNSIGNED NOT NULL,
  `idMultimedia` BIGINT UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idFormCensus`),
  INDEX(`idMultimedia`),
  FOREIGN KEY (idFormCensus) REFERENCES form_census(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `form_plumber`
--

CREATE TABLE IF NOT EXISTS `form_plumber`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  consecutive VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  name VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  lastName VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  request VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  tapon TINYINT DEFAULT NULL,
  documentNumber VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  ri TINYINT DEFAULT NULL,
  observations VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  newPipe TINYINT DEFAULT NULL,
  ph TINYINT DEFAULT NULL,
  meeting DATE DEFAULT NULL,
  comments VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `form_plumber_details`
--

CREATE TABLE IF NOT EXISTS `form_plumber_details`(
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  `distance` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  `pipe` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  `fall` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  `idFormPlumber` INT(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idFormPlumber`),
  FOREIGN KEY (idFormPlumber) REFERENCES form_plumber(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `form_plumber_path`
--

CREATE TABLE IF NOT EXISTS `form_plumber_segment`(
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `segment` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `form_plumber_material`
--

CREATE TABLE IF NOT EXISTS `form_plumber_material`(
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `materialName` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `form_plumber_multimedia`
--

CREATE TABLE IF NOT EXISTS `form_plumber_multimedia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idFormPlumber` INT(10) UNSIGNED NOT NULL,
  `idMultimedia` BIGINT UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idFormPlumber`),
  INDEX(`idMultimedia`),
  FOREIGN KEY (idFormPlumber) REFERENCES form_plumber(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `form_sells`
--

CREATE TABLE IF NOT EXISTS form_sells(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  prospect TINYINT DEFAULT NULL,
  uninteresting LONGTEXT COLLATE utf8_unicode_ci NULL,
  comments LONGTEXT COLLATE utf8_unicode_ci NULL,
  owner TINYINT DEFAULT NULL,
  consecutive VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  name VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  lastName VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  lastNameOp VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  payment TINYINT DEFAULT NULL,
  financialService TINYINT DEFAULT NULL,
  requestNumber VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  meeting DATE DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `form_sells_multimedia`
--

CREATE TABLE IF NOT EXISTS `form_sells_multimedia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idSell` INT(10) UNSIGNED NOT NULL,
  `idMultimedia` BIGINT UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idSell`),
  INDEX(`idMultimedia`),
  FOREIGN KEY (idSell) REFERENCES form_sells(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `form_installation`
--
CREATE TABLE IF NOT EXISTS form_installation(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  consecutive VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  name VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  lastName VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  request VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  phLabel TINYINT DEFAULT NULL,
  agencyPh TINYINT DEFAULT NULL,
  agencyNumber VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  installation TINYINT DEFAULT NULL,
  abnormalities VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  comments VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  brand VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  type VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  serialNuber VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  measurement VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  active TINYINT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `form_installation_material`
--

CREATE TABLE IF NOT EXISTS `form_installation_material`(
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `materialName` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `form_installation_details`
--

CREATE TABLE IF NOT EXISTS `form_installation_details`(
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `qty` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idFormInstallation` INT(10) UNSIGNED NOT NULL,
  `idInstallationMaterial` INT(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idFormInstallation`),
  INDEX(`idInstallationMaterial`),
  FOREIGN KEY (idFormInstallation) REFERENCES form_installation(id),
  FOREIGN KEY (idInstallationMaterial) REFERENCES form_installation_material(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `form_installation_multimedia`
--

CREATE TABLE IF NOT EXISTS `form_installation_multimedia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idFormInstallation` INT(10) UNSIGNED NOT NULL,
  `idMultimedia` BIGINT UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idFormInstallation`),
  INDEX(`idMultimedia`),
  FOREIGN KEY (idFormInstallation) REFERENCES form_installation(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `report_employee_report`
--

CREATE TABLE IF NOT EXISTS `report_employee_form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idReport` INT(10) UNSIGNED NOT NULL,
  `idEmployee` INT(10) UNSIGNED NOT NULL,
  `idForm` INT(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idReport`),
  INDEX(`idEmployee`),
  INDEX(`idForm`),
  FOREIGN KEY (idEmployee) REFERENCES employee(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `agreement_employee_form`
--

CREATE TABLE IF NOT EXISTS `agreement_employee_form` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idAgreement` INT(10) UNSIGNED NOT NULL,
  `idEmployee` INT(10) UNSIGNED NOT NULL,
  `idForm` INT(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idAgreement`),
  INDEX(`idEmployee`),
  INDEX(`idForm`),
  FOREIGN KEY (idAgreement) REFERENCES agreement(id),
  FOREIGN KEY (idEmployee) REFERENCES employee(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
  `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
  `idTask` INT(10) UNSIGNED NOT NULL,
  `idReport` BIGINT UNSIGNED NOT NULL,
  `idAgreementForm` INT(10) UNSIGNED NOT NULL,
  `idAgreement` INT(10) UNSIGNED NOT NULL,
  `idEmployee` INT(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idTask`),
  INDEX(`idReport`),
  INDEX(`idAgreementForm`),
  INDEX(`idAgreement`),
  INDEX(`idEmployee`),
  FOREIGN KEY (`idTask`) REFERENCES task(id),
  FOREIGN KEY (`idAgreementForm`) REFERENCES agreement_employee_form(id),
  FOREIGN KEY (`idAgreement`) REFERENCES agreement(id),
  FOREIGN KEY (`idEmployee`) REFERENCES employee(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `device`
--

CREATE TABLE IF NOT EXISTS `device`(
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idDevice` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  `model` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  `tokenDevice` VARCHAR(255) COLLATE utf8_unicode_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `device_employee`
--

CREATE TABLE IF NOT EXISTS `device_employee`(
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idDeviceTable` INT(10) UNSIGNED NOT NULL,
  `idEmployee` INT(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX(`idDeviceTable`),
  INDEX(`idEmployee`),
  FOREIGN KEY (idDeviceTable) REFERENCES device(id),
  FOREIGN KEY (idEmployee) REFERENCES employee(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;