-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 01 月 07 日 10:11
-- 服务器版本: 5.1.61-log
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `agent_product_db`
--
CREATE DATABASE `agent_product_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent_product_db`;

-- --------------------------------------------------------

--
-- 表的结构 `game_product`
--

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
  `game_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=正式,1=内测,2=公测',
  PRIMARY KEY (`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `section_list`
--

CREATE TABLE IF NOT EXISTS `section_list` (
  `game_id` char(5) NOT NULL,
  `server_section_id` char(5) NOT NULL,
  `section_name` char(32) NOT NULL,
  PRIMARY KEY (`server_section_id`,`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `server_list`
--

CREATE TABLE IF NOT EXISTS `server_list` (
  `game_id` char(5) NOT NULL,
  `account_server_section` char(5) NOT NULL,
  `account_server_id` char(5) NOT NULL,
  `server_name` char(32) NOT NULL,
  `server_ip` char(32) NOT NULL,
  `server_port` int(11) NOT NULL,
  `server_game_ip` char(32) NOT NULL,
  `server_game_port` int(11) NOT NULL,
  `server_message_ip` char(32) NOT NULL,
  `server_message_port` int(11) NOT NULL,
  `team_server` char(32) NOT NULL,
  `team_server_port` int(11) NOT NULL,
  `server_max_player` int(11) NOT NULL DEFAULT '0',
  `account_count` int(11) NOT NULL DEFAULT '0',
  `server_language` char(16) DEFAULT NULL,
  `server_sort` int(11) NOT NULL DEFAULT '0',
  `server_recommend` tinyint(1) NOT NULL DEFAULT '0',
  `server_mode` enum('debug','normal','partner') NOT NULL DEFAULT 'normal',
  PRIMARY KEY (`game_id`,`account_server_section`,`account_server_id`),
  KEY `server_recommend` (`server_recommend`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `server_list_view`
--
CREATE TABLE IF NOT EXISTS `server_list_view` (
`game_id` char(5)
,`account_server_section` char(5)
,`account_server_id` char(5)
,`server_name` char(32)
,`server_ip` char(32)
,`server_port` int(11)
,`server_message_ip` char(32)
,`server_message_port` int(11)
,`server_max_player` int(11)
,`account_count` int(11)
,`server_language` char(16)
,`server_recommend` tinyint(1)
,`section_name` char(32)
,`game_name` char(64)
,`game_version` char(16)
,`game_platform` enum('web','ios','android')
,`auth_key` char(128)
,`game_pic_small` text
,`game_pic_middium` text
,`game_pic_big` text
,`game_download_iphone` text
,`game_download_ipad` text
,`game_status` tinyint(4)
,`server_mode` enum('debug','normal','partner')
);
-- --------------------------------------------------------

--
-- 视图结构 `server_list_view`
--
DROP TABLE IF EXISTS `server_list_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `server_list_view` AS select `server_list`.`game_id` AS `game_id`,`server_list`.`account_server_section` AS `account_server_section`,`server_list`.`account_server_id` AS `account_server_id`,`server_list`.`server_name` AS `server_name`,`server_list`.`server_ip` AS `server_ip`,`server_list`.`server_port` AS `server_port`,`server_list`.`server_message_ip` AS `server_message_ip`,`server_list`.`server_message_port` AS `server_message_port`,`server_list`.`server_max_player` AS `server_max_player`,`server_list`.`account_count` AS `account_count`,`server_list`.`server_language` AS `server_language`,`server_list`.`server_recommend` AS `server_recommend`,`section_list`.`section_name` AS `section_name`,`game_product`.`game_name` AS `game_name`,`game_product`.`game_version` AS `game_version`,`game_product`.`game_platform` AS `game_platform`,`game_product`.`auth_key` AS `auth_key`,`game_product`.`game_pic_small` AS `game_pic_small`,`game_product`.`game_pic_middium` AS `game_pic_middium`,`game_product`.`game_pic_big` AS `game_pic_big`,`game_product`.`game_download_iphone` AS `game_download_iphone`,`game_product`.`game_download_ipad` AS `game_download_ipad`,`game_product`.`game_status` AS `game_status`,`server_list`.`server_mode` AS `server_mode` from ((`server_list` join `section_list` on((`server_list`.`account_server_section` = `section_list`.`server_section_id`))) join `game_product` on((`server_list`.`game_id` = `game_product`.`game_id`)));

--
-- 数据库: `agent_account_db`
--
CREATE DATABASE `agent_account_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent_account_db`;

-- --------------------------------------------------------

--
-- 表的结构 `account_added_game`
--

CREATE TABLE IF NOT EXISTS `account_added_game` (
  `GUID` char(36) NOT NULL,
  `game_id` char(10) NOT NULL,
  PRIMARY KEY (`GUID`,`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `closure_account`
--

CREATE TABLE IF NOT EXISTS `closure_account` (
  `GUID` char(36) NOT NULL,
  `account_closure_reason` text,
  `account_closure_starttime` int(11) NOT NULL,
  `account_closure_endtime` int(11) NOT NULL,
  PRIMARY KEY (`GUID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `game_account`
--

CREATE TABLE IF NOT EXISTS `game_account` (
  `account_id` char(12) NOT NULL,
  `account_name` char(64) NOT NULL,
  `account_guid` char(36) NOT NULL,
  `game_id` char(5) NOT NULL,
  `account_server_id` char(5) NOT NULL,
  `account_server_section` char(5) NOT NULL,
  `nick_name` char(32) NOT NULL,
  `account_cash` int(11) NOT NULL DEFAULT '0',
  `account_active` tinyint(1) NOT NULL DEFAULT '0',
  `account_active_week` tinyint(1) NOT NULL DEFAULT '0',
  `account_active_month` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_guid`,`game_id`,`account_server_id`,`account_server_section`),
  UNIQUE KEY `account_id` (`account_id`),
  KEY `account_active` (`account_active`),
  KEY `account_name` (`account_name`),
  KEY `nick_name` (`nick_name`),
  KEY `account_active_2` (`account_active`),
  KEY `account_active_week` (`account_active_week`),
  KEY `account_active_month` (`account_active_month`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `game_account_view`
--
CREATE TABLE IF NOT EXISTS `game_account_view` (
`game_id` char(10)
,`GUID` char(36)
,`game_name` char(64)
,`game_version` char(16)
,`game_platform` enum('web','ios','android')
,`auth_key` char(128)
,`game_pic_middium` text
,`game_pic_big` text
,`game_pic_small` text
);
-- --------------------------------------------------------

--
-- 表的结构 `master_account`
--

CREATE TABLE IF NOT EXISTS `master_account` (
  `master_id` int(11) NOT NULL AUTO_INCREMENT,
  `master_name` char(16) NOT NULL,
  `master_generationtime` int(11) NOT NULL,
  PRIMARY KEY (`master_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `web_account`
--

CREATE TABLE IF NOT EXISTS `web_account` (
  `GUID` char(36) NOT NULL,
  `account_name` char(64) NOT NULL,
  `account_pass` char(32) NOT NULL,
  `account_email` char(64) NOT NULL,
  `account_nickname` char(16) DEFAULT NULL,
  `account_secret_key` char(128) NOT NULL,
  `account_firstname` char(32) DEFAULT NULL,
  `account_lastname` char(32) DEFAULT NULL,
  `account_country` char(16) DEFAULT NULL,
  `account_pass_question` char(128) DEFAULT NULL,
  `account_pass_answer` char(128) DEFAULT NULL,
  `account_point` int(11) NOT NULL DEFAULT '0',
  `game_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `server_section` char(5) NOT NULL,
  `account_regtime` int(11) NOT NULL DEFAULT '0',
  `account_lastlogin` int(11) NOT NULL DEFAULT '0',
  `account_currentlogin` int(11) NOT NULL DEFAULT '0',
  `account_lastip` char(16) DEFAULT NULL,
  `account_currentip` char(16) DEFAULT NULL,
  `account_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=正常 0=冻结 -1=封停',
  `account_activity` int(11) NOT NULL DEFAULT '0',
  `partner_key` char(8) NOT NULL,
  PRIMARY KEY (`GUID`),
  KEY `account_name` (`account_name`,`account_pass`,`server_section`,`game_id`,`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 视图结构 `game_account_view`
--
DROP TABLE IF EXISTS `game_account_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `game_account_view` AS select `account_added_game`.`game_id` AS `game_id`,`account_added_game`.`GUID` AS `GUID`,`agent_product_db`.`game_product`.`game_name` AS `game_name`,`agent_product_db`.`game_product`.`game_version` AS `game_version`,`agent_product_db`.`game_product`.`game_platform` AS `game_platform`,`agent_product_db`.`game_product`.`auth_key` AS `auth_key`,`agent_product_db`.`game_product`.`game_pic_middium` AS `game_pic_middium`,`agent_product_db`.`game_product`.`game_pic_big` AS `game_pic_big`,`agent_product_db`.`game_product`.`game_pic_small` AS `game_pic_small` from (`account_added_game` join `agent_product_db`.`game_product` on((`account_added_game`.`game_id` = `agent_product_db`.`game_product`.`game_id`)));
--
-- 数据库: `agent_adminlog_db`
--
CREATE DATABASE `agent_adminlog_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent_adminlog_db`;

-- --------------------------------------------------------

--
-- 表的结构 `log_scc`
--

CREATE TABLE IF NOT EXISTS `log_scc` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_type` varchar(64) NOT NULL,
  `log_user` text,
  `log_relative_page_url` varchar(128) NOT NULL,
  `log_relative_parameter` text NOT NULL,
  `log_addition_parameter` text,
  `log_relative_method` varchar(12) NOT NULL,
  `log_time` datetime NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=220 ;
--
-- 数据库: `agent_funds_flow_db`
--
CREATE DATABASE `agent_funds_flow_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent_funds_flow_db`;

-- --------------------------------------------------------

--
-- 表的结构 `check_funds`
--

CREATE TABLE IF NOT EXISTS `check_funds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=271 ;

-- --------------------------------------------------------

--
-- 表的结构 `currency_exchange_rate`
--

CREATE TABLE IF NOT EXISTS `currency_exchange_rate` (
  `currency` char(6) NOT NULL,
  `exchange_rate` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currency`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `funds_checkinout`
--

CREATE TABLE IF NOT EXISTS `funds_checkinout` (
  `funds_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_guid` char(36) NOT NULL,
  `account_name` char(64) NOT NULL,
  `account_nickname` char(32) NOT NULL,
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
  PRIMARY KEY (`funds_id`),
  KEY `account_guid` (`account_name`),
  KEY `account_id` (`account_id`),
  KEY `game_id` (`game_id`,`server_id`,`server_section`),
  KEY `funds_flow_dir` (`funds_flow_dir`),
  KEY `funds_time` (`funds_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21408 ;

-- --------------------------------------------------------

--
-- 表的结构 `funds_order`
--

CREATE TABLE IF NOT EXISTS `funds_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` char(22) NOT NULL,
  `game_id` char(1) NOT NULL,
  `section_id` char(1) NOT NULL,
  `server_id` char(1) NOT NULL,
  `checksum` char(64) NOT NULL,
  `check_count` int(11) NOT NULL,
  `posttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `checksum_2` (`checksum`),
  KEY `checksum` (`checksum`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=185 ;
--
-- 数据库: `agent_log_db`
--
CREATE DATABASE `agent_log_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent_log_db`;

-- --------------------------------------------------------

--
-- 表的结构 `log_active_count_daily`
--

CREATE TABLE IF NOT EXISTS `log_active_count_daily` (
  `log_date` date NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `cache_count` int(11) NOT NULL,
  PRIMARY KEY (`log_date`,`game_id`,`section_id`,`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_active_count_month`
--

CREATE TABLE IF NOT EXISTS `log_active_count_month` (
  `log_date` date NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `cache_count` int(11) NOT NULL,
  PRIMARY KEY (`log_date`,`game_id`,`section_id`,`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_active_count_week`
--

CREATE TABLE IF NOT EXISTS `log_active_count_week` (
  `log_date` date NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `cache_count` int(11) NOT NULL,
  PRIMARY KEY (`log_date`,`game_id`,`section_id`,`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_daily_statistics`
--

CREATE TABLE IF NOT EXISTS `log_daily_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_date` date NOT NULL,
  `server_name` char(16) NOT NULL,
  `reg_account` int(11) NOT NULL,
  `modify_account` int(11) NOT NULL,
  `login_account` int(11) NOT NULL,
  `orders_num` int(11) NOT NULL,
  `orders_sum` int(11) NOT NULL,
  `partner_key` char(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=131 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_login_count_per_date`
--

CREATE TABLE IF NOT EXISTS `log_login_count_per_date` (
  `cache_year` int(11) NOT NULL,
  `cache_month` int(11) NOT NULL,
  `cache_date` int(11) NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `platform` enum('iphone','ipad','web') NOT NULL,
  `cache_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cache_year`,`cache_month`,`cache_date`,`game_id`,`section_id`,`server_id`),
  KEY `platform` (`platform`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_login_count_per_minutes`
--

CREATE TABLE IF NOT EXISTS `log_login_count_per_minutes` (
  `cache_year` int(11) NOT NULL,
  `cache_month` int(11) NOT NULL,
  `cache_date` int(11) NOT NULL,
  `cache_hour` int(11) NOT NULL,
  `cache_minutes` int(11) NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `platform` enum('iphone','ipad','web') NOT NULL,
  `cache_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cache_year`,`cache_month`,`cache_date`,`cache_hour`,`cache_minutes`,`game_id`,`section_id`,`server_id`),
  KEY `platform` (`platform`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_overview`
--

CREATE TABLE IF NOT EXISTS `log_overview` (
  `log_date` date NOT NULL,
  `log_register_count` int(11) NOT NULL,
  `log_login_count` int(11) NOT NULL,
  `log_active_count` int(11) NOT NULL,
  `log_pay_count` int(11) NOT NULL,
  `log_payment_count` int(11) NOT NULL,
  `log_checkin_count` int(11) NOT NULL,
  `log_item_count` int(11) NOT NULL COMMENT '充值的暗能水晶数',
  `log_checkout_count` int(11) NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  PRIMARY KEY (`log_date`,`game_id`,`section_id`,`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_overview_group_by_type`
--

CREATE TABLE IF NOT EXISTS `log_overview_group_by_type` (
  `log_date` date NOT NULL,
  `log_active_count` int(11) NOT NULL,
  `log_pay_count` int(11) NOT NULL,
  `log_payment_count` int(11) NOT NULL,
  `log_checkin_count` int(11) NOT NULL,
  `log_item_count` int(11) NOT NULL COMMENT '充值的暗能水晶数',
  `log_checkout_count` int(11) NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `funds_type` int(11) NOT NULL,
  PRIMARY KEY (`log_date`,`game_id`,`section_id`,`server_id`,`funds_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_props_sell`
--

CREATE TABLE IF NOT EXISTS `log_props_sell` (
  `log_date` date NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` char(32) NOT NULL,
  `item_count` int(11) NOT NULL,
  `item_fee` int(11) NOT NULL,
  PRIMARY KEY (`log_date`,`game_id`,`section_id`,`server_id`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_register_count_per_date`
--

CREATE TABLE IF NOT EXISTS `log_register_count_per_date` (
  `cache_year` int(11) NOT NULL,
  `cache_month` int(11) NOT NULL,
  `cache_date` int(11) NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `platform` enum('iphone','ipad','web') NOT NULL,
  `cache_count` int(11) NOT NULL,
  PRIMARY KEY (`cache_year`,`cache_month`,`cache_date`,`game_id`,`section_id`,`server_id`,`platform`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_register_count_per_minutes`
--

CREATE TABLE IF NOT EXISTS `log_register_count_per_minutes` (
  `cache_year` int(11) NOT NULL,
  `cache_month` int(11) NOT NULL,
  `cache_date` int(11) NOT NULL,
  `cache_hour` int(11) NOT NULL,
  `cache_minutes` int(11) NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `platform` enum('iphone','ipad','web') NOT NULL,
  `cache_count` int(11) NOT NULL,
  PRIMARY KEY (`cache_year`,`cache_month`,`cache_date`,`cache_hour`,`cache_minutes`,`game_id`,`section_id`,`server_id`,`platform`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
-- 数据库: `agent_log_db_201203`
--
CREATE DATABASE `agent_log_db_201203` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent_log_db_201203`;

-- --------------------------------------------------------

--
-- 表的结构 `log_account`
--

CREATE TABLE IF NOT EXISTS `log_account` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_GUID` char(36) NOT NULL,
  `log_account_name` char(64) DEFAULT NULL,
  `log_account_email` char(128) DEFAULT NULL,
  `log_action` char(64) NOT NULL,
  `log_uri` char(128) NOT NULL,
  `log_method` char(10) DEFAULT NULL,
  `log_parameter` text,
  `log_time` int(11) NOT NULL DEFAULT '0',
  `log_time_local` datetime NOT NULL,
  `log_ip` char(24) NOT NULL,
  `game_id` char(5) NOT NULL,
  `section_id` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  `platform` enum('iphone','ipad','web') NOT NULL DEFAULT 'iphone',
  PRIMARY KEY (`log_id`),
  KEY `log_GUID` (`log_GUID`) USING BTREE,
  KEY `log_account_name` (`log_account_name`) USING BTREE,
  KEY `log_time` (`log_time`) USING BTREE,
  KEY `log_action` (`log_action`) USING BTREE,
  KEY `game_id` (`game_id`,`section_id`,`server_id`),
  KEY `platform` (`platform`),
  KEY `log_ip` (`log_ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=180869 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_mall`
--

CREATE TABLE IF NOT EXISTS `log_mall` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_account_id` char(16) NOT NULL,
  `log_account_name` char(64) NOT NULL,
  `log_account_nickname` char(64) NOT NULL,
  `log_type` enum('building_upgrade','skill_upgrade','construct_tower','exchange_resource','accelerate','output','mall_props','vehicles_strengthen','vehicles_repair','vehicles_resurrection','legion_battle') NOT NULL,
  `log_spend_item_id` char(32) NOT NULL,
  `log_spend_item_name` char(16) NOT NULL,
  `log_spend_item_count` int(11) NOT NULL,
  `log_get_item_id` char(32) NOT NULL,
  `log_get_item_name` char(16) NOT NULL,
  `log_get_item_count` int(11) NOT NULL,
  `log_time` int(11) NOT NULL,
  `log_local_time` datetime NOT NULL,
  `game_id` char(5) NOT NULL,
  `server_section` char(5) NOT NULL,
  `server_id` char(5) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_item_id` (`log_spend_item_id`),
  KEY `log_time` (`log_time`),
  KEY `log_account_id` (`log_account_id`),
  KEY `game_id` (`game_id`,`server_section`,`server_id`),
  KEY `log_get_item_id` (`log_get_item_id`),
  KEY `log_type` (`log_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=134791 ;

--
-- 数据库: `agent_web_db`
--
CREATE DATABASE `agent_web_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent_web_db`;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `scc_auto_template`
--
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
-- 表的结构 `scc_channels`
--

CREATE TABLE IF NOT EXISTS `scc_channels` (
  `channel_id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_name` char(16) NOT NULL,
  `channel_url` varchar(255) DEFAULT NULL,
  `channel_father_id` int(11) NOT NULL DEFAULT '0',
  `channel_web_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`channel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_config`
--

CREATE TABLE IF NOT EXISTS `scc_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(45) NOT NULL,
  `config_close_scc` tinyint(1) NOT NULL DEFAULT '0',
  `config_close_reason` text NOT NULL,
  `config_selected` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_links`
--

CREATE TABLE IF NOT EXISTS `scc_links` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `link_content` text NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_mail_autosend`
--

CREATE TABLE IF NOT EXISTS `scc_mail_autosend` (
  `auto_id` int(11) NOT NULL AUTO_INCREMENT,
  `auto_template_id` int(11) NOT NULL,
  `auto_actived` tinyint(1) NOT NULL DEFAULT '1',
  `auto_name` text,
  PRIMARY KEY (`auto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_mail_template`
--

CREATE TABLE IF NOT EXISTS `scc_mail_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` text,
  `template_content` text NOT NULL,
  `template_auto_send` tinyint(4) DEFAULT NULL,
  `template_subject` text,
  `template_reader` varchar(45) DEFAULT NULL,
  `smtp_host` varchar(20) NOT NULL DEFAULT '67.228.209.12',
  `smtp_user` varchar(45) NOT NULL DEFAULT 'contact@macxdvd.com',
  `smtp_pass` varchar(45) NOT NULL DEFAULT 'cont333999',
  `smtp_from` varchar(45) NOT NULL DEFAULT 'contact@macxdvd.com',
  `smtp_fromName` varchar(45) NOT NULL DEFAULT 'contact@macxdvd.com',
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_media`
--

CREATE TABLE IF NOT EXISTS `scc_media` (
  `media_id` int(11) NOT NULL AUTO_INCREMENT,
  `media_title` char(32) CHARACTER SET utf8 DEFAULT NULL,
  `media_comment` text CHARACTER SET utf8,
  `media_type` int(11) NOT NULL DEFAULT '1' COMMENT '1=视频,2=原画,3=截图',
  `media_pic_small` text,
  `media_url` text CHARACTER SET utf8 NOT NULL,
  `media_posttime` int(11) NOT NULL DEFAULT '0',
  `media_index_show` tinyint(1) NOT NULL DEFAULT '0',
  `media_web_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`media_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_news`
--

CREATE TABLE IF NOT EXISTS `scc_news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `news_title` text NOT NULL,
  `news_channel_id` int(11) DEFAULT '1',
  `news_intro` text,
  `news_content` text,
  `news_posttime` int(11) DEFAULT NULL,
  `news_top_show` tinyint(1) DEFAULT '0',
  `news_tags` text,
  `news_keywords` text,
  `news_description` text,
  `news_display_title` text,
  `news_display_pic` text,
  `news_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=待审核,1=已审核',
  PRIMARY KEY (`news_id`),
  KEY `news_status` (`news_status`) USING BTREE,
  KEY `news_channel_id` (`news_channel_id`) USING BTREE,
  KEY `news_top_show` (`news_top_show`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `scc_news_view`
--
CREATE TABLE IF NOT EXISTS `scc_news_view` (
`channel_id` int(11)
,`channel_name` char(16)
,`channel_url` varchar(255)
,`channel_father_id` int(11)
,`channel_web_id` int(11)
,`news_id` int(11)
,`news_title` text
,`news_channel_id` int(11)
,`news_intro` text
,`news_content` text
,`news_posttime` int(11)
,`news_top_show` tinyint(1)
,`news_tags` text
,`news_keywords` text
,`news_description` text
,`news_display_title` text
,`news_status` tinyint(4)
,`news_display_pic` text
);
-- --------------------------------------------------------

--
-- 替换视图以便查看 `scc_notice_sender`
--
CREATE TABLE IF NOT EXISTS `scc_notice_sender` (
`notice_id` int(11)
,`notice_content` text
,`notice_endtime` int(11)
,`notice_posttime` int(11)
,`notice_visible` tinyint(1)
,`notice_sender_id` varchar(36)
,`notice_reciever_id` varchar(36)
,`GUID` varchar(36)
,`user_name` varchar(16)
,`user_pass` varchar(64)
,`user_permission` int(11)
,`user_founder` tinyint(1)
,`user_freezed` tinyint(4)
,`additional_permission` text
);
-- --------------------------------------------------------

--
-- 表的结构 `scc_partner`
--

CREATE TABLE IF NOT EXISTS `scc_partner` (
  `GUID` varchar(36) NOT NULL,
  `user_name` varchar(16) NOT NULL,
  `user_pass` varchar(64) NOT NULL,
  `user_freezed` tinyint(4) NOT NULL DEFAULT '0',
  `additional_permission` text,
  `partner_key` char(16) NOT NULL,
  PRIMARY KEY (`GUID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `scc_permission`
--

CREATE TABLE IF NOT EXISTS `scc_permission` (
  `permission_id` int(11) NOT NULL,
  `permission_name` varchar(24) NOT NULL,
  `permission_list` text NOT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `scc_private_notice`
--

CREATE TABLE IF NOT EXISTS `scc_private_notice` (
  `notice_id` int(11) NOT NULL AUTO_INCREMENT,
  `notice_content` text NOT NULL,
  `notice_endtime` int(11) NOT NULL DEFAULT '0',
  `notice_posttime` int(11) NOT NULL DEFAULT '0',
  `notice_visible` tinyint(1) NOT NULL DEFAULT '1',
  `notice_sender_id` varchar(36) NOT NULL,
  `notice_reciever_id` varchar(36) NOT NULL,
  PRIMARY KEY (`notice_id`),
  KEY `notice_posttime` (`notice_posttime`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_slide`
--

CREATE TABLE IF NOT EXISTS `scc_slide` (
  `slide_id` int(11) NOT NULL AUTO_INCREMENT,
  `slide_pic_path` text NOT NULL,
  `slide_pic_width` int(11) DEFAULT NULL,
  `slide_pic_height` int(11) DEFAULT NULL,
  `slide_link` text,
  `slide_web_id` int(11) NOT NULL,
  PRIMARY KEY (`slide_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_tags`
--

CREATE TABLE IF NOT EXISTS `scc_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(45) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- 表的结构 `scc_user`
--

CREATE TABLE IF NOT EXISTS `scc_user` (
  `GUID` varchar(36) NOT NULL,
  `user_name` varchar(16) NOT NULL,
  `user_pass` varchar(64) NOT NULL,
  `user_permission` int(11) NOT NULL DEFAULT '1',
  `user_founder` tinyint(1) NOT NULL DEFAULT '0',
  `user_freezed` tinyint(4) NOT NULL DEFAULT '0',
  `additional_permission` text,
  PRIMARY KEY (`GUID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `scc_user_permission`
--
CREATE TABLE IF NOT EXISTS `scc_user_permission` (
`GUID` varchar(36)
,`user_name` varchar(16)
,`user_pass` varchar(64)
,`user_permission` int(11)
,`user_founder` tinyint(1)
,`user_freezed` tinyint(4)
,`additional_permission` text
,`permission_id` int(11)
,`permission_name` varchar(24)
,`permission_list` text
);
-- --------------------------------------------------------

--
-- 表的结构 `scc_web`
--

CREATE TABLE IF NOT EXISTS `scc_web` (
  `web_id` int(11) NOT NULL AUTO_INCREMENT,
  `web_name` varchar(45) DEFAULT NULL,
  `web_url` varchar(255) DEFAULT NULL,
  `web_secretkey` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`web_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 视图结构 `scc_auto_template`
--
DROP TABLE IF EXISTS `scc_auto_template`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `scc_auto_template` AS select `scc_mail_template`.`template_id` AS `template_id`,`scc_mail_template`.`template_name` AS `template_name`,`scc_mail_template`.`template_content` AS `template_content`,`scc_mail_template`.`template_subject` AS `template_subject`,`scc_mail_template`.`template_reader` AS `template_reader`,`scc_mail_template`.`smtp_host` AS `smtp_host`,`scc_mail_template`.`smtp_user` AS `smtp_user`,`scc_mail_template`.`smtp_pass` AS `smtp_pass`,`scc_mail_template`.`smtp_from` AS `smtp_from`,`scc_mail_template`.`smtp_fromName` AS `smtp_fromName`,`scc_mail_autosend`.`auto_id` AS `auto_id`,`scc_mail_autosend`.`auto_template_id` AS `auto_template_id`,`scc_mail_autosend`.`auto_actived` AS `auto_actived`,`scc_mail_autosend`.`auto_name` AS `auto_name` from (`scc_mail_template` join `scc_mail_autosend`) where (`scc_mail_autosend`.`auto_template_id` = `scc_mail_template`.`template_id`);

-- --------------------------------------------------------

--
-- 视图结构 `scc_news_view`
--
DROP TABLE IF EXISTS `scc_news_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `scc_news_view` AS select `scc_channels`.`channel_id` AS `channel_id`,`scc_channels`.`channel_name` AS `channel_name`,`scc_channels`.`channel_url` AS `channel_url`,`scc_channels`.`channel_father_id` AS `channel_father_id`,`scc_channels`.`channel_web_id` AS `channel_web_id`,`scc_news`.`news_id` AS `news_id`,`scc_news`.`news_title` AS `news_title`,`scc_news`.`news_channel_id` AS `news_channel_id`,`scc_news`.`news_intro` AS `news_intro`,`scc_news`.`news_content` AS `news_content`,`scc_news`.`news_posttime` AS `news_posttime`,`scc_news`.`news_top_show` AS `news_top_show`,`scc_news`.`news_tags` AS `news_tags`,`scc_news`.`news_keywords` AS `news_keywords`,`scc_news`.`news_description` AS `news_description`,`scc_news`.`news_display_title` AS `news_display_title`,`scc_news`.`news_status` AS `news_status`,`scc_news`.`news_display_pic` AS `news_display_pic` from (`scc_channels` join `scc_news` on((`scc_channels`.`channel_id` = `scc_news`.`news_channel_id`)));

-- --------------------------------------------------------

--
-- 视图结构 `scc_notice_sender`
--
DROP TABLE IF EXISTS `scc_notice_sender`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `scc_notice_sender` AS select `a`.`notice_id` AS `notice_id`,`a`.`notice_content` AS `notice_content`,`a`.`notice_endtime` AS `notice_endtime`,`a`.`notice_posttime` AS `notice_posttime`,`a`.`notice_visible` AS `notice_visible`,`a`.`notice_sender_id` AS `notice_sender_id`,`a`.`notice_reciever_id` AS `notice_reciever_id`,`b`.`GUID` AS `GUID`,`b`.`user_name` AS `user_name`,`b`.`user_pass` AS `user_pass`,`b`.`user_permission` AS `user_permission`,`b`.`user_founder` AS `user_founder`,`b`.`user_freezed` AS `user_freezed`,`b`.`additional_permission` AS `additional_permission` from (`scc_private_notice` `a` join `scc_user` `b`) where (`a`.`notice_sender_id` = `b`.`GUID`);

-- --------------------------------------------------------

--
-- 视图结构 `scc_user_permission`
--
DROP TABLE IF EXISTS `scc_user_permission`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `scc_user_permission` AS select `a`.`GUID` AS `GUID`,`a`.`user_name` AS `user_name`,`a`.`user_pass` AS `user_pass`,`a`.`user_permission` AS `user_permission`,`a`.`user_founder` AS `user_founder`,`a`.`user_freezed` AS `user_freezed`,`a`.`additional_permission` AS `additional_permission`,`b`.`permission_id` AS `permission_id`,`b`.`permission_name` AS `permission_name`,`b`.`permission_list` AS `permission_list` from (`scc_user` `a` join `scc_permission` `b`) where (`a`.`user_permission` = `b`.`permission_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
