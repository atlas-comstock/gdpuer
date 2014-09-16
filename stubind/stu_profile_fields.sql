/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : we7

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2014-03-30 14:43:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ims_profile_fields`
-- ----------------------------
DROP TABLE IF EXISTS `ims_stu_profile_fields`;
CREATE TABLE `ims_stu_profile_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field` varchar(255) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `displayorder` smallint(6) NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否必填',
  `unchangeable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否不可修改',
  `showinregister` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示在注册表单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_profile_fields
-- ----------------------------

INSERT INTO `ims_stu_profile_fields` VALUES ('1', 'from_user', '1', 'openid', '', '1', '0', '0', '1');
INSERT INTO `ims_stu_profile_fields` VALUES ('2', 'xh', '1', '学号', '', '0', '0', '1', '1');
INSERT INTO `ims_stu_profile_fields` VALUES ('3', 'jwcpwd', '1', '教务处密码', '', '1', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('4', 'libpwd', '1', '图书馆密码', '', '0', '0', '0', '1');
INSERT INTO `ims_stu_profile_fields` VALUES ('5', 'realname', '1', '真实姓名', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('6', 'nickname', '1', '昵称', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('7', 'xb', '1', '性别', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('8', 'csrq', '1', '出生日期', '', '1', '0', '0', '1');
INSERT INTO `ims_stu_profile_fields` VALUES ('9', 'sfzh', '1', '身份证号', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('10', 'xymc', '1', '学院名称', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('11', 'zymc', '1', '专业名称', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('12', 'zyfx', '1', '专业方向', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('13', 'bjmc', '1', '班级名称', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('14', 'nj', '1', '年级', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('15', 'syszd', '1', '生源所在地', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('16', 'wechat', '1', '微信号', '', '0', '0', '1', '1');
INSERT INTO `ims_stu_profile_fields` VALUES ('17', 'avatar', '1', '头像', '', '1', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('18', 'qq', '1', 'QQ号', '', '0', '0', '0', '1');
INSERT INTO `ims_stu_profile_fields` VALUES ('19', 'mobile', '1', '手机号码', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('20', 'shortnum', '1', '短号', '', '0', '0', '0', '0');
INSERT INTO `ims_stu_profile_fields` VALUES ('21', 'room', '1', '宿舍号', '', '0', '0', '0', '0');