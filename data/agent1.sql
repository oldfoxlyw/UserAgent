-- phpMyAdmin SQL Dump
-- version 4.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2014-10-20 10:55:35
-- 服务器版本： 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `agent1_account_db`
--
DROP DATABASE `agent1_account_db`;
CREATE DATABASE IF NOT EXISTS `agent1_account_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent1_account_db`;

-- --------------------------------------------------------

--
-- 表的结构 `account_added_game`
--

DROP TABLE IF EXISTS `account_added_game`;
CREATE TABLE IF NOT EXISTS `account_added_game` (
  `GUID` char(36) NOT NULL,
  `game_id` char(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `account_login_token`
--

DROP TABLE IF EXISTS `account_login_token`;
CREATE TABLE IF NOT EXISTS `account_login_token` (
  `guid` bigint(20) NOT NULL,
  `token` char(64) NOT NULL,
  `expire_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `web_account`
--

DROP TABLE IF EXISTS `web_account`;
CREATE TABLE IF NOT EXISTS `web_account` (
`GUID` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `account_name` char(64) NOT NULL,
  `account_pass` char(32) NOT NULL,
  `server_id` char(6) NOT NULL,
  `account_nickname` char(16) NOT NULL DEFAULT '',
  `account_email` char(32) DEFAULT NULL,
  `account_pass_question` char(4) DEFAULT NULL,
  `account_pass_answer` char(4) DEFAULT NULL,
  `account_point` int(11) NOT NULL DEFAULT '0',
  `account_regtime` int(11) NOT NULL DEFAULT '0',
  `account_lastlogin` int(11) NOT NULL DEFAULT '0',
  `account_currentlogin` int(11) NOT NULL DEFAULT '0',
  `account_lastip` char(16) DEFAULT NULL,
  `account_currentip` char(16) DEFAULT NULL,
  `account_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=正常 0=试玩 -1=封停',
  `account_activity` int(11) NOT NULL DEFAULT '0',
  `account_job` char(16) DEFAULT '',
  `profession_icon` char(32) NOT NULL DEFAULT '',
  `account_level` int(11) NOT NULL DEFAULT '0',
  `account_mission` bigint(20) NOT NULL DEFAULT '0',
  `partner_key` char(16) NOT NULL DEFAULT 'default',
  `partner_id` char(32) NOT NULL DEFAULT '',
  `closure_endtime` int(11) NOT NULL DEFAULT '0',
  `device_id` char(64) NOT NULL DEFAULT '',
  `ad_id` char(32) NOT NULL DEFAULT ''
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=200100191000001 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_added_game`
--
ALTER TABLE `account_added_game`
 ADD PRIMARY KEY (`GUID`,`game_id`);

--
-- Indexes for table `account_login_token`
--
ALTER TABLE `account_login_token`
 ADD PRIMARY KEY (`guid`);

--
-- Indexes for table `web_account`
--
ALTER TABLE `web_account`
 ADD PRIMARY KEY (`GUID`), ADD KEY `account_name` (`account_name`,`account_pass`,`server_id`), ADD KEY `partner_id` (`partner_key`,`partner_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `web_account`
--
ALTER TABLE `web_account`
MODIFY `GUID` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=200100191000001;--
-- Database: `agent1_adminlog_db`
--
DROP DATABASE `agent1_adminlog_db`;
CREATE DATABASE IF NOT EXISTS `agent1_adminlog_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent1_adminlog_db`;

-- --------------------------------------------------------

--
-- 表的结构 `log_scc`
--

DROP TABLE IF EXISTS `log_scc`;
CREATE TABLE IF NOT EXISTS `log_scc` (
`log_id` int(11) NOT NULL,
  `log_type` varchar(64) NOT NULL,
  `log_user` text,
  `log_relative_page_url` varchar(128) NOT NULL,
  `log_relative_parameter` text NOT NULL,
  `log_addition_parameter` text,
  `log_time` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_scc`
--
ALTER TABLE `log_scc`
 ADD PRIMARY KEY (`log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_scc`
--
ALTER TABLE `log_scc`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;--
-- Database: `agent1_funds_flow_db`
--
DROP DATABASE `agent1_funds_flow_db`;
CREATE DATABASE IF NOT EXISTS `agent1_funds_flow_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent1_funds_flow_db`;

-- --------------------------------------------------------

--
-- 表的结构 `funds_checkinout`
--

DROP TABLE IF EXISTS `funds_checkinout`;
CREATE TABLE IF NOT EXISTS `funds_checkinout` (
`funds_id` bigint(20) NOT NULL,
  `account_guid` bigint(20) NOT NULL,
  `account_name` char(64) NOT NULL,
  `account_nickname` char(32) NOT NULL,
  `account_level` int(11) NOT NULL,
  `account_id` char(16) NOT NULL,
  `game_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `server_section` char(5) NOT NULL,
  `funds_flow_dir` enum('CHECK_IN','CHECK_OUT') NOT NULL,
  `funds_amount` int(11) NOT NULL,
  `funds_item_amount` int(11) NOT NULL,
  `funds_item_current` int(11) NOT NULL,
  `funds_time` int(11) NOT NULL,
  `funds_time_local` datetime NOT NULL,
  `funds_type` int(11) NOT NULL DEFAULT '1' COMMENT '1=游戏内充值 0=GM手动调整',
  `partner_key` char(16) NOT NULL DEFAULT 'default',
  `receipt_data` text NOT NULL,
  `appstore_status` int(11) NOT NULL,
  `appstore_device_id` char(64) NOT NULL,
  `order_id` char(64) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `funds_order`
--

DROP TABLE IF EXISTS `funds_order`;
CREATE TABLE IF NOT EXISTS `funds_order` (
`id` int(11) NOT NULL,
  `player_id` char(22) NOT NULL,
  `server_id` char(1) NOT NULL,
  `checksum` char(64) NOT NULL,
  `check_count` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `funds_id` int(11) NOT NULL,
  `posttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `funds_checkinout`
--
ALTER TABLE `funds_checkinout`
 ADD PRIMARY KEY (`funds_id`), ADD KEY `account_guid` (`account_name`), ADD KEY `account_id` (`account_id`), ADD KEY `game_id` (`game_id`,`server_id`,`server_section`), ADD KEY `funds_flow_dir` (`funds_flow_dir`), ADD KEY `funds_time` (`funds_time`);

--
-- Indexes for table `funds_order`
--
ALTER TABLE `funds_order`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `checksum_2` (`checksum`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `funds_checkinout`
--
ALTER TABLE `funds_checkinout`
MODIFY `funds_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `funds_order`
--
ALTER TABLE `funds_order`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;--
-- Database: `agent1_log_db`
--
DROP DATABASE `agent1_log_db`;
CREATE DATABASE IF NOT EXISTS `agent1_log_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent1_log_db`;

-- --------------------------------------------------------

--
-- 表的结构 `log_buy_equipment_detail`
--

DROP TABLE IF EXISTS `log_buy_equipment_detail`;
CREATE TABLE IF NOT EXISTS `log_buy_equipment_detail` (
`id` int(11) NOT NULL,
  `date` date NOT NULL,
  `server_id` char(8) NOT NULL,
  `partner_key` char(16) NOT NULL,
  `level_detail` text NOT NULL,
  `mission_detail` text NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_daily_statistics`
--

DROP TABLE IF EXISTS `log_daily_statistics`;
CREATE TABLE IF NOT EXISTS `log_daily_statistics` (
`id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `server_id` char(8) NOT NULL,
  `server_name` char(16) NOT NULL,
  `reg_account` int(11) NOT NULL DEFAULT '0',
  `reg_new_account` int(11) NOT NULL DEFAULT '0',
  `valid_account` int(11) NOT NULL DEFAULT '0' COMMENT '等级大于等于1级的帐号',
  `valid_new_account` int(11) NOT NULL DEFAULT '0',
  `level_account` int(11) NOT NULL DEFAULT '0',
  `modify_account` int(11) NOT NULL DEFAULT '0' COMMENT '持有注册帐号的用户',
  `modify_new_account` int(11) NOT NULL DEFAULT '0',
  `login_account` int(11) NOT NULL DEFAULT '0',
  `login_account_valid` int(11) NOT NULL DEFAULT '0',
  `active_account` int(11) NOT NULL DEFAULT '0' COMMENT '活跃用户，三天内登陆过游戏的人数',
  `dau` int(11) NOT NULL DEFAULT '0',
  `flowover_account` int(11) NOT NULL DEFAULT '0' COMMENT '流失用户，超过一周没有登录游戏的人数',
  `reflow_account` int(11) NOT NULL DEFAULT '0',
  `orders_current_sum` int(11) NOT NULL DEFAULT '0' COMMENT '当天订单总额',
  `orders_num` int(11) NOT NULL DEFAULT '0',
  `orders_sum` int(11) NOT NULL DEFAULT '0',
  `arpu` int(11) NOT NULL DEFAULT '0' COMMENT '充值率',
  `recharge_account` int(11) NOT NULL DEFAULT '0' COMMENT '当天充值人数',
  `order_count` int(11) NOT NULL DEFAULT '0' COMMENT '订单数',
  `partner_key` char(16) NOT NULL DEFAULT 'default',
  `at` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_flowover_cache`
--

DROP TABLE IF EXISTS `log_flowover_cache`;
CREATE TABLE IF NOT EXISTS `log_flowover_cache` (
  `guid` bigint(20) NOT NULL,
  `server_id` char(8) NOT NULL,
  `partner_key` char(16) NOT NULL DEFAULT 'default',
  `account_job` char(16) NOT NULL,
  `account_level` int(11) NOT NULL,
  `account_mission` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_flowover_detail`
--

DROP TABLE IF EXISTS `log_flowover_detail`;
CREATE TABLE IF NOT EXISTS `log_flowover_detail` (
  `date` date NOT NULL,
  `server_id` char(8) NOT NULL,
  `partner_key` char(16) NOT NULL DEFAULT 'default',
  `job` text NOT NULL,
  `level` text NOT NULL,
  `mission` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_funds_hours`
--

DROP TABLE IF EXISTS `log_funds_hours`;
CREATE TABLE IF NOT EXISTS `log_funds_hours` (
  `log_date` date NOT NULL,
  `log_hour` int(11) NOT NULL,
  `server_id` char(5) NOT NULL,
  `partner_key` char(16) NOT NULL,
  `funds_sum` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_online_count`
--

DROP TABLE IF EXISTS `log_online_count`;
CREATE TABLE IF NOT EXISTS `log_online_count` (
  `server_id` char(8) NOT NULL,
  `partner_key` char(16) NOT NULL,
  `log_date` date NOT NULL,
  `log_hour` int(11) NOT NULL DEFAULT '0',
  `log_count` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_retention`
--

DROP TABLE IF EXISTS `log_retention`;
CREATE TABLE IF NOT EXISTS `log_retention` (
  `log_date` date NOT NULL,
  `server_id` char(5) NOT NULL,
  `partner_key` char(16) NOT NULL,
  `prev_register` int(11) NOT NULL DEFAULT '0',
  `prev_current_login` int(11) NOT NULL DEFAULT '0',
  `next_retention` int(11) NOT NULL DEFAULT '0',
  `third_register` int(11) NOT NULL DEFAULT '0',
  `third_current_login` int(11) NOT NULL DEFAULT '0',
  `third_retention` int(11) NOT NULL DEFAULT '0',
  `seven_register` int(11) NOT NULL DEFAULT '0',
  `seven_current_login` int(11) NOT NULL DEFAULT '0',
  `seven_retention` int(11) NOT NULL DEFAULT '0',
  `level1` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_retention1`
--

DROP TABLE IF EXISTS `log_retention1`;
CREATE TABLE IF NOT EXISTS `log_retention1` (
  `log_date` date NOT NULL,
  `server_id` char(5) NOT NULL,
  `partner_key` char(16) NOT NULL,
  `level_account` int(11) NOT NULL DEFAULT '0' COMMENT '有效用户数',
  `next_current_login` int(11) NOT NULL DEFAULT '0' COMMENT '次日登录',
  `next_retention` int(11) NOT NULL DEFAULT '0' COMMENT '次日留存',
  `third_current_login` int(11) NOT NULL DEFAULT '0' COMMENT '点三登录',
  `third_retention` int(11) NOT NULL DEFAULT '0' COMMENT '点三留存',
  `third_current_login_range` int(11) NOT NULL DEFAULT '0' COMMENT '连续三日登录',
  `third_retention_range` int(11) NOT NULL DEFAULT '0',
  `seven_current_login` int(11) NOT NULL DEFAULT '0',
  `seven_retention` int(11) NOT NULL DEFAULT '0',
  `seven_current_login_range` int(11) NOT NULL DEFAULT '0' COMMENT '小区间七日登录',
  `seven_retention_range` int(11) NOT NULL DEFAULT '0' COMMENT '小区间七日留存率',
  `seven_current_login_huge` int(11) NOT NULL DEFAULT '0' COMMENT '大区间7日登录',
  `seven_retention_huge` int(11) NOT NULL DEFAULT '0' COMMENT '大区间七日留存',
  `level1` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log_buy_equipment_detail`
--
ALTER TABLE `log_buy_equipment_detail`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_daily_statistics`
--
ALTER TABLE `log_daily_statistics`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `log_date` (`log_date`,`server_id`,`partner_key`);

--
-- Indexes for table `log_flowover_cache`
--
ALTER TABLE `log_flowover_cache`
 ADD PRIMARY KEY (`guid`,`server_id`,`partner_key`);

--
-- Indexes for table `log_flowover_detail`
--
ALTER TABLE `log_flowover_detail`
 ADD PRIMARY KEY (`date`,`server_id`,`partner_key`);

--
-- Indexes for table `log_funds_hours`
--
ALTER TABLE `log_funds_hours`
 ADD PRIMARY KEY (`log_date`,`server_id`,`log_hour`,`partner_key`);

--
-- Indexes for table `log_online_count`
--
ALTER TABLE `log_online_count`
 ADD PRIMARY KEY (`server_id`,`partner_key`,`log_date`,`log_hour`);

--
-- Indexes for table `log_retention`
--
ALTER TABLE `log_retention`
 ADD PRIMARY KEY (`log_date`,`server_id`,`partner_key`);

--
-- Indexes for table `log_retention1`
--
ALTER TABLE `log_retention1`
 ADD PRIMARY KEY (`log_date`,`server_id`,`partner_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_buy_equipment_detail`
--
ALTER TABLE `log_buy_equipment_detail`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `log_daily_statistics`
--
ALTER TABLE `log_daily_statistics`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;--
-- Database: `agent1_log_db_201203`
--
DROP DATABASE `agent1_log_db_201203`;
CREATE DATABASE IF NOT EXISTS `agent1_log_db_201203` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent1_log_db_201203`;

-- --------------------------------------------------------

--
-- 表的结构 `equipment_name`
--

DROP TABLE IF EXISTS `equipment_name`;
CREATE TABLE IF NOT EXISTS `equipment_name` (
  `equipment_name` char(16) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_account`
--

DROP TABLE IF EXISTS `log_account`;
CREATE TABLE IF NOT EXISTS `log_account` (
`log_id` int(11) NOT NULL,
  `log_GUID` char(36) NOT NULL,
  `device_id` char(64) NOT NULL,
  `log_account_name` char(64) DEFAULT NULL,
  `log_account_level` int(11) NOT NULL DEFAULT '0',
  `log_action` char(64) NOT NULL,
  `log_parameter` text,
  `log_time` int(11) NOT NULL,
  `log_ip` char(24) NOT NULL,
  `server_id` char(5) NOT NULL,
  `partner_key` char(16) NOT NULL DEFAULT 'default'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_action_mall`
--

DROP TABLE IF EXISTS `log_action_mall`;
CREATE TABLE IF NOT EXISTS `log_action_mall` (
`id` int(11) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `server_id` char(5) NOT NULL,
  `nickname` char(16) NOT NULL,
  `content` text NOT NULL,
  `posttime` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_api`
--

DROP TABLE IF EXISTS `log_api`;
CREATE TABLE IF NOT EXISTS `log_api` (
`log_id` int(11) NOT NULL,
  `log_GUID` char(36) NOT NULL,
  `log_account_name` char(64) DEFAULT NULL,
  `log_action` char(64) NOT NULL,
  `log_parameter` text,
  `log_time` int(11) NOT NULL,
  `log_ip` char(24) NOT NULL,
  `server_id` char(5) NOT NULL,
  `partner_key` char(16) NOT NULL DEFAULT 'default'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_consume`
--

DROP TABLE IF EXISTS `log_consume`;
CREATE TABLE IF NOT EXISTS `log_consume` (
`log_id` int(11) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `role_level` int(11) NOT NULL DEFAULT '0',
  `role_mission` char(16) NOT NULL,
  `action_name` char(64) NOT NULL,
  `current_gold` int(11) NOT NULL,
  `spend_gold` int(11) NOT NULL,
  `current_special_gold` int(11) NOT NULL,
  `spend_special_gold` int(11) NOT NULL,
  `item_name` char(64) NOT NULL,
  `item_info` text NOT NULL,
  `item_type` int(11) NOT NULL,
  `item_level` int(11) NOT NULL DEFAULT '0' COMMENT '装备等级',
  `item_value` int(11) NOT NULL DEFAULT '0' COMMENT '装�',
  `item_job` char(16) NOT NULL DEFAULT '' COMMENT '装备�',
  `log_time` int(11) NOT NULL,
  `server_id` char(5) NOT NULL,
  `partner_key` char(16) NOT NULL DEFAULT 'default'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_rep`
--

DROP TABLE IF EXISTS `log_rep`;
CREATE TABLE IF NOT EXISTS `log_rep` (
`id` bigint(20) NOT NULL,
  `server_id` char(5) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `type` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `profession` char(16) NOT NULL,
  `nickname` char(20) NOT NULL,
  `posttime` int(11) NOT NULL,
  `partner_key` char(16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipment_name`
--
ALTER TABLE `equipment_name`
 ADD PRIMARY KEY (`equipment_name`), ADD KEY `type` (`type`);

--
-- Indexes for table `log_account`
--
ALTER TABLE `log_account`
 ADD PRIMARY KEY (`log_id`), ADD KEY `log_GUID` (`log_GUID`) USING BTREE, ADD KEY `log_account_name` (`log_account_name`) USING BTREE, ADD KEY `log_action` (`log_action`) USING BTREE, ADD KEY `game_id` (`server_id`);

--
-- Indexes for table `log_action_mall`
--
ALTER TABLE `log_action_mall`
 ADD PRIMARY KEY (`id`), ADD KEY `player_id` (`player_id`);

--
-- Indexes for table `log_api`
--
ALTER TABLE `log_api`
 ADD PRIMARY KEY (`log_id`), ADD KEY `log_GUID` (`log_GUID`) USING BTREE, ADD KEY `log_account_name` (`log_account_name`) USING BTREE, ADD KEY `log_action` (`log_action`) USING BTREE, ADD KEY `game_id` (`server_id`);

--
-- Indexes for table `log_consume`
--
ALTER TABLE `log_consume`
 ADD PRIMARY KEY (`log_id`), ADD KEY `player_id` (`player_id`), ADD KEY `item_name` (`item_name`), ADD KEY `server_id` (`server_id`);

--
-- Indexes for table `log_rep`
--
ALTER TABLE `log_rep`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log_account`
--
ALTER TABLE `log_account`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `log_action_mall`
--
ALTER TABLE `log_action_mall`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `log_api`
--
ALTER TABLE `log_api`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `log_consume`
--
ALTER TABLE `log_consume`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `log_rep`
--
ALTER TABLE `log_rep`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;--
-- Database: `agent1_product_db`
--
DROP DATABASE `agent1_product_db`;
CREATE DATABASE IF NOT EXISTS `agent1_product_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent1_product_db`;

-- --------------------------------------------------------

--
-- 表的结构 `coupon`
--

DROP TABLE IF EXISTS `coupon`;
CREATE TABLE IF NOT EXISTS `coupon` (
  `coupon` char(16) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `coupon_used`
--

DROP TABLE IF EXISTS `coupon_used`;
CREATE TABLE IF NOT EXISTS `coupon_used` (
  `role_id` bigint(20) NOT NULL,
  `coupon` char(16) NOT NULL,
  `timestamp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `game_announcement`
--

DROP TABLE IF EXISTS `game_announcement`;
CREATE TABLE IF NOT EXISTS `game_announcement` (
`id` int(11) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `post_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `partner_key` text NOT NULL,
  `server_id` char(5) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `game_announcement_crontab`
--

DROP TABLE IF EXISTS `game_announcement_crontab`;
CREATE TABLE IF NOT EXISTS `game_announcement_crontab` (
`id` int(11) NOT NULL,
  `server_id` char(5) NOT NULL,
  `content` text NOT NULL,
  `minutes` char(64) NOT NULL,
  `hour` char(64) NOT NULL,
  `date` char(64) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `game_autosend_message`
--

DROP TABLE IF EXISTS `game_autosend_message`;
CREATE TABLE IF NOT EXISTS `game_autosend_message` (
`id` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_auto_send` tinyint(4) NOT NULL DEFAULT '1',
  `pattern` char(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `game_code`
--

DROP TABLE IF EXISTS `game_code`;
CREATE TABLE IF NOT EXISTS `game_code` (
  `code` char(20) NOT NULL,
  `comment` char(16) NOT NULL DEFAULT '',
  `disabled` tinyint(4) NOT NULL DEFAULT '0',
  `server_id` char(8) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `game_product`
--

DROP TABLE IF EXISTS `game_product`;
CREATE TABLE IF NOT EXISTS `game_product` (
  `game_id` char(5) NOT NULL,
  `game_name` char(64) NOT NULL,
  `game_version` char(16) NOT NULL,
  `game_platform` enum('web','ios','android') DEFAULT 'ios',
  `auth_key` char(128) NOT NULL,
  `game_pic_small` text,
  `game_pic_middium` text,
  `game_pic_big` text,
  `game_download_iphone` text,
  `game_download_ipad` text,
  `game_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=正式,1=内测,2=公测'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `server_list`
--

DROP TABLE IF EXISTS `server_list`;
CREATE TABLE IF NOT EXISTS `server_list` (
`id` int(11) NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` int(11) NOT NULL,
  `account_server_id` char(5) NOT NULL,
  `server_name` char(32) NOT NULL,
  `server_ip` text NOT NULL,
  `server_game_ip` text NOT NULL,
  `game_message_ip` text NOT NULL,
  `const_server_ip` text NOT NULL,
  `voice_server_ip` text NOT NULL,
  `cross_server_ip` text NOT NULL,
  `server_max_player` int(11) NOT NULL DEFAULT '0',
  `account_count` int(11) NOT NULL DEFAULT '0',
  `server_language` char(16) NOT NULL DEFAULT 'CN',
  `server_sort` int(11) NOT NULL DEFAULT '0',
  `server_recommend` tinyint(1) NOT NULL DEFAULT '0',
  `server_debug` tinyint(4) NOT NULL DEFAULT '0',
  `partner` char(64) NOT NULL DEFAULT 'default',
  `version` char(64) NOT NULL,
  `server_status` int(11) NOT NULL DEFAULT '1' COMMENT '0=关闭；1=正常；2=繁忙；3=拥挤；9=隐藏',
  `server_new` int(11) NOT NULL DEFAULT '1' COMMENT '1=新服；0=旧服',
  `special_ip` char(16) NOT NULL DEFAULT '',
  `server_starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开服时间',
  `need_activate` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `server_status`
--

DROP TABLE IF EXISTS `server_status`;
CREATE TABLE IF NOT EXISTS `server_status` (
  `server_status` int(11) NOT NULL,
  `message` text NOT NULL,
  `redirectUrl` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
 ADD PRIMARY KEY (`coupon`), ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `coupon_used`
--
ALTER TABLE `coupon_used`
 ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `game_announcement`
--
ALTER TABLE `game_announcement`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_announcement_crontab`
--
ALTER TABLE `game_announcement_crontab`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_autosend_message`
--
ALTER TABLE `game_autosend_message`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_code`
--
ALTER TABLE `game_code`
 ADD PRIMARY KEY (`code`);

--
-- Indexes for table `game_product`
--
ALTER TABLE `game_product`
 ADD PRIMARY KEY (`game_id`);

--
-- Indexes for table `server_list`
--
ALTER TABLE `server_list`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `unique_id` (`game_id`,`account_server_id`,`server_name`,`special_ip`), ADD KEY `server_recommend` (`server_recommend`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game_announcement`
--
ALTER TABLE `game_announcement`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `game_announcement_crontab`
--
ALTER TABLE `game_announcement_crontab`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `game_autosend_message`
--
ALTER TABLE `game_autosend_message`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `server_list`
--
ALTER TABLE `server_list`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;--
-- Database: `agent1_web_db`
--
DROP DATABASE `agent1_web_db`;
CREATE DATABASE IF NOT EXISTS `agent1_web_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent1_web_db`;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `scc_auto_template`
--
DROP VIEW IF EXISTS `scc_auto_template`;
CREATE TABLE IF NOT EXISTS `scc_auto_template` (
`template_id` int(11)
,`template_name` text
,`template_content` text
,`template_subject` text
,`template_reader` varchar(45)
,`smtp_host` varchar(20)
,`smtp_user` varchar(45)
,`smtp_pass` varchar(45)
,`smtp_from` varchar(45)
,`smtp_fromName` varchar(45)
,`auto_id` int(11)
,`auto_template_id` int(11)
,`auto_actived` tinyint(1)
,`auto_name` text
);
-- --------------------------------------------------------

--
-- 表的结构 `scc_config`
--

DROP TABLE IF EXISTS `scc_config`;
CREATE TABLE IF NOT EXISTS `scc_config` (
`config_id` int(11) NOT NULL,
  `config_name` varchar(45) NOT NULL,
  `config_close_scc` tinyint(1) NOT NULL DEFAULT '0',
  `config_close_reason` text NOT NULL,
  `config_selected` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_mail_autosend`
--

DROP TABLE IF EXISTS `scc_mail_autosend`;
CREATE TABLE IF NOT EXISTS `scc_mail_autosend` (
`auto_id` int(11) NOT NULL,
  `auto_template_id` int(11) NOT NULL,
  `auto_actived` tinyint(1) NOT NULL DEFAULT '1',
  `auto_name` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_mail_template`
--

DROP TABLE IF EXISTS `scc_mail_template`;
CREATE TABLE IF NOT EXISTS `scc_mail_template` (
`template_id` int(11) NOT NULL,
  `template_name` text,
  `template_content` text NOT NULL,
  `template_auto_send` tinyint(4) DEFAULT NULL,
  `template_subject` text,
  `template_reader` varchar(45) DEFAULT NULL,
  `smtp_host` varchar(20) NOT NULL DEFAULT '67.228.209.12',
  `smtp_user` varchar(45) NOT NULL DEFAULT 'contact@macxdvd.com',
  `smtp_pass` varchar(45) NOT NULL DEFAULT 'cont333999',
  `smtp_from` varchar(45) NOT NULL DEFAULT 'contact@macxdvd.com',
  `smtp_fromName` varchar(45) NOT NULL DEFAULT 'contact@macxdvd.com'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_partner`
--

DROP TABLE IF EXISTS `scc_partner`;
CREATE TABLE IF NOT EXISTS `scc_partner` (
  `partner_key` char(16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `scc_permission`
--

DROP TABLE IF EXISTS `scc_permission`;
CREATE TABLE IF NOT EXISTS `scc_permission` (
  `permission_id` int(11) NOT NULL,
  `permission_name` varchar(24) NOT NULL,
  `permission_list` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `scc_user`
--

DROP TABLE IF EXISTS `scc_user`;
CREATE TABLE IF NOT EXISTS `scc_user` (
  `GUID` varchar(36) NOT NULL,
  `user_name` varchar(16) NOT NULL,
  `user_pass` varchar(64) NOT NULL,
  `user_permission` int(11) NOT NULL DEFAULT '1',
  `permission_name` char(16) NOT NULL,
  `user_founder` tinyint(1) NOT NULL DEFAULT '0',
  `user_freezed` tinyint(4) NOT NULL DEFAULT '0',
  `additional_permission` text NOT NULL,
  `user_fromwhere` char(16) NOT NULL DEFAULT 'default'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `scc_user_permission`
--
DROP VIEW IF EXISTS `scc_user_permission`;
CREATE TABLE IF NOT EXISTS `scc_user_permission` (
`GUID` varchar(36)
,`user_name` varchar(16)
,`user_pass` varchar(64)
,`user_permission` int(11)
,`user_founder` tinyint(1)
,`user_freezed` tinyint(4)
,`additional_permission` text
,`user_fromwhere` char(16)
,`permission_id` int(11)
,`permission_name` varchar(24)
,`permission_list` text
);
-- --------------------------------------------------------

--
-- 视图结构 `scc_auto_template`
--
DROP TABLE IF EXISTS `scc_auto_template`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `scc_auto_template` AS select `scc_mail_template`.`template_id` AS `template_id`,`scc_mail_template`.`template_name` AS `template_name`,`scc_mail_template`.`template_content` AS `template_content`,`scc_mail_template`.`template_subject` AS `template_subject`,`scc_mail_template`.`template_reader` AS `template_reader`,`scc_mail_template`.`smtp_host` AS `smtp_host`,`scc_mail_template`.`smtp_user` AS `smtp_user`,`scc_mail_template`.`smtp_pass` AS `smtp_pass`,`scc_mail_template`.`smtp_from` AS `smtp_from`,`scc_mail_template`.`smtp_fromName` AS `smtp_fromName`,`scc_mail_autosend`.`auto_id` AS `auto_id`,`scc_mail_autosend`.`auto_template_id` AS `auto_template_id`,`scc_mail_autosend`.`auto_actived` AS `auto_actived`,`scc_mail_autosend`.`auto_name` AS `auto_name` from (`scc_mail_template` join `scc_mail_autosend`) where (`scc_mail_autosend`.`auto_template_id` = `scc_mail_template`.`template_id`);

-- --------------------------------------------------------

--
-- 视图结构 `scc_user_permission`
--
DROP TABLE IF EXISTS `scc_user_permission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `scc_user_permission` AS select `a`.`GUID` AS `GUID`,`a`.`user_name` AS `user_name`,`a`.`user_pass` AS `user_pass`,`a`.`user_permission` AS `user_permission`,`a`.`user_founder` AS `user_founder`,`a`.`user_freezed` AS `user_freezed`,`a`.`additional_permission` AS `additional_permission`,`a`.`user_fromwhere` AS `user_fromwhere`,`b`.`permission_id` AS `permission_id`,`b`.`permission_name` AS `permission_name`,`b`.`permission_list` AS `permission_list` from (`scc_user` `a` join `scc_permission` `b`) where (`a`.`user_permission` = `b`.`permission_id`);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `scc_config`
--
ALTER TABLE `scc_config`
 ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `scc_mail_autosend`
--
ALTER TABLE `scc_mail_autosend`
 ADD PRIMARY KEY (`auto_id`);

--
-- Indexes for table `scc_mail_template`
--
ALTER TABLE `scc_mail_template`
 ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `scc_partner`
--
ALTER TABLE `scc_partner`
 ADD PRIMARY KEY (`partner_key`);

--
-- Indexes for table `scc_permission`
--
ALTER TABLE `scc_permission`
 ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `scc_user`
--
ALTER TABLE `scc_user`
 ADD PRIMARY KEY (`GUID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `scc_config`
--
ALTER TABLE `scc_config`
MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `scc_mail_autosend`
--
ALTER TABLE `scc_mail_autosend`
MODIFY `auto_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scc_mail_template`
--
ALTER TABLE `scc_mail_template`
MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
