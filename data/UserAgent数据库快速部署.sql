SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema agent1_account_db
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `agent1_account_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_account_db` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema agent1_adminlog_db
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `agent1_adminlog_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_adminlog_db` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema agent1_funds_flow_db
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `agent1_funds_flow_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_funds_flow_db` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema agent1_log_db
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `agent1_log_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_log_db` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema agent1_log_db_201203
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `agent1_log_db_201203` ;
CREATE SCHEMA IF NOT EXISTS `agent1_log_db_201203` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema agent1_product_db
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `agent1_product_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_product_db` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema agent1_web_db
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `agent1_web_db` ;
CREATE SCHEMA IF NOT EXISTS `agent1_web_db` DEFAULT CHARACTER SET utf8 ;
USE `agent1_account_db` ;

-- -----------------------------------------------------
-- Table `agent1_account_db`.`account_added_game`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_account_db`.`account_added_game` ;

CREATE TABLE IF NOT EXISTS `agent1_account_db`.`account_added_game` (
  `GUID` CHAR(36) NOT NULL,
  `game_id` CHAR(10) NOT NULL,
  PRIMARY KEY (`GUID`, `game_id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_account_db`.`web_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_account_db`.`web_account` ;

CREATE TABLE IF NOT EXISTS `agent1_account_db`.`web_account` (
  `GUID` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `account_name` CHAR(64) NOT NULL,
  `account_pass` CHAR(32) NOT NULL,
  `server_id` CHAR(6) NOT NULL,
  `account_nickname` CHAR(16) NOT NULL DEFAULT '',
  `account_email` CHAR(32) NULL DEFAULT NULL,
  `account_pass_question` CHAR(4) NULL DEFAULT NULL,
  `account_pass_answer` CHAR(4) NULL DEFAULT NULL,
  `account_point` INT(11) NOT NULL DEFAULT '0',
  `account_regtime` INT(11) NOT NULL DEFAULT '0',
  `account_lastlogin` INT(11) NOT NULL DEFAULT '0',
  `account_currentlogin` INT(11) NOT NULL DEFAULT '0',
  `account_lastip` CHAR(16) NULL DEFAULT NULL,
  `account_currentip` CHAR(16) NULL DEFAULT NULL,
  `account_status` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1=正常 0=试玩 -1=封停',
  `account_activity` INT(11) NOT NULL DEFAULT '0',
  `account_job` CHAR(16) NULL DEFAULT '',
  `profession_icon` CHAR(32) NOT NULL DEFAULT '',
  `account_level` INT NOT NULL DEFAULT 0,
  `account_mission` BIGINT NOT NULL DEFAULT 0,
  `partner_key` CHAR(16) NOT NULL DEFAULT 'default',
  `partner_id` CHAR(32) NOT NULL DEFAULT '',
  `closure_endtime` INT NOT NULL DEFAULT 0,
  `device_id` CHAR(64) NOT NULL DEFAULT '',
  `ad_id` CHAR(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`GUID`),
  INDEX `account_name` (`account_name` ASC, `account_pass` ASC, `server_id` ASC),
  INDEX `partner_id` (`partner_key` ASC, `partner_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 200100191006909;


-- -----------------------------------------------------
-- Table `agent1_account_db`.`account_login_token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_account_db`.`account_login_token` ;

CREATE TABLE IF NOT EXISTS `agent1_account_db`.`account_login_token` (
  `guid` BIGINT NOT NULL,
  `token` CHAR(64) NOT NULL,
  `expire_time` INT NOT NULL,
  PRIMARY KEY (`guid`))
ENGINE = InnoDB;

USE `agent1_adminlog_db` ;

-- -----------------------------------------------------
-- Table `agent1_adminlog_db`.`log_scc`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_adminlog_db`.`log_scc` ;

CREATE TABLE IF NOT EXISTS `agent1_adminlog_db`.`log_scc` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `log_type` VARCHAR(64) NOT NULL,
  `log_user` TEXT NULL DEFAULT NULL,
  `log_relative_page_url` VARCHAR(128) NOT NULL,
  `log_relative_parameter` TEXT NOT NULL,
  `log_addition_parameter` TEXT NULL DEFAULT NULL,
  `log_time` DATETIME NOT NULL,
  PRIMARY KEY (`log_id`))
ENGINE = MyISAM
AUTO_INCREMENT = 14;

USE `agent1_funds_flow_db` ;

-- -----------------------------------------------------
-- Table `agent1_funds_flow_db`.`funds_checkinout`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_funds_flow_db`.`funds_checkinout` ;

CREATE TABLE IF NOT EXISTS `agent1_funds_flow_db`.`funds_checkinout` (
  `funds_id` INT(11) NOT NULL AUTO_INCREMENT,
  `account_guid` BIGINT(20) NOT NULL,
  `account_name` CHAR(64) NOT NULL,
  `account_nickname` CHAR(32) NOT NULL,
  `account_id` CHAR(16) NOT NULL,
  `game_id` CHAR(5) NOT NULL,
  `server_id` CHAR(5) NOT NULL,
  `server_section` CHAR(5) NOT NULL,
  `funds_flow_dir` ENUM('CHECK_IN','CHECK_OUT') NOT NULL,
  `funds_amount` INT(11) NOT NULL,
  `funds_item_amount` INT(11) NOT NULL,
  `funds_item_current` INT(11) NOT NULL,
  `funds_time` INT(11) NOT NULL,
  `funds_time_local` DATETIME NOT NULL,
  `funds_type` INT(11) NOT NULL DEFAULT '1' COMMENT '1=游戏内充值 0=GM手动调整',
  `partner_key` CHAR(16) NOT NULL DEFAULT 'default',
  `receipt_data` TEXT NOT NULL,
  `appstore_status` INT NOT NULL,
  `appstore_device_id` CHAR(64) NOT NULL,
  PRIMARY KEY (`funds_id`),
  INDEX `account_guid` (`account_name` ASC),
  INDEX `account_id` (`account_id` ASC),
  INDEX `game_id` (`game_id` ASC, `server_id` ASC, `server_section` ASC),
  INDEX `funds_flow_dir` (`funds_flow_dir` ASC),
  INDEX `funds_time` (`funds_time` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 188;


-- -----------------------------------------------------
-- Table `agent1_funds_flow_db`.`funds_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_funds_flow_db`.`funds_order` ;

CREATE TABLE IF NOT EXISTS `agent1_funds_flow_db`.`funds_order` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `player_id` CHAR(22) NOT NULL,
  `server_id` CHAR(1) NOT NULL,
  `checksum` CHAR(64) NOT NULL,
  `check_count` INT(11) NOT NULL,
  `status` INT NOT NULL,
  `funds_id` INT NOT NULL,
  `posttime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `checksum_2` (`checksum` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 188;

USE `agent1_log_db` ;

-- -----------------------------------------------------
-- Table `agent1_log_db`.`log_daily_statistics`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db`.`log_daily_statistics` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db`.`log_daily_statistics` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `log_date` DATE NOT NULL,
  `server_id` CHAR(8) NOT NULL,
  `server_name` CHAR(16) NOT NULL,
  `reg_account` INT(11) NOT NULL DEFAULT 0,
  `reg_new_account` INT(11) NOT NULL DEFAULT 0,
  `reg_new_account_valid` INT(11) NOT NULL DEFAULT 0,
  `valid_account` INT(11) NOT NULL DEFAULT 0 COMMENT '等级大于等于1级的帐号',
  `valid_new_account` INT(11) NOT NULL DEFAULT 0,
  `level_account` INT(11) NOT NULL DEFAULT 0,
  `modify_account` INT(11) NOT NULL DEFAULT 0 COMMENT '持有注册帐号的用户',
  `modify_new_account` INT(11) NOT NULL DEFAULT 0,
  `login_account` INT(11) NOT NULL DEFAULT 0,
  `login_account_valid` INT(11) NOT NULL DEFAULT 0,
  `old_login_account` INT(11) NOT NULL DEFAULT 0 COMMENT '当天登录的老用户',
  `active_account` INT(11) NOT NULL DEFAULT 0 COMMENT '活跃用户，三天内登陆过游戏的人数',
  `dau` INT(11) NOT NULL DEFAULT 0,
  `flowover_account` INT(11) NOT NULL DEFAULT 0 COMMENT '流失用户，超过一周没有登录游戏的人数',
  `reflow_account` INT NOT NULL DEFAULT 0,
  `orders_current_sum` INT(11) NOT NULL DEFAULT 0 COMMENT '当天订单总额',
  `orders_num` INT(11) NOT NULL DEFAULT 0,
  `orders_sum` INT(11) NOT NULL DEFAULT 0,
  `arpu` INT(11) NOT NULL DEFAULT 0 COMMENT '充值率',
  `recharge_account` INT(11) NOT NULL DEFAULT 0 COMMENT '当天充值人数',
  `order_count` INT(11) NOT NULL DEFAULT 0 COMMENT '订单数',
  `partner_key` CHAR(16) NOT NULL DEFAULT 'default',
  `at` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `log_date` (`log_date` ASC, `server_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 101;


-- -----------------------------------------------------
-- Table `agent1_log_db`.`log_flowover_cache`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db`.`log_flowover_cache` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db`.`log_flowover_cache` (
  `guid` BIGINT NOT NULL,
  `server_id` CHAR(8) NOT NULL,
  `partner_key` CHAR(16) NOT NULL DEFAULT 'default',
  `account_job` CHAR(16) NOT NULL,
  `account_level` INT NOT NULL,
  `account_mission` BIGINT NOT NULL,
  PRIMARY KEY (`guid`, `server_id`, `partner_key`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_log_db`.`log_flowover_detail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db`.`log_flowover_detail` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db`.`log_flowover_detail` (
  `date` DATE NOT NULL,
  `server_id` CHAR(8) NOT NULL,
  `partner_key` CHAR(16) NOT NULL DEFAULT 'default',
  `job` TEXT NOT NULL,
  `level` TEXT NOT NULL,
  `mission` TEXT NOT NULL,
  PRIMARY KEY (`date`, `server_id`, `partner_key`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_log_db`.`log_online_count`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db`.`log_online_count` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db`.`log_online_count` (
  `server_id` CHAR(8) NOT NULL,
  `partner_key` CHAR(16) NOT NULL,
  `log_date` DATE NOT NULL,
  `log_hour` INT NOT NULL DEFAULT 0,
  `log_count` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`server_id`, `partner_key`, `log_date`, `log_hour`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_log_db`.`log_buy_equipment_detail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db`.`log_buy_equipment_detail` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db`.`log_buy_equipment_detail` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `server_id` CHAR(8) NOT NULL,
  `partner_key` CHAR(16) NOT NULL,
  `level_detail` TEXT NOT NULL,
  `mission_detail` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_log_db`.`log_retention`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db`.`log_retention` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db`.`log_retention` (
  `log_date` DATE NOT NULL,
  `server_id` CHAR(5) NOT NULL,
  `partner_key` CHAR(16) NOT NULL,
  `prev_register` INT NOT NULL DEFAULT 0,
  `prev_current_login` INT NOT NULL DEFAULT 0,
  `next_retention` INT NOT NULL DEFAULT 0,
  `third_register` INT NOT NULL DEFAULT 0,
  `third_current_login` INT NOT NULL DEFAULT 0,
  `third_retention` INT NOT NULL DEFAULT 0,
  `seven_register` INT NOT NULL DEFAULT 0,
  `seven_current_login` INT NOT NULL DEFAULT 0,
  `seven_retention` INT NOT NULL DEFAULT 0,
  `level1` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`log_date`, `server_id`, `partner_key`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_log_db`.`log_funds_hours`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db`.`log_funds_hours` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db`.`log_funds_hours` (
  `log_date` DATE NOT NULL,
  `log_hour` INT NOT NULL,
  `server_id` CHAR(5) NOT NULL,
  `partner_key` CHAR(16) NOT NULL,
  `funds_sum` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`log_date`, `server_id`, `log_hour`, `partner_key`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_log_db`.`log_retention1`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db`.`log_retention1` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db`.`log_retention1` (
  `log_date` DATE NOT NULL,
  `server_id` CHAR(5) NOT NULL,
  `partner_key` CHAR(16) NOT NULL,
  `level_account` INT NOT NULL DEFAULT 0 COMMENT '有效用户数',
  `next_current_login` INT NOT NULL DEFAULT 0 COMMENT '次日登录',
  `next_retention` INT NOT NULL DEFAULT 0 COMMENT '次日留存',
  `third_current_login` INT NOT NULL DEFAULT 0 COMMENT '点三登录',
  `third_retention` INT NOT NULL DEFAULT 0 COMMENT '点三留存',
  `third_current_login_range` INT NOT NULL DEFAULT 0 COMMENT '连续三日登录',
  `third_retention_range` INT NOT NULL DEFAULT 0,
  `seven_current_login` INT NOT NULL DEFAULT 0,
  `seven_retention` INT NOT NULL DEFAULT 0,
  `seven_current_login_range` INT NOT NULL DEFAULT 0 COMMENT '小区间七日登录',
  `seven_retention_range` INT NOT NULL DEFAULT 0 COMMENT '小区间七日留存率',
  `seven_current_login_huge` INT NOT NULL DEFAULT 0 COMMENT '大区间7日登录',
  `seven_retention_huge` INT NOT NULL DEFAULT 0 COMMENT '大区间七日留存',
  `level1` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`log_date`, `server_id`, `partner_key`))
ENGINE = InnoDB;

USE `agent1_log_db_201203` ;

-- -----------------------------------------------------
-- Table `agent1_log_db_201203`.`log_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db_201203`.`log_account` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db_201203`.`log_account` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `log_GUID` CHAR(36) NOT NULL,
  `log_account_name` CHAR(64) NULL DEFAULT NULL,
  `log_account_level` INT NOT NULL DEFAULT 0,
  `log_action` CHAR(64) NOT NULL,
  `log_parameter` TEXT NULL DEFAULT NULL,
  `log_time` INT(11) NOT NULL,
  `log_ip` CHAR(24) NOT NULL,
  `server_id` CHAR(5) NOT NULL,
  `partner_key` CHAR(16) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`log_id`),
  INDEX `log_GUID` USING BTREE (`log_GUID` ASC),
  INDEX `log_account_name` USING BTREE (`log_account_name` ASC),
  INDEX `log_action` USING BTREE (`log_action` ASC),
  INDEX `game_id` (`server_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 2068;


-- -----------------------------------------------------
-- Table `agent1_log_db_201203`.`log_consume`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db_201203`.`log_consume` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db_201203`.`log_consume` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `player_id` BIGINT NOT NULL,
  `role_id` BIGINT NOT NULL,
  `role_level` INT NOT NULL DEFAULT 0,
  `role_mission` CHAR(16) NOT NULL,
  `action_name` CHAR(64) NOT NULL,
  `current_gold` INT NOT NULL,
  `spend_gold` INT NOT NULL,
  `current_special_gold` INT(11) NOT NULL,
  `spend_special_gold` INT(11) NOT NULL,
  `item_name` CHAR(64) NOT NULL,
  `item_info` TEXT NOT NULL,
  `item_type` INT NOT NULL,
  `item_level` INT NOT NULL DEFAULT 0 COMMENT '装备等级',
  `item_value` INT NOT NULL DEFAULT 0 COMMENT '装�' /* comment truncated */ /*�品质
1=普通 2=绿色 3=蓝色 4=紫色*/,
  `item_job` CHAR(16) NOT NULL DEFAULT '' COMMENT '装备�' /* comment truncated */ /*�求的职业
1=战士 2=猎手 3=潜行者 4=法师*/,
  `log_time` INT(11) NOT NULL,
  `server_id` CHAR(5) NOT NULL,
  `partner_key` CHAR(16) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`log_id`),
  INDEX `player_id` (`player_id` ASC),
  INDEX `item_name` (`item_name` ASC),
  INDEX `server_id` (`server_id` ASC))
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `agent1_log_db_201203`.`equipment_name`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db_201203`.`equipment_name` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db_201203`.`equipment_name` (
  `equipment_name` CHAR(16) NOT NULL,
  `type` INT NOT NULL,
  PRIMARY KEY (`equipment_name`),
  INDEX `type` (`type` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_log_db_201203`.`log_api`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db_201203`.`log_api` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db_201203`.`log_api` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `log_GUID` CHAR(36) NOT NULL,
  `log_account_name` CHAR(64) NULL DEFAULT NULL,
  `log_action` CHAR(64) NOT NULL,
  `log_parameter` TEXT NULL DEFAULT NULL,
  `log_time` INT(11) NOT NULL,
  `log_ip` CHAR(24) NOT NULL,
  `server_id` CHAR(5) NOT NULL,
  `partner_key` CHAR(16) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`log_id`),
  INDEX `log_GUID` USING BTREE (`log_GUID` ASC),
  INDEX `log_account_name` USING BTREE (`log_account_name` ASC),
  INDEX `log_action` USING BTREE (`log_action` ASC),
  INDEX `game_id` (`server_id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 2068
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_log_db_201203`.`log_rep`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db_201203`.`log_rep` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db_201203`.`log_rep` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `server_id` CHAR(5) NOT NULL,
  `player_id` BIGINT NOT NULL,
  `type` INT NOT NULL,
  `time` INT NOT NULL,
  `profession` CHAR(16) NOT NULL,
  `nickname` CHAR(20) NOT NULL,
  `posttime` INT NOT NULL,
  `partner_key` CHAR(16) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_log_db_201203`.`log_action_mall`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_log_db_201203`.`log_action_mall` ;

CREATE TABLE IF NOT EXISTS `agent1_log_db_201203`.`log_action_mall` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `player_id` BIGINT NOT NULL,
  `role_id` BIGINT NOT NULL,
  `nickname` CHAR(16) NOT NULL,
  `content` TEXT NOT NULL,
  `posttime` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `player_id` (`player_id` ASC))
ENGINE = InnoDB;

USE `agent1_product_db` ;

-- -----------------------------------------------------
-- Table `agent1_product_db`.`game_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`game_product` ;

CREATE TABLE IF NOT EXISTS `agent1_product_db`.`game_product` (
  `game_id` CHAR(5) NOT NULL,
  `game_name` CHAR(64) NOT NULL,
  `game_version` CHAR(16) NOT NULL,
  `game_platform` ENUM('web','ios','android') NULL DEFAULT 'ios',
  `auth_key` CHAR(128) NOT NULL,
  `game_pic_small` TEXT NULL DEFAULT NULL,
  `game_pic_middium` TEXT NULL DEFAULT NULL,
  `game_pic_big` TEXT NULL DEFAULT NULL,
  `game_download_iphone` TEXT NULL DEFAULT NULL,
  `game_download_ipad` TEXT NULL DEFAULT NULL,
  `game_status` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0=正式,1=内测,2=公测',
  PRIMARY KEY (`game_id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_product_db`.`server_list`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`server_list` ;

CREATE TABLE IF NOT EXISTS `agent1_product_db`.`server_list` (
  `id` INT NOT NULL,
  `game_id` CHAR(5) NOT NULL,
  `section_id` INT NOT NULL,
  `account_server_id` CHAR(5) NOT NULL,
  `server_name` CHAR(32) NOT NULL,
  `server_ip` TEXT NOT NULL,
  `server_game_ip` TEXT NOT NULL,
  `game_message_ip` TEXT NOT NULL,
  `const_server_ip` TEXT NOT NULL,
  `voice_server_ip` TEXT NOT NULL,
  `cross_server_ip` TEXT NOT NULL,
  `server_max_player` INT(11) NOT NULL DEFAULT '0',
  `account_count` INT(11) NOT NULL DEFAULT '0',
  `server_language` CHAR(16) NOT NULL DEFAULT 'CN',
  `server_sort` INT(11) NOT NULL DEFAULT '0',
  `server_recommend` TINYINT(1) NOT NULL DEFAULT '0',
  `server_debug` TINYINT NOT NULL DEFAULT 0,
  `partner` CHAR(64) NOT NULL DEFAULT 'default',
  `version` CHAR(64) NOT NULL,
  `server_status` INT(11) NOT NULL DEFAULT '1' COMMENT '0=关闭；1=正常；2=繁忙；3=拥挤；9=隐藏',
  `server_new` INT(11) NOT NULL DEFAULT 1 COMMENT '1=新服；0=旧服',
  `special_ip` CHAR(16) NOT NULL DEFAULT '',
  `server_starttime` INT NOT NULL DEFAULT 0 COMMENT '开服时间',
  `need_activate` TINYINT NOT NULL DEFAULT 1,
  INDEX `server_recommend` (`server_recommend` ASC),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `unique_id` (`game_id` ASC, `account_server_id` ASC, `server_name` ASC, `special_ip` ASC))
ENGINE = MyISAM;


-- -----------------------------------------------------
-- Table `agent1_product_db`.`server_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`server_status` ;

CREATE TABLE IF NOT EXISTS `agent1_product_db`.`server_status` (
  `server_status` INT(11) NOT NULL,
  `message` TEXT NOT NULL,
  `redirectUrl` TEXT NOT NULL)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_product_db`.`game_announcement`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`game_announcement` ;

CREATE TABLE IF NOT EXISTS `agent1_product_db`.`game_announcement` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `summary` TEXT NOT NULL,
  `content` TEXT NOT NULL,
  `post_time` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_product_db`.`game_autosend_message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`game_autosend_message` ;

CREATE TABLE IF NOT EXISTS `agent1_product_db`.`game_autosend_message` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` TEXT NOT NULL,
  `is_auto_send` TINYINT NOT NULL DEFAULT 1,
  `pattern` CHAR(32) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_product_db`.`game_code`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`game_code` ;

CREATE TABLE IF NOT EXISTS `agent1_product_db`.`game_code` (
  `code` CHAR(20) NOT NULL,
  `comment` CHAR(16) NOT NULL DEFAULT '',
  `disabled` TINYINT NOT NULL DEFAULT 0,
  `server_id` CHAR(8) NOT NULL DEFAULT '',
  PRIMARY KEY (`code`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agent1_product_db`.`game_announcement_crontab`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_product_db`.`game_announcement_crontab` ;

CREATE TABLE IF NOT EXISTS `agent1_product_db`.`game_announcement_crontab` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `server_id` CHAR(5) NOT NULL,
  `content` TEXT NOT NULL,
  `minutes` CHAR(64) NOT NULL,
  `hour` CHAR(64) NOT NULL,
  `date` CHAR(64) NOT NULL,
  `starttime` INT NOT NULL,
  `endtime` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

USE `agent1_web_db` ;

-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_config`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_config` ;

CREATE TABLE IF NOT EXISTS `agent1_web_db`.`scc_config` (
  `config_id` INT(11) NOT NULL AUTO_INCREMENT,
  `config_name` VARCHAR(45) NOT NULL,
  `config_close_scc` TINYINT(1) NOT NULL DEFAULT '0',
  `config_close_reason` TEXT NOT NULL,
  `config_selected` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`config_id`))
ENGINE = MyISAM
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_mail_autosend`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_mail_autosend` ;

CREATE TABLE IF NOT EXISTS `agent1_web_db`.`scc_mail_autosend` (
  `auto_id` INT(11) NOT NULL AUTO_INCREMENT,
  `auto_template_id` INT(11) NOT NULL,
  `auto_actived` TINYINT(1) NOT NULL DEFAULT '1',
  `auto_name` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`auto_id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_mail_template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_mail_template` ;

CREATE TABLE IF NOT EXISTS `agent1_web_db`.`scc_mail_template` (
  `template_id` INT(11) NOT NULL AUTO_INCREMENT,
  `template_name` TEXT NULL DEFAULT NULL,
  `template_content` TEXT NOT NULL,
  `template_auto_send` TINYINT(4) NULL DEFAULT NULL,
  `template_subject` TEXT NULL DEFAULT NULL,
  `template_reader` VARCHAR(45) NULL DEFAULT NULL,
  `smtp_host` VARCHAR(20) NOT NULL DEFAULT '67.228.209.12',
  `smtp_user` VARCHAR(45) NOT NULL DEFAULT 'contact@macxdvd.com',
  `smtp_pass` VARCHAR(45) NOT NULL DEFAULT 'cont333999',
  `smtp_from` VARCHAR(45) NOT NULL DEFAULT 'contact@macxdvd.com',
  `smtp_fromName` VARCHAR(45) NOT NULL DEFAULT 'contact@macxdvd.com',
  PRIMARY KEY (`template_id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_permission`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_permission` ;

CREATE TABLE IF NOT EXISTS `agent1_web_db`.`scc_permission` (
  `permission_id` INT(11) NOT NULL,
  `permission_name` VARCHAR(24) NOT NULL,
  `permission_list` TEXT NOT NULL,
  PRIMARY KEY (`permission_id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_user` ;

CREATE TABLE IF NOT EXISTS `agent1_web_db`.`scc_user` (
  `GUID` VARCHAR(36) NOT NULL,
  `user_name` VARCHAR(16) NOT NULL,
  `user_pass` VARCHAR(64) NOT NULL,
  `user_permission` INT(11) NOT NULL DEFAULT '1',
  `permission_name` CHAR(16) NOT NULL,
  `user_founder` TINYINT(1) NOT NULL DEFAULT '0',
  `user_freezed` TINYINT(4) NOT NULL DEFAULT '0',
  `additional_permission` TEXT NOT NULL,
  `user_fromwhere` CHAR(16) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`GUID`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `agent1_web_db`.`scc_partner`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `agent1_web_db`.`scc_partner` ;

CREATE TABLE IF NOT EXISTS `agent1_web_db`.`scc_partner` (
  `partner_key` CHAR(16) NOT NULL,
  PRIMARY KEY (`partner_key`))
ENGINE = InnoDB;

USE `agent1_web_db` ;

-- -----------------------------------------------------
-- Placeholder table for view `agent1_web_db`.`scc_auto_template`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `agent1_web_db`.`scc_auto_template` (`template_id` INT, `template_name` INT, `template_content` INT, `template_subject` INT, `template_reader` INT, `smtp_host` INT, `smtp_user` INT, `smtp_pass` INT, `smtp_from` INT, `smtp_fromName` INT, `auto_id` INT, `auto_template_id` INT, `auto_actived` INT, `auto_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `agent1_web_db`.`scc_user_permission`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `agent1_web_db`.`scc_user_permission` (`GUID` INT, `user_name` INT, `user_pass` INT, `user_permission` INT, `user_founder` INT, `user_freezed` INT, `additional_permission` INT, `user_fromwhere` INT, `permission_id` INT, `permission_name` INT, `permission_list` INT);

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
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `agent1_web_db`.`scc_user_permission` AS select `a`.`GUID` AS `GUID`,`a`.`user_name` AS `user_name`,`a`.`user_pass` AS `user_pass`,`a`.`user_permission` AS `user_permission`,`a`.`user_founder` AS `user_founder`,`a`.`user_freezed` AS `user_freezed`,`a`.`additional_permission` AS `additional_permission`,`a`.`user_fromwhere` AS `user_fromwhere`,`b`.`permission_id` AS `permission_id`,`b`.`permission_name` AS `permission_name`,`b`.`permission_list` AS `permission_list` from (`agent1_web_db`.`scc_user` `a` join `agent1_web_db`.`scc_permission` `b`) where (`a`.`user_permission` = `b`.`permission_id`);

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
INSERT INTO `agent1_web_db`.`scc_user` (`GUID`, `user_name`, `user_pass`, `user_permission`, `permission_name`, `user_founder`, `user_freezed`, `additional_permission`, `user_fromwhere`) VALUES ('D2EF3D9D-2022-B1B1-C211-88CAEDFAAB8E', 'johnnyeven', 'b40714d351a35e8f0d2f15ee977da4a9f5a7e2cd', 999, '超级管理员', 1, 0, '', 'default');

COMMIT;


-- -----------------------------------------------------
-- Data for table `agent1_web_db`.`scc_partner`
-- -----------------------------------------------------
START TRANSACTION;
USE `agent1_web_db`;
INSERT INTO `agent1_web_db`.`scc_partner` (`partner_key`) VALUES ('default');

COMMIT;

