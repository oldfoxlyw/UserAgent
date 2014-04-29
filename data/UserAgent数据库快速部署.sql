SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `pulse_db_web` ;
CREATE SCHEMA IF NOT EXISTS `pulse_db_web` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
DROP SCHEMA IF EXISTS `pulse_db_log` ;
CREATE SCHEMA IF NOT EXISTS `pulse_db_log` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `pulse_db_web` ;

-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_products`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_products` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_products` (
  `product_id` INT NOT NULL AUTO_INCREMENT,
  `product_name` CHAR(16) NOT NULL COMMENT '游戏名称',
  `product_category` CHAR(16) NOT NULL COMMENT '游戏类型',
  `product_comment` TEXT NOT NULL COMMENT '游戏描述',
  `product_url_website` CHAR(128) NOT NULL COMMENT '游戏官网链接',
  `product_url_entry` CHAR(128) NOT NULL COMMENT '游戏入口链接',
  `product_status` ENUM('PUBLIC','BETA','HOT','CLOSE') NOT NULL DEFAULT 'PUBLIC',
  `product_recommand` TINYINT NOT NULL DEFAULT 1,
  `product_sort` INT NOT NULL DEFAULT 0,
  `product_exchange_rate` INT NOT NULL COMMENT '1元人民币能兑换多少游戏币',
  `product_currency_name` CHAR(8) NOT NULL,
  `product_server_role` CHAR(64) NOT NULL COMMENT '获取角色列表的接口',
  `product_server_recharge` CHAR(64) NOT NULL COMMENT '充值的接口',
  `product_key` CHAR(32) NOT NULL,
  PRIMARY KEY (`product_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1001;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_serverlist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_serverlist` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_serverlist` (
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `server_name` CHAR(32) NOT NULL,
  `server_time_start` INT NOT NULL,
  `server_status` ENUM('NORMAL','HOT','CLOSE') NOT NULL DEFAULT 'NORMAL',
  `server_web_url` CHAR(64) NOT NULL,
  `server_game_ip` CHAR(16) NOT NULL,
  `server_game_port` CHAR(10) NOT NULL,
  `server_service_url` CHAR(64) NOT NULL,
  `product_name` CHAR(16) NOT NULL,
  `server_type` TINYINT NOT NULL DEFAULT 1 COMMENT '1=正式服\n0=测试服',
  PRIMARY KEY (`product_id`, `server_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_news`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_news` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_news` (
  `news_id` INT NOT NULL AUTO_INCREMENT,
  `news_title` CHAR(64) NOT NULL,
  `product_id` INT NOT NULL,
  `product_name` CHAR(16) NOT NULL,
  `news_category` CHAR(16) NOT NULL,
  `news_content` MEDIUMTEXT NOT NULL,
  `news_posttime` INT NOT NULL,
  PRIMARY KEY (`news_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1000;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_account` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_account` (
  `account_id` BIGINT NOT NULL AUTO_INCREMENT,
  `account_name` CHAR(32) NOT NULL,
  `account_pass` CHAR(64) NOT NULL,
  `account_email` CHAR(64) NOT NULL,
  `account_mobile` CHAR(32) NOT NULL,
  `account_nickname` CHAR(16) NOT NULL,
  `account_sex` TINYINT NOT NULL DEFAULT 1,
  `account_birthday` INT NOT NULL DEFAULT 0,
  `account_country` CHAR(32) NOT NULL,
  `account_city` CHAR(32) NOT NULL,
  `account_job` CHAR(32) NOT NULL,
  `account_regtime` INT NOT NULL,
  `account_lastlogin` INT NOT NULL,
  `ucenter_uid` INT NOT NULL DEFAULT 0,
  `account_cash` INT NOT NULL DEFAULT 0 COMMENT '剩余平台币',
  `account_recharge` INT NOT NULL DEFAULT 0 COMMENT '累计充值',
  `partner` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`account_id`),
  INDEX `account_name` (`account_name` ASC, `account_pass` ASC),
  INDEX `ucenter_uid` (`ucenter_uid` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_activity`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_activity` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_activity` (
  `activity_id` INT NOT NULL AUTO_INCREMENT,
  `activity_name` CHAR(32) NOT NULL,
  `activity_url` CHAR(128) NOT NULL,
  `activity_loop` TINYINT NOT NULL DEFAULT 1,
  `activity_looptype` INT NOT NULL DEFAULT 1 COMMENT 'activity_looptype\n0=不重复\n1=每天\n2=每周\n3=每月',
  `activity_time_start` INT NOT NULL,
  `activity_time_end` INT NOT NULL,
  `product_id` INT NOT NULL,
  PRIMARY KEY (`activity_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_log` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_log` (
  `log_id` BIGINT NOT NULL AUTO_INCREMENT,
  `log_type` CHAR(32) NOT NULL,
  `log_account_name` CHAR(32) NOT NULL,
  `log_uri` CHAR(128) NOT NULL,
  `log_method` CHAR(8) NOT NULL,
  `log_parameter` TEXT NOT NULL,
  `log_time_local` DATETIME NOT NULL,
  `partner` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`log_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_feed`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_feed` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_feed` (
  `feed_id` BIGINT NOT NULL AUTO_INCREMENT,
  `account_id` BIGINT NOT NULL,
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `feed_time` INT NOT NULL,
  PRIMARY KEY (`feed_id`),
  INDEX `account_id` (`account_id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_mail_template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_mail_template` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_mail_template` (
  `template_id` INT NOT NULL AUTO_INCREMENT,
  `template_name` CHAR(64) NOT NULL,
  `template_subject` CHAR(64) NOT NULL,
  `template_content` TEXT NOT NULL,
  `smtp_host` CHAR(16) NOT NULL,
  `smtp_user` CHAR(32) NOT NULL,
  `smtp_pass` CHAR(32) NOT NULL,
  `smtp_from` VARCHAR(64) NOT NULL,
  `smtp_fromName` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`template_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_mail_bind`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_mail_bind` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_mail_bind` (
  `code` CHAR(32) NOT NULL,
  `account_id` BIGINT NOT NULL,
  `account_email` CHAR(64) NOT NULL,
  `expire_time` INT NOT NULL,
  `bind_validate` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`code`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_coupon`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_coupon` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_coupon` (
  `coupon_content` CHAR(32) NOT NULL,
  `product_id` INT NOT NULL COMMENT '适用于哪个游戏',
  `coupon_type` TINYINT NOT NULL DEFAULT 0 COMMENT '礼券类型\n0=现金礼券\n1=道具礼券',
  `coupon_detail` CHAR(128) NOT NULL COMMENT 'JSON格式\n如果coupon_type=0\ncoupon_detail={value:10}\n\n如果coupon_type=1\ncoupon_detail={[{id:1002, value:1}, {id:1003, value:2}]}',
  `coupon_expire_time` INT NOT NULL,
  `coupon_inuse` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`coupon_content`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_account_coupon`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_account_coupon` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_account_coupon` (
  `account_id` BIGINT NOT NULL,
  `coupon_content` CHAR(32) NOT NULL,
  `post_time` INT NOT NULL,
  `expire_time` INT NOT NULL,
  PRIMARY KEY (`account_id`, `coupon_content`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_appeal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_appeal` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_appeal` (
  `appeal_id` BIGINT NOT NULL AUTO_INCREMENT,
  `appeal_category` CHAR(16) NOT NULL,
  `account_id` BIGINT NOT NULL COMMENT '发布者ID',
  `account_name` CHAR(32) NOT NULL COMMENT '申诉的帐号',
  `appeal_content` TEXT NOT NULL,
  `appeal_posttime` INT NOT NULL,
  `appeal_reply` TEXT NOT NULL,
  `appeal_reply_posttime` INT NOT NULL,
  `appeal_status` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`appeal_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 10020153001;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_server_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_server_log` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_server_log` (
  `product_id` INT NOT NULL,
  `account_id` BIGINT NOT NULL,
  `server_id` INT NOT NULL,
  `updatetime` INT NOT NULL,
  PRIMARY KEY (`product_id`, `account_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_role_count`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_role_count` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_role_count` (
  `product_id` INT NOT NULL,
  `account_id` BIGINT NOT NULL,
  `server_id` INT NOT NULL,
  `count` INT NOT NULL,
  PRIMARY KEY (`product_id`, `account_id`, `server_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_screenshot`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_screenshot` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_screenshot` (
  `screenshot_id` INT NOT NULL AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `product_name` CHAR(16) NOT NULL,
  `screenshot_title` CHAR(64) NOT NULL,
  `screenshot_content` TEXT NOT NULL,
  `screenshot_posttime` INT NOT NULL,
  `screenshot_pic_url` TEXT NOT NULL,
  PRIMARY KEY (`screenshot_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_admin` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_admin` (
  `admin_id` INT NOT NULL AUTO_INCREMENT,
  `admin_name` CHAR(32) NOT NULL,
  `admin_pass` CHAR(64) NOT NULL,
  `admin_regtime` INT NOT NULL,
  `admin_lastlogin` INT NOT NULL DEFAULT 0,
  `admin_level` INT NOT NULL DEFAULT 10,
  `role_id` INT NOT NULL,
  `role_name` CHAR(16) NOT NULL,
  `custom_permission` TEXT NOT NULL,
  PRIMARY KEY (`admin_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_admin_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_admin_role` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_admin_role` (
  `role_id` INT NOT NULL AUTO_INCREMENT,
  `role_name` CHAR(16) NOT NULL,
  `role_level` INT NOT NULL DEFAULT 10,
  `role_permission` TEXT NOT NULL,
  PRIMARY KEY (`role_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_role` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_role` (
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `partner` INT NOT NULL,
  `account_id` BIGINT NOT NULL,
  `role_id` BIGINT NOT NULL,
  `nickname` CHAR(32) NOT NULL,
  `level` INT NOT NULL,
  `last_mission` CHAR(36) NOT NULL,
  `login_time` INT NOT NULL,
  `order_count` INT NOT NULL DEFAULT 0 COMMENT '累计付费次数',
  `order_sum` INT NOT NULL DEFAULT 0 COMMENT '累计充值金额（分为单位）',
  PRIMARY KEY (`product_id`, `server_id`, `account_id`, `role_id`, `partner`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_web`.`pulse_partner`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_web`.`pulse_partner` ;

CREATE TABLE IF NOT EXISTS `pulse_db_web`.`pulse_partner` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` CHAR(16) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1001;

USE `pulse_db_log` ;

-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_account_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_account_role` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_account_role` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `account_id` BIGINT NOT NULL,
  `role_id` BIGINT NOT NULL DEFAULT 0,
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL DEFAULT 0,
  `terminal_type` TINYINT NOT NULL DEFAULT 1 COMMENT '终端类型\n1=PC登录\n2=Mobile登录\n3=网页登录',
  `partner` INT NOT NULL DEFAULT 0,
  `action_type` INT NOT NULL COMMENT '1=登录\n2=注册',
  `log_time` INT NOT NULL,
  `log_ip` CHAR(20) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `role_id` (`account_id` ASC, `role_id` ASC, `product_id` ASC, `server_id` ASC, `terminal_type` ASC, `partner` ASC),
  INDEX `action_type` (`action_type` ASC),
  INDEX `sts` (`product_id` ASC, `server_id` ASC, `terminal_type` ASC, `partner` ASC, `action_type` ASC, `log_time` ASC),
  INDEX `unique_role_id` (`role_id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_product_common`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_product_common` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_product_common` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `log_date` DATE NOT NULL,
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `terminal_type` TINYINT NOT NULL COMMENT '终端类型\n0=总数\n1=PC登录\n2=Mobile登录\n3=网页登录',
  `partner` INT NOT NULL,
  `count_register` INT NOT NULL COMMENT '日注册用户数',
  `count_register_new` INT NOT NULL COMMENT '日新增注册用户数',
  `count_ulogin` INT NOT NULL COMMENT '日独立登录数',
  `count_login` INT NOT NULL COMMENT '日登录次数（有重复）',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_product_consume`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_product_consume` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_product_consume` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `log_date` DATE NOT NULL,
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `order_type` TINYINT NOT NULL COMMENT '充值渠道：0=总数 1=paypal 999=平台直充',
  `partner` INT NOT NULL,
  `sum_order` INT NOT NULL COMMENT '订单总额',
  `count_upaid` INT NOT NULL COMMENT '日付费用户数（去重）',
  `arpu` INT NOT NULL COMMENT '销售金额/付费用户数(付费一次，重复不计)',
  `login_arpu` INT NOT NULL DEFAULT 0 COMMENT '登录用户付费转化率\n付费用户/登录用户',
  `register_arpu` INT NOT NULL DEFAULT 0 COMMENT '新增注册用户付费转化率\n付费用户/注册用户',
  `all_arpu` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_order` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_order` (
  `order_id` BIGINT NOT NULL AUTO_INCREMENT,
  `account_id` BIGINT NOT NULL,
  `partner` INT NOT NULL,
  `order_cash` INT NOT NULL COMMENT '以分为单位',
  `account_cash` INT NOT NULL COMMENT '累计充值金额',
  `account_fromwhere` INT NOT NULL,
  `order_type` INT NOT NULL DEFAULT 1 COMMENT '充值渠道：1=paypal',
  `order_time` INT NOT NULL,
  PRIMARY KEY (`order_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_order_game`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_order_game` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_order_game` (
  `order_id` BIGINT NOT NULL AUTO_INCREMENT,
  `account_id` BIGINT NOT NULL,
  `role_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `partner` INT NOT NULL,
  `order_cash` INT NOT NULL COMMENT '金额（分为单位）',
  `gold_count` INT NOT NULL COMMENT '增加的游戏币数量',
  `account_cash` INT NOT NULL COMMENT '剩余平台币',
  `order_time` INT NOT NULL,
  `order_type` INT NOT NULL DEFAULT 999 COMMENT '充值渠道：1=paypal 999=平台直充',
  PRIMARY KEY (`order_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_consume`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_consume` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_consume` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `account_id` BIGINT NOT NULL,
  `role_id` BIGINT NOT NULL,
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `partner` INT NOT NULL,
  `currency_type` TINYINT NOT NULL COMMENT '货币种类\n0=普通游戏币\n1=付费游戏币',
  `currency_current` INT NOT NULL,
  `currency_change` INT NOT NULL,
  `item_name` CHAR(64) NOT NULL,
  `item_type` TINYINT NOT NULL,
  `time` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `role_id` (`account_id` ASC, `role_id` ASC, `product_id` ASC, `server_id` ASC, `partner` ASC, `currency_type` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_online_count_detail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_online_count_detail` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_online_count_detail` (
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `date` DATE NOT NULL,
  `hour` INT NOT NULL,
  `minutes` INT NOT NULL,
  `count` INT NOT NULL,
  PRIMARY KEY (`date`, `product_id`, `server_id`, `hour`, `minutes`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_online_avg_daily`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_online_avg_daily` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_online_avg_daily` (
  `id` BIGINT NOT NULL,
  `date` DATE NOT NULL,
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `hour` INT NOT NULL,
  `count` INT NOT NULL DEFAULT 0,
  `arpu` INT NOT NULL DEFAULT 0 COMMENT '平均在线ARPU',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_online_max_daily`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_online_max_daily` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_online_max_daily` (
  `id` BIGINT NOT NULL,
  `date` DATE NOT NULL,
  `product_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `hour` INT NOT NULL,
  `count` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_function_click_count`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_function_click_count` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_function_click_count` (
  `func_name` CHAR(16) NOT NULL,
  `count` BIGINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`func_name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_product_order_count`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_product_order_count` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_product_order_count` (
  `role_id` BIGINT NOT NULL,
  `date` DATE NOT NULL,
  `count` INT NOT NULL,
  PRIMARY KEY (`role_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pulse_db_log`.`log_product_paid_count`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pulse_db_log`.`log_product_paid_count` ;

CREATE TABLE IF NOT EXISTS `pulse_db_log`.`log_product_paid_count` (
  `log_date` DATE NOT NULL,
  `account_id` BIGINT NOT NULL,
  `product_id` BIGINT NOT NULL,
  `server_id` INT NOT NULL,
  `partner` INT NOT NULL,
  `role_id` BIGINT NOT NULL,
  `count` INT NOT NULL COMMENT '日付费次数',
  `sum` INT NOT NULL,
  PRIMARY KEY (`log_date`, `account_id`, `product_id`, `server_id`, `partner`, `role_id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `pulse_db_web`.`pulse_products`
-- -----------------------------------------------------
START TRANSACTION;
USE `pulse_db_web`;
INSERT INTO `pulse_db_web`.`pulse_products` (`product_id`, `product_name`, `product_category`, `product_comment`, `product_url_website`, `product_url_entry`, `product_status`, `product_recommand`, `product_sort`, `product_exchange_rate`, `product_currency_name`, `product_server_role`, `product_server_recharge`, `product_key`) VALUES (1001, '星际移民2.0', 'ARPG科幻页游', '星际移民', 'http://localhost:8080/pulse/ss2', 'http://localhost:8080/pulse/ss2/entry', 'PUBLIC', 1, 0, 1, '暗能水晶', '', '', '');

COMMIT;


-- -----------------------------------------------------
-- Data for table `pulse_db_web`.`pulse_admin`
-- -----------------------------------------------------
START TRANSACTION;
USE `pulse_db_web`;
INSERT INTO `pulse_db_web`.`pulse_admin` (`admin_id`, `admin_name`, `admin_pass`, `admin_regtime`, `admin_lastlogin`, `admin_level`, `role_id`, `role_name`, `custom_permission`) VALUES (1, 'johnnyeven', 'b40714d351a35e8f0d2f15ee977da4a9f5a7e2cd', 0, 0, 9999, 1, '创建者', 'all');

COMMIT;


-- -----------------------------------------------------
-- Data for table `pulse_db_web`.`pulse_admin_role`
-- -----------------------------------------------------
START TRANSACTION;
USE `pulse_db_web`;
INSERT INTO `pulse_db_web`.`pulse_admin_role` (`role_id`, `role_name`, `role_level`, `role_permission`) VALUES (1, '创建者', 9999, 'all');

COMMIT;


-- -----------------------------------------------------
-- Data for table `pulse_db_web`.`pulse_partner`
-- -----------------------------------------------------
START TRANSACTION;
USE `pulse_db_web`;
INSERT INTO `pulse_db_web`.`pulse_partner` (`id`, `name`) VALUES (1, 'default');

COMMIT;

