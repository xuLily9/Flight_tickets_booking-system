/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : demo

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-07-30 15:16:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `plane`
-- ----------------------------
DROP TABLE IF EXISTS `plane`;
CREATE TABLE `plane` (
  `plane_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Airline_id',
  `destination` varchar(20) NOT NULL DEFAULT '' COMMENT 'Destination_city',
  `airline` varchar(30) NOT NULL DEFAULT '' COMMENT 'Airline',
  `capacity` tinyint(3) unsigned NOT NULL COMMENT 'Empty_seats',
  `base_price` float(8,0) unsigned DEFAULT NULL COMMENT 'base_price',
  PRIMARY KEY (`plane_id`)
)
ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of plane
-- ----------------------------
INSERT INTO `plane` VALUES ('1', 'Ankara', 'Air Europe', '3', '1000');
INSERT INTO `plane` VALUES ('2', 'Ankara', 'Icarus Airlines', '2', '500');
INSERT INTO `plane` VALUES ('3', 'Bucharest', 'Air Europe', '3', '800');
INSERT INTO `plane` VALUES ('4', 'Bucharest', 'Romanian Airways', '2', '700');
INSERT INTO `plane` VALUES ('5', 'Melbourne', 'Oceanic Airlines', '4', '3000');
INSERT INTO `plane` VALUES ('6', 'Melbourne', 'Trans Pacific Airlines', '4', '2500');
INSERT INTO `plane` VALUES ('7', 'Warsaw', 'Air Europe', '3', '500');
INSERT INTO `plane` VALUES ('8', 'Warsaw', 'Uneasy Jet', '2', '600');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `email` varchar(40) NOT NULL DEFAULT '' COMMENT 'Email_address',
  `destination` varchar(30) NOT NULL DEFAULT '' COMMENT 'Destination',
  `airline` varchar(20) NOT NULL COMMENT 'Airline',
  `price` float(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Price',
  `order_price` float(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Real_price'
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;
