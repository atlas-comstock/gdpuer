
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `ims_stu_profile`;
CREATE TABLE `ims_stu_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `fakeid` varchar(255) NOT NULL DEFAULT '' COMMENT 'FAKEID',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT 'OPENid',
  `xh` varchar(50) NOT NULL DEFAULT '' COMMENT '学号',
  `jwcpwd` text NOT NULL DEFAULT '' COMMENT '教务处密码',
  `libpwd` text NOT NULL DEFAULT '' COMMENT '图书馆密码',
  `realname` varchar(10) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称',
  `xb` text NOT NULL DEFAULT '' COMMENT '性别',
  `csrq` text NOT NULL DEFAULT '' COMMENT '出生日期',
  `sfzh` text NOT NULL DEFAULT '' COMMENT '身份证号',
  `xymc` text NOT NULL DEFAULT '' COMMENT '学院名称',
  `zymc` text NOT NULL DEFAULT '' COMMENT '专业名称',
  `zyfx` text NOT NULL DEFAULT '' COMMENT '专业方向',
  `bjmc` text NOT NULL DEFAULT '' COMMENT '班级名称',
  `nj` text NOT NULL  DEFAULT '' COMMENT '年级',
  `syszd` text NOT NULL DEFAULT '' COMMENT '生源所在地',
  `wechat` varchar(255) NOT NULL DEFAULT '' COMMENT '微信号',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT 'QQ号',
  `avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '头像',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `shortnum` varchar(11) NOT NULL DEFAULT '' COMMENT '短号',
  `room` varchar(255) NOT NULL DEFAULT '' COMMENT '宿舍号',
  `createtime` int(10) unsigned NOT NULL COMMENT '加入时间',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;