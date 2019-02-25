/*
Navicat MySQL Data Transfer

Source Server         : chun
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : chun

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-12-19 20:56:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for address
-- ----------------------------
DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(240) NOT NULL,
  `tel` bigint(11) NOT NULL,
  `address` char(240) NOT NULL,
  `doornumber` char(120) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `lat` char(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of address
-- ----------------------------
INSERT INTO `address` VALUES ('3', '123', '15521970656', '广东省广州市天河区稚乐街8骏景花园', '132', '4', '1', '113');
INSERT INTO `address` VALUES ('4', '456', '15521970656', '广东省广州市天河区建中路58号', '456', '4', '0', '113');
INSERT INTO `address` VALUES ('5', '456', '15521970656', '广东省广州市天河区骏中街84', '111', '4', '0', '113.390735,23.128598');

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(30) NOT NULL,
  `password` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', 'admin');

-- ----------------------------
-- Table structure for cougood
-- ----------------------------
DROP TABLE IF EXISTS `cougood`;
CREATE TABLE `cougood` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '优惠券id',
  `tid` int(11) NOT NULL COMMENT '商品类型id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cougood
-- ----------------------------
INSERT INTO `cougood` VALUES ('2', '7', '1');
INSERT INTO `cougood` VALUES ('3', '12', '4');
INSERT INTO `cougood` VALUES ('4', '12', '5');
INSERT INTO `cougood` VALUES ('5', '13', '4');
INSERT INTO `cougood` VALUES ('6', '14', '6');
INSERT INTO `cougood` VALUES ('7', '14', '4');
INSERT INTO `cougood` VALUES ('8', '14', '1');
INSERT INTO `cougood` VALUES ('9', '14', '2');
INSERT INTO `cougood` VALUES ('10', '14', '3');

-- ----------------------------
-- Table structure for coupon
-- ----------------------------
DROP TABLE IF EXISTS `coupon`;
CREATE TABLE `coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(120) NOT NULL,
  `content` char(240) NOT NULL,
  `timetype` int(11) NOT NULL COMMENT '有效期类型',
  `open` char(80) NOT NULL COMMENT '有效期开始',
  `close` char(80) NOT NULL COMMENT '有效期结束',
  `type` int(11) NOT NULL COMMENT '0首单半价，1咖啡人，2满减满价，3满减满件',
  `limi` int(11) NOT NULL COMMENT '0无限制，限制领取次数',
  `number` int(11) NOT NULL,
  `link` char(240) NOT NULL,
  `extra` char(240) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0可使用,1已过期',
  `condition` int(11) NOT NULL COMMENT '满减条件',
  `moneytype` int(11) NOT NULL COMMENT '1减现2打折3减件4免运费',
  `money` int(11) NOT NULL COMMENT '减多少钱或者打折',
  `tiptype` int(11) NOT NULL COMMENT '0全部商品1部分商品',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of coupon
-- ----------------------------
INSERT INTO `coupon` VALUES ('1', '首单半价', '5折', '1', '0', '30', '0', '1', '499', 'http://127.0.0.1/chunxiaqiudongxiawucha/public/index.php/index/bargin/yhq?id=1', '', '0', '0', '0', '0', '0');
INSERT INTO `coupon` VALUES ('2', '咖啡人', '订单中价格最低的咖啡免费', '1', '0', '15', '1', '1', '499', 'http://127.0.0.1/chunxiaqiudongxiawucha/public/index.php/index/bargin/yhq?id=2', '', '0', '0', '0', '0', '0');
INSERT INTO `coupon` VALUES ('14', '满20免配送费', '满20免配送费', '2', '2017-12-14', '2017-12-28', '2', '1', '9', 'http://127.0.0.1/chunxiaqiudongxiawucha/public/index.php/index/bargin/yhq?id=14', '', '0', '20', '4', '0', '1');
INSERT INTO `coupon` VALUES ('13', '满3减1', '满3减1', '2', '2017-12-14', '2017-12-29', '3', '1', '9', 'http://127.0.0.1/chunxiaqiudongxiawucha/public/index.php/index/bargin/yhq?id=13', '', '0', '3', '3', '1', '1');
INSERT INTO `coupon` VALUES ('12', '满10减2', '满10减2', '1', '0', '30', '2', '1', '9', 'http://127.0.0.1/chunxiaqiudongxiawucha/public/index.php/index/bargin/yhq?id=12', '', '0', '10', '1', '2', '1');

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(240) NOT NULL,
  `money` double(8,2) NOT NULL,
  `newmoney` double(8,2) NOT NULL,
  `sale` int(11) NOT NULL COMMENT '销量',
  `img` char(240) NOT NULL,
  `sendmoney` double(6,2) NOT NULL COMMENT '运费,0免运费',
  `number` int(11) NOT NULL COMMENT '剩余数量',
  `type` int(11) NOT NULL COMMENT '0不限购，其余是限购数量',
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of goods
-- ----------------------------
INSERT INTO `goods` VALUES ('10', '11', '0.01', '0.00', '0', '20171219\\c55142e1981fcf095690cd6ce133aee6.jpg', '5.00', '5', '0', '1513686322');
INSERT INTO `goods` VALUES ('6', '牛奶', '0.01', '1222.00', '0', '20171201\\5ed3cdf4fb28556d543be8611edef9c1.png', '0.00', '800', '3', '1512133340');
INSERT INTO `goods` VALUES ('7', '冰激凌', '0.01', '0.00', '0', '20171201\\18fcfabd414dd93fbe3e4ce37df00222.png', '0.00', '12', '2', '1512133670');
INSERT INTO `goods` VALUES ('8', '可乐', '0.01', '0.00', '0', '20171201\\18fcfabd414dd93fbe3e4ce37df00222.png', '0.00', '12', '2', '1512133670');
INSERT INTO `goods` VALUES ('9', '饺子', '0.01', '0.00', '0', '20171201\\18fcfabd414dd93fbe3e4ce37df00222.png', '0.00', '12', '2', '1512133670');

-- ----------------------------
-- Table structure for goodstip
-- ----------------------------
DROP TABLE IF EXISTS `goodstip`;
CREATE TABLE `goodstip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL COMMENT '商品id',
  `tid` int(11) NOT NULL COMMENT '标签id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of goodstip
-- ----------------------------
INSERT INTO `goodstip` VALUES ('25', '9', '3');
INSERT INTO `goodstip` VALUES ('24', '8', '2');
INSERT INTO `goodstip` VALUES ('16', '7', '1');
INSERT INTO `goodstip` VALUES ('23', '6', '4');
INSERT INTO `goodstip` VALUES ('28', '8', '1');
INSERT INTO `goodstip` VALUES ('27', '6', '1');
INSERT INTO `goodstip` VALUES ('22', '6', '5');
INSERT INTO `goodstip` VALUES ('26', '6', '6');
INSERT INTO `goodstip` VALUES ('29', '9', '1');
INSERT INTO `goodstip` VALUES ('30', '10', '6');

-- ----------------------------
-- Table structure for mancoupon
-- ----------------------------
DROP TABLE IF EXISTS `mancoupon`;
CREATE TABLE `mancoupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` bigint(20) NOT NULL,
  `cid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0可使用,1锁定，2已过期，3已使用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mancoupon
-- ----------------------------
INSERT INTO `mancoupon` VALUES ('14', '1513252815', '14', '4', '0');
INSERT INTO `mancoupon` VALUES ('13', '1513169053', '12', '4', '0');
INSERT INTO `mancoupon` VALUES ('12', '1513169039', '13', '4', '0');
INSERT INTO `mancoupon` VALUES ('11', '1513168791', '2', '4', '0');
INSERT INTO `mancoupon` VALUES ('10', '1513168719', '1', '4', '0');

-- ----------------------------
-- Table structure for ordergoods
-- ----------------------------
DROP TABLE IF EXISTS `ordergoods`;
CREATE TABLE `ordergoods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL COMMENT '订单id',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `num` int(11) NOT NULL COMMENT '份数',
  `money` double(10,2) NOT NULL COMMENT '总金额',
  `name` char(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ordergoods
-- ----------------------------
INSERT INTO `ordergoods` VALUES ('37', '17', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('36', '17', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('35', '16', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('61', '27', '9', '1', '5.00', '饺子');
INSERT INTO `ordergoods` VALUES ('46', '20', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('45', '20', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('44', '20', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('43', '19', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('42', '19', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('41', '19', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('34', '16', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('33', '16', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('60', '27', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('59', '27', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('67', '29', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('66', '29', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('65', '29', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('68', '30', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('69', '30', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('70', '30', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('76', '32', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('75', '32', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('74', '32', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('77', '33', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('78', '33', '8', '2', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('79', '34', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('80', '34', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('81', '34', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('87', '36', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('86', '36', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('85', '36', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('88', '37', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('89', '37', '8', '2', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('90', '37', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('91', '38', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('92', '38', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('93', '38', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('94', '39', '7', '1', '5.00', '冰激凌');
INSERT INTO `ordergoods` VALUES ('95', '39', '8', '1', '5.00', '可乐');
INSERT INTO `ordergoods` VALUES ('96', '39', '6', '1', '94.00', '牛奶');
INSERT INTO `ordergoods` VALUES ('129', '62', '8', '1', '0.01', '可乐');
INSERT INTO `ordergoods` VALUES ('124', '59', '9', '1', '0.01', '饺子');
INSERT INTO `ordergoods` VALUES ('122', '57', '6', '1', '0.01', '牛奶');
INSERT INTO `ordergoods` VALUES ('128', '62', '7', '1', '0.01', '冰激凌');
INSERT INTO `ordergoods` VALUES ('131', '64', '7', '1', '0.01', '冰激凌');
INSERT INTO `ordergoods` VALUES ('132', '64', '8', '1', '0.01', '可乐');
INSERT INTO `ordergoods` VALUES ('133', '65', '7', '1', '0.01', '冰激凌');
INSERT INTO `ordergoods` VALUES ('134', '65', '8', '1', '0.01', '可乐');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordernum` char(80) NOT NULL COMMENT '订单号',
  `aid` int(11) NOT NULL COMMENT '地址id',
  `did` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券id，0就是不使用',
  `money` double(10,2) NOT NULL COMMENT '总计',
  `dismoney` double(10,2) NOT NULL DEFAULT '0.00',
  `num` int(11) NOT NULL COMMENT '份数',
  `sendtime` char(240) NOT NULL,
  `ordertime` bigint(20) NOT NULL COMMENT '下单时间',
  `sendmoney` double(5,2) NOT NULL COMMENT '运费,0免运费',
  `extra` char(240) NOT NULL DEFAULT '0' COMMENT '备注',
  `pingjia` char(240) NOT NULL COMMENT '评价',
  `status` int(11) NOT NULL COMMENT '0未支付,1制作中，2配送中，3已送达，4已评价',
  `uid` int(11) NOT NULL,
  `good` int(11) NOT NULL COMMENT '0差评，1好评',
  `print` int(11) DEFAULT NULL COMMENT '1代表需要打印',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES ('65', 'A201712192012321082', '3', '10', '0.02', '0.01', '2', '', '1513686452', '0.00', '加个shot带搅拌棒', '', '8', '4', '0', null);

-- ----------------------------
-- Table structure for sendman
-- ----------------------------
DROP TABLE IF EXISTS `sendman`;
CREATE TABLE `sendman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` char(80) NOT NULL,
  `name` char(80) NOT NULL,
  `head` char(240) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sendman
-- ----------------------------
INSERT INTO `sendman` VALUES ('1', 'o3dzNwtvtYzj94vudhpDRnwMv8QM', 'ruan', 'http://wx.qlogo.cn/mmopen/vi_32/uUz0d4QI0KygGF1ZWQIHtNz3ef89ICrja1xjngEVKzreVvGXM1LV4DuhxCaHhKQoQnYiaPe11KZKAxnA75oZNQw/0\n', '0');
INSERT INTO `sendman` VALUES ('2', 'oy0pn1jdO15-LYfLxqjSd4cChads', '小白的程序猿', 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLBTib771Bl7s65D9Ieh0fuaFTVB1ibJJYss2kDQXGW83A9laianuY4L08ic87cE0UwTEfgbGQednYQYg/0', '0');
INSERT INTO `sendman` VALUES ('3', 'oy0pn1vKmN2JSvcpUSaKg6JZYjVs', '愿星～', 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKIDicmPy1RVicCpz15PZWHzbhvQHo7OBzia03uTrQPcRpWwQPrpiaD7Q8GBD61PyyyicK19v7qByef96Q/0', '0');

-- ----------------------------
-- Table structure for tip
-- ----------------------------
DROP TABLE IF EXISTS `tip`;
CREATE TABLE `tip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(240) NOT NULL,
  `list` int(11) NOT NULL COMMENT '标签顺序，由小到大',
  `type` int(11) NOT NULL COMMENT '1显示，0禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tip
-- ----------------------------
INSERT INTO `tip` VALUES ('1', '今天下午茶', '3', '1');
INSERT INTO `tip` VALUES ('2', '推荐', '5', '1');
INSERT INTO `tip` VALUES ('3', '冰咖', '6', '1');
INSERT INTO `tip` VALUES ('4', '热咖', '2', '1');
INSERT INTO `tip` VALUES ('5', '果饮', '4', '1');
INSERT INTO `tip` VALUES ('6', '包包', '1', '1');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` char(90) NOT NULL COMMENT '用户ID',
  `openid` char(240) NOT NULL COMMENT '微信openid',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '购买次数',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0标星，1已标星',
  `img` char(240) NOT NULL,
  `name` char(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '', 'Smile.只为u,', '0', '0', 'touxiang.jpg', 'Smile.只为u,');
INSERT INTO `user` VALUES ('4', '123', '软小死', '0', '0', '04.jpg', '阮小死');
INSERT INTO `user` VALUES ('6', '1712060004', 'oy0pn1vUbr9NaYkXv-B5X6f_74bc', '0', '0', 'http://wx.qlogo.cn/mmopen/vi_32/8tRnGSSG8zExrF0niayA3adqG29oUe4Iffibfwzk7Aic0unXIXlgsNDcha66tOwwFJuNlId6zLIddz4THvhgxeHzg/0', '阮 ');
INSERT INTO `user` VALUES ('7', '1712090006', 'oy0pn1jdO15-LYfLxqjSd4cChads', '0', '0', 'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTLBTib771Bl7s65D9Ieh0fuaFTVB1ibJJYss2kDQXGW83A9laianuY4L08ic87cE0UwTEfgbGQednYQYg/0', '小白的程序猿');
