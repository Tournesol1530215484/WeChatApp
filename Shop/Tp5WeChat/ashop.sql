/*
Navicat MySQL Data Transfer

Source Server         : apitest
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : ashop

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-04-17 17:20:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wx_banner
-- ----------------------------
DROP TABLE IF EXISTS `wx_banner`;
CREATE TABLE `wx_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '标题',
  `images` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '图片连接地址',
  `describe` text COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `scort` tinyint(4) NOT NULL COMMENT '排序',
  `link` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '外部链接',
  `attr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attr2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0商品 1专题',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wx_banner
-- ----------------------------
INSERT INTO `wx_banner` VALUES ('49', '第一张', '20190416/c35e28a578a8bbc8e3562aadb67af651.jpg', '第一张', '0', 'http://www.tp5cms.com', null, null, '0');
INSERT INTO `wx_banner` VALUES ('50', '第二张', '20190416/f88c5961551b17ad63f11e6d30c53c63.jpg', '第二张', '0', 'http://www.tp5cms.com', null, null, '0');
INSERT INTO `wx_banner` VALUES ('51', '第三张', '20190416/229b9e4ed422445aa311e044752202d3.jpg', '第三张', '0', 'http://www.tp5cms.com', null, null, '0');

-- ----------------------------
-- Table structure for wx_cate
-- ----------------------------
DROP TABLE IF EXISTS `wx_cate`;
CREATE TABLE `wx_cate` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '栏目名称',
  `cate_type` tinyint(1) DEFAULT '5' COMMENT '栏目类型 1：系统分类，2：帮助分类，3.网店帮助，4.网店信息，5.普通分类',
  `keywords` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '关键字',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `show_nav` tinyint(1) DEFAULT '1' COMMENT '是否显示在顶部,1:显示，0：不显示',
  `sort` tinyint(255) NOT NULL COMMENT '排序',
  `pid` tinyint(1) DEFAULT NULL COMMENT '上级栏目',
  `images` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '缩略图',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wx_cate
-- ----------------------------
INSERT INTO `wx_cate` VALUES ('1', '男装', '0', 'T恤', '男装', '1', '0', '0', '20190411/7c035b104bead08c10dd46ab4da25e63.jpg');
INSERT INTO `wx_cate` VALUES ('61', '家电', '0', '家电', '家电', '1', '0', '0', '20190411/f3d142f5689a2e87798af12c50afa0fe.jpg');
INSERT INTO `wx_cate` VALUES ('60', '女装', '0', '女装', '女装', '1', '0', '0', '20190411/04f8892f619e30ca9f6cef48357e055a.jpg');
INSERT INTO `wx_cate` VALUES ('63', '长袖', '1', '长袖', '长袖', '1', '0', '1', '20190411/2db3bbb7751b85a4564815da37c86a24.jpg');
INSERT INTO `wx_cate` VALUES ('64', '夹克', '1', '夹克', '夹克', '1', '0', '1', '20190411/8eeea46716a5be5cea9bde243bdeb436.jpg');
INSERT INTO `wx_cate` VALUES ('65', '卫衣', '1', '卫衣', '卫衣', '1', '0', '1', '20190411/ea5038a00a2439ea0b90c3a8ceb241e2.jpg');
INSERT INTO `wx_cate` VALUES ('66', '帽子', '1', '帽子', '帽子', '1', '0', '1', '20190411/6ca948e024769bac8036eab9c841d1ea.jpg');
INSERT INTO `wx_cate` VALUES ('67', '主机', '61', '主机', '主机', '1', '0', '61', '20190411/f0b625dd20388b8ce7bfaf3919c76fd4.jpg');
INSERT INTO `wx_cate` VALUES ('68', '办公桌', '61', '办公桌', '办公桌', '1', '0', '61', '20190411/09f4424eaecd5f8f1c92b6e580c02915.jpg');
INSERT INTO `wx_cate` VALUES ('69', '卫衣', '60', '卫衣', '卫衣', '1', '0', '60', '20190416/83d563dc0e84d202f8f445ff5284d7c6.jpg');
INSERT INTO `wx_cate` VALUES ('70', '内衣', '60', '内衣', '内衣', '1', '0', '60', '20190416/a2284810288c5613280c5d32ce2c1f0a.jpg');

-- ----------------------------
-- Table structure for wx_goods
-- ----------------------------
DROP TABLE IF EXISTS `wx_goods`;
CREATE TABLE `wx_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `goods_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品名称',
  `goods_keywords` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品关键字',
  `goods_code` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品编号',
  `og_thumb` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品主图',
  `sm_thumb` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商品小图',
  `big_thumb` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商品大图',
  `marke_price` decimal(10,2) DEFAULT NULL COMMENT '市场价格',
  `shop_price` decimal(10,2) DEFAULT NULL COMMENT '当前价格',
  `on_sale` tinyint(1) DEFAULT '0' COMMENT '是否在售,0表示未在售，1表示在售',
  `category_id` tinyint(1) DEFAULT NULL COMMENT '所属栏目',
  `brand_id` tinyint(1) DEFAULT NULL COMMENT '商品品牌',
  `type_id` tinyint(1) DEFAULT NULL COMMENT '商品类型',
  `volume` int(10) DEFAULT '0' COMMENT '销量',
  `stock` int(10) DEFAULT '0' COMMENT '库存',
  `goods_desc` text COLLATE utf8_unicode_ci NOT NULL COMMENT '商品描述',
  `goods_weight` double DEFAULT '0' COMMENT '商品重量',
  `goods_unit` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '单位',
  `goods_rec` tinyint(1) DEFAULT '1' COMMENT '表示的是商品',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`) USING BTREE COMMENT '栏目',
  KEY `brand_id` (`brand_id`) COMMENT '品牌',
  KEY `type_id` (`type_id`) COMMENT '类型'
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wx_goods
-- ----------------------------
INSERT INTO `wx_goods` VALUES ('87', '蕾丝边', '蕾丝边', '155546232663644', '20190417/8d75c56eb95115fe1bf751c02b394cd4.jpg', null, '20190417/1cb71ac4a8f755b9361a4a21954ea28a.jpg', '11.00', '11.00', '1', '69', null, '0', '0', '0', '', '1', 'Kg', '1');
INSERT INTO `wx_goods` VALUES ('86', '仙女衣', '仙女衣', '155546227964523', '20190417/20125ac20cfb6fa0abe806bef0a10f19.jpg', null, '20190417/bca1a2b47288bd363221f2a536fb50cb.jpg', '88.88', '66.00', '1', '69', null, '0', '0', '0', '', '1', 'Kg', '1');
INSERT INTO `wx_goods` VALUES ('82', '女士卫衣', '女士卫衣', '155540213923735', '20190416/0e642cc1d2d05c11e0e1004219cf6f9c.jpg', null, '20190417/bbe1fadc07bfeaca7032ebe1b17da593.jpg', '88.88', '66.00', '1', '69', null, '0', '10', '0', '', '1', 'Kg', '1');
INSERT INTO `wx_goods` VALUES ('83', '女士牛仔衣', '女士牛仔衣', '155540220455851', '20190416/2d8d44ce6da73cc730658ecfe1294682.jpg', null, '20190417/24dbc6f32c7c66b0c38669c6a8530f45.jpg', '88.88', '66.00', '1', '69', null, '0', '0', '0', '', '1', 'Kg', '1');
INSERT INTO `wx_goods` VALUES ('84', '男卫衣', '男卫衣', '155540228397867', '20190416/b482ead8445a4afc59e8c193fc1bda04.jpg', null, null, '7198.00', '666.00', '1', '65', null, '0', '0', '0', '', '1', 'Kg', '1');
INSERT INTO `wx_goods` VALUES ('85', '衬衣', '衬衣', '155540260375743', '20190416/a94f4714c460da583a2398c848590ef8.jpg', null, '20190417/5afd460f9564ebcab79a94ffde14687d.jpg', '11.00', '11.00', '1', '69', null, '0', '0', '0', '', '111', 'Kg', '1');
INSERT INTO `wx_goods` VALUES ('88', '纯红仙女', '纯红仙女', '155546248034201', '20190417/4293374a8742b3f0634da316998a6ed6.jpg', null, '20190417/b1980d59bc55776dc6401bc8de23c95c.jpg', '198.00', '188.00', '1', '69', null, '0', '0', '0', '', '1', 'Kg', '1');
INSERT INTO `wx_goods` VALUES ('89', '丁阁仕A6L家用全身按摩椅', '测试', '155548563828686', '20190417/1cc867378c95dea4f9bc2b60266c730a.jpg', null, '20190417/aceccb814ff5d046d113e94370a56643.jpg', '88.88', '188.00', '1', '69', null, '0', '10', '0', '', '111', 'Kg', '1');

-- ----------------------------
-- Table structure for wx_goodsphoto
-- ----------------------------
DROP TABLE IF EXISTS `wx_goodsphoto`;
CREATE TABLE `wx_goodsphoto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(5) NOT NULL COMMENT '商品id',
  `og_photo` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wx_goodsphoto
-- ----------------------------
INSERT INTO `wx_goodsphoto` VALUES ('47', '84', '20190416/09721ca8a402fd8dabf79f13b5f6099e.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('46', '84', '20190416/da041b7c1e41830a008e44fcaee7c793.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('45', '84', '20190416/630b6d713737f50a7ee15e480131d681.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('44', '83', '20190416/45e3d3165980fa178fc231b4452b6d30.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('38', '82', '20190416/d035ab98df796bf0426e110c9b7b5b4d.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('39', '82', '20190416/90a37be3d45e94b0124bb4b76cd2db7b.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('40', '82', '20190416/1369b85195d2cb765cb1c5325171a32a.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('41', '82', '20190416/620e191597949487e887fce21c122a03.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('42', '83', '20190416/d78bb6d41c8e6e9f8fe92235ab5053a3.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('43', '83', '20190416/856aac318c59e5e26780fdd7825b7b28.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('48', '85', '20190416/bc1b823c327ab13748e59f8c66489b63.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('49', '85', '20190416/99cbc3233ab3e8053e4ba42e1cd35645.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('50', '85', '20190416/7afce6825cbc9cea19d623de12145f19.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('51', '86', '20190417/1e4b1df0bb27b11877c534e30171ec4c.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('52', '86', '20190417/553da8a91b808b3db0529b891a66cf11.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('53', '86', '20190417/553da8a91b808b3db0529b891a66cf11.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('54', '87', '20190417/8ea1369fd800686c694ccd9397939aa8.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('55', '87', '20190417/8ea1369fd800686c694ccd9397939aa8.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('56', '87', '20190417/946e756323fe24026748051aa833dc69.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('57', '88', '20190417/b140f90f2c1b204ea8a15ff38009d5fb.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('58', '88', '20190417/83013bec94817427b6c0322d2f25333f.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('59', '88', '20190417/347e889dfb44c2728bd16f208f27fb28.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('60', '88', '20190417/cd5b19186641c30511c5abc3c02405cd.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('61', '89', '20190417/35c724bd86a12fddcaec414cca0848b5.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('62', '89', '20190417/a249467e21554e18cac173cef746ab05.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('63', '89', '20190417/dbdf57aa81a8a47b11ddee523cb3849e.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('64', '89', '20190417/c09dbd595311c02786763ec7aac8f7b4.jpg');
INSERT INTO `wx_goodsphoto` VALUES ('67', '89', '20190417/7fa604a3ec1cf46393a88dcc53f2b9a0.jpg');

-- ----------------------------
-- Table structure for wx_special
-- ----------------------------
DROP TABLE IF EXISTS `wx_special`;
CREATE TABLE `wx_special` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scort` tinyint(3) NOT NULL DEFAULT '1' COMMENT '排序',
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '标题',
  `describe` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `images` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wx_special
-- ----------------------------
INSERT INTO `wx_special` VALUES ('1', '1', '男装专题', '男装专题', '20190414/b9329316e2f5a4affb1c0b6270c0ada9.jpg');
INSERT INTO `wx_special` VALUES ('2', '1', '女装专题', '女装专题', '20190414/00e747d5276daa65019bfed8e43f7893.jpg');
INSERT INTO `wx_special` VALUES ('3', '1', '家电专题', '家电专题', '');

-- ----------------------------
-- Table structure for wx_special_res
-- ----------------------------
DROP TABLE IF EXISTS `wx_special_res`;
CREATE TABLE `wx_special_res` (
  `goodsid` int(11) NOT NULL COMMENT '商品id',
  `specialid` int(11) NOT NULL COMMENT '专题id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of wx_special_res
-- ----------------------------
INSERT INTO `wx_special_res` VALUES ('84', '1');
