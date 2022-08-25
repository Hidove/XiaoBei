/*
 Navicat Premium Data Transfer

 Source Server         : 42.193.161.4
 Source Server Type    : MySQL
 Source Server Version : 50734
 Source Host           : 42.193.161.4:3306
 Source Schema         : xiaobei_com

 Target Server Type    : MySQL
 Target Server Version : 50734
 File Encoding         : 65001

 Date: 25/08/2022 15:27:16
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
  `temperature` decimal(3, 1) NOT NULL DEFAULT 36.5,
  `coordinates` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `healthState` int(11) NOT NULL DEFAULT 1,
  `dangerousRegion` int(11) NOT NULL DEFAULT 2,
  `dangerousRegionRemark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '是否去过高风险地区备注',
  `contactSituation` int(11) NOT NULL DEFAULT 2 COMMENT '近七日接触情况',
  `goOut` int(11) NOT NULL DEFAULT 1 COMMENT '近七日是否有跨区域的外出',
  `goOutRemark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '近七日是否有跨区域的外出备注',
  `familySituation` int(11) NOT NULL DEFAULT 2 COMMENT '同住家人身体状况',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL,
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1076348 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for hidove_user
-- ----------------------------
DROP TABLE IF EXISTS `hidove_user`;
CREATE TABLE `hidove_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `temperature` decimal(3, 1) NOT NULL DEFAULT 36.5,
  `coordinates` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `healthState` int(11) NOT NULL,
  `dangerousRegion` int(11) NOT NULL,
  `dangerousRegionRemark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '是否去过高风险地区备注',
  `contactSituation` int(11) NOT NULL DEFAULT 2 COMMENT '近七日接触情况',
  `goOut` int(11) NOT NULL DEFAULT 1 COMMENT '近七日是否有跨区域的外出',
  `goOutRemark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '近七日是否有跨区域的外出备注',
  `familySituation` int(11) NOT NULL DEFAULT 2 COMMENT '同住家人身体状况',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT 1,
  `send_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '{\"hour\":0,\"minute\":0}',
  `notification` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '{\"status\":0}',
  `create_time` int(11) NOT NULL,
  `run_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1583 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
