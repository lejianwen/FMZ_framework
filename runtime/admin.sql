SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admins`
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nick_name` varchar(255) DEFAULT '',
  `telephone` varchar(50) DEFAULT '',
  `qq` varchar(11) DEFAULT '',
  `other` text DEFAULT NULL,
  `group_id` tinyint(2) NOT NULL COMMENT '管理员组',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES ('1', 'admin', '96e79218965eb72c92a549dd5a330112', 'OOOOO', '3333', '111111', '', '1', '2017-03-22 17:14:31', '2018-08-29 19:18:52');

-- ----------------------------
-- Table structure for `menus`
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL DEFAULT '',
  `path` varchar(50) NOT NULL DEFAULT '',
  `icon` varchar(20) NOT NULL DEFAULT '&#xe616;',
  `sort` int(10) unsigned NOT NULL DEFAULT 0,
  `status` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COMMENT='菜单表';

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES ('1', '0', '测试管理', '', '', '9', '1', '2018-02-28 12:53:21', '2018-03-01 17:26:45');
INSERT INTO `menus` VALUES ('2', '1', '测试列表', '/admin/test/index', '', '0', '1', '2018-02-28 12:54:09', '2018-02-28 21:44:43');
INSERT INTO `menus` VALUES ('3', '0', '系统管理', '', '&#xe616;', '0', '1', '2018-02-28 12:57:54', '2018-02-28 12:57:54');
INSERT INTO `menus` VALUES ('4', '3', '管理员列表', '/admin/admin/index', '', '0', '1', '2018-02-28 12:58:13', '2018-02-28 12:58:13');
INSERT INTO `menus` VALUES ('5', '3', '菜单列表', '/admin/menu/index', '', '0', '1', '2018-02-28 12:58:30', '2018-02-28 20:59:16');
