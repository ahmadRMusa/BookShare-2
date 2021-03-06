/*
Navicat MySQL Data Transfer

Source Server         : 157
Source Server Version : 50067
Source Host           : 192.168.21.157:3306
Source Database       : bookshare

Target Server Type    : MYSQL
Target Server Version : 50067
File Encoding         : 65001

Date: 2012-04-27 12:32:58
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `books`
-- ----------------------------
DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(45) NOT NULL,
  `description` varchar(1000) default NULL,
  `short_description` varchar(200) default NULL,
  `points` float default '0',
  `create_time` timestamp NULL default CURRENT_TIMESTAMP,
  `create_by` int(10) unsigned default NULL,
  `rating` int(11) default '0',
  `active` int(11) default '0',
  `download_times` int(11) default '0',
  `pages` varchar(500) default NULL,
  `xml` varchar(10000) default NULL,
  `file_path` varchar(500) default NULL,
  `file_url` varchar(500) default NULL,
  `promote` int(11) default '0',
  `cover` varchar(500) default NULL,
  `thumb` varchar(500) default NULL,
  `version` int(11) NOT NULL default '1',
  `author` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

