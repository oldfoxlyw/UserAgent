SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `agent1_account_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_account_db` DEFAULT CHARACTER SET utf8 ;
DROP SCHEMA IF EXISTS `agent1_adminlog_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_adminlog_db` DEFAULT CHARACTER SET utf8 ;
DROP SCHEMA IF EXISTS `agent1_funds_flow_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_funds_flow_db` DEFAULT CHARACTER SET utf8 ;
DROP SCHEMA IF EXISTS `agent1_log_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_log_db` DEFAULT CHARACTER SET utf8 ;
DROP SCHEMA IF EXISTS `agent1_log_db_201203` ;
CREATE SCHEMA IF NOT EXISTS `agent1_log_db_201203` DEFAULT CHARACTER SET utf8 ;
DROP SCHEMA IF EXISTS `agent1_product_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_product_db` DEFAULT CHARACTER SET utf8 ;
DROP SCHEMA IF EXISTS `agent1_web_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_web_db` DEFAULT CHARACTER SET utf8 ;
USE `agent1_account_db` ;

-- -----------------------------------------------------
-- Table `agent1_account_db`.`account_added_game`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_account_db`.`account_added_game` ;

CREATE  TABLE IF NOT EXISTS `agent1_account_db`.`account_added_game` (
  `GUID` CHAR(36) NOT NULL ,
  `game_id` CHAR(10) NOT NULL ,
  PRIMARY KEY (`GUID`, `game_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_account_db`.`closure_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_account_db`.`closure_account` ;

CREATE  TABLE IF NOT EXISTS `agent1_account_db`.`closure_account` (
  `GUID` CHAR(36) NOT NULL ,
  `account_closure_reason` TEXT NULL DEFAULT NULL ,
  `account_closure_starttime` INT(11) NOT NULL ,
  `account_closure_endtime` INT(11) NOT NULL ,
  PRIMARY KEY (`GUID`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_account_db`.`master_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_account_db`.`master_account` ;

CREATE  TABLE IF NOT EXISTS `agent1_account_db`.`master_account` (
  `master_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `master_name` CHAR(16) NOT NULL ,
  `master_generationtime` INT(11) NOT NULL ,
  PRIMARY KEY (`master_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_account_db`.`web_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_account_db`.`web_account` ;

CREATE  TABLE IF NOT EXISTS `agent1_account_db`.`web_account` (
  `GUID` BIGINT(20) NOT NULL AUTO_INCREMENT ,
  `account_name` CHAR(64) NOT NULL ,
  `account_pass` CHAR(32) NOT NULL ,
  `server_id` CHAR(6) NOT NULL ,
  `account_email` CHAR(64) NOT NULL ,
  `account_nickname` CHAR(16) NULL DEFAULT NULL ,
  `account_pass_question` CHAR(128) NULL DEFAULT NULL ,
  `account_pass_answer` CHAR(128) NULL DEFAULT NULL ,
  `account_point` INT(11) NOT NULL DEFAULT '0' ,
  `account_regtime` INT(11) NOT NULL DEFAULT '0' ,
  `account_lastlogin` INT(11) NOT NULL DEFAULT '0' ,
  `account_currentlogin` INT(11) NOT NULL DEFAULT '0' ,
  `account_lastip` CHAR(16) NULL DEFAULT NULL ,
  `account_currentip` CHAR(16) NULL DEFAULT NULL ,
  `account_status` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1=正常 0=试玩 -1=封停' ,
  `account_activity` INT(11) NOT NULL DEFAULT '0' ,
  `account_job` CHAR(16) NULL ,
  `account_level` INT NOT NULL DEFAULT 0 ,
  `account_mission` BIGINT NOT NULL DEFAULT 0 ,
  `partner_key` CHAR(8) NOT NULL ,
  PRIMARY KEY (`GUID`) ,
  INDEX `account_name` (`account_name` ASC, `account_pass` ASC, `server_id` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 200110091006909
DEFAULT CHARACTER SET = utf8;

USE `agent1_adminlog_db` ;

-- -----------------------------------------------------
-- Table `agent1_adminlog_db`.`log_scc`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_adminlog_db`.`log_scc` ;

CREATE  TABLE IF NOT EXISTS `agent1_adminlog_db`.`log_scc` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `log_type` VARCHAR(64) NOT NULL ,
  `log_user` TEXT NULL DEFAULT NULL ,
  `log_relative_page_url` VARCHAR(128) NOT NULL ,
  `log_relative_parameter` TEXT NOT NULL ,
  `log_addition_parameter` TEXT NULL DEFAULT NULL ,
  `log_relative_method` VARCHAR(12) NOT NULL ,
  `log_time` DATETIME NOT NULL ,
  PRIMARY KEY (`log_id`) )
ENGINE = MyISAM
AUTO_INCREMENT = 14
DEFAULT CHARACTER SET = utf8;

USE `agent1_funds_flow_db` ;

-- -----------------------------------------------------
-- Table `agent1_funds_flow_db`.`funds_checkinout`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_funds_flow_db`.`funds_checkinout` ;

CREATE  TABLE IF NOT EXISTS `agent1_funds_flow_db`.`funds_checkinout` (
  `funds_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `account_guid` BIGINT(20) NOT NULL ,
  `account_name` CHAR(64) NOT NULL ,
  `account_nickname` CHAR(32) NOT NULL ,
  `account_id` CHAR(16) NOT NULL ,
  `game_id` CHAR(5) NOT NULL ,
  `server_id` CHAR(5) NOT NULL ,
  `server_section` CHAR(5) NOT NULL ,
  `funds_flow_dir` ENUM('CHECK_IN','CHECK_OUT') NOT NULL ,
  `funds_amount` INT(11) NOT NULL ,
  `funds_item_amount` INT(11) NOT NULL ,
  `funds_item_current` INT(11) NOT NULL ,
  `funds_time` INT(11) NOT NULL ,
  `funds_time_local` DATETIME NOT NULL ,
  `funds_type` INT(11) NOT NULL DEFAULT '1' COMMENT '1=游戏内充值 0=GM手动调整' ,
  PRIMARY KEY (`funds_id`) ,
  INDEX `account_guid` (`account_name` ASC) ,
  INDEX `account_id` (`account_id` ASC) ,
  INDEX `game_id` (`game_id` ASC, `server_id` ASC, `server_section` ASC) ,
  INDEX `funds_flow_dir` (`funds_flow_dir` ASC) ,
  INDEX `funds_time` (`funds_time` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 188
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_funds_flow_db`.`funds_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_funds_flow_db`.`funds_order` ;

CREATE  TABLE IF NOT EXISTS `agent1_funds_flow_db`.`funds_order` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `player_id` CHAR(22) NOT NULL ,
  `game_id` CHAR(1) NOT NULL ,
  `section_id` CHAR(1) NOT NULL ,
  `server_id` CHAR(1) NOT NULL ,
  `checksum` CHAR(64) NOT NULL ,
  `check_count` INT(11) NOT NULL ,
  `posttime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `checksum_2` (`checksum` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 188
DEFAULT CHARACTER SET = utf8;

USE `agent1_log_db` ;

-- -----------------------------------------------------
-- Table `agent1_log_db`.`log_daily_statistics`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db`.`log_daily_statistics` ;

CREATE  TABLE IF NOT EXISTS `agent1_log_db`.`log_daily_statistics` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `log_date` DATE NOT NULL ,
  `server_id` CHAR(8) NOT NULL ,
  `server_name` CHAR(16) NOT NULL ,
  `reg_account` INT(11) NOT NULL ,
  `reg_new_account` INT(11) NOT NULL ,
  `modify_account` INT(11) NOT NULL ,
  `login_account` INT(11) NOT NULL ,
  `active_account` INT(11) NOT NULL DEFAULT '0' COMMENT '活跃用户，三天内登陆过游戏的人数' ,
  `flowover_account` INT(11) NOT NULL DEFAULT '0' COMMENT '流失用户，超过一周没有登录游戏的人数' ,
  `reflow_account` INT NOT NULL DEFAULT 0 ,
  `orders_current_sum` INT(11) NOT NULL COMMENT '当天订单总额' ,
  `orders_num` INT(11) NOT NULL ,
  `orders_sum` INT(11) NOT NULL ,
  `arpu` INT(11) NOT NULL ,
  `recharge_account` INT(11) NOT NULL COMMENT '当天充值人数' ,
  `order_count` INT(11) NOT NULL COMMENT '订单数' ,
  `second_survive` INT(11) NOT NULL COMMENT '次日留存' ,
  `partner_key` CHAR(16) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `log_date` (`log_date` ASC, `server_id` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 101
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_log_db`.`log_flowover_cache`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db`.`log_flowover_cache` ;

CREATE  TABLE IF NOT EXISTS `agent1_log_db`.`log_flowover_cache` (
  `guid` BIGINT NOT NULL ,
  `server_id` CHAR(8) NOT NULL ,
  PRIMARY KEY (`guid`, `server_id`) )
ENGINE = InnoDB;

USE `agent1_log_db_201203` ;

-- -----------------------------------------------------
-- Table `agent1_log_db_201203`.`log_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db_201203`.`log_account` ;

CREATE  TABLE IF NOT EXISTS `agent1_log_db_201203`.`log_account` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `log_GUID` CHAR(36) NOT NULL ,
  `log_account_name` CHAR(64) NULL DEFAULT NULL ,
  `log_action` CHAR(64) NOT NULL ,
  `log_parameter` TEXT NULL DEFAULT NULL ,
  `log_time` INT(11) NOT NULL ,
  `log_ip` CHAR(24) NOT NULL ,
  `server_id` CHAR(5) NOT NULL ,
  PRIMARY KEY (`log_id`) ,
  INDEX `log_GUID` USING BTREE (`log_GUID` ASC) ,
  INDEX `log_account_name` USING BTREE (`log_account_name` ASC) ,
  INDEX `log_action` USING BTREE (`log_action` ASC) ,
  INDEX `game_id` (`server_id` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 2068
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_log_db_201203`.`log_mall`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db_201203`.`log_mall` ;

CREATE  TABLE IF NOT EXISTS `agent1_log_db_201203`.`log_mall` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `log_account_id` CHAR(16) NOT NULL ,
  `log_account_name` CHAR(64) NOT NULL ,
  `log_account_nickname` CHAR(64) NOT NULL ,
  `log_type` ENUM('building_upgrade','skill_upgrade','construct_tower','exchange_resource','accelerate','output','mall_props','vehicles_strengthen','vehicles_repair','vehicles_resurrection','legion_battle','shop_buy') NOT NULL ,
  `log_spend_item_id` CHAR(32) NOT NULL ,
  `log_spend_item_name` CHAR(16) NOT NULL ,
  `log_spend_item_count` INT(11) NOT NULL ,
  `log_get_item_id` CHAR(32) NOT NULL ,
  `log_get_item_name` CHAR(16) NOT NULL ,
  `log_get_item_count` INT(11) NOT NULL ,
  `log_time` INT(11) NOT NULL ,
  `log_local_time` DATETIME NOT NULL ,
  `game_id` CHAR(5) NOT NULL ,
  `server_section` CHAR(5) NOT NULL ,
  `server_id` CHAR(5) NOT NULL ,
  PRIMARY KEY (`log_id`) ,
  INDEX `log_item_id` (`log_spend_item_id` ASC) ,
  INDEX `log_time` (`log_time` ASC) ,
  INDEX `log_account_id` (`log_account_id` ASC) ,
  INDEX `game_id` (`game_id` ASC, `server_section` ASC, `server_id` ASC) ,
  INDEX `log_get_item_id` (`log_get_item_id` ASC) ,
  INDEX `log_type` (`log_type` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_log_db_201203`.`log_payment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db_201203`.`log_payment` ;

CREATE  TABLE IF NOT EXISTS `agent1_log_db_201203`.`log_payment` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `log_GUID` BIGINT(20) NOT NULL ,
  `log_action` CHAR(64) NOT NULL ,
  `log_uri` CHAR(128) NOT NULL ,
  `log_method` CHAR(10) NULL DEFAULT NULL ,
  `log_parameter` TEXT NULL DEFAULT NULL ,
  `log_time` INT(11) NOT NULL DEFAULT '0' ,
  `log_time_local` DATETIME NOT NULL ,
  `log_ip` CHAR(24) NOT NULL ,
  `game_id` CHAR(5) NOT NULL ,
  `section_id` CHAR(5) NOT NULL ,
  `server_id` CHAR(5) NOT NULL ,
  `platform` ENUM('iphone','ipad','web') NOT NULL DEFAULT 'iphone' ,
  PRIMARY KEY (`log_id`) ,
  INDEX `log_GUID` USING BTREE (`log_GUID` ASC) ,
  INDEX `log_time` USING BTREE (`log_time` ASC) ,
  INDEX `log_action` USING BTREE (`log_action` ASC) ,
  INDEX `game_id` (`game_id` ASC, `section_id` ASC, `server_id` ASC) ,
  INDEX `platform` (`platform` ASC) ,
  INDEX `log_ip` (`log_ip` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

USE `agent1_product_db` ;

-- -----------------------------------------------------
-- Table `agent1_product_db`.`game_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`game_product` ;

CREATE  TABLE IF NOT EXISTS `agent1_product_db`.`game_product` (
  `game_id` CHAR(5) NOT NULL ,
  `game_name` CHAR(64) NOT NULL ,
  `game_version` CHAR(16) NOT NULL ,
  `game_platform` ENUM('web','ios','android') NULL DEFAULT 'ios' ,
  `auth_key` CHAR(128) NOT NULL ,
  `game_pic_small` TEXT NULL DEFAULT NULL ,
  `game_pic_middium` TEXT NULL DEFAULT NULL ,
  `game_pic_big` TEXT NULL DEFAULT NULL ,
  `game_download_iphone` TEXT NULL DEFAULT NULL ,
  `game_download_ipad` TEXT NULL DEFAULT NULL ,
  `game_status` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0=正式,1=内测,2=公测' ,
  PRIMARY KEY (`game_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_product_db`.`section_list`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`section_list` ;

CREATE  TABLE IF NOT EXISTS `agent1_product_db`.`section_list` (
  `game_id` CHAR(5) NOT NULL ,
  `server_section_id` CHAR(5) NOT NULL ,
  `section_name` CHAR(32) NOT NULL ,
  PRIMARY KEY (`server_section_id`, `game_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_product_db`.`server_list`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`server_list` ;

CREATE  TABLE IF NOT EXISTS `agent1_product_db`.`server_list` (
  `game_id` CHAR(5) NOT NULL ,
  `account_server_section` CHAR(5) NOT NULL ,
  `account_server_id` CHAR(5) NOT NULL ,
  `server_name` CHAR(32) NOT NULL ,
  `server_ip` CHAR(32) NOT NULL ,
  `server_port` INT(11) NOT NULL ,
  `server_game_ip` CHAR(32) NOT NULL ,
  `server_game_port` INT(11) NOT NULL ,
  `server_message_ip` CHAR(32) NOT NULL ,
  `server_message_port` INT(11) NOT NULL ,
  `team_server` CHAR(32) NOT NULL ,
  `team_server_port` INT(11) NOT NULL ,
  `server_max_player` INT(11) NOT NULL DEFAULT '0' ,
  `account_count` INT(11) NOT NULL DEFAULT '0' ,
  `server_language` CHAR(16) NULL DEFAULT NULL ,
  `server_sort` INT(11) NOT NULL DEFAULT '0' ,
  `server_recommend` TINYINT(1) NOT NULL DEFAULT '0' ,
  `server_mode` ENUM('debug','normal','partner') NOT NULL DEFAULT 'normal' ,
  `partner` CHAR(16) NOT NULL ,
  `server_status` INT(11) NOT NULL DEFAULT '1' COMMENT '0=关闭；1=正常；2=繁忙；3=拥挤' ,
  PRIMARY KEY (`game_id`, `account_server_section`, `account_server_id`) ,
  INDEX `server_recommend` USING BTREE (`server_recommend` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_product_db`.`server_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`server_status` ;

CREATE  TABLE IF NOT EXISTS `agent1_product_db`.`server_status` (
  `server_status` INT(11) NOT NULL ,
  `message` TEXT NOT NULL ,
  `redirectUrl` TEXT NOT NULL )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_product_db`.`game_announcement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`game_announcement` ;

CREATE  TABLE IF NOT EXISTS `agent1_product_db`.`game_announcement` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `content` TEXT NOT NULL ,
  `post_time` INT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

USE `agent1_web_db` ;

-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_config`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_config` ;

CREATE  TABLE IF NOT EXISTS `agent1_web_db`.`scc_config` (
  `config_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `config_name` VARCHAR(45) NOT NULL ,
  `config_close_scc` TINYINT(1) NOT NULL DEFAULT '0' ,
  `config_close_reason` TEXT NOT NULL ,
  `config_selected` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`config_id`) )
ENGINE = MyISAM
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_mail_autosend`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_mail_autosend` ;

CREATE  TABLE IF NOT EXISTS `agent1_web_db`.`scc_mail_autosend` (
  `auto_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `auto_template_id` INT(11) NOT NULL ,
  `auto_actived` TINYINT(1) NOT NULL DEFAULT '1' ,
  `auto_name` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`auto_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_mail_template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_mail_template` ;

CREATE  TABLE IF NOT EXISTS `agent1_web_db`.`scc_mail_template` (
  `template_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `template_name` TEXT NULL DEFAULT NULL ,
  `template_content` TEXT NOT NULL ,
  `template_auto_send` TINYINT(4) NULL DEFAULT NULL ,
  `template_subject` TEXT NULL DEFAULT NULL ,
  `template_reader` VARCHAR(45) NULL DEFAULT NULL ,
  `smtp_host` VARCHAR(20) NOT NULL DEFAULT '67.228.209.12' ,
  `smtp_user` VARCHAR(45) NOT NULL DEFAULT 'contact@macxdvd.com' ,
  `smtp_pass` VARCHAR(45) NOT NULL DEFAULT 'cont333999' ,
  `smtp_from` VARCHAR(45) NOT NULL DEFAULT 'contact@macxdvd.com' ,
  `smtp_fromName` VARCHAR(45) NOT NULL DEFAULT 'contact@macxdvd.com' ,
  PRIMARY KEY (`template_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_permission` ;

CREATE  TABLE IF NOT EXISTS `agent1_web_db`.`scc_permission` (
  `permission_id` INT(11) NOT NULL ,
  `permission_name` VARCHAR(24) NOT NULL ,
  `permission_list` TEXT NOT NULL ,
  PRIMARY KEY (`permission_id`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_user` ;

CREATE  TABLE IF NOT EXISTS `agent1_web_db`.`scc_user` (
  `GUID` VARCHAR(36) NOT NULL ,
  `user_name` VARCHAR(16) NOT NULL ,
  `user_pass` VARCHAR(64) NOT NULL ,
  `user_permission` INT(11) NOT NULL DEFAULT '1' ,
  `permission_name` CHAR(16) NOT NULL ,
  `user_founder` TINYINT(1) NOT NULL DEFAULT '0' ,
  `user_freezed` TINYINT(4) NOT NULL DEFAULT '0' ,
  `additional_permission` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`GUID`) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

USE `agent1_account_db` ;
USE `agent1_adminlog_db` ;
USE `agent1_funds_flow_db` ;
USE `agent1_log_db` ;
USE `agent1_log_db_201203` ;
USE `agent1_product_db` ;

-- -----------------------------------------------------
-- Placeholder table for view `agent1_product_db`.`server_list_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `agent1_product_db`.`server_list_view` (`game_id` INT, `account_server_section` INT, `account_server_id` INT, `server_name` INT, `server_ip` INT, `server_port` INT, `server_message_ip` INT, `server_message_port` INT, `server_max_player` INT, `account_count` INT, `server_language` INT, `server_recommend` INT, `section_name` INT, `game_name` INT, `game_version` INT, `game_platform` INT, `auth_key` INT, `game_pic_small` INT, `game_pic_middium` INT, `game_pic_big` INT, `game_download_iphone` INT, `game_download_ipad` INT, `game_status` INT, `server_mode` INT);

-- -----------------------------------------------------
-- View `agent1_product_db`.`server_list_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `agent1_product_db`.`server_list_view` ;
DROP TABLE IF EXISTS `agent1_product_db`.`server_list_view`;
USE `agent1_product_db`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `agent1_product_db`.`server_list_view` AS select `agent1_product_db`.`server_list`.`game_id` AS `game_id`,`agent1_product_db`.`server_list`.`account_server_section` AS `account_server_section`,`agent1_product_db`.`server_list`.`account_server_id` AS `account_server_id`,`agent1_product_db`.`server_list`.`server_name` AS `server_name`,`agent1_product_db`.`server_list`.`server_ip` AS `server_ip`,`agent1_product_db`.`server_list`.`server_port` AS `server_port`,`agent1_product_db`.`server_list`.`server_message_ip` AS `server_message_ip`,`agent1_product_db`.`server_list`.`server_message_port` AS `server_message_port`,`agent1_product_db`.`server_list`.`server_max_player` AS `server_max_player`,`agent1_product_db`.`server_list`.`account_count` AS `account_count`,`agent1_product_db`.`server_list`.`server_language` AS `server_language`,`agent1_product_db`.`server_list`.`server_recommend` AS `server_recommend`,`agent1_product_db`.`section_list`.`section_name` AS `section_name`,`agent1_product_db`.`game_product`.`game_name` AS `game_name`,`agent1_product_db`.`game_product`.`game_version` AS `game_version`,`agent1_product_db`.`game_product`.`game_platform` AS `game_platform`,`agent1_product_db`.`game_product`.`auth_key` AS `auth_key`,`agent1_product_db`.`game_product`.`game_pic_small` AS `game_pic_small`,`agent1_product_db`.`game_product`.`game_pic_middium` AS `game_pic_middium`,`agent1_product_db`.`game_product`.`game_pic_big` AS `game_pic_big`,`agent1_product_db`.`game_product`.`game_download_iphone` AS `game_download_iphone`,`agent1_product_db`.`game_product`.`game_download_ipad` AS `game_download_ipad`,`agent1_product_db`.`game_product`.`game_status` AS `game_status`,`agent1_product_db`.`server_list`.`server_mode` AS `server_mode` from ((`agent1_product_db`.`server_list` join `agent1_product_db`.`section_list` on((`agent1_product_db`.`server_list`.`account_server_section` = `agent1_product_db`.`section_list`.`server_section_id`))) join `agent1_product_db`.`game_product` on((`agent1_product_db`.`server_list`.`game_id` = `agent1_product_db`.`game_product`.`game_id`)));
USE `agent1_web_db` ;

-- -----------------------------------------------------
-- Placeholder table for view `agent1_web_db`.`scc_auto_template`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `agent1_web_db`.`scc_auto_template` (`template_id` INT, `template_name` INT, `template_content` INT, `template_subject` INT, `template_reader` INT, `smtp_host` INT, `smtp_user` INT, `smtp_pass` INT, `smtp_from` INT, `smtp_fromName` INT, `auto_id` INT, `auto_template_id` INT, `auto_actived` INT, `auto_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `agent1_web_db`.`scc_user_permission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `agent1_web_db`.`scc_user_permission` (`GUID` INT, `user_name` INT, `user_pass` INT, `user_permission` INT, `user_founder` INT, `user_freezed` INT, `additional_permission` INT, `permission_id` INT, `permission_name` INT, `permission_list` INT);

-- -----------------------------------------------------
-- View `agent1_web_db`.`scc_auto_template`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `agent1_web_db`.`scc_auto_template` ;
DROP TABLE IF EXISTS `agent1_web_db`.`scc_auto_template`;
USE `agent1_web_db`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `agent1_web_db`.`scc_auto_template` AS select `agent1_web_db`.`scc_mail_template`.`template_id` AS `template_id`,`agent1_web_db`.`scc_mail_template`.`template_name` AS `template_name`,`agent1_web_db`.`scc_mail_template`.`template_content` AS `template_content`,`agent1_web_db`.`scc_mail_template`.`template_subject` AS `template_subject`,`agent1_web_db`.`scc_mail_template`.`template_reader` AS `template_reader`,`agent1_web_db`.`scc_mail_template`.`smtp_host` AS `smtp_host`,`agent1_web_db`.`scc_mail_template`.`smtp_user` AS `smtp_user`,`agent1_web_db`.`scc_mail_template`.`smtp_pass` AS `smtp_pass`,`agent1_web_db`.`scc_mail_template`.`smtp_from` AS `smtp_from`,`agent1_web_db`.`scc_mail_template`.`smtp_fromName` AS `smtp_fromName`,`agent1_web_db`.`scc_mail_autosend`.`auto_id` AS `auto_id`,`agent1_web_db`.`scc_mail_autosend`.`auto_template_id` AS `auto_template_id`,`agent1_web_db`.`scc_mail_autosend`.`auto_actived` AS `auto_actived`,`agent1_web_db`.`scc_mail_autosend`.`auto_name` AS `auto_name` from (`agent1_web_db`.`scc_mail_template` join `agent1_web_db`.`scc_mail_autosend`) where (`agent1_web_db`.`scc_mail_autosend`.`auto_template_id` = `agent1_web_db`.`scc_mail_template`.`template_id`);

-- -----------------------------------------------------
-- View `agent1_web_db`.`scc_user_permission`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `agent1_web_db`.`scc_user_permission` ;
DROP TABLE IF EXISTS `agent1_web_db`.`scc_user_permission`;
USE `agent1_web_db`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `agent1_web_db`.`scc_user_permission` AS select `a`.`GUID` AS `GUID`,`a`.`user_name` AS `user_name`,`a`.`user_pass` AS `user_pass`,`a`.`user_permission` AS `user_permission`,`a`.`user_founder` AS `user_founder`,`a`.`user_freezed` AS `user_freezed`,`a`.`additional_permission` AS `additional_permission`,`b`.`permission_id` AS `permission_id`,`b`.`permission_name` AS `permission_name`,`b`.`permission_list` AS `permission_list` from (`agent1_web_db`.`scc_user` `a` join `agent1_web_db`.`scc_permission` `b`) where (`a`.`user_permission` = `b`.`permission_id`);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `agent1_product_db`.`game_product`
-- -----------------------------------------------------
START TRANSACTION;
USE `agent1_product_db`;
INSERT INTO `agent1_product_db`.`game_product` (`game_id`, `game_name`, `game_version`, `game_platform`, `auth_key`, `game_pic_small`, `game_pic_middium`, `game_pic_big`, `game_download_iphone`, `game_download_ipad`, `game_status`) VALUES ('B', '战神Online', '1.0.0', 'ios', '467022354ac09e8dd2233acbbde1db7fa9j8ekk7', '', '', '', '', '', 0);

COMMIT;

-- -----------------------------------------------------
-- Data for table `agent1_web_db`.`scc_permission`
-- -----------------------------------------------------
START TRANSACTION;
USE `agent1_web_db`;
INSERT INTO `agent1_web_db`.`scc_permission` (`permission_id`, `permission_name`, `permission_list`) VALUES (999, '超级管理员', 'All');

COMMIT;

-- -----------------------------------------------------
-- Data for table `agent1_web_db`.`scc_user`
-- -----------------------------------------------------
START TRANSACTION;
USE `agent1_web_db`;
INSERT INTO `agent1_web_db`.`scc_user` (`GUID`, `user_name`, `user_pass`, `user_permission`, `permission_name`, `user_founder`, `user_freezed`, `additional_permission`) VALUES ('D2EF3D9D-2022-B1B1-C211-88CAEDFAAB8E', 'johnnyeven', 'b40714d351a35e8f0d2f15ee977da4a9f5a7e2cd', 999, '超级管理员', 1, 0, NULL);

COMMIT;
