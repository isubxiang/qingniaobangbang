

CREATE TABLE IF NOT EXISTS `tu_ad` (
  `ad_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL COMMENT '学校ID',
  `site_id` int(11) DEFAULT '0',
  `city_id` int(11) unsigned DEFAULT '0',
  `user_id` int(11) DEFAULT NULL COMMENT '购买人ID',
  `title` varchar(64) DEFAULT NULL,
  `link_url` varchar(128) DEFAULT NULL,
  `photo` varchar(128) DEFAULT NULL,
  `code` varchar(1024) DEFAULT NULL,
  `prestore_integral` int(11) DEFAULT '0' COMMENT '预存积分',
  `is_wxapp` tinyint(1) DEFAULT '0',
  `state` tinyint(1) DEFAULT '0',
  `src` varchar(64) DEFAULT NULL,
  `wb_src` varchar(128) DEFAULT NULL,
  `xcx_name` varchar(32) DEFAULT NULL,
  `appid` varchar(32) DEFAULT NULL,
  `click` int(11) NOT NULL DEFAULT '0',
  `is_target` tinyint(1) NOT NULL DEFAULT '0',
  `bg_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reset_time` int(11) DEFAULT NULL COMMENT '点击量更新时间',
  `closed` tinyint(1) DEFAULT '0',
  `orderby` tinyint(3) DEFAULT '100',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=237 ;

--
-- 转存表中的数据 `tu_ad`
--

INSERT INTO `tu_ad` (`ad_id`, `school_id`, `site_id`, `city_id`, `user_id`, `title`, `link_url`, `photo`, `code`, `prestore_integral`, `is_wxapp`, `state`, `src`, `wb_src`, `xcx_name`, `appid`, `click`, `is_target`, `bg_date`, `end_date`, `reset_time`, `closed`, `orderby`) VALUES
(235, 1, 85, 0, NULL, '小程序首页banner570*27012', '', '/attachs/2018/12/11/5c0fc26151ee5.jpg', '', 0, 0, 1, '/pages/shop/_/index?type=3', '', '', '', 0, 0, '2018-12-06', '2021-12-06', NULL, 0, 2),
(236, 1, 85, 0, NULL, '订单', '', '/attachs/2019/05/13/5cd8ae94511f5.jpg', '', 0, 0, 1, '/pages/shop/_/index?id=1', '', '', '', 0, 0, '2018-01-14', '2020-04-20', NULL, 0, 100);

-- --------------------------------------------------------

--
-- 表的结构 `tu_admin`
--

CREATE TABLE IF NOT EXISTS `tu_admin` (
  `admin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT NULL COMMENT '管理员类型',
  `user_id` int(11) DEFAULT NULL COMMENT '绑定会员ID',
  `username` varchar(32) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL COMMENT '区域管理',
  `business_id` int(11) DEFAULT NULL COMMENT '商圈管理',
  `mobile` varchar(11) DEFAULT NULL,
  `lock_admin_mum` int(11) DEFAULT '0',
  `is_admin_lock` tinyint(1) DEFAULT '0',
  `is_admin_lock_time` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `last_time` int(11) DEFAULT NULL,
  `last_ip` varchar(20) DEFAULT NULL,
  `is_ip` tinyint(1) NOT NULL DEFAULT '0',
  `is_username_lock` tinyint(1) NOT NULL DEFAULT '0',
  `closed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

--
-- 转存表中的数据 `tu_admin`
--

INSERT INTO `tu_admin` (`admin_id`, `type`, `user_id`, `username`, `password`, `school_id`, `role_id`, `city_id`, `area_id`, `business_id`, `mobile`, `lock_admin_mum`, `is_admin_lock`, `is_admin_lock_time`, `create_time`, `create_ip`, `last_time`, `last_ip`, `is_ip`, `is_username_lock`, `closed`) VALUES
(1, 1, 5, 'admin', '21232f297a57a5a743894a0e4a801fc3', 0, 1, 0, 0, 0, '15000000000', 0, 0, 0, 1441880203, '127.0.0.1', 1559550959, '122.226.145.42', 0, 0, 0),
(83, 2, 0, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 1, 3, 58, 0, 0, '17051187989', 0, 0, 0, 1553013226, '223.150.229.212', 1551541867, '122.226.145.42', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `tu_admin_action_logs`
--

CREATE TABLE IF NOT EXISTS `tu_admin_action_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) DEFAULT NULL COMMENT '城市',
  `type` tinyint(1) DEFAULT NULL COMMENT '类型',
  `admin_id` int(11) DEFAULT NULL COMMENT '操作员iD',
  `intro` varchar(256) DEFAULT NULL COMMENT '操作日志',
  `create_time` varchar(32) DEFAULT NULL,
  `create_ip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_admin_ip_auth`
--

CREATE TABLE IF NOT EXISTS `tu_admin_ip_auth` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `start` varchar(32) DEFAULT NULL,
  `end` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `tu_admin_ip_auth`
--

INSERT INTO `tu_admin_ip_auth` (`id`, `start`, `end`) VALUES
(1, '1.86.55.15', '1.86.55.288'),
(2, '1.85.150.228', '1.85.155.228');

-- --------------------------------------------------------

--
-- 表的结构 `tu_admin_log`
--

CREATE TABLE IF NOT EXISTS `tu_admin_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` int(1) DEFAULT '1' COMMENT '1会员2管理员',
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `last_time` int(10) DEFAULT NULL,
  `last_ip` varchar(20) DEFAULT NULL,
  `login` text,
  `audit` int(10) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_ad_site`
--

CREATE TABLE IF NOT EXISTS `tu_ad_site` (
  `site_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `theme` varchar(32) DEFAULT NULL,
  `site_name` varchar(64) DEFAULT NULL,
  `site_type` tinyint(1) DEFAULT NULL,
  `site_place` smallint(5) DEFAULT '0',
  `site_price` int(11) DEFAULT '0' COMMENT '广告位销售价格',
  PRIMARY KEY (`site_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=86 ;

--
-- 转存表中的数据 `tu_ad_site`
--

INSERT INTO `tu_ad_site` (`site_id`, `theme`, `site_name`, `site_type`, `site_place`, `site_price`) VALUES
(85, 'red', '小程序首页banner570*270', 1, 32, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tu_area`
--

CREATE TABLE IF NOT EXISTS `tu_area` (
  `area_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(11) DEFAULT '0',
  `area_name` varchar(32) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '管理员ID',
  `ratio` int(11) DEFAULT '0',
  `Name` varchar(32) DEFAULT NULL COMMENT '名称',
  `LevelType` tinyint(6) DEFAULT NULL COMMENT '等级',
  `CityCode` tinyint(6) DEFAULT NULL,
  `ZipCode` tinyint(6) DEFAULT NULL,
  `MergerName` varchar(32) DEFAULT NULL,
  `lng` varchar(32) DEFAULT NULL,
  `lat` varchar(32) DEFAULT NULL,
  `pinyin` varchar(32) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT '100',
  PRIMARY KEY (`area_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=98 ;

--
-- 转存表中的数据 `tu_area`
--

INSERT INTO `tu_area` (`area_id`, `city_id`, `area_name`, `user_id`, `ratio`, `Name`, `LevelType`, `CityCode`, `ZipCode`, `MergerName`, `lng`, `lat`, `pinyin`, `orderby`) VALUES
(2, 1, '萍乡', 19, 100, NULL, NULL, NULL, NULL, NULL, '', '', NULL, 2);

-- --------------------------------------------------------

--
-- 表的结构 `tu_article`
--

CREATE TABLE IF NOT EXISTS `tu_article` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL COMMENT '关联的商家文章ID',
  `title` varchar(128) DEFAULT NULL,
  `cate_id` int(11) DEFAULT '0',
  `city_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `shop_id` int(10) NOT NULL DEFAULT '0',
  `source` varchar(32) DEFAULT NULL,
  `profiles` text,
  `keywords` varchar(256) DEFAULT NULL,
  `orderby` tinyint(4) NOT NULL DEFAULT '100',
  `photo` varchar(128) DEFAULT NULL,
  `audio` varchar(128) DEFAULT NULL COMMENT '音乐URL',
  `video` varchar(128) DEFAULT NULL COMMENT '视频URL',
  `video_photo` varchar(128) DEFAULT NULL,
  `details` text,
  `istop` int(2) NOT NULL DEFAULT '0',
  `isroll` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `zan` int(6) NOT NULL DEFAULT '0',
  `donate_num` int(10) DEFAULT '0',
  `closed` tinyint(2) NOT NULL DEFAULT '0',
  `valuate` tinyint(2) DEFAULT '0',
  `audit` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `tu_article`
--

INSERT INTO `tu_article` (`article_id`, `news_id`, `title`, `cate_id`, `city_id`, `area_id`, `shop_id`, `source`, `profiles`, `keywords`, `orderby`, `photo`, `audio`, `video`, `video_photo`, `details`, `istop`, `isroll`, `create_time`, `create_ip`, `views`, `zan`, `donate_num`, `closed`, `valuate`, `audit`) VALUES
(1, 0, '注册协议', 0, NULL, NULL, 0, '', NULL, '', 100, NULL, NULL, NULL, NULL, '<p>在您向青鸟帮帮外卖服务平台（以下简称青鸟帮帮平台）提交自由跑腿人注册申请之前，您应当仔细阅读本协议方便注册。本协议将成为您和青鸟帮帮平台之间具有法律约束力的文件。</p><p>&nbsp; &nbsp; &nbsp; 自由跑腿人系具备完全民事行为能力的自然人。</p><p>&nbsp; &nbsp; &nbsp; 自由跑腿人自愿接受青鸟帮帮平台放到待抢区的任务，为青鸟帮帮用户提供服务完成任务事项。</p><p>&nbsp; &nbsp; &nbsp; 自由跑腿人申请注册并经青鸟帮帮审核通过后，通过青鸟帮帮平台按要求完成任务事项，并在事项完成后获得相应报酬。</p><p>&nbsp; &nbsp; &nbsp; 青鸟帮帮平台作为信息发布、服务平台，仅为平台用户提供信息服务，供用户自主选择接受任务事项信息。青鸟帮帮平台不对任务事项信息的真实性或准确性及所涉物品的质量、安全或合法性等提供担保。您应自行谨慎判断确定相关信息的真实性、合法性和有效性，并自行承担因此产生的责任与损失。用户对本平台上任何信息资料的选择、接受，取决于用户自己并由其自行承担所有风险和责任。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;请您仔细阅读本条款，您提交相关信息并经青鸟帮帮平台审核成功申请成为自由跑腿人后，表明您已了解并接受 《自由跑腿人注册协议》，本协议条款即构成了对双方具有法律约束力的文件。 届时您不得以未阅读（不理解或不同意）本协议内容为由，主张协议内容无效或要求撤销。</p><p>&nbsp; &nbsp; &nbsp; 一、 服务模式：</p><p>&nbsp; &nbsp; &nbsp; 1、自由跑腿人申请注册并经青鸟帮帮平台审核通过后，即可通过手机终端自主选择接受、完成飞毛腿跑腿用户通过青鸟帮帮平台发布的任务事项，并在事项完成后获得该任务事项的报酬。</p><p>&nbsp; &nbsp; &nbsp; 2、青鸟帮帮平台或青鸟帮帮平台指定的区管公司对本区域内青鸟帮帮平台的运营进行监管。</p><p>&nbsp; &nbsp; &nbsp; 3、对于自由跑腿人自愿接受的任务事项，应当按照本协议附件一、附件二、附件三中的要求完成。除了附件一、二、三之外，自由跑腿人无需遵守青鸟帮帮平台内部管理的相关规章制度，但应当依照本协议和法律法规的规定，妥善完成其所接受的每项任务事项。</p><p>&nbsp; &nbsp; &nbsp; 4、自由跑腿人自行选择接受并完成青鸟帮帮平台上发布的任务事项；自行选择任务事项完成方式及完成任务事项所需的交通工具和其他设施设备。&nbsp; &nbsp; &nbsp; &nbsp;自由跑腿人承诺：在接受及完成任务事项的过程中，自由跑腿人的人身及财产安全由自由跑腿人自行负责并承担全部责任；因自由跑腿人原因造成任何第三方人身、财产损失或侵害第三方合法权益的，自由跑腿人应妥善处理纠纷并承担全部赔偿责任。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;二、注册有效期限：从自由跑腿人资格审核通过之日起。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;三、自由跑腿人收益：</p><p>&nbsp; &nbsp; &nbsp; &nbsp;1、自由跑腿人通过青鸟帮帮平台获取任务事项信息并接受、完成任务事项后，可按照青鸟帮帮平台发布的该任务事项报酬，获得信息发布方给付的任务事项报酬（平台会按平台服务费收取标准扣除相应比例的信息服务费，故自由跑腿人最终收入=配送费价格-平台服务费）（平台服务费目前暂定为配送费×16%）。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;2、自由跑腿人获得的任务报酬产生的税收等费用，由自由跑腿人依法自行承担。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;四、自由跑腿人注册申请条件&nbsp; &nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp;1、年满十八周岁且身体健康，具有完全民事行为能力和相应劳动能力。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;2、应遵守国家有关法律法规和青鸟帮帮平台管理规定，为青鸟帮帮平台上的用户提供服务。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;3、根据青鸟帮帮平台要求完成在线学习内容并通过在线考核，提供相关个人信息及接受、完成任务事项所需的交通工具和其他设施设备；并对所提供的信息的真实性、有效性及使用的交通工具、设施设备的合法性、安全性承担全部责任。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;4、对接受、完成任务事项中若出现违法犯罪行为，包括但不限于侵占、盗窃、抢劫、抢夺等，依法承担全部刑事、民事责任。青鸟帮帮平台有权将该违法行为报告给相关司法机关及管理部门。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;5、自由跑腿人同意青鸟帮帮平台对其提供的信息资料进行统一管理；青鸟帮帮平台有权对自由跑腿人的服务进行监督，并决定注销自由跑腿人账户，取消自由跑腿人资格。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;6、对在合作过程中知悉的青鸟帮帮平台的商业信息及青鸟帮帮平台用户的详细信息进行保密，不得阅览、复印、摘抄或以其他方式传播、披露、泄露，自行或允许他人为青鸟帮帮平台任务事项之外的用途使用。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;7、禁止自由跑腿人个人或伙同他人以欺诈的方式获取青鸟帮帮平台给予的各种补贴、扶持政策、商业利益，否则，青鸟帮帮平台有权没收该部分违法所得，并追究行为人的法律责任。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;五、本协议自自由跑腿人通过青鸟帮帮平台提交自由跑腿人注册申请并完成在线学习内容、经审核通过之日起生效。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;六、本协议约定的注册有效期限届满后，若自由跑腿人或青鸟帮帮平台均未注销该自由跑腿人注册账号及信息，本协议自动续延一年。</p><p>&nbsp; &nbsp; &nbsp; &nbsp;自由跑腿人必须遵守青鸟帮帮平台的相关规则，如果自由跑腿人审核通过后，连续一周内未接受任何任务或者订单，青鸟帮帮平台有权按规定暂停或者，停止其服务。&nbsp;</p><p>&nbsp; &nbsp;</p><p><br/></p>', 0, 0, 1555247954, '222.181.200.221', 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `tu_article_cate`
--

CREATE TABLE IF NOT EXISTS `tu_article_cate` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(32) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL COMMENT '商家ID，备用',
  `user_id` int(11) DEFAULT NULL COMMENT '会员ID，备用',
  `price` int(11) DEFAULT NULL COMMENT '购买价格，备份',
  `intro` varchar(128) NOT NULL COMMENT '分类简介',
  `orderby` tinyint(3) DEFAULT '100',
  `create_time` int(11) NOT NULL COMMENT '时间',
  `create_ip` varchar(15) NOT NULL COMMENT 'IP',
  `closed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1代表删除',
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `tu_article_cate`
--

INSERT INTO `tu_article_cate` (`cate_id`, `cate_name`, `parent_id`, `shop_id`, `user_id`, `price`, `intro`, `orderby`, `create_time`, `create_ip`, `closed`) VALUES
(1, '家庭琐事', 0, NULL, NULL, NULL, '', 1, 0, '', 0),
(2, '重庆大事记', 1, NULL, NULL, NULL, '', 1, 0, '', 0),
(7, '其他新闻', 1, NULL, NULL, NULL, '', 2, 0, '', 0),
(4, '头条', 0, NULL, NULL, NULL, '', 0, 0, '', 0),
(5, '民生', 4, NULL, NULL, NULL, '', 1, 0, '', 0),
(6, '乡镇', 4, NULL, NULL, NULL, '', 2, 0, '', 0),
(8, '问答求助', 0, NULL, NULL, NULL, '', 2, 0, '', 0),
(9, '数码家电', 8, NULL, NULL, NULL, '', 1, 0, '', 0),
(10, '美女', 8, NULL, NULL, NULL, '', 2, 0, '', 0),
(11, '摆龙门阵', 0, NULL, NULL, NULL, '', 3, 0, '', 0),
(12, '杂事', 11, NULL, NULL, NULL, '', 0, 0, '', 0),
(13, '测试1', 6, NULL, NULL, NULL, '', 1, 0, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `tu_article_comment`
--

CREATE TABLE IF NOT EXISTS `tu_article_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级评论id,若是一级评论则为0',
  `nickname` varchar(100) DEFAULT NULL COMMENT '评论人昵称',
  `user_id` int(11) DEFAULT NULL COMMENT '评论人UID',
  `post_id` int(11) DEFAULT NULL COMMENT '新闻编号',
  `content` text COMMENT '评论内容',
  `zan` int(6) NOT NULL DEFAULT '0',
  `cai` int(6) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL COMMENT '评论或回复发表时间',
  `create_ip` varchar(20) NOT NULL,
  `audit` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_article_donate`
--

CREATE TABLE IF NOT EXISTS `tu_article_donate` (
  `donate_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `city_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL COMMENT '新闻编号',
  `money` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '评论或回复发表时间',
  `create_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`donate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_article_photos`
--

CREATE TABLE IF NOT EXISTS `tu_article_photos` (
  `pic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `photo` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`pic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_business`
--

CREATE TABLE IF NOT EXISTS `tu_business` (
  `business_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `business_name` varchar(32) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '商圈绑定管理员',
  `ratio` int(11) DEFAULT '0',
  `lng` varchar(15) DEFAULT NULL COMMENT '经度',
  `lat` varchar(15) DEFAULT NULL COMMENT '纬度',
  `orderby` tinyint(3) DEFAULT '100',
  `is_hot` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`business_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- 转存表中的数据 `tu_business`
--

INSERT INTO `tu_business` (`business_id`, `business_name`, `area_id`, `user_id`, `ratio`, `lng`, `lat`, `orderby`, `is_hot`) VALUES
(1, '生态公园', 2, 1, 20, NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tu_city`
--

CREATE TABLE IF NOT EXISTS `tu_city` (
  `city_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '管理员ID',
  `ratio` int(11) DEFAULT '0',
  `agent_id` int(11) DEFAULT NULL COMMENT '代理ID',
  `photo` varchar(255) DEFAULT NULL,
  `pinyin` varchar(32) DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT '0',
  `domain` tinyint(1) NOT NULL DEFAULT '0',
  `lng` varchar(15) DEFAULT NULL,
  `lat` varchar(15) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT '100',
  `first_letter` char(1) DEFAULT NULL,
  `theme` varchar(21) NOT NULL DEFAULT 'default',
  `pid` smallint(6) DEFAULT '0' COMMENT '上级城市ID',
  `ShortName` varchar(6) DEFAULT NULL COMMENT '城市名称',
  `LevelType` tinyint(6) DEFAULT NULL COMMENT '等级',
  `CityCode` tinyint(6) DEFAULT NULL COMMENT '行政区划代码',
  `ZipCode` tinyint(6) DEFAULT NULL COMMENT '邮编代码',
  `MergerName` varchar(32) DEFAULT NULL COMMENT '连贯地区总和',
  `ParentId` int(6) DEFAULT NULL COMMENT '父级',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` varchar(15) DEFAULT NULL,
  `create_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- 转存表中的数据 `tu_city`
--

INSERT INTO `tu_city` (`city_id`, `name`, `user_id`, `ratio`, `agent_id`, `photo`, `pinyin`, `is_open`, `domain`, `lng`, `lat`, `orderby`, `first_letter`, `theme`, `pid`, `ShortName`, `LevelType`, `CityCode`, `ZipCode`, `MergerName`, `ParentId`, `closed`, `create_time`, `create_ip`) VALUES
(1, '萍乡', 19, 0, 2, '/attachs/2017/09/12/59b7680c094bc.png', 'mengzi', 1, 0, '117.193374', '34.270643', 1, 'X', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(58, '荆州', 64, 0, NULL, '/attachs/2019/02/25/thumb_5c7353d39f235.jpg', 'jingzhou', 1, 0, '', '', 0, 'J', '', 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, '1551061974', '183.93.115.157');

-- --------------------------------------------------------

--
-- 表的结构 `tu_connect`
--

CREATE TABLE IF NOT EXISTS `tu_connect` (
  `connect_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('weibo','test','weixin','qq') DEFAULT 'qq' COMMENT 'test 作为调试的时候使用！以免不懂得用户误会小弟啊',
  `open_id` varchar(32) DEFAULT NULL,
  `openid` varchar(32) DEFAULT NULL COMMENT 'xiao',
  `token` varchar(512) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `headimgurl` varchar(500) DEFAULT NULL,
  `unionid` varchar(32) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `rd_session` char(32) DEFAULT '''''' COMMENT '小程序登录状态',
  `session_key` char(64) DEFAULT '''''' COMMENT '小程序秘钥',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`connect_id`),
  UNIQUE KEY `type` (`type`,`open_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_coupon`
--

CREATE TABLE IF NOT EXISTS `tu_coupon` (
  `coupon_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT NULL,
  `cate_id` smallint(6) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL COMMENT '学校',
  `city_id` int(11) DEFAULT '0',
  `area_id` int(11) DEFAULT '0',
  `business_id` int(11) DEFAULT '0',
  `title` varchar(128) DEFAULT NULL,
  `photo` varchar(128) DEFAULT NULL,
  `full_price` int(11) DEFAULT NULL COMMENT '满多少钱',
  `reduce_price` int(11) DEFAULT NULL COMMENT '减多少钱',
  `discount` int(11) DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `downloads` int(11) DEFAULT '0',
  `intro` varchar(1024) DEFAULT NULL,
  `audit` tinyint(1) DEFAULT '0',
  `num` int(11) DEFAULT '9999999',
  `limit_num` tinyint(3) DEFAULT '0' COMMENT '0代表不限制',
  `closed` tinyint(1) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`coupon_id`),
  KEY `cate_id` (`cate_id`,`area_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_coupon_download`
--

CREATE TABLE IF NOT EXISTS `tu_coupon_download` (
  `download_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL COMMENT '学校',
  `shop_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `mobile` char(11) DEFAULT NULL,
  `money` int(11) DEFAULT '0' COMMENT '可使用金额',
  `code` char(8) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT '0',
  `is_sms` tinyint(1) DEFAULT '0',
  `used_time` int(11) DEFAULT NULL,
  `used_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`download_id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=110 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_dayu_sms`
--

CREATE TABLE IF NOT EXISTS `tu_dayu_sms` (
  `sms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sign` varchar(32) DEFAULT NULL,
  `code` varchar(1024) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `status` int(1) unsigned DEFAULT NULL COMMENT '0失败，1成功',
  `info` varchar(128) DEFAULT NULL,
  `content` varchar(500) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`sms_id`),
  UNIQUE KEY `sms_key` (`sign`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_dayu_tag`
--

CREATE TABLE IF NOT EXISTS `tu_dayu_tag` (
  `dayu_id` int(11) NOT NULL AUTO_INCREMENT,
  `dayu_local` varchar(60) DEFAULT NULL,
  `dayu_name` varchar(128) DEFAULT NULL,
  `dayu_tag` varchar(36) DEFAULT NULL,
  `dayu_note` varchar(256) DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`dayu_id`),
  KEY `dayu_id` (`dayu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

--
-- 转存表中的数据 `tu_dayu_tag`
--

INSERT INTO `tu_dayu_tag` (`dayu_id`, `dayu_local`, `dayu_name`, `dayu_tag`, `dayu_note`, `is_open`) VALUES
(2, 'sms_yzm', '验证码', 'SMS_116590507', '验证码：${code}，如非本人操作，请忽略此短信', 1),
(6, 'sms_user_newpwd', '找回密码', 'SMS_7225707', '尊敬的用户：您好，您在${siteName}的密码被重置成${newpwd}您可以使用${newpwd}重新登录', 1),
(37, 'sms_delivery_user', '快递员抢单短信通知', 'SMS_12936181', '您好${userName}，配送中心有新的订单了，标题${runningName}日期：{date}', 1),
(38, 'runningPayUser', '用户发布跑腿短信通知用户', 'SMS_13185667', '你好${userName}，您发布的跑腿${runningId}已成功付费${needPay}，${time}', 1),
(39, 'sms_running_delivery_user', '配送员接单通知用户万能短信接口', 'SMS_13196831', '${userName}您发布的任务已被${deliveryName}抢单，正在${statusName}...', 1),
(60, 'runningAcceptUser', '配送员抢单通知买家', 'SMS_13185662', '你好${userName}，您发布的跑腿${runningId}已经被配送员${DeliveryName}抢单，手机${DeliveryMobile}', 1),
(59, 'sms_ele_tz_shop', '跑腿新订单外卖通知商家', 'SMS_13185661', '${shopName}您的外卖商城有定的订单${runningId}请尽快处理', 1),
(58, 'register', '新用户注册短信通知会员', 'sms-15456456', '您被商家${shopName}邀请成功注册${sitename}会员，注册ID${userId}，账户${userAccount}，密码${userPassword}', 1);

-- --------------------------------------------------------

--
-- 表的结构 `tu_delivery`
--

CREATE TABLE IF NOT EXISTS `tu_delivery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL COMMENT '城市',
  `area_id` int(11) DEFAULT NULL COMMENT '地区',
  `business_id` int(11) DEFAULT NULL COMMENT '商圈',
  `photo` varchar(64) DEFAULT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `mobile` varchar(11) NOT NULL DEFAULT '',
  `addr` varchar(64) DEFAULT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `delivery_type` tinyint(1) NOT NULL DEFAULT '0',
  `is_sms` tinyint(1) NOT NULL DEFAULT '0',
  `is_weixin` tinyint(1) NOT NULL DEFAULT '0',
  `is_music` tinyint(1) NOT NULL DEFAULT '0',
  `lat` varchar(32) DEFAULT NULL COMMENT '进度',
  `lng` varchar(32) DEFAULT NULL COMMENT '纬度',
  `num` int(11) DEFAULT NULL,
  `audit` tinyint(1) NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobile` (`mobile`),
  UNIQUE KEY `username` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_delivery_comment`
--

CREATE TABLE IF NOT EXISTS `tu_delivery_comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) DEFAULT '0',
  `shop_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '0商城1外卖',
  `type_order_id` int(11) DEFAULT NULL COMMENT '原始订单ID',
  `delivery_id` int(11) DEFAULT NULL COMMENT '配送员ID',
  `score` tinyint(1) DEFAULT '0',
  `d1` tinyint(1) DEFAULT '0' COMMENT '点评1',
  `d2` tinyint(1) DEFAULT '0' COMMENT '点评2',
  `d3` tinyint(1) DEFAULT '0' COMMENT '点评3',
  `content` varchar(1024) DEFAULT '',
  `tag` varchar(64) DEFAULT NULL COMMENT '标签',
  `reply` varchar(1024) DEFAULT '',
  `reply_time` int(10) DEFAULT '0',
  `reply_ip` varchar(15) DEFAULT '',
  `create_time` int(10) DEFAULT '0',
  `create_ip` varchar(15) NOT NULL DEFAULT '',
  `closed` tinyint(1) DEFAULT '0' COMMENT '1代表删除',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_delivery_comment_pics`
--

CREATE TABLE IF NOT EXISTS `tu_delivery_comment_pics` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` int(10) DEFAULT '0',
  `photo` varchar(128) DEFAULT '',
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_delivery_comment_tag`
--

CREATE TABLE IF NOT EXISTS `tu_delivery_comment_tag` (
  `tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0商城1外卖',
  `tagName` varchar(64) DEFAULT NULL COMMENT '标签名字',
  `orderby` int(11) DEFAULT '100' COMMENT '排序',
  `closed` tinyint(1) DEFAULT '0' COMMENT '1代表删除',
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_delivery_order`
--

CREATE TABLE IF NOT EXISTS `tu_delivery_order` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL COMMENT '0是商城，1是外卖，2是快件',
  `type_order_id` int(10) unsigned NOT NULL COMMENT '关联的分类中的订单编号',
  `delivery_id` int(10) unsigned NOT NULL COMMENT '配送员ID',
  `shop_id` int(10) unsigned NOT NULL,
  `city_id` int(10) NOT NULL COMMENT '订单城市',
  `area_id` int(10) DEFAULT NULL COMMENT '订单商家地区',
  `business_id` int(10) DEFAULT NULL COMMENT '订单商家商圈ID',
  `lat` varchar(15) DEFAULT NULL,
  `lng` varchar(15) DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL COMMENT '会员ID',
  `shop_name` varchar(32) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `mobile` varchar(32) DEFAULT NULL,
  `addr` varchar(128) DEFAULT NULL,
  `addr_id` int(10) DEFAULT NULL COMMENT '外卖地址',
  `address_id` int(11) DEFAULT NULL COMMENT '商城地址',
  `pei_type` tinyint(1) DEFAULT '0' COMMENT '配送费类型',
  `pei_type_km` tinyint(6) DEFAULT '0' COMMENT '配送费KM',
  `logistics_price_inrto` varchar(256) DEFAULT NULL COMMENT '配送费备注',
  `need_pay` int(11) DEFAULT '0' COMMENT '订单支付价格',
  `logistics_price` int(11) DEFAULT NULL,
  `intro` varchar(128) DEFAULT NULL COMMENT '抢单备注',
  `is_appoint` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不指定，1指定配送员',
  `appoint_user_id` int(11) DEFAULT NULL COMMENT '指定配送员ID',
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '接单时间',
  `end_time` int(10) DEFAULT '0' COMMENT '完成时间 ',
  `status` tinyint(1) unsigned NOT NULL COMMENT '0是货到付款，1是已付款，2是配送中，8是已完成。',
  `closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_delivery_order_tail`
--

CREATE TABLE IF NOT EXISTS `tu_delivery_order_tail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_order_id` int(10) unsigned NOT NULL COMMENT '购物订单',
  `order_id` int(10) unsigned NOT NULL COMMENT '配送订单ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '会员ID',
  `price` int(11) DEFAULT '0' COMMENT '价格',
  `create_time` int(10) unsigned NOT NULL,
  `closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_delivery_set`
--

CREATE TABLE IF NOT EXISTS `tu_delivery_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) DEFAULT '0' COMMENT '分站ID',
  `cs_time` smallint(6) DEFAULT '50' COMMENT '抢单超时时间',
  `s_dist` decimal(10,2) DEFAULT '2.00' COMMENT '起步公里数',
  `s_price` decimal(10,2) DEFAULT '0.50' COMMENT '起步价为',
  `one_dist` decimal(10,2) DEFAULT '0.50' COMMENT '每公里价位',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_delivery_surface`
--

CREATE TABLE IF NOT EXISTS `tu_delivery_surface` (
  `surface_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL COMMENT '城市',
  `area_id` int(11) DEFAULT NULL COMMENT '地区',
  `business_id` int(11) DEFAULT NULL COMMENT '商圈',
  `thumb` text,
  `name` varchar(32) NOT NULL DEFAULT '',
  `mobile` varchar(11) NOT NULL DEFAULT '',
  `addr` varchar(64) DEFAULT NULL,
  `receiver` varchar(64) DEFAULT NULL COMMENT '签收人',
  `intro` varchar(256) DEFAULT NULL,
  `lat` varchar(32) DEFAULT NULL COMMENT '进度',
  `lng` varchar(32) DEFAULT NULL COMMENT '纬度',
  `audit` tinyint(1) NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`surface_id`),
  UNIQUE KEY `surface_id_2` (`surface_id`),
  KEY `surface_id` (`surface_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_ele`
--

CREATE TABLE IF NOT EXISTS `tu_ele` (
  `shop_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL COMMENT 'school_id',
  `shop_name` varchar(64) DEFAULT NULL COMMENT '冗余方便查询',
  `city_id` int(11) unsigned DEFAULT NULL,
  `area_id` int(11) DEFAULT '0',
  `business_id` int(11) DEFAULT '0',
  `cate` varchar(64) DEFAULT NULL,
  `pic1` varchar(256) DEFAULT NULL COMMENT '营业执照',
  `pic2` varchar(256) DEFAULT NULL COMMENT '卫生许可证',
  `lng` varchar(15) DEFAULT NULL,
  `lat` varchar(15) DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT '0' COMMENT '1 代表营业中',
  `is_coupon` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不支持，1支持',
  `busihour` varchar(100) NOT NULL,
  `is_radius` int(11) DEFAULT NULL,
  `given_distribution` int(11) DEFAULT NULL,
  `is_print_deliver` tinyint(1) NOT NULL DEFAULT '0',
  `is_voice` tinyint(1) NOT NULL DEFAULT '0',
  `is_refresh` tinyint(1) NOT NULL DEFAULT '0',
  `is_refresh_second` int(3) DEFAULT NULL,
  `tags` varchar(128) DEFAULT NULL,
  `is_pay` tinyint(1) DEFAULT '0' COMMENT '1代表支持在线付款',
  `is_daofu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不支持，1支持',
  `is_fan` tinyint(1) DEFAULT '0' COMMENT '1 代表返现',
  `fan_money` int(10) DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT NULL,
  `full_money` int(10) DEFAULT '0' COMMENT '满多少MONEY 立刻减几元',
  `new_money` int(10) DEFAULT '0' COMMENT '减多少钱  比如说 满20减5元 那么  每增加10块钱 将额外减一元',
  `is_full` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开启满减',
  `order_price_full_1` int(11) DEFAULT NULL COMMENT '满多少1',
  `order_price_reduce_1` int(11) DEFAULT NULL COMMENT '减多少1',
  `order_price_full_2` int(11) DEFAULT NULL COMMENT '满多少2',
  `order_price_reduce_2` int(11) DEFAULT NULL COMMENT '减多少2',
  `logistics` int(10) DEFAULT '0' COMMENT '0代表不收取配送费 填写其他的将代表收取',
  `logistics_full` int(11) DEFAULT NULL COMMENT '满多少金额免配送费',
  `since_money` int(10) DEFAULT NULL COMMENT '起送价',
  `pei_type` tinyint(1) DEFAULT '0' COMMENT '配送模式',
  `pei_type_1km` int(6) DEFAULT '0' COMMENT '配送费1KM',
  `pei_type_2km` int(6) DEFAULT '0' COMMENT '配送费2KM',
  `pei_type_3km` int(6) DEFAULT '0' COMMENT '配送费3KM',
  `pei_type_4km` int(6) DEFAULT '0' COMMENT '配送费4KM',
  `pei_type_5km` int(6) DEFAULT '0' COMMENT '配送费5KM',
  `pei_type_6km` int(6) DEFAULT '0' COMMENT '配送费6KM',
  `pei_type_7km` int(6) DEFAULT '0' COMMENT '配送费7KM',
  `sold_num` int(10) DEFAULT NULL,
  `month_num` int(10) DEFAULT NULL,
  `intro` varchar(1024) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT '100' COMMENT '数字越小排序越高',
  `distribution` tinyint(3) DEFAULT '30' COMMENT '分钟  配送时间',
  `audit` tinyint(3) unsigned DEFAULT '0' COMMENT '0审核中1成功入驻2未通过',
  `rate` int(11) DEFAULT '60' COMMENT '费率 每个商品的结算价格',
  PRIMARY KEY (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_ele_cate`
--

CREATE TABLE IF NOT EXISTS `tu_ele_cate` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT NULL,
  `cate_name` varchar(32) DEFAULT NULL,
  `num` int(11) DEFAULT '0',
  `closed` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`cate_id`),
  KEY `shop_id` (`shop_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=89 ;

--
-- 转存表中的数据 `tu_ele_cate`
--

INSERT INTO `tu_ele_cate` (`cate_id`, `shop_id`, `cate_name`, `num`, `closed`) VALUES
(14, 1, '面食', 4, 0),
(88, 1, '哎呀', 2, 0),
(87, 4, '吃的', 0, 0),
(86, 4, '口红', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `tu_ele_dianping`
--

CREATE TABLE IF NOT EXISTS `tu_ele_dianping` (
  `dianping_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `speed` tinyint(3) unsigned DEFAULT '0',
  `cost` int(11) DEFAULT NULL,
  `contents` varchar(1024) DEFAULT NULL,
  `reply` varchar(1024) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `show_date` date DEFAULT NULL,
  `closed` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`order_id`),
  KEY `dianping_id` (`dianping_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_ele_dianping_pics`
--

CREATE TABLE IF NOT EXISTS `tu_ele_dianping_pics` (
  `pic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `pic` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`pic_id`),
  KEY `dianping_id` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_ele_order`
--

CREATE TABLE IF NOT EXISTS `tu_ele_order` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `orderType` tinyint(1) DEFAULT '1' COMMENT '1正常下单，2自提',
  `addr_id` int(11) DEFAULT '0',
  `total_price` int(11) DEFAULT '0',
  `old_total_price` int(11) DEFAULT '0',
  `logistics` int(11) DEFAULT '0',
  `freight` int(11) DEFAULT '0' COMMENT '运费备用',
  `need_pay` int(11) DEFAULT '0',
  `old_need_pay` int(11) DEFAULT '0',
  `num` int(11) DEFAULT '0',
  `new_money` int(11) DEFAULT '0',
  `orderType_logistics` int(11) DEFAULT '0' COMMENT '自提中专费用',
  `logistics_full_money` int(11) DEFAULT '0' COMMENT '满多少配送费立减费用',
  `download_coupon_id` int(11) DEFAULT NULL COMMENT '使用下载优惠劵ID',
  `reduce_coupun_money` int(11) DEFAULT NULL COMMENT '使用优惠劵减去费用',
  `full_reduce_price` int(11) NOT NULL DEFAULT '0' COMMENT '满减费用',
  `tableware_price` int(11) DEFAULT '0' COMMENT '餐具费用',
  `settlement_price` int(11) DEFAULT '0',
  `settlementIntro` varchar(256) DEFAULT NULL COMMENT '结算备注',
  `profit_price` int(11) DEFAULT '0',
  `profit_price_intro` varchar(128) DEFAULT NULL,
  `status` tinyint(3) DEFAULT '0' COMMENT '1等待处理  2代表已经确认  8代表配送完成',
  `is_pay` tinyint(1) DEFAULT '0' COMMENT '0是货到付款，1是在线支付',
  `is_daofu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为在线支付，1为货到付款',
  `is_store` tinyint(1) NOT NULL DEFAULT '0',
  `is_profit` tinyint(1) DEFAULT '0' COMMENT '1代表已分销，0未分销',
  `is_print` tinyint(1) DEFAULT '0',
  `updateAddrNum` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  `pay_time` int(11) DEFAULT NULL COMMENT '外卖付款时间',
  `refund_time` int(11) DEFAULT NULL COMMENT '退款时间',
  `orders_time` int(11) DEFAULT NULL COMMENT '接单时间',
  `end_time` int(11) DEFAULT NULL COMMENT '订单完成时间',
  `create_ip` varchar(15) DEFAULT NULL,
  `audit_time` int(11) DEFAULT '0',
  `closed` tinyint(1) DEFAULT '0',
  `month` int(11) DEFAULT '201501',
  `message` varchar(100) DEFAULT NULL,
  `date` varchar(32) DEFAULT NULL COMMENT 'date送货时间',
  `code` varchar(6) DEFAULT NULL COMMENT '支付方式',
  `is_dianping` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_ele_order_cart`
--

CREATE TABLE IF NOT EXISTS `tu_ele_order_cart` (
  `cart_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned DEFAULT '0' COMMENT '商家ID',
  `user_id` int(11) DEFAULT '0' COMMENT '会员ID',
  `option_id` int(11) DEFAULT '0' COMMENT '规格ID',
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `price` int(11) unsigned DEFAULT '0' COMMENT '价格',
  `total_price` varchar(11) DEFAULT '0' COMMENT '不知道什么价格',
  `data` varchar(5000) NOT NULL,
  `num_data` varchar(5000) DEFAULT NULL COMMENT '不知道',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `paytime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cart_id`),
  KEY `uid` (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_ele_order_product`
--

CREATE TABLE IF NOT EXISTS `tu_ele_order_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL COMMENT '规格ID',
  `product_name` varchar(32) DEFAULT NULL COMMENT '菜品名称',
  `num` int(11) DEFAULT '0',
  `price` int(11) DEFAULT '0' COMMENT '单价',
  `settlement_price` int(11) DEFAULT '0' COMMENT '结算价',
  `total_price` int(11) DEFAULT NULL,
  `tableware_price` int(11) DEFAULT '0' COMMENT '订单菜单表新增餐具费',
  `month` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_ele_product`
--

CREATE TABLE IF NOT EXISTS `tu_ele_product` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(32) DEFAULT NULL,
  `desc` varchar(255) NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `type` int(1) DEFAULT '1',
  `cate_id` int(11) DEFAULT NULL,
  `is_options` tinyint(1) DEFAULT '0' COMMENT '多规格',
  `photo` varchar(128) DEFAULT NULL,
  `cost_price` int(11) DEFAULT NULL COMMENT '原价',
  `price` int(11) DEFAULT NULL,
  `tableware_price` int(11) DEFAULT '0' COMMENT '餐具费',
  `settlement_price` int(11) unsigned DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT '0',
  `is_hot` tinyint(1) DEFAULT '0',
  `is_tuijian` tinyint(1) DEFAULT '0',
  `num` int(11) DEFAULT '0' COMMENT '库存',
  `limit_num` int(11) DEFAULT '0' COMMENT '每人每天限制',
  `sold_num` int(11) DEFAULT '0',
  `month_num` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `closed` tinyint(1) DEFAULT '0',
  `audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`),
  KEY `shop_id` (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_ele_product_options`
--

CREATE TABLE IF NOT EXISTS `tu_ele_product_options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) unsigned DEFAULT '0' COMMENT '商家ID',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `name` varchar(50) NOT NULL,
  `price` varchar(50) NOT NULL,
  `total` int(10) NOT NULL DEFAULT '-1',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`product_id`),
  KEY `sid` (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_group`
--

CREATE TABLE IF NOT EXISTS `tu_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `store_id` int(11) NOT NULL COMMENT '门店id',
  `shop_id` int(11) DEFAULT NULL,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `goods_logo` varchar(255) NOT NULL,
  `goods_name` varchar(255) NOT NULL COMMENT '商品名称',
  `kt_num` int(11) NOT NULL COMMENT '开团人数',
  `yg_num` int(11) NOT NULL COMMENT '已购数量',
  `kt_time` int(11) NOT NULL COMMENT '开团时间',
  `dq_time` int(11) NOT NULL COMMENT '到期时间',
  `state` int(4) NOT NULL COMMENT '1.拼团中2成功,3失败',
  `user_id` int(11) NOT NULL COMMENT '团长user_id',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_group_goods`
--

CREATE TABLE IF NOT EXISTS `tu_group_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `store_id` int(11) NOT NULL COMMENT '门店id',
  `shop_id` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL COMMENT '分类ID',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `logo` varchar(255) NOT NULL COMMENT 'logo',
  `img` text NOT NULL COMMENT '多图',
  `inventory` int(11) NOT NULL COMMENT '库存',
  `pt_price` decimal(10,2) NOT NULL COMMENT '拼团价格',
  `y_price` decimal(10,2) NOT NULL COMMENT '原价',
  `dd_price` decimal(10,2) NOT NULL COMMENT '单独购买价格',
  `ycd_num` int(11) NOT NULL COMMENT '已成团数量',
  `ysc_num` int(11) NOT NULL COMMENT '已售出数量',
  `people` int(11) NOT NULL COMMENT '成团人数',
  `start_time` int(11) NOT NULL COMMENT '开始时间',
  `end_time` int(11) NOT NULL COMMENT '结束时间',
  `xf_time` int(11) NOT NULL COMMENT '消费截止时间',
  `is_shelves` int(4) NOT NULL DEFAULT '1' COMMENT '1上架,2下架',
  `details` text NOT NULL COMMENT '商品详情',
  `details_img` text NOT NULL COMMENT '详情多图',
  `num` int(11) NOT NULL COMMENT '排序',
  `state` int(4) NOT NULL DEFAULT '1' COMMENT '1待审核,2通过，3拒绝',
  `display` int(4) NOT NULL DEFAULT '1' COMMENT '1显示,2不显示',
  `uniacid` int(11) NOT NULL,
  `introduction` text NOT NULL,
  `time` int(11) NOT NULL COMMENT '发布时间',
  `cityname` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_group_order`
--

CREATE TABLE IF NOT EXISTS `tu_group_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `group_id` int(11) NOT NULL COMMENT '团id',
  `store_id` int(11) NOT NULL COMMENT '门店id',
  `shop_id` int(11) DEFAULT NULL,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `order_num` varchar(30) NOT NULL COMMENT '订单号',
  `logo` varchar(255) NOT NULL COMMENT '商品图片',
  `goods_name` varchar(255) NOT NULL COMMENT '商品名称',
  `goods_type` varchar(50) NOT NULL COMMENT '商品类型',
  `price` decimal(10,2) NOT NULL COMMENT '单价',
  `goods_num` int(11) NOT NULL COMMENT '商品数量',
  `money` decimal(10,2) NOT NULL COMMENT '订单金额',
  `pay_type` int(4) NOT NULL COMMENT '付款方式1微信，2余额',
  `receive_name` varchar(30) NOT NULL COMMENT '收货人',
  `receive_tel` varchar(20) NOT NULL COMMENT '收货人电话',
  `receive_addr` varchar(512) DEFAULT NULL,
  `receive_address` varchar(100) NOT NULL COMMENT '收货人地址',
  `note` varchar(100) NOT NULL COMMENT '备注',
  `state` int(4) NOT NULL COMMENT '1未付款,2已付款,3已完成,4已关闭,5已失效',
  `xf_time` int(11) NOT NULL COMMENT '消费截止时间',
  `time` int(11) NOT NULL COMMENT '下单时间',
  `pay_time` int(11) NOT NULL COMMENT '付款时间',
  `cz_time` int(11) NOT NULL COMMENT '完成/关闭/失效时间',
  `code` varchar(30) NOT NULL COMMENT '支付商户号',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_group_type`
--

CREATE TABLE IF NOT EXISTS `tu_group_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `name` varchar(20) NOT NULL,
  `img` varchar(500) NOT NULL,
  `num` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_keyword`
--

CREATE TABLE IF NOT EXISTS `tu_keyword` (
  `key_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(32) DEFAULT NULL,
  `type` tinyint(2) DEFAULT '0' COMMENT '搜索关键字，0不限，1表示团购，2表示商家',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`key_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_lock`
--

CREATE TABLE IF NOT EXISTS `tu_lock` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `t` char(8) DEFAULT '0' COMMENT 'UID 操作分钟级别锁',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`t`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_menu`
--

CREATE TABLE IF NOT EXISTS `tu_menu` (
  `menu_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(32) DEFAULT NULL,
  `menu_action` varchar(64) DEFAULT NULL,
  `parent_id` smallint(5) DEFAULT '0',
  `orderby` tinyint(3) unsigned DEFAULT '100' COMMENT '1排序第一',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '0代表不直接显示',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1127 ;

--
-- 转存表中的数据 `tu_menu`
--

INSERT INTO `tu_menu` (`menu_id`, `menu_name`, `menu_action`, `parent_id`, `orderby`, `is_show`) VALUES
(1, '系统', '', 0, 1, 1),
(2, '设置', '', 0, 2, 1),
(3, '商家', '', 0, 4, 1),
(4, '会员', '', 0, 3, 1),
(203, '支付方式', 'payment/index', 202, 1, 1),
(8, '运营', '', 0, 10, 1),
(9, '管理员管理', '', 1, 2, 1),
(11, '后台设置', '', 1, 1, 1),
(12, '菜单列表', 'menu/index', 11, 2, 1),
(13, '新增菜单', 'menu/create', 11, 3, 0),
(14, '编辑菜单', 'menu/edit', 11, 1, 0),
(15, '删除菜单', 'menu/delete', 11, 3, 0),
(16, '更新菜单', 'menu/update', 11, 4, 0),
(17, '批量菜单', 'menu/action', 11, 1, 0),
(18, '角色管理', 'role/index', 9, 2, 1),
(25, '新增角色', 'role/create', 9, 100, 0),
(26, '编辑角色', 'role/edit', 9, 100, 0),
(27, '删除角色', 'role/delete', 9, 100, 0),
(28, '角色授权', 'role/auth', 9, 100, 0),
(29, '管理员管理', 'admin/index', 9, 1, 1),
(30, '新增管理员', 'admin/create', 9, 100, 0),
(31, '编辑管理员', 'admin/edit', 9, 100, 0),
(32, '删除管理员', 'admin/delete', 9, 100, 0),
(33, '会员管理', '', 4, 1, 1),
(34, '会员管理', 'user/index', 33, 1, 1),
(35, '新增会员', 'user/create', 33, 100, 0),
(36, '编辑会员', 'user/edit', 33, 100, 0),
(37, '删除会员', 'user/delete', 33, 100, 0),
(39, '缓存管理', '', 8, 6, 1),
(40, '清空缓存', 'clean/cache', 39, 100, 1),
(41, '审核会员', 'user/audit', 33, 100, 0),
(43, '商家管理', '', 3, 3, 1),
(49, '基础设置', '', 2, 1, 1),
(50, '区域设置', '', 2, 3, 1),
(51, '区域管理', 'area/index', 50, 3, 1),
(52, '新增区域', 'area/create', 50, 100, 0),
(53, '编辑区域', 'area/edit', 50, 100, 0),
(54, '删除区域', 'area/delete', 50, 100, 0),
(55, '商圈管理', 'business/index', 50, 100, 0),
(56, '新增商圈', 'business/create', 50, 100, 0),
(57, '编辑商圈', 'business/edit', 50, 100, 0),
(58, '删除商圈', 'business/delete', 50, 100, 0),
(277, '审核商家', 'shop/audit', 43, 100, 0),
(61, '文章内容', '', 915, 1, 1),
(62, '抢购管理', '', 896, 6, 1),
(66, '广告管理', '', 8, 1, 1),
(202, '支付设置', '', 2, 5, 1),
(215, '微信', '', 0, 11, 1),
(278, '积分兑换', 'integralexchange/index', 234, 2, 1),
(279, '设为已完成兑换', 'integralexchange/audit', 234, 2, 0),
(280, '设为热门分类', 'shopcate/hots', 42, 6, 0),
(80, '站点设置', 'setting/site', 49, 1, 1),
(81, '附件设置', 'setting/attachs', 49, 2, 1),
(85, '商家列表', 'shop/index', 43, 2, 1),
(86, '新增商家', 'shop/create', 43, 100, 0),
(87, '修改商家', 'shop/edit', 43, 100, 0),
(88, '删除商家', 'shop/delete', 43, 100, 0),
(90, '异步选择会员', 'user/select', 33, 100, 0),
(91, '异步查询商圈', 'business/child', 50, 100, 0),
(101, '商家异步查询', 'shop/select', 43, 100, 0),
(1117, '拼团商品', 'group/goods', 1115, 2, 1),
(1116, '拼团分类', 'group/type', 1115, 1, 1),
(1115, '拼团功能', '', 1114, 100, 1),
(1114, '拼团', '', 0, 9, 1),
(1113, '拼车', '', 0, 8, 1),
(1112, '主题列表', 'thread/index', 1111, 1, 1),
(1111, '主题列表', '', 1102, 4, 1),
(1110, '贴吧配置', 'setting/wxapp', 1103, 1, 1),
(1109, '打赏列表', 'thread/donate', 1108, 1, 1),
(1108, '贴吧打赏', '', 1102, 5, 1),
(1107, '帖子列表', 'threadpost/index', 1106, 1, 1),
(1106, '帖子列表', '', 1102, 4, 1),
(1105, '贴吧分类', 'threadcate/index', 1104, 1, 1),
(1104, '贴吧分类', '', 1102, 2, 1),
(1103, '贴吧设置', '', 1102, 1, 1),
(509, '提现记录', '', 4, 3, 1),
(206, '支付日志', 'paymentlogs/index', 202, 4, 1),
(156, '短信设置', 'setting/sms', 49, 4, 1),
(158, '模版管理', '', 2, 6, 1),
(159, '短信宝模版', 'sms/index', 158, 3, 1),
(160, '新增短信模版', 'sms/create', 158, 12, 0),
(161, '编辑短信模版', 'sms/edit', 158, 13, 0),
(162, '关闭短信模版', 'sms/delete', 158, 14, 0),
(175, '批量开启短信', 'sms/audit', 158, 15, 0),
(189, '广告位置', 'adsite/index', 66, 2, 1),
(193, '广告管理', 'ad/index', 66, 100, 0),
(194, '新增广告', 'ad/create', 66, 100, 0),
(195, '编辑广告', 'ad/edit', 66, 100, 0),
(196, '删除广告', 'ad/delete', 66, 100, 0),
(204, '安装支付', 'payment/install', 202, 2, 0),
(205, '卸载支付', 'payment/uninstall', 202, 3, 0),
(259, '积分设置', 'setting/integral', 49, 4, 1),
(441, '资金记录', 'shopmoney/index', 440, 1, 1),
(211, '会员积分日志', 'userintegrallogs/index', 291, 40, 1),
(212, '增加积分', 'user/integral', 33, 100, 0),
(213, '商户资金日志', 'usergoldlogs/index', 291, 42, 1),
(214, '增加金块', 'user/gold', 33, 100, 0),
(216, '微信配置', '', 215, 1, 1),
(218, '微信配置', 'setting/weixin', 216, 1, 1),
(219, '微信关键字', 'weixinkeyword/index', 216, 2, 1),
(220, '新增关键字', 'weixinkeyword/create', 216, 100, 0),
(221, '编辑关键字', 'weixinkeyword/edit', 216, 100, 0),
(222, '删除关键字', 'weixinkeyword/delete', 216, 100, 0),
(240, '会员等级', 'userrank/index', 33, 2, 1),
(241, '新增等级', 'userrank/create', 33, 100, 0),
(242, '编辑等级', 'userrank/edit', 33, 100, 0),
(243, '删除等级', 'userrank/delete', 33, 100, 0),
(262, '新增地址', 'useraddr/create', 260, 100, 0),
(255, '分类列表', 'activitycate/index', 244, 1, 1),
(256, '添加分类', 'activitycate/create', 244, 100, 0),
(257, '编辑分类', 'activitycate/edit', 244, 100, 0),
(258, '删除分类', 'activitycate/delete', 244, 100, 0),
(263, '编辑地址', 'useraddr/edit', 260, 100, 0),
(264, '删除地址', 'useraddr/delete', 260, 100, 0),
(274, '微信消息列表', 'weixinmsg/index', 932, 11, 1),
(281, '热门商圈', 'business/hots', 50, 100, 0),
(291, '会员日志', '', 4, 2, 1),
(292, '会员余额日志', 'usermoneylogs/index', 291, 43, 1),
(326, '增加余额', 'user/money', 33, 100, 0),
(327, '新增商家资金', 'shopmoney/create', 43, 100, 0),
(336, '自定义菜单', 'setting/weixinmenu', 216, 3, 1),
(337, '删除微信消息', 'weixinmsg/delete', 216, 100, 0),
(850, '会员回收站', 'user/recycle', 33, 4, 1),
(517, '新增站点', 'city/create', 50, 100, 0),
(519, '删除站点', 'city/delete', 50, 100, 0),
(518, '编辑站点', 'city/edit', 50, 100, 0),
(527, '微信模板消息', 'weixintmpl/index', 932, 13, 1),
(413, '删除关键字', 'keyword/delete', 408, 100, 0),
(416, '外卖商家列表', '', 901, 2, 1),
(516, '城市站点', 'city/index', 50, 2, 1),
(421, '商家列表', 'ele/index', 416, 1, 1),
(422, '新增商家', 'ele/create', 416, 100, 0),
(423, '编辑商家', 'ele/edit', 416, 100, 0),
(424, '删除商家', 'ele/delete', 416, 100, 0),
(425, '打样管理', 'ele/opened', 416, 100, 0),
(430, '外卖菜单管理', 'eleproduct/index', 911, 2, 1),
(431, '新增菜单', 'eleproduct/create', 911, 100, 0),
(432, '编辑菜单', 'eleproduct/edit', 911, 100, 0),
(433, '删除菜单', 'eleproduct/delete', 911, 100, 0),
(434, '进入商家中心', 'shop/login', 43, 100, 0),
(438, '餐饮订单管理', 'eleorder/index', 912, 3, 1),
(439, '删除订单', 'eleorder/delete', 912, 100, 0),
(440, '结算管理', '', 8, 4, 1),
(585, '数据库备份', 'database/index', 534, 2, 1),
(510, '会员提现管理', 'usercash/index', 509, 1, 1),
(190, '添加广告位', 'adsite/create', 66, 4, 1),
(534, '系统维护', '', 1, 3, 1),
(536, '销售流水', '', 8, 5, 1),
(537, '资金记录', 'shopmoney/index', 536, 1, 1),
(538, '月订单汇总', 'shopmoney/tjmonth', 536, 2, 1),
(539, '日订单汇总', 'shopmoney/tjday', 536, 3, 1),
(540, '年订单汇总', 'shopmoney/tjyear', 536, 4, 1),
(1102, '贴吧管理', '', 0, 7, 1),
(822, '跑腿设置', 'setting/running', 731, 5, 1),
(597, '增加余额', 'user/money', 541, 100, 0),
(625, '文章回收站', 'article/recovery', 61, 6, 1),
(626, '文章回复', 'articlereply/index', 61, 5, 1),
(627, '商家回收站', 'shop/recovery', 43, 3, 1),
(1122, '红包管理', '', 8, 2, 1),
(1123, '红包列表', 'coupon/index', 1122, 1, 1),
(1124, '用户红包列表', 'coupondownload/index', 1122, 2, 1),
(1121, '拼车列表', 'pinche/index', 1120, 1, 1),
(1120, '拼车功能', '', 1113, 100, 1),
(1119, '拼团订单', 'group/order', 1115, 4, 1),
(1077, '跑腿分类设置', 'running/set', 1061, 1, 1),
(1076, '跑腿设置', 'setting/running', 1062, 1, 1),
(1099, '跑腿文章列表', 'article/index', 1062, 2, 1),
(1100, '推送信息', '', 8, 3, 1),
(1101, '推送消息', 'push/index', 1100, 1, 1),
(715, '商户提现管理', 'usercash/gold', 509, 2, 1),
(723, '大于短信模板', 'dayu/index', 158, 1, 1),
(1098, '配送员财务记录', 'running/finance', 1082, 2, 1),
(729, '文章审核', 'article/audit', 61, 18, 0),
(744, '商户短信增加', 'smsshop/create', 742, 2, 1),
(731, '常用设置', '', 2, 2, 1),
(734, '登录与注册', 'setting/register', 731, 2, 1),
(738, '充值/提现/转账管理', 'setting/cash', 731, 4, 1),
(740, '常用功能设置', 'setting/config', 731, 1, 1),
(1118, '拼团管理', 'group/index', 1115, 3, 1),
(775, '图片上传设置', '', 2, 4, 1),
(776, '七牛云配置', 'upset/index', 775, 1, 1),
(781, '商家列表', 'booking/index', 646, 1, 1),
(823, '大于短信发送记录', 'dayusms/index', 158, 2, 1),
(824, '短信宝发送记录', 'smsbao/index', 158, 4, 1),
(828, '支付设置', 'setting/pay', 731, 6, 1),
(831, '商家等级管理', '', 3, 2, 1),
(832, '商家等级列表', 'shopgrade/index', 831, 1, 1),
(833, '等级购买列表', 'shopgradeorder/index', 831, 2, 1),
(835, '外卖设置', 'setting/ele', 731, 7, 1),
(851, '会员绑定列表', 'user/binding', 33, 3, 1),
(911, '外卖菜品管理', '', 901, 3, 1),
(901, '外卖', '', 0, 5, 1),
(927, '城市列表', 'city/index', 923, 1, 1),
(928, '区域列表', 'area/index', 923, 2, 1),
(959, '基本配置', '', 916, 1, 1),
(960, '基本配置', 'setting/life', 959, 1, 1),
(961, '商家设置', 'setting/shop', 731, 8, 1),
(1094, '小程序配置', 'setting/wxapp', 1093, 1, 1),
(1093, '小程序配置', '', 1092, 1, 1),
(1092, '小程序', '', 0, 12, 1),
(1025, '广告列表', 'ad/index', 66, 3, 1),
(1039, '小程序配置', '', 1038, 1, 1),
(1040, '小程序配置', 'setting/wxapp', 1039, 1, 1),
(1041, '管理员登录日志', 'admin/log', 9, 4, 1),
(1042, '禁止访问IP段列表', 'admin/ip', 9, 4, 1),
(643, '跑腿', '', 0, 6, 1),
(1057, '订单列表', 'custom/order', 1056, 1, 1),
(1060, '订单列表', 'runningcate/index', 643, 6, 1),
(1061, '分类设置', 'running/set', 643, 2, 1),
(1062, '基本设置', 'setting/running', 643, 1, 1),
(1063, '配送员报表', 'delivery/surface', 523, 5, 1),
(1064, '外卖基本设置', '', 901, 1, 1),
(1065, '外卖设置', 'setting/ele', 1064, 1, 1),
(1075, '配送员尾款订单', 'delivery/tail', 523, 6, 1),
(1078, '跑腿分类列表', 'runningcate/index', 1061, 2, 1),
(1080, '订单列表', 'running/index', 1060, 1, 1),
(1081, '学校列表', '', 643, 3, 1),
(1082, '配送员管理', '', 643, 4, 1),
(1083, '学校列表', 'running/school', 1081, 1, 1),
(1084, '配送员列表', 'running/delivery', 1082, 1, 1),
(1085, '餐饮订单列表', 'running/product', 1060, 2, 1),
(1086, '跑腿地址', '', 643, 5, 1),
(1087, '跑腿地址列表', 'running/addr', 1086, 1, 1),
(1097, '模板消息记录', 'weixinmsg/index', 216, 5, 1),
(1096, '统计列表', 'running/tongji', 1095, 1, 1),
(1095, '订单统计', '', 643, 7, 1),
(1125, '敏感词过滤', 'sensitive/index', 49, 5, 1),
(1126, '模板消息配置', 'weixintmpl/index', 216, 4, 1);

-- --------------------------------------------------------

--
-- 表的结构 `tu_paddlist`
--

CREATE TABLE IF NOT EXISTS `tu_paddlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `level` tinyint(3) unsigned DEFAULT '0' COMMENT '地域等级',
  `upid` mediumint(8) unsigned DEFAULT '0' COMMENT '上级地域',
  `displayorder` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `idx_upid` (`upid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='地址库' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_payment`
--

CREATE TABLE IF NOT EXISTS `tu_payment` (
  `payment_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL COMMENT 'school_id',
  `name` varchar(32) DEFAULT NULL,
  `logo` varchar(32) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `mobile_logo` varchar(32) DEFAULT NULL,
  `contents` varchar(1024) DEFAULT NULL,
  `setting` text,
  `is_mobile_only` tinyint(1) DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT '0',
  `error_intro` varchar(64) DEFAULT NULL COMMENT '微信支付错误说明',
  PRIMARY KEY (`payment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `tu_payment`
--

INSERT INTO `tu_payment` (`payment_id`, `school_id`, `name`, `logo`, `code`, `mobile_logo`, `contents`, `setting`, `is_mobile_only`, `is_open`, `error_intro`) VALUES
(1, NULL, '小程序支付', 'wxapp.png', 'wxapp', 'wxapp_mobile.png', '认证服务号并必须绑定本网站并设置好支付授权目录', 'a:5:{s:5:"appid";s:18:"wx9d0412b0183467da";s:9:"appsecret";s:32:"679b6245ff1f88818f87a5364ab17aa1";s:5:"mchid";s:10:"1498926702";s:6:"appkey";s:32:"4096c8125344c65a6fbfbc50e95b6018";s:6:"safety";s:6:"******";}', 1, 1, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `tu_payment_logs`
--

CREATE TABLE IF NOT EXISTS `tu_payment_logs` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL COMMENT '学校ID',
  `user_id` int(11) DEFAULT '0',
  `type` enum('thread','money','ele','group','pinche','running','shop') DEFAULT 'running' COMMENT '支付类型',
  `types` varchar(32) DEFAULT NULL COMMENT '订单类型2',
  `info` varchar(32) DEFAULT NULL,
  `order_id` int(11) DEFAULT '0',
  `order_ids` text COMMENT '如果该支付方式支持多个订单（合并付款）',
  `code` varchar(32) DEFAULT NULL,
  `need_pay` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `pay_time` int(11) DEFAULT NULL,
  `pay_ip` varchar(15) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT '0',
  `return_order_id` varchar(32) DEFAULT NULL COMMENT '返回订单id',
  `return_trade_no` varchar(32) DEFAULT NULL COMMENT '返回交易号',
  `return_code` varchar(32) DEFAULT NULL COMMENT '返回状态码',
  `return_msg` varchar(32) DEFAULT NULL COMMENT '返回说明',
  `return_date` varchar(32) DEFAULT NULL COMMENT '返回支付时间',
  `out_refund_no` varchar(32) DEFAULT NULL COMMENT '退款单号',
  `refund_id` varchar(32) DEFAULT NULL COMMENT '微信退款单号',
  `refund_fee` varchar(32) DEFAULT NULL COMMENT '退款金额',
  `refund_info` varchar(256) DEFAULT NULL COMMENT '退款说明',
  `settlement_refund_fee` varchar(32) DEFAULT NULL COMMENT '应结退款金额',
  `refund_time` varchar(32) DEFAULT NULL COMMENT '退款时间',
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_pinche`
--

CREATE TABLE IF NOT EXISTS `tu_pinche` (
  `pinche_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `log_id` int(11) DEFAULT NULL COMMENT '支付日志ID',
  `cate_id` int(10) DEFAULT NULL,
  `city_id` int(10) DEFAULT NULL,
  `area_id` int(10) DEFAULT NULL,
  `community_id` int(10) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `photo` varchar(128) DEFAULT NULL,
  `start_time` date DEFAULT NULL,
  `start_time_more` varchar(32) DEFAULT NULL,
  `goplace` varchar(64) DEFAULT NULL COMMENT '出发地',
  `toplace` varchar(64) DEFAULT NULL COMMENT '到达地',
  `middleplace` varchar(64) DEFAULT NULL COMMENT '途径地',
  `num` int(11) DEFAULT '1' COMMENT '拼车人数',
  `num_1` varchar(80) DEFAULT NULL,
  `num_2` varchar(80) DEFAULT NULL,
  `num_3` varchar(80) DEFAULT NULL,
  `num_4` varchar(80) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL COMMENT '联系人',
  `mobile` varchar(11) DEFAULT NULL,
  `details` varchar(256) DEFAULT NULL,
  `views` int(11) DEFAULT '0' COMMENT '浏览量',
  `money` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  `lat` varchar(15) DEFAULT NULL,
  `lng` varchar(15) DEFAULT NULL,
  `star_lat` varchar(15) DEFAULT NULL COMMENT '出发经度',
  `star_lng` varchar(15) DEFAULT NULL COMMENT '出发纬度',
  `end_lat` varchar(15) DEFAULT NULL COMMENT '结束经度',
  `end_lng` varchar(15) DEFAULT NULL COMMENT '结束纬度',
  `closed` tinyint(1) DEFAULT '0',
  `top_time` int(11) DEFAULT NULL COMMENT '置顶时间',
  `create_time` int(10) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`pinche_id`),
  UNIQUE KEY `pinche_id` (`pinche_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_push`
--

CREATE TABLE IF NOT EXISTS `tu_push` (
  `push_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` int(11) DEFAULT NULL COMMENT '类别',
  `type` int(11) DEFAULT NULL COMMENT '类型',
  `user_id` int(10) DEFAULT NULL,
  `rank_id` int(10) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL COMMENT '商家等级ID',
  `title` varchar(128) DEFAULT NULL,
  `intro` varchar(128) DEFAULT NULL COMMENT '内容',
  `url` varchar(128) DEFAULT NULL COMMENT '链接',
  `create_time` int(11) DEFAULT NULL,
  `push_num` int(11) DEFAULT '0' COMMENT '人数',
  `push_time` varchar(32) NOT NULL COMMENT '推送时间',
  `is_push` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`push_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_register_ip`
--

CREATE TABLE IF NOT EXISTS `tu_register_ip` (
  `ip_id` int(10) NOT NULL AUTO_INCREMENT,
  `ip` varchar(30) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `is_lock` int(11) NOT NULL,
  KEY `ip` (`ip`),
  KEY `ip_id` (`ip_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_role`
--

CREATE TABLE IF NOT EXISTS `tu_role` (
  `role_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT NULL COMMENT '角色类型',
  `school_id` int(11) DEFAULT NULL COMMENT '分站',
  `city_id` int(11) DEFAULT NULL COMMENT '城市ID',
  `area_id` int(11) DEFAULT NULL COMMENT '地区ID',
  `business_id` int(11) DEFAULT NULL COMMENT '商圈ID',
  `role_name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `tu_role`
--

INSERT INTO `tu_role` (`role_id`, `type`, `school_id`, `city_id`, `area_id`, `business_id`, `role_name`) VALUES
(1, 1, NULL, 1, 0, 0, '超级管理员'),
(3, 2, 1, 58, 0, 0, '分站');

-- --------------------------------------------------------

--
-- 表的结构 `tu_role_maps`
--

CREATE TABLE IF NOT EXISTS `tu_role_maps` (
  `role_id` smallint(5) DEFAULT NULL,
  `menu_id` smallint(5) DEFAULT NULL,
  UNIQUE KEY `role_id` (`role_id`,`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tu_role_maps`
--

INSERT INTO `tu_role_maps` (`role_id`, `menu_id`) VALUES
(3, 34),
(3, 189),
(3, 203),
(3, 206),
(3, 510),
(3, 537),
(3, 715),
(3, 1025),
(3, 1077),
(3, 1078),
(3, 1080),
(3, 1083),
(3, 1084),
(3, 1085),
(3, 1087),
(3, 1096),
(3, 1098);

-- --------------------------------------------------------

--
-- 表的结构 `tu_running`
--

CREATE TABLE IF NOT EXISTS `tu_running` (
  `running_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL COMMENT '学校ID',
  `Code` varchar(32) DEFAULT NULL,
  `files` varchar(256) DEFAULT NULL COMMENT '文件',
  `user_id` int(10) DEFAULT '0',
  `is_ele_pei` tinyint(1) DEFAULT '0' COMMENT '0网站接单1商家接单',
  `appoint_delivery_id` int(11) DEFAULT '0' COMMENT '指定配送员ID',
  `cid` int(11) unsigned NOT NULL COMMENT '配送员ID',
  `delivery_id` int(11) DEFAULT '0',
  `city_id` int(10) DEFAULT '0',
  `cate_id` int(11) DEFAULT NULL,
  `text1` varchar(128) DEFAULT NULL,
  `text2` varchar(128) DEFAULT NULL,
  `text3` varchar(128) DEFAULT NULL,
  `text4` varchar(32) DEFAULT NULL,
  `text5` varchar(32) DEFAULT NULL,
  `num1` int(11) DEFAULT '0',
  `num2` int(11) DEFAULT '0',
  `select1` int(11) DEFAULT NULL,
  `select2` int(11) DEFAULT NULL,
  `select3` int(11) DEFAULT NULL,
  `select4` int(11) DEFAULT NULL,
  `select5` int(11) DEFAULT NULL,
  `tag` varchar(128) DEFAULT NULL,
  `title` varchar(512) DEFAULT NULL,
  `thumb` text NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `addr` varchar(255) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `price` int(11) NOT NULL COMMENT '价格',
  `freight` int(11) NOT NULL COMMENT '运费，后台设置',
  `need_pay` int(11) NOT NULL COMMENT '实际付款',
  `lat` varchar(15) DEFAULT NULL,
  `lng` varchar(15) DEFAULT NULL,
  `lbs_addr` varchar(60) NOT NULL COMMENT 'lbs地址',
  `IsSecret` varchar(32) DEFAULT NULL,
  `Serial` varchar(32) DEFAULT NULL COMMENT '系列',
  `Weight` varchar(32) DEFAULT '5' COMMENT '重量',
  `coupon_id` int(11) DEFAULT NULL COMMENT '优惠券ID',
  `coupon_price` int(11) DEFAULT '0' COMMENT '优惠券优惠价格',
  `download_coupon_id` int(11) DEFAULT NULL COMMENT '红包ID',
  `ExpectTime` varchar(32) DEFAULT NULL COMMENT '预期时间',
  `ExpiredMinutes` varchar(32) DEFAULT NULL COMMENT '报时',
  `GroupId` int(11) DEFAULT NULL COMMENT '群主ID',
  `LimitDelivererGender` tinyint(1) DEFAULT '0' COMMENT '限制男女接单',
  `Money` int(11) DEFAULT '0' COMMENT '总价',
  `MoneyTip` int(11) DEFAULT '0' COMMENT '消费',
  `MoneyFreight` int(11) DEFAULT '0' COMMENT '运费',
  `MoneyFreightFullMoney` int(11) DEFAULT '0' COMMENT '满减配送费',
  `MoneyPayment` int(11) DEFAULT '0' COMMENT '实际支付',
  `Remark` varchar(512) DEFAULT NULL COMMENT '说明',
  `ShopId` int(11) DEFAULT NULL COMMENT '商家ID',
  `Type` int(1) DEFAULT '0' COMMENT '类型',
  `Stype` int(11) DEFAULT NULL,
  `Role` int(1) DEFAULT '0' COMMENT '是否垫付',
  `startAddress` text,
  `endAddress` text,
  `latitude` varchar(32) DEFAULT NULL COMMENT '进度',
  `longitude` varchar(32) DEFAULT NULL COMMENT '纬度',
  `dlatitude` varchar(32) DEFAULT NULL,
  `dlongitude` varchar(32) DEFAULT NULL,
  `OrderStatus` int(11) DEFAULT '1' COMMENT '1待付款',
  `orderType` tinyint(1) DEFAULT '1' COMMENT '1配送2自提',
  `CreatedTime` varchar(32) DEFAULT NULL,
  `content` varchar(256) DEFAULT NULL COMMENT '完成时候内容',
  `labels` varchar(256) DEFAULT NULL COMMENT '完成时候标签',
  `status` tinyint(1) DEFAULT '0' COMMENT '0未付款，1已付款，2配送中，3已完成配送 8用户已确认',
  `closed` tinyint(1) DEFAULT '0',
  `create_time` int(10) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `pay_time` varchar(32) DEFAULT NULL,
  `update_time` int(20) DEFAULT NULL,
  `delivery_end_time` varchar(16) DEFAULT NULL,
  `cancel_time` varchar(16) DEFAULT NULL COMMENT '取消时间',
  `end_time` int(20) NOT NULL,
  `LastGiveupId` int(11) DEFAULT NULL,
  `LastGiveupCause` varchar(256) DEFAULT NULL COMMENT '跑腿人员放弃原因',
  `LastGiveupTime` varchar(15) DEFAULT NULL COMMENT '跑腿人员放弃时间',
  `OrderRefundInfo` varchar(64) DEFAULT NULL COMMENT '退款失败说明',
  `ShipperCode` varchar(32) DEFAULT NULL COMMENT '快递公司编码',
  `OrderCode` varchar(32) DEFAULT NULL COMMENT '单号',
  `PrintingInfo` varchar(256) DEFAULT NULL COMMENT '返回说明',
  `PrintingTime` varchar(16) DEFAULT NULL COMMENT '打印时间',
  `IsPrinting` tinyint(1) DEFAULT '0' COMMENT '是否打印',
  `isPrint` tinyint(1) DEFAULT '0' COMMENT '网络打印机状态',
  `isPrintInfo` varchar(256) DEFAULT NULL COMMENT '打印回调说明',
  `is_ticket_printing` tinyint(1) DEFAULT '0' COMMENT '打印状态',
  `is_ticket_printing_time` varchar(16) DEFAULT NULL COMMENT '打印时间',
  `is_xujia` tinyint(1) DEFAULT '0' COMMENT '1虚假',
  PRIMARY KEY (`running_id`),
  KEY `running_id` (`running_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=264 ;

--
-- 转存表中的数据 `tu_running`
--

INSERT INTO `tu_running` (`running_id`, `school_id`, `Code`, `files`, `user_id`, `is_ele_pei`, `appoint_delivery_id`, `cid`, `delivery_id`, `city_id`, `cate_id`, `text1`, `text2`, `text3`, `text4`, `text5`, `num1`, `num2`, `select1`, `select2`, `select3`, `select4`, `select5`, `tag`, `title`, `thumb`, `name`, `addr`, `mobile`, `price`, `freight`, `need_pay`, `lat`, `lng`, `lbs_addr`, `IsSecret`, `Serial`, `Weight`, `coupon_id`, `coupon_price`, `download_coupon_id`, `ExpectTime`, `ExpiredMinutes`, `GroupId`, `LimitDelivererGender`, `Money`, `MoneyTip`, `MoneyFreight`, `MoneyFreightFullMoney`, `MoneyPayment`, `Remark`, `ShopId`, `Type`, `Stype`, `Role`, `startAddress`, `endAddress`, `latitude`, `longitude`, `dlatitude`, `dlongitude`, `OrderStatus`, `orderType`, `CreatedTime`, `content`, `labels`, `status`, `closed`, `create_time`, `create_ip`, `pay_time`, `update_time`, `delivery_end_time`, `cancel_time`, `end_time`, `LastGiveupId`, `LastGiveupCause`, `LastGiveupTime`, `OrderRefundInfo`, `ShipperCode`, `OrderCode`, `PrintingInfo`, `PrintingTime`, `IsPrinting`, `isPrint`, `isPrintInfo`, `is_ticket_printing`, `is_ticket_printing_time`, `is_xujia`) VALUES
(48, 2, 'GPMGHWYSFEGMJUWEMIQOVRYLQBGHEBHR', NULL, 1, 0, 0, 0, 2, 0, 4, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '测地辅导工作', '', '经纬度(13654125541)', '56ass6', '13654125541', 0, 20, 20, '23.129163', '113.264435', '', NULL, NULL, '5', NULL, 0, NULL, NULL, '1440', NULL, 0, 0, 0, 20, 0, 20, '测地辅导工作', NULL, 2, 4, 0, 'a:4:{s:9:"AddressId";s:2:"17";s:13:"IsUserAddress";b:1;s:7:"Address";s:22:"经纬度(13654125541)";s:11:"Description";s:6:"56ass6";}', 'a:4:{s:9:"AddressId";s:2:"17";s:13:"IsUserAddress";b:0;s:7:"Address";s:22:"经纬度(13654125541)";s:11:"Description";s:6:"56ass6";}', '23.129163', '113.264435', '29.540077892752', '108.77340549829', 128, 1, '2019-04-11 12:25:21 ', '自动完成订单', '自动完成订单', 1, 0, 1554956721, '222.181.205.135', '1554956745', 1554966025, '1554966356', NULL, 1555154501, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, 0),
(47, 2, 'THSPNLMHCPBEPSWEBRRPUNUSOTVEEYKQ', NULL, 1, 0, 0, 0, 2, 0, 1, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '测试下单', '', '经纬度(13654125541)', '56ass6', '13654125541', 0, 20, 20, '29.534393', '108.766727', '', NULL, NULL, '5', NULL, 0, NULL, NULL, '1440', NULL, 0, 0, 0, 20, 0, 20, '测试下单', NULL, 2, 1, 0, 'a:6:{s:9:"AddressId";i:-1;s:13:"IsUserAddress";b:0;s:7:"Address";s:12:"当前位置";s:11:"Description";s:39:"重庆市黔江区西沙步行街115号";s:9:"Longitude";d:108.77120225694445;s:8:"Latitude";d:108.77120225694445;}', 'a:4:{s:9:"AddressId";s:2:"17";s:13:"IsUserAddress";b:1;s:7:"Address";s:22:"经纬度(13654125541)";s:11:"Description";s:6:"56ass6";}', '29.534393', '108.766727', '29.540077892752', '108.77340549829', 16, 1, '2019-04-11 12:19:32 ', NULL, NULL, 1, 0, 1554956372, '222.181.205.135', '1554956606', 1554966427, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `tu_running_cate`
--

CREATE TABLE IF NOT EXISTS `tu_running_cate` (
  `cate_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL COMMENT '学校ID',
  `channel_id` tinyint(3) DEFAULT '0',
  `cate_name` varchar(32) DEFAULT NULL,
  `Detail` varchar(32) DEFAULT NULL,
  `ErrandType` int(11) DEFAULT '0',
  `DefaultRemark` varchar(32) DEFAULT NULL,
  `Url` varchar(256) DEFAULT NULL,
  `Tag` varchar(32) DEFAULT NULL,
  `Remark` varchar(32) DEFAULT NULL,
  `Src` varchar(246) DEFAULT NULL,
  `price` int(11) DEFAULT '0' COMMENT '分类扣费',
  `price1` int(11) DEFAULT '0' COMMENT '购买价格',
  `num` int(11) DEFAULT '0' COMMENT '信息数冗余',
  `text1` varchar(32) DEFAULT NULL COMMENT '文本字段一',
  `text2` varchar(32) DEFAULT NULL COMMENT '文本字段2',
  `text3` varchar(32) DEFAULT NULL,
  `text4` varchar(32) DEFAULT NULL,
  `text5` varchar(32) DEFAULT NULL,
  `num1` varchar(32) DEFAULT NULL,
  `num2` varchar(32) DEFAULT NULL COMMENT '5个text字段，如果text 字段没有设置那么就不启用',
  `unit1` varchar(32) DEFAULT NULL,
  `unit2` varchar(32) DEFAULT NULL,
  `select1` varchar(32) DEFAULT NULL,
  `select2` varchar(32) DEFAULT NULL,
  `select3` varchar(32) DEFAULT NULL,
  `select4` varchar(32) DEFAULT NULL COMMENT '多选1',
  `select5` varchar(32) DEFAULT NULL COMMENT '多选2',
  `rate` int(11) DEFAULT '0',
  `orderby` tinyint(3) DEFAULT '100',
  `is_hot` tinyint(1) DEFAULT '0',
  `is_show` tinyint(1) DEFAULT '0',
  `is_system` tinyint(1) DEFAULT '0',
  `onMoneyTap` tinyint(1) DEFAULT '0',
  `onExpressFeeLink` tinyint(1) DEFAULT '0',
  `onExpressFeeLinkName` varchar(256) DEFAULT NULL,
  `onExpressFeeLinkId` int(11) DEFAULT NULL,
  `onFile` tinyint(1) DEFAULT '0',
  `ErrandTimeRangeDays` varchar(11) DEFAULT '0',
  `ErrandTimeRangeBegin` varchar(11) DEFAULT '8:00',
  `ErrandTimeRangeEnd` varchar(11) DEFAULT '22:00',
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23336 ;

--
-- 转存表中的数据 `tu_running_cate`
--

INSERT INTO `tu_running_cate` (`cate_id`, `school_id`, `channel_id`, `cate_name`, `Detail`, `ErrandType`, `DefaultRemark`, `Url`, `Tag`, `Remark`, `Src`, `price`, `price1`, `num`, `text1`, `text2`, `text3`, `text4`, `text5`, `num1`, `num2`, `unit1`, `unit2`, `select1`, `select2`, `select3`, `select4`, `select5`, `rate`, `orderby`, `is_hot`, `is_show`, `is_system`, `onMoneyTap`, `onExpressFeeLink`, `onExpressFeeLinkName`, `onExpressFeeLinkId`, `onFile`, `ErrandTimeRangeDays`, `ErrandTimeRangeBegin`, `ErrandTimeRangeEnd`) VALUES
(5, 1, 1, '游戏', '打游戏', 0, '标记', '/pages/errand/apply/index?type=5&remark=打游戏', '标签', '打游戏', NULL, 23, 68, 0, '新旧程度', '文本字段2', '文本字段3', '文本字段4', '文本字段5', '原价', '现价', '元', '元', '供求', '品牌', '价格', '单选字段4', '单选字段5', 0, 5, 1, 0, 0, 0, 0, NULL, NULL, 0, '0', '8:00', '22:00'),
(1, 2, 1, '取件', '数码产品', 0, '标记', '/pages/errand/apply/index?type=1&remark=请直接将取件短信粘贴此处。示例：【菜鸟驿站】取件码8888', '标签', '请直接将取件短信粘贴此处。示例：【菜鸟驿站】取件码8888', NULL, 0, 0, 0, '新旧程度', '', '', '', '', '原价', '现价', '', '', '供求', '分类', '价格', '', '', 0, 1, 0, 0, 0, 0, 0, NULL, NULL, 0, '0', '8:00', '22:00'),
(2, 2, 1, '带饭', '带饭点我', 0, '标记', '/pages/errand/apply/index?type=2&remark=带饭', '标签', '', NULL, 1200, 68, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 2, 0, 0, 0, 0, 0, NULL, NULL, 0, '0', '8:00', '22:00'),
(3, 1, 1, '奶茶', '买奶茶', 0, '标记', '/pages/shop/_/index?type=1', '', '提示说明', NULL, 1, 1, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 3, 0, 0, 0, 0, 0, NULL, NULL, 0, '0', '8:00', '22:00'),
(4, 2, 1, '辅导', '辅导', 0, '标记', '/pages/errand/apply/index?type=4&remark=辅导作业', '', '提示说明', NULL, 100, 100, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 4, 0, 0, 0, 0, 0, NULL, NULL, 0, '0', '8:00', '22:00'),
(6, 1, 1, '搬运', '搬运东西', 0, '标记', '/pages/errand/apply/index?type=6&remark=搬运列子', '', '', NULL, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 6, 0, 0, 0, 0, 0, NULL, NULL, 0, '0', '8:00', '22:00'),
(7, 1, 1, '打印', '课件论文', 0, '标记', '/pages/errand/apply/index?type=7&remark=打印东西', '', '', NULL, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 7, 0, 0, 0, 0, 0, NULL, NULL, 0, '0', '8:00', '22:00'),
(1024, 1, 1, '全能', '无所不能', 0, '标记', '/pages/errand/apply/index?type=1024', '', '', NULL, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 127, 0, 0, 0, 0, 0, NULL, NULL, 0, '0', '8:00', '22:00'),
(23334, 1, 9, '兼职', '不定时更新兼职，项目欢迎大家报名', 0, '圆通快递', '/pages/errand/apply/index?type=1&remark=搬运列子', '快递', '提示说明', NULL, 12000, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, '0', '8:00', '22:00'),
(23335, 2, 1, '新版', '介绍', 0, '标记', '/pages/errand/apply/index?type=23335&remark=新版', '快递', '提示说明', NULL, 100, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2, 0, 0, 0, 0, 0, NULL, NULL, 0, '0', '8:00', '22:00');

-- --------------------------------------------------------

--
-- 表的结构 `tu_running_cate_attr`
--

CREATE TABLE IF NOT EXISTS `tu_running_cate_attr` (
  `attr_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` smallint(5) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `attr_name` varchar(32) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT '100',
  PRIMARY KEY (`attr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2692 ;

--
-- 转存表中的数据 `tu_running_cate_attr`
--

INSERT INTO `tu_running_cate_attr` (`attr_id`, `cate_id`, `type`, `attr_name`, `orderby`) VALUES
(1, 1, 'select1', '供应', 1),
(2, 1, 'select1', '求购', 2),
(3, 1, 'select2', '苹果', 2),
(4, 1, 'select2', '三星', 3),
(5, 1, 'select2', '魅族', 4),
(6, 1, 'select2', 'htc', 5),
(7, 1, 'select3', '500元以下', 2),
(8, 1, 'select3', '500-1000元', 3),
(9, 1, 'select3', '1000-2000元', 4),
(10, 1, 'select3', '2000-3000元', 5),
(11, 1, 'select3', '3000-4000元', 6),
(12, 1, 'select3', '4000-5000元', 7),
(13, 1, 'select3', '5000元以上', 8),
(1705, 67, 'select2', '2000元以下', 2),
(15, 2, 'select1', '供应', 1),
(16, 2, 'select1', '求购', 2),
(36, 2, 'select2', 'MP3/MP4', 4),
(37, 2, 'select2', '镜头/配件', 5),
(38, 2, 'select2', '其他', 6),
(34, 2, 'select2', '相机', 2),
(35, 2, 'select2', '手机及其配件', 3),
(24, 3, 'select1', '供应', 1),
(25, 3, 'select1', '求购', 2),
(26, 3, 'select3', '50以下', 2),
(27, 3, 'select3', '50—150', 3),
(28, 3, 'select3', '150—300', 4),
(29, 3, 'select3', '300—500', 5),
(30, 3, 'select3', '500—1000', 6),
(31, 3, 'select3', '1000以上', 7),
(32, 4, 'select1', '供应', 1),
(33, 4, 'select1', '求购', 2),
(39, 2, 'select3', '200以下', 2),
(40, 2, 'select3', '200—500', 3),
(41, 2, 'select3', '500—1000', 4),
(42, 2, 'select3', '1000—2000', 5),
(43, 2, 'select3', '2000以上', 6),
(44, 4, 'select2', '空调', 2),
(45, 4, 'select2', '冰箱/冰柜', 3),
(46, 4, 'select2', '厨房电器', 4),
(47, 4, 'select2', '电视机', 5),
(48, 4, 'select2', '居家电器', 6),
(49, 4, 'select2', '洗衣机', 7),
(50, 4, 'select2', '卫浴/护理器', 8),
(51, 4, 'select2', '音响电器', 9),
(52, 4, 'select2', '其它', 10),
(53, 4, 'select3', '200以下', 2),
(54, 4, 'select3', '200—500', 3),
(55, 4, 'select3', '500—800', 4),
(56, 4, 'select3', '800—1200', 5),
(57, 4, 'select3', '1200—1600', 6),
(58, 4, 'select3', '1600—2200', 7),
(59, 4, 'select3', '2200—3000', 8),
(60, 4, 'select3', '3000以上', 9),
(61, 3, 'select2', '健身/游泳', 2),
(62, 3, 'select2', '旅游/景点/酒店', 3),
(63, 3, 'select2', '演出/电影', 4),
(64, 3, 'select2', '超市', 5),
(65, 3, 'select2', '美食', 6),
(66, 3, 'select2', '月饼券', 7),
(91, 3, 'select2', '其它', 8),
(68, 5, 'select1', '供应', 1),
(69, 5, 'select1', '求购', 2),
(70, 5, 'select2', '家居用品', 2),
(71, 5, 'select2', '化妆品', 3),
(72, 5, 'select2', '家纺', 4),
(73, 5, 'select2', '烟酒', 5),
(74, 5, 'select2', '其它', 6),
(75, 5, 'select3', '50以下', 2),
(76, 5, 'select3', '50—150', 3),
(77, 5, 'select3', '150—350', 4),
(78, 5, 'select3', '350—600', 5),
(79, 5, 'select3', '600—1000', 6),
(80, 5, 'select3', '1000—2000', 7),
(81, 5, 'select3', '2000以上', 8),
(82, 1, 'select2', '小米', 6),
(83, 1, 'select2', '诺基亚', 7),
(84, 1, 'select2', '华为', 8),
(85, 1, 'select2', '联想', 9),
(86, 1, 'select2', '中兴', 10),
(87, 1, 'select2', 'oppo', 11),
(88, 1, 'select2', '步步高', 12),
(89, 1, 'select2', '金立', 13),
(90, 1, 'select2', '其它', 14),
(92, 6, 'select1', '供应', 1),
(93, 6, 'select1', '求购', 2),
(94, 6, 'select2', '打印机', 2),
(95, 6, 'select2', '复印/扫描/投影', 3),
(96, 6, 'select2', '文具', 4),
(97, 6, 'select2', '其它', 5),
(98, 6, 'select3', '50以下', 2),
(99, 6, 'select3', '50—150', 3),
(100, 6, 'select3', '150—300', 4),
(101, 6, 'select3', '300—500', 5),
(102, 6, 'select3', '500—800', 6),
(103, 6, 'select3', '800—1200', 7),
(104, 6, 'select3', '1200以上', 8),
(105, 7, 'select1', '供应', 1),
(106, 7, 'select1', '求购', 2),
(120, 7, 'select2', '女装', 2),
(121, 7, 'select2', '男装', 3),
(122, 7, 'select2', '鞋子', 4),
(123, 7, 'select2', '手表', 5),
(124, 7, 'select2', '配饰', 6),
(125, 7, 'select2', '羽绒服', 7),
(126, 7, 'select2', '其他', 8),
(128, 7, 'select3', '30以下', 2),
(129, 7, 'select3', '30—50', 3),
(130, 7, 'select3', '50—100', 4),
(131, 7, 'select3', '100—150', 5),
(132, 7, 'select3', '150—200', 6),
(133, 7, 'select3', '200—300', 7),
(134, 7, 'select3', '300—500', 8),
(135, 7, 'select3', '500以上', 9),
(136, 8, 'select1', '供应', 1),
(137, 8, 'select1', '求购', 2),
(139, 8, 'select2', '运动器材', 2),
(140, 8, 'select2', '图书/音响', 3),
(141, 8, 'select2', '器材', 4),
(142, 8, 'select2', '其它', 5),
(144, 8, 'select3', '300以下', 2),
(145, 8, 'select3', '300—500', 3),
(146, 8, 'select3', '500—800', 4),
(147, 8, 'select3', '800—1200', 5),
(148, 8, 'select3', '1200—1600', 6),
(149, 8, 'select3', '1600以上', 6),
(150, 9, 'select1', '供应', 1),
(151, 9, 'select1', '求购', 2),
(153, 9, 'select2', 'Gateway/捷威', 2),
(154, 9, 'select2', 'QHTF/清华同方', 3),
(155, 9, 'select2', 'Toshiba/东芝', 4),
(156, 9, 'select2', 'ASUS/华硕', 5),
(157, 9, 'select2', 'Dell/戴尔', 6),
(158, 9, 'select2', 'Lenovo/联想', 7),
(159, 9, 'select2', 'Acer/宏碁', 8),
(160, 9, 'select2', 'SAMSUNG/三星', 9),
(161, 9, 'select2', 'Apple/苹果', 10),
(162, 9, 'select2', 'HP/惠普', 11),
(163, 9, 'select2', '联想ThinkPad/IBMA', 12),
(164, 9, 'select2', 'Fujitsu/富士通', 13),
(165, 9, 'select2', '山寨/高仿', 14),
(166, 9, 'select2', 'Haier/海尔', 15),
(167, 9, 'select2', 'BENQ/明基', 16),
(168, 9, 'select2', '日立', 17),
(169, 9, 'select2', 'NEC', 18),
(170, 9, 'select2', 'HASEE/神舟', 19),
(171, 9, 'select2', '方正', 20),
(172, 9, 'select2', '其它', 21),
(174, 9, 'select3', '500以下', 2),
(175, 9, 'select3', '500—800', 3),
(176, 9, 'select3', '800—1200', 4),
(177, 9, 'select3', '1200—1600', 5),
(178, 9, 'select3', '1600—2000', 6),
(179, 9, 'select3', '2000—3000', 7),
(180, 9, 'select3', '3000以上', 8),
(181, 10, 'select1', '供应', 1),
(182, 10, 'select1', '求购', 2),
(184, 10, 'select2', '桌子/椅子/凳子', 2),
(185, 10, 'select2', '床/床垫', 3),
(186, 10, 'select2', '沙发/茶几', 4),
(187, 10, 'select2', '架子', 5),
(188, 10, 'select2', '柜橱', 6),
(189, 10, 'select2', '其它', 7),
(191, 10, 'select3', '100以下', 2),
(192, 10, 'select3', '100—200', 3),
(193, 10, 'select3', '200—400', 4),
(194, 10, 'select3', '400—600', 5),
(195, 10, 'select3', '600—800', 6),
(196, 10, 'select3', '800—1200', 7),
(197, 10, 'select3', '1200以上', 8),
(198, 11, 'select1', '供应', 1),
(199, 11, 'select1', '求购', 2),
(200, 11, 'select2', '移动', 2),
(201, 11, 'select2', '联通', 3),
(202, 11, 'select2', '电信', 4),
(203, 11, 'select2', '小灵通/固话', 5),
(204, 11, 'select2', 'QQ/微信', 6),
(206, 11, 'select2', '流量卡', 7),
(207, 11, 'select2', '游戏帐号', 8),
(208, 11, 'select2', '其它', 9),
(210, 11, 'select3', '20以下', 2),
(211, 11, 'select3', '20—50', 3),
(212, 11, 'select3', '50—100', 4),
(213, 11, 'select3', '100—200', 5),
(214, 11, 'select3', '200—500', 6),
(215, 11, 'select3', '500以上', 7),
(216, 12, 'select1', '供应', 1),
(217, 12, 'select1', '求购', 2),
(219, 12, 'select2', '婴孩服饰', 2),
(220, 12, 'select2', '食品/奶粉', 3),
(221, 12, 'select2', '玩具', 4),
(222, 12, 'select2', '母婴用品', 5),
(223, 12, 'select2', '婴儿床', 6),
(224, 12, 'select2', '婴儿车', 7),
(225, 12, 'select2', '其它', 8),
(227, 12, 'select3', '50以下', 2),
(228, 12, 'select3', '50—100', 3),
(229, 12, 'select3', '100—200', 4),
(230, 12, 'select3', '200—300', 5),
(231, 12, 'select3', '300—500', 6),
(232, 12, 'select3', '500以上', 7),
(233, 13, 'select1', '供应', 1),
(234, 13, 'select1', '求购', 2),
(236, 13, 'select2', '十字绣', 2),
(237, 13, 'select2', '珠宝/玉器/名石', 3),
(238, 13, 'select2', '书画', 4),
(239, 13, 'select2', '钱币/邮票/票证', 5),
(240, 13, 'select2', '古玩', 6),
(241, 13, 'select2', '钟表', 7),
(242, 13, 'select2', '瓷器', 8),
(243, 13, 'select2', '其它', 9),
(245, 13, 'select3', '300以下', 2),
(246, 13, 'select3', '300—500', 3),
(247, 13, 'select3', '500—1000', 4),
(248, 13, 'select3', '1000—2000', 5),
(249, 13, 'select3', '2000—4000', 6),
(250, 13, 'select3', '4000—8000', 7),
(251, 13, 'select3', '8000—12000', 8),
(252, 13, 'select3', '12000—20000', 9),
(253, 13, 'select3', '20000以上', 10),
(254, 14, 'select1', '供应', 1),
(255, 14, 'select1', '求购', 2),
(257, 14, 'select2', 'Gateway/捷威', 2),
(258, 14, 'select2', 'QHTF/清华同方', 3),
(259, 14, 'select2', 'Toshiba/东芝', 4),
(260, 14, 'select2', 'ASUS/华硕', 5),
(261, 14, 'select2', 'Dell/戴尔', 6),
(262, 14, 'select2', 'Lenovo/联想', 7),
(263, 14, 'select2', 'Acer/宏碁', 8),
(264, 14, 'select2', 'SAMSUNG/三星', 9),
(265, 14, 'select2', 'Apple/苹果', 10),
(266, 14, 'select2', 'HP/惠普', 11),
(267, 14, 'select2', '联想ThinkPad/IBMA', 12),
(268, 14, 'select2', 'Fujitsu/富士通', 13),
(269, 14, 'select2', '山寨/高仿', 14),
(270, 14, 'select2', 'Haier/海尔', 15),
(271, 14, 'select2', 'BENQ/明基', 16),
(272, 14, 'select2', '日立', 17),
(273, 14, 'select2', 'NEC', 18),
(274, 14, 'select2', 'HASEE/神舟', 19),
(275, 14, 'select2', '方正', 20),
(276, 14, 'select2', '其它', 21),
(278, 14, 'select3', '300以下', 2),
(279, 14, 'select3', '300—500', 3),
(280, 14, 'select3', '500—1000', 4),
(281, 14, 'select3', '1000—2000', 5),
(282, 14, 'select3', '2000—3000', 6),
(283, 14, 'select3', '3000—5000', 7),
(284, 14, 'select3', '5000以上', 8),
(285, 15, 'select1', '供应', 1),
(286, 15, 'select1', '求购', 2),
(288, 15, 'select2', '苗木/种子', 2),
(289, 15, 'select2', '茶叶/饮品', 3),
(290, 15, 'select2', '肉蛋禽/生鲜', 4),
(291, 15, 'select2', '休闲/保健食品', 5),
(292, 15, 'select2', '粮油/蔬果', 6),
(293, 15, 'select2', '其它', 7),
(295, 15, 'select3', '20以下', 2),
(296, 15, 'select3', '20—50', 3),
(297, 15, 'select3', '50—200', 4),
(298, 15, 'select3', '200—500', 5),
(299, 15, 'select3', '500以上', 6),
(300, 16, 'select1', '供应', 1),
(301, 16, 'select1', '强哥', 2),
(303, 16, 'select2', '机械设备', 2),
(304, 16, 'select2', '工程车械', 3),
(305, 16, 'select2', '物品租赁', 4),
(306, 16, 'select2', '灯光影响', 5),
(307, 16, 'select2', '场地租赁', 6),
(308, 16, 'select2', '服装租赁', 7),
(309, 16, 'select2', '办公用品租赁', 8),
(310, 16, 'select2', '家具租赁', 9),
(311, 16, 'select2', '汽车租赁', 10),
(312, 16, 'select2', '脚手架租赁', 11),
(313, 16, 'select2', '其它', 12),
(315, 16, 'select3', '200以下', 2),
(316, 16, 'select3', '200—500', 3),
(317, 16, 'select3', '500—1000', 4),
(318, 16, 'select3', '1000—2000', 5),
(319, 16, 'select3', '2000—5000', 6),
(320, 16, 'select3', '5000以上', 7),
(321, 17, 'select1', '供应', 1),
(322, 17, 'select1', '求购', 2),
(324, 17, 'select2', '纽曼', 2),
(325, 17, 'select2', '摩托罗拉', 3),
(326, 17, 'select2', 'ThinkPad', 4),
(327, 17, 'select2', '索尼', 5),
(328, 17, 'select2', '戴尔', 6),
(329, 17, 'select2', '华硕', 7),
(330, 17, 'select2', '宏碁', 8),
(331, 17, 'select2', '汉王', 9),
(332, 17, 'select2', '爱可视', 10),
(333, 17, 'select2', 'e途', 11),
(334, 17, 'select2', 'HTC', 12),
(335, 17, 'select2', 'KindleFire', 13),
(336, 17, 'select2', '山寨/高仿', 14),
(337, 17, 'select2', '华为', 15),
(338, 17, 'select2', '三星', 16),
(339, 17, 'select2', '优派', 17),
(340, 17, 'select2', 'Google', 18),
(341, 17, 'select2', 'iPad', 19),
(342, 17, 'select2', '联想', 20),
(343, 17, 'select2', '其它', 21),
(345, 17, 'select3', '200以下', 2),
(346, 17, 'select3', '200—500', 3),
(347, 17, 'select3', '500—800', 4),
(348, 17, 'select3', '800—1200', 5),
(349, 17, 'select3', '1200—1800', 6),
(350, 17, 'select3', '1800—2500', 7),
(351, 17, 'select3', '2500以上', 8),
(352, 18, 'select1', '供应', 1),
(353, 18, 'select1', '求购', 2),
(355, 18, 'select2', '械仪器仪表', 2),
(356, 18, 'select2', '印刷/塑料机', 3),
(357, 18, 'select2', '发电/化工设备', 4),
(358, 18, 'select2', '纺织/农业/木工', 5),
(359, 18, 'select2', '食品机械', 6),
(360, 18, 'select2', '工程机械机床', 7),
(361, 18, 'select2', '其它', 8),
(363, 18, 'select3', '300以下', 2),
(364, 18, 'select3', '300—600', 3),
(365, 18, 'select3', '600—1000', 4),
(366, 18, 'select3', '1000—1800', 5),
(367, 18, 'select3', '1800—3000', 6),
(368, 18, 'select3', '3000以上', 7),
(369, 19, 'select1', '供应', 1),
(370, 19, 'select1', '求购', 2),
(372, 19, 'select2', '50以下', 2),
(373, 19, 'select2', '50—200', 3),
(374, 19, 'select2', '200—500', 4),
(375, 19, 'select2', '500—1000', 5),
(376, 19, 'select2', '1000以上', 6),
(378, 20, 'select2', '礼品回收', 2),
(379, 20, 'select2', '卡券回收', 3),
(380, 20, 'select2', '办公耗材回收', 4),
(381, 20, 'select2', '数码产品回收', 5),
(382, 20, 'select2', '设备机械回收', 6),
(383, 20, 'select2', '家具回收', 7),
(384, 20, 'select2', '黄金回收', 8),
(385, 20, 'select2', '家电回收', 9),
(386, 20, 'select2', '电脑回收', 10),
(387, 20, 'select2', '废旧物品回收', 11),
(389, 21, 'select1', '2万以下', 2),
(390, 21, 'select1', '2万—5万', 3),
(391, 21, 'select1', '5万—8万', 4),
(392, 21, 'select1', '8万—12万', 5),
(393, 21, 'select1', '12万—18万', 6),
(394, 21, 'select1', '18万—25万', 7),
(395, 21, 'select1', '25万以上', 8),
(397, 21, 'select2', '跑车', 2),
(398, 21, 'select2', 'MPV/商务车', 3),
(399, 21, 'select2', '豪华车', 4),
(400, 21, 'select2', '中型车', 5),
(401, 21, 'select2', 'SUV/越野车', 6),
(402, 21, 'select2', '小型车', 7),
(404, 21, 'select2', '其它', 8),
(406, 21, 'select3', '东风', 2),
(407, 21, 'select3', '奔驰', 3),
(408, 21, 'select3', '标致', 4),
(409, 21, 'select3', '福田', 5),
(410, 21, 'select3', '马自达', 6),
(411, 21, 'select3', '比亚迪', 7),
(412, 21, 'select3', '瑞麒', 8),
(413, 21, 'select3', '长安', 9),
(414, 21, 'select3', '起亚', 10),
(415, 21, 'select3', '福特', 11),
(416, 21, 'select3', '宝马', 12),
(417, 21, 'select3', '奥迪', 13),
(418, 21, 'select3', '丰田', 14),
(419, 21, 'select3', '雪佛兰', 15),
(420, 21, 'select3', '日产', 16),
(421, 21, 'select3', '江淮', 17),
(422, 21, 'select3', '本田', 18),
(423, 21, 'select3', '别克', 19),
(424, 21, 'select3', '现代', 20),
(425, 21, 'select3', '大众', 21),
(426, 21, 'select3', '奇瑞', 22),
(427, 21, 'select3', '长城   ', 23),
(428, 21, 'select3', ' 雪铁龙   ', 24),
(429, 21, 'select3', '中华  ', 25),
(430, 21, 'select3', ' 荣威  ', 26),
(431, 21, 'select3', ' 铃木 ', 27),
(432, 21, 'select3', ' MG ', 42),
(433, 21, 'select3', ' 开瑞   ', 43),
(434, 21, 'select3', '一汽   ', 44),
(435, 21, 'select3', '沃尔沃   ', 45),
(436, 21, 'select3', '金杯', 46),
(437, 21, 'select3', '三菱', 47),
(438, 21, 'select3', '莲花   ', 48),
(439, 21, 'select3', '凯迪拉克    ', 49),
(440, 21, 'select3', '海马   ', 50),
(441, 21, 'select3', '哈飞   ', 51),
(442, 21, 'select3', '长丰     ', 52),
(443, 21, 'select3', '东南    ', 41),
(444, 21, 'select3', '吉利    ', 40),
(445, 21, 'select3', '斯柯达    ', 39),
(446, 21, 'select3', '夏利    ', 28),
(447, 21, 'select3', '昌河   ', 29),
(448, 21, 'select3', '双环   ', 30),
(449, 21, 'select3', '双龙   ', 31),
(450, 21, 'select3', '西安奥拓   ', 32),
(451, 21, 'select3', '斯巴鲁    ', 33),
(452, 21, 'select3', '萨博    ', 34),
(453, 21, 'select3', '中兴   ', 35),
(454, 21, 'select3', '宝骏    ', 36),
(455, 21, 'select3', '众泰   ', 37),
(456, 21, 'select3', 'MINI(迷你)   ', 38),
(457, 21, 'select3', '解放    ', 53),
(458, 21, 'select3', '保时捷    ', 54),
(459, 21, 'select3', '北汽制造    ', 55),
(460, 21, 'select3', '宾利    ', 56),
(461, 21, 'select3', '奔腾   ', 57),
(462, 21, 'select3', '英菲尼迪         ', 58),
(463, 21, 'select3', '吉利英伦   ', 59),
(464, 21, 'select3', '新凯   ', 60),
(465, 21, 'select3', '东风风神     ', 61),
(466, 21, 'select3', '江铃   ', 62),
(467, 21, 'select3', '东风风行    ', 63),
(468, 21, 'select3', '捷豹    ', 64),
(469, 21, 'select3', '菲亚特   ', 65),
(470, 21, 'select3', '克莱斯勒    ', 66),
(471, 21, 'select3', '吉普   ', 67),
(472, 21, 'select3', '吉奥  ', 68),
(473, 21, 'select3', '红旗    ', 69),
(474, 21, 'select3', '华普    ', 70),
(475, 21, 'select3', '华泰   ', 71),
(476, 21, 'select3', '黄海   ', 72),
(477, 21, 'select3', '雷克萨斯    ', 73),
(478, 21, 'select3', '雷诺    ', 74),
(479, 21, 'select3', '欧宝   ', 75),
(480, 21, 'select3', '吉利帝豪   ', 76),
(481, 21, 'select3', '道奇   ', 77),
(482, 21, 'select3', '大迪    ', 78),
(483, 21, 'select3', '讴歌    ', 79),
(484, 21, 'select3', '路虎    ', 80),
(485, 21, 'select3', '力帆     ', 81),
(486, 21, 'select3', '林肯    ', 82),
(487, 21, 'select3', '法拉利    ', 83),
(488, 21, 'select3', '陆风川汽  ', 84),
(489, 21, 'select3', ' 野马   ', 85),
(490, 21, 'select3', '玛莎拉蒂', 86),
(491, 21, 'select3', '兰博基尼', 87),
(492, 21, 'select3', '其他', 88),
(494, 22, 'select1', '2万以下', 2),
(495, 22, 'select1', '2万—4万', 3),
(496, 22, 'select1', '4万—8万', 4),
(497, 22, 'select1', '8万—12万', 5),
(498, 22, 'select1', '12万—18万', 6),
(499, 22, 'select1', '18万—25万', 7),
(500, 22, 'select1', '25万—50万', 8),
(501, 22, 'select1', '50万以上', 9),
(503, 22, 'select2', '小型车', 2),
(504, 22, 'select2', '中型车', 3),
(505, 22, 'select2', 'SUV/越野车', 4),
(506, 22, 'select2', '豪华车', 5),
(507, 22, 'select2', 'MPV/商务车', 6),
(508, 22, 'select2', '跑车', 7),
(509, 22, 'select2', '其它', 8),
(511, 22, 'select3', '奇瑞   ', 46),
(512, 22, 'select3', '大众   ', 55),
(513, 22, 'select3', '现代   ', 56),
(514, 22, 'select3', '别克   ', 58),
(515, 22, 'select3', '本田   ', 59),
(516, 22, 'select3', '江淮   ', 60),
(517, 22, 'select3', '日产   ', 61),
(518, 22, 'select3', '雪佛兰   ', 64),
(519, 22, 'select3', '丰田   ', 54),
(520, 22, 'select3', '奥迪   ', 66),
(521, 22, 'select3', '宝马   ', 65),
(522, 22, 'select3', '福特  ', 62),
(523, 22, 'select3', '起亚  ', 47),
(524, 22, 'select3', '长安   ', 48),
(525, 22, 'select3', '瑞麒   ', 49),
(526, 22, 'select3', '比亚迪    ', 50),
(527, 22, 'select3', '马自达   ', 51),
(528, 22, 'select3', '福田    ', 52),
(529, 22, 'select3', '标致    ', 53),
(530, 22, 'select3', '奔驰   ', 67),
(531, 22, 'select3', '东风   ', 79),
(532, 22, 'select3', '长城   ', 72),
(533, 22, 'select3', '雪铁龙   ', 80),
(534, 22, 'select3', '中华  ', 81),
(535, 22, 'select3', '荣威  ', 82),
(536, 22, 'select3', '铃木  ', 83),
(537, 22, 'select3', 'MG  ', 57),
(538, 22, 'select3', '开瑞   ', 84),
(539, 22, 'select3', '一汽   ', 85),
(540, 22, 'select3', '沃尔沃   ', 86),
(541, 22, 'select3', '金杯   ', 87),
(542, 22, 'select3', '三菱   ', 78),
(543, 22, 'select3', '莲花   ', 77),
(544, 22, 'select3', '凯迪拉克   ', 68),
(545, 22, 'select3', ' 海马  ', 63),
(546, 22, 'select3', ' 哈飞   ', 70),
(547, 22, 'select3', '长丰     ', 71),
(548, 22, 'select3', '东南    ', 73),
(549, 22, 'select3', '吉利    ', 74),
(550, 22, 'select3', '斯柯达    ', 75),
(551, 22, 'select3', '夏利    ', 69),
(552, 22, 'select3', '昌河  ', 44),
(553, 22, 'select3', ' 双环   ', 76),
(554, 22, 'select3', '双龙   ', 45),
(555, 22, 'select3', '西安奥拓   ', 13),
(556, 22, 'select3', '斯巴鲁   ', 14),
(557, 22, 'select3', ' 萨博    ', 15),
(558, 22, 'select3', '中兴   ', 16),
(559, 22, 'select3', '宝骏    ', 17),
(560, 22, 'select3', '众泰   ', 18),
(561, 22, 'select3', 'MINI(迷你)   ', 19),
(562, 22, 'select3', '解放    ', 20),
(563, 22, 'select3', '保时捷    ', 21),
(564, 22, 'select3', '北汽制造    ', 12),
(565, 22, 'select3', '宾利    ', 11),
(566, 22, 'select3', '奔腾   ', 2),
(567, 22, 'select3', '英菲尼迪         ', 3),
(568, 22, 'select3', '吉利英伦   ', 4),
(569, 22, 'select3', '新凯   ', 5),
(570, 22, 'select3', '东风风神     ', 6),
(571, 22, 'select3', '江铃   ', 7),
(572, 22, 'select3', '东风风行    ', 8),
(573, 22, 'select3', '捷豹    ', 9),
(574, 22, 'select3', '菲亚特   ', 10),
(575, 22, 'select3', '克莱斯勒    ', 22),
(576, 22, 'select3', '吉普   ', 23),
(577, 22, 'select3', '吉奥  ', 24),
(578, 22, 'select3', '红旗    ', 36),
(579, 22, 'select3', '华普    ', 37),
(580, 22, 'select3', '华泰   ', 38),
(581, 22, 'select3', '黄海   ', 39),
(582, 22, 'select3', '雷克萨斯    ', 40),
(583, 22, 'select3', '雷诺    ', 41),
(584, 22, 'select3', '欧宝   ', 42),
(585, 22, 'select3', '吉利帝豪   ', 43),
(586, 22, 'select3', '道奇   ', 35),
(587, 22, 'select3', '大迪    ', 34),
(588, 22, 'select3', '讴歌    ', 33),
(589, 22, 'select3', '路虎    ', 25),
(590, 22, 'select3', '力帆     ', 26),
(591, 22, 'select3', '林肯    ', 27),
(592, 22, 'select3', '法拉利   ', 28),
(593, 22, 'select3', ' 陆风川汽   ', 29),
(594, 22, 'select3', '野马   ', 30),
(595, 22, 'select3', '玛莎拉蒂', 31),
(596, 22, 'select3', '拉博基尼', 32),
(597, 22, 'select3', '其它', 88),
(599, 23, 'select1', '2万以下', 2),
(600, 23, 'select1', '2万—5万', 3),
(601, 23, 'select1', '5万—10万', 4),
(602, 23, 'select1', '10万—20万', 5),
(603, 23, 'select1', '20万—30万', 6),
(604, 23, 'select1', '30万以上', 7),
(607, 23, 'select2', '小型车   ', 2),
(608, 23, 'select2', 'SUV/越野车      ', 3),
(609, 23, 'select2', '中型车      ', 4),
(610, 23, 'select2', '豪华车    ', 5),
(611, 23, 'select2', 'MPV/商务车     ', 6),
(612, 23, 'select2', '跑车    ', 7),
(613, 23, 'select2', '其他', 8),
(614, 23, 'select3', '奇瑞    ', 2),
(615, 23, 'select3', '大众   ', 3),
(616, 23, 'select3', '现代   ', 4),
(617, 23, 'select3', '别克   ', 5),
(618, 23, 'select3', '本田   ', 6),
(619, 23, 'select3', '江淮   ', 7),
(620, 23, 'select3', '日产   ', 8),
(621, 23, 'select3', '雪佛兰   ', 9),
(622, 23, 'select3', '丰田   ', 10),
(623, 23, 'select3', '奥迪   ', 11),
(624, 23, 'select3', '宝马   ', 12),
(625, 23, 'select3', '福特  ', 13),
(626, 23, 'select3', '起亚  ', 14),
(627, 23, 'select3', '长安   ', 15),
(628, 23, 'select3', '瑞麒   ', 16),
(629, 23, 'select3', '比亚迪    ', 17),
(630, 23, 'select3', '马自达   ', 18),
(631, 23, 'select3', '福田    ', 19),
(632, 23, 'select3', '标致    ', 20),
(633, 23, 'select3', '奔驰   ', 21),
(634, 23, 'select3', '东风   ', 22),
(635, 23, 'select3', '长城   ', 23),
(636, 23, 'select3', '雪铁龙   ', 24),
(637, 23, 'select3', '中华  ', 25),
(638, 23, 'select3', '荣威  ', 26),
(639, 23, 'select3', '铃木  ', 27),
(640, 23, 'select3', 'MG  ', 28),
(641, 23, 'select3', '开瑞   ', 29),
(642, 23, 'select3', '一汽   ', 30),
(643, 23, 'select3', '沃尔沃   ', 31),
(644, 23, 'select3', '金杯   ', 32),
(645, 23, 'select3', '三菱   ', 33),
(646, 23, 'select3', '莲花   ', 34),
(647, 23, 'select3', '凯迪拉克    ', 35),
(648, 23, 'select3', '海马   ', 36),
(649, 23, 'select3', '哈飞   ', 37),
(650, 23, 'select3', '长丰     ', 38),
(651, 23, 'select3', '东南    ', 39),
(652, 23, 'select3', '吉利    ', 40),
(653, 23, 'select3', '斯柯达    ', 41),
(654, 23, 'select3', '夏利    ', 42),
(655, 23, 'select3', '昌河   ', 43),
(656, 23, 'select3', '双环   ', 44),
(657, 23, 'select3', '双龙   ', 45),
(658, 23, 'select3', '西安奥拓   ', 46),
(659, 23, 'select3', '斯巴鲁    ', 47),
(660, 23, 'select3', '萨博    ', 48),
(661, 23, 'select3', '中兴   ', 49),
(662, 23, 'select3', '宝骏    ', 50),
(663, 23, 'select3', '众泰   ', 51),
(664, 23, 'select3', 'MINI(迷你)   ', 52),
(665, 23, 'select3', '解放    ', 53),
(666, 23, 'select3', '保时捷    ', 54),
(667, 23, 'select3', '北汽制造    ', 55),
(668, 23, 'select3', '宾利    ', 56),
(669, 23, 'select3', '奔腾   ', 57),
(670, 23, 'select3', '英菲尼迪         ', 58),
(671, 23, 'select3', '吉利英伦   ', 59),
(672, 23, 'select3', '新凯   ', 60),
(673, 23, 'select3', '东风风神     ', 61),
(674, 23, 'select3', '江铃   ', 62),
(675, 23, 'select3', '东风风行    ', 63),
(676, 23, 'select3', '捷豹    ', 64),
(677, 23, 'select3', '菲亚特   ', 65),
(678, 23, 'select3', '克莱斯勒    ', 66),
(679, 23, 'select3', '吉普   ', 67),
(680, 23, 'select3', '吉奥  ', 68),
(681, 23, 'select3', '红旗    ', 69),
(682, 23, 'select3', '华普    ', 70),
(683, 23, 'select3', '华泰   ', 71),
(684, 23, 'select3', '黄海   ', 72),
(685, 23, 'select3', '雷克萨斯    ', 73),
(686, 23, 'select3', '雷诺    ', 74),
(687, 23, 'select3', '欧宝   ', 75),
(688, 23, 'select3', '吉利帝豪   ', 76),
(689, 23, 'select3', '道奇   ', 77),
(690, 23, 'select3', '大迪    ', 78),
(691, 23, 'select3', '讴歌    ', 79),
(692, 23, 'select3', '路虎    ', 80),
(693, 23, 'select3', '力帆     ', 81),
(694, 23, 'select3', '林肯    ', 82),
(695, 23, 'select3', '法拉利    ', 83),
(696, 23, 'select3', '陆风川汽   ', 84),
(697, 23, 'select3', '野马   ', 85),
(698, 23, 'select3', '玛莎拉蒂', 86),
(699, 23, 'select3', '兰博基尼', 87),
(700, 23, 'select3', '其他', 88),
(702, 24, 'select1', '电动摩托车', 2),
(703, 24, 'select1', '电动自行车', 3),
(704, 24, 'select1', '电动三轮车', 4),
(705, 24, 'select1', '其它', 5),
(707, 24, 'select2', '爱玛', 2),
(708, 24, 'select2', '新日', 3),
(709, 24, 'select2', '绿源', 4),
(710, 24, 'select2', '立马', 5),
(711, 24, 'select2', '雅迪', 6),
(712, 24, 'select2', '小刀', 7),
(713, 24, 'select2', '其它', 8),
(715, 24, 'select3', '300元以下', 2),
(716, 24, 'select3', '300元—600元', 3),
(717, 24, 'select3', '600元—1000元', 4),
(718, 24, 'select3', '1000元—1500元', 5),
(719, 24, 'select3', '1500元—2000元', 6),
(720, 24, 'select3', '2000元以上', 7),
(722, 25, 'select1', '山地车 ', 2),
(723, 25, 'select1', '死飞    ', 3),
(724, 25, 'select1', '普通车    ', 4),
(725, 25, 'select1', '公路车    ', 5),
(726, 25, 'select1', '折叠车    ', 6),
(727, 25, 'select1', '休闲车    ', 7),
(728, 25, 'select1', '三轮车    ', 8),
(729, 25, 'select1', '折叠童车', 9),
(730, 25, 'select1', '其他', 10),
(732, 25, 'select2', '捷安特   ', 2),
(733, 25, 'select2', '美利达   ', 3),
(734, 25, 'select2', '凤凰   ', 4),
(735, 25, 'select2', '永久   ', 5),
(736, 25, 'select2', '富士达    ', 6),
(737, 25, 'select2', '喜德盛    ', 7),
(738, 25, 'select2', '捷马   ', 8),
(739, 25, 'select2', '飞鸽    ', 9),
(740, 25, 'select2', '飞跃   ', 10),
(741, 25, 'select2', '英克莱  ', 11),
(742, 25, 'select2', '其他', 12),
(744, 25, 'select3', '100元以下', 2),
(745, 25, 'select3', '100元—200元', 3),
(746, 25, 'select3', '200元—500元', 4),
(747, 25, 'select3', '500元—1000元', 5),
(748, 25, 'select3', '1000以上', 6),
(750, 26, 'select1', '自卸车   ', 2),
(751, 26, 'select1', '厢式货车    ', 3),
(752, 26, 'select1', '平板车    ', 4),
(753, 26, 'select1', '半挂车    ', 5),
(754, 26, 'select1', '油罐车    ', 6),
(755, 26, 'select1', '水泥罐车   ', 7),
(756, 26, 'select1', '牵引车  ', 8),
(757, 26, 'select1', '随车吊   ', 9),
(758, 26, 'select1', '其他', 10),
(760, 26, 'select2', '瑞沃奥威   ', 2),
(761, 26, 'select2', '欧曼   ', 3),
(762, 26, 'select2', '解放    ', 4),
(763, 26, 'select2', '江淮   ', 5),
(764, 26, 'select2', '时代金刚   ', 6),
(765, 26, 'select2', '东风天龙   ', 7),
(766, 26, 'select2', '高栏   ', 8),
(767, 26, 'select2', '东风   ', 9),
(768, 26, 'select2', '福田   ', 10),
(769, 26, 'select2', '陕汽德龙       ', 11),
(770, 26, 'select2', '斯太尔   ', 12),
(771, 26, 'select2', '豪沃      ', 13),
(772, 26, 'select2', '红岩    ', 14),
(773, 26, 'select2', '陕汽奥龙   ', 15),
(774, 26, 'select2', '康明斯   ', 16),
(775, 26, 'select2', '悍威   ', 17),
(776, 26, 'select2', '新大威    ', 18),
(777, 26, 'select2', '解放赛龙    ', 19),
(778, 26, 'select2', '跃进   ', 20),
(779, 26, 'select2', '威铃    ', 21),
(780, 26, 'select2', '凯马   ', 22),
(781, 26, 'select2', '中国重汽    ', 23),
(782, 26, 'select2', '江铃   ', 24),
(783, 26, 'select2', '中国重汽豪运   ', 25),
(784, 26, 'select2', '其他', 26),
(786, 26, 'select3', '5万以下', 2),
(787, 26, 'select3', '5万—8万', 3),
(788, 26, 'select3', '8万—12万', 4),
(789, 26, 'select3', '12万—18万', 5),
(790, 26, 'select3', '18万—25万', 6),
(791, 26, 'select3', '25万以上', 7),
(793, 27, 'select1', '踏板', 2),
(794, 27, 'select1', '轻骑', 3),
(795, 27, 'select1', '跑车', 4),
(796, 27, 'select1', '越野', 5),
(797, 27, 'select1', '其它', 6),
(799, 27, 'select2', '建设    ', 2),
(800, 27, 'select2', '雅马哈    ', 3),
(801, 27, 'select2', '本田   ', 4),
(802, 27, 'select2', '铃木  ', 5),
(803, 27, 'select2', '轻骑   ', 6),
(804, 27, 'select2', '豪爵   ', 7),
(805, 27, 'select2', '钱江   ', 8),
(806, 27, 'select2', '光阳   ', 9),
(807, 27, 'select2', '宗申  ', 10),
(808, 27, 'select2', '金城   ', 11),
(809, 27, 'select2', '隆鑫   ', 12),
(810, 27, 'select2', '踏板   ', 13),
(811, 27, 'select2', '巧格   ', 14),
(812, 27, 'select2', '力帆   ', 15),
(813, 27, 'select2', '迅鹰   ', 16),
(814, 27, 'select2', '三阳   ', 17),
(815, 27, 'select2', '川崎   ', 18),
(816, 27, 'select2', '新大洲    ', 19),
(817, 27, 'select2', '林海   ', 20),
(818, 27, 'select2', '凌鹰   ', 21),
(819, 27, 'select2', '哈雷   ', 22),
(820, 27, 'select2', '春风   ', 23),
(821, 27, 'select2', '飞鹰   ', 24),
(822, 27, 'select2', '街车   ', 25),
(823, 27, 'select2', '劲隆       ', 26),
(824, 27, 'select2', '小帅哥    ', 27),
(825, 27, 'select2', '呈捷    ', 28),
(826, 27, 'select2', '大地鹰王    ', 29),
(827, 27, 'select2', '钻豹   ', 30),
(828, 27, 'select2', '龟王   ', 31),
(829, 27, 'select2', '天剑   ', 32),
(830, 27, 'select2', '越野    ', 33),
(831, 27, 'select2', '大阳    ', 34),
(832, 27, 'select2', '祖玛   ', 35),
(833, 27, 'select2', '重庆大运         ', 36),
(834, 27, 'select2', '马杰斯特   ', 37),
(835, 27, 'select2', '公主   ', 38),
(836, 27, 'select2', '大帅哥   ', 39),
(837, 27, 'select2', '太子   ', 40),
(838, 27, 'select2', '高赛   ', 41),
(839, 27, 'select2', '南方   ', 42),
(840, 27, 'select2', '铁马   ', 43),
(841, 27, 'select2', '猎鹰   ', 44),
(842, 27, 'select2', '五羊   ', 45),
(843, 27, 'select2', '邦德   ', 46),
(844, 27, 'select2', '巡洋舰   ', 47),
(845, 27, 'select2', '福喜  ', 48),
(846, 27, 'select2', '小未战    ', 49),
(847, 27, 'select2', '大未战   ', 50),
(848, 27, 'select2', '海王星    ', 51),
(849, 27, 'select2', '幸福   ', 52),
(850, 27, 'select2', '大绵羊    ', 53),
(851, 27, 'select2', '长江   ', 54),
(852, 27, 'select2', '其他', 55),
(854, 27, 'select3', '500元以下', 2),
(855, 27, 'select3', '500元—1000元', 3),
(856, 27, 'select3', '1000元—2000元', 4),
(857, 27, 'select3', '2000元—3000元', 5),
(858, 27, 'select3', '3000元以上', 6),
(861, 28, 'select1', '没车', 2),
(862, 28, 'select1', '有车', 3),
(863, 28, 'select2', '长途拼车', 2),
(864, 28, 'select2', '上下班拼车', 3),
(865, 28, 'select2', '上下学拼车', 4),
(866, 28, 'select2', '其它拼车', 5),
(867, 29, 'select1', '供应', 1),
(868, 29, 'select1', '求购', 2),
(870, 29, 'select2', '配件   ', 2),
(871, 29, 'select2', '影音电器    ', 3),
(872, 29, 'select2', '清洁保养   ', 4),
(873, 29, 'select2', 'GPS   ', 5),
(874, 29, 'select2', '外饰    ', 6),
(875, 29, 'select2', '内饰', 7),
(876, 29, 'select2', '坐垫/脚垫   ', 8),
(877, 29, 'select2', ' 轮胎/轮毂    ', 9),
(878, 29, 'select2', '其他', 10),
(880, 29, 'select3', '50元以下', 2),
(881, 29, 'select3', '50元—150元', 3),
(882, 29, 'select3', '150元—300元', 4),
(883, 29, 'select3', '300元—500元', 5),
(884, 29, 'select3', '500元—1000元', 6),
(885, 29, 'select3', '1000元以上', 7),
(887, 30, 'select1', '吉利   ', 2),
(888, 30, 'select1', '比亚迪    ', 3),
(889, 30, 'select1', '吉利帝豪    ', 4),
(890, 30, 'select1', '奇瑞    ', 5),
(891, 30, 'select1', '大众    ', 6),
(892, 30, 'select1', '荣威    ', 7),
(893, 30, 'select1', 'MG   ', 8),
(894, 30, 'select1', '东风   ', 9),
(895, 30, 'select1', '东风风神    ', 10),
(896, 30, 'select1', '别克        ', 11),
(897, 30, 'select1', '起亚     ', 12),
(898, 30, 'select1', '宝马    ', 13),
(899, 30, 'select1', '福特    ', 14),
(900, 30, 'select1', '现代    ', 15),
(901, 30, 'select1', '铃木    ', 16),
(902, 30, 'select1', '本田    ', 17),
(903, 30, 'select1', '雪佛兰    ', 18),
(904, 30, 'select1', '奥迪   ', 19),
(905, 30, 'select1', '江铃   ', 20),
(906, 30, 'select1', '沃尔沃    ', 21),
(907, 30, 'select1', '解放    ', 22),
(908, 30, 'select1', '开瑞   ', 23),
(909, 30, 'select1', '奔驰    ', 24),
(910, 30, 'select1', '雪铁龙    ', 25),
(911, 30, 'select1', '道奇   ', 26),
(912, 30, 'select1', '江淮    ', 27),
(913, 30, 'select1', '捷豹    ', 28),
(914, 30, 'select1', '北汽制造     ', 29),
(915, 30, 'select1', '一汽    ', 30),
(916, 30, 'select1', '标致    ', 31),
(917, 30, 'select1', '莲花   ', 32),
(918, 30, 'select1', '东风风行   ', 33),
(919, 30, 'select1', '瑞麒   ', 34),
(920, 30, 'select1', '三菱    ', 35),
(921, 30, 'select1', '斯巴鲁   ', 36),
(922, 30, 'select1', '奔腾    ', 37),
(923, 30, 'select1', '日产   ', 38),
(924, 30, 'select1', '斯柯达    ', 39),
(925, 30, 'select1', '萨博    ', 40),
(926, 30, 'select1', '双环    ', 41),
(927, 30, 'select1', '西安奥拓    ', 42),
(928, 30, 'select1', '双龙    ', 43),
(929, 30, 'select1', '保时捷  ', 44),
(930, 30, 'select1', '中兴   ', 45),
(931, 30, 'select1', '中华    ', 46),
(932, 30, 'select1', '众泰    ', 47),
(933, 30, 'select1', '宝骏   ', 48),
(934, 30, 'select1', 'MINI(迷你)   ', 49),
(935, 30, 'select1', '长丰    ', 50),
(936, 30, 'select1', '长城   ', 51),
(937, 30, 'select1', '新凯   ', 52),
(938, 30, 'select1', '英菲尼迪  ', 53),
(939, 30, 'select1', '吉利英伦   ', 54),
(940, 30, 'select1', '长安  ', 55),
(941, 30, 'select1', '夏利   ', 56),
(942, 30, 'select1', '马自达   ', 57),
(943, 30, 'select1', '华泰    ', 58),
(944, 30, 'select1', '华普   ', 59),
(945, 30, 'select1', '黄海  ', 60),
(946, 30, 'select1', '吉奥   ', 61),
(947, 30, 'select1', '大迪    ', 62),
(948, 30, 'select1', '吉普    ', 63),
(949, 30, 'select1', '红旗   ', 64),
(950, 30, 'select1', '海马   ', 65),
(951, 30, 'select1', '丰田     ', 66),
(952, 30, 'select1', '菲亚特  ', 67),
(953, 30, 'select1', '东南   ', 68),
(954, 30, 'select1', '福田   ', 69),
(955, 30, 'select1', '哈飞    ', 70),
(956, 30, 'select1', '金杯   ', 71),
(957, 30, 'select1', '川汽野马   ', 72),
(958, 30, 'select1', '路虎   ', 73),
(959, 30, 'select1', '陆风   ', 74),
(960, 30, 'select1', '法拉利    ', 75),
(961, 30, 'select1', '讴歌    ', 76),
(962, 30, 'select1', '欧宝    ', 77),
(963, 30, 'select1', '昌河    ', 78),
(964, 30, 'select1', '林肯', 79),
(965, 30, 'select1', '克莱斯勒   ', 80),
(966, 30, 'select1', '凯迪拉克    ', 81),
(967, 30, 'select1', '雷克萨斯   ', 82),
(968, 30, 'select1', '雷诺   ', 83),
(969, 30, 'select1', '力帆   ', 84),
(970, 30, 'select1', '宾利   ', 85),
(971, 30, 'select1', '玛莎拉蒂', 86),
(972, 30, 'select1', '兰博基尼', 87),
(973, 30, 'select1', '其他', 88),
(975, 30, 'select2', '10万以下', 2),
(976, 30, 'select2', '10万—20万', 3),
(977, 30, 'select2', '20万—30万', 4),
(978, 30, 'select2', '30万—50万', 5),
(979, 30, 'select2', '50万—100万', 6),
(980, 30, 'select2', '100万以上', 7),
(982, 31, 'select1', '汽车保养', 2),
(983, 31, 'select1', '汽车美容', 3),
(984, 31, 'select1', '汽车改装', 4),
(985, 31, 'select1', '汽车检修', 5),
(986, 31, 'select1', '洗车', 6),
(987, 31, 'select1', '其它', 7),
(989, 32, 'select1', '装载机', 2),
(990, 32, 'select1', '叉车', 3),
(991, 32, 'select1', '挖掘机', 4),
(992, 32, 'select1', '吊车', 5),
(993, 32, 'select1', '搅拌车', 6),
(994, 32, 'select1', '推土机', 7),
(995, 32, 'select1', '压路机', 8),
(996, 32, 'select1', '随车吊', 9),
(997, 32, 'select1', '其他', 10),
(999, 32, 'select2', '龙工', 2),
(1000, 32, 'select2', '柳工', 3),
(1001, 32, 'select2', '小松', 4),
(1002, 32, 'select2', '临工', 5),
(1003, 32, 'select2', '徐工', 6),
(1004, 32, 'select2', '玉柴', 7),
(1005, 32, 'select2', '豪沃', 8),
(1006, 32, 'select2', '日立', 9),
(1007, 32, 'select2', '山推', 10),
(1008, 32, 'select2', '现代', 11),
(1009, 32, 'select2', '山河智能', 12),
(1010, 32, 'select2', '尼桑', 13),
(1011, 32, 'select2', '东方红', 14),
(1012, 32, 'select2', '卡特', 15),
(1013, 32, 'select2', '长江', 16),
(1014, 32, 'select2', '宣化', 17),
(1015, 32, 'select2', '三一重工', 18),
(1016, 32, 'select2', '其他', 19),
(1018, 32, 'select3', '2万以下', 2),
(1019, 32, 'select3', '2万—5万', 3),
(1020, 32, 'select3', '5万—8万', 4),
(1021, 32, 'select3', '8万—12万', 5),
(1022, 32, 'select3', '12万—18万', 6),
(1023, 32, 'select3', '18万—25万', 7),
(1024, 32, 'select3', '25万以上', 8),
(1026, 33, 'select1', '过户上牌', 2),
(1027, 33, 'select1', '年检验车', 3),
(1028, 33, 'select1', '车辆保险', 4),
(1030, 34, 'select1', '面包车', 2),
(1031, 34, 'select1', '小巴车', 3),
(1032, 34, 'select1', '中巴车', 4),
(1033, 34, 'select1', '大客车', 5),
(1035, 34, 'select2', '五菱', 2),
(1036, 34, 'select2', '长安', 3),
(1037, 34, 'select2', '东风小康', 4),
(1038, 34, 'select2', '江淮', 5),
(1039, 34, 'select2', '开瑞', 6),
(1040, 34, 'select2', '哈飞', 7),
(1041, 34, 'select2', '金杯', 8),
(1042, 34, 'select2', '金龙', 9),
(1043, 34, 'select2', '东南', 10),
(1044, 34, 'select2', '依维柯', 11),
(1045, 34, 'select2', '佳宝', 12),
(1046, 34, 'select2', '昌河', 13),
(1047, 34, 'select2', '松花江', 14),
(1048, 34, 'select2', '宇通', 15),
(1049, 34, 'select2', '福田', 16),
(1050, 34, 'select2', '少林', 17),
(1051, 34, 'select2', '全顺', 18),
(1052, 34, 'select2', '丰田', 19),
(1053, 34, 'select2', '江铃', 20),
(1054, 34, 'select2', '其他', 21),
(1056, 34, 'select3', '2万以下', 2),
(1057, 34, 'select3', '2万—3万', 3),
(1058, 34, 'select3', '3万—5万', 4),
(1059, 34, 'select3', '5万—8万', 5),
(1060, 34, 'select3', '8万—12万', 6),
(1061, 34, 'select3', '12万—20万', 7),
(1062, 34, 'select3', '20万以上', 8),
(1064, 35, 'select1', '拖拉机', 2),
(1065, 35, 'select1', '收割机', 3),
(1066, 35, 'select1', '旋耕/微耕机', 4),
(1067, 35, 'select1', '插秧机', 5),
(1068, 35, 'select1', '抽水机', 6),
(1069, 35, 'select1', '其他', 7),
(1072, 35, 'select2', '常林', 2),
(1073, 35, 'select2', '常发', 3),
(1074, 35, 'select2', '五征', 4),
(1075, 35, 'select2', '四方', 5),
(1076, 35, 'select2', '黄金海马', 6),
(1077, 35, 'select2', '沭河', 7),
(1078, 35, 'select2', '约翰迪尔', 8),
(1079, 35, 'select2', '福田雷沃', 9),
(1080, 35, 'select2', '东风', 10),
(1081, 35, 'select2', '纽荷兰', 11),
(1082, 35, 'select2', '时风', 12),
(1083, 35, 'select2', '东方红', 13),
(1084, 35, 'select2', '其他', 14),
(1085, 35, 'select3', '1万以下', 2),
(1086, 35, 'select3', '1万—2万', 3),
(1087, 35, 'select3', '2万—4万', 4),
(1088, 35, 'select3', '4万—6万', 5),
(1089, 35, 'select3', '6万—8万', 6),
(1090, 35, 'select3', '8万以上', 7),
(1092, 36, 'select1', '小型车', 2),
(1093, 36, 'select1', 'SUV/越野车', 3),
(1094, 36, 'select1', '中型车', 4),
(1095, 36, 'select1', '豪华车', 5),
(1096, 36, 'select1', 'MPV/商务车', 6),
(1097, 36, 'select1', '跑车', 7),
(1098, 36, 'select1', '面包车', 8),
(1101, 36, 'select1', '其它', 9),
(1102, 36, 'select2', '奇瑞   ', 2),
(1103, 36, 'select2', '大众    ', 3),
(1104, 36, 'select2', '现代   ', 4),
(1105, 36, 'select2', '别克   ', 5),
(1106, 36, 'select2', '本田   ', 6),
(1107, 36, 'select2', '江淮   ', 7),
(1108, 36, 'select2', '日产   ', 8),
(1109, 36, 'select2', '雪佛兰   ', 9),
(1110, 36, 'select2', '丰田   ', 10),
(1111, 36, 'select2', '奥迪   ', 11),
(1112, 36, 'select2', '宝马   ', 12),
(1113, 36, 'select2', '福特  ', 13),
(1114, 36, 'select2', '起亚  ', 14),
(1115, 36, 'select2', '长安   ', 15),
(1116, 36, 'select2', '瑞麒   ', 16),
(1117, 36, 'select2', '比亚迪    ', 17),
(1118, 36, 'select2', '马自达   ', 18),
(1119, 36, 'select2', '福田    ', 19),
(1120, 36, 'select2', '标致    ', 20),
(1121, 36, 'select2', '奔驰   ', 21),
(1122, 36, 'select2', '东风   ', 22),
(1123, 36, 'select2', '长城   ', 23),
(1124, 36, 'select2', '雪铁龙   ', 24),
(1125, 36, 'select2', '中华  ', 25),
(1126, 36, 'select2', '荣威  ', 26),
(1127, 36, 'select2', '铃木  ', 27),
(1128, 36, 'select2', 'MG  ', 28),
(1129, 36, 'select2', '开瑞   ', 29),
(1130, 36, 'select2', '一汽   ', 30),
(1131, 36, 'select2', '沃尔沃   ', 31),
(1132, 36, 'select2', '金杯   ', 32),
(1133, 36, 'select2', '三菱   ', 33),
(1134, 36, 'select2', '莲花   ', 34),
(1135, 36, 'select2', '凯迪拉克    ', 35),
(1136, 36, 'select2', '海马   ', 36),
(1137, 36, 'select2', '哈飞   ', 37),
(1138, 36, 'select2', '长丰     ', 38),
(1139, 36, 'select2', '东南    ', 39),
(1140, 36, 'select2', '吉利    ', 40),
(1141, 36, 'select2', '斯柯达    ', 41),
(1142, 36, 'select2', '夏利    ', 42),
(1143, 36, 'select2', '昌河   ', 43),
(1144, 36, 'select2', '双环   ', 44),
(1145, 36, 'select2', '双龙   ', 45),
(1146, 36, 'select2', '西安奥拓   ', 46),
(1147, 36, 'select2', '斯巴鲁    ', 47),
(1148, 36, 'select2', '萨博    ', 48),
(1149, 36, 'select2', '中兴   ', 49),
(1150, 36, 'select2', '宝骏    ', 50),
(1151, 36, 'select2', '众泰   ', 51),
(1152, 36, 'select2', 'MINI(迷你)   ', 52),
(1153, 36, 'select2', '解放    ', 53),
(1154, 36, 'select2', '保时捷    ', 54),
(1155, 36, 'select2', '北汽制造    ', 55),
(1156, 36, 'select2', '宾利    ', 56),
(1157, 36, 'select2', '奔腾   ', 57),
(1158, 36, 'select2', '英菲尼迪         ', 58),
(1159, 36, 'select2', '吉利英伦   ', 59),
(1160, 36, 'select2', '新凯   ', 60),
(1161, 36, 'select2', '东风风神     ', 61),
(1162, 36, 'select2', '江铃   ', 62),
(1163, 36, 'select2', '东风风行    ', 63),
(1164, 36, 'select2', '捷豹    ', 64),
(1165, 36, 'select2', '菲亚特   ', 65),
(1166, 36, 'select2', '克莱斯勒    ', 66),
(1167, 36, 'select2', '吉普   ', 67),
(1168, 36, 'select2', '吉奥  ', 68),
(1169, 36, 'select2', '红旗    ', 69),
(1170, 36, 'select2', '华普    ', 70),
(1171, 36, 'select2', '华泰   ', 71),
(1172, 36, 'select2', '黄海   ', 72),
(1173, 36, 'select2', '雷克萨斯    ', 73),
(1174, 36, 'select2', '雷诺    ', 74),
(1175, 36, 'select2', '欧宝   ', 75),
(1176, 36, 'select2', '吉利帝豪   ', 76),
(1177, 36, 'select2', '道奇   ', 77),
(1178, 36, 'select2', '大迪    ', 78),
(1179, 36, 'select2', '讴歌    ', 79),
(1180, 36, 'select2', '路虎    ', 80),
(1181, 36, 'select2', '力帆     ', 81),
(1182, 36, 'select2', '林肯    ', 82),
(1183, 36, 'select2', '法拉利    ', 83),
(1184, 36, 'select2', '陆风川汽   ', 84),
(1185, 36, 'select2', '野马   ', 85),
(1186, 36, 'select2', '玛莎拉蒂', 86),
(1187, 36, 'select2', '兰博基尼', 87),
(1188, 36, 'select2', '其他', 88),
(1189, 36, 'select3', '1万以下', 2),
(1190, 36, 'select3', '1万—2万', 3),
(1191, 36, 'select3', '2万—4万', 4),
(1192, 36, 'select3', '4万以上', 5),
(1194, 37, 'select1', '车辆收购', 2),
(1195, 37, 'select1', '车辆评估', 3),
(1199, 38, 'select1', '快递员', 2),
(1200, 38, 'select1', '送货员', 3),
(1201, 38, 'select1', '保安', 4),
(1202, 38, 'select1', '编辑', 5),
(1203, 38, 'select1', '前台', 6),
(1204, 38, 'select1', '收银员', 7),
(1205, 38, 'select1', '店长', 8),
(1206, 38, 'select1', '摄影/影视', 9),
(1207, 38, 'select1', '广告/美工/设计', 10),
(1208, 38, 'select1', '保健按摩', 11),
(1209, 38, 'select1', 'KTV/酒吧', 12),
(1210, 38, 'select1', '美容美发', 13),
(1211, 38, 'select1', '网管', 14),
(1212, 38, 'select1', '翻译', 15),
(1213, 38, 'select1', '仓管', 16),
(1214, 38, 'select1', '切配', 17),
(1215, 38, 'select1', '厨师', 18),
(1216, 38, 'select1', '司机', 19),
(1217, 38, 'select1', '文员', 20),
(1218, 38, 'select1', '人事', 21),
(1219, 38, 'select1', '客服', 22),
(1220, 38, 'select1', '服务员', 23),
(1221, 38, 'select1', '营业员', 24),
(1222, 38, 'select1', '工人/技工', 25),
(1223, 38, 'select1', '行政', 26),
(1224, 38, 'select1', '房产经纪人', 27),
(1225, 38, 'select1', '助理', 28),
(1226, 38, 'select1', '教育/培训/咨询', 29),
(1227, 38, 'select1', '金融/银行/保险', 30),
(1228, 38, 'select1', '财务/会计/出纳', 31),
(1229, 38, 'select1', '家政', 32),
(1230, 38, 'select1', '保洁', 33),
(1231, 38, 'select1', '销售/业务员', 34),
(1232, 38, 'select1', '程序工程师', 35),
(1233, 38, 'select1', '其他', 36),
(1234, 38, 'select2', '女', 2),
(1235, 38, 'select2', '男', 3),
(1236, 38, 'select3', '本科及以上', 2),
(1237, 38, 'select3', '大专', 3),
(1238, 38, 'select3', '高中/中专', 4),
(1239, 38, 'select3', '初中及以下', 5),
(1241, 39, 'select1', '翻译', 2),
(1242, 39, 'select1', '演员', 3),
(1243, 39, 'select1', '客服', 4),
(1244, 39, 'select1', '充场/座谈会', 5),
(1245, 39, 'select1', '摄影', 6),
(1246, 39, 'select1', '网站', 7),
(1247, 39, 'select1', '会计', 8),
(1248, 39, 'select1', '模特', 9),
(1249, 39, 'select1', '礼仪', 10),
(1250, 39, 'select1', '设计', 11),
(1251, 39, 'select1', '家教', 12),
(1252, 39, 'select1', '服务员', 13),
(1253, 39, 'select1', '场地布置', 14),
(2688, 1, 'select4', '单选字段4_1', 100),
(1256, 39, 'select2', '女', 2),
(1257, 39, 'select2', '男', 3),
(1259, 40, 'select1', '旅游', 2),
(1260, 40, 'select1', '运动', 3),
(1261, 40, 'select1', '唱歌/泡吧', 4),
(1262, 40, 'select1', '创业', 5),
(1263, 40, 'select1', '做公益', 6),
(1264, 40, 'select1', '逛街', 7),
(1265, 40, 'select1', '学习', 8),
(1266, 40, 'select1', '玩桌游', 9),
(1267, 40, 'select1', '看演出', 10),
(1268, 40, 'select1', '晨跑', 11),
(1269, 40, 'select1', '广场活动', 12),
(1270, 40, 'select1', '其他', 13),
(1272, 40, 'select2', '女', 2),
(1273, 40, 'select2', '男', 3),
(1275, 41, 'select1', '女', 2),
(1276, 41, 'select1', '男', 3),
(1278, 41, 'select2', '25岁以下', 2),
(1279, 41, 'select2', '25岁—30岁', 3),
(1280, 41, 'select2', '30岁—40岁', 4),
(1281, 41, 'select2', '40岁—50岁', 5),
(1282, 41, 'select2', '50岁—60岁', 6),
(1283, 41, 'select2', '60岁以上', 7),
(1285, 42, 'select1', '女', 2),
(1286, 42, 'select1', '男', 3),
(1288, 42, 'select2', '20岁以下', 2),
(1289, 42, 'select2', '20岁—25岁', 3),
(1290, 42, 'select2', '25岁—30岁岁', 4),
(1291, 42, 'select2', '30岁—40岁', 5),
(1292, 42, 'select2', '40岁—50岁', 6),
(1293, 42, 'select2', '50岁以上', 7),
(1295, 44, 'select1', '魔术', 17),
(1296, 44, 'select1', '古玩鉴赏', 18),
(1297, 44, 'select1', '电器维修', 19),
(1298, 44, 'select1', '唱歌', 20),
(1299, 44, 'select1', '方言', 21),
(1300, 44, 'select1', '理财/金融', 22),
(1301, 44, 'select1', '数理化', 23),
(1302, 44, 'select1', '武术', 24),
(1303, 44, 'select1', '电工', 25),
(1304, 44, 'select1', '象棋/围棋', 26),
(1305, 44, 'select1', '中医', 27),
(1306, 44, 'select1', '平面设计', 28),
(1307, 44, 'select1', '服装设计', 29),
(1308, 44, 'select1', '会计/财务', 16),
(1309, 44, 'select1', '乐器', 15),
(1310, 44, 'select1', '美容/按摩', 2),
(1311, 44, 'select1', '外语', 3),
(1312, 44, 'select1', '开车', 4),
(1313, 44, 'select1', '电脑', 5),
(1314, 44, 'select1', '体育/打球', 6),
(1315, 44, 'select1', '跳舞', 7),
(1316, 44, 'select1', '室内设计', 8),
(1317, 44, 'select1', '摄影', 9),
(1318, 44, 'select1', '销售', 10),
(1319, 44, 'select1', '书法绘画', 11),
(1320, 44, 'select1', '烹饪', 12),
(1321, 44, 'select1', '投资/开店', 13),
(1322, 44, 'select1', '电脑程序', 14),
(1323, 44, 'select1', '其他', 30),
(1325, 45, 'select1', '寻人启事', 2),
(1326, 45, 'select1', '寻物启事', 3),
(1327, 45, 'select1', '寻宠物启事', 4),
(1328, 45, 'select1', '老乡', 5),
(1329, 45, 'select1', '其他', 6),
(1331, 46, 'select1', '没车', 2),
(1332, 46, 'select1', '有车', 3),
(1334, 46, 'select2', '长途拼车', 2),
(1335, 46, 'select2', '上下班拼车', 3),
(1336, 46, 'select2', '上下学拼车', 4),
(1337, 46, 'select2', '其他拼车', 5),
(1339, 47, 'select1', '整套出租', 2),
(1340, 47, 'select1', '单间出租', 3),
(1341, 47, 'select1', '床位出租', 4),
(1343, 47, 'select2', '500元以下', 2),
(1344, 47, 'select2', '500元—1000元', 3),
(1345, 47, 'select2', '1000元—1500元', 4),
(1346, 47, 'select2', '1500元—2500元', 5),
(1347, 47, 'select2', '2500元—3500元', 6),
(1348, 47, 'select2', '3500元以上', 7),
(1350, 47, 'select3', '4室及以上', 2),
(1351, 47, 'select3', '3室', 3),
(1352, 47, 'select3', '2室', 4),
(1353, 47, 'select3', '1室', 5),
(1355, 48, 'select1', '整套出租', 2),
(1356, 48, 'select1', '单间出租', 3),
(1357, 48, 'select1', '床位出租', 4),
(1359, 48, 'select2', '500元以下', 2),
(1360, 48, 'select2', '500元—1000元', 3),
(1361, 48, 'select2', '1000元—1500元', 4),
(1362, 48, 'select2', '1500元—2500元', 5),
(1363, 48, 'select2', '2500元—3500元', 6),
(1364, 48, 'select2', '3500元以上', 7),
(1366, 48, 'select3', '4室及以上', 2),
(1367, 48, 'select3', '3室', 3),
(1368, 48, 'select3', '2室', 4),
(1369, 48, 'select3', '1室', 5),
(1371, 49, 'select1', '限70平米以下', 2),
(1372, 49, 'select1', '70平米—90平米', 3),
(1373, 49, 'select1', '90平米—110平米', 4),
(1374, 49, 'select1', '110平米—150平米', 5),
(1375, 49, 'select1', '150平米以上', 6),
(1377, 49, 'select2', '30万以下', 2),
(1378, 49, 'select2', '30万—40万', 3),
(1379, 49, 'select2', '40万—50万', 4),
(1380, 49, 'select2', '50万—70万', 5),
(1381, 49, 'select2', '70万—90万', 6),
(1382, 49, 'select2', '90万—120万', 7),
(1383, 49, 'select2', '120万以上', 8),
(1385, 49, 'select3', '4室及以上', 2),
(1386, 49, 'select3', '3室2厅2卫', 3),
(1387, 49, 'select3', '3室2厅1卫', 4),
(1388, 49, 'select3', '3室1厅1卫', 5),
(1389, 49, 'select3', '2室2厅2卫', 6),
(1390, 49, 'select3', '1室1厅1卫', 7),
(1391, 49, 'select3', '2室1厅1卫', 8),
(1392, 49, 'select3', '2室2厅1卫', 9),
(1393, 49, 'select3', '1室0厅1卫', 10),
(1394, 49, 'select3', '其它', 11),
(1396, 50, 'select1', '临街店铺', 2),
(1397, 50, 'select1', '购物中心', 3),
(1398, 50, 'select1', '住宅底商摊位/柜台', 4),
(1399, 50, 'select1', '写字楼底商', 5),
(1400, 50, 'select1', '其他', 6),
(1402, 50, 'select2', '50平米以下', 2),
(1403, 50, 'select2', '50平米—100平米', 3),
(1404, 50, 'select2', '100平米—150平米', 4),
(1405, 50, 'select2', '150平米—250平米', 5),
(1406, 50, 'select2', '250平米以上', 6),
(1408, 50, 'select3', '30万以下', 2),
(1409, 50, 'select3', '30万—50万', 3),
(1410, 50, 'select3', '50万—80万', 4),
(1411, 50, 'select3', '80万—120万', 5),
(1412, 50, 'select3', '120万—300万', 6),
(1413, 50, 'select3', '300万—500万', 7),
(1414, 50, 'select3', '500万以上', 8),
(1418, 51, 'select1', '餐饮美食', 2),
(1419, 51, 'select1', '空铺转让', 3),
(1420, 51, 'select1', '服饰鞋包', 4),
(1421, 51, 'select1', '休闲娱乐', 5),
(1422, 51, 'select1', '美容美发', 6),
(1423, 51, 'select1', '酒店宾馆', 7),
(1424, 51, 'select1', '超市零售', 8),
(1425, 51, 'select1', '生活服务', 9),
(1426, 51, 'select1', '汽修美容', 10),
(1427, 51, 'select1', '家居建材', 11),
(1428, 51, 'select1', '电子通讯', 12),
(1429, 51, 'select1', '教育培训', 13),
(1430, 51, 'select1', '专柜转让', 14),
(1431, 51, 'select1', '医药保健', 15),
(1432, 51, 'select1', '其它', 16),
(1433, 51, 'select2', '50平米以下', 2),
(1434, 51, 'select2', '50平米—80平米', 3),
(1435, 51, 'select2', '80平米—120平米', 4),
(1436, 51, 'select2', '120平米—200平米', 5),
(1437, 51, 'select2', '200平米以上', 6),
(1438, 51, 'select3', '2000元/月以下', 2),
(1439, 51, 'select3', '2000元/月—3000元/月', 3);
INSERT INTO `tu_running_cate_attr` (`attr_id`, `cate_id`, `type`, `attr_name`, `orderby`) VALUES
(1440, 51, 'select3', '3000元/月—5000元/月', 4),
(1441, 51, 'select3', '5000元/月—8000元/月', 5),
(1442, 51, 'select3', '8000元/月—15000元/月', 6),
(1443, 51, 'select3', '15000元/月以上', 7),
(1447, 53, 'select1', '纯写字楼', 2),
(1448, 53, 'select1', '商务中心', 3),
(1449, 53, 'select1', '商住楼', 4),
(1450, 53, 'select1', '园区办公', 5),
(1451, 53, 'select1', '其他', 6),
(1452, 53, 'select2', '50平米以下', 2),
(1453, 53, 'select2', '50平米—100平米', 3),
(1454, 53, 'select2', '100平米—150平米', 4),
(1455, 53, 'select2', '150平米—200平米', 5),
(1456, 53, 'select2', '200平米—300平米', 6),
(1457, 53, 'select2', '300平米以上', 7),
(1458, 53, 'select3', '50元/平米/天以下', 2),
(1459, 53, 'select3', '50—100元/平米/天', 3),
(1460, 53, 'select3', '100—200元/平米/天', 4),
(1461, 53, 'select3', '200—400元/平米/天', 5),
(1462, 53, 'select3', '400以上元/平米/天', 6),
(1466, 54, 'select1', '家庭旅馆', 2),
(1467, 54, 'select1', '宾馆酒店', 3),
(1468, 54, 'select1', '高档公寓', 4),
(1469, 54, 'select1', '其他', 5),
(1470, 54, 'select2', '床位', 2),
(1471, 54, 'select2', '单间', 3),
(1472, 54, 'select2', '整套', 4),
(1473, 54, 'select3', '20元/天以下', 2),
(1474, 54, 'select3', '20元/天—50元/天', 3),
(1475, 54, 'select3', '50元/天—100元/天', 4),
(1476, 54, 'select3', '100元/天—200元/天', 5),
(1477, 54, 'select3', '200元/天—400元/天', 6),
(1478, 54, 'select3', '400元/天以上', 7),
(1480, 55, 'select1', '50平米以下', 2),
(1481, 55, 'select1', '50平米—150平米', 3),
(1482, 55, 'select1', '150平米—300平米', 4),
(1483, 55, 'select1', '300平米—500平米', 5),
(1484, 55, 'select1', '500平米—1000平米', 6),
(1485, 55, 'select1', '1000平米—3000平米', 7),
(1486, 55, 'select1', '3000平米以上', 8),
(1488, 55, 'select2', '50元/平米/天以下', 2),
(1489, 55, 'select2', '50—100元/平米/天', 3),
(1490, 55, 'select2', '100—200元/平米/天', 4),
(1491, 55, 'select2', '200—400元/平米/天', 5),
(1492, 55, 'select2', '400元/平米/天以上', 6),
(1496, 56, 'select1', '纯写字楼', 2),
(1497, 56, 'select1', '商务中心', 3),
(1498, 56, 'select1', '商住楼', 4),
(1499, 56, 'select1', '园区办公', 5),
(1500, 56, 'select1', '其他', 6),
(1501, 56, 'select2', '50平米以下', 2),
(1502, 56, 'select2', '50平米—80平米', 3),
(1503, 56, 'select2', '80平米—120平米', 4),
(1504, 56, 'select2', '120平米—180平米', 5),
(1505, 56, 'select2', '180平米—250平米', 6),
(1506, 56, 'select2', '250平米以上', 7),
(1507, 56, 'select3', '30万元以下', 2),
(1508, 56, 'select3', '30万元—50万元', 3),
(1509, 56, 'select3', '50万元—100万元', 4),
(1510, 56, 'select3', '100万元—200万元', 5),
(1511, 56, 'select3', '200万元—300万元', 6),
(1512, 56, 'select3', '300万元—500万元', 7),
(1513, 56, 'select3', '500万元以上', 8),
(1515, 57, 'select1', '50平米以下', 2),
(1516, 57, 'select1', '50平米—80平米', 3),
(1517, 57, 'select1', '80平米—120平米', 4),
(1518, 57, 'select1', '120平米—150平米', 5),
(1519, 57, 'select1', '150平米—250平米', 6),
(1520, 57, 'select1', '250平米以上', 7),
(1522, 57, 'select2', '30万元以下', 2),
(1523, 57, 'select2', '30万元—50万元', 3),
(1524, 57, 'select2', '50万元—80万元', 4),
(1525, 57, 'select2', '80万元—120万元', 5),
(1526, 57, 'select2', '120万元—200万元', 6),
(1527, 57, 'select2', '200万元以上', 7),
(1529, 57, 'select3', '4室及以上', 2),
(1530, 57, 'select3', '3室2厅2卫', 3),
(1531, 57, 'select3', '3室2厅1卫', 4),
(1532, 57, 'select3', '3室1厅1卫', 5),
(1533, 57, 'select3', '2室2厅2卫', 6),
(1534, 57, 'select3', '1室1厅1卫', 7),
(1535, 57, 'select3', '2室1厅1卫', 8),
(1536, 57, 'select3', '2室2厅1卫1', 9),
(1537, 57, 'select3', '室0厅1卫', 10),
(1538, 57, 'select3', '其它', 11),
(1540, 58, 'select1', '初中', 2),
(1541, 58, 'select1', '高中', 3),
(1542, 58, 'select1', '小学', 4),
(1543, 58, 'select1', '小升初', 5),
(1544, 58, 'select1', '初升高', 6),
(1545, 58, 'select1', '其他', 7),
(1547, 58, 'select2', '30元/小时以下', 2),
(1548, 58, 'select2', '30元/小时—50元/小时', 3),
(1549, 58, 'select2', '50元/小时—100元/小时', 4),
(1550, 58, 'select2', '100元/小时—200元/小时', 5),
(1551, 58, 'select2', '200元/小时以上', 6),
(1553, 59, 'select1', '财会/金融', 2),
(1554, 59, 'select1', '建筑', 3),
(1555, 59, 'select1', '公务员', 4),
(1556, 59, 'select1', '美容美发', 5),
(1557, 59, 'select1', '经营/管理', 6),
(1558, 59, 'select1', '技工', 7),
(1559, 59, 'select1', '教师', 8),
(1560, 59, 'select1', '心理/医疗/保健', 9),
(1561, 59, 'select1', '厨师', 10),
(1562, 59, 'select1', '外贸/采购/物流', 11),
(1563, 59, 'select1', '司法考试', 12),
(1564, 59, 'select1', '网络技术', 13),
(1565, 59, 'select1', '面点', 14),
(1566, 59, 'select1', '其他', 15),
(1568, 59, 'select2', '200元以下', 2),
(1569, 59, 'select2', '200元—500元', 3),
(1570, 59, 'select2', '500元—1000元', 4),
(1571, 59, 'select2', '1000元—3000元', 5),
(1572, 59, 'select2', '3000元—7000元', 6),
(1573, 59, 'select2', '7000元以上', 7),
(1575, 60, 'select1', '自考', 2),
(1576, 60, 'select1', '专科/本科', 3),
(1577, 60, 'select1', '网络/远程', 4),
(1578, 60, 'select1', '成人高考', 5),
(1579, 60, 'select1', '在职研究生', 6),
(1580, 60, 'select1', '出国留学', 7),
(1581, 60, 'select1', '考研', 8),
(1582, 60, 'select1', '其他', 9),
(1584, 60, 'select2', '500元以下', 2),
(1585, 60, 'select2', '500元—2000元', 3),
(1586, 60, 'select2', '2000元—5000元', 4),
(1587, 60, 'select2', '5000元—10000元', 5),
(1588, 60, 'select2', '10000元以上', 6),
(1591, 61, 'select1', '办公自动化', 2),
(1592, 61, 'select1', '网络营销', 3),
(1593, 61, 'select1', '认证培训', 4),
(1594, 61, 'select1', '网络工程/管理', 5),
(1595, 61, 'select1', '硬件技术/维修', 6),
(1596, 61, 'select1', '影视动画', 7),
(1597, 61, 'select1', '软件开发', 8),
(1598, 61, 'select1', '数据库培训', 9),
(1599, 61, 'select1', '计算机等级考试', 10),
(1600, 61, 'select1', '计算机软考', 11),
(1601, 61, 'select1', 'app开发', 12),
(1602, 61, 'select1', '游戏开发', 13),
(1603, 61, 'select1', '其他', 14),
(1604, 61, 'select2', '500元以下', 2),
(1605, 61, 'select2', '500元—1000元', 3),
(1606, 61, 'select2', '1000元—3000元', 4),
(1607, 61, 'select2', '3000元—7000元', 5),
(1608, 61, 'select2', '7000元以上', 6),
(1611, 62, 'select1', '书法', 2),
(1612, 62, 'select1', '美术', 3),
(1613, 62, 'select1', '舞蹈', 4),
(1614, 62, 'select1', '乐器', 5),
(1615, 62, 'select1', '声乐', 6),
(1616, 62, 'select1', '表演/礼仪', 7),
(1617, 62, 'select1', '其他', 8),
(1618, 62, 'select2', '500元以下', 2),
(1619, 62, 'select2', '500—1000', 3),
(1620, 62, 'select2', '1000—2000', 4),
(1621, 62, 'select2', '2000—4000', 5),
(1622, 62, 'select2', '4000—7000', 6),
(1623, 62, 'select2', '7000以上', 7),
(1626, 63, 'select1', '韩语', 2),
(1627, 63, 'select1', '英语', 3),
(1628, 63, 'select1', '日语', 4),
(1629, 63, 'select1', '俄语', 5),
(1630, 63, 'select1', '考试类英语', 6),
(1631, 63, 'select1', '西班牙语', 7),
(1632, 63, 'select1', '法语', 8),
(1633, 63, 'select1', '德语', 9),
(1634, 63, 'select1', '其他语种', 10),
(1635, 63, 'select2', '500元以下', 2),
(1636, 63, 'select2', '500元—1000元', 3),
(1637, 63, 'select2', '1000元—2000元', 4),
(1638, 63, 'select2', '2000元—3000元', 5),
(1639, 63, 'select2', '3000元以上', 6),
(1642, 64, 'select1', '室内设计', 2),
(1643, 64, 'select1', '平面设计', 3),
(1644, 64, 'select1', '模具设计', 4),
(1645, 64, 'select1', '网页设计', 5),
(1646, 64, 'select1', '广告设计', 6),
(1647, 64, 'select1', '建筑设计', 7),
(1648, 64, 'select1', '景观/园林设计', 8),
(1649, 64, 'select1', '家具设计', 9),
(1650, 64, 'select1', '形象设计', 10),
(1651, 64, 'select1', '动漫设计', 11),
(1652, 64, 'select1', '包装设计', 12),
(1653, 64, 'select1', '服装设计', 13),
(1654, 64, 'select1', '其他', 14),
(1655, 64, 'select2', '500元以下', 2),
(1656, 64, 'select2', '500元—1000元', 3),
(1657, 64, 'select2', '1000元—3000元', 4),
(1658, 64, 'select2', '3000元—5000元', 5),
(1659, 64, 'select2', '5000元—8000元', 6),
(1660, 64, 'select2', '8000元以上', 7),
(1662, 65, 'select1', '学前教育', 2),
(1663, 65, 'select1', '亲子教育', 3),
(1664, 65, 'select1', '胎教', 4),
(1665, 65, 'select1', '母婴护理', 5),
(1666, 65, 'select1', '其他', 6),
(1668, 65, 'select2', '500元以下', 2),
(1669, 65, 'select2', '500元—1000元', 3),
(1670, 65, 'select2', '1000元—3000元', 4),
(1671, 65, 'select2', '3000元—5000元', 5),
(1672, 65, 'select2', '5000元以上', 6),
(1674, 66, 'select1', '跆拳道', 2),
(1675, 66, 'select1', '球类', 3),
(1676, 66, 'select1', '游泳', 4),
(1677, 66, 'select1', '健身', 5),
(1678, 66, 'select1', '武术', 6),
(1679, 66, 'select1', '瑜伽', 7),
(1680, 66, 'select1', '棋类', 8),
(1681, 66, 'select1', '其他', 9),
(1683, 66, 'select2', '500元以下', 2),
(1684, 66, 'select2', '500元—1000元', 3),
(1685, 66, 'select2', '1000元—3000元', 4),
(1686, 66, 'select2', '3000元以上', 5),
(1688, 67, 'select1', '普工', 2),
(1689, 67, 'select1', '安装/维修工', 3),
(1690, 67, 'select1', '焊工', 4),
(1691, 67, 'select1', '电工', 5),
(1692, 67, 'select1', '装卸/搬运工', 6),
(1693, 67, 'select1', '建筑/装修工', 7),
(1694, 67, 'select1', '缝纫/制衣工', 8),
(1695, 67, 'select1', '车工/铣工/钳工', 9),
(1696, 67, 'select1', '模具工', 10),
(1697, 67, 'select1', '油漆工', 11),
(1698, 67, 'select1', '木工', 12),
(1699, 67, 'select1', '生产主管/组长', 13),
(1700, 67, 'select1', '钳工', 14),
(1701, 67, 'select1', '车间主任', 15),
(1702, 67, 'select1', '厂长/副厂长', 16),
(1703, 67, 'select1', '其他', 17),
(1706, 67, 'select2', '2000元—3000元', 3),
(1707, 67, 'select2', '3000元—5000元', 4),
(1708, 67, 'select2', '5000元—8000元', 5),
(1709, 67, 'select2', '8000元—12000元', 6),
(1710, 67, 'select2', '12000元以上', 7),
(1712, 68, 'select1', '销售专员', 2),
(1713, 68, 'select1', '电话销售', 3),
(1714, 68, 'select1', '销售经理/主管', 4),
(1715, 68, 'select1', '保险/金融顾问', 5),
(1716, 68, 'select1', '跟单员/助理', 6),
(1717, 68, 'select1', '网络销售', 7),
(1718, 68, 'select1', '房产经纪人', 8),
(1719, 68, 'select1', '保险经纪人', 9),
(1720, 68, 'select1', '证券/期货/外汇经纪人', 10),
(1721, 68, 'select1', '信用卡/银行卡业务', 11),
(1722, 68, 'select1', '股票/期货操盘手', 12),
(1723, 68, 'select1', '银行会计/柜员', 13),
(1724, 68, 'select1', '证券经理/总监', 14),
(1725, 68, 'select1', '银行经理/主任', 15),
(1726, 68, 'select1', '其他', 16),
(1728, 68, 'select2', '2000元以下', 2),
(1729, 68, 'select2', '2000元—3000元', 3),
(1730, 68, 'select2', '3000元—5000元', 4),
(1731, 68, 'select2', '5000元—8000元', 5),
(1732, 68, 'select2', '8000元—12000元', 6),
(1733, 68, 'select2', '12000元以上', 7),
(1735, 69, 'select1', '足疗师', 2),
(1736, 69, 'select1', '按摩师', 3),
(1737, 69, 'select1', '保健师', 4),
(1738, 69, 'select1', '其他', 5),
(1740, 69, 'select2', '2000元以下', 2),
(1741, 69, 'select2', '2000元—3000元', 3),
(1742, 69, 'select2', '3000元—5000元', 4),
(1743, 69, 'select2', '5000元—8000元', 5),
(1744, 69, 'select2', '8000元—12000元', 6),
(1745, 69, 'select2', '12000元以上', 7),
(1747, 70, 'select1', '室内设计', 2),
(1748, 70, 'select1', '平面设计', 3),
(1749, 70, 'select1', '广告设计', 4),
(1750, 70, 'select1', 'CAD制图', 5),
(1751, 70, 'select1', '网页设计', 6),
(1752, 70, 'select1', '店面/陈列设计', 7),
(1753, 70, 'select1', '摄影师', 8),
(1754, 70, 'select1', '包装设计', 9),
(1755, 70, 'select1', '服装设计', 10),
(1756, 70, 'select1', '其它', 11),
(1758, 70, 'select2', '2000元以下', 2),
(1759, 70, 'select2', '2000元—3000元', 3),
(1760, 70, 'select2', '3000元—5000元', 4),
(1761, 70, 'select2', '5000元—8000元', 5),
(1762, 70, 'select2', '8000元—12000元', 6),
(1763, 70, 'select2', '12000元以上', 7),
(1765, 71, 'select1', '文员', 2),
(1766, 71, 'select1', '行政专员/助理', 3),
(1767, 71, 'select1', '前台/总机/接待', 4),
(1768, 71, 'select1', '人事专员/助理', 5),
(1769, 71, 'select1', '秘书/助理', 6),
(1770, 71, 'select1', '编辑/文案', 7),
(1771, 71, 'select1', '后勤', 8),
(1772, 71, 'select1', '招聘经理/主管', 9),
(1773, 71, 'select1', '招聘专员/助理', 10),
(1774, 71, 'select1', '行政经理/主管', 11),
(1775, 71, 'select1', '人事经理/主管', 12),
(1776, 71, 'select1', '记者/采编', 13),
(1777, 71, 'select1', '猎头', 14),
(1778, 71, 'select1', '行政总监', 15),
(1779, 71, 'select1', '总编/副总编/主编', 16),
(1780, 71, 'select1', '其他', 17),
(1782, 71, 'select2', '2000元以下', 2),
(1783, 71, 'select2', '2000元—3000元', 3),
(1784, 71, 'select2', '3000元—5000元', 4),
(1785, 71, 'select2', '5000元—8000元', 5),
(1786, 71, 'select2', '8000元—12000元', 6),
(1787, 71, 'select2', '12000元以上', 7),
(1789, 72, 'select1', '营业员', 2),
(1790, 72, 'select1', '导购员', 3),
(1791, 72, 'select1', '促销员', 4),
(1792, 72, 'select1', '店长', 5),
(1793, 72, 'select1', '理货员', 6),
(1794, 72, 'select1', '其他', 7),
(1797, 72, 'select2', '2000元以下', 2),
(1798, 72, 'select2', '2000元—3000元', 3),
(1799, 72, 'select2', '3000元—5000元', 4),
(1800, 72, 'select2', '5000元—8000元', 5),
(1801, 72, 'select2', '8000元—12000元', 6),
(1802, 72, 'select2', '12000元以上', 7),
(1803, 72, 'select3', '做六休一', 2),
(1804, 72, 'select3', '做二休一', 3),
(1805, 72, 'select3', '做五休二', 4),
(1806, 72, 'select3', '做一休一', 5),
(1807, 72, 'select3', '其他', 6),
(1809, 73, 'select1', '餐厅服务员', 2),
(1810, 73, 'select1', '收银员', 3),
(1811, 73, 'select1', '大堂服务员', 4),
(1812, 73, 'select1', '客房服务员', 5),
(1813, 73, 'select1', '大堂经理/领班', 6),
(1814, 73, 'select1', '迎宾/接待', 7),
(1815, 73, 'select1', '其他', 8),
(1817, 73, 'select2', '2000元以下', 2),
(1818, 73, 'select2', '2000元—3000元', 3),
(1819, 73, 'select2', '3000元—5000元', 4),
(1820, 73, 'select2', '5000元—8000元', 5),
(1821, 73, 'select2', '8000元—12000元', 6),
(1822, 73, 'select2', '12000元以上', 7),
(1824, 74, 'select1', '厨师', 2),
(1825, 74, 'select1', '切配/厨工', 3),
(1826, 74, 'select1', '面点师', 4),
(1827, 74, 'select1', '洗碗工', 5),
(1828, 74, 'select1', '其他', 6),
(1830, 74, 'select2', '2000元以下', 2),
(1831, 74, 'select2', '2000元—3000元', 3),
(1832, 74, 'select2', '3000元—5000元', 4),
(1833, 74, 'select2', '5000元—8000元', 5),
(1834, 74, 'select2', '8000元—12000元', 6),
(1835, 74, 'select2', '12000元以上', 7),
(1837, 75, 'select1', '送货员', 2),
(1838, 75, 'select1', '快递员', 3),
(1839, 75, 'select1', '仓库管理员', 4),
(1840, 75, 'select1', '跟单员', 5),
(1841, 75, 'select1', '送餐员', 6),
(1842, 75, 'select1', '调度员', 7),
(1843, 75, 'select1', '其它', 8),
(1845, 75, 'select2', '2000元以下', 2),
(1846, 75, 'select2', '2000元—3000元', 3),
(1847, 75, 'select2', '3000元—5000元', 4),
(1848, 75, 'select2', '5000元—8000元', 5),
(1849, 75, 'select2', '8000元—12000元', 6),
(1850, 75, 'select2', '12000元以上', 7),
(1852, 76, 'select1', '900元以下', 2),
(1853, 76, 'select1', '900元—2000元', 3),
(1854, 76, 'select1', '2000元—3000元', 4),
(1855, 76, 'select1', '3000元以上', 5),
(1857, 77, 'select1', '保洁', 2),
(1858, 77, 'select1', '保姆', 3),
(1859, 77, 'select1', '钟点工', 5),
(1860, 77, 'select1', '陪护', 6),
(1861, 77, 'select1', '育婴师', 6),
(1862, 77, 'select1', '月嫂', 7),
(1863, 77, 'select1', '其它', 8),
(1865, 77, 'select2', '900元以下', 2),
(1866, 77, 'select2', '900元—2000元', 3),
(1867, 77, 'select2', '2000元—3000元', 4),
(1868, 77, 'select2', '3000元以上', 5),
(1870, 78, 'select1', '货车', 2),
(1871, 78, 'select1', '出租车', 3),
(1872, 78, 'select1', '轿车', 4),
(1873, 78, 'select1', '面包车', 5),
(1874, 78, 'select1', '客车', 6),
(1875, 78, 'select1', '挖掘机', 7),
(1876, 78, 'select1', '叉车/铲车/吊车', 8),
(1877, 78, 'select1', '驾校教练/陪驾', 9),
(1878, 78, 'select1', '其他', 10),
(1880, 78, 'select2', '2000元以下', 2),
(1881, 78, 'select2', '2000元—3000元', 3),
(1882, 78, 'select2', '3000元—5000元', 4),
(1883, 78, 'select2', '5000元—8000元', 5),
(1884, 78, 'select2', '8000元—12000元', 6),
(1885, 78, 'select2', '12000元以上', 7),
(1887, 79, 'select1', '中小学教师', 2),
(1888, 79, 'select1', '家教', 3),
(1889, 79, 'select1', '早教', 4),
(1890, 79, 'select1', '文艺/体育教师', 5),
(1891, 79, 'select1', '企业培训', 6),
(1892, 79, 'select1', '运动/健身教练', 7),
(1893, 79, 'select1', '校长', 8),
(1894, 79, 'select1', '瑜伽/舞蹈老师', 9),
(1895, 79, 'select1', '其他', 10),
(1897, 79, 'select2', '2000元以下', 2),
(1898, 79, 'select2', '2000元—3000元', 3),
(1899, 79, 'select2', '3000元—5000元', 4),
(1900, 79, 'select2', '5000元—8000元', 5),
(1901, 79, 'select2', '8000元—12000元', 6),
(1902, 79, 'select2', '12000元以上', 7),
(1904, 80, 'select1', '会计', 2),
(1905, 80, 'select1', '财务', 3),
(1906, 80, 'select1', '出纳', 4),
(1907, 80, 'select1', '统计员', 5),
(1908, 80, 'select1', '审计', 6),
(1909, 80, 'select1', '税务', 7),
(1910, 80, 'select1', '其他', 8),
(1912, 80, 'select2', '2000元以下', 2),
(1913, 80, 'select2', '2000元—3000元', 3),
(1914, 80, 'select2', '3000元—5000元', 4),
(1915, 80, 'select2', '5000元—8000元', 5),
(1916, 80, 'select2', '8000元—12000元', 6),
(1917, 80, 'select2', '12000元以上', 7),
(1919, 81, 'select1', '淘宝美工', 2),
(1920, 81, 'select1', '淘宝客服', 3),
(1921, 81, 'select1', '网店店长', 4),
(1922, 81, 'select1', '店铺运营/推广', 5),
(1923, 81, 'select1', '其它', 6),
(1925, 81, 'select2', '2000元以下', 2),
(1926, 81, 'select2', '2000元—3000元', 3),
(1927, 81, 'select2', '3000元—5000元', 4),
(1928, 81, 'select2', '5000元—8000元', 5),
(1929, 81, 'select2', '8000元—12000元', 6),
(1930, 81, 'select2', '12000元以上', 7),
(1932, 81, 'select3', '做六休一', 2),
(1933, 81, 'select3', '做二休一', 3),
(1934, 81, 'select3', '做五休二', 4),
(1935, 81, 'select3', '做一休一', 5),
(1936, 81, 'select3', '其他', 6),
(1938, 82, 'select1', '客服专员/助理', 2),
(1939, 82, 'select1', '客服经理/主管', 3),
(1940, 82, 'select1', '其他', 4),
(1942, 82, 'select2', '2000元以下', 2),
(1943, 82, 'select2', '2000元—3000元', 3),
(1944, 82, 'select2', '3000元—5000元', 4),
(1945, 82, 'select2', '5000元以上', 5),
(1947, 83, 'select1', '美发师', 2),
(1948, 83, 'select1', '化妆师', 3),
(1949, 83, 'select1', '美容师', 4),
(1950, 83, 'select1', '美发助理/学徒', 5),
(1951, 83, 'select1', '美容助理/学徒', 6),
(1952, 83, 'select1', '美甲师', 7),
(1953, 83, 'select1', '洗头工', 8),
(1954, 83, 'select1', '其它', 9),
(1956, 83, 'select2', '2000元以下', 2),
(1957, 83, 'select2', '2000元—3000元', 3),
(1958, 83, 'select2', '3000元—5000元', 4),
(1959, 83, 'select2', '5000元—8000元', 5),
(1960, 83, 'select2', '8000元—12000元', 6),
(1961, 83, 'select2', '12000元以上', 7),
(1963, 84, 'select1', '技术支持/维护', 2),
(1964, 84, 'select1', '游戏设计/开发', 3),
(1965, 84, 'select1', '网络工程师', 4),
(1966, 84, 'select1', '网站运营', 5),
(1967, 84, 'select1', '网站编辑', 6),
(1968, 84, 'select1', '技术专员/助理', 7),
(1969, 84, 'select1', '程序员', 8),
(1970, 84, 'select1', '网络管理员', 9),
(1971, 84, 'select1', '硬件工程师', 10),
(1972, 84, 'select1', '技术总监/经理', 11),
(1973, 84, 'select1', '软件工程师', 12),
(1974, 84, 'select1', '产品经理/专员', 13),
(1975, 84, 'select1', '测试员', 14),
(1976, 84, 'select1', '通信工程师', 15),
(1977, 84, 'select1', '架构师', 16),
(1978, 84, 'select1', '数据库管理', 17),
(1979, 84, 'select1', '其他', 18),
(1981, 84, 'select2', '2000元以下', 2),
(1982, 84, 'select2', '2000元—3000元', 3),
(1983, 84, 'select2', '3000元—5000元', 4),
(1984, 84, 'select2', '5000元—8000元', 5),
(1985, 84, 'select2', '8000元—12000元', 6),
(1986, 84, 'select2', '12000元以上', 7),
(1988, 84, 'select3', '无经验', 2),
(1989, 84, 'select3', '一年经验', 3),
(1990, 84, 'select3', '二年经验', 4),
(1991, 84, 'select3', '三年经验', 5),
(1992, 84, 'select3', '三年以上经验', 6),
(1994, 85, 'select1', '市场专员/助理', 2),
(1995, 85, 'select1', '营销经理', 3),
(1996, 85, 'select1', '市场经理/主管', 4),
(1997, 85, 'select1', '客户经理/主管', 5),
(1998, 85, 'select1', '公关专员/助理', 6),
(1999, 85, 'select1', '公关经理/主管', 7),
(2000, 85, 'select1', '大客户经理', 8),
(2001, 85, 'select1', '其他', 9),
(2003, 85, 'select2', '2000元以下', 2),
(2004, 85, 'select2', '2000元—3000元', 3),
(2005, 85, 'select2', '3000元—5000元', 4),
(2006, 85, 'select2', '5000元—8000元', 5),
(2007, 85, 'select2', '8000元—12000元', 6),
(2008, 85, 'select2', '12000元以上', 7),
(2010, 87, 'select1', '德语', 2),
(2011, 87, 'select1', '英语', 3),
(2012, 87, 'select1', '韩语', 4),
(2013, 87, 'select1', '日语', 5),
(2014, 87, 'select1', '俄语', 6),
(2015, 87, 'select1', '西班牙语', 7),
(2016, 87, 'select1', '法语', 8),
(2017, 87, 'select1', '其它', 9),
(2019, 87, 'select2', '2000元以下', 2),
(2020, 87, 'select2', '2000元—3000元', 3),
(2021, 87, 'select2', '3000元—5000元', 4),
(2022, 87, 'select2', '5000元—8000元', 5),
(2023, 87, 'select2', '8000元—12000元', 6),
(2024, 87, 'select2', '12000元以上', 7),
(2026, 89, 'select1', '2000元以下', 2),
(2027, 89, 'select1', '2000元—3000元', 3),
(2028, 89, 'select1', '3000元—5000元', 4),
(2029, 89, 'select1', '5000元—8000元', 5),
(2030, 89, 'select1', '8000元—12000元', 6),
(2031, 89, 'select1', '12000元以上', 7),
(2033, 90, 'select1', '产品代理', 2),
(2034, 90, 'select1', '服装箱包', 3),
(2035, 90, 'select1', '餐饮加盟', 4),
(2036, 90, 'select1', '教育培训', 5),
(2037, 90, 'select1', '建材家居', 6),
(2038, 90, 'select1', '干洗加盟', 7),
(2039, 90, 'select1', '美容保健', 8),
(2040, 90, 'select1', '快递物流', 9),
(2041, 90, 'select1', '礼品饰品', 10),
(2042, 90, 'select1', '农业养殖', 11),
(2043, 90, 'select1', '母婴用品', 12),
(2044, 90, 'select1', '其他', 13),
(2046, 91, 'select1', '无抵押贷款', 2),
(2047, 91, 'select1', '企业/个体户贷款', 3),
(2048, 91, 'select1', '汽车抵押贷款', 4),
(2049, 91, 'select1', '房产抵押贷款', 5),
(2050, 91, 'select1', '买房贷款', 6),
(2051, 91, 'select1', '买车贷款', 7),
(2052, 91, 'select1', '其它', 8),
(2054, 92, 'select1', '空调维修', 2),
(2055, 92, 'select1', '热水器维修', 3),
(2056, 92, 'select1', '空调移机', 4),
(2057, 92, 'select1', '洗衣机维修', 5),
(2058, 92, 'select1', '冰箱维修', 6),
(2059, 92, 'select1', '电视机维修', 7),
(2060, 92, 'select1', '饮水机维修', 8),
(2061, 92, 'select1', '电饭煲维修', 9),
(2062, 92, 'select1', '抽油烟机维修', 10),
(2063, 92, 'select1', '电磁炉维修', 11),
(2064, 92, 'select1', '其他', 12),
(2066, 93, 'select1', '吊顶', 2),
(2067, 93, 'select1', '门窗', 3),
(2068, 93, 'select1', '涂料', 4),
(2069, 93, 'select1', '钢材', 5),
(2070, 93, 'select1', '五金', 6),
(2071, 93, 'select1', '墙纸', 7),
(2072, 93, 'select1', '地板', 8),
(2073, 93, 'select1', '油漆', 9),
(2074, 93, 'select1', '卫浴洁具', 10),
(2075, 93, 'select1', '玻璃', 11),
(2076, 93, 'select1', '瓷砖', 12),
(2077, 93, 'select1', '窗帘', 13),
(2078, 93, 'select1', '灯具', 14),
(2079, 93, 'select1', '水泥', 15),
(2080, 93, 'select1', '橱柜', 16),
(2081, 93, 'select1', '厨卫电器', 17),
(2082, 93, 'select1', '暖气地暖', 18),
(2083, 93, 'select1', '其它', 19),
(2085, 94, 'select1', '公司注册', 2),
(2086, 94, 'select1', '工商年检', 3),
(2087, 94, 'select1', '商标注册', 4),
(2088, 94, 'select1', '公司转让', 5),
(2089, 94, 'select1', '专利注册', 6),
(2090, 94, 'select1', '海外公司注册', 7),
(2091, 94, 'select1', '专项审批', 8),
(2092, 94, 'select1', '香港公司注册', 9),
(2093, 94, 'select1', '公司注销', 10),
(2094, 94, 'select1', '验资开户', 11),
(2095, 94, 'select1', '资质认证', 12),
(2096, 94, 'select1', '外资公司注册', 13),
(2097, 94, 'select1', '其它', 14),
(2099, 95, 'select1', '新房装修', 2),
(2100, 95, 'select1', '店铺装修', 3),
(2101, 95, 'select1', '办公室/写字楼装修', 4),
(2102, 95, 'select1', '二手房装修', 5),
(2103, 95, 'select1', '厂房装修', 6),
(2104, 95, 'select1', '拆旧', 7),
(2105, 95, 'select1', '学校/幼儿园装修', 8),
(2106, 95, 'select1', '商场/超市装修', 9),
(2107, 95, 'select1', '酒店装修', 10),
(2108, 95, 'select1', '其他', 11),
(2110, 96, 'select1', '理财产品', 2),
(2111, 96, 'select1', '期货', 3),
(2112, 96, 'select1', '股票', 4),
(2113, 96, 'select1', '黄金', 5),
(2114, 96, 'select1', '证券', 6),
(2115, 96, 'select1', '信用卡', 7),
(2116, 96, 'select1', '基金', 8),
(2117, 96, 'select1', '外汇', 9),
(2118, 96, 'select1', '保险', 10),
(2119, 96, 'select1', '其它', 11),
(2121, 97, 'select1', '会计', 2),
(2122, 97, 'select1', '审计', 3),
(2124, 98, 'select1', '电脑维修', 2),
(2125, 98, 'select1', '笔记本维修', 3),
(2126, 98, 'select1', '网络布线', 4),
(2127, 98, 'select1', 'IT外包', 5),
(2128, 98, 'select1', '打印机维修', 6),
(2129, 98, 'select1', '数据恢复', 7),
(2130, 98, 'select1', '传真机维修', 8),
(2131, 98, 'select1', '安防监控', 9),
(2132, 98, 'select1', 'ipad/平板电脑维修', 10),
(2133, 98, 'select1', '机房建设', 11),
(2134, 98, 'select1', '其它', 12),
(2136, 99, 'select1', '货运', 2),
(2137, 99, 'select1', '快递', 3),
(2138, 99, 'select1', '仓储', 4),
(2139, 99, 'select1', '行李托运', 5),
(2140, 99, 'select1', '进出口报关', 6),
(2141, 99, 'select1', '其它', 7),
(2143, 100, 'select1', '农产品加工/代理', 2),
(2144, 100, 'select1', '农作物', 3),
(2145, 100, 'select1', '畜禽养殖', 4),
(2146, 100, 'select1', '水产', 5),
(2147, 100, 'select1', '园林花卉', 6),
(2148, 100, 'select1', '动植物种苗', 7),
(2149, 100, 'select1', '农机具/设备', 8),
(2150, 100, 'select1', '饲料/兽药', 9),
(2151, 100, 'select1', '农药/肥料', 10),
(2152, 100, 'select1', '其它', 11),
(2154, 101, 'select1', '活动策划', 12),
(2155, 101, 'select1', '广告策划', 11),
(2156, 101, 'select1', '平面设计', 10),
(2157, 101, 'select1', '网页设计', 9),
(2158, 101, 'select1', 'Logo设计/VI设计', 8),
(2159, 101, 'select1', '工业设计', 7),
(2160, 101, 'select1', '景观设计', 6),
(2161, 101, 'select1', '影视/动画制作', 5),
(2162, 101, 'select1', '室内设计', 4),
(2163, 101, 'select1', '名片设计', 3),
(2164, 101, 'select1', '服装设计', 2),
(2165, 101, 'select1', '其它', 13),
(2167, 102, 'select1', '管道疏通', 2),
(2168, 102, 'select1', '下水道疏通', 3),
(2169, 102, 'select1', '马桶疏通', 4),
(2170, 102, 'select1', '化粪池疏通', 5),
(2171, 102, 'select1', '管道清淤', 6),
(2172, 102, 'select1', '打捞', 7),
(2174, 103, 'select1', '带司机租车', 2),
(2175, 103, 'select1', '商务租车', 3),
(2176, 103, 'select1', '旅游租车', 4),
(2177, 103, 'select1', '婚车', 5),
(2178, 103, 'select1', '豪华车', 6),
(2179, 103, 'select1', '货车', 7),
(2180, 103, 'select1', '其他', 8),
(2182, 103, 'select2', '100元以下', 2),
(2183, 103, 'select2', '200元—300元', 3),
(2184, 103, 'select2', '300元—500元', 4),
(2185, 103, 'select2', '500元—800元', 5),
(2186, 103, 'select2', '800元—1200元', 6),
(2187, 103, 'select2', '1200元以上', 7),
(2189, 104, 'select1', '周边游', 2),
(2190, 104, 'select1', '农家乐', 3),
(2191, 104, 'select1', '旅行社', 4),
(2192, 104, 'select1', '导游', 5),
(2193, 104, 'select1', '港澳台游', 6),
(2194, 104, 'select1', '温泉', 7),
(2195, 104, 'select1', '出国游', 8),
(2196, 104, 'select1', '其它', 9),
(2198, 105, 'select1', '婚纱摄影', 2),
(2199, 105, 'select1', '艺术照', 3),
(2200, 105, 'select1', '写真', 4),
(2201, 105, 'select1', '摄像', 5),
(2202, 105, 'select1', '商业摄影', 6),
(2203, 105, 'select1', '儿童摄影', 7),
(2204, 105, 'select1', '其他', 8),
(2206, 106, 'select1', '日常保洁', 2),
(2207, 106, 'select1', '擦玻璃', 3),
(2208, 106, 'select1', '开荒保洁', 4),
(2209, 106, 'select1', '抽油烟机清洗', 5),
(2210, 106, 'select1', '地毯清洗', 6),
(2211, 106, 'select1', '外墙清洗', 7),
(2212, 106, 'select1', '干洗', 8),
(2213, 106, 'select1', '石材翻新/养护', 9),
(2214, 106, 'select1', '地板打蜡', 10),
(2215, 106, 'select1', '沙发清洗', 11),
(2216, 106, 'select1', '空调清洗', 12),
(2217, 106, 'select1', '灯具清洗', 13),
(2218, 106, 'select1', '空气净化', 14),
(2219, 106, 'select1', '瓷砖美缝', 15),
(2220, 106, 'select1', '其他', 16),
(2222, 107, 'select1', '100元以下', 2),
(2223, 107, 'select1', '100元—200元', 3),
(2224, 107, 'select1', '200元—300元', 4),
(2225, 107, 'select1', '300元—500元', 5),
(2226, 107, 'select1', '500元以上', 6),
(2228, 109, 'select1', 'C1(手动档汽车)', 2),
(2229, 109, 'select1', 'C2(自动挡汽车)', 3),
(2230, 109, 'select1', 'B1(中型客车)', 4),
(2231, 109, 'select1', 'B2(大型货车)', 5),
(2232, 109, 'select1', 'E(二轮摩托车)', 6),
(2233, 109, 'select1', 'F(轻便摩托车)', 7),
(2234, 109, 'select1', 'D(三轮摩托车)', 8),
(2235, 109, 'select1', 'A2(牵引车)', 9),
(2236, 109, 'select1', 'C3(载货汽车)', 10),
(2237, 109, 'select1', 'C4(三轮汽车)', 11),
(2238, 109, 'select1', 'C5(残疾人专用)', 12),
(2239, 109, 'select1', 'A(大型客车)', 13),
(2240, 109, 'select1', 'A3(城市公交车)', 14),
(2242, 109, 'select2', '2000元以下', 2),
(2243, 109, 'select2', '2000元—5000元', 3),
(2244, 109, 'select2', '5000元—8000元', 4),
(2245, 109, 'select2', '8000元以上', 5),
(2247, 111, 'select1', '健身', 2),
(2248, 111, 'select1', '瑜伽', 3),
(2249, 111, 'select1', '按摩', 4),
(2250, 111, 'select1', '足疗', 5),
(2251, 111, 'select1', '会所', 6),
(2252, 111, 'select1', 'KTV', 7),
(2253, 111, 'select1', '洗浴', 8),
(2254, 111, 'select1', '酒吧', 9),
(2255, 111, 'select1', '桌游', 10),
(2256, 111, 'select1', '演唱会', 11),
(2257, 111, 'select1', '演出票务', 12),
(2258, 111, 'select1', '电影票', 13),
(2259, 111, 'select1', '其他', 14),
(2261, 112, 'select1', '印刷', 2),
(2262, 112, 'select1', '喷绘', 3),
(2263, 112, 'select1', '名片', 4),
(2264, 112, 'select1', '制卡', 5),
(2265, 112, 'select1', '灯箱', 6),
(2266, 112, 'select1', '易拉宝', 7),
(2267, 112, 'select1', '其他', 8),
(2269, 113, 'select1', '签证', 2),
(2270, 113, 'select1', '机票', 3),
(2271, 113, 'select1', '移民', 4),
(2273, 114, 'select1', '礼品定制', 2),
(2274, 114, 'select1', '商务礼品', 3),
(2275, 114, 'select1', '其它', 4),
(2277, 115, 'select1', '水电维修', 2),
(2278, 115, 'select1', '马桶维修', 3),
(2279, 115, 'select1', '防水补漏', 4),
(2280, 115, 'select1', '淋浴房维修', 5),
(2281, 115, 'select1', '家具维修', 6),
(2282, 115, 'select1', '门窗维修', 7),
(2283, 115, 'select1', '沙发维修', 8),
(2284, 115, 'select1', '其他', 9),
(2286, 116, 'select1', '美容', 10),
(2287, 116, 'select1', '减肥瘦身', 9),
(2288, 116, 'select1', '祛痘/祛斑', 8),
(2289, 116, 'select1', 'spa', 7),
(2290, 116, 'select1', '丰胸', 6),
(2291, 116, 'select1', '美发', 5),
(2292, 116, 'select1', '除皱', 4),
(2293, 116, 'select1', '脱毛', 3),
(2294, 116, 'select1', '美甲', 2),
(2297, 116, 'select2', '100元以下', 2),
(2298, 116, 'select2', '100元—200元', 3),
(2299, 116, 'select2', '200元—400元', 4),
(2300, 116, 'select2', '400元—800元', 5),
(2301, 116, 'select2', '800元—1200元', 6),
(2302, 116, 'select2', '1200元—1500元', 7),
(2303, 116, 'select2', '1500元以上', 8),
(2305, 117, 'select1', '家政公司', 2),
(2306, 117, 'select1', '保姆', 3),
(2307, 117, 'select1', '东家', 4),
(2308, 117, 'select1', '其它', 5),
(2310, 117, 'select2', '钟点工', 2),
(2311, 117, 'select2', '做饭', 3),
(2312, 117, 'select2', '保姆', 4),
(2313, 117, 'select2', '照顾孩子', 5),
(2314, 117, 'select2', '住家阿姨', 6),
(2315, 117, 'select2', '照顾老人', 7),
(2316, 117, 'select2', '育婴师/育儿嫂', 8),
(2317, 117, 'select2', '月嫂', 9),
(2318, 117, 'select2', '催乳师', 10),
(2319, 117, 'select2', '护工/陪护', 11),
(2320, 117, 'select2', '其它', 12),
(2322, 118, 'select1', '网站建设', 2),
(2323, 118, 'select1', '网站推广', 3),
(2324, 118, 'select1', '网站维护/外包', 4),
(2325, 118, 'select1', '域名注册', 5),
(2326, 118, 'select1', '服务器', 6),
(2327, 118, 'select1', '企业邮箱', 7),
(2328, 118, 'select1', '其它', 8),
(2330, 119, 'select1', '绿植/盆栽', 2),
(2331, 119, 'select1', '园艺用品', 3),
(2332, 119, 'select1', '鲜花', 4),
(2333, 119, 'select1', '其它', 5),
(2335, 120, 'select1', '新娘化妆', 2),
(2336, 120, 'select1', '婚车租赁', 3),
(2337, 120, 'select1', '婚庆公司', 4),
(2338, 120, 'select1', '婚礼跟拍', 5),
(2339, 120, 'select1', '婚纱礼服', 6),
(2340, 120, 'select1', '司仪', 7),
(2341, 120, 'select1', '婚礼用品', 8),
(2342, 120, 'select1', '婚宴', 9),
(2343, 120, 'select1', '喜糖', 10),
(2344, 120, 'select1', '婚戒首饰', 11),
(2345, 120, 'select1', '其他', 12),
(2347, 120, 'select2', '500元以下', 2),
(2348, 120, 'select2', '500元—1000元', 3),
(2349, 120, 'select2', '1000元—2000元', 4),
(2350, 120, 'select2', '2000元—4000元', 5),
(2351, 120, 'select2', '4000元以上', 6),
(2353, 121, 'select1', '庆典公司', 2),
(2354, 121, 'select1', '场地布置', 3),
(2355, 121, 'select1', '灯光音响', 4),
(2356, 121, 'select1', '礼仪模特', 5),
(2357, 121, 'select1', '乐队演出', 6),
(2358, 121, 'select1', '司仪', 7),
(2359, 121, 'select1', '化妆', 8),
(2360, 121, 'select1', '其它', 9),
(2362, 122, 'select1', '债务纠纷', 2),
(2363, 122, 'select1', '合同纠纷', 3),
(2364, 122, 'select1', '交通事故', 4),
(2365, 122, 'select1', '离婚', 5),
(2366, 122, 'select1', '刑事辩护', 6),
(2367, 122, 'select1', '知识产权', 7),
(2368, 122, 'select1', '房产纠纷', 8),
(2369, 122, 'select1', '法律援助', 9),
(2370, 122, 'select1', '劳动纠纷', 10),
(2371, 122, 'select1', '其它', 11),
(2373, 123, 'select1', '快餐', 2),
(2374, 123, 'select1', '送水', 3),
(2375, 123, 'select1', '食材配送', 4),
(2376, 123, 'select1', '宴会外卖', 5),
(2377, 123, 'select1', '承包食堂', 6),
(2378, 123, 'select1', '其它', 7),
(2380, 124, 'select1', '星级酒店', 2),
(2381, 124, 'select1', '经济型酒店', 3),
(2382, 124, 'select1', '宾馆', 4),
(2383, 124, 'select1', '青年旅舍', 5),
(2384, 124, 'select1', '中等', 6),
(2385, 124, 'select1', '度假村', 7),
(2386, 124, 'select1', '连锁酒店', 8),
(2387, 124, 'select1', '其它', 9),
(2389, 124, 'select2', '100元以下', 2),
(2390, 124, 'select2', '100元—200元', 3),
(2391, 124, 'select2', '200元—300元', 4),
(2392, 124, 'select2', '300元—500元', 5),
(2393, 124, 'select2', '500元—800元', 6),
(2394, 124, 'select2', '800元以上', 7),
(2396, 125, 'select1', '购物', 2),
(2397, 125, 'select1', '休闲娱乐', 3),
(2398, 125, 'select1', '论坛', 4),
(2399, 125, 'select1', '新闻', 5),
(2400, 125, 'select1', '交通地图', 6),
(2401, 125, 'select1', '游戏', 7),
(2402, 125, 'select1', 'KTV', 8),
(2403, 125, 'select1', '会所', 9),
(2404, 125, 'select1', '茶庄', 10),
(2405, 125, 'select1', '其他', 11),
(2407, 126, 'select1', '笔译', 2),
(2408, 126, 'select1', '口译', 3),
(2409, 126, 'select1', '速记', 4),
(2410, 126, 'select1', '同声传译', 5),
(2411, 126, 'select1', '其它', 6),
(2413, 127, 'select1', '面部整形', 2),
(2414, 127, 'select1', '眼部整形', 3),
(2415, 127, 'select1', '鼻部整形', 4),
(2416, 127, 'select1', '唇部整形', 5),
(2417, 127, 'select1', '牙齿美容', 6),
(2418, 127, 'select1', '皮肤美容', 7),
(2419, 127, 'select1', '胸部整形', 8),
(2420, 127, 'select1', '微整形', 9),
(2421, 127, 'select1', '吸脂溶脂减肥', 10),
(2422, 127, 'select1', '私密整形', 11),
(2423, 127, 'select1', '激光脱毛', 12),
(2424, 127, 'select1', '假体取出', 13),
(2425, 127, 'select1', '其他', 14),
(2427, 128, 'select1', '肾病科', 2),
(2428, 128, 'select1', '皮肤病科', 3),
(2429, 128, 'select1', '风湿科', 4),
(2430, 128, 'select1', '眼科', 5),
(2431, 128, 'select1', '骨科', 6),
(2432, 128, 'select1', '儿科', 7),
(2433, 128, 'select1', '泌尿科', 8),
(2434, 128, 'select1', '肝病科', 9),
(2435, 128, 'select1', '不孕不育', 10),
(2436, 128, 'select1', '妇科', 11),
(2437, 128, 'select1', '性交障碍', 12),
(2438, 128, 'select1', '性病科', 13),
(2439, 128, 'select1', '内分泌科', 14),
(2440, 128, 'select1', '肿瘤科', 15),
(2441, 128, 'select1', '男科', 16),
(2442, 128, 'select1', '其它', 17),
(2444, 130, 'select1', '摄影', 2),
(2445, 130, 'select1', '翻译', 3),
(2446, 130, 'select1', '装卸工', 4),
(2447, 130, 'select1', '地勤', 5),
(2448, 130, 'select1', '杂务', 6),
(2449, 130, 'select1', '其他', 7),
(2451, 130, 'select2', '100元以下', 2),
(2452, 130, 'select2', '100元—200元', 3),
(2453, 130, 'select2', '200元—300元', 4),
(2454, 130, 'select2', '300元—500元', 5),
(2455, 130, 'select2', '500元以上', 6),
(2457, 131, 'select1', '中学理科', 2),
(2458, 131, 'select1', '小学英语', 3),
(2459, 131, 'select1', '中学文科', 4),
(2460, 131, 'select1', '文艺体育', 5),
(2461, 131, 'select1', '奥数', 6),
(2462, 131, 'select1', '其他', 7),
(2464, 131, 'select2', '30元以下', 2),
(2465, 131, 'select2', '30元—50元', 3),
(2466, 131, 'select2', '50元—70元', 4),
(2467, 131, 'select2', '70元—100元', 5),
(2468, 131, 'select2', '100元—150元', 6),
(2469, 131, 'select2', '150元以上', 7),
(2471, 132, 'select1', '派发', 2),
(2472, 132, 'select1', '促销', 3),
(2473, 132, 'select1', '其它 ', 4),
(2475, 132, 'select2', '10元以下', 2),
(2476, 132, 'select2', '10元—20元', 3),
(2477, 132, 'select2', '20元—30元', 4),
(2478, 132, 'select2', '30元—70元', 5),
(2479, 132, 'select2', '70元以上', 6),
(2481, 133, 'select1', '礼仪', 2),
(2482, 133, 'select1', '模特', 3),
(2483, 133, 'select1', '其它', 4),
(2485, 133, 'select2', '20元以下', 2),
(2486, 133, 'select2', '20元—30元', 3),
(2487, 133, 'select2', '30元—60元', 4),
(2488, 133, 'select2', '60元—100元', 5),
(2489, 133, 'select2', '100元—150元', 6),
(2490, 133, 'select2', '150元以上', 7),
(2492, 134, 'select1', '10元以下', 2),
(2493, 134, 'select1', '10元—20元', 3),
(2494, 134, 'select1', '20元—40元', 4),
(2495, 134, 'select1', '40元—70元', 5),
(2496, 134, 'select1', '70元以上', 6),
(2498, 135, 'select1', '10元以下', 2),
(2499, 135, 'select1', '10元—20元', 3),
(2500, 135, 'select1', '20元—30元', 4),
(2501, 135, 'select1', '30元以上', 5),
(2503, 135, 'select2', '上午', 2),
(2504, 135, 'select2', '中午', 3),
(2505, 135, 'select2', '下午', 4),
(2506, 135, 'select2', '晚间', 5),
(2507, 135, 'select2', '晚9点以后', 6),
(2508, 135, 'select2', '其它', 7),
(2510, 136, 'select1', '设计', 2),
(2511, 136, 'select1', '网站', 3),
(2512, 136, 'select1', '其它', 4),
(2514, 136, 'select2', '50元以下', 2),
(2515, 136, 'select2', '50—100', 3),
(2516, 136, 'select2', '100—200', 4),
(2517, 136, 'select2', '200以上', 5),
(2519, 137, 'select1', '50元以下', 2),
(2520, 137, 'select1', '50元—100元', 3),
(2521, 137, 'select1', '100元—200元', 4),
(2522, 137, 'select1', '200元以上', 5),
(2524, 138, 'select1', '50元以下', 2),
(2525, 138, 'select1', '50元—100元', 3),
(2526, 138, 'select1', '100元—200元', 4),
(2527, 138, 'select1', '200元以上', 5),
(2529, 139, 'select1', '50元以下', 2),
(2530, 139, 'select1', '50元—100元', 3),
(2531, 139, 'select1', '100元—200元', 4),
(2532, 139, 'select1', '200元—500元', 5),
(2533, 139, 'select1', '500元以上', 6),
(2535, 140, 'select1', '50元以下', 2),
(2536, 140, 'select1', '50元—100元', 3),
(2537, 140, 'select1', '100元—200元', 4),
(2538, 140, 'select1', '200元以上', 5),
(2540, 141, 'select1', '50元以下', 2),
(2541, 141, 'select1', '50元—100元', 3),
(2542, 141, 'select1', '100元—200元', 5),
(2543, 141, 'select1', '200元以上', 6),
(2545, 142, 'select1', '泰迪', 2),
(2546, 142, 'select1', '金毛', 3),
(2547, 142, 'select1', '比熊', 4),
(2548, 142, 'select1', '萨摩耶', 5),
(2549, 142, 'select1', '阿拉斯加', 6),
(2550, 142, 'select1', '博美', 7),
(2551, 142, 'select1', '哈士奇', 8),
(2552, 142, 'select1', '拉布拉多', 9),
(2553, 142, 'select1', '德国牧羊犬', 10),
(2554, 142, 'select1', '松狮', 11),
(2555, 142, 'select1', '秋田犬', 12),
(2556, 142, 'select1', '吉娃娃', 13),
(2557, 142, 'select1', '藏獒', 14),
(2558, 142, 'select1', '雪纳瑞', 15),
(2559, 142, 'select1', '贵宾', 16),
(2560, 142, 'select1', '边境牧羊犬', 17),
(2561, 142, 'select1', '巴哥犬', 18),
(2562, 142, 'select1', '古牧', 19),
(2563, 142, 'select1', '罗威纳', 20),
(2564, 142, 'select1', '银狐犬', 21),
(2565, 142, 'select1', '杜宾犬', 22),
(2566, 142, 'select1', '京巴', 23),
(2567, 142, 'select1', '比特', 24),
(2568, 142, 'select1', '苏格兰牧羊犬', 25),
(2569, 142, 'select1', '高加索犬', 26),
(2570, 142, 'select1', '灵缇犬', 27),
(2571, 142, 'select1', '西高地', 28),
(2572, 142, 'select1', '马犬', 29),
(2573, 142, 'select1', '喜乐蒂', 30),
(2574, 142, 'select1', '牛头梗', 31),
(2575, 142, 'select1', '雪橇犬', 32),
(2576, 142, 'select1', '西施犬', 33),
(2577, 142, 'select1', '大白熊', 34),
(2578, 142, 'select1', '卡斯罗', 35),
(2579, 142, 'select1', '沙皮犬', 36),
(2580, 142, 'select1', '蝴蝶犬', 37),
(2581, 142, 'select1', '伯恩山犬', 38),
(2582, 142, 'select1', '斗牛犬', 39),
(2583, 142, 'select1', '万能梗', 40),
(2584, 142, 'select1', '小鹿犬', 41),
(2585, 142, 'select1', '猎狐梗', 42),
(2586, 142, 'select1', '威玛烈犬', 43),
(2587, 142, 'select1', '柴犬', 44),
(2588, 142, 'select1', '斑点狗', 45),
(2589, 142, 'select1', '巴吉度猎犬', 46),
(2590, 142, 'select1', '阿富汗猎犬', 47),
(2591, 142, 'select1', '格力犬', 48),
(2592, 142, 'select1', '比格犬', 49),
(2593, 142, 'select1', '大丹犬', 50),
(2594, 142, 'select1', '腊肠犬', 51),
(2595, 142, 'select1', '可卡犬', 52),
(2596, 142, 'select1', '柯基犬', 53),
(2597, 142, 'select1', '圣伯纳', 54),
(2598, 142, 'select1', '其他', 55),
(2600, 142, 'select2', '100元以下', 2),
(2601, 142, 'select2', '100元—300元', 3),
(2602, 142, 'select2', '300元—500元', 4),
(2603, 142, 'select2', '500元—800元', 5),
(2604, 142, 'select2', '800元—1200元', 6),
(2605, 142, 'select2', '1200元—2000元', 7),
(2606, 142, 'select2', '2000元以上', 8),
(2608, 142, 'select3', '公', 2),
(2609, 142, 'select3', '牧', 3),
(2611, 143, 'select1', '其他水族', 2),
(2612, 143, 'select1', '奇石盆景', 3),
(2613, 143, 'select1', '玩赏鸟', 4),
(2614, 143, 'select1', '观赏鱼', 5),
(2615, 143, 'select1', '其他', 6),
(2617, 143, 'select2', '100元以下', 2),
(2618, 143, 'select2', '100元—300元', 3),
(2619, 143, 'select2', '300元—500元', 4),
(2620, 143, 'select2', '500元—800元', 5),
(2621, 143, 'select2', '800元—1200元', 6),
(2622, 143, 'select2', '1200元—2000元', 7),
(2623, 143, 'select2', '2000元以上', 8),
(2625, 144, 'select1', '赠送', 2),
(2626, 144, 'select1', '求赠', 3),
(2628, 144, 'select2', '狗', 2),
(2629, 144, 'select2', '猫', 3),
(2630, 144, 'select2', '鼠', 4),
(2631, 144, 'select2', '兔', 5),
(2632, 144, 'select2', '鸟', 6),
(2633, 144, 'select2', '水族', 7),
(2634, 144, 'select2', '蛇', 8),
(2635, 144, 'select2', '猪', 9),
(2636, 144, 'select2', '其他', 10),
(2638, 145, 'select1', '蓝猫', 2),
(2639, 145, 'select1', '短毛猫', 3),
(2640, 145, 'select1', '折耳猫', 4),
(2641, 145, 'select1', '加菲猫', 5),
(2642, 145, 'select1', '虎斑猫', 6),
(2643, 145, 'select1', '金吉拉', 7),
(2644, 145, 'select1', '波斯猫', 8),
(2645, 145, 'select1', '暹罗猫', 9),
(2646, 145, 'select1', '豹猫', 10),
(2647, 145, 'select1', '其他', 11),
(2649, 145, 'select2', '100元以下', 2),
(2650, 145, 'select2', '100元—300元', 3),
(2651, 145, 'select2', '300元—500元', 4),
(2652, 145, 'select2', '500元—800元', 5),
(2653, 145, 'select2', '800元—1200元', 6),
(2654, 145, 'select2', '1200元—2000元', 7),
(2655, 145, 'select2', '2000元以上', 8),
(2657, 145, 'select3', '公', 2),
(2658, 145, 'select3', '母', 3),
(2660, 146, 'select1', '配种', 2),
(2661, 146, 'select1', '训练', 3),
(2662, 146, 'select1', '寄养', 4),
(2663, 146, 'select1', '医院', 5),
(2664, 146, 'select1', '美容', 6),
(2665, 146, 'select1', '托运', 7),
(2666, 146, 'select1', '临时照看', 8),
(2667, 146, 'select1', '其他', 9),
(2669, 147, 'select1', '宠物衣服', 2),
(2670, 147, 'select1', '宠物除毛器', 3),
(2671, 147, 'select1', '宠物粮', 4),
(2672, 147, 'select1', '宠物链', 5),
(2673, 147, 'select1', '宠物鞋', 6),
(2674, 147, 'select1', '宠物配饰', 7),
(2675, 147, 'select1', '宠物其它应用', 8),
(2676, 147, 'select1', '宠物其它食品', 9),
(2677, 147, 'select1', '其它', 10),
(2679, 147, 'select2', '供应', 2),
(2680, 147, 'select2', '求购', 3),
(2681, 147, 'select3', '测试1', 100),
(2682, 147, 'select3', '测试2', 100),
(2683, 147, 'select3', '测试3', 100),
(2684, 47, 'select4', '测试1', 1),
(2685, 47, 'select4', '测试2', 2),
(2686, 47, 'select5', '对不对1', 1),
(2687, 47, 'select5', '对不对2', 2),
(2689, 1, 'select4', '单选字段4_2', 100),
(2690, 1, 'select5', '单选字段5_1', 100),
(2691, 1, 'select5', '单选字段5_2', 100);

-- --------------------------------------------------------

--
-- 表的结构 `tu_running_cate_notify`
--

CREATE TABLE IF NOT EXISTS `tu_running_cate_notify` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  KEY `id` (`id`),
  KEY `cate_id` (`cate_id`),
  KEY `user_id` (`user_id`),
  KEY `school_id` (`school_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_running_cate_tag`
--

CREATE TABLE IF NOT EXISTS `tu_running_cate_tag` (
  `tag_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` smallint(5) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `tag_name` varchar(32) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT '100',
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `tu_running_cate_tag`
--

INSERT INTO `tu_running_cate_tag` (`tag_id`, `cate_id`, `type`, `tag_name`, `orderby`) VALUES
(1, 23334, 'select23334', '兼职', 100),
(2, 23334, 'select23334', '服务员', 100),
(3, 23334, 'select23334', '快递', 100),
(4, 23334, 'select23334', '发传单', 100),
(5, 23334, 'select23334', '服务员', 100),
(6, 23334, 'select23334', '家教', 100);

-- --------------------------------------------------------

--
-- 表的结构 `tu_running_delivery`
--

CREATE TABLE IF NOT EXISTS `tu_running_delivery` (
  `delivery_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `SchoolId` int(11) DEFAULT NULL COMMENT '学校',
  `school_id` int(11) DEFAULT NULL,
  `StudentCode` varchar(32) DEFAULT NULL COMMENT '学生代码',
  `RealName` varchar(16) DEFAULT NULL COMMENT '名字',
  `Major` varchar(64) DEFAULT NULL COMMENT '学校',
  `IdCode` varchar(32) DEFAULT NULL COMMENT '身份证',
  `Gender` int(1) DEFAULT '0' COMMENT '男女',
  `EnrollmentDate` varchar(32) DEFAULT NULL COMMENT '入学日期',
  `Department` varchar(32) DEFAULT NULL COMMENT '系',
  `EncryptedData` varchar(128) DEFAULT NULL COMMENT '加密',
  `EncryptedIv` varchar(32) DEFAULT NULL COMMENT 'IV',
  `FileList` varchar(256) DEFAULT NULL,
  `PicUrl0` varchar(256) DEFAULT NULL COMMENT '图片1',
  `PicUrl1` varchar(256) DEFAULT NULL COMMENT '图片2',
  `phoneNumber` varchar(32) DEFAULT NULL COMMENT '手机',
  `lat` varchar(32) DEFAULT NULL COMMENT '经度',
  `lng` varchar(32) DEFAULT NULL COMMENT '纬度',
  `num` int(11) DEFAULT '0' COMMENT '抢单数量',
  `audit` tinyint(1) NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `update_time` varchar(32) DEFAULT NULL,
  `create_time_ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`delivery_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_running_delivery_position_logs`
--

CREATE TABLE IF NOT EXISTS `tu_running_delivery_position_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `lat` varchar(32) DEFAULT NULL COMMENT '经度',
  `lng` varchar(32) DEFAULT NULL COMMENT '纬度',
  `create_time` varchar(15) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`user_id`,`lat`,`lng`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_running_money`
--

CREATE TABLE IF NOT EXISTS `tu_running_money` (
  `money_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL COMMENT '城市',
  `area_id` int(11) DEFAULT NULL COMMENT '地区',
  `business_id` int(11) DEFAULT NULL COMMENT '商圈',
  `shop_id` int(11) DEFAULT NULL COMMENT '商家ID',
  `running_id` int(11) DEFAULT NULL COMMENT '跑腿订单号',
  `delivery_id` int(11) DEFAULT NULL COMMENT '配送员ID',
  `user_id` int(11) DEFAULT NULL COMMENT '会员ID',
  `order_id` int(11) DEFAULT NULL COMMENT '原始订单ID',
  `money` int(11) DEFAULT '0' COMMENT '跑腿费',
  `commission` int(11) DEFAULT '0' COMMENT '佣金',
  `type` enum('city','running','money','pinche') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'running' COMMENT '类别',
  `types` tinyint(1) DEFAULT '1' COMMENT '1会员2城市',
  `year` int(11) DEFAULT NULL COMMENT '年',
  `month` int(11) DEFAULT NULL COMMENT '月',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `intro` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`money_id`),
  KEY `shop_id` (`delivery_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_running_product`
--

CREATE TABLE IF NOT EXISTS `tu_running_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Code` varchar(32) DEFAULT NULL,
  `running_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT '0',
  `product_id` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL COMMENT '商家ID',
  `user_id` int(11) DEFAULT NULL,
  `addr_id` int(11) DEFAULT NULL,
  `product_name` varchar(32) DEFAULT NULL COMMENT '菜品名称',
  `CommodityId` int(11) DEFAULT NULL COMMENT '菜品ID',
  `Price` varchar(32) DEFAULT '0' COMMENT '单价',
  `settlement_price` int(11) DEFAULT '0' COMMENT '结算价',
  `Quantity` int(11) DEFAULT '0' COMMENT '数量',
  `OrderStatus` int(11) DEFAULT '0',
  `orderType` tinyint(1) DEFAULT '1' COMMENT '1配送2自提',
  `create_time` varchar(16) DEFAULT NULL,
  `pay_time` varchar(16) DEFAULT NULL,
  `update_time` varchar(16) DEFAULT NULL,
  `end_time` varchar(16) DEFAULT NULL,
  `cancel_time` varchar(16) DEFAULT NULL COMMENT '取消时间',
  `is_print` int(1) DEFAULT '0',
  `closed` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_running_school`
--

CREATE TABLE IF NOT EXISTS `tu_running_school` (
  `school_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `business_id` int(11) DEFAULT NULL,
  `Name` varchar(32) DEFAULT NULL,
  `Region` varchar(32) DEFAULT NULL,
  `lat` varchar(32) DEFAULT NULL,
  `lng` varchar(32) DEFAULT NULL,
  `FreightMoneyCaption` varchar(32) DEFAULT NULL COMMENT '运费说明',
  `MinFreightMoney` varchar(11) NOT NULL DEFAULT '0' COMMENT '最低运费',
  `is_cash` int(1) DEFAULT '0' COMMENT '开启',
  `user` int(11) DEFAULT '0' COMMENT '会员最小',
  `user_big` int(11) DEFAULT '0' COMMENT '会员最大',
  `user_cash_commission` int(11) DEFAULT '0' COMMENT '会员佣金',
  `shop` int(11) DEFAULT '0' COMMENT '商家最小',
  `shop_big` int(11) DEFAULT '0' COMMENT '商家最大',
  `shop_cash_commission` int(11) DEFAULT '0' COMMENT '商家佣金',
  `user_cash_second` int(11) DEFAULT '1' COMMENT '会员每日提现',
  `shop_cash_second` int(11) DEFAULT '1' COMMENT '商家每日提现',
  `admin_yongjin_rate` int(11) DEFAULT '0',
  `city_yongjin_rate` int(11) DEFAULT '0',
  `orderby` int(11) DEFAULT '500',
  `audit` tinyint(1) NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`school_id`),
  KEY `school_id` (`school_id`),
  KEY `school_id_2` (`school_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_running_school_shop`
--

CREATE TABLE IF NOT EXISTS `tu_running_school_shop` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `orderby` int(11) DEFAULT '500',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_sensitive_words`
--

CREATE TABLE IF NOT EXISTS `tu_sensitive_words` (
  `words_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `words` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`words_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_seo`
--

CREATE TABLE IF NOT EXISTS `tu_seo` (
  `seo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `seo_key` varchar(32) DEFAULT NULL,
  `seo_explain` varchar(1024) DEFAULT NULL,
  `seo_title` varchar(1024) DEFAULT NULL,
  `seo_keywords` varchar(1024) DEFAULT NULL,
  `seo_desc` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`seo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

--
-- 转存表中的数据 `tu_seo`
--

INSERT INTO `tu_seo` (`seo_id`, `seo_key`, `seo_explain`, `seo_title`, `seo_keywords`, `seo_desc`) VALUES
(1, 'index_index', '首页SEO配置\r\n', '{city_name}在线-{city_name}第一网络媒体,{city_name}房产,{city_name}招聘!【官网】', '{sitename},{sitename}网,{city_name}门户,{city_name}门户网', '{city_name}{sitename}网-{city_name}抢购首选,专业品质{city_name}{sitename}网!为您提供{city_name}美食,电影票,KTV,酒店,旅游等{city_name}{sitename}大全,{city_name}网站大全{city_name}{sitename}网,您身边的吃喝玩乐好帮手!'),
(3, 'news_detail', '文章详情\r\n1、{title}标题\r\n2、{keywords}关键字\r\n3、{desc}描述\r\n4、{cate_name}分类', '{title}-{sitename}', '{keywords}', '{desc}'),
(5, 'coupon_index', '优惠券列表\r\n1、{area_name}地区\r\n2、{cate_name}', '{area_name}{cate_name}商户哪家好？{sitename}', '{area_name}{cate_name}商户哪家好？{sitename}', '{area_name}{cate_name}商户哪家好？{sitename}'),
(6, 'coupon_detail', '优惠券详情\r\n1、{shop_name}商家名称\r\n2、{title}优惠券标题\r\n', '商户{shop_name}的优惠券“{title}”', '商户{shop_name}的优惠券“{title}”', '商户{shop_name}的优惠券“{title}”'),
(7, 'tuan_detail', '生活抢购详情\r\n1、{shop_name}商家名称\r\n2、{title}优惠券标题', '{title} -{city_name}{sitename}网。\r\n', '{cate_area},{cate_business},{cate_name},{shop_name},{city_name}抢购,打折,{city_name}打折,{sitename}{city_name}站,{sitename}', '{intro}'),
(8, 'tuan_index', '团购列表\r\n1、{area_name}地区\r\n2、{cate_name}\r\n3、{business_name}', '{city_name}{area_name}{business_name}{cate_name}本地生活团购！吃喝玩乐一切都在{sitename}！', '{city_name}{area_name}{business_name}{cate_name}本地生活团购！吃喝玩乐一切都在{sitename}！', '{city_name}{area_name}{business_name}{cate_name}本地生活团购！吃喝玩乐一切都在{sitename}！'),
(9, 'news_index', '新闻列表\r\n1、{cate_name} 分类', '{sitename}_{city_name}资讯网', '{cate_name}，{sitename}', '{sitename}_{city_name}本地最具有价值的资讯网站'),
(13, 'shop_index', '商家列表\r\n', '{city_name}{sitename}商家大全', '{area_name}{cate_name}{business_name}商家列表，{city_name}{sitename}', '{city_name}{sitename}最权威的商家展示平台。'),
(14, 'shop_detail', '商家详情\r\n', '{shop_name}{shop_tel}', '{shop_name}，{shop_tel}，{addr},{cate_area},{cate_business}', '{details}'),
(15, 'app_index', 'APP页面', '{sitename}_手机客户端下载', '手机客户端，{sitename}手机客户端', '{sitename}手机客户端是服务于{city_name}地区的专业生活服务软件。'),
(18, 'activity_index', '活动类型：1、{cate_name} 2、{area_name}', '{city_name}{area_name}活动首页', '{city_name}{area_name},{cate_name}活动,{sitename}', '{city_name}{area_name}最全的{cate_name}活动.'),
(19, 'activity_detail', '活动详情\r\n1、{title}标题\r\n2、{shop_name}商家名称', '{title}-{city_name}{sitename}', '{cate_name},{shop_name},{cate_area},{cate_business},{title},{addr}', '{intro}'),
(20, 'ele_ding', '订座列表\r\n1、{cate_name}分类\r\n2、{area_name}地区\r\n3、{business_name}商圈', '{area_name}{cate_name}{business_name}订座列表', '{area_name}{cate_name}{business_name}订座列表', '{area_name}{cate_name}{business_name}订座列表'),
(21, 'mall_index', '购物列表\r\n1、{cate_name}分类\r\n2、{area_name}地区\r\n3、{business_name}商圈', '{city_name}{area_name}{cate_name}{business_name}网上购物商城', '{area_name}{cate_name}{business_name}', '{area_name}{cate_name}{business_name}{city_name}{sitename}'),
(22, 'mall_detail', '购物详情\r\n1、{shop_name}商家名称\r\n2、{title}商品标题', '{title}-{city_name}{sitename}', '{cate_name}，{cate_area}，{cate_business}，{shop_name}', '{intro}'),
(25, 'jifen_index', '积分兑换列表', '积分兑换列表', '积分兑换列表', '积分兑换列表'),
(26, 'jifen_detail', '积分商品&quot;{title}&quot;详情', '积分商品&quot;{title}&quot;详情', '积分商品&quot;{title}&quot;详情', '积分商品&quot;{title}&quot;详情'),
(27, 'ele_detail', '订餐详情\r\n1、{shop_name}商家名称\r\n2、{shop_tel}订餐电话', '{city_name}{shop_name}的订餐电话“{shop_tel}”。', '{shop_name}，{city_name}{shop_name}，{shop_tel}', '{shop_name}是{city_name}{shop_name}最优秀的外卖商家。'),
(28, 'ele_shop', '订餐详情', '{city_name}{sitename}点餐页', '{sitename}', '{sitename}外卖，是最好的外卖。'),
(29, 'ele_index', '餐饮美食首页', '点外卖上{city_name}{sitename}', '{city_name}{sitename}外卖，{city_name}{sitename}点餐，{city_name}{sitename}包厢，{city_name}{sitename}送餐', '{city_name}当地做好的外卖网站。'),
(30, 'life_index', '分类搜索页', '{city_name}新{channel_name}{cate_name}- {sitename}分类信息网！', '{city_name}分类信息网,{sitename}新{channel_name}{cate_name},{area_name}{business_name}新{channel_name}{cate_name}', '{city_name}{channel_name}{cate_name}频道为您提供{channel_name}{cate_name}信息，在此有大量{channel_name}{cate_name}信息供您选择，您可以免费查看和发布{channel_name}{cate_name}信息。'),
(31, 'life_detail', '生活信息详情\r\n', '{title} - {city_name}{channel} {cate}- {sitename}', '{text1}{channel}{cate},价格{num},{title},{channel}{cate}', '{desc}-{sitename}。'),
(32, 'life_main', '分类信息首页', '{city_name}分类信息网-免费发布信息-{sitename}信息网', '{city_name},{city_name}分类信息,{city_name}免费发布分类信息，{city_name}{city_name}网', '{city_name}分类信息网为您提供中国分类信息，您可以免费查找{city_name}最新最全的二手物品交易、二手车买卖、房屋租售、宠物、招聘、兼职、求职、交友活动、生活服务信息。免费发布尽在找啥有分类信息网。'),
(33, 'life_fabu', '发布生活信息', '{city_name}发布生活信息', '{city_name}发布生活信息', '{city_name}发布生活信息'),
(34, 'shop_apply', '商家入驻', '{city_name}商家入驻申请', '{city_name}商家入驻申请', '{city_name}商家入驻申请，做一个优秀发本地O2O平台。'),
(36, 'ele_main', '餐饮美食主页', '点外卖上{city_name}{sitename}', '{city_name}{sitename}外卖，{city_name}{sitename}点餐，{city_name}{sitename}包厢，{city_name}{sitename}送餐', '{city_name}当地做好的外卖网站。'),
(37, 'ele_takeout', '外卖点餐', '{city_name}{sitename}点餐首页', '{city_name}{sitename}点餐', '最好的外卖网站，{city_name}{sitename}网。'),
(38, 'ele_pay', '订单支付页面', '{city_name}外卖订单支付', '订单支付', '订单支付-{sitename}'),
(39, 'tuan_main', '抢购首页', '{sitename}-{sitename}网抢购-超人气抢购网站-抢购上{sitename}', '抢购,{city_name},{sitename},{sitename}抢购,{sitename}抢购网,抢购大全', '{city_name}{sitename}抢购，是做好的在线抢购网站！'),
(41, 'billboard_index', '榜单首页', '{city_name}{sitename}榜单首页', '{city_name}{sitename}榜单，{city_name}{sitename}商家排行榜，{city_name}{sitename}抢购排行，{city_name}{sitename}商品排行', '{city_name}{sitename}权威的数据中心。'),
(42, 'billboard_bdlist', '榜单内容', '{city_name}{cate_name} 榜单', '{city_name}{cate_name}， {city_name}{sitename}', '{city_name}{sitename}榜单中心。'),
(43, 'billborder_bddetails', '榜单详情', '{title}-{city_name}{sitename}', '{title},{city_name}{sitename}', '{city_name}{sitename}榜单排行网站。'),
(44, 'jifen_detail', '积分主页', '{title}{city_name}{sitename}', '{price},{num},{title},{sitename}', '{details}'),
(45, 'jifen_inex', '积分首页', '{city_name}{sitename}一积分兑换', '{city_name}积分兑换，{city_name}积分，{city_name}礼品兑换，{city_name}兑换商品，{sitename}', '可用积分在{city_name}{sitename}上兑换商品哦！'),
(63, 'appoint_index', '\r\n1、{area_name}地区\r\n2、{cate_name}\r\n3、{business_name}', '{city_name}{area_name}{business_name}{cate_name}家政，预约家政来{sitename}', '{city_name}{area_name}{business_name}{cate_name}家政，预约家政来{sitename}', '{city_name}{area_name}{business_name}{cate_name}家政，预约家政来{sitename}'),
(64, 'appoint_detail', '{city_name}详情页', '{title} -{city_name}{sitename}', '{cate_area},{cate_business},{cate_name},{shop_name},{city_name}家政,{sitename}{city_name}站,{sitename}', '{intro}');

-- --------------------------------------------------------

--
-- 表的结构 `tu_session`
--

CREATE TABLE IF NOT EXISTS `tu_session` (
  `session_id` varchar(64) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` blob,
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_setting`
--

CREATE TABLE IF NOT EXISTS `tu_setting` (
  `k` varchar(255) NOT NULL DEFAULT '',
  `v` text,
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `tu_setting`
--

INSERT INTO `tu_setting` (`k`, `v`) VALUES
('site', 'a:19:{s:8:"sitename";s:9:"青鸟帮帮";s:4:"host";s:28:"https://paotui.jintao365.com";s:5:"https";s:1:"1";s:6:"hostdo";s:13:"jintao365.com";s:11:"host_prefix";s:6:"paotui";s:4:"logo";s:43:"/attachs/2018/12/11/thumb_5c0fc3b9cfcf9.jpg";s:6:"wxcode";s:43:"/attachs/2018/12/11/thumb_5c0fc3bbca3b4.jpg";s:2:"qq";s:9:"120585022";s:3:"tel";s:9:"137756874";s:5:"email";s:0:"";s:3:"icp";s:0:"";s:5:"title";s:12:"校园跑腿";s:7:"keyword";s:12:"校园跑腿";s:11:"description";s:12:"校园跑腿";s:6:"tongji";s:0:"";s:7:"city_id";s:1:"1";s:3:"lng";s:10:"116.413614";s:3:"lat";s:9:"39.910949";s:9:"web_close";s:1:"1";}'),
('sms', 'a:12:{s:5:"dxapi";s:2:"bo";s:3:"url";s:100:"http://api.smsbao.com/sms?u=xiaozhushangye&p=a68ed68ef9273d75c70bed392f7692d3&m={mobile}&c={content}";s:7:"charset";s:1:"0";s:4:"curl";s:4:"file";s:15:"sms_bao_account";s:14:"xiaozhushangye";s:16:"sms_bao_password";s:20:"zmd2327317zmd2327317";s:4:"code";s:0:"";s:12:"dayu_version";s:1:"2";s:4:"sign";s:15:"无极生活网";s:5:"dykey";s:16:"LTAIRr5BrpGAX4S5";s:8:"dysecret";s:31:"ORFC2KjbNpzeMvh9qzm3bYoUrE2nUf ";s:10:"yunpianApi";s:0:"";}'),
('weixin', 'a:9:{s:5:"token";s:18:"wx0ba96e5ac1965605";s:5:"appid";s:18:"wxabc451516ef7ab8a";s:9:"appsecret";s:32:"43d7a9eb0722ae343084f00e718d02fb";s:4:"type";s:1:"1";s:11:"description";s:24:"你好，欢迎关注！";s:5:"title";s:27:"你还，欢迎关注我们";s:7:"linkurl";s:22:"http://www.lzamai.com/";s:5:"photo";s:54:"http://images4.5maiche.cn/2016-05-21_57406ca94935d.jpg";s:9:"user_auto";s:1:"1";}'),
('integral', 'a:24:{s:3:"buy";s:1:"0";s:10:"is_restore";s:1:"0";s:12:"restore_type";s:1:"3";s:14:"restore_points";s:2:"20";s:16:"is_goods_restore";s:1:"1";s:14:"is_ele_restore";s:1:"1";s:15:"is_tuan_restore";s:1:"1";s:15:"return_integral";s:1:"0";s:6:"return";s:1:"1";s:20:"tuan_return_integral";s:1:"1";s:20:"mall_return_integral";s:1:"1";s:17:"integral_exchange";s:1:"1";s:21:"integral_exchange_tax";s:2:"40";s:23:"integral_exchange_small";s:3:"100";s:21:"integral_exchange_big";s:4:"1000";s:5:"share";s:1:"2";s:5:"reply";s:1:"2";s:6:"mobile";s:1:"2";s:5:"email";s:1:"2";s:4:"sign";s:1:"2";s:8:"register";s:1:"2";s:7:"useraux";s:1:"2";s:9:"firstsign";s:1:"2";s:4:"life";s:1:"2";}'),
('weixinmenu', 'a:2:{s:6:"button";a:3:{i:1;a:2:{s:4:"name";s:6:"首页";s:3:"url";s:0:"";}i:2;a:2:{s:4:"name";s:6:"新版";s:3:"url";s:0:"";}i:3;a:2:{s:4:"name";s:6:"一级";s:3:"url";s:0:"";}}s:5:"child";a:3:{i:1;a:5:{i:1;a:2:{s:4:"name";s:7:"外卖2";s:3:"url";s:35:"http://demo2.jintao365.com/wap/ele/";}i:2;a:2:{s:4:"name";s:7:"商城2";s:3:"url";s:31:"http://demo2.jintao365.com/wap/";}i:3;a:2:{s:4:"name";s:7:"商家3";s:3:"url";s:35:"http://demo2.jintao365.com/wap/shop";}i:4;a:2:{s:4:"name";s:9:"自媒体";s:3:"url";s:35:"http://demo2.jintao365.com/wap/news";}i:5;a:2:{s:4:"name";s:12:"分类信息";s:3:"url";s:30:"http://demo2.jintao365.com/wap";}}i:2;a:5:{i:1;a:2:{s:4:"name";s:6:"团购";s:3:"url";s:35:"http://demo2.jintao365.com/wap/tuan";}i:2;a:2:{s:4:"name";s:15:"一元云购物";s:3:"url";s:36:"http://demo2.jintao365.com/wap/cloud";}i:3;a:2:{s:4:"name";s:6:"家政";s:3:"url";s:35:"http://demo2.jintao365.com/wap/life";}i:4;a:2:{s:4:"name";s:6:"小区";s:3:"url";s:40:"http://demo2.jintao365.com/wap/community";}i:5;a:2:{s:4:"name";s:12:"社区村镇";s:3:"url";s:38:"http://demo2.jintao365.com/wap/village";}}i:3;a:5:{i:1;a:2:{s:4:"name";s:13:"店员管理1";s:3:"url";s:33:"http://demo2.jintao365.com/worker";}i:2;a:2:{s:4:"name";s:13:"小区管理2";s:3:"url";s:31:"http://demo2.jintao365.com/wuye";}i:3;a:2:{s:4:"name";s:13:"商户管理3";s:3:"url";s:33:"http://demo2.jintao365.com/seller";}i:4;a:2:{s:4:"name";s:13:"物流平台4";s:3:"url";s:35:"http://demo2.jintao365.com/delivery";}i:5;a:2:{s:4:"name";s:12:"会员中心";s:3:"url";s:31:"http://demo2.jintao365.com/user";}}}}'),
('shop', 'a:8:{s:11:"is_shop_zan";s:1:"1";s:11:"day_zan_num";s:1:"5";s:16:"day_shop_zan_num";s:1:"1";s:19:"zan_reward_integral";s:1:"0";s:13:"express_price";s:1:"3";s:10:"commission";s:1:"8";s:17:"shop_apply_prrice";s:1:"0";s:4:"time";i:1542025368;}'),
('ele', 'a:5:{s:19:"tableware_price_max";s:1:"2";s:20:"tableware_price_mini";s:1:"0";s:23:"past_due_ele_order_time";s:2:"15";s:17:"user_addr_default";s:1:"1";s:4:"time";i:1552551883;}'),
('register', 'a:14:{s:4:"bind";s:1:"0";s:21:"register_distribution";s:1:"0";s:16:"register_service";s:1:"1";s:20:"register_service_url";s:29:"https://fenxiao.jintao365.com";s:21:"register_service_info";s:110:"         注册服务协议\r\n\r\n   1：请大家遵守当地法律法规！\r\n   2：请不要发不良言论!\r\n";s:15:"register_mobile";s:1:"0";s:12:"register_yzm";s:1:"1";s:17:"register_password";s:1:"4";s:16:"wap_register_yzm";s:1:"0";s:22:"wap_register_password2";s:1:"1";s:18:"register_lock_numl";s:1:"5";s:21:"register_is_lock_time";s:3:"900";s:24:"register_register_ip_num";s:1:"2";s:30:"register_register_is_lock_time";s:3:"600";}'),
('cash', 'a:9:{s:7:"is_cash";s:1:"1";s:4:"user";s:3:"100";s:8:"user_big";s:3:"500";s:20:"user_cash_commission";s:3:"1.8";s:4:"shop";s:3:"100";s:8:"shop_big";s:4:"1000";s:20:"shop_cash_commission";s:3:"3.9";s:16:"user_cash_second";s:1:"3";s:16:"shop_cash_second";s:1:"1";}'),
('running', 'a:16:{s:7:"freight";s:1:"5";s:13:"interval_time";s:2:"60";s:6:"prompt";s:79:"跑脚适用于让人帮买东西、帮助送个货等场景，跑脚5元起步";s:30:"running_weixin_original_refund";s:1:"1";s:34:"running_weixin_original_refund_mix";s:3:"100";s:4:"rate";s:2:"10";s:10:"tongshiNum";s:3:"120";s:11:"tongshiTime";s:1:"3";s:20:"ErrandTimeRangeBegin";s:5:"14:00";s:18:"ErrandTimeRangeEnd";s:5:"22:00";s:19:"FreightMoneyCaption";s:9:"雨雪天";s:15:"MinFreightMoney";s:4:"0.01";s:29:"NormalDeliveryAllowOrderTypes";s:1:"1";s:20:"ExpressCostArticleId";s:1:"1";s:22:"ErrandServiceArticleId";s:1:"1";s:9:"card_open";s:1:"0";}'),
('pay', 'a:4:{s:13:"force_respond";s:1:"0";s:4:"auto";s:6:"weixin";s:4:"cash";s:6:"weixin";s:15:"is_wap_password";s:1:"1";}'),
('config', 'a:10:{s:8:"iocnfont";s:50:"//at.alicdn.com/t/font_295173_xnubd8xdu6czyqfr.css";s:9:"iocnfont2";s:0:"";s:16:"false_order_open";s:1:"1";s:15:"false_order_num";s:2:"20";s:19:"false_order_user_id";s:7:"1|2|3|4";s:21:"false_order_school_id";s:3:"1|2";s:23:"false_order_delivery_id";s:3:"1|2";s:17:"false_order_title";s:81:"我要下单|我要买东西|帮我买一个书包谢谢|你好你今年多大了";s:21:"false_order_money_mix";s:1:"5";s:21:"false_order_money_big";s:2:"30";}'),
('delivery', 'a:13:{s:13:"interval_time";s:2:"30";s:3:"num";s:1:"3";s:8:"pei_type";s:1:"1";s:12:"pei_type_1km";s:1:"4";s:12:"pei_type_2km";s:1:"5";s:12:"pei_type_3km";s:3:"6.2";s:12:"pei_type_4km";s:3:"7.2";s:12:"pei_type_5km";s:3:"8.2";s:12:"pei_type_6km";s:3:"9.2";s:12:"pei_type_7km";s:4:"10.2";s:2:"d1";s:12:"描述相符";s:2:"d2";s:12:"服务态度";s:2:"d3";s:12:"快速守时";}'),
('wxapp', 'a:16:{s:5:"appid";s:18:"wx9d0412b0183467da";s:9:"appsecret";s:32:"679b6245ff1f88818f87a5364ab17aa1";s:7:"is_tzdz";s:1:"0";s:7:"is_pgzf";s:1:"1";s:10:"top_type_1";s:3:"0.1";s:10:"top_type_2";s:3:"0.2";s:10:"top_type_3";s:3:"0.3";s:8:"is_bdtel";s:1:"0";s:5:"is_ff";s:1:"1";s:4:"tzmc";s:6:"信息";s:7:"formid1";s:43:"S_Euw9efBSD_16kcQjxcGe8vPLFmoQzLaV_ISI9Xjow";s:7:"formid2";s:43:"zfUgU-prQfKWiYROlgixM7I_8wS76JHcT0Vv1OC391o";s:4:"tid3";s:43:"JqLdW9BKkDptQ_OkK_KBOG2_enT-_ENSxaK2VllZV9U";s:6:"hp_tid";s:43:"xNyzv33heMh21EOSyiCpKu4Cq8hqPprid_BB6Lhe3w8";s:5:"z_tid";s:43:"b2HQhWtBee72oW9LYLJWY8WlwcJP5mouQ3mXMXax1Mc";s:6:"tg_tid";s:43:"HOAOZgnOb-ckltl1KFgOQTYLXAmeEltN-KjDzDDGtFE";}');

-- --------------------------------------------------------

--
-- 表的结构 `tu_shop`
--

CREATE TABLE IF NOT EXISTS `tu_shop` (
  `shop_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL COMMENT 'school_id',
  `user_id` int(11) DEFAULT NULL,
  `cate_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL COMMENT '商家等级',
  `wap_template_id` tinyint(1) DEFAULT '0' COMMENT '手机模板',
  `pc_template_id` tinyint(1) DEFAULT '0' COMMENT '电脑模板',
  `area_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT '0',
  `type_id` char(20) DEFAULT '0',
  `business_id` int(11) DEFAULT NULL,
  `shop_name` varchar(64) DEFAULT NULL,
  `logo` varchar(128) DEFAULT NULL,
  `photo` varchar(128) DEFAULT NULL,
  `service_weixin_qrcode` varchar(255) DEFAULT NULL COMMENT '客服微信二维码',
  `tel` varchar(64) DEFAULT NULL,
  `extension` varchar(8) DEFAULT NULL,
  `qq` varchar(10) NOT NULL,
  `mobile` varchar(11) DEFAULT '0',
  `contact` varchar(32) DEFAULT NULL,
  `addr` varchar(64) DEFAULT NULL,
  `score` tinyint(3) DEFAULT '0' COMMENT '评价',
  `score_num` int(11) DEFAULT '0',
  `fans_num` int(11) DEFAULT '0',
  `zan_num` int(11) DEFAULT '0' COMMENT '点赞数量',
  `d1` tinyint(3) DEFAULT '0',
  `d2` tinyint(3) DEFAULT '0',
  `d3` tinyint(3) DEFAULT '0' COMMENT '点评项目',
  `panorama_url` varchar(128) DEFAULT NULL COMMENT '商家全景URL',
  `orderby` int(11) DEFAULT '100' COMMENT '越小排序越高',
  `lng` varchar(15) DEFAULT NULL,
  `lat` varchar(15) DEFAULT NULL,
  `ding_num` int(10) DEFAULT '0',
  `closed` tinyint(1) DEFAULT '0',
  `view` int(11) DEFAULT '0',
  `audit` tinyint(1) DEFAULT '0' COMMENT '1代表已经审核',
  `recognition` tinyint(1) NOT NULL DEFAULT '0',
  `is_online` int(11) DEFAULT '0' COMMENT '1是农村电商',
  `is_tuan_pay` tinyint(1) DEFAULT '1' COMMENT '抢购在线支付',
  `is_hotel_pay` tinyint(1) DEFAULT '1' COMMENT '酒店在线支付',
  `is_mall_pay` tinyint(1) DEFAULT '1' COMMENT '商城在线支付',
  `is_ele_pay` tinyint(1) DEFAULT '1' COMMENT '外卖在线支付',
  `is_ele_pei` tinyint(1) DEFAULT '0' COMMENT '外卖配送',
  `is_taking` tinyint(1) DEFAULT '0' COMMENT '自动接单',
  `is_market_pei` int(1) DEFAULT '0' COMMENT '菜市场配送',
  `is_store_pei` int(1) DEFAULT '0' COMMENT '便利店配送',
  `is_goods_pei` tinyint(1) DEFAULT '0' COMMENT '商城配送',
  `is_ding` tinyint(1) DEFAULT '0' COMMENT '针对餐饮行业的订座',
  `is_biz` tinyint(1) DEFAULT '0',
  `is_profit` tinyint(1) DEFAULT '0',
  `is_renzheng` int(1) NOT NULL DEFAULT '0',
  `is_ele_print` tinyint(4) DEFAULT '1',
  `is_ele_print_type` tinyint(1) DEFAULT '0',
  `is_tuan_print` tinyint(4) DEFAULT '0',
  `is_goods_print` tinyint(4) DEFAULT '0',
  `is_booking_print` tinyint(4) DEFAULT '0' COMMENT '1开启家政打印',
  `is_appoint_print` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1开启家政打印',
  `is_goods_backers` tinyint(1) DEFAULT '0' COMMENT '1开启商城推手',
  `is_ele_backers` tinyint(1) DEFAULT '0' COMMENT '1开启外卖推手',
  `is_restore_prestige` tinyint(1) DEFAULT NULL COMMENT '是否开启返威望',
  `is_shop_earnest` tinyint(1) DEFAULT '0' COMMENT '0关闭1开启',
  `earnest` int(11) DEFAULT '0' COMMENT '定金比例',
  `tags` varchar(64) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `tui_uid` int(11) DEFAULT '0' COMMENT '代理商',
  `apiKey` varchar(100) NOT NULL,
  `mKey` varchar(100) NOT NULL,
  `partner` varchar(100) NOT NULL,
  `machine_code` varchar(100) NOT NULL,
  `service` text COMMENT '各种统计代码',
  `service_audit` tinyint(1) NOT NULL DEFAULT '0',
  `express_price` int(11) DEFAULT '0' COMMENT '抢单模式下配送费为0给抢单员默认运费',
  `shop_apply_prrice` int(11) DEFAULT '0' COMMENT '商家入驻费用',
  `commission` int(11) DEFAULT '0' COMMENT '商家佣金用于商城结算',
  `bg_date` date DEFAULT NULL COMMENT '开始时间',
  `end_date` date DEFAULT NULL COMMENT '结束时间',
  `qrcode` varchar(256) DEFAULT NULL COMMENT '二维码',
  PRIMARY KEY (`shop_id`),
  KEY `cate_id` (`cate_id`,`area_id`,`business_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_shop_banner`
--

CREATE TABLE IF NOT EXISTS `tu_shop_banner` (
  `banner_id` int(10) NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) DEFAULT '0',
  `is_mobile` tinyint(1) DEFAULT '1',
  `photo` varchar(128) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT NULL,
  `audit` tinyint(1) DEFAULT '1',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_shop_branch`
--

CREATE TABLE IF NOT EXISTS `tu_shop_branch` (
  `branch_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `score` tinyint(3) DEFAULT '0',
  `password` varchar(32) DEFAULT '',
  `shop_id` int(11) DEFAULT '0',
  `cate_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT '0',
  `area_id` int(11) DEFAULT '0',
  `business_id` int(11) DEFAULT '0',
  `photo` varchar(128) NOT NULL,
  `addr` varchar(128) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT '100',
  `lng` varchar(15) DEFAULT NULL,
  `lat` varchar(15) DEFAULT NULL,
  `telephone` varchar(11) NOT NULL DEFAULT '',
  `business_time` varchar(64) DEFAULT NULL,
  `d1` tinyint(3) DEFAULT '0',
  `d2` tinyint(3) DEFAULT '0',
  `d3` tinyint(3) DEFAULT '0',
  `score_num` int(10) unsigned NOT NULL,
  `closed` tinyint(1) DEFAULT '0',
  `view` int(11) DEFAULT '0',
  `fans_num` int(11) DEFAULT '0',
  `details` text NOT NULL,
  `audit` tinyint(1) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_shop_cate`
--

CREATE TABLE IF NOT EXISTS `tu_shop_cate` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(32) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `orderby` tinyint(3) DEFAULT '100',
  `is_hot` tinyint(1) DEFAULT '0',
  `d1` varchar(32) DEFAULT '价格',
  `d2` varchar(32) DEFAULT '环境',
  `d3` varchar(32) DEFAULT '服务',
  `book_num` int(11) DEFAULT '0' COMMENT '分类预约人次',
  `title` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

--
-- 转存表中的数据 `tu_shop_cate`
--

INSERT INTO `tu_shop_cate` (`cate_id`, `cate_name`, `parent_id`, `orderby`, `is_hot`, `d1`, `d2`, `d3`, `book_num`, `title`) VALUES
(1, '餐饮美食', 0, 1, 1, '价格', '环境', '服务', 0, '吃尽天下美食'),
(2, '小吃快餐', 1, 11, 0, '口味', '环境', '服务', 0, ''),
(59, '四星级酒店', 13, 33, 0, '价格', '环境', '服务', 0, ''),
(41, '驾校', 8, 72, 0, '价格', '环境', '服务', 0, ''),
(6, '结婚', 0, 4, 0, '价格', '环境', '服务', 0, '一辈子的大事'),
(7, '家居', 0, 6, 0, '价格', '环境', '服务', 0, '家居是一种生活'),
(14, '生活服务', 0, 10, 0, '价格', '环境', '服务', 0, '生活服务尽在附近'),
(8, '汽车', 0, 7, 0, '价格', '环境', '服务', 0, '买车去旅游'),
(9, '丽人', 0, 8, 0, '价格', '环境', '服务', 0, '美丽就在这里开始'),
(10, '母婴', 0, 5, 0, '价格', '环境', '服务', 0, '关爱孕妇及婴儿'),
(11, '教育培训', 0, 9, 0, '价格', '环境', '服务', 0, '一年之计在于春'),
(12, '测试休闲娱乐', 0, 2, 1, '价格', '环境', '服务', 0, '生活需要享受'),
(13, '酒店', 0, 3, 0, '价格', '环境', '服务', 0, '住最好的地方'),
(15, '医院', 14, 102, 0, '价格', '环境', '服务', 0, ''),
(16, '便利店', 14, 101, 0, '价格', '环境', '服务', 0, ''),
(17, 'KTV', 12, 21, 0, '价格', '环境', '服务', 0, ''),
(18, '足疗按摩', 12, 22, 0, '价格', '环境', '服务', 0, ''),
(19, '经济型酒店', 13, 31, 0, '价格', '环境', '服务', 0, ''),
(20, '五星级酒店', 13, 32, 0, '价格', '环境', '服务', 0, ''),
(21, '幼儿', 11, 91, 0, '价格', '环境', '服务', 0, ''),
(22, '小学', 11, 92, 0, '价格', '环境', '服务', 0, ''),
(23, '高中', 11, 94, 0, '价格', '环境', '服务', 0, ''),
(24, '初中', 11, 93, 0, '价格', '环境', '服务', 0, ''),
(25, '计算机', 11, 95, 0, '价格', '环境', '服务', 0, ''),
(26, '其他', 11, 97, 0, '价格', '环境', '服务', 0, ''),
(27, '外语', 11, 96, 0, '价格', '环境', '服务', 0, ''),
(28, '婚庆公司', 6, 41, 0, '价格', '环境', '服务', 0, ''),
(29, '婚纱摄影', 6, 42, 0, '价格', '环境', '服务', 0, ''),
(30, '新娘跟妆', 6, 43, 0, '价格', '环境', '服务', 0, ''),
(31, '司仪服务', 6, 44, 0, '价格', '环境', '服务', 0, ''),
(32, '装修公司', 7, 61, 0, '价格', '环境', '服务', 0, ''),
(33, '建材商', 7, 62, 0, '价格', '环境', '服务', 0, ''),
(34, '家具', 7, 63, 0, '价格', '环境', '服务', 0, ''),
(35, '软装布艺', 7, 64, 0, '价格', '环境', '服务', 0, ''),
(36, '4S店', 8, 71, 0, '价格', '环境', '服务', 0, ''),
(42, '江浙菜', 1, 11, 1, '口味', '环境', '服务', 0, ''),
(38, '母婴用品', 10, 51, 0, '价格', '环境', '服务', 0, ''),
(40, 'SPA', 9, 81, 0, '价格', '环境', '服务', 0, ''),
(43, '面包糕点', 1, 12, 0, '口味', '环境', '服务', 0, ''),
(44, '海鲜', 1, 13, 0, '口味', '环境', '服务', 0, ''),
(45, '火锅', 1, 11, 1, '口味', '环境', '服务', 0, ''),
(46, '川菜', 1, 11, 1, '口味', '环境', '服务', 0, ''),
(47, '徽菜', 1, 11, 0, '口味', '环境', '服务', 0, ''),
(48, '东北菜', 1, 17, 0, '口味', '环境', '服务', 0, ''),
(49, '西餐', 1, 18, 0, '口味', '环境', '服务', 0, ''),
(50, '粤菜', 1, 19, 0, '口味', '环境', '服务', 0, ''),
(51, '日本料理', 1, 11, 1, '口味', '环境', '服务', 0, ''),
(52, '韩国菜', 1, 11, 0, '口味', '环境', '服务', 0, ''),
(53, '其他', 1, 22, 0, '口味', '环境', '服务', 0, ''),
(54, '电影', 12, 2, 1, '价格', '环境', '服务', 0, ''),
(55, '酒吧', 12, 24, 1, '价格', '环境', '服务', 0, ''),
(56, '咖啡厅', 12, 25, 0, '价格', '环境', '服务', 0, ''),
(57, '温泉', 12, 26, 0, '价格', '环境', '服务', 0, ''),
(58, '棋牌室', 12, 27, 0, '价格', '环境', '服务', 0, ''),
(60, '三星级酒店', 13, 34, 0, '价格', '环境', '服务', 0, ''),
(61, '度假村', 13, 35, 0, '价格', '环境', '服务', 0, ''),
(62, '婚戒首饰', 6, 45, 0, '价格', '环境', '服务', 0, ''),
(63, '亲子摄影', 10, 52, 0, '价格', '环境', '服务', 0, ''),
(64, '加油站', 14, 101, 0, '价格', '环境', '服务', 0, ''),
(65, '旅游', 0, 1, 0, '号', '坏', '很好', 0, '国内外旅游'),
(66, '国内', 65, 1, 1, '好', '坏', '很好', 0, '海南'),
(69, '购物', 0, 0, 1, '', '', '', 0, '商城用'),
(70, '服装服饰', 69, 0, 0, '', '', '', 0, ''),
(71, '电器数码', 69, 0, 0, '', '', '', 0, ''),
(72, '美妆日化', 69, 0, 0, '', '', '', -3, ''),
(73, '家居日用', 69, 0, 0, '', '', '', 0, ''),
(91, '美食', 0, 0, 0, '3', '3', '3', 0, '美食');

-- --------------------------------------------------------

--
-- 表的结构 `tu_shop_cate_attr`
--

CREATE TABLE IF NOT EXISTS `tu_shop_cate_attr` (
  `attr_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` smallint(5) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `attr_name` varchar(32) DEFAULT NULL,
  `attr_price` int(11) DEFAULT '0' COMMENT '预售价格',
  `attr_intro` varchar(128) DEFAULT NULL COMMENT '简介',
  `attr_book_num` int(11) DEFAULT '0' COMMENT '预约人次',
  `orderby` tinyint(3) DEFAULT '100',
  PRIMARY KEY (`attr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_shop_details`
--

CREATE TABLE IF NOT EXISTS `tu_shop_details` (
  `shop_id` int(11) NOT NULL DEFAULT '0',
  `details` text,
  `theme_id` int(11) DEFAULT '0',
  `theme_expir_time` int(11) DEFAULT NULL,
  `discounts` varchar(32) DEFAULT NULL,
  `business_time` varchar(32) DEFAULT '9:00-18:00',
  `price` int(10) DEFAULT NULL,
  `near` varchar(64) DEFAULT NULL,
  `wei_pic` varchar(256) DEFAULT NULL,
  `is_dingyue` tinyint(1) DEFAULT '0' COMMENT '是否是订阅号',
  `app_id` varchar(32) DEFAULT NULL,
  `app_key` varchar(256) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  `weixin_msg` text,
  `menus` text,
  `seo_title` varchar(32) DEFAULT NULL,
  `seo_keywords` varchar(32) DEFAULT NULL,
  `seo_description` varchar(32) DEFAULT NULL,
  `icp` varchar(32) DEFAULT NULL,
  `sitelogo` varchar(64) DEFAULT NULL,
  `bank` varchar(1024) DEFAULT NULL,
  `delivery_time` tinyint(3) DEFAULT '30' COMMENT '接单倒计时（单位：分钟）',
  PRIMARY KEY (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_shop_grade`
--

CREATE TABLE IF NOT EXISTS `tu_shop_grade` (
  `grade_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `grade_name` varchar(32) DEFAULT NULL,
  `photo` varchar(256) DEFAULT NULL,
  `money` varchar(11) DEFAULT '0' COMMENT '单独升级购买费用',
  `gold` int(11) NOT NULL DEFAULT '0' COMMENT '需要多少商户资金自动升级',
  `content` varchar(256) NOT NULL COMMENT '等级描述',
  `is_mall` tinyint(1) NOT NULL DEFAULT '0',
  `is_tuan` tinyint(1) NOT NULL DEFAULT '0',
  `is_ele` tinyint(1) NOT NULL DEFAULT '0',
  `is_market` int(11) DEFAULT NULL,
  `is_store` int(11) DEFAULT NULL,
  `is_news` tinyint(1) NOT NULL DEFAULT '0',
  `is_hotel` tinyint(1) NOT NULL DEFAULT '0',
  `is_booking` tinyint(1) NOT NULL DEFAULT '0',
  `is_farm` tinyint(1) NOT NULL DEFAULT '0',
  `is_appoint` tinyint(1) NOT NULL DEFAULT '0',
  `is_huodong` tinyint(1) NOT NULL DEFAULT '0',
  `is_coupon` tinyint(1) NOT NULL DEFAULT '0',
  `is_life` tinyint(1) NOT NULL DEFAULT '0',
  `is_jifen` tinyint(1) NOT NULL DEFAULT '0',
  `is_cloud` tinyint(1) NOT NULL DEFAULT '0',
  `is_book` tinyint(1) NOT NULL DEFAULT '0',
  `is_stock` tinyint(1) NOT NULL DEFAULT '0',
  `is_edu` tinyint(1) NOT NULL DEFAULT '0',
  `is_zhe` tinyint(1) NOT NULL DEFAULT '0',
  `is_ktv` tinyint(1) DEFAULT '0' COMMENT 'KTV',
  `is_mall_num` int(11) NOT NULL DEFAULT '0',
  `is_tuan_num` int(11) NOT NULL DEFAULT '0',
  `is_ele_num` int(11) NOT NULL DEFAULT '0',
  `is_market_num` int(11) DEFAULT NULL,
  `is_store_num` int(11) DEFAULT NULL,
  `is_news_num` int(11) NOT NULL DEFAULT '0',
  `is_hotel_num` int(11) NOT NULL DEFAULT '0',
  `is_booking_num` int(11) NOT NULL DEFAULT '0',
  `is_farm_num` int(11) NOT NULL DEFAULT '0',
  `is_appoint_num` int(11) NOT NULL DEFAULT '0',
  `is_huodong_num` int(11) NOT NULL DEFAULT '0',
  `is_coupon_num` int(11) NOT NULL DEFAULT '0',
  `is_life_num` int(11) NOT NULL DEFAULT '0',
  `is_jifen_num` int(11) NOT NULL DEFAULT '0',
  `is_cloud_num` int(11) NOT NULL DEFAULT '0',
  `is_book_num` int(11) NOT NULL DEFAULT '0',
  `is_stock_num` int(11) NOT NULL DEFAULT '0',
  `is_edu_num` int(11) NOT NULL DEFAULT '0',
  `is_zhe_num` int(11) NOT NULL DEFAULT '0',
  `is_ktv_num` int(11) DEFAULT '0' COMMENT 'KTV数量',
  `orderby` int(11) NOT NULL DEFAULT '10' COMMENT '排序，默认10，当然可以修改',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`grade_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `tu_shop_grade`
--

INSERT INTO `tu_shop_grade` (`grade_id`, `grade_name`, `photo`, `money`, `gold`, `content`, `is_mall`, `is_tuan`, `is_ele`, `is_market`, `is_store`, `is_news`, `is_hotel`, `is_booking`, `is_farm`, `is_appoint`, `is_huodong`, `is_coupon`, `is_life`, `is_jifen`, `is_cloud`, `is_book`, `is_stock`, `is_edu`, `is_zhe`, `is_ktv`, `is_mall_num`, `is_tuan_num`, `is_ele_num`, `is_market_num`, `is_store_num`, `is_news_num`, `is_hotel_num`, `is_booking_num`, `is_farm_num`, `is_appoint_num`, `is_huodong_num`, `is_coupon_num`, `is_life_num`, `is_jifen_num`, `is_cloud_num`, `is_book_num`, `is_stock_num`, `is_edu_num`, `is_zhe_num`, `is_ktv_num`, `orderby`, `closed`, `create_time`, `create_ip`) VALUES
(7, '专营店', 'http://images4.5maiche.cn/2016-09-29_57ec76376cad7.jpg', '10000', 100000, '专营店是最好的等级', 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 100, 0, 10, 0, 0, 0, 0, 0, 0, 0, 0, 10, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, NULL, NULL),
(8, '旗舰店', 'http://images4.5maiche.cn/2016-09-29_57ec7655332c5.jpg', '10000', 1000000, '你好，功能更多了', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 0, 2, 0, NULL, NULL),
(9, 'vip', 'http://images4.5maiche.cn/2017-05-17_591c41fe66151.jpg', '29500', 50000, '算了', 0, 1, 1, NULL, NULL, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 1, NULL, NULL),
(10, '外卖', 'http://images4.5maiche.cn/2017-11-02_59fae3f6a06d7.jpg', '1000000', 10000000, '外卖', 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 100, 100, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 1, NULL, NULL),
(11, '商城', 'http://images4.5maiche.cn/2017-11-02_59fae463ba0c2.jpg', '10000000', 100000000, '商城', 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 1, NULL, NULL),
(12, '黄金等级店铺', 'http://images4.5maiche.cn/2017-11-21_5a13e9f45cd23.jpg', '300000', 200000, '哈哈', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 1000, 11, 1, NULL, NULL),
(13, '百货销售试用商家', '/attachs/2018/03/04/thumb_5a9b792ac1f0a.jpg', '100', 100, '试用商家等级', 1, 1, 1, 0, 1, 1, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 50, 20, 20, 0, 50, 50, 0, 0, 0, 0, 20, 100, 100, 0, 0, 0, 0, 0, 0, 0, 6, 1, NULL, NULL),
(14, '普通店铺', '/attachs/2018/03/04/thumb_5a9be5bb27fd7.png', '100', 100, '测尺寸', 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `tu_shop_grade_order`
--

CREATE TABLE IF NOT EXISTS `tu_shop_grade_order` (
  `order_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `shop_id` int(10) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '购买人的会员ID',
  `grade_id` int(11) DEFAULT NULL COMMENT '购买等级',
  `money` int(11) DEFAULT NULL COMMENT '付款金额不做促销',
  `status` tinyint(1) DEFAULT '0' COMMENT '0未付款，1已付款',
  `closed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0代表正常，1代表删除',
  `shop_name` varchar(50) DEFAULT NULL COMMENT '购买商家名称',
  `create_time` int(10) DEFAULT NULL,
  `create_ip` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_shop_money`
--

CREATE TABLE IF NOT EXISTS `tu_shop_money` (
  `money_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL COMMENT '学校ID',
  `shop_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `branch_id` smallint(5) DEFAULT NULL,
  `money` int(11) DEFAULT '0',
  `commission` int(11) DEFAULT '0' COMMENT '佣金',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  `type` enum('tuan','ele','booking','goods','breaks','hotel','crowd','farm','appoint','cloud','edu','book','stock','ktv','market','store') DEFAULT 'tuan',
  `order_id` int(11) DEFAULT '0',
  `intro` varchar(215) DEFAULT NULL,
  PRIMARY KEY (`money_id`),
  KEY `shop_id` (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_sms`
--

CREATE TABLE IF NOT EXISTS `tu_sms` (
  `sms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sms_key` varchar(32) DEFAULT NULL,
  `sms_explain` varchar(1024) DEFAULT NULL,
  `sms_tmpl` varchar(2048) DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`sms_id`),
  UNIQUE KEY `sms_key` (`sms_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=95 ;

--
-- 转存表中的数据 `tu_sms`
--

INSERT INTO `tu_sms` (`sms_id`, `sms_key`, `sms_explain`, `sms_tmpl`, `is_open`) VALUES
(43, 'sms_admin_login_admin', '后台账户异地IP登陆短信通知', '尊敬的{userName}，你的后台于{time}账户操作异常，请查看！【{sitename}】', 1),
(35, 'sms_user_newpwd', '找回密码', '尊敬的用户：您好，您在{sitename}的密码被重置成{newpwd}您可以使用{newpwd}重新登录【{sitename}】', 1),
(33, 'sms_yzm', '验证码', '您在{sitename}手机认证的验证码是{code}千万别告诉别人【{sitename}】', 1),
(59, 'sms_delivery_user', '快递员抢单短信通知', '您好{userName}，配送中心有新的订单了，标题{runningName}日期：{date}【{sitename}】', 1),
(60, 'runningPayUser', '用户发布跑腿短信通知用户', '你好{userName}，您发布的跑腿{runningId}已成功付费{needPay}，{time}【{sitename}】', 1),
(61, 'sms_running_delivery_user', '配送员接单通知用户万能短信接口', '{userName}您发布的任务已被{deliveryName}抢单，正在{statusName}...【{sitename}】', 1),
(93, 'sms_ele_tz_shop', '跑腿新订单外卖通知商家', '{shopName}您的外卖商城有定的订单{runningId}请尽快处理【{sitename}】', 1),
(94, 'runningAcceptUser', '配送员抢单通知买家', '你好{userName}，您发布的跑腿{runningId}已经被配送员{DeliveryName}抢单，手机{DeliveryMobile}【{sitename}】', 1),
(85, 'register', '会员注册发送短信给用户', '您被商家{shopName}邀请成功注册{sitename}会员，注册ID{userId}，账户{userAccount}，密码{userPassword}【{sitename}】', 1);

-- --------------------------------------------------------

--
-- 表的结构 `tu_sms_bao`
--

CREATE TABLE IF NOT EXISTS `tu_sms_bao` (
  `sms_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` int(11) DEFAULT '0' COMMENT '商家ID',
  `mobile` varchar(11) DEFAULT NULL,
  `status` int(6) unsigned DEFAULT NULL COMMENT '0成功，其他件错误码',
  `content` varchar(500) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`sms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_thread`
--

CREATE TABLE IF NOT EXISTS `tu_thread` (
  `thread_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `cate_id` int(10) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `thread_name` varchar(32) DEFAULT NULL,
  `tel_money` int(11) DEFAULT '0',
  `intro` varchar(256) DEFAULT '',
  `photo` varchar(128) DEFAULT '',
  `banner` varchar(128) DEFAULT '',
  `posts` int(10) DEFAULT '0',
  `fans` int(10) DEFAULT '0',
  `is_hot` tinyint(1) DEFAULT '0',
  `is_essence` tinyint(1) NOT NULL DEFAULT '0',
  `orderby` int(11) NOT NULL DEFAULT '100',
  `closed` tinyint(1) DEFAULT '0',
  `create_time` int(10) DEFAULT '0',
  `create_ip` varchar(15) DEFAULT '',
  PRIMARY KEY (`thread_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `tu_thread`
--

INSERT INTO `tu_thread` (`thread_id`, `school_id`, `cate_id`, `user_id`, `thread_name`, `tel_money`, `intro`, `photo`, `banner`, `posts`, `fans`, `is_hot`, `is_essence`, `orderby`, `closed`, `create_time`, `create_ip`) VALUES
(4, NULL, 2, 1, '电影', 0, '1', '/attachs/2019/05/21/5ce3b0e747a8e.jpg', '/attachs/2019/05/21/5ce3b0e8ee9b7.jpg', 0, 0, 1, 1, 1, 0, 1558425844, '222.181.207.196');

-- --------------------------------------------------------

--
-- 表的结构 `tu_thread_cate`
--

CREATE TABLE IF NOT EXISTS `tu_thread_cate` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT '0',
  `photo` varchar(256) DEFAULT NULL,
  `cate_name` varchar(32) DEFAULT '',
  `money` int(11) DEFAULT '0' COMMENT '发布费用',
  `orderby` tinyint(3) DEFAULT '100',
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `tu_thread_cate`
--

INSERT INTO `tu_thread_cate` (`cate_id`, `school_id`, `photo`, `cate_name`, `money`, `orderby`) VALUES
(2, 0, '/attachs/2019/05/21/5ce391dc13984.png', '电影动漫', 0, 1),
(3, 0, '/attachs/2019/05/21/5ce391e57cf57.png', '电视剧', 0, 2),
(4, 0, '/attachs/2019/05/21/5ce391edde804.png', '生活运动', 0, 4),
(5, 0, '/attachs/2019/05/21/5ce391f5ed2ee.png', '健身', 0, 5),
(6, 0, '/attachs/2019/05/21/5ce391fe2c935.png', '体育竞技', 0, 6),
(7, 0, '/attachs/2019/05/21/5ce392061268d.png', '饮食健康', 0, 7),
(8, 0, '/attachs/2019/05/21/5ce3920f7c450.png', '养生', 0, 8),
(9, 0, '/attachs/2019/05/21/5ce3921ddd5a6.png', '网络红人', 0, 9),
(10, 0, '/attachs/2019/05/21/5ce392263febb.png', '媒体数码', 0, 10);

-- --------------------------------------------------------

--
-- 表的结构 `tu_thread_collect`
--

CREATE TABLE IF NOT EXISTS `tu_thread_collect` (
  `thread_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`thread_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_thread_donate`
--

CREATE TABLE IF NOT EXISTS `tu_thread_donate` (
  `donate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `post_id` int(10) DEFAULT '0',
  `user_id` int(10) DEFAULT '0',
  `money` decimal(10,1) DEFAULT '0.0',
  `create_time` int(10) DEFAULT '0',
  `create_ip` varchar(15) DEFAULT '',
  PRIMARY KEY (`donate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_thread_post`
--

CREATE TABLE IF NOT EXISTS `tu_thread_post` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_id` int(11) DEFAULT '0',
  `title` varchar(128) DEFAULT NULL,
  `photo` varchar(256) DEFAULT NULL,
  `thread_id` int(10) DEFAULT '0',
  `city_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `cate_id` int(10) DEFAULT '0',
  `details` text,
  `user_id` int(10) DEFAULT '0',
  `school_id` int(11) DEFAULT NULL,
  `donate_num` int(10) DEFAULT '0',
  `reply_num` int(10) DEFAULT '0',
  `zan_num` int(10) DEFAULT '0',
  `top_num` int(11) DEFAULT '0' COMMENT '置顶天数',
  `top_date` date DEFAULT '0000-00-00' COMMENT '置顶日期',
  `is_top` tinyint(1) DEFAULT '0' COMMENT '置顶判断',
  `money` varchar(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `address` varchar(128) DEFAULT NULL,
  `views` int(10) DEFAULT '0',
  `mobile` varchar(15) DEFAULT NULL COMMENT '电话',
  `lng` varchar(15) DEFAULT NULL,
  `lat` varchar(15) DEFAULT NULL,
  `is_fine` tinyint(1) NOT NULL DEFAULT '0',
  `last_id` int(10) DEFAULT '0',
  `last_time` int(10) DEFAULT '0',
  `orderby` int(11) NOT NULL,
  `audit` tinyint(1) DEFAULT '0',
  `closed` tinyint(1) DEFAULT '0',
  `create_time` int(10) DEFAULT '0',
  `create_ip` varchar(15) DEFAULT '',
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_thread_post_collection`
--

CREATE TABLE IF NOT EXISTS `tu_thread_post_collection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id` (`post_id`,`create_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_thread_post_comments`
--

CREATE TABLE IF NOT EXISTS `tu_thread_post_comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `post_id` int(10) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0',
  `contents` text,
  `user_id` int(10) DEFAULT '0',
  `reply_comment_id` int(10) DEFAULT '0',
  `reply_user_id` int(10) DEFAULT '0',
  `closed` tinyint(1) DEFAULT '0',
  `create_time` int(10) DEFAULT '0',
  `create_ip` varchar(15) DEFAULT '',
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_thread_post_photo`
--

CREATE TABLE IF NOT EXISTS `tu_thread_post_photo` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `post_id` int(10) DEFAULT '0',
  `photo` varchar(128) DEFAULT '',
  PRIMARY KEY (`photo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_thread_post_zan`
--

CREATE TABLE IF NOT EXISTS `tu_thread_post_zan` (
  `zan_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`zan_id`),
  UNIQUE KEY `post_id` (`post_id`,`create_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_uploadset`
--

CREATE TABLE IF NOT EXISTS `tu_uploadset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `para` text NOT NULL,
  `status` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='上传插件' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `tu_uploadset`
--

INSERT INTO `tu_uploadset` (`id`, `type`, `para`, `status`) VALUES
(2, 'Qiniu', '{"region":"0","water":"1","accessKey":"qVyLW4y_4HDZHY9-8MolcMCGtesFTuBu11JHpEdk","secrectKey":"Rx5nJoQmk-nitpIJPpLaMYG323kfAK8MKYqwoVIT","domain":"http:\\/\\/images4.yanjiu007.com","bucket":"mantuo","timeout":"3000"}', 0);

-- --------------------------------------------------------

--
-- 表的结构 `tu_users`
--

CREATE TABLE IF NOT EXISTS `tu_users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL COMMENT '学校ID',
  `city_id` int(11) DEFAULT NULL COMMENT '城市ID',
  `open_id` varchar(32) DEFAULT NULL,
  `openid` varchar(32) DEFAULT NULL,
  `unionid` varchar(32) DEFAULT NULL,
  `account` varchar(64) DEFAULT NULL COMMENT '邮件或者手机',
  `password` char(32) DEFAULT NULL,
  `pay_password` char(32) DEFAULT NULL,
  `Gender` int(1) DEFAULT '0' COMMENT '性别',
  `face` varchar(500) DEFAULT NULL,
  `poster` varchar(128) DEFAULT NULL COMMENT '海报',
  `ext0` varchar(15) DEFAULT NULL COMMENT '为了兼容DISCUZ 设立的用户名存储的字段',
  `nickname` varchar(32) DEFAULT NULL,
  `integral` int(11) DEFAULT '0',
  `prestige` int(11) DEFAULT '0' COMMENT '声望，影响等级的',
  `money` int(11) DEFAULT '0' COMMENT '账户余额',
  `moneys` int(11) DEFAULT '0',
  `rank_id` tinyint(4) DEFAULT '0' COMMENT '等级ID',
  `gold` int(11) DEFAULT '0',
  `frozen_money` int(11) DEFAULT NULL COMMENT '会员余额-冻结金',
  `frozen_gold` int(11) DEFAULT NULL COMMENT '冻结金-商家资金',
  `reg_time` int(11) DEFAULT '0',
  `reg_ip` varchar(15) DEFAULT NULL,
  `last_time` int(11) DEFAULT NULL,
  `frozen_money_time` int(11) DEFAULT NULL COMMENT '会员余额-冻结金修改时间',
  `frozen_today_money` int(11) DEFAULT '0' COMMENT '每天返还冻结金金额',
  `frozen_gold_time` int(11) DEFAULT NULL COMMENT '修改商家资金冻结时间',
  `prestige_frozen` int(11) NOT NULL DEFAULT '0' COMMENT '威望冻结金',
  `is_prestige_frozen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0代表威望已冻结，1已解冻',
  `closed` tinyint(1) DEFAULT '0' COMMENT '-1代表需要激活   0 代表正常  1 代表删除',
  `uc_id` int(11) DEFAULT '0',
  `email` varchar(64) DEFAULT NULL COMMENT '认证的邮件',
  `mobile` varchar(11) DEFAULT NULL COMMENT '认证的手机号码',
  `ping_num` int(11) DEFAULT '0',
  `post_num` int(11) DEFAULT '0',
  `lock_num` int(11) DEFAULT '0',
  `invite1` int(11) DEFAULT NULL,
  `invite2` int(11) DEFAULT NULL,
  `invite3` int(11) DEFAULT NULL,
  `invite4` int(11) DEFAULT NULL,
  `invite5` int(11) DEFAULT NULL,
  `invite6` int(11) DEFAULT '0',
  `token` char(32) DEFAULT '0',
  `fuid1` int(10) unsigned NOT NULL DEFAULT '0',
  `fuid2` int(10) unsigned NOT NULL DEFAULT '0',
  `fuid3` int(10) unsigned NOT NULL DEFAULT '0',
  `fuid4` int(11) DEFAULT NULL,
  `fuid5` int(11) DEFAULT NULL,
  `fuid6` int(11) DEFAULT NULL,
  `fuid7` int(11) DEFAULT NULL,
  `fuid8` int(11) DEFAULT NULL,
  `fuid9` int(11) DEFAULT NULL,
  `fuid10` int(11) DEFAULT NULL,
  `fuid11` int(11) DEFAULT NULL,
  `fuid12` int(11) DEFAULT NULL,
  `fuid13` int(11) DEFAULT NULL,
  `fuid14` int(11) DEFAULT NULL,
  `fuid15` int(11) DEFAULT NULL,
  `fuid16` int(11) DEFAULT NULL,
  `fuid17` int(11) DEFAULT NULL,
  `fuid18` int(11) DEFAULT NULL,
  `fuid19` int(11) DEFAULT NULL,
  `fuid20` int(11) DEFAULT NULL,
  `notifyFlag` varchar(1) DEFAULT '0' COMMENT '是否开启通知',
  `notifyFlag2` tinyint(1) DEFAULT '0',
  `bindFlag` tinyint(1) DEFAULT '0' COMMENT '是否关注',
  `notifyFrom` varchar(16) DEFAULT '0:00' COMMENT '开启时间',
  `notifyEnd` varchar(16) DEFAULT '22:00' COMMENT '完成时间',
  `lat` varchar(32) DEFAULT NULL,
  `lng` varchar(32) DEFAULT NULL,
  `day` varchar(32) DEFAULT '0' COMMENT '签到天数',
  `carBrand_id` int(11) DEFAULT NULL COMMENT '汽车品牌ID',
  `school_year` int(11) DEFAULT NULL COMMENT '入校年份',
  `addr` varchar(64) DEFAULT NULL COMMENT '地址',
  `identity` tinyint(1) DEFAULT '0' COMMENT '身份：1教师，2本科生，3研究生，4其他',
  `is_lock` tinyint(1) DEFAULT '0',
  `is_lock_time` int(11) DEFAULT NULL,
  `is_aux` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员是否实名认证',
  `is_backers` int(11) DEFAULT '0' COMMENT '1申请中，2已审核，3已拒绝',
  `is_user_earnest` tinyint(1) DEFAULT '0' COMMENT '0关闭1开启',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_users_cash`
--

CREATE TABLE IF NOT EXISTS `tu_users_cash` (
  `cash_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) DEFAULT NULL COMMENT '学校ID',
  `user_id` int(11) DEFAULT NULL,
  `type` enum('user','shop','flowworker') DEFAULT 'user',
  `code` enum('weixin','bank','alipay') DEFAULT 'weixin',
  `shop_id` smallint(5) DEFAULT NULL,
  `city_id` smallint(5) DEFAULT NULL,
  `area_id` smallint(5) DEFAULT NULL,
  `money` int(11) DEFAULT NULL,
  `moneys` int(11) DEFAULT NULL COMMENT '物流车资金',
  `gold` int(11) NOT NULL DEFAULT '0' COMMENT '商户提现资金',
  `commission` int(11) DEFAULT NULL COMMENT '提现佣金',
  `alipay_account` varchar(32) DEFAULT NULL COMMENT '支付宝账户',
  `alipay_real_name` varchar(32) DEFAULT NULL COMMENT '支付宝姓名',
  `re_user_name` varchar(64) DEFAULT NULL COMMENT '已认证姓名',
  `status` tinyint(1) DEFAULT '0' COMMENT '0未审核1通过2拒绝',
  `reason` text,
  `account` varchar(64) DEFAULT NULL,
  `bank_name` varchar(128) DEFAULT NULL,
  `bank_num` varchar(32) DEFAULT NULL,
  `bank_branch` varchar(128) DEFAULT NULL,
  `bank_realname` varchar(64) DEFAULT NULL,
  `mch_billno` varchar(32) DEFAULT NULL COMMENT '微信提现商户号',
  `is_lock` tinyint(1) NOT NULL DEFAULT '0',
  `return_msg` varchar(128) DEFAULT NULL COMMENT '微信提现状态返回吗',
  `payment_no` varchar(128) DEFAULT NULL COMMENT '微信订单号',
  `partner_trade_no` varchar(128) DEFAULT NULL COMMENT '商户订单号',
  `payment_time` varchar(64) DEFAULT NULL COMMENT '付款时间',
  `addtime` int(11) DEFAULT NULL COMMENT '提现提交时间',
  `info` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`cash_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_users_ex`
--

CREATE TABLE IF NOT EXISTS `tu_users_ex` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `last_uid` int(11) DEFAULT '0',
  `views` int(11) DEFAULT NULL,
  `bank_name` varchar(128) DEFAULT NULL,
  `bank_num` varchar(32) DEFAULT NULL,
  `bank_branch` varchar(128) DEFAULT NULL,
  `bank_realname` varchar(64) DEFAULT NULL,
  `job` varchar(20) DEFAULT NULL,
  `sex` int(2) DEFAULT NULL,
  `age` int(11) DEFAULT NULL COMMENT '80代表80',
  `status` int(11) DEFAULT NULL COMMENT '1单生2热念',
  `star_id` tinyint(10) NOT NULL DEFAULT '0',
  `born_year` varchar(20) DEFAULT NULL,
  `born_month` tinyint(20) DEFAULT NULL,
  `born_day` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_user_addr`
--

CREATE TABLE IF NOT EXISTS `tu_user_addr` (
  `addr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '1' COMMENT '1会员2学校',
  `user_id` int(11) DEFAULT '0',
  `city_id` int(11) DEFAULT '0',
  `area_id` int(11) DEFAULT '0',
  `business_id` int(11) DEFAULT '0',
  `Gender` int(11) DEFAULT NULL,
  `SchoolId` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `addr` varchar(1024) DEFAULT NULL,
  `lng` varchar(32) DEFAULT NULL,
  `lat` varchar(32) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0',
  `closed` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`addr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_user_formid`
--

CREATE TABLE IF NOT EXISTS `tu_user_formid` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` varchar(64) DEFAULT NULL,
  `formId` varchar(64) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `openid` varchar(32) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_user_gold_logs`
--

CREATE TABLE IF NOT EXISTS `tu_user_gold_logs` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `gold` int(11) DEFAULT '0',
  `intro` varchar(256) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_user_guide_logs`
--

CREATE TABLE IF NOT EXISTS `tu_user_guide_logs` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `shop_id` int(11) NOT NULL COMMENT '商家ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `guide_id` int(11) NOT NULL COMMENT '推荐人的ID',
  `type` enum('tuan','ele','booking','goods','breaks','hotel','crowd','farm','appoint','cloud','edu') NOT NULL DEFAULT 'tuan' COMMENT '分成类型',
  `money` int(11) DEFAULT '0' COMMENT '分成金额',
  `intro` varchar(64) DEFAULT NULL COMMENT '分成理由',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_user_integral_logs`
--

CREATE TABLE IF NOT EXISTS `tu_user_integral_logs` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `integral` int(11) DEFAULT NULL,
  `intro` varchar(256) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_user_money_logs`
--

CREATE TABLE IF NOT EXISTS `tu_user_money_logs` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `school_id` int(11) DEFAULT '0' COMMENT '学校ID',
  `type` tinyint(2) DEFAULT NULL COMMENT '类型',
  `month` int(11) DEFAULT NULL,
  `money` int(11) DEFAULT '0',
  `intro` varchar(512) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_user_rank`
--

CREATE TABLE IF NOT EXISTS `tu_user_rank` (
  `rank_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rank_name` varchar(32) DEFAULT NULL,
  `rate` int(11) DEFAULT '0' COMMENT '费率',
  `number` int(11) DEFAULT '0' COMMENT '需要人数',
  `discount` int(11) DEFAULT NULL COMMENT '折扣',
  `reward` int(11) DEFAULT NULL COMMENT '奖励折扣',
  `icon` varchar(64) DEFAULT NULL,
  `icon1` varchar(64) DEFAULT NULL,
  `integral` int(11) DEFAULT '0' COMMENT '需要积分',
  `prestige` int(11) DEFAULT '0' COMMENT '需要的积分数',
  `rebate` int(10) DEFAULT '0',
  `photo` varchar(128) NOT NULL COMMENT '图标',
  `price` int(11) DEFAULT '0' COMMENT '用户购买等级金额',
  PRIMARY KEY (`rank_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_user_weixin`
--

CREATE TABLE IF NOT EXISTS `tu_user_weixin` (
  `wx_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `shop_id` int(10) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `nickname` varchar(200) DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL,
  `unionid` varchar(50) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`wx_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_weixin_keyword`
--

CREATE TABLE IF NOT EXISTS `tu_weixin_keyword` (
  `keyword_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(32) DEFAULT NULL,
  `type` enum('news','text') DEFAULT 'text' COMMENT '0普通消息 1图片消息',
  `title` varchar(128) DEFAULT NULL,
  `contents` text,
  `url` varchar(128) DEFAULT NULL,
  `photo` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`keyword_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_weixin_msg`
--

CREATE TABLE IF NOT EXISTS `tu_weixin_msg` (
  `msg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `open_id` varchar(64) DEFAULT NULL COMMENT 'open_id',
  `status` varchar(11) DEFAULT NULL COMMENT '返回码，默认空',
  `info` varchar(128) DEFAULT NULL COMMENT '返回码说明',
  `serial` varchar(64) DEFAULT NULL COMMENT '模板库编号',
  `template_id` varchar(128) DEFAULT NULL COMMENT '模板编号',
  `comment` text COMMENT '内容',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL COMMENT '发送IP',
  PRIMARY KEY (`msg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_weixin_setting`
--

CREATE TABLE IF NOT EXISTS `tu_weixin_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '小程序名称',
  `appid` varchar(32) DEFAULT NULL COMMENT 'appid',
  `appsecret` varchar(64) DEFAULT NULL COMMENT 'appsecret',
  `intro` varchar(128) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) DEFAULT NULL,
  `create_ip` varchar(15) DEFAULT NULL COMMENT '发送IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tu_weixin_tmpl`
--

CREATE TABLE IF NOT EXISTS `tu_weixin_tmpl` (
  `tmpl_id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '模板标题',
  `serial` varchar(255) DEFAULT NULL COMMENT '模板系统编号',
  `template_id` varchar(255) DEFAULT NULL COMMENT '模板应用ID',
  `info` varchar(255) DEFAULT NULL COMMENT '模板介绍',
  `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序',
  `status` tinyint(4) DEFAULT NULL COMMENT '模板介绍',
  `create_time` int(10) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`tmpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- 转存表中的数据 `tu_weixin_tmpl`
--

INSERT INTO `tu_weixin_tmpl` (`tmpl_id`, `title`, `serial`, `template_id`, `info`, `sort`, `status`, `create_time`, `update_time`) VALUES
(1, '订单配送通知', 'AT0177', 'X5I3WNcTxVl1SvRQ_bDmqJ7U0kBnOHwt6t2aJ-RrgiY', '', 0, 1, 0, 1548837701),
(2, '订单取消通知', 'AT0177', 'X5I3WNcTxVl1SvRQ_bDmqJ7U0kBnOHwt6t2aJ-RrgiY', NULL, 0, 1, 1491832824, 1548837701),
(3, '接单成功提醒', 'AT0177', 'X5I3WNcTxVl1SvRQ_bDmqJ7U0kBnOHwt6t2aJ-RrgiY', NULL, 0, 1, 1491826213, 1548837701);


