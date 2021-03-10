SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE `test`.`hidove_log` ADD COLUMN `temperature` decimal(3, 1) NOT NULL DEFAULT 36.5 AFTER `user_id`;

ALTER TABLE `test`.`hidove_log` ADD COLUMN `dangerousRegionRemark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '是否去过高风险地区备注' AFTER `dangerousRegion`;

ALTER TABLE `test`.`hidove_log` ADD COLUMN `contactSituation` int(11) NOT NULL DEFAULT 2 COMMENT '近七日接触情况' AFTER `dangerousRegionRemark`;

ALTER TABLE `test`.`hidove_log` ADD COLUMN `goOut` int(11) NOT NULL DEFAULT 1 COMMENT '近七日是否有跨区域的外出' AFTER `contactSituation`;

ALTER TABLE `test`.`hidove_log` ADD COLUMN `goOutRemark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '近七日是否有跨区域的外出备注' AFTER `goOut`;

ALTER TABLE `test`.`hidove_log` ADD COLUMN `familySituation` int(11) NOT NULL DEFAULT 2 COMMENT '同住家人身体状况' AFTER `goOutRemark`;

ALTER TABLE `test`.`hidove_log` MODIFY COLUMN `healthState` int(11) NOT NULL DEFAULT 1 AFTER `location`;

ALTER TABLE `test`.`hidove_log` MODIFY COLUMN `dangerousRegion` int(11) NOT NULL DEFAULT 2 AFTER `healthState`;

ALTER TABLE `test`.`hidove_log` MODIFY COLUMN `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' AFTER `familySituation`;

ALTER TABLE `test`.`hidove_user` ADD COLUMN `temperature` decimal(3, 1) NOT NULL DEFAULT 36.5 AFTER `password`;

ALTER TABLE `test`.`hidove_user` ADD COLUMN `dangerousRegionRemark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '是否去过高风险地区备注' AFTER `dangerousRegion`;

ALTER TABLE `test`.`hidove_user` ADD COLUMN `contactSituation` int(11) NOT NULL DEFAULT 2 COMMENT '近七日接触情况' AFTER `dangerousRegionRemark`;

ALTER TABLE `test`.`hidove_user` ADD COLUMN `goOut` int(11) NOT NULL DEFAULT 1 COMMENT '近七日是否有跨区域的外出' AFTER `contactSituation`;

ALTER TABLE `test`.`hidove_user` ADD COLUMN `goOutRemark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '近七日是否有跨区域的外出备注' AFTER `goOut`;

ALTER TABLE `test`.`hidove_user` ADD COLUMN `familySituation` int(11) NOT NULL DEFAULT 2 COMMENT '同住家人身体状况' AFTER `goOutRemark`;

ALTER TABLE `test`.`hidove_user` ADD COLUMN `status` int(11) NOT NULL DEFAULT 1 COMMENT '是否启动自动任务' AFTER `remark`;

ALTER TABLE `test`.`hidove_user` ADD COLUMN `send_time` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '{\"hour\":0,\"minute\":0}' AFTER `status`;

ALTER TABLE `test`.`hidove_user` MODIFY COLUMN `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名' AFTER `id`;

ALTER TABLE `test`.`hidove_user` MODIFY COLUMN `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '密码' AFTER `username`;

ALTER TABLE `test`.`hidove_user` MODIFY COLUMN `coordinates` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '地区' AFTER `temperature`;

ALTER TABLE `test`.`hidove_user` MODIFY COLUMN `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '经纬度' AFTER `coordinates`;

ALTER TABLE `test`.`hidove_user` MODIFY COLUMN `healthState` int(11) NOT NULL DEFAULT 1 COMMENT '健康状态' AFTER `location`;

ALTER TABLE `test`.`hidove_user` MODIFY COLUMN `dangerousRegion` int(11) NOT NULL DEFAULT 2 COMMENT '是否去过高风险地区' AFTER `healthState`;

ALTER TABLE `test`.`hidove_user` MODIFY COLUMN `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '备注' AFTER `familySituation`;

SET FOREIGN_KEY_CHECKS=1;