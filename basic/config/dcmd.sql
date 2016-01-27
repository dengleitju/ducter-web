-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: dcmd
-- ------------------------------------------------------
-- Server version	5.1.73

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `dcmd_app`
--

DROP TABLE IF EXISTS `dcmd_app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app` (
  `app_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(128) NOT NULL,
  `app_alias` varchar(128) NOT NULL,
  `sa_gid` int(10) unsigned NOT NULL,
  `svr_gid` int(10) unsigned NOT NULL,
  `depart_id` int(10) unsigned NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`app_id`),
  UNIQUE KEY `app_name` (`app_name`),
  UNIQUE KEY `app_alias` (`app_alias`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app`
--

LOCK TABLES `dcmd_app` WRITE;
/*!40000 ALTER TABLE `dcmd_app` DISABLE KEYS */;
INSERT INTO `dcmd_app` VALUES (52,'test_app','test_app_alias',52,53,52,'test','2015-10-14 11:32:42','2015-10-14 11:32:42',1);
/*!40000 ALTER TABLE `dcmd_app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_arch_diagram`
--

DROP TABLE IF EXISTS `dcmd_app_arch_diagram`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_arch_diagram` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `arch_name` varchar(200) NOT NULL,
  `diagram` longblob,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_app_arch_diagram` (`app_id`,`arch_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_arch_diagram`
--

LOCK TABLES `dcmd_app_arch_diagram` WRITE;
/*!40000 ALTER TABLE `dcmd_app_arch_diagram` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_app_arch_diagram` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_center`
--

DROP TABLE IF EXISTS `dcmd_center`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_center` (
  `host` varchar(32) NOT NULL,
  `master` int(11) NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`host`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_center`
--

LOCK TABLES `dcmd_center` WRITE;
/*!40000 ALTER TABLE `dcmd_center` DISABLE KEYS */;
INSERT INTO `dcmd_center` VALUES ('182.92.7.132:6667',1,'2015-12-03 10:31:55');
/*!40000 ALTER TABLE `dcmd_center` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_command`
--

DROP TABLE IF EXISTS `dcmd_command`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_command` (
  `cmd_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `subtask_id` bigint(20) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `cmd_type` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cmd_id`),
  KEY `idx_command_svr` (`svr_name`,`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_command`
--

LOCK TABLES `dcmd_command` WRITE;
/*!40000 ALTER TABLE `dcmd_command` DISABLE KEYS */;
INSERT INTO `dcmd_command` VALUES (2,3,0,'',0,'test_app_server_pool_1','',1,1,'','2015-10-14 14:09:46','2015-10-14 14:09:46',1),(3,3,1,'test_app_server_pool_11',14,'test_app_server_pool_1','10.162.207.221',9,1,'','2015-10-14 14:09:52','2015-10-14 14:09:52',1);
/*!40000 ALTER TABLE `dcmd_command` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_command_history`
--

DROP TABLE IF EXISTS `dcmd_command_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_command_history` (
  `cmd_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `subtask_id` bigint(20) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `cmd_type` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cmd_id`),
  KEY `idx_command_svr` (`svr_name`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_command_history`
--

LOCK TABLES `dcmd_command_history` WRITE;
/*!40000 ALTER TABLE `dcmd_command_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_command_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_cron`
--

DROP TABLE IF EXISTS `dcmd_cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_cron` (
  `cron_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_name` varchar(64) NOT NULL,
  `script_name` varchar(64) NOT NULL,
  `script_md5` varchar(32) NOT NULL,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `min` varchar(32) NOT NULL,
  `hour` varchar(32) NOT NULL,
  `day` varchar(32) NOT NULL,
  `month` varchar(32) NOT NULL,
  `week` varchar(32) NOT NULL,
  `arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cron_id`),
  KEY `cron_name` (`cron_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_cron`
--

LOCK TABLES `dcmd_cron` WRITE;
/*!40000 ALTER TABLE `dcmd_cron` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_cron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_cron_event`
--

DROP TABLE IF EXISTS `dcmd_cron_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_cron_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_id` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `level` int(10) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cron_ip` (`ip`,`cron_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_cron_event`
--

LOCK TABLES `dcmd_cron_event` WRITE;
/*!40000 ALTER TABLE `dcmd_cron_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_cron_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_cron_node`
--

DROP TABLE IF EXISTS `dcmd_cron_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_cron_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_id` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `cron_state` int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cron_ip` (`cron_id`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_cron_node`
--

LOCK TABLES `dcmd_cron_node` WRITE;
/*!40000 ALTER TABLE `dcmd_cron_node` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_cron_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_department`
--

DROP TABLE IF EXISTS `dcmd_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_department` (
  `depart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `depart_name` varchar(64) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`depart_id`),
  UNIQUE KEY `app` (`depart_name`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_department`
--

LOCK TABLES `dcmd_department` WRITE;
/*!40000 ALTER TABLE `dcmd_department` DISABLE KEYS */;
INSERT INTO `dcmd_department` VALUES (52,'开发部1','开发部1','2015-10-14 11:32:28','2015-10-14 11:32:28',1);
/*!40000 ALTER TABLE `dcmd_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_group`
--

DROP TABLE IF EXISTS `dcmd_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_group` (
  `gid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gname` varchar(64) NOT NULL,
  `gtype` int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`gid`),
  UNIQUE KEY `app` (`gname`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_group`
--

LOCK TABLES `dcmd_group` WRITE;
/*!40000 ALTER TABLE `dcmd_group` DISABLE KEYS */;
INSERT INTO `dcmd_group` VALUES (52,'test_sys',1,'test','2015-10-13 17:09:54','2015-10-13 17:09:54',1),(53,'test_work',2,'test','2015-10-13 17:10:12','2015-10-13 17:10:12',1);
/*!40000 ALTER TABLE `dcmd_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_group_cmd`
--

DROP TABLE IF EXISTS `dcmd_group_cmd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_group_cmd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`gid`,`opr_cmd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_group_cmd`
--

LOCK TABLES `dcmd_group_cmd` WRITE;
/*!40000 ALTER TABLE `dcmd_group_cmd` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_group_cmd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_group_repeat_cmd`
--

DROP TABLE IF EXISTS `dcmd_group_repeat_cmd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_group_repeat_cmd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL,
  `repeat_cmd_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`gid`,`repeat_cmd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_group_repeat_cmd`
--

LOCK TABLES `dcmd_group_repeat_cmd` WRITE;
/*!40000 ALTER TABLE `dcmd_group_repeat_cmd` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_group_repeat_cmd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_node`
--

DROP TABLE IF EXISTS `dcmd_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_node` (
  `nid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL,
  `ngroup_id` int(10) NOT NULL,
  `host` varchar(128) NOT NULL,
  `sid` varchar(128) NOT NULL,
  `did` varchar(128) NOT NULL,
  `os_type` varchar(128) NOT NULL,
  `os_ver` varchar(128) NOT NULL,
  `bend_ip` varchar(16) NOT NULL,
  `public_ip` varchar(16) NOT NULL,
  `mach_room` varchar(128) NOT NULL,
  `rack` varchar(32) NOT NULL,
  `seat` varchar(32) NOT NULL,
  `online_time` datetime NOT NULL,
  `server_brand` varchar(128) NOT NULL,
  `server_model` varchar(32) NOT NULL,
  `cpu` varchar(32) NOT NULL,
  `memory` varchar(32) NOT NULL,
  `disk` varchar(64) NOT NULL,
  `purchase_time` datetime NOT NULL,
  `maintain_time` datetime NOT NULL,
  `maintain_fac` varchar(128) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`nid`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_node`
--

LOCK TABLES `dcmd_node` WRITE;
/*!40000 ALTER TABLE `dcmd_node` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_node_group`
--

DROP TABLE IF EXISTS `dcmd_node_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_node_group` (
  `ngroup_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngroup_name` varchar(128) NOT NULL,
  `gid` int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ngroup_id`),
  UNIQUE KEY `ngroup_name` (`ngroup_name`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_node_group`
--

LOCK TABLES `dcmd_node_group` WRITE;
/*!40000 ALTER TABLE `dcmd_node_group` DISABLE KEYS */;
INSERT INTO `dcmd_node_group` VALUES (22,'test_pool',52,'test','2015-10-13 17:11:50','2015-10-13 17:11:50',1);
/*!40000 ALTER TABLE `dcmd_node_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_node_group_attr`
--

DROP TABLE IF EXISTS `dcmd_node_group_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_node_group_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngroup_id` int(10) unsigned NOT NULL,
  `attr_name` varchar(32) NOT NULL,
  `attr_value` varchar(256) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_node_group_attr` (`ngroup_id`,`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_node_group_attr`
--

LOCK TABLES `dcmd_node_group_attr` WRITE;
/*!40000 ALTER TABLE `dcmd_node_group_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_node_group_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_node_group_attr_def`
--

DROP TABLE IF EXISTS `dcmd_node_group_attr_def`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_node_group_attr_def` (
  `attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(32) NOT NULL,
  `optional` int(11) NOT NULL,
  `attr_type` int(10) NOT NULL,
  `def_value` varchar(256) DEFAULT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`attr_id`),
  UNIQUE KEY `dcmd_node_group_attr_def` (`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_node_group_attr_def`
--

LOCK TABLES `dcmd_node_group_attr_def` WRITE;
/*!40000 ALTER TABLE `dcmd_node_group_attr_def` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_node_group_attr_def` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd` (
  `opr_cmd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opr_cmd` varchar(64) NOT NULL,
  `ui_name` varchar(255) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `script_md5` varchar(32) NOT NULL DEFAULT '',
  `timeout` int(11) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`opr_cmd_id`),
  UNIQUE KEY `opr_cmd` (`opr_cmd`),
  UNIQUE KEY `ui_name` (`ui_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd`
--

LOCK TABLES `dcmd_opr_cmd` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd` DISABLE KEYS */;
INSERT INTO `dcmd_opr_cmd` VALUES (1,'host_user','get_host_user','sre','f4b5024642b198e66b8d27acec0bd09b',20,'»ñÈ¡Ö÷»úÓÃ»§','2014-02-20 15:55:05','2014-02-20 15:55:05',1),(2,'os_info','os_info','sre','78cd5d54bca0b4ee2c4c17a7c08f6759',10,'»ñÈ¡Ö÷»úÐÅÏ¢','2014-02-20 15:55:05','2014-02-20 15:55:05',1);
/*!40000 ALTER TABLE `dcmd_opr_cmd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd_arg`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd_arg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd_arg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `arg_name` varchar(32) NOT NULL,
  `optional` int(11) NOT NULL,
  `arg_type` int(10) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `opr_cmd_id` (`opr_cmd_id`,`arg_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd_arg`
--

LOCK TABLES `dcmd_opr_cmd_arg` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd_arg` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_opr_cmd_arg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd_exec`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd_exec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd_exec` (
  `exec_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`exec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd_exec`
--

LOCK TABLES `dcmd_opr_cmd_exec` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd_exec` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_opr_cmd_exec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd_exec_history`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd_exec_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd_exec_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `exec_id` bigint(20) NOT NULL,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_exec_id` (`exec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd_exec_history`
--

LOCK TABLES `dcmd_opr_cmd_exec_history` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd_exec_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_opr_cmd_exec_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd_repeat_exec`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd_repeat_exec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd_repeat_exec` (
  `repeat_cmd_id` int(10) NOT NULL AUTO_INCREMENT,
  `repeat_cmd_name` varchar(64) NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `repeat` int(10) NOT NULL,
  `cache_time` int(10) NOT NULL,
  `ip_mutable` int(10) NOT NULL,
  `arg_mutable` int(10) NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`repeat_cmd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd_repeat_exec`
--

LOCK TABLES `dcmd_opr_cmd_repeat_exec` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd_repeat_exec` DISABLE KEYS */;
INSERT INTO `dcmd_opr_cmd_repeat_exec` VALUES (4,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:34:58','2015-10-14 11:34:58',1);
/*!40000 ALTER TABLE `dcmd_opr_cmd_repeat_exec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd_repeat_exec_history`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd_repeat_exec_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd_repeat_exec_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `repeat_cmd_name` varchar(64) NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `repeat` int(10) NOT NULL,
  `cache_time` int(10) NOT NULL,
  `ip_mutable` int(10) NOT NULL,
  `arg_mutable` int(10) NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd_repeat_exec_history`
--

LOCK TABLES `dcmd_opr_cmd_repeat_exec_history` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd_repeat_exec_history` DISABLE KEYS */;
INSERT INTO `dcmd_opr_cmd_repeat_exec_history` VALUES (4,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:35:05','2015-10-14 11:34:58',1),(5,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:35:12','2015-10-14 11:34:58',1),(6,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:08','2015-10-14 11:34:58',1),(7,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:11','2015-10-14 11:34:58',1),(8,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:12','2015-10-14 11:34:58',1),(9,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:12','2015-10-14 11:34:58',1),(10,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:13','2015-10-14 11:34:58',1),(11,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:15','2015-10-14 11:34:58',1),(12,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:16','2015-10-14 11:34:58',1),(13,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:16','2015-10-14 11:34:58',1),(14,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:17','2015-10-14 11:34:58',1),(15,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:18','2015-10-14 11:34:58',1),(16,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:37:19','2015-10-14 11:34:58',1),(17,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:39:38','2015-10-14 11:34:58',1),(18,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:43:23','2015-10-14 11:34:58',1),(19,'os_info','os_info','sre',10,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2015-10-14 11:43:26','2015-10-14 11:34:58',1);
/*!40000 ALTER TABLE `dcmd_opr_cmd_repeat_exec_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_log`
--

DROP TABLE IF EXISTS `dcmd_opr_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_table` varchar(64) NOT NULL,
  `opr_type` int(11) NOT NULL,
  `sql_statement` text NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_opr_log_table` (`log_table`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_log`
--

LOCK TABLES `dcmd_opr_log` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_log` DISABLE KEYS */;
INSERT INTO `dcmd_opr_log` VALUES (1,'dcmd_group',1,'insert group:test_sys','2015-10-13 17:09:54',1),(2,'dcmd_group',1,'insert group:test_work','2015-10-13 17:10:12',1),(3,'dcmd_user_group',1,'add user:1 group:52','2015-10-13 17:11:37',1),(4,'dcmd_node',1,'insert node:10.162.207.221','2015-10-13 17:14:35',1),(5,'dcmd_department',1,'insert department:开发部1','2015-10-14 11:32:28',1),(6,'dcmd_app',1,'insert app:test_app','2015-10-14 11:32:42',1),(7,'dcmd_service',1,'insert service:test_app_server_pool_1','2015-10-14 11:33:22',1),(8,'dcmd_service_pool',1,'insert service pool:test_app_server_pool_11','2015-10-14 11:33:57',1),(9,'dcmd_service_pool_node',1,'add ip:10.162.207.221','2015-10-14 11:34:04',1),(10,'dcmd_opr_cmd_repeat_exec',1,'insert repeat exec cmd:os_info','2015-10-14 11:34:58',1),(11,'dcmd_app',1,'insert task template:etst','2015-10-14 13:56:49',1),(12,'dcmd_task',1,'insert task:½ø¶È²âÊÔÈÎÎñ½Å±¾-20151014135742-test','2015-10-14 13:58:03',1),(13,'dcmd_task',1,'insert task:½ø¶È²âÊÔÈÎÎñ½Å±¾-20151014135845-asdf','2015-10-14 13:58:57',1),(14,'dcmd_task',1,'insert task:½ø¶È²âÊÔÈÎÎñ½Å±¾-20151014135845-asdf','2015-10-14 14:02:23',1),(15,'dcmd_task',1,'insert task:½ø¶È²âÊÔÈÎÎñ½Å±¾-20151014140229-asdf','2015-10-14 14:09:04',1),(16,'dcmd_node',3,'delete node:10.162.207.221','2015-10-14 16:55:17',1);
/*!40000 ALTER TABLE `dcmd_opr_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service`
--

DROP TABLE IF EXISTS `dcmd_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service` (
  `svr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_name` varchar(128) NOT NULL,
  `svr_alias` varchar(128) NOT NULL,
  `svr_path` varchar(128) NOT NULL,
  `run_user` varchar(16) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `node_multi_pool` int(10) unsigned NOT NULL,
  `owner` int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`svr_id`),
  UNIQUE KEY `svr_name` (`app_id`,`svr_name`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service`
--

LOCK TABLES `dcmd_service` WRITE;
/*!40000 ALTER TABLE `dcmd_service` DISABLE KEYS */;
INSERT INTO `dcmd_service` VALUES (26,'test_app_server_pool_1','test_app_server_pool_1_alias','/home/denglei','denglei',52,0,1,'test','2015-10-14 11:33:22','2015-10-14 11:33:22',1);
/*!40000 ALTER TABLE `dcmd_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_arch_diagram`
--

DROP TABLE IF EXISTS `dcmd_service_arch_diagram`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_arch_diagram` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `arch_name` varchar(200) NOT NULL,
  `diagram` longblob,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_service_arch_diagram` (`svr_id`,`arch_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_arch_diagram`
--

LOCK TABLES `dcmd_service_arch_diagram` WRITE;
/*!40000 ALTER TABLE `dcmd_service_arch_diagram` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_arch_diagram` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_monitor`
--

DROP TABLE IF EXISTS `dcmd_service_monitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_monitor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `monitor_type` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `check_script_name` varchar(64) NOT NULL,
  `check_script_md5` varchar(32) NOT NULL,
  `check_script_arg` text,
  `start_script_name` varchar(64) DEFAULT NULL,
  `start_script_md5` varchar(32) DEFAULT NULL,
  `start_script_arg` text,
  `stop_script_name` varchar(64) DEFAULT NULL,
  `stop_script_md5` varchar(32) DEFAULT NULL,
  `stop_script_arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_service_monitor` (`app_id`,`svr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_monitor`
--

LOCK TABLES `dcmd_service_monitor` WRITE;
/*!40000 ALTER TABLE `dcmd_service_monitor` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_monitor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_monitor_stat`
--

DROP TABLE IF EXISTS `dcmd_service_monitor_stat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_monitor_stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `level` int(10) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_monitor_stat`
--

LOCK TABLES `dcmd_service_monitor_stat` WRITE;
/*!40000 ALTER TABLE `dcmd_service_monitor_stat` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_monitor_stat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool`
--

DROP TABLE IF EXISTS `dcmd_service_pool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool` (
  `svr_pool_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `app_id` int(10) NOT NULL,
  `repo` varchar(512) NOT NULL,
  `env_ver` varchar(64) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`svr_pool_id`),
  UNIQUE KEY `svr_pool` (`svr_id`,`svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool`
--

LOCK TABLES `dcmd_service_pool` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool` DISABLE KEYS */;
INSERT INTO `dcmd_service_pool` VALUES (14,'test_app_server_pool_11',26,52,'as','afda','asdfa','2015-10-14 11:33:57','2015-10-14 11:33:57',1);
/*!40000 ALTER TABLE `dcmd_service_pool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool_attr`
--

DROP TABLE IF EXISTS `dcmd_service_pool_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `attr_name` varchar(32) NOT NULL,
  `attr_value` varchar(256) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_service_pool_attr` (`svr_pool_id`,`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool_attr`
--

LOCK TABLES `dcmd_service_pool_attr` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_pool_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool_attr_def`
--

DROP TABLE IF EXISTS `dcmd_service_pool_attr_def`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool_attr_def` (
  `attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(32) NOT NULL,
  `optional` int(11) NOT NULL,
  `attr_type` int(10) NOT NULL,
  `def_value` varchar(256) DEFAULT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`attr_id`),
  UNIQUE KEY `dcmd_service_pool_attr_def` (`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool_attr_def`
--

LOCK TABLES `dcmd_service_pool_attr_def` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool_attr_def` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_pool_attr_def` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool_node`
--

DROP TABLE IF EXISTS `dcmd_service_pool_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `nid` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_pool_id` (`svr_pool_id`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool_node`
--

LOCK TABLES `dcmd_service_pool_node` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool_node` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_pool_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_softpkg`
--

DROP TABLE IF EXISTS `dcmd_softpkg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_softpkg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(11) unsigned NOT NULL,
  `svr_id` int(11) unsigned NOT NULL,
  `version` varchar(64) NOT NULL,
  `repo_file` varchar(256) NOT NULL,
  `upload_file` varchar(256) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `repo_file` (`repo_file`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_softpkg`
--

LOCK TABLES `dcmd_softpkg` WRITE;
/*!40000 ALTER TABLE `dcmd_softpkg` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_softpkg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task`
--

DROP TABLE IF EXISTS `dcmd_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task` (
  `task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_name` varchar(128) NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `depend_task_id` int(10) unsigned NOT NULL,
  `depend_task_name` varchar(128) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_path` varchar(256) NOT NULL,
  `tag` varchar(128) NOT NULL,
  `update_env` int(10) NOT NULL,
  `update_tag` int(10) NOT NULL,
  `node_multi_pool` int(10) unsigned NOT NULL,
  `state` int(11) NOT NULL,
  `freeze` int(11) NOT NULL,
  `valid` int(11) NOT NULL,
  `pause` int(11) NOT NULL,
  `err_msg` varchar(512) NOT NULL,
  `concurrent_rate` int(10) NOT NULL,
  `timeout` int(10) NOT NULL,
  `auto` int(11) NOT NULL,
  `process` int(10) NOT NULL,
  `task_arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `idx_task_name` (`task_name`),
  KEY `idx_dcmd_svr_task_name` (`svr_name`,`task_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task`
--

LOCK TABLES `dcmd_task` WRITE;
/*!40000 ALTER TABLE `dcmd_task` DISABLE KEYS */;
INSERT INTO `dcmd_task` VALUES (1,'½ø¶È²âÊÔÈÎÎñ½Å±¾-20151014135742-test','test_process',0,'NULL',52,'test_app',26,'test_app_server_pool_1','/home/denglei','v2',0,0,0,0,0,1,0,'',10,10,0,0,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','','2015-10-14 13:58:03','2015-10-14 13:58:03',1),(2,'½ø¶È²âÊÔÈÎÎñ½Å±¾-20151014135845-asdf','test_process',0,'NULL',52,'test_app',26,'test_app_server_pool_1','/home/denglei','v1',0,0,0,0,0,1,0,'',10,10,0,0,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','','2015-10-14 13:58:57','2015-10-14 13:58:57',1),(3,'½ø¶È²âÊÔÈÎÎñ½Å±¾-20151014140229-asdf','test_process',0,'NULL',52,'test_app',26,'test_app_server_pool_1','/home/denglei','v1',0,0,0,3,0,1,0,'',10,10,0,0,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','','2015-10-14 14:09:04','2015-10-14 14:09:04',1);
/*!40000 ALTER TABLE `dcmd_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_cmd`
--

DROP TABLE IF EXISTS `dcmd_task_cmd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_cmd` (
  `task_cmd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_cmd` varchar(64) NOT NULL,
  `script_md5` varchar(32) NOT NULL,
  `timeout` int(11) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  `ui_name` varchar(64) NOT NULL,
  PRIMARY KEY (`task_cmd_id`),
  UNIQUE KEY `task_cmd` (`task_cmd`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_cmd`
--

LOCK TABLES `dcmd_task_cmd` WRITE;
/*!40000 ALTER TABLE `dcmd_task_cmd` DISABLE KEYS */;
INSERT INTO `dcmd_task_cmd` VALUES (1,'test_process','eee6d3932d4e082d3850b9ec30e68c91',1800,'ÓÃÓÚ²âÊÔdcmd½ø¶ÈÏÔÊ¾','2014-12-27 10:56:00','2014-12-27 10:56:00',1,'½ø¶È²âÊÔÈÎÎñ½Å±¾'),(2,'test_task_env','1c737d825d3f88174d637bf1ac8dffde',100,'²âÊÔtask»·¾³±äÁ¿','2014-12-27 10:56:00','2014-12-27 10:56:00',1,'½ø¶ÈTask»·¾³±äÁ¿'),(3,'install_by_svn','4f2d5b646166939bcbd5a6da6f042b42',1000,'','2014-12-27 10:56:00','2014-12-27 10:56:00',1,'install_by_svn');
/*!40000 ALTER TABLE `dcmd_task_cmd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_cmd_arg`
--

DROP TABLE IF EXISTS `dcmd_task_cmd_arg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_cmd_arg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_cmd_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `arg_name` varchar(32) NOT NULL,
  `optional` int(10) NOT NULL,
  `arg_type` int(10) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_cmd` (`task_cmd_id`,`arg_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_cmd_arg`
--

LOCK TABLES `dcmd_task_cmd_arg` WRITE;
/*!40000 ALTER TABLE `dcmd_task_cmd_arg` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_cmd_arg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_history`
--

DROP TABLE IF EXISTS `dcmd_task_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_history` (
  `task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_name` varchar(128) NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `depend_task_id` int(10) unsigned NOT NULL,
  `depend_task_name` varchar(128) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_path` varchar(256) NOT NULL,
  `tag` varchar(128) NOT NULL,
  `update_env` int(10) NOT NULL,
  `update_tag` int(10) NOT NULL,
  `node_multi_pool` int(10) unsigned NOT NULL,
  `state` int(11) NOT NULL,
  `freeze` int(11) NOT NULL,
  `valid` int(11) NOT NULL,
  `pause` int(11) NOT NULL,
  `err_msg` varchar(512) NOT NULL,
  `concurrent_rate` int(10) NOT NULL,
  `timeout` int(10) NOT NULL,
  `auto` int(11) NOT NULL,
  `process` int(10) NOT NULL,
  `task_arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `idx_task_finish_name` (`task_name`),
  KEY `idx_task_svr_task_finish_name` (`svr_name`,`task_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_history`
--

LOCK TABLES `dcmd_task_history` WRITE;
/*!40000 ALTER TABLE `dcmd_task_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_node`
--

DROP TABLE IF EXISTS `dcmd_task_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_node` (
  `subtask_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `state` int(11) NOT NULL,
  `ignored` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `finish_time` datetime NOT NULL,
  `process` varchar(32) DEFAULT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`subtask_id`),
  UNIQUE KEY `task_id` (`task_id`,`ip`,`svr_pool`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_node`
--

LOCK TABLES `dcmd_task_node` WRITE;
/*!40000 ALTER TABLE `dcmd_task_node` DISABLE KEYS */;
INSERT INTO `dcmd_task_node` VALUES (1,3,'test_process','test_app_server_pool_11','test_app_server_pool_1','10.162.207.221',2,0,'2015-10-14 14:09:52','2015-10-14 14:10:42','100','','2015-10-14 14:10:42','2015-10-14 14:09:16',1);
/*!40000 ALTER TABLE `dcmd_task_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_node_history`
--

DROP TABLE IF EXISTS `dcmd_task_node_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_node_history` (
  `subtask_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `state` int(11) NOT NULL,
  `ignored` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `finish_time` datetime NOT NULL,
  `process` varchar(32) DEFAULT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`subtask_id`),
  UNIQUE KEY `task_id` (`task_id`,`ip`,`svr_pool`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_node_history`
--

LOCK TABLES `dcmd_task_node_history` WRITE;
/*!40000 ALTER TABLE `dcmd_task_node_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_node_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_service_pool`
--

DROP TABLE IF EXISTS `dcmd_task_service_pool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_service_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `env_ver` varchar(64) NOT NULL,
  `repo` varchar(512) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `undo_node` int(10) unsigned NOT NULL,
  `doing_node` int(10) unsigned NOT NULL,
  `finish_node` int(10) unsigned NOT NULL,
  `fail_node` int(10) unsigned NOT NULL,
  `ignored_fail_node` int(10) unsigned NOT NULL,
  `ignored_doing_node` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  `state` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_task_id_svr_pool_id` (`task_id`,`svr_pool_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_service_pool`
--

LOCK TABLES `dcmd_task_service_pool` WRITE;
/*!40000 ALTER TABLE `dcmd_task_service_pool` DISABLE KEYS */;
INSERT INTO `dcmd_task_service_pool` VALUES (1,3,'test_process','test_app_server_pool_11',14,'afda','as','denglei',0,0,1,0,0,0,'2015-10-14 14:09:04','2015-10-14 14:09:04',1,3);
/*!40000 ALTER TABLE `dcmd_task_service_pool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_service_pool_attr`
--

DROP TABLE IF EXISTS `dcmd_task_service_pool_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_service_pool_attr` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `attr_name` varchar(32) NOT NULL,
  `attr_value` varchar(256) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_task_service_pool_attr` (`task_id`,`svr_pool_id`,`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_service_pool_attr`
--

LOCK TABLES `dcmd_task_service_pool_attr` WRITE;
/*!40000 ALTER TABLE `dcmd_task_service_pool_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_service_pool_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_service_pool_attr_history`
--

DROP TABLE IF EXISTS `dcmd_task_service_pool_attr_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_service_pool_attr_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `attr_name` varchar(32) NOT NULL,
  `attr_value` varchar(256) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_task_service_pool_attr_history` (`task_id`,`svr_pool_id`,`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_service_pool_attr_history`
--

LOCK TABLES `dcmd_task_service_pool_attr_history` WRITE;
/*!40000 ALTER TABLE `dcmd_task_service_pool_attr_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_service_pool_attr_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_service_pool_history`
--

DROP TABLE IF EXISTS `dcmd_task_service_pool_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_service_pool_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `env_ver` varchar(64) NOT NULL,
  `repo` varchar(512) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `undo_node` int(10) unsigned NOT NULL,
  `doing_node` int(10) unsigned NOT NULL,
  `finish_node` int(10) unsigned NOT NULL,
  `fail_node` int(10) unsigned NOT NULL,
  `ignored_fail_node` int(10) unsigned NOT NULL,
  `ignored_doing_node` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_id` (`task_id`,`svr_pool`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_service_pool_history`
--

LOCK TABLES `dcmd_task_service_pool_history` WRITE;
/*!40000 ALTER TABLE `dcmd_task_service_pool_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_service_pool_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_template`
--

DROP TABLE IF EXISTS `dcmd_task_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_template` (
  `task_tmpt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_tmpt_name` varchar(128) NOT NULL,
  `task_cmd_id` int(10) NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `app_id` int(10) NOT NULL,
  `update_env` int(10) NOT NULL,
  `concurrent_rate` int(10) NOT NULL,
  `timeout` int(10) NOT NULL,
  `process` int(10) NOT NULL,
  `auto` int(11) NOT NULL,
  `task_arg` text,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`task_tmpt_id`),
  UNIQUE KEY `task_tmpt_name` (`task_tmpt_name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_template`
--

LOCK TABLES `dcmd_task_template` WRITE;
/*!40000 ALTER TABLE `dcmd_task_template` DISABLE KEYS */;
INSERT INTO `dcmd_task_template` VALUES (24,'etst',1,'test_process',26,'test_app_server_pool_1',52,0,10,10,0,0,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','asdf','2015-10-14 13:56:49','2015-10-14 13:56:49',1);
/*!40000 ALTER TABLE `dcmd_task_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_template_service_pool`
--

DROP TABLE IF EXISTS `dcmd_task_template_service_pool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_template_service_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_tmpt_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_tmpt_id` (`task_tmpt_id`,`svr_pool_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_template_service_pool`
--

LOCK TABLES `dcmd_task_template_service_pool` WRITE;
/*!40000 ALTER TABLE `dcmd_task_template_service_pool` DISABLE KEYS */;
INSERT INTO `dcmd_task_template_service_pool` VALUES (1,24,14,'2015-10-14 13:57:33','2015-10-14 13:57:33',1);
/*!40000 ALTER TABLE `dcmd_task_template_service_pool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_user`
--

DROP TABLE IF EXISTS `dcmd_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `passwd` varchar(64) NOT NULL,
  `sa` int(10) NOT NULL,
  `admin` int(10) NOT NULL,
  `depart_id` int(10) NOT NULL,
  `tel` varchar(128) NOT NULL,
  `email` varchar(64) NOT NULL,
  `state` int(11) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_user`
--

LOCK TABLES `dcmd_user` WRITE;
/*!40000 ALTER TABLE `dcmd_user` DISABLE KEYS */;
INSERT INTO `dcmd_user` VALUES (1,'admin','6910a551ce91b6b4b9258925bc72aaeb',1,1,0,'','',1,'','2014-12-19 03:13:54','2014-12-19 03:13:54',1);
/*!40000 ALTER TABLE `dcmd_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_user_group`
--

DROP TABLE IF EXISTS `dcmd_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_user_group`
--

LOCK TABLES `dcmd_user_group` WRITE;
/*!40000 ALTER TABLE `dcmd_user_group` DISABLE KEYS */;
INSERT INTO `dcmd_user_group` VALUES (38,1,52,'comment','2015-10-13 17:11:37','2015-10-13 17:11:37',1);
/*!40000 ALTER TABLE `dcmd_user_group` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-03 10:31:55
