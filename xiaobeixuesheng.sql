/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : xiaobeixuesheng

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 18/02/2021 17:48:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for hidove_log
-- ----------------------------
DROP TABLE IF EXISTS `hidove_log`;
CREATE TABLE `hidove_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `coordinates` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `healthState` int(11) NOT NULL,
  `dangerousRegion` int(11) NOT NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `create_time` int(11) NOT NULL,
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hidove_log
-- ----------------------------

-- ----------------------------
-- Table structure for hidove_user
-- ----------------------------
DROP TABLE IF EXISTS `hidove_user`;
CREATE TABLE `hidove_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `coordinates` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `healthState` int(11) NOT NULL,
  `dangerousRegion` int(11) NOT NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `create_time` int(11) NULL DEFAULT NULL,
  `run_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hidove_user
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
