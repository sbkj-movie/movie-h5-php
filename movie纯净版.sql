-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2019-03-18 17:55:40
-- 服务器版本： 5.5.56-log
-- PHP 版本： 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 数据库： `0571_shipin`
--

--
-- 表的结构 `grade_account`
--

CREATE TABLE `grade_account` (
  `ID` int(16) UNSIGNED ZEROFILL NOT NULL,
  `USER_ID` int(16) DEFAULT NULL COMMENT '总代理的id',
  `GZ_NAME` varchar(16) DEFAULT NULL COMMENT '公众号名称',
  `APP_ID` varchar(32) DEFAULT NULL COMMENT '公众账号appid',
  `APP_SECRECT` varchar(256) DEFAULT NULL COMMENT '密钥',
  `API_KEY` varchar(256) DEFAULT NULL,
  `MCH_ID` varchar(256) DEFAULT NULL COMMENT '商户号',
  `STATUS` int(4) UNSIGNED ZEROFILL DEFAULT '0000' COMMENT '总代理设置的商户信息，0：失效 1 ：正常使用',
  `IS_DEL` int(4) UNSIGNED ZEROFILL DEFAULT '0000' COMMENT '是否删除 0：未删除 1：已删除',
  `GMT_CREATE` datetime DEFAULT NULL,
  `GMT_MODIFIED` datetime DEFAULT NULL,
  `FILE_PATH` text COMMENT '文件路径'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='打款商户配置、公众号配置' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `grade_mch_pay`
--

CREATE TABLE `grade_mch_pay` (
  `ID` int(16) UNSIGNED ZEROFILL NOT NULL COMMENT '主键id',
  `ORDER_NUM` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '订单号',
  `USER_ID` int(16) DEFAULT NULL COMMENT '用户id',
  `WX_USER_NAME` varchar(16) DEFAULT NULL COMMENT '微信用户昵称',
  `PARENT_CODE` varchar(32) DEFAULT NULL COMMENT '所属上级用户的邀请码',
  `GENERAL_AGENT_CODE` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '总代理推广码',
  `PAY_PRICE` decimal(12,4) DEFAULT NULL COMMENT '转账金额',
  `PAY_MSG` varchar(128) CHARACTER SET utf8 DEFAULT NULL COMMENT '转账信息',
  `PAY_STATUS` int(4) DEFAULT NULL COMMENT '转账状态',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '订单状态 0：正常显示 1：已删除',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFIED` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `grade_run_percent`
--

CREATE TABLE `grade_run_percent` (
  `ID` int(16) UNSIGNED ZEROFILL NOT NULL COMMENT '主键id',
  `USER_ID` int(16) DEFAULT NULL COMMENT '总代理用户id',
  `EXTENSION_CODE` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '总代理邀请码',
  `RUN_LEVEL` int(4) DEFAULT NULL COMMENT '利润级数',
  `RUN_PERCENT` varchar(256) DEFAULT NULL COMMENT '利润百分比',
  `IS_DEL` int(4) UNSIGNED ZEROFILL DEFAULT '0000' COMMENT '是否删除',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFIED` datetime DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `grade_wx_public_num`
--

CREATE TABLE `grade_wx_public_num` (
  `ID` int(16) UNSIGNED ZEROFILL NOT NULL,
  `USER_ID` int(16) DEFAULT NULL COMMENT '用户id',
  `OPEN_ID` varchar(64) DEFAULT NULL COMMENT '授权后此用户对应公众号的唯一标识',
  `APP_ID` varchar(32) DEFAULT NULL COMMENT '公众号appid',
  `WX_USER_NAME` varchar(128) DEFAULT NULL COMMENT '授权成功微信返回的昵称',
  `WX_HEAD_IMG` text COMMENT '微信返回的头像信息',
  `GA_ID` int(16) DEFAULT NULL COMMENT '商户信息表主键id',
  `IS_DEL` int(4) DEFAULT NULL COMMENT '是否删除 0：未删除 1：已删除',
  `GMT_CREATE` datetime DEFAULT NULL,
  `GMT_MODIFIED` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户授权过得公众号表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `mage_admin_user`
--

CREATE TABLE `mage_admin_user` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `AU_ACCOUNT` varchar(16) DEFAULT NULL COMMENT '管理员账户',
  `AU_PSWD` char(32) DEFAULT NULL COMMENT '管理员密码',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `AU_PERMISSION` varchar(30) NOT NULL COMMENT '权限',
  `GMT_MODIFIED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='后台管理员表';

--
-- 转存表中的数据 `mage_admin_user`
--

INSERT INTO `mage_admin_user` (`ID`, `AU_ACCOUNT`, `AU_PSWD`, `GMT_CREATE`, `AU_PERMISSION`, `GMT_MODIFIED`, `IS_DEL`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', NULL, 'look,edit,del', NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `mage_app`
--

CREATE TABLE `mage_app` (
  `ID` int(5) NOT NULL,
  `APP_TYPE` int(3) NOT NULL DEFAULT '1' COMMENT '1:苹果，2：安卓',
  `APP_NUM` varchar(30) NOT NULL COMMENT '版本号',
  `APP_HREF` varchar(255) DEFAULT NULL COMMENT '下载地址',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `IS_DEL` int(3) NOT NULL DEFAULT '0' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- 表的结构 `mage_custom_service`
--

CREATE TABLE `mage_custom_service` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `CS_TYPE` varchar(16) DEFAULT NULL COMMENT '客服方式 微信/QQ/电话',
  `CS_NUMBER` varchar(20) DEFAULT NULL COMMENT '客服号码',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFIED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客服信息表';

--
-- 转存表中的数据 `mage_custom_service`
--

INSERT INTO `mage_custom_service` (`ID`, `CS_TYPE`, `CS_NUMBER`, `GMT_CREATE`, `GMT_MODIFIED`, `IS_DEL`) VALUES
(1, '微信', '111', '2018-11-22 17:39:24', NULL, 0),
(2, 'QQ', '594654554', '2018-11-29 16:28:01', NULL, 0),
(3, '电话', '1879415414', '2018-11-29 16:28:12', NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `mage_hupijiao`
--

CREATE TABLE `mage_hupijiao` (
  `ID` INT(16) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `HU_NAME` VARCHAR(50) NULL DEFAULT NULL COMMENT '支付通道名称',
  `HU_APPID` VARCHAR(64) NULL DEFAULT NULL COMMENT '虎皮椒appid',
  `HU_APP_SECRET` TEXT NULL COMMENT '虎皮椒appSecret',
  `HU_APP_GONG` TEXT NULL COMMENT '公钥',
  `MAX_MONEY` DECIMAL(10,2) NOT NULL COMMENT '最高限额',
  `NOW_MONEY` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '当前金额',
  `HU_TYPE` INT(3) NOT NULL DEFAULT '1' COMMENT '1:宝付支付，2：青苹果支付',
  `GMT_CREATE` DATETIME NULL DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFIED` DATETIME NULL DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` INT(4) NULL DEFAULT '0' COMMENT '是否删除',
  `SORT_NUM` INT(4) NULL DEFAULT NULL COMMENT '排列序号'
) ENGINE=MYISAM DEFAULT CHARSET=utf8 COMMENT='虎皮椒对应key、value';

-- --------------------------------------------------------

--
-- 表的结构 `mage_log`
--

CREATE TABLE `mage_log` (
  `ID` int(11) NOT NULL,
  `LOG_TYPE` int(3) DEFAULT '0' COMMENT '0:login，1:add,2:edit,3:del,4:状态',
  `LOG_UID` int(11) DEFAULT NULL COMMENT '用户id',
  `LOG_NAME` varchar(30) DEFAULT NULL COMMENT '用户姓名',
  `LOG_DESC` varchar(255) DEFAULT NULL COMMENT '详情',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '操作时间',
  `IS_DEL` int(3) DEFAULT '0' COMMENT '是否删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- 表的结构 `mage_look_url`
--

CREATE TABLE `mage_look_url` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `LU_NAME` varchar(16) DEFAULT NULL COMMENT '地址名称',
  `LU_REALM` varchar(128) DEFAULT NULL COMMENT '地址域名',
  `LU_OTHER` varchar(128) NOT NULL COMMENT '地址其他参数',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='请求地址表';


-- --------------------------------------------------------

--
-- 表的结构 `mage_permission_type`
--

CREATE TABLE `mage_permission_type` (
  `ID` int(16) NOT NULL COMMENT 'id',
  `PT_KEY` varchar(16) DEFAULT NULL COMMENT '权限key:编辑',
  `PT_VALUE` varchar(16) DEFAULT NULL COMMENT '权限value：edit',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFIED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限表';

-- --------------------------------------------------------

--
-- 表的结构 `mage_shop`
--

CREATE TABLE `mage_shop` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `SP_NAME` varchar(16) DEFAULT NULL COMMENT '套餐名称',
  `SP_PRICE` decimal(8,2) DEFAULT NULL COMMENT '套餐价格',
  `SP_END_TIME` int(5) UNSIGNED DEFAULT '0' COMMENT '有效期(日，月)',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='套餐表';



-- --------------------------------------------------------

--
-- 表的结构 `mv_bank_card`
--

CREATE TABLE `mv_bank_card` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `BC_USERID` int(16) DEFAULT NULL COMMENT '用户id',
  `BC_NUMBER` varchar(64) DEFAULT NULL COMMENT '银行卡号',
  `BC_NAME` varchar(32) DEFAULT NULL COMMENT '持卡人姓名',
  `BC_TELNO` varchar(32) DEFAULT NULL COMMENT '持卡人手机号',
  `BC_OPEN_BANK` varchar(32) DEFAULT NULL COMMENT '开户行',
  `BC_OPEN_BANK_ADDRESS` varchar(128) DEFAULT NULL COMMENT '开户行地址',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='银行卡管理表';


-- --------------------------------------------------------

--
-- 表的结构 `mv_banner`
--

CREATE TABLE `mv_banner` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `BN_NAME` varchar(16) DEFAULT NULL COMMENT '轮播图名称',
  `BN_URL` text COMMENT '轮播图地址',
  `BN_TYPE` varchar(16) DEFAULT NULL COMMENT '类型 视频/漫画',
  `BN_TYPE_ID` int(16) DEFAULT NULL COMMENT '对应视频表/漫画表id',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='轮播图表';

-- --------------------------------------------------------

--
-- 表的结构 `mv_cartoon`
--

CREATE TABLE `mv_cartoon` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `CT_NAME` varchar(16) DEFAULT NULL COMMENT '漫画名称',
  `CT_PHOTO_URL` text COMMENT '漫画封面图',
  `CT_CONTENT` varchar(512) NOT NULL COMMENT '漫画简介',
  `CT_AUTHOR` varchar(64) NOT NULL COMMENT '漫画作者',
  `CT_TYPE` varchar(64) DEFAULT NULL COMMENT '漫画类型 免费/会员',
  `CT_PHYLETIC` int(5) NOT NULL DEFAULT '4' COMMENT '漫画分类',
  `CT_CATEGORY` varchar(64) NOT NULL COMMENT '漫画类别 科幻/动作',
  `CT_COUNT` int(8) DEFAULT NULL COMMENT '漫画章节数量',
  `CT_STATUS` int(4) DEFAULT '1' COMMENT '漫画上架/下架',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='漫画表';



--
-- 表的结构 `mv_cartoon_detail`
--

CREATE TABLE `mv_cartoon_detail` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `CT_ID` int(16) DEFAULT NULL COMMENT '漫画表id',
  `CTD_TITLE` varchar(128) DEFAULT NULL COMMENT '单章标题',
  `CTD_PHOTO_URL` text COMMENT '封面图',
  `CTD_TYPE` varchar(16) DEFAULT NULL COMMENT '章节类型 免费/会员',
  `CTD_PHOTO_LIST` text COMMENT '章节图片',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='漫画详情表';

-- --------------------------------------------------------

--
-- 表的结构 `mv_class`
--

CREATE TABLE `mv_class` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `CL_NAME` varchar(16) DEFAULT NULL COMMENT '类型名称',
  `CL_TYPE` int(3) NOT NULL DEFAULT '1' COMMENT '类型',
  `CL_NUM` int(5) NOT NULL DEFAULT '0' COMMENT '限制数量',
  `CL_PIC` varchar(255) DEFAULT NULL COMMENT '分类图片',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFIED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类表';

--
-- 转存表中的数据 `mv_class`
--

INSERT INTO `mv_class` (`ID`, `CL_NAME`, `CL_TYPE`, `CL_NUM`, `CL_PIC`, `GMT_CREATE`, `GMT_MODIFIED`, `IS_DEL`) VALUES
(1, '精选', 1, 0, 'Public/upload/2019-01-07/2019_01_07_15_39_56_75273.jpeg', '2018-11-21 17:41:07', '2019-01-07 15:39:58', 0),
(2, '视频', 1, 20, NULL, '2018-11-22 09:58:49', '2019-01-24 16:26:54', 0),
(3, '影片', 1, 1, NULL, '2018-11-22 12:24:59', '2019-01-24 16:28:50', 0),
(4, '漫画', 2, 4, NULL, NULL, '2019-01-24 15:52:09', 0);

--
-- 表的结构 `mv_comment`
--

CREATE TABLE `mv_comment` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `CM_TYPE` varchar(32) DEFAULT NULL COMMENT '评论类型 漫画/视频',
  `CM_TYPE_ID` int(16) DEFAULT NULL COMMENT '评论内容对应id',
  `CM_USER_ID` int(16) DEFAULT NULL COMMENT '用户id',
  `CM_USERNAME` varchar(32) DEFAULT NULL COMMENT '用户名',
  `CM_HEADIMG` text COMMENT '用户头像',
  `CM_STAR` int(8) DEFAULT NULL COMMENT '评论星',
  `CM_CONTENT` text COMMENT '评论内容',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表';


-- --------------------------------------------------------

--
-- 表的结构 `mv_look_history`
--

CREATE TABLE `mv_look_history` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `LH_USERID` int(16) DEFAULT NULL COMMENT '用户id',
  `LH_TYPE` varchar(16) DEFAULT NULL COMMENT '观看类型 免费/付费',
  `LH_PHYLETIC` varchar(16) DEFAULT NULL COMMENT '观看类别 视频/漫画',
  `LH_CLASS` int(5) DEFAULT '0' COMMENT '分类id',
  `LH_LOOKID` int(16) DEFAULT NULL COMMENT '观看id',
  `TYPE` int(3) DEFAULT '0' COMMENT '1:观看，2：收藏',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='观看记录表';


--
-- 表的结构 `mv_movies`
--

CREATE TABLE `mv_movies` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `MV_NAME` varchar(2048) DEFAULT NULL COMMENT '视频名称',
  `MV_PHOTO_URL` text COMMENT '视频封面图',
  `MV_HURL` varchar(2048) DEFAULT NULL COMMENT '国内视频地址',
  `MV_WURL` varchar(2048) DEFAULT NULL COMMENT '国外视频地址',
  `MV_SHURL` varchar(2048) DEFAULT NULL COMMENT '国内视频试看地址',
  `MV_WHURL` varchar(2048) DEFAULT NULL COMMENT '国外视频试看地址',
  `MV_TIME` varchar(16) NOT NULL COMMENT '播放时长',
  `MV_PLAY_COUNT` varchar(16) NOT NULL COMMENT '播放次数',
  `MV_TYPE` varchar(16) NOT NULL COMMENT '视频类型 免费/会员',
  `MV_PHYLETIC` varchar(64) DEFAULT NULL COMMENT '视频种类 短视频/电影',
  `MV_CONTENT` varchar(2048) DEFAULT NULL COMMENT '视频简介',
  `MV_CATEGORY` varchar(64) NOT NULL COMMENT '视频类别 科幻/动作',
  `MV_STATUS` int(4) DEFAULT '1' COMMENT '视频上架/下架',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='视频表';



--
-- 表的结构 `mv_my_collection`
--

CREATE TABLE `mv_my_collection` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `MC_USERID` int(16) DEFAULT NULL COMMENT '用户id',
  `MC_TYPE` varchar(16) DEFAULT NULL COMMENT '收藏类型 免费/付费',
  `MC_PHYLETIC` varchar(16) DEFAULT NULL COMMENT '收藏类别 视频/漫画',
  `MC_ID` int(16) DEFAULT NULL COMMENT '收藏id',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='我的收藏表';

-- --------------------------------------------------------

--
-- 表的结构 `mv_put_forward`
--

CREATE TABLE `mv_put_forward` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `PF_USER_ID` int(16) DEFAULT NULL COMMENT '用户id',
  `PF_PRICE` decimal(8,0) DEFAULT NULL COMMENT '提现金额',
  `PF_BANK_CARD_ID` int(16) DEFAULT NULL COMMENT '提现银行卡表id',
  `PF_STATUS` int(4) DEFAULT '0' COMMENT '审核状态 未审核0/通过1/拒绝2',
  `PF_COMMENT` varchar(255) DEFAULT NULL COMMENT '备注',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='提现表';



--
-- 表的结构 `mv_setall`
--

CREATE TABLE `mv_setall` (
  `ID` int(11) NOT NULL,
  `TYPE` varchar(30) DEFAULT NULL COMMENT '名称',
  `VALUE` varchar(255) DEFAULT NULL COMMENT '值',
  `DESC` varchar(55) DEFAULT NULL COMMENT '中文名称',
  `CONTENT` text COMMENT '内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 表的结构 `mv_shop_history`
--

CREATE TABLE `mv_shop_history` (
  `ID` int(16) NOT NULL COMMENT 'id',
  `SH_ORDER` varchar(50) NOT NULL COMMENT '订单号',
  `SH_USERID` int(16) DEFAULT NULL COMMENT '用户id',
  `HPID` int(16) NOT NULL COMMENT '虎皮椒账号id',
  `SHID` int(16) DEFAULT NULL COMMENT '套餐id',
  `SH_NAME` varchar(16) DEFAULT NULL COMMENT '套餐名称',
  `SH_PRICE` decimal(8,2) DEFAULT NULL COMMENT '套餐金额',
  `SH_PAY` decimal(8,2) DEFAULT NULL COMMENT '付款金额',
  `IS_PAY` int(3) NOT NULL DEFAULT '0' COMMENT '是否支付',
  `SH_END` datetime DEFAULT NULL COMMENT '套餐到期时间',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购买记录表';

--
-- 表的结构 `mv_user`
--

CREATE TABLE `mv_user` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `USERNAME` varchar(16) DEFAULT NULL COMMENT '用户名',
  `TELNO` int(16) DEFAULT NULL COMMENT '电话',
  `USERIMG` text COMMENT '用户头像',
  `EXTENSION_CODE` varchar(32) DEFAULT NULL COMMENT '推广码',
  `PARENT_CODE` varchar(32) DEFAULT NULL COMMENT '所属上级推广码',
  `FACTION` varchar(32) DEFAULT NULL COMMENT '下级分销比例',
  `SECURITY_QUESTION` text COMMENT '密保问题',
  `TRUENAME` varchar(16) NOT NULL COMMENT '真实姓名',
  `LOGIN_PSWD` varchar(32) DEFAULT NULL COMMENT '登录密码',
  `PAY_PSWD` varchar(32) DEFAULT NULL COMMENT '交易密码',
  `NO_PRICE` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '未体现金额',
  `IS_PRICE` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '已提现金额',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '创建时间',
  `GMT_MODIFIED` datetime DEFAULT NULL COMMENT '修改时间',
  `USER_TYPE` int(3) DEFAULT '0' COMMENT '0:自己注册，1：后台添加',
  `TOKEN` varchar(32) DEFAULT NULL COMMENT '登录token',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除',
  `USER_STATUS` int(11) NOT NULL DEFAULT '0' COMMENT '是不是总代理0：不是 1：是',
  `SINGLE_PERCENT` decimal(4,0) DEFAULT '0' COMMENT '设置为总代理时设置的分润比例'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 表的结构 `mv_user_profit`
--

CREATE TABLE `mv_user_profit` (
  `ID` int(16) NOT NULL COMMENT '主键id',
  `UP_USERID` int(16) DEFAULT NULL COMMENT '充值用户id',
  `UP_RUN_USERID` int(16) DEFAULT NULL COMMENT '获得分润用户id',
  `UP_PRICE` decimal(8,2) DEFAULT NULL COMMENT '分润金额',
  `GMT_CREATE` datetime DEFAULT NULL COMMENT '充值时间',
  `GMT_MODIFED` datetime DEFAULT NULL COMMENT '修改时间',
  `IS_DEL` int(4) DEFAULT '0' COMMENT '是否删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分润表';


--
-- 转储表的索引
--

--
-- 表的索引 `grade_account`
--
ALTER TABLE `grade_account`
  ADD PRIMARY KEY (`ID`) USING BTREE,
  ADD KEY `grade_account_USER_ID_STATUS_IS_DEL_index` (`USER_ID`,`STATUS`,`IS_DEL`) USING BTREE;

--
-- 表的索引 `grade_mch_pay`
--
ALTER TABLE `grade_mch_pay`
  ADD PRIMARY KEY (`ID`) USING BTREE;

--
-- 表的索引 `grade_run_percent`
--
ALTER TABLE `grade_run_percent`
  ADD PRIMARY KEY (`ID`) USING BTREE;

--
-- 表的索引 `grade_wx_public_num`
--
ALTER TABLE `grade_wx_public_num`
  ADD PRIMARY KEY (`ID`) USING BTREE;

--
-- 表的索引 `mage_admin_user`
--
ALTER TABLE `mage_admin_user`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mage_app`
--
ALTER TABLE `mage_app`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mage_custom_service`
--
ALTER TABLE `mage_custom_service`
  ADD PRIMARY KEY (`ID`);


--
-- 表的索引 `mage_hupijiao`
--
ALTER TABLE `mage_hupijiao`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mage_log`
--
ALTER TABLE `mage_log`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mage_look_url`
--
ALTER TABLE `mage_look_url`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mage_permission_type`
--
ALTER TABLE `mage_permission_type`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mage_shop`
--
ALTER TABLE `mage_shop`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_bank_card`
--
ALTER TABLE `mv_bank_card`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_banner`
--
ALTER TABLE `mv_banner`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_cartoon`
--
ALTER TABLE `mv_cartoon`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_cartoon_detail`
--
ALTER TABLE `mv_cartoon_detail`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_class`
--
ALTER TABLE `mv_class`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_comment`
--
ALTER TABLE `mv_comment`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_look_history`
--
ALTER TABLE `mv_look_history`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_movies`
--
ALTER TABLE `mv_movies`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_my_collection`
--
ALTER TABLE `mv_my_collection`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_put_forward`
--
ALTER TABLE `mv_put_forward`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_setall`
--
ALTER TABLE `mv_setall`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_shop_history`
--
ALTER TABLE `mv_shop_history`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_user`
--
ALTER TABLE `mv_user`
  ADD PRIMARY KEY (`ID`);

--
-- 表的索引 `mv_user_profit`
--
ALTER TABLE `mv_user_profit`
  ADD PRIMARY KEY (`ID`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `grade_account`
--
ALTER TABLE `grade_account`
  MODIFY `ID` int(16) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用表AUTO_INCREMENT `grade_mch_pay`
--
ALTER TABLE `grade_mch_pay`
  MODIFY `ID` int(16) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT COMMENT '主键id';

--
-- 使用表AUTO_INCREMENT `grade_run_percent`
--
ALTER TABLE `grade_run_percent`
  MODIFY `ID` int(16) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT COMMENT '主键id';

--
-- 使用表AUTO_INCREMENT `grade_wx_public_num`
--
ALTER TABLE `grade_wx_public_num`
  MODIFY `ID` int(16) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `mage_admin_user`
--
ALTER TABLE `mage_admin_user`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `mage_app`
--
ALTER TABLE `mage_app`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `mage_custom_service`
--
ALTER TABLE `mage_custom_service`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `mage_hupijiao`
--
ALTER TABLE `mage_hupijiao`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `mage_log`
--
ALTER TABLE `mage_log`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- 使用表AUTO_INCREMENT `mage_look_url`
--
ALTER TABLE `mage_look_url`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `mage_permission_type`
--
ALTER TABLE `mage_permission_type`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- 使用表AUTO_INCREMENT `mage_shop`
--
ALTER TABLE `mage_shop`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `mv_bank_card`
--
ALTER TABLE `mv_bank_card`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=35;

--
-- 使用表AUTO_INCREMENT `mv_banner`
--
ALTER TABLE `mv_banner`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=32;

--
-- 使用表AUTO_INCREMENT `mv_cartoon`
--
ALTER TABLE `mv_cartoon`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `mv_cartoon_detail`
--
ALTER TABLE `mv_cartoon_detail`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=29;

--
-- 使用表AUTO_INCREMENT `mv_class`
--
ALTER TABLE `mv_class`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=15;

--
-- 使用表AUTO_INCREMENT `mv_comment`
--
ALTER TABLE `mv_comment`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=65;

--
-- 使用表AUTO_INCREMENT `mv_look_history`
--
ALTER TABLE `mv_look_history`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=227;

--
-- 使用表AUTO_INCREMENT `mv_movies`
--
ALTER TABLE `mv_movies`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=55;

--
-- 使用表AUTO_INCREMENT `mv_my_collection`
--
ALTER TABLE `mv_my_collection`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id';

--
-- 使用表AUTO_INCREMENT `mv_put_forward`
--
ALTER TABLE `mv_put_forward`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `mv_setall`
--
ALTER TABLE `mv_setall`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `mv_shop_history`
--
ALTER TABLE `mv_shop_history`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=185;

--
-- 使用表AUTO_INCREMENT `mv_user`
--
ALTER TABLE `mv_user`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=72;

--
-- 使用表AUTO_INCREMENT `mv_user_profit`
--
ALTER TABLE `mv_user_profit`
  MODIFY `ID` int(16) NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=5;
COMMIT;

ALTER TABLE `mv_banner`
MODIFY COLUMN `BN_NAME`  varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '轮播图名称' AFTER `ID`;



