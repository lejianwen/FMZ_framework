/*
 Navicat Premium Data Transfer

 Source Server         : 59.110.162.9
 Source Server Type    : MariaDB
 Source Server Version : 100316
 Source Host           : localhost:3307
 Source Schema         : shiyou

 Target Server Type    : MariaDB
 Target Server Version : 100316
 File Encoding         : 65001

 Date: 21/05/2020 22:35:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE `admin_logs`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL,
  `uri` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `method` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `post` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `created_at` timestamp(0) NULL DEFAULT current_timestamp,
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_logs
-- ----------------------------

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `route_names` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `api_routes` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '\'[]\'',
  `created_at` timestamp(0) NULL DEFAULT current_timestamp,
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
INSERT INTO `admin_roles` VALUES (1, '超管', '*', '[\"/api/admin/user/lock\"]', '2020-01-26 09:44:55', '2020-05-07 21:59:45');
INSERT INTO `admin_roles` VALUES (2, 'test', '*', '[\"/api/admin/user/unlock\",\"/api/admin/goods/create\"]', '2020-05-07 20:58:00', '2020-05-07 20:58:00');

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `nickname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `telephone` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `qq` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `role_id` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员组',
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `token_expire_time` timestamp(0) NULL DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp,
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES (1, 'admin', '96e79218965eb72c92a549dd5a330112', 'OOOOO', '', '3333', '111111', 1, '27bc180c3de1916f7b9834792801f003', '2020-06-20 21:53:17', 1, '2017-03-22 17:14:31', '2020-05-21 21:53:17');

-- ----------------------------
-- Table structure for configs
-- ----------------------------
DROP TABLE IF EXISTS `configs`;
CREATE TABLE `configs`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `value` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NULL DEFAULT current_timestamp,
  `updated_at` timestamp(0) NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of configs
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
