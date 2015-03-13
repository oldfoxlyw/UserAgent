-- phpMyAdmin SQL Dump
-- version 4.1.14.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-03-11 14:33:38
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
  `game_id` char(10) NOT NULL,
  PRIMARY KEY (`GUID`,`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `account_login_token`
--

DROP TABLE IF EXISTS `account_login_token`;
CREATE TABLE IF NOT EXISTS `account_login_token` (
  `guid` bigint(20) NOT NULL,
  `token` char(64) NOT NULL,
  `expire_time` int(11) NOT NULL,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `web_account`
--

DROP TABLE IF EXISTS `web_account`;
CREATE TABLE IF NOT EXISTS `web_account` (
  `GUID` bigint(20) NOT NULL AUTO_INCREMENT,
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
  `ad_id` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`GUID`),
  KEY `account_name` (`account_name`,`account_pass`,`server_id`),
  KEY `partner_id` (`partner_key`,`partner_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=200100191010001 ;

--
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
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_type` varchar(64) NOT NULL,
  `log_user` text,
  `log_relative_page_url` varchar(128) NOT NULL,
  `log_relative_parameter` text NOT NULL,
  `log_addition_parameter` text,
  `log_time` datetime NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
--
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
  `funds_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_guid` bigint(20) NOT NULL,
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
  `partner_key` char(16) NOT NULL DEFAULT 'default',
  `receipt_data` text NOT NULL,
  `appstore_status` int(11) NOT NULL,
  `appstore_device_id` char(64) NOT NULL,
  PRIMARY KEY (`funds_id`),
  KEY `account_guid` (`account_name`),
  KEY `account_id` (`account_id`),
  KEY `game_id` (`game_id`,`server_id`,`server_section`),
  KEY `funds_flow_dir` (`funds_flow_dir`),
  KEY `funds_time` (`funds_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `funds_order`
--

DROP TABLE IF EXISTS `funds_order`;
CREATE TABLE IF NOT EXISTS `funds_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` char(22) NOT NULL,
  `server_id` char(1) NOT NULL,
  `checksum` char(64) NOT NULL,
  `check_count` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `funds_id` int(11) NOT NULL,
  `posttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `checksum_2` (`checksum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
--
-- Database: `agent1_log_db`
--
DROP DATABASE `agent1_log_db`;
CREATE DATABASE IF NOT EXISTS `agent1_log_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent1_log_db`;

-- --------------------------------------------------------

--
-- 表的结构 `blue_crystal_logs`
--

DROP TABLE IF EXISTS `blue_crystal_logs`;
CREATE TABLE IF NOT EXISTS `blue_crystal_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `guid` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `action_name` char(32) NOT NULL,
  `current_blue_crystal` int(11) NOT NULL,
  `spend_blue_crystal` int(11) NOT NULL,
  `desc` char(64) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `equipment_logs`
--

DROP TABLE IF EXISTS `equipment_logs`;
CREATE TABLE IF NOT EXISTS `equipment_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `guid` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `action_name` char(32) NOT NULL,
  `equipment_id` bigint(20) NOT NULL,
  `current_info` text NOT NULL,
  `info` text NOT NULL,
  `desc` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `experience_logs`
--

DROP TABLE IF EXISTS `experience_logs`;
CREATE TABLE IF NOT EXISTS `experience_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `guid` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `action_name` char(32) NOT NULL,
  `target_type` char(32) NOT NULL,
  `target_id` bigint(20) NOT NULL,
  `total_experience` bigint(11) NOT NULL,
  `add_exp` int(11) NOT NULL,
  `desc` char(64) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `gold_logs`
--

DROP TABLE IF EXISTS `gold_logs`;
CREATE TABLE IF NOT EXISTS `gold_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `guid` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `action_name` char(32) NOT NULL,
  `current_gold` int(11) NOT NULL,
  `spend_gold` int(11) NOT NULL,
  `desc` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `guild_contribution_logs`
--

DROP TABLE IF EXISTS `guild_contribution_logs`;
CREATE TABLE IF NOT EXISTS `guild_contribution_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `guid` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `action_name` char(32) NOT NULL,
  `current_contribution` int(11) NOT NULL,
  `spend_contribution` int(11) NOT NULL,
  `desc` char(64) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `item_logs`
--

DROP TABLE IF EXISTS `item_logs`;
CREATE TABLE IF NOT EXISTS `item_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `guid` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `action_name` char(32) NOT NULL,
  `total_count` int(11) NOT NULL,
  `spend_count` int(11) NOT NULL,
  `item_const_id` char(32) NOT NULL,
  `desc` char(64) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_buy_equipment_detail`
--

DROP TABLE IF EXISTS `log_buy_equipment_detail`;
CREATE TABLE IF NOT EXISTS `log_buy_equipment_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `server_id` char(8) NOT NULL,
  `partner_key` char(16) NOT NULL,
  `level_detail` text NOT NULL,
  `mission_detail` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_daily_statistics`
--

DROP TABLE IF EXISTS `log_daily_statistics`;
CREATE TABLE IF NOT EXISTS `log_daily_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_date` date NOT NULL,
  `server_id` char(8) NOT NULL,
  `server_name` char(16) NOT NULL,
  `reg_account` int(11) NOT NULL DEFAULT '0',
  `reg_new_account` int(11) NOT NULL DEFAULT '0',
  `reg_new_account_valid` int(11) NOT NULL DEFAULT '0',
  `valid_account` int(11) NOT NULL DEFAULT '0' COMMENT '等级大于等于1级的帐号',
  `valid_new_account` int(11) NOT NULL DEFAULT '0',
  `level_account` int(11) NOT NULL DEFAULT '0',
  `modify_account` int(11) NOT NULL DEFAULT '0' COMMENT '持有注册帐号的用户',
  `modify_new_account` int(11) NOT NULL DEFAULT '0',
  `login_account` int(11) NOT NULL DEFAULT '0',
  `login_account_valid` int(11) NOT NULL DEFAULT '0',
  `old_login_account` int(11) NOT NULL DEFAULT '0' COMMENT '当天登录的老用户',
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
  `at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `log_date` (`log_date`,`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `account_mission` bigint(20) NOT NULL,
  PRIMARY KEY (`guid`,`server_id`,`partner_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `mission` text NOT NULL,
  PRIMARY KEY (`date`,`server_id`,`partner_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `level1` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`log_date`,`server_id`,`partner_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `retinue_logs`
--

DROP TABLE IF EXISTS `retinue_logs`;
CREATE TABLE IF NOT EXISTS `retinue_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `guid` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `action_name` char(32) NOT NULL,
  `retinue_id` bigint(20) NOT NULL,
  `current_info` text NOT NULL,
  `info` text NOT NULL,
  `desc` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `role_info_logs`
--

DROP TABLE IF EXISTS `role_info_logs`;
CREATE TABLE IF NOT EXISTS `role_info_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL,
  `guid` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `action_name` char(32) NOT NULL,
  `attrs_type` char(32) NOT NULL,
  `current_value` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `total_value` int(11) NOT NULL,
  `desc` char(64) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
--
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
  `type` int(11) NOT NULL,
  PRIMARY KEY (`equipment_name`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log_account`
--

DROP TABLE IF EXISTS `log_account`;
CREATE TABLE IF NOT EXISTS `log_account` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_GUID` char(36) NOT NULL,
  `device_id` char(64) NOT NULL,
  `log_account_name` char(64) DEFAULT NULL,
  `log_account_level` int(11) NOT NULL DEFAULT '0',
  `log_action` char(64) NOT NULL,
  `log_parameter` text,
  `log_time` int(11) NOT NULL,
  `log_ip` char(24) NOT NULL,
  `server_id` char(5) NOT NULL,
  `partner_key` char(16) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`log_id`),
  KEY `log_GUID` (`log_GUID`) USING BTREE,
  KEY `log_account_name` (`log_account_name`) USING BTREE,
  KEY `log_action` (`log_action`) USING BTREE,
  KEY `game_id` (`server_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `log_account`
--

INSERT INTO `log_account` (`log_id`, `log_GUID`, `device_id`, `log_account_name`, `log_account_level`, `log_action`, `log_parameter`, `log_time`, `log_ip`, `server_id`, `partner_key`) VALUES
(1, '200100191009986', '', 'Gf91b4cc13b0', 0, 'ACCOUNT_LOGIN_SUCCESS', '{"account_name":"Gf91b4cc13b0","account_pass":"0656d6d6","server_id":"10","code":""}', 1426055410, '182.150.22.242', '10', 'default');

-- --------------------------------------------------------

--
-- 表的结构 `log_action_mall`
--

DROP TABLE IF EXISTS `log_action_mall`;
CREATE TABLE IF NOT EXISTS `log_action_mall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `nickname` char(16) NOT NULL,
  `content` text NOT NULL,
  `posttime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_api`
--

DROP TABLE IF EXISTS `log_api`;
CREATE TABLE IF NOT EXISTS `log_api` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_GUID` char(36) NOT NULL,
  `log_account_name` char(64) DEFAULT NULL,
  `log_action` char(64) NOT NULL,
  `log_parameter` text,
  `log_time` int(11) NOT NULL,
  `log_ip` char(24) NOT NULL,
  `server_id` char(5) NOT NULL,
  `partner_key` char(16) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`log_id`),
  KEY `log_GUID` (`log_GUID`) USING BTREE,
  KEY `log_account_name` (`log_account_name`) USING BTREE,
  KEY `log_action` (`log_action`) USING BTREE,
  KEY `game_id` (`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_consume`
--

DROP TABLE IF EXISTS `log_consume`;
CREATE TABLE IF NOT EXISTS `log_consume` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `item_value` int(11) NOT NULL DEFAULT '0' COMMENT '装',
  `item_job` char(16) NOT NULL DEFAULT '' COMMENT '装备',
  `log_time` int(11) NOT NULL,
  `server_id` char(5) NOT NULL,
  `partner_key` char(16) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`log_id`),
  KEY `player_id` (`player_id`),
  KEY `item_name` (`item_name`),
  KEY `server_id` (`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `log_rep`
--

DROP TABLE IF EXISTS `log_rep`;
CREATE TABLE IF NOT EXISTS `log_rep` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_id` char(5) NOT NULL,
  `player_id` bigint(20) NOT NULL,
  `type` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `profession` char(16) NOT NULL,
  `nickname` char(20) NOT NULL,
  `posttime` int(11) NOT NULL,
  `partner_key` char(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
--
-- Database: `agent1_product_db`
--
DROP DATABASE `agent1_product_db`;
CREATE DATABASE IF NOT EXISTS `agent1_product_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agent1_product_db`;

-- --------------------------------------------------------

--
-- 表的结构 `game_announcement`
--

DROP TABLE IF EXISTS `game_announcement`;
CREATE TABLE IF NOT EXISTS `game_announcement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `post_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `game_announcement_crontab`
--

DROP TABLE IF EXISTS `game_announcement_crontab`;
CREATE TABLE IF NOT EXISTS `game_announcement_crontab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` char(5) NOT NULL,
  `content` text NOT NULL,
  `every` int(11) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `minutes` char(64) NOT NULL,
  `hour` char(64) NOT NULL,
  `date` char(64) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `game_autosend_message`
--

DROP TABLE IF EXISTS `game_autosend_message`;
CREATE TABLE IF NOT EXISTS `game_autosend_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `is_auto_send` tinyint(4) NOT NULL DEFAULT '1',
  `pattern` char(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `game_code`
--

DROP TABLE IF EXISTS `game_code`;
CREATE TABLE IF NOT EXISTS `game_code` (
  `code` char(20) NOT NULL,
  `comment` char(16) NOT NULL DEFAULT '',
  `disabled` tinyint(4) NOT NULL DEFAULT '0',
  `server_id` char(8) NOT NULL DEFAULT '',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `server_list`
--

DROP TABLE IF EXISTS `server_list`;
CREATE TABLE IF NOT EXISTS `server_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `legion_message_ip` text NOT NULL,
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
  `need_activate` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_id` (`game_id`,`account_server_id`,`server_name`,`special_ip`),
  KEY `server_recommend` (`server_recommend`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `server_list`
--

INSERT INTO `server_list` (`id`, `game_id`, `section_id`, `account_server_id`, `server_name`, `server_ip`, `server_game_ip`, `game_message_ip`, `const_server_ip`, `voice_server_ip`, `cross_server_ip`, `legion_message_ip`, `server_max_player`, `account_count`, `server_language`, `server_sort`, `server_recommend`, `server_debug`, `partner`, `version`, `server_status`, `server_new`, `special_ip`, `server_starttime`, `need_activate`) VALUES
(1, 'B', 11, '11', '外网测试', '[{"ip":"182.254.208.170","lan":"10.131.134.53","port":"7091","lanport":"7091"}]', '[{"ip":"182.254.208.170","port":"7665"},{"ip":"182.254.208.170","port":"7666"},{"ip":"182.254.208.170","port":"7667"},{"ip":"182.254.208.170","port":"7668"}]', '[{"ip":"127.0.0.1","port":"2100"}]', '[{"ip":"182.254.208.170","port":"7091"}]', '[{"ip":"182.254.208.170","port":"7091"}]', '[{"ip":"182.254.208.170","port":"7091"}]', '[{"ip":"127.0.0.1","port":"2101"}]', 100000, 0, 'CN', 4, 0, 0, 'default,public_default', '', 1, 1, '', 0, 1),
(2, 'B', 10, '10', 'DK2内网测试服(G1)', '[{"ip":"192.168.1.230","lan":"192.168.1.230","port":"6091","lanport":"6089"}]', '[{"ip":"192.168.1.65","port":"6660"}]', '[{"ip":"192.168.1.230","port":"9898"}]', '[{"ip":"192.168.1.230","port":"6091"}]', '[{"ip":"192.168.1.230","port":"6091"}]', '[{"ip":"192.168.1.230","port":"6091"}]', '[{"ip":"192.168.1.230","port":"1101"}]', 100000, 0, 'CN', 5, 1, 0, 'default', '', 1, 1, '', 0, 1);

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
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(45) NOT NULL,
  `config_close_scc` tinyint(1) NOT NULL DEFAULT '0',
  `config_close_reason` text NOT NULL,
  `config_selected` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `scc_config` (`config_id`, `config_name`, `config_close_scc`, `config_close_reason`, `config_selected`) VALUES
(1, 'default', 0, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `scc_mail_autosend`
--

DROP TABLE IF EXISTS `scc_mail_autosend`;
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

DROP TABLE IF EXISTS `scc_mail_template`;
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
-- 表的结构 `scc_partner`
--

DROP TABLE IF EXISTS `scc_partner`;
CREATE TABLE IF NOT EXISTS `scc_partner` (
  `partner_key` char(16) NOT NULL,
  PRIMARY KEY (`partner_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `scc_partner`
--

INSERT INTO `scc_partner` (`partner_key`) VALUES
('default');

-- --------------------------------------------------------

--
-- 表的结构 `scc_permission`
--

DROP TABLE IF EXISTS `scc_permission`;
CREATE TABLE IF NOT EXISTS `scc_permission` (
  `permission_id` int(11) NOT NULL,
  `permission_name` varchar(24) NOT NULL,
  `permission_list` text NOT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `scc_permission`
--

INSERT INTO `scc_permission` (`permission_id`, `permission_name`, `permission_list`) VALUES
(999, '超级管理员', 'All');

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
  `user_fromwhere` char(16) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`GUID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `scc_user`
--

INSERT INTO `scc_user` (`GUID`, `user_name`, `user_pass`, `user_permission`, `permission_name`, `user_founder`, `user_freezed`, `additional_permission`, `user_fromwhere`) VALUES
('D2EF3D9D-2022-B1B1-C211-88CAEDFAAB8E', 'admin', 'b40714d351a35e8f0d2f15ee977da4a9f5a7e2cd', 999, '超级管理员', 1, 0, '', 'default');

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
-- Database: `gm_system_db`
--
DROP DATABASE `gm_system_db`;
CREATE DATABASE IF NOT EXISTS `gm_system_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `gm_system_db`;

-- --------------------------------------------------------

--
-- 表的结构 `report_permission`
--

DROP TABLE IF EXISTS `report_permission`;
CREATE TABLE IF NOT EXISTS `report_permission` (
  `permission_level` int(11) NOT NULL,
  `permission_name` char(16) NOT NULL,
  `permission_list` text NOT NULL,
  PRIMARY KEY (`permission_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `report_permission`
--

INSERT INTO `report_permission` (`permission_level`, `permission_name`, `permission_list`) VALUES
(999, '超级管理员', 'All');

-- --------------------------------------------------------

--
-- 表的结构 `report_user`
--

DROP TABLE IF EXISTS `report_user`;
CREATE TABLE IF NOT EXISTS `report_user` (
  `guid` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_name` char(32) NOT NULL,
  `user_pass` char(64) NOT NULL,
  `user_founder` tinyint(4) NOT NULL DEFAULT '0',
  `user_lastlogin` int(11) NOT NULL DEFAULT '0',
  `permission_level` int(11) NOT NULL,
  `permission_name` char(16) NOT NULL,
  `user_fromwhere` char(16) NOT NULL,
  `user_status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30016078102 ;

--
-- 转存表中的数据 `report_user`
--

INSERT INTO `report_user` (`guid`, `user_name`, `user_pass`, `user_founder`, `user_lastlogin`, `permission_level`, `permission_name`, `user_fromwhere`, `user_status`) VALUES
(30016078101, 'admin', 'b40714d351a35e8f0d2f15ee977da4a9f5a7e2cd', 1, 0, 999, '超级管理员', 'default', 1);

-- --------------------------------------------------------

--
-- 表的结构 `system_log`
--

DROP TABLE IF EXISTS `system_log`;
CREATE TABLE IF NOT EXISTS `system_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_action` varchar(64) NOT NULL,
  `log_uri` varchar(128) NOT NULL,
  `log_parameter` text NOT NULL,
  `log_time` int(11) NOT NULL,
  `log_guid` bigint(20) NOT NULL,
  `log_name` char(16) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `system_permission`
--

DROP TABLE IF EXISTS `system_permission`;
CREATE TABLE IF NOT EXISTS `system_permission` (
  `permission_level` int(11) NOT NULL,
  `permission_name` char(16) NOT NULL,
  `permission_list` text NOT NULL,
  PRIMARY KEY (`permission_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `system_permission`
--

INSERT INTO `system_permission` (`permission_level`, `permission_name`, `permission_list`) VALUES
(999, '超级管理员', 'All');

-- --------------------------------------------------------

--
-- 表的结构 `system_user`
--

DROP TABLE IF EXISTS `system_user`;
CREATE TABLE IF NOT EXISTS `system_user` (
  `guid` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_name` char(32) NOT NULL,
  `user_pass` char(64) NOT NULL,
  `user_founder` tinyint(4) NOT NULL DEFAULT '0',
  `user_lastlogin` int(11) NOT NULL DEFAULT '0',
  `permission_level` int(11) NOT NULL,
  `permission_name` char(16) NOT NULL,
  `user_fromwhere` char(16) NOT NULL,
  `user_status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30016078102 ;

--
-- 转存表中的数据 `system_user`
--

INSERT INTO `system_user` (`guid`, `user_name`, `user_pass`, `user_founder`, `user_lastlogin`, `permission_level`, `permission_name`, `user_fromwhere`, `user_status`) VALUES
(30016078101, 'admin', 'b40714d351a35e8f0d2f15ee977da4a9f5a7e2cd', 1, 0, 999, '超级管理员', 'default', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
