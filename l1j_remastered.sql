/*
 Navicat Premium Dump SQL

 Source Server         : MySQL Server
 Source Server Type    : MySQL
 Source Server Version : 90200 (9.2.0)
 Source Host           : localhost:3306
 Source Schema         : l1j_remastered

 Target Server Type    : MySQL
 Target Server Version : 90200 (9.2.0)
 File Encoding         : 65001

 Date: 02/04/2025 05:29:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for 0_translations
-- ----------------------------
DROP TABLE IF EXISTS `0_translations`;
CREATE TABLE `0_translations`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `text_korean` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `text_english` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `source` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `line_number` int NULL DEFAULT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_transl_source_line`(`source` ASC, `line_number` ASC) USING BTREE,
  INDEX `idx_transl_korean_text`(`text_korean`(768) ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 56812 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for accounts
-- ----------------------------
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts`  (
  `login` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `password` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `lastactive` datetime NULL DEFAULT NULL,
  `lastQuit` datetime NULL DEFAULT NULL,
  `access_level` int NOT NULL DEFAULT 0,
  `ip` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `host` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `banned` int UNSIGNED NOT NULL DEFAULT 0,
  `charslot` int NOT NULL DEFAULT 6,
  `warehouse_password` int NOT NULL DEFAULT 0,
  `notice` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '0',
  `quiz` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `hddId` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `boardId` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `Tam_Point` int NOT NULL DEFAULT 0,
  `Buff_DMG_Time` datetime NULL DEFAULT NULL,
  `Buff_Reduc_Time` datetime NULL DEFAULT NULL,
  `Buff_Magic_Time` datetime NULL DEFAULT NULL,
  `Buff_Stun_Time` datetime NULL DEFAULT NULL,
  `Buff_Hold_Time` datetime NULL DEFAULT NULL,
  `BUFF_PCROOM_Time` datetime NULL DEFAULT NULL,
  `Buff_FireDefence_Time` datetime NULL DEFAULT NULL,
  `Buff_EarthDefence_Time` datetime NULL DEFAULT NULL,
  `Buff_WaterDefence_Time` datetime NULL DEFAULT NULL,
  `Buff_WindDefence_Time` datetime NULL DEFAULT NULL,
  `Buff_SoulDefence_Time` datetime NULL DEFAULT NULL,
  `Buff_Str_Time` datetime NULL DEFAULT NULL,
  `Buff_Dex_Time` datetime NULL DEFAULT NULL,
  `Buff_Wis_Time` datetime NULL DEFAULT NULL,
  `Buff_Int_Time` datetime NULL DEFAULT NULL,
  `Buff_FireAttack_Time` datetime NULL DEFAULT NULL,
  `Buff_EarthAttack_Time` datetime NULL DEFAULT NULL,
  `Buff_WaterAttack_Time` datetime NULL DEFAULT NULL,
  `Buff_WindAttack_Time` datetime NULL DEFAULT NULL,
  `Buff_Hero_Time` datetime NULL DEFAULT NULL,
  `Buff_Life_Time` datetime NULL DEFAULT NULL,
  `second_password` varchar(11) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `Ncoin` int NOT NULL DEFAULT 0,
  `Npoint` int NOT NULL DEFAULT 0,
  `Shop_open_count` int NOT NULL DEFAULT 0,
  `DragonRaid_Buff` datetime NULL DEFAULT NULL,
  `Einhasad` int NOT NULL DEFAULT 0,
  `EinDayBonus` int NOT NULL DEFAULT 0,
  `IndunCheckTime` datetime NULL DEFAULT NULL,
  `IndunCount` int NOT NULL DEFAULT 0,
  `app_char` int NOT NULL DEFAULT 0,
  `app_terms_agree` enum('false','true') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  `PSSTime` int NOT NULL DEFAULT 1800,
  PRIMARY KEY (`login`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for accounts_free_buff_shield
-- ----------------------------
DROP TABLE IF EXISTS `accounts_free_buff_shield`;
CREATE TABLE `accounts_free_buff_shield`  (
  `account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `favor_locked_time` int NOT NULL DEFAULT 0,
  `pccafe_favor_remain_count` int NOT NULL DEFAULT 0,
  `free_favor_remain_count` int NOT NULL DEFAULT 0,
  `event_favor_remain_count` int NOT NULL DEFAULT 0,
  `pccafe_reward_item_count` int NOT NULL DEFAULT 0,
  `reset_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`account_name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for accounts_pcmaster_golden
-- ----------------------------
DROP TABLE IF EXISTS `accounts_pcmaster_golden`;
CREATE TABLE `accounts_pcmaster_golden`  (
  `account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `index_id` int NOT NULL DEFAULT 0,
  `type` int NOT NULL DEFAULT 1,
  `grade` blob NULL,
  `remain_time` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`account_name`, `index_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for accounts_shop_limit
-- ----------------------------
DROP TABLE IF EXISTS `accounts_shop_limit`;
CREATE TABLE `accounts_shop_limit`  (
  `accountName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `buyShopId` int NOT NULL DEFAULT 0,
  `buyItemId` int NOT NULL DEFAULT 0,
  `buyCount` int NOT NULL DEFAULT 0,
  `buyTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `limitTerm` enum('WEEK','DAY','NONE') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'DAY',
  PRIMARY KEY (`accountName`, `buyShopId`, `buyItemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for adshop
-- ----------------------------
DROP TABLE IF EXISTS `adshop`;
CREATE TABLE `adshop`  (
  `account` varchar(13) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `name` varchar(13) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `sex` int NOT NULL,
  `type` int NOT NULL,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `heading` int NOT NULL,
  `map_id` int NOT NULL
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ai_user
-- ----------------------------
DROP TABLE IF EXISTS `ai_user`;
CREATE TABLE `ai_user`  (
  `name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `ai_type` enum('AI_BATTLE','COLOSEUM','HUNT','FISHING','TOWN_MOVE','SCARECROW_ATTACK') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'AI_BATTLE',
  `level` int NOT NULL DEFAULT 0,
  `class` enum('lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'crown',
  `gender` enum('MALE(0)','FEMALE(1)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'MALE(0)',
  `str` int NOT NULL DEFAULT 0,
  `con` int NOT NULL DEFAULT 0,
  `dex` int NOT NULL DEFAULT 0,
  `inti` int NOT NULL DEFAULT 0,
  `wis` int NOT NULL DEFAULT 0,
  `cha` int NOT NULL DEFAULT 0,
  `alignment` int NOT NULL DEFAULT 0,
  `hit` int NOT NULL DEFAULT 0,
  `bow_hit` int NOT NULL DEFAULT 0,
  `dmg` int NOT NULL DEFAULT 0,
  `bow_dmg` int NOT NULL DEFAULT 0,
  `reduction` int NOT NULL DEFAULT 0,
  `skill_hit` int NOT NULL DEFAULT 0,
  `spirit_hit` int NOT NULL DEFAULT 0,
  `dragon_hit` int NOT NULL DEFAULT 0,
  `magic_hit` int NOT NULL DEFAULT 0,
  `fear_hit` int NOT NULL DEFAULT 0,
  `skill_regist` int NOT NULL DEFAULT 0,
  `spirit_regist` int NOT NULL DEFAULT 0,
  `dragon_regist` int NOT NULL DEFAULT 0,
  `fear_regist` int NOT NULL DEFAULT 0,
  `dg` int NOT NULL DEFAULT 0,
  `er` int NOT NULL DEFAULT 0,
  `me` int NOT NULL DEFAULT 0,
  `mr` int NOT NULL DEFAULT 0,
  `hp` int NOT NULL DEFAULT 0,
  `mp` int NOT NULL DEFAULT 0,
  `hpr` int NOT NULL DEFAULT 0,
  `mpr` int NOT NULL DEFAULT 0,
  `title` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `clanId` int NOT NULL DEFAULT 0,
  `clanname` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `elfAttr` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`name`, `ai_type`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ai_user_buff
-- ----------------------------
DROP TABLE IF EXISTS `ai_user_buff`;
CREATE TABLE `ai_user_buff`  (
  `class` enum('lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'crown',
  `elfAttr` int NOT NULL DEFAULT 0,
  `buff` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`class`, `elfAttr`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ai_user_drop
-- ----------------------------
DROP TABLE IF EXISTS `ai_user_drop`;
CREATE TABLE `ai_user_drop`  (
  `class` enum('lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown','all') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'all',
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NOT NULL DEFAULT 1,
  `chance` int NOT NULL DEFAULT 100,
  PRIMARY KEY (`class`, `itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ai_user_fish
-- ----------------------------
DROP TABLE IF EXISTS `ai_user_fish`;
CREATE TABLE `ai_user_fish`  (
  `loc_x` int NOT NULL DEFAULT 0,
  `loc_y` int NOT NULL DEFAULT 0,
  `heading` int NOT NULL DEFAULT 0,
  `fish_x` int NOT NULL DEFAULT 0,
  `fish_y` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`loc_x`, `loc_y`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ai_user_item
-- ----------------------------
DROP TABLE IF EXISTS `ai_user_item`;
CREATE TABLE `ai_user_item`  (
  `class` enum('lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown','all') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'all',
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NOT NULL DEFAULT 1,
  `enchantLevel` int NOT NULL DEFAULT 0,
  `attrLevel` int NOT NULL DEFAULT 0,
  `equip` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`class`, `itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ai_user_ment
-- ----------------------------
DROP TABLE IF EXISTS `ai_user_ment`;
CREATE TABLE `ai_user_ment`  (
  `id` int NOT NULL,
  `ment` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `type` enum('login','logout','kill','death') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'login',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ai_user_skill
-- ----------------------------
DROP TABLE IF EXISTS `ai_user_skill`;
CREATE TABLE `ai_user_skill`  (
  `class` enum('lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'crown',
  `active` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `passive` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`class`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_alim_log
-- ----------------------------
DROP TABLE IF EXISTS `app_alim_log`;
CREATE TABLE `app_alim_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `logContent` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `type` int NOT NULL DEFAULT 0,
  `insertTime` datetime NULL DEFAULT NULL,
  `status` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`account_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_auth_extension
-- ----------------------------
DROP TABLE IF EXISTS `app_auth_extension`;
CREATE TABLE `app_auth_extension`  (
  `extension` varchar(5) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`extension`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_board_content
-- ----------------------------
DROP TABLE IF EXISTS `app_board_content`;
CREATE TABLE `app_board_content`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'NULL',
  `date` datetime NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `content` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `readcount` int NULL DEFAULT 0,
  `chatype` int NULL DEFAULT 0,
  `chasex` int NULL DEFAULT 0,
  `likenames` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `mainImg` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `top` enum('true','false') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_board_content_comment
-- ----------------------------
DROP TABLE IF EXISTS `app_board_content_comment`;
CREATE TABLE `app_board_content_comment`  (
  `id` int NOT NULL DEFAULT 0,
  `boardId` int NOT NULL DEFAULT 0,
  `name` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `chaType` int NOT NULL DEFAULT 0,
  `chaSex` int NOT NULL DEFAULT 0,
  `date` datetime NOT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `likenames` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_board_free
-- ----------------------------
DROP TABLE IF EXISTS `app_board_free`;
CREATE TABLE `app_board_free`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'NULL',
  `date` datetime NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `content` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `readcount` int NULL DEFAULT 0,
  `chatype` int NULL DEFAULT 0,
  `chasex` int NULL DEFAULT 0,
  `likenames` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `mainImg` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_board_free_comment
-- ----------------------------
DROP TABLE IF EXISTS `app_board_free_comment`;
CREATE TABLE `app_board_free_comment`  (
  `id` int NOT NULL DEFAULT 0,
  `boardId` int NOT NULL DEFAULT 0,
  `name` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `chaType` int NOT NULL DEFAULT 0,
  `chaSex` int NOT NULL DEFAULT 0,
  `date` datetime NOT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `likenames` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_board_notice
-- ----------------------------
DROP TABLE IF EXISTS `app_board_notice`;
CREATE TABLE `app_board_notice`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'NULL',
  `date` datetime NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'NULL',
  `content` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `readcount` int NULL DEFAULT 0,
  `type` int NULL DEFAULT 0,
  `top` enum('false','true') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  `mainImg` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_board_pitch
-- ----------------------------
DROP TABLE IF EXISTS `app_board_pitch`;
CREATE TABLE `app_board_pitch`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT 'NULL',
  `date` datetime NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `content` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `readcount` int NULL DEFAULT 0,
  `chatype` int NULL DEFAULT 0,
  `chasex` int NULL DEFAULT 0,
  `likenames` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `mainImg` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `top` enum('true','false') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_board_pitch_comment
-- ----------------------------
DROP TABLE IF EXISTS `app_board_pitch_comment`;
CREATE TABLE `app_board_pitch_comment`  (
  `id` int NOT NULL DEFAULT 0,
  `boardId` int NOT NULL DEFAULT 0,
  `name` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `chaType` int NOT NULL DEFAULT 0,
  `chaSex` int NOT NULL DEFAULT 0,
  `date` datetime NOT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `likenames` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_coupon
-- ----------------------------
DROP TABLE IF EXISTS `app_coupon`;
CREATE TABLE `app_coupon`  (
  `number` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `type` enum('VOUCHER','NCOIN','NPOINT') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NCOIN',
  `price` int NOT NULL DEFAULT 0,
  `status` enum('false','true') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  `useAccount` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `createTime` datetime NOT NULL,
  `useTime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`number`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_customer
-- ----------------------------
DROP TABLE IF EXISTS `app_customer`;
CREATE TABLE `app_customer`  (
  `id` int NOT NULL DEFAULT 0,
  `login` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `type` int NOT NULL DEFAULT 1,
  `title` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `status` enum('Submitted','Answered') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'Submitted',
  `date` datetime NOT NULL,
  `comment` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `commentDate` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_customer_normal
-- ----------------------------
DROP TABLE IF EXISTS `app_customer_normal`;
CREATE TABLE `app_customer_normal`  (
  `id` int NOT NULL DEFAULT 0,
  `title` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_dictionary_item
-- ----------------------------
DROP TABLE IF EXISTS `app_dictionary_item`;
CREATE TABLE `app_dictionary_item`  (
  `schar` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`schar`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_engine_log
-- ----------------------------
DROP TABLE IF EXISTS `app_engine_log`;
CREATE TABLE `app_engine_log`  (
  `id` int NOT NULL DEFAULT 0,
  `account` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `engine` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_extra_info
-- ----------------------------
DROP TABLE IF EXISTS `app_extra_info`;
CREATE TABLE `app_extra_info`  (
  `extra_info_id` int NOT NULL AUTO_INCREMENT,
  `extra_info_type` enum('ITEM','NPC','SKILL','MAP') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `extra_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`extra_info_id`) USING BTREE,
  UNIQUE INDEX `idx_extra_info_name`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 30104 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_guide
-- ----------------------------
DROP TABLE IF EXISTS `app_guide`;
CREATE TABLE `app_guide`  (
  `id` int NOT NULL DEFAULT 0,
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `content` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_guide_boss
-- ----------------------------
DROP TABLE IF EXISTS `app_guide_boss`;
CREATE TABLE `app_guide_boss`  (
  `id` int NOT NULL DEFAULT 0,
  `loc` int NOT NULL DEFAULT 0,
  `locName` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `number` int NOT NULL DEFAULT 0,
  `bossName` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `bossImg` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `spawnLoc` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `spawnTime` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `dropName` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_guide_recommend
-- ----------------------------
DROP TABLE IF EXISTS `app_guide_recommend`;
CREATE TABLE `app_guide_recommend`  (
  `id` int NOT NULL DEFAULT 0,
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `content` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `url` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `img` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_item_search
-- ----------------------------
DROP TABLE IF EXISTS `app_item_search`;
CREATE TABLE `app_item_search`  (
  `seq` int NOT NULL AUTO_INCREMENT,
  `item_name` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `item_keyword` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`seq`, `item_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 155 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_nshop
-- ----------------------------
DROP TABLE IF EXISTS `app_nshop`;
CREATE TABLE `app_nshop`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `itemid` int NOT NULL DEFAULT 0,
  `itemname` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `price` int NOT NULL DEFAULT 0,
  `price_type` enum('NCOIN','NPOINT') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'NCOIN',
  `saved_point` int NOT NULL DEFAULT 0,
  `pack` int NOT NULL DEFAULT 0,
  `enchant` int NOT NULL DEFAULT 0,
  `limitCount` int NOT NULL DEFAULT 50,
  `flag` enum('NONE','DISCOUNT','ESSENTIAL','HOT','LIMIT','LIMIT_MONTH','LIMIT_WEEK','NEW','REDKNIGHT') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'NONE',
  `iteminfo` varchar(700) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_page_info
-- ----------------------------
DROP TABLE IF EXISTS `app_page_info`;
CREATE TABLE `app_page_info`  (
  `uri` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `path` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `className` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `cnbType` int NOT NULL DEFAULT 0,
  `cnbSubType` int NOT NULL DEFAULT 0,
  `needIngame` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `needLauncher` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `needLogin` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `needGm` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `Json` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `fileUpload` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`uri`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_powerbook
-- ----------------------------
DROP TABLE IF EXISTS `app_powerbook`;
CREATE TABLE `app_powerbook`  (
  `id` int NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'NULL',
  `content` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `mainImg` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `main` enum('true','false') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  `guideMain` enum('true','false') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_powerbook_guide
-- ----------------------------
DROP TABLE IF EXISTS `app_powerbook_guide`;
CREATE TABLE `app_powerbook_guide`  (
  `group_type` enum('1. Beginner''s Guide','2. Classes','3. Items','4. Magic','5. Magic Dolls','6. Monsters & Dungeons','7. Party Content','8. World Content','9. Combat System','10. Community','11. Main Systems','12. Service') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '1. Beginner\'s Guide',
  `id` int NOT NULL DEFAULT 0,
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `uri` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `is_new` enum('true','false') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`group_type`, `id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_promotion
-- ----------------------------
DROP TABLE IF EXISTS `app_promotion`;
CREATE TABLE `app_promotion`  (
  `id` int NOT NULL DEFAULT 0,
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `subText` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `promotionDate` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `targetLink` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `promotionImg` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `listallImg` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_report
-- ----------------------------
DROP TABLE IF EXISTS `app_report`;
CREATE TABLE `app_report`  (
  `id` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `targetName` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `type` enum('Sexual','Abuse','Advertisement','Illegal Program','Content','Privacy','Lies','Other') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'Sexual',
  `log` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_shop_rank
-- ----------------------------
DROP TABLE IF EXISTS `app_shop_rank`;
CREATE TABLE `app_shop_rank`  (
  `group_type` enum('1.All','2.Weapon','3.Armor','4.Accessory','5.Other') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '1.All',
  `shop_type` enum('1.Sell','2.Buy') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '1.Sell',
  `id` int NOT NULL DEFAULT 0,
  `item_id` int NOT NULL DEFAULT 0,
  `enchant` int NOT NULL DEFAULT 0,
  `search_rank` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`group_type`, `shop_type`, `id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_support
-- ----------------------------
DROP TABLE IF EXISTS `app_support`;
CREATE TABLE `app_support`  (
  `id` int NOT NULL DEFAULT 0,
  `account_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `character_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `pay_amount` int NOT NULL DEFAULT 0,
  `write_date` datetime NULL DEFAULT NULL,
  `status` enum('STANBY','FINISH') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'STANBY',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_support_message
-- ----------------------------
DROP TABLE IF EXISTS `app_support_message`;
CREATE TABLE `app_support_message`  (
  `type` enum('AGREE','PROGRESS','REWARD') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'AGREE',
  `index_id` int NOT NULL DEFAULT 1,
  `content` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`type`, `index_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_support_request
-- ----------------------------
DROP TABLE IF EXISTS `app_support_request`;
CREATE TABLE `app_support_request`  (
  `id` int NOT NULL DEFAULT 0,
  `account_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `character_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `request_date` datetime NOT NULL,
  `response` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `response_date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_trade
-- ----------------------------
DROP TABLE IF EXISTS `app_trade`;
CREATE TABLE `app_trade`  (
  `id` int NOT NULL DEFAULT 0,
  `title` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `content` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `bank` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `bankNumber` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `status` enum('SELL','IN_PROGRESS','COMPLETED','CANCELLED') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'SELL',
  `sellerName` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `sellerCharacter` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `sellerPhone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `buyerName` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `buyerCharacter` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `buyerPhone` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `writeTime` datetime NOT NULL,
  `send` enum('false','true') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  `receive` enum('false','true') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  `completeTime` datetime NULL DEFAULT NULL,
  `sellerCancle` enum('false','true') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  `buyerCancle` enum('false','true') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  `top` enum('true','false') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_uri_block
-- ----------------------------
DROP TABLE IF EXISTS `app_uri_block`;
CREATE TABLE `app_uri_block`  (
  `uri` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`uri`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for app_uri_pass
-- ----------------------------
DROP TABLE IF EXISTS `app_uri_pass`;
CREATE TABLE `app_uri_pass`  (
  `uri` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`uri`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for area
-- ----------------------------
DROP TABLE IF EXISTS `area`;
CREATE TABLE `area`  (
  `areaid` int NOT NULL DEFAULT 0,
  `mapid` int NOT NULL DEFAULT 0,
  `areaname` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `x1` int NOT NULL DEFAULT 0,
  `y1` int NOT NULL DEFAULT 0,
  `x2` int NOT NULL DEFAULT 0,
  `y2` int NOT NULL DEFAULT 0,
  `flag` int NOT NULL DEFAULT 0,
  `restart` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`areaid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for armor
-- ----------------------------
DROP TABLE IF EXISTS `armor`;
CREATE TABLE `armor`  (
  `item_id` int NOT NULL DEFAULT 0,
  `item_name_id` int NOT NULL DEFAULT 0,
  `desc_kr` varchar(70) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'NULL',
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_powerbook` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `note` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_id` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `itemGrade` enum('ONLY','MYTH','LEGEND','HERO','RARE','ADVANC','NORMAL') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NORMAL',
  `type` enum('NONE','HELMET','ARMOR','T_SHIRT','CLOAK','GLOVE','BOOTS','SHIELD','AMULET','RING','BELT','RING_2','EARRING','GARDER','RON','PAIR','SENTENCE','SHOULDER','BADGE','PENDANT') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `grade` int NOT NULL DEFAULT 0,
  `material` enum('NONE(-)','LIQUID(액체)','WAX(밀랍)','VEGGY(식물성)','FLESH(동물성)','PAPER(종이)','CLOTH(천)','LEATHER(가죽)','WOOD(나무)','BONE(뼈)','DRAGON_HIDE(용비늘)','IRON(철)','METAL(금속)','COPPER(구리)','SILVER(은)','GOLD(금)','PLATINUM(백금)','MITHRIL(미스릴)','PLASTIC(블랙미스릴)','GLASS(유리)','GEMSTONE(보석)','MINERAL(광석)','ORIHARUKON(오리하루콘)','DRANIUM(드라니움)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(-)',
  `weight` int UNSIGNED NOT NULL DEFAULT 0,
  `iconId` int UNSIGNED NOT NULL DEFAULT 0,
  `spriteId` int UNSIGNED NOT NULL DEFAULT 0,
  `ac` int NOT NULL DEFAULT 0,
  `ac_sub` int NOT NULL DEFAULT 0,
  `safenchant` int NOT NULL DEFAULT 0,
  `use_royal` int UNSIGNED NOT NULL DEFAULT 0,
  `use_knight` int UNSIGNED NOT NULL DEFAULT 0,
  `use_mage` int UNSIGNED NOT NULL DEFAULT 0,
  `use_elf` int UNSIGNED NOT NULL DEFAULT 0,
  `use_darkelf` int UNSIGNED NOT NULL DEFAULT 0,
  `use_dragonknight` int UNSIGNED NOT NULL DEFAULT 0,
  `use_illusionist` int UNSIGNED NOT NULL DEFAULT 0,
  `use_warrior` int UNSIGNED NOT NULL DEFAULT 0,
  `use_fencer` int UNSIGNED NOT NULL DEFAULT 0,
  `use_lancer` int UNSIGNED NOT NULL DEFAULT 0,
  `add_str` int NOT NULL DEFAULT 0,
  `add_con` int NOT NULL DEFAULT 0,
  `add_dex` int NOT NULL DEFAULT 0,
  `add_int` int NOT NULL DEFAULT 0,
  `add_wis` int NOT NULL DEFAULT 0,
  `add_cha` int NOT NULL DEFAULT 0,
  `add_hp` int NOT NULL DEFAULT 0,
  `add_mp` int NOT NULL DEFAULT 0,
  `add_hpr` int NOT NULL DEFAULT 0,
  `add_mpr` int NOT NULL DEFAULT 0,
  `add_sp` int NOT NULL DEFAULT 0,
  `min_lvl` int UNSIGNED NOT NULL DEFAULT 0,
  `max_lvl` int UNSIGNED NOT NULL DEFAULT 0,
  `m_def` int NOT NULL DEFAULT 0,
  `haste_item` int UNSIGNED NOT NULL DEFAULT 0,
  `carryBonus` int UNSIGNED NOT NULL DEFAULT 0,
  `hit_rate` int NOT NULL DEFAULT 0,
  `dmg_rate` int NOT NULL DEFAULT 0,
  `bow_hit_rate` int NOT NULL DEFAULT 0,
  `bow_dmg_rate` int NOT NULL DEFAULT 0,
  `bless` int UNSIGNED NOT NULL DEFAULT 1,
  `trade` int UNSIGNED NOT NULL DEFAULT 0,
  `retrieve` int UNSIGNED NOT NULL DEFAULT 0,
  `specialretrieve` int UNSIGNED NOT NULL DEFAULT 0,
  `retrieveEnchant` int NOT NULL DEFAULT 0,
  `cant_delete` int UNSIGNED NOT NULL DEFAULT 0,
  `cant_sell` int UNSIGNED NOT NULL DEFAULT 0,
  `max_use_time` int NOT NULL DEFAULT 0,
  `defense_water` int NOT NULL DEFAULT 0,
  `defense_wind` int NOT NULL DEFAULT 0,
  `defense_fire` int NOT NULL DEFAULT 0,
  `defense_earth` int NOT NULL DEFAULT 0,
  `attr_all` int NOT NULL DEFAULT 0,
  `regist_stone` int NOT NULL DEFAULT 0,
  `regist_sleep` int NOT NULL DEFAULT 0,
  `regist_freeze` int NOT NULL DEFAULT 0,
  `regist_blind` int NOT NULL DEFAULT 0,
  `regist_skill` int NOT NULL DEFAULT 0,
  `regist_spirit` int NOT NULL DEFAULT 0,
  `regist_dragon` int NOT NULL DEFAULT 0,
  `regist_fear` int NOT NULL DEFAULT 0,
  `regist_all` int NOT NULL DEFAULT 0,
  `hitup_skill` int NOT NULL DEFAULT 0,
  `hitup_spirit` int NOT NULL DEFAULT 0,
  `hitup_dragon` int NOT NULL DEFAULT 0,
  `hitup_fear` int NOT NULL DEFAULT 0,
  `hitup_all` int NOT NULL DEFAULT 0,
  `hitup_magic` int NOT NULL DEFAULT 0,
  `damage_reduction` int NOT NULL DEFAULT 0,
  `MagicDamageReduction` int NOT NULL DEFAULT 0,
  `reductionEgnor` int NOT NULL DEFAULT 0,
  `reductionPercent` int NOT NULL DEFAULT 0,
  `PVPDamage` int NOT NULL DEFAULT 0,
  `PVPDamageReduction` int NOT NULL DEFAULT 0,
  `PVPDamageReductionPercent` int NOT NULL DEFAULT 0,
  `PVPMagicDamageReduction` int NOT NULL DEFAULT 0,
  `PVPReductionEgnor` int NOT NULL DEFAULT 0,
  `PVPMagicDamageReductionEgnor` int NOT NULL DEFAULT 0,
  `abnormalStatusDamageReduction` int NOT NULL DEFAULT 0,
  `abnormalStatusPVPDamageReduction` int NOT NULL DEFAULT 0,
  `PVPDamagePercent` int NOT NULL DEFAULT 0,
  `expBonus` int NOT NULL DEFAULT 0,
  `rest_exp_reduce_efficiency` int NOT NULL DEFAULT 0,
  `shortCritical` int NOT NULL DEFAULT 0,
  `longCritical` int NOT NULL DEFAULT 0,
  `magicCritical` int NOT NULL DEFAULT 0,
  `addDg` int NOT NULL DEFAULT 0,
  `addEr` int NOT NULL DEFAULT 0,
  `addMe` int NOT NULL DEFAULT 0,
  `poisonRegist` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `imunEgnor` int NOT NULL DEFAULT 0,
  `stunDuration` int NOT NULL DEFAULT 0,
  `tripleArrowStun` int NOT NULL DEFAULT 0,
  `strangeTimeIncrease` int NOT NULL DEFAULT 0,
  `strangeTimeDecrease` int NOT NULL DEFAULT 0,
  `potionRegist` int NOT NULL DEFAULT 0,
  `potionPercent` int NOT NULL DEFAULT 0,
  `potionValue` int NOT NULL DEFAULT 0,
  `hprAbsol32Second` int NOT NULL DEFAULT 0,
  `mprAbsol64Second` int NOT NULL DEFAULT 0,
  `mprAbsol16Second` int NOT NULL DEFAULT 0,
  `hpPotionDelayDecrease` int NOT NULL DEFAULT 0,
  `hpPotionCriticalProb` int NOT NULL DEFAULT 0,
  `increaseArmorSkillProb` int NOT NULL DEFAULT 0,
  `attackSpeedDelayRate` int NOT NULL DEFAULT 0,
  `moveSpeedDelayRate` int NOT NULL DEFAULT 0,
  `MainId` int NOT NULL DEFAULT 0,
  `MainId2` int NOT NULL DEFAULT 0,
  `MainId3` int NOT NULL DEFAULT 0,
  `Set_Id` int NOT NULL DEFAULT 0,
  `polyDescId` int NOT NULL DEFAULT 0,
  `Magic_name` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for armor_set
-- ----------------------------
DROP TABLE IF EXISTS `armor_set`;
CREATE TABLE `armor_set`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `note` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `sets` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `polyid` int NOT NULL DEFAULT 0,
  `min_enchant` int NOT NULL DEFAULT 0,
  `ac` int NOT NULL DEFAULT 0,
  `hp` int NOT NULL DEFAULT 0,
  `mp` int NOT NULL DEFAULT 0,
  `hpr` int NOT NULL DEFAULT 0,
  `mpr` int NOT NULL DEFAULT 0,
  `mr` int NOT NULL DEFAULT 0,
  `str` int NOT NULL DEFAULT 0,
  `dex` int NOT NULL DEFAULT 0,
  `con` int NOT NULL DEFAULT 0,
  `wis` int NOT NULL DEFAULT 0,
  `cha` int NOT NULL DEFAULT 0,
  `intl` int NOT NULL DEFAULT 0,
  `shorthitup` int NOT NULL DEFAULT 0,
  `shortdmgup` int NOT NULL DEFAULT 0,
  `shortCritical` int NOT NULL DEFAULT 0,
  `longhitup` int NOT NULL DEFAULT 0,
  `longdmgup` int NOT NULL DEFAULT 0,
  `longCritical` int NOT NULL DEFAULT 0,
  `sp` int NOT NULL DEFAULT 0,
  `magichitup` int NOT NULL DEFAULT 0,
  `magicCritical` int NOT NULL DEFAULT 0,
  `earth` int NOT NULL DEFAULT 0,
  `fire` int NOT NULL DEFAULT 0,
  `wind` int NOT NULL DEFAULT 0,
  `water` int NOT NULL DEFAULT 0,
  `reduction` int NOT NULL DEFAULT 0,
  `reductionEgnor` int NOT NULL DEFAULT 0,
  `magicReduction` int NOT NULL DEFAULT 0,
  `PVPDamage` int NOT NULL DEFAULT 0,
  `PVPDamageReduction` int NOT NULL DEFAULT 0,
  `PVPMagicDamageReduction` int NOT NULL DEFAULT 0,
  `PVPReductionEgnor` int NOT NULL DEFAULT 0,
  `PVPMagicDamageReductionEgnor` int NOT NULL DEFAULT 0,
  `abnormalStatusDamageReduction` int NOT NULL DEFAULT 0,
  `abnormalStatusPVPDamageReduction` int NOT NULL DEFAULT 0,
  `PVPDamagePercent` int NOT NULL DEFAULT 0,
  `expBonus` int NOT NULL DEFAULT 0,
  `rest_exp_reduce_efficiency` int NOT NULL DEFAULT 0,
  `dg` int NOT NULL DEFAULT 0,
  `er` int NOT NULL DEFAULT 0,
  `me` int NOT NULL DEFAULT 0,
  `toleranceSkill` int NOT NULL DEFAULT 0,
  `toleranceSpirit` int NOT NULL DEFAULT 0,
  `toleranceDragon` int NOT NULL DEFAULT 0,
  `toleranceFear` int NOT NULL DEFAULT 0,
  `toleranceAll` int NOT NULL DEFAULT 0,
  `hitupSkill` int NOT NULL DEFAULT 0,
  `hitupSpirit` int NOT NULL DEFAULT 0,
  `hitupDragon` int NOT NULL DEFAULT 0,
  `hitupFear` int NOT NULL DEFAULT 0,
  `hitupAll` int NOT NULL DEFAULT 0,
  `strangeTimeIncrease` int NOT NULL DEFAULT 0,
  `underWater` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 159 CHARACTER SET = euckr COLLATE = euckr_korean_ci COMMENT = 'MyISAM free: 10240 kB' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for attendance_accounts
-- ----------------------------
DROP TABLE IF EXISTS `attendance_accounts`;
CREATE TABLE `attendance_accounts`  (
  `account` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `dailyCount` int NOT NULL DEFAULT 0,
  `isCompleted` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `resetDate` datetime NULL DEFAULT NULL,
  `groupData` blob NULL,
  `groupOpen` blob NULL,
  `randomItems` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `rewardHistory` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`account`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for attendance_item
-- ----------------------------
DROP TABLE IF EXISTS `attendance_item`;
CREATE TABLE `attendance_item`  (
  `groupType` int NOT NULL DEFAULT 0,
  `index` int NOT NULL DEFAULT 0,
  `item_id` int NOT NULL DEFAULT 0,
  `item_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `enchant` int NULL DEFAULT 0,
  `count` int NULL DEFAULT 1,
  `broadcast` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `bonus_type` enum('RandomDiceItem(3)','GiveItem(2)','UseItem(1)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'GiveItem(2)',
  PRIMARY KEY (`groupType`, `index`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for attendance_item_random
-- ----------------------------
DROP TABLE IF EXISTS `attendance_item_random`;
CREATE TABLE `attendance_item_random`  (
  `groupType` int NOT NULL DEFAULT 0,
  `index` int NOT NULL DEFAULT 0,
  `itemId` int NOT NULL DEFAULT 0,
  `itemName` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_kr` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `count` int NOT NULL DEFAULT 1,
  `broadcast` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `level` enum('1','2','3','4','5') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`groupType`, `index`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for auth_ip
-- ----------------------------
DROP TABLE IF EXISTS `auth_ip`;
CREATE TABLE `auth_ip`  (
  `ip` varchar(15) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`ip`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for autoloot
-- ----------------------------
DROP TABLE IF EXISTS `autoloot`;
CREATE TABLE `autoloot`  (
  `item_id` int NOT NULL DEFAULT 0,
  `note` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for balance
-- ----------------------------
DROP TABLE IF EXISTS `balance`;
CREATE TABLE `balance`  (
  `attackerType` enum('npc','lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'crown',
  `targetType` enum('npc','lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'crown',
  `physicalDmg` int NOT NULL DEFAULT 0,
  `physicalHit` int NOT NULL DEFAULT 0,
  `physicalReduction` int NOT NULL DEFAULT 0,
  `magicDmg` int NOT NULL DEFAULT 0,
  `magicHit` int NOT NULL DEFAULT 0,
  `magicReduction` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`attackerType`, `targetType`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ban_account
-- ----------------------------
DROP TABLE IF EXISTS `ban_account`;
CREATE TABLE `ban_account`  (
  `account` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `reason` enum('BUG_ABOUS','CHAT_ABOUS','CHEAT','ETC') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'ETC',
  `counter` int NOT NULL DEFAULT 1,
  `limitTime` datetime NOT NULL,
  PRIMARY KEY (`account`, `reason`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ban_board
-- ----------------------------
DROP TABLE IF EXISTS `ban_board`;
CREATE TABLE `ban_board`  (
  `number` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `account` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `registTime` datetime NOT NULL,
  PRIMARY KEY (`number`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ban_hdd
-- ----------------------------
DROP TABLE IF EXISTS `ban_hdd`;
CREATE TABLE `ban_hdd`  (
  `number` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `account` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `registTime` datetime NOT NULL,
  PRIMARY KEY (`number`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ban_ip
-- ----------------------------
DROP TABLE IF EXISTS `ban_ip`;
CREATE TABLE `ban_ip`  (
  `address` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `reason` enum('CONNECTION_OVER','PACKET_ATTACK','BAD_USER','UNSUAL_REQUEST','WEB_URI_LENGTH_OVER','WEB_REQUEST_OVER','SERVER_SLANDER','WELLKNOWN_PORT','BUG_ABOUS','CHEAT','ETC','WEB_ATTACK_REQUEST','WEB_NOT_AUTH_IP') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'ETC',
  `registTime` datetime NOT NULL,
  PRIMARY KEY (`address`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for beginner
-- ----------------------------
DROP TABLE IF EXISTS `beginner`;
CREATE TABLE `beginner`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL DEFAULT 0,
  `count` int NOT NULL DEFAULT 0,
  `charge_count` int NOT NULL DEFAULT 0,
  `enchantlvl` int NOT NULL DEFAULT 0,
  `item_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `activate` enum('A','P','K','E','W','D','T','B','J','F','L') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for beginner_addteleport
-- ----------------------------
DROP TABLE IF EXISTS `beginner_addteleport`;
CREATE TABLE `beginner_addteleport`  (
  `id` int UNSIGNED NOT NULL,
  `num_id` int UNSIGNED NOT NULL DEFAULT 0,
  `speed_id` int NOT NULL DEFAULT -1,
  `char_id` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  `randomX` int UNSIGNED NOT NULL DEFAULT 0,
  `randomY` int UNSIGNED NOT NULL DEFAULT 0,
  `item_obj_id` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`char_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for beginner_box
-- ----------------------------
DROP TABLE IF EXISTS `beginner_box`;
CREATE TABLE `beginner_box`  (
  `itemid` int NOT NULL,
  `count` int NOT NULL DEFAULT 0,
  `enchantlvl` int NOT NULL DEFAULT 0,
  `item_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `bless` int NOT NULL DEFAULT 1,
  `activate` enum('lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown','all') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'all'
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for beginner_quest
-- ----------------------------
DROP TABLE IF EXISTS `beginner_quest`;
CREATE TABLE `beginner_quest`  (
  `quest_id` int NOT NULL,
  `note` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `use` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  `auto_complete` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `fastLevel` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`quest_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for beginner_quest_drop
-- ----------------------------
DROP TABLE IF EXISTS `beginner_quest_drop`;
CREATE TABLE `beginner_quest_drop`  (
  `classId` int NOT NULL DEFAULT 0,
  `desc` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `mainQuestId` int NOT NULL DEFAULT 0,
  `mainItemNameId` int NOT NULL DEFAULT 0,
  `subQuestId` int NOT NULL DEFAULT 0,
  `subItemNameId` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`classId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for beginner_teleport
-- ----------------------------
DROP TABLE IF EXISTS `beginner_teleport`;
CREATE TABLE `beginner_teleport`  (
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_armor_element_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_armor_element_common`;
CREATE TABLE `bin_armor_element_common`  (
  `type` int NOT NULL DEFAULT 0,
  `enchant` int NOT NULL DEFAULT 0,
  `fr` int NOT NULL DEFAULT 0,
  `wr` int NOT NULL DEFAULT 0,
  `ar` int NOT NULL DEFAULT 0,
  `er` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`type`, `enchant`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_catalyst_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_catalyst_common`;
CREATE TABLE `bin_catalyst_common`  (
  `nameId` int NOT NULL DEFAULT 0,
  `nameId_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `input` int NOT NULL DEFAULT 0,
  `input_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `output` int NOT NULL DEFAULT 0,
  `output_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `successProb` int NOT NULL DEFAULT 0,
  `rewardCount` int NOT NULL DEFAULT 0,
  `preserveProb` int NOT NULL DEFAULT 0,
  `failOutput` int NOT NULL DEFAULT 0,
  `failOutput_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`nameId`, `input`, `output`, `failOutput`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_charged_time_map_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_charged_time_map_common`;
CREATE TABLE `bin_charged_time_map_common`  (
  `id` int NOT NULL DEFAULT 0,
  `groups` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `multi_group_list` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_companion_class_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_companion_class_common`;
CREATE TABLE `bin_companion_class_common`  (
  `classId` int NOT NULL DEFAULT 0,
  `class` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `category` enum('DOG_FIGHT(5)','WILD(4)','PET(3)','DEVINE_BEAST(2)','FIERCE_ANIMAL(1)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'FIERCE_ANIMAL(1)',
  `element` enum('LIGHT(5)','EARTH(4)','AIR(3)','WATER(2)','FIRE(1)','NONE(0)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(0)',
  `skill` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`classId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_companion_enchant_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_companion_enchant_common`;
CREATE TABLE `bin_companion_enchant_common`  (
  `tier` int NOT NULL,
  `enchantCost` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `openCost` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`tier`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_companion_skill_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_companion_skill_common`;
CREATE TABLE `bin_companion_skill_common`  (
  `id` int NOT NULL DEFAULT 0,
  `descNum` int NOT NULL DEFAULT 0,
  `descKr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `enchantBonus` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_companion_stat_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_companion_stat_common`;
CREATE TABLE `bin_companion_stat_common`  (
  `id` int NOT NULL DEFAULT 0,
  `statType` enum('INT(2)','CON(1)','STR(0)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'STR(0)',
  `value` int NOT NULL DEFAULT 0,
  `meleeDmg` int NOT NULL DEFAULT 0,
  `meleeHit` int NOT NULL DEFAULT 0,
  `regenHP` int NOT NULL DEFAULT 0,
  `ac` int NOT NULL DEFAULT 0,
  `spellDmg` int NOT NULL DEFAULT 0,
  `spellHit` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_craft_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_craft_common`;
CREATE TABLE `bin_craft_common`  (
  `craft_id` int NOT NULL DEFAULT 0,
  `desc_id` int NOT NULL DEFAULT 0,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `min_level` int NOT NULL DEFAULT 0,
  `max_level` int NOT NULL DEFAULT 0,
  `required_gender` int NOT NULL DEFAULT 0,
  `min_align` int NOT NULL DEFAULT 0,
  `max_align` int NOT NULL DEFAULT 0,
  `min_karma` int NOT NULL DEFAULT 0,
  `max_karma` int NOT NULL DEFAULT 0,
  `max_count` int NOT NULL DEFAULT 0,
  `is_show` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `PCCafeOnly` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `bmProbOpen` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `required_classes` int NOT NULL DEFAULT 0,
  `required_quests` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `required_sprites` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `required_items` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `inputs_arr_input_item` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `inputs_arr_option_item` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `outputs_success` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `outputs_failure` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `outputs_success_prob_by_million` int NOT NULL DEFAULT 0,
  `batch_delay_sec` int NOT NULL DEFAULT 0,
  `period_list` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `cur_successcount` int NOT NULL DEFAULT 0,
  `max_successcount` int NOT NULL DEFAULT 0,
  `except_npc` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `SuccessCountType` enum('World(0)','Account(1)','Character(2)','AllServers(3)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'World(0)',
  PRIMARY KEY (`craft_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_einpoint_cost_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_einpoint_cost_common`;
CREATE TABLE `bin_einpoint_cost_common`  (
  `value` int NOT NULL DEFAULT 0,
  `point` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`value`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_einpoint_faith_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_einpoint_faith_common`;
CREATE TABLE `bin_einpoint_faith_common`  (
  `GroupId` int NOT NULL DEFAULT 0,
  `spellId` int NOT NULL DEFAULT 0,
  `Index_indexId` int NOT NULL DEFAULT 0,
  `Index_spellId` int NOT NULL DEFAULT 0,
  `Index_cost` int NOT NULL DEFAULT 0,
  `Index_duration` int NOT NULL DEFAULT 0,
  `Index_additional_desc` int NOT NULL DEFAULT 0,
  `Index_additional_desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `additional_desc` int NOT NULL DEFAULT 0,
  `additional_desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `BuffInfo_tooltipStrId` int NOT NULL DEFAULT 0,
  `BuffInfo_tooltipStrId_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`GroupId`, `Index_indexId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_einpoint_meta_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_einpoint_meta_common`;
CREATE TABLE `bin_einpoint_meta_common`  (
  `index_id` int NOT NULL DEFAULT 0,
  `stat_type` enum('BLESS(0)','LUCKY(1)','VITAL(2)','ITEM_SPELL_PROB(3)','ABSOLUTE_REGEN(4)','POTION(5)','ATTACK_SPELL(6)','PVP_REDUCTION(7)','PVE_REDUCTION(8)','_MAX_(9)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '_MAX_(9)',
  `AbilityMetaData1_token` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `AbilityMetaData1_x100` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `AbilityMetaData1_unit` enum('None(1)','Percent(2)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'None(1)',
  `AbilityMetaData2_token` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `AbilityMetaData2_x100` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `AbilityMetaData2_unit` enum('None(1)','Percent(2)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'None(1)',
  PRIMARY KEY (`index_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_einpoint_normal_prob_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_einpoint_normal_prob_common`;
CREATE TABLE `bin_einpoint_normal_prob_common`  (
  `Normal_level` int NOT NULL DEFAULT 0,
  `prob` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`Normal_level`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_einpoint_overstat_prob_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_einpoint_overstat_prob_common`;
CREATE TABLE `bin_einpoint_overstat_prob_common`  (
  `over_level` int NOT NULL DEFAULT 0,
  `prob` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`over_level`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_einpoint_prob_table_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_einpoint_prob_table_common`;
CREATE TABLE `bin_einpoint_prob_table_common`  (
  `isLastChance` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `bonusPoint` int NOT NULL DEFAULT 0,
  `prob` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`isLastChance`, `bonusPoint`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_einpoint_stat_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_einpoint_stat_common`;
CREATE TABLE `bin_einpoint_stat_common`  (
  `index_id` int NOT NULL DEFAULT 0,
  `stat_type` enum('BLESS(0)','LUCKY(1)','VITAL(2)','ITEM_SPELL_PROB(3)','ABSOLUTE_REGEN(4)','POTION(5)','ATTACK_SPELL(6)','PVP_REDUCTION(7)','PVE_REDUCTION(8)','_MAX_(9)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '_MAX_(9)',
  `value` int NOT NULL DEFAULT 0,
  `Ability1_minIncValue` int NOT NULL DEFAULT 0,
  `Ability1_maxIncValue` int NOT NULL DEFAULT 0,
  `Ability2_minIncValue` int NOT NULL DEFAULT 0,
  `Ability2_maxIncValue` int NOT NULL DEFAULT 0,
  `StatMaxInfo_level` int NOT NULL DEFAULT 0,
  `StatMaxInfo_statMax` int NOT NULL DEFAULT 0,
  `eachStatMax` int NOT NULL DEFAULT 0,
  `totalStatMax` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`index_id`, `value`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_element_enchant_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_element_enchant_common`;
CREATE TABLE `bin_element_enchant_common`  (
  `prob_index` int NOT NULL DEFAULT 0,
  `type_index` int NOT NULL DEFAULT 0,
  `level` int NOT NULL DEFAULT 0,
  `increaseProb` int NOT NULL DEFAULT 0,
  `decreaseProb` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`prob_index`, `type_index`, `level`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_enchant_scroll_table_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_enchant_scroll_table_common`;
CREATE TABLE `bin_enchant_scroll_table_common`  (
  `enchantType` int NOT NULL DEFAULT 0,
  `nameid` int NOT NULL DEFAULT 0,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `targetEnchant` int NOT NULL DEFAULT 0,
  `noTargetMaterialList` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `target_category` enum('NONE(0)','WEAPON(1)','ARMOR(2)','ACCESSORY(3)','ELEMENT(4)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(0)',
  `isBmEnchantScroll` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `elementalType` int NOT NULL DEFAULT 0,
  `useBlesscodeScroll` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`enchantType`, `nameid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_enchant_table_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_enchant_table_common`;
CREATE TABLE `bin_enchant_table_common`  (
  `item_index` int NOT NULL DEFAULT 0,
  `bonusLevel_index` int NOT NULL DEFAULT 0,
  `enchantSuccessProb` int NOT NULL DEFAULT 0,
  `enchantTotalProb` int NOT NULL DEFAULT 0,
  `bmEnchantSuccessProb` int NOT NULL DEFAULT 0,
  `bmEnchantRemainProb` int NOT NULL DEFAULT 0,
  `bmEnchantFailDownProb` int NOT NULL DEFAULT 0,
  `bmEnchantTotalProb` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`item_index`, `bonusLevel_index`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_entermaps_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_entermaps_common`;
CREATE TABLE `bin_entermaps_common`  (
  `id` int NOT NULL DEFAULT 0,
  `action_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `number_id` int NOT NULL DEFAULT 0,
  `loc_x` int NOT NULL DEFAULT 0,
  `loc_y` int NOT NULL DEFAULT 0,
  `loc_range` int NOT NULL DEFAULT 0,
  `priority_id` int NOT NULL DEFAULT 0,
  `maxUser` int NOT NULL DEFAULT 0,
  `conditions` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `destinations` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`id`, `action_name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_favorbook_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_favorbook_common`;
CREATE TABLE `bin_favorbook_common`  (
  `category_id` int NOT NULL DEFAULT 0,
  `desc_id` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `start_date` datetime NULL DEFAULT NULL,
  `end_date` datetime NULL DEFAULT NULL,
  `sort` int NOT NULL DEFAULT 0,
  `slot_id` int NOT NULL DEFAULT 0,
  `state_infos` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `red_dot_notice` int NOT NULL DEFAULT 0,
  `default_display_item_id` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`category_id`, `slot_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_general_goods_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_general_goods_common`;
CREATE TABLE `bin_general_goods_common`  (
  `NameId` int NOT NULL DEFAULT 0,
  `NameId_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`NameId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_huntingquest_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_huntingquest_common`;
CREATE TABLE `bin_huntingquest_common`  (
  `maxQuestCount` int NOT NULL DEFAULT 0,
  `goalKillCount` int NOT NULL DEFAULT 0,
  `reset_HourOfDay` int NOT NULL DEFAULT -1,
  `reward_normal_ConditionalRewards` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `reward_normal_UsedItemID` int NOT NULL,
  `reward_normal_UsedAmount` int NOT NULL DEFAULT 0,
  `reward_dragon_ConditionalRewards` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `reward_dragon_UsedItemID` int NOT NULL DEFAULT 0,
  `reward_dragon_UsedAmount` int NOT NULL DEFAULT 0,
  `reward_hightdragon_ConditionalRewards` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `reward_hightdragon_UsedItemID` int NOT NULL DEFAULT 0,
  `reward_hightdragon_UsedAmount` int NOT NULL DEFAULT 0,
  `requiredCondition_MinLevel` int NOT NULL DEFAULT 0,
  `requiredCondition_MaxLevel` int NOT NULL DEFAULT 0,
  `requiredCondition_Map` int NOT NULL DEFAULT 0,
  `requiredCondition_LocationDesc` int NOT NULL DEFAULT 0,
  `enterMapID` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`requiredCondition_Map`, `requiredCondition_LocationDesc`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_indun_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_indun_common`;
CREATE TABLE `bin_indun_common`  (
  `mapKind` int NOT NULL DEFAULT 0,
  `keyItemId` int NOT NULL DEFAULT 0,
  `minPlayer` int NOT NULL DEFAULT 0,
  `maxPlayer` int NOT NULL DEFAULT 0,
  `minAdena` int NOT NULL DEFAULT 0,
  `maxAdena` int NOT NULL DEFAULT 0,
  `minLevel` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `bmkeyItemId` int NOT NULL DEFAULT 0,
  `eventKeyItemId` int NOT NULL DEFAULT 0,
  `dungeon_type` enum('UNDEFINED(0)','DEFENCE_TYPE(1)','DUNGEON_TYPE(2)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'UNDEFINED(0)',
  `enable_boost_mode` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`mapKind`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_item_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_item_common`;
CREATE TABLE `bin_item_common`  (
  `name_id` int NOT NULL DEFAULT 0,
  `icon_id` int NOT NULL DEFAULT 0,
  `sprite_id` int NOT NULL DEFAULT 0,
  `desc_id` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `real_desc` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `material` enum('DRANIUM(23)','ORIHARUKON(22)','MINERAL(21)','GEMSTONE(20)','GLASS(19)','PLASTIC(18)','MITHRIL(17)','PLATINUM(16)','GOLD(15)','SILVER(14)','COPPER(13)','METAL(12)','IRON(11)','DRAGON_HIDE(10)','BONE(9)','WOOD(8)','LEATHER(7)','CLOTH(6)','PAPER(5)','FLESH(4)','VEGGY(3)','WAX(2)','LIQUID(1)','NONE(0)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(0)',
  `weight_1000ea` int NOT NULL DEFAULT 0,
  `level_limit_min` int NOT NULL DEFAULT 0,
  `level_limit_max` int NOT NULL DEFAULT 0,
  `prince_permit` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `knight_permit` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `elf_permit` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `magician_permit` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `darkelf_permit` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `dragonknight_permit` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `illusionist_permit` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `warrior_permit` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `fencer_permit` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `lancer_permit` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `equip_bonus_list` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `interaction_type` int NOT NULL DEFAULT 0,
  `real_weight` int NOT NULL DEFAULT 0,
  `spell_range` int NOT NULL DEFAULT 0,
  `item_category` enum('WAND(1013)','AUTO_USED_BY_BUFF_ITEM(1012)','SPELL_EXTRACTOR(1011)','ARROW(1010)','POTION_MANA(1009)','LUCKY_BAG(1008)','WAND_CALL_LIGHTNING(1007)','ARMOR_SERIES_MAIN(1006)','ARMOR_SERIES(1005)','SCROLL(1004)','SCROLL_TELEPORT_HOME(1003)','SCROLL_TELEPORT_TOWN(1002)','POTION_HEAL(1001)','POTION(1000)','ITEM(23)','LIGHT(22)','FOOD(21)','ARMOR(19)','WEAPON(1)','NONE(0)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(0)',
  `body_part` enum('BODYPART_ALL(-1)','BP_PENDANT(33554432)','BP_INSIGNIA(16777216)','BP_PAULDRON(8388608)','BP_HERALDRY(4194304)','EXT_SLOTS(2097152)','RUNE(1048576)','L_WRIST(524288)','R_WRIST(262144)','BACK(131072)','L_SHOULDER(65536)','R_SHOULDER(32768)','EAR(16384)','WAIST(8192)','NECK(4096)','R_FINGER(2048)','L_FINGER(1024)','R_HOLD(512)','L_HOLD(256)','R_HAND(128)','L_HAND(64)','FOOT(32)','LEG(16)','CLOAK(8)','SHIRT(4)','TORSO(2)','HEAD(1)','NONE(0)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(0)',
  `ac` int NOT NULL DEFAULT 0,
  `extended_weapon_type` enum('WEAPON_EX_NOT_EQUIPPED(13)','WEAPON_EX_KIRINGKU(12)','WEAPON_EX_DOUBLE_AXE(11)','WEAPON_EX_CHAIN_SWORD(10)','WEAPON_EX_GAUNTLET(9)','WEAPON_EX_CRAW(8)','WEAPON_EX_DOUBLE_SWORD(7)','WEAPON_EX_LARGE_SWORD(6)','WEAPON_EX_DAGGER(5)','WEAPON_EX_STAFF(4)','WEAPON_EX_SPEAR(3)','WEAPON_EX_BOW(2)','WEAPON_EX_AXE(1)','WEAPON_EX_ONEHAND_SWORD(0)','NONE(-1)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(-1)',
  `large_damage` int NOT NULL DEFAULT 0,
  `small_damage` int NOT NULL DEFAULT 0,
  `hit_bonus` int NOT NULL DEFAULT 0,
  `damage_bonus` int NOT NULL DEFAULT 0,
  `armor_series_info` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `cost` int NOT NULL DEFAULT 0,
  `can_set_mage_enchant` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `merge` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `pss_event_item` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `market_searching_item` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `lucky_bag_reward_list` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `element_enchant_table` int NOT NULL DEFAULT 0,
  `accessory_enchant_table` int NOT NULL DEFAULT 0,
  `bm_prob_open` int NOT NULL DEFAULT 0,
  `enchant_type` int NOT NULL DEFAULT 0,
  `is_elven` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `forced_elemental_enchant` int NOT NULL DEFAULT 0,
  `max_enchant` int NOT NULL DEFAULT 0,
  `energy_lost` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `prob` int NOT NULL DEFAULT 0,
  `pss_heal_item` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `useInterval` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`name_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_ndl_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_ndl_common`;
CREATE TABLE `bin_ndl_common`  (
  `map_number` int NOT NULL DEFAULT 0,
  `npc_classId` int NOT NULL DEFAULT 0,
  `npc_desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `territory_startXY` int NOT NULL DEFAULT 0,
  `territory_endXY` int NOT NULL DEFAULT 0,
  `territory_location_desc` int NOT NULL DEFAULT 0,
  `territory_average_npc_value` int NOT NULL DEFAULT 0,
  `territory_average_ac` int NOT NULL DEFAULT 0,
  `territory_average_level` int NOT NULL DEFAULT 0,
  `territory_average_wis` int NOT NULL DEFAULT 0,
  `territory_average_mr` int NOT NULL DEFAULT 0,
  `territory_average_magic_barrier` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`map_number`, `npc_classId`, `territory_startXY`, `territory_endXY`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_npc_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_npc_common`;
CREATE TABLE `bin_npc_common`  (
  `class_id` int NOT NULL DEFAULT 0,
  `npc_id` int NOT NULL,
  `with_bin_spawn` tinyint(1) NOT NULL,
  `sprite_id` int NOT NULL DEFAULT 0,
  `desc_id` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `level` int NOT NULL DEFAULT 0,
  `hp` int NOT NULL DEFAULT 0,
  `mp` int NOT NULL DEFAULT 0,
  `ac` int NOT NULL DEFAULT 0,
  `str` int NOT NULL DEFAULT 0,
  `con` int NOT NULL DEFAULT 0,
  `dex` int NOT NULL DEFAULT 0,
  `wis` int NOT NULL DEFAULT 0,
  `inti` int NOT NULL DEFAULT 0,
  `cha` int NOT NULL DEFAULT 0,
  `mr` int NOT NULL DEFAULT 0,
  `magic_level` int NOT NULL DEFAULT 0,
  `magic_bonus` int NOT NULL DEFAULT 0,
  `magic_evasion` int NOT NULL DEFAULT 0,
  `resistance_fire` int NOT NULL DEFAULT 0,
  `resistance_water` int NOT NULL DEFAULT 0,
  `resistance_air` int NOT NULL DEFAULT 0,
  `resistance_earth` int NOT NULL DEFAULT 0,
  `alignment` int NOT NULL DEFAULT 0,
  `big` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `drop_items` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `tendency` enum('AGGRESSIVE(2)','PASSIVE(1)','NEUTRAL(0)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NEUTRAL(0)',
  `category` int NOT NULL DEFAULT 0,
  `is_bossmonster` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `can_turnundead` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`class_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_passivespell_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_passivespell_common`;
CREATE TABLE `bin_passivespell_common`  (
  `passive_id` int NOT NULL DEFAULT 0,
  `duration` int NOT NULL DEFAULT 0,
  `spell_bonus_list` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `delay_group_id` int NOT NULL DEFAULT 0,
  `extract_item_name_id` int NOT NULL DEFAULT 0,
  `extract_item_count` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`passive_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_pc_master_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_pc_master_common`;
CREATE TABLE `bin_pc_master_common`  (
  `utilities` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `pc_bonus_map_infos` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `notification` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `buff_group` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `buff_bonus` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_portrait_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_portrait_common`;
CREATE TABLE `bin_portrait_common`  (
  `asset_id` int NOT NULL DEFAULT 0,
  `desc_id` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`asset_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_potential_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_potential_common`;
CREATE TABLE `bin_potential_common`  (
  `id` int NOT NULL DEFAULT 0,
  `grade` int NOT NULL DEFAULT 0,
  `desc_id` int NOT NULL DEFAULT 0,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `material_list` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `event_config` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_ship_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_ship_common`;
CREATE TABLE `bin_ship_common`  (
  `id` int NOT NULL DEFAULT 0,
  `dockWorld` int NOT NULL DEFAULT 0,
  `shipWorld` int NOT NULL DEFAULT 0,
  `ticket` int NOT NULL DEFAULT 0,
  `levelLimit` int NOT NULL DEFAULT 0,
  `dock_startX` int NOT NULL DEFAULT 0,
  `dock_startY` int NOT NULL DEFAULT 0,
  `dock_endX` int NOT NULL DEFAULT 0,
  `dock_endY` int NOT NULL DEFAULT 0,
  `shipLoc_x` int NOT NULL DEFAULT 0,
  `shipLoc_y` int NOT NULL DEFAULT 0,
  `destWorld` int NOT NULL DEFAULT 0,
  `destLoc_x` int NOT NULL DEFAULT 0,
  `destLoc_y` int NOT NULL DEFAULT 0,
  `destLoc_range` int NOT NULL DEFAULT 0,
  `schedule_day` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `schedule_time` blob NULL,
  `schedule_duration` int NOT NULL DEFAULT 0,
  `schedule_ship_operating_duration` int NOT NULL DEFAULT 0,
  `returnWorld` int NOT NULL DEFAULT 0,
  `returnLoc_x` int NOT NULL DEFAULT 0,
  `returnLoc_y` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_spell_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_spell_common`;
CREATE TABLE `bin_spell_common`  (
  `spell_id` int NOT NULL DEFAULT 0,
  `spell_category` enum('COMPANION_SPELL_BUFF(2)','SPELL_BUFF(1)','SPELL(0)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'SPELL(0)',
  `on_icon_id` int NOT NULL DEFAULT 0,
  `off_icon_id` int NOT NULL DEFAULT 0,
  `duration` int NOT NULL DEFAULT 0,
  `tooltip_str_id` int NOT NULL DEFAULT 0,
  `tooltip_str_kr` varchar(200) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `spell_bonus_list` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `companion_on_icon_id` int NOT NULL DEFAULT 0,
  `companion_off_icon_id` int NOT NULL DEFAULT 0,
  `companion_icon_priority` int NOT NULL DEFAULT 0,
  `companion_tooltip_str_id` int NOT NULL DEFAULT 0,
  `companion_new_str_id` int NOT NULL DEFAULT 0,
  `companion_end_str_id` int NOT NULL DEFAULT 0,
  `companion_is_good` int NOT NULL DEFAULT 0,
  `companion_duration_show_type` int NOT NULL DEFAULT 0,
  `delay_group_id` int NOT NULL DEFAULT 0,
  `extract_item_name_id` int NOT NULL DEFAULT 0,
  `extract_item_count` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`spell_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_timecollection_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_timecollection_common`;
CREATE TABLE `bin_timecollection_common`  (
  `buffSelect` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `rewardList` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `enchantSection` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `group_id` int NOT NULL DEFAULT 0,
  `group_desc` int NOT NULL DEFAULT 0,
  `group_desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `group_level_min` int NOT NULL DEFAULT 0,
  `group_level_max` int NOT NULL DEFAULT 0,
  `group_period_StartDate` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `group_period_EndDate` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `group_set_id` int NOT NULL DEFAULT 0,
  `group_set_desc` int NOT NULL DEFAULT 0,
  `group_set_desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `group_set_defaultTime` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `group_set_recycle` int NOT NULL DEFAULT 0,
  `group_set_itemSlot` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `group_set_BuffType` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `group_set_endBonus` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `group_set_ExtraTimeId` int NOT NULL DEFAULT 0,
  `group_set_SetType` enum('NONE(-1)','TC_INFINITY(0)','TC_LIMITED(1)','TC_BONUS_INFINITY(2)','TC_BONUS_LIMITED(3)','TC_ADENA_REFILL(4)','TC_ADENA_REFILL_ERROR(5)','TC_BONUS_ADENA_REFILL(6)','TC_BONUS_ADENA_REFILL_ERROR(7)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(-1)',
  `ExtraTimeSection` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `NPCDialogInfo` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `AlarmSetting` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`group_id`, `group_set_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_treasurebox_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_treasurebox_common`;
CREATE TABLE `bin_treasurebox_common`  (
  `id` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `excavateTime` int NOT NULL DEFAULT 0,
  `desc_id` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `grade` enum('Common(0)','Good(1)','Prime(2)','Legendary(3)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'Common(0)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bin_treasureboxreward_common
-- ----------------------------
DROP TABLE IF EXISTS `bin_treasureboxreward_common`;
CREATE TABLE `bin_treasureboxreward_common`  (
  `nameid` int NOT NULL DEFAULT 0,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `grade` enum('Common(0)','Good(1)','Prime(2)','Legendary(3)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'Common(0)',
  PRIMARY KEY (`nameid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_app_lfc
-- ----------------------------
DROP TABLE IF EXISTS `board_app_lfc`;
CREATE TABLE `board_app_lfc`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'NULL',
  `date` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'NULL',
  `title` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'NULL',
  `content` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'NULL',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_auction
-- ----------------------------
DROP TABLE IF EXISTS `board_auction`;
CREATE TABLE `board_auction`  (
  `house_id` int UNSIGNED NOT NULL DEFAULT 0,
  `house_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `house_area` int UNSIGNED NOT NULL DEFAULT 0,
  `deadline` datetime NULL DEFAULT NULL,
  `price` int UNSIGNED NOT NULL DEFAULT 0,
  `location` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `old_owner` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `old_owner_id` int UNSIGNED NOT NULL DEFAULT 0,
  `bidder` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `bidder_id` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`house_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_free
-- ----------------------------
DROP TABLE IF EXISTS `board_free`;
CREATE TABLE `board_free`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `date` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `title` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_notice
-- ----------------------------
DROP TABLE IF EXISTS `board_notice`;
CREATE TABLE `board_notice`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `date` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `title` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_notice1
-- ----------------------------
DROP TABLE IF EXISTS `board_notice1`;
CREATE TABLE `board_notice1`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `date` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `title` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_notice2
-- ----------------------------
DROP TABLE IF EXISTS `board_notice2`;
CREATE TABLE `board_notice2`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `date` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `title` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_notice3
-- ----------------------------
DROP TABLE IF EXISTS `board_notice3`;
CREATE TABLE `board_notice3`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `date` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `title` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_posts_fix
-- ----------------------------
DROP TABLE IF EXISTS `board_posts_fix`;
CREATE TABLE `board_posts_fix`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `date` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `title` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_posts_key
-- ----------------------------
DROP TABLE IF EXISTS `board_posts_key`;
CREATE TABLE `board_posts_key`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `date` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `title` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_user
-- ----------------------------
DROP TABLE IF EXISTS `board_user`;
CREATE TABLE `board_user`  (
  `id` int NOT NULL DEFAULT 0,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `date` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `title` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for board_user1
-- ----------------------------
DROP TABLE IF EXISTS `board_user1`;
CREATE TABLE `board_user1`  (
  `id` int NOT NULL,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `date` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `title` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for bots
-- ----------------------------
DROP TABLE IF EXISTS `bots`;
CREATE TABLE `bots`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `x` int NOT NULL DEFAULT 0,
  `y` int NOT NULL DEFAULT 0,
  `heading` int NOT NULL DEFAULT 0,
  `mapId` int NOT NULL,
  `type` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 132 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for castle
-- ----------------------------
DROP TABLE IF EXISTS `castle`;
CREATE TABLE `castle`  (
  `castle_id` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `war_time` datetime NULL DEFAULT NULL,
  `tax_rate` int NOT NULL DEFAULT 0,
  `public_money` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`castle_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for castle_present
-- ----------------------------
DROP TABLE IF EXISTS `castle_present`;
CREATE TABLE `castle_present`  (
  `itemid` int NOT NULL,
  `count` int NOT NULL DEFAULT 0,
  `memo` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`itemid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for castle_soldier
-- ----------------------------
DROP TABLE IF EXISTS `castle_soldier`;
CREATE TABLE `castle_soldier`  (
  `castle_id` int NOT NULL AUTO_INCREMENT,
  `soldier1` int NOT NULL DEFAULT 0,
  `soldier1_npcid` int NOT NULL DEFAULT 0,
  `soldier1_name` varchar(10) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `soldier2` int NOT NULL DEFAULT 0,
  `soldier2_npcid` int NOT NULL DEFAULT 0,
  `soldier2_name` varchar(10) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `soldier3` int NOT NULL DEFAULT 0,
  `soldier3_npcid` int NOT NULL DEFAULT 0,
  `soldier3_name` varchar(10) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `soldier4` int NOT NULL DEFAULT 0,
  `soldier4_npcid` int NOT NULL DEFAULT 0,
  `soldier4_name` varchar(10) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`castle_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for catalyst
-- ----------------------------
DROP TABLE IF EXISTS `catalyst`;
CREATE TABLE `catalyst`  (
  `nameId` int NOT NULL DEFAULT 0,
  `nameId_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `nameId_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `input` int NOT NULL DEFAULT 0,
  `input_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `input_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `successProb` int NOT NULL DEFAULT 0,
  `broad` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`nameId`, `input`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for catalyst_custom
-- ----------------------------
DROP TABLE IF EXISTS `catalyst_custom`;
CREATE TABLE `catalyst_custom`  (
  `itemId` int NOT NULL DEFAULT 0,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `input_itemId` int NOT NULL DEFAULT 0,
  `input_enchant` int NOT NULL DEFAULT 0,
  `input_desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `input_desc` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `output_itemId` int NOT NULL DEFAULT 0,
  `output_desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `output_desc` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `successProb` int NOT NULL DEFAULT 100,
  `rewardCount` int NOT NULL DEFAULT 1,
  `rewardEnchant` int NOT NULL DEFAULT 0,
  `broad` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`itemId`, `input_itemId`, `input_enchant`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_arca
-- ----------------------------
DROP TABLE IF EXISTS `character_arca`;
CREATE TABLE `character_arca`  (
  `id` int NOT NULL DEFAULT 0,
  `charId` int NOT NULL DEFAULT 0,
  `day` int NOT NULL DEFAULT 0,
  `useItemId` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`, `charId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_beginner_quest
-- ----------------------------
DROP TABLE IF EXISTS `character_beginner_quest`;
CREATE TABLE `character_beginner_quest`  (
  `charId` int NOT NULL DEFAULT 0,
  `info` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`charId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_buddys
-- ----------------------------
DROP TABLE IF EXISTS `character_buddys`;
CREATE TABLE `character_buddys`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `char_id` int NOT NULL DEFAULT 0,
  `buddy_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `buddy_memo` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`char_id`, `buddy_name`) USING BTREE,
  INDEX `key_id`(`id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_buff
-- ----------------------------
DROP TABLE IF EXISTS `character_buff`;
CREATE TABLE `character_buff`  (
  `char_obj_id` int NOT NULL DEFAULT 0,
  `skill_id` int NOT NULL DEFAULT -1,
  `remaining_time` int NOT NULL DEFAULT 0,
  `poly_id` int NULL DEFAULT 0,
  PRIMARY KEY (`char_obj_id`, `skill_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_companion
-- ----------------------------
DROP TABLE IF EXISTS `character_companion`;
CREATE TABLE `character_companion`  (
  `item_objId` int UNSIGNED NOT NULL,
  `objid` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `npcId` int UNSIGNED NOT NULL,
  `level` int UNSIGNED NOT NULL DEFAULT 1,
  `exp` int UNSIGNED NOT NULL DEFAULT 0,
  `maxHp` int UNSIGNED NOT NULL DEFAULT 160,
  `currentHp` int UNSIGNED NOT NULL DEFAULT 160,
  `friend_ship_marble` int UNSIGNED NOT NULL DEFAULT 0,
  `friend_ship_guage` int UNSIGNED NOT NULL DEFAULT 0,
  `add_str` int NOT NULL DEFAULT 0,
  `add_con` int NOT NULL DEFAULT 0,
  `add_int` int NOT NULL DEFAULT 0,
  `remain_stats` int UNSIGNED NOT NULL DEFAULT 0,
  `elixir_use_count` int UNSIGNED NOT NULL DEFAULT 0,
  `dead` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `oblivion` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `tier` int NOT NULL DEFAULT 1,
  `wild` blob NOT NULL,
  `lessExp` int NOT NULL DEFAULT 0,
  `traningTime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`item_objId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_companion_buff
-- ----------------------------
DROP TABLE IF EXISTS `character_companion_buff`;
CREATE TABLE `character_companion_buff`  (
  `objid` int UNSIGNED NOT NULL DEFAULT 0,
  `buff_id` int UNSIGNED NOT NULL DEFAULT 0,
  `duration` int NOT NULL,
  PRIMARY KEY (`objid`, `buff_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for character_config
-- ----------------------------
DROP TABLE IF EXISTS `character_config`;
CREATE TABLE `character_config`  (
  `object_id` int NOT NULL DEFAULT 0,
  `length` int UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NULL,
  PRIMARY KEY (`object_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_death_exp
-- ----------------------------
DROP TABLE IF EXISTS `character_death_exp`;
CREATE TABLE `character_death_exp`  (
  `char_id` int NOT NULL DEFAULT 0,
  `delete_time` datetime NOT NULL,
  `death_level` int NOT NULL DEFAULT 0,
  `exp_value` int NOT NULL DEFAULT 0,
  `recovery_cost` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`char_id`, `delete_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_death_item
-- ----------------------------
DROP TABLE IF EXISTS `character_death_item`;
CREATE TABLE `character_death_item`  (
  `char_id` int NOT NULL DEFAULT 0,
  `delete_time` datetime NOT NULL,
  `db_id` int NOT NULL DEFAULT 0,
  `itemId` int NOT NULL,
  `count` int NOT NULL DEFAULT 0,
  `enchant` int NOT NULL DEFAULT 0,
  `identi` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `chargeCount` int NOT NULL DEFAULT 0,
  `bless` int NOT NULL DEFAULT 1,
  `attrEnchant` int NOT NULL DEFAULT 0,
  `specialEnchant` int NOT NULL DEFAULT 0,
  `potential_id` int NOT NULL DEFAULT 0,
  `slot_first` int NOT NULL DEFAULT 0,
  `slot_second` int NOT NULL DEFAULT 0,
  `recovery_cost` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`db_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_einhasadfaith
-- ----------------------------
DROP TABLE IF EXISTS `character_einhasadfaith`;
CREATE TABLE `character_einhasadfaith`  (
  `objId` int NOT NULL DEFAULT 0,
  `groupId` int NOT NULL DEFAULT 0,
  `indexId` int NOT NULL DEFAULT 0,
  `spellId` int NOT NULL DEFAULT -1,
  `expiredTime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`objId`, `indexId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_einhasadstat
-- ----------------------------
DROP TABLE IF EXISTS `character_einhasadstat`;
CREATE TABLE `character_einhasadstat`  (
  `objid` int NOT NULL DEFAULT 0,
  `bless` int NOT NULL DEFAULT 0,
  `lucky` int NOT NULL DEFAULT 0,
  `vital` int NOT NULL DEFAULT 0,
  `itemSpellProb` int NOT NULL DEFAULT 0,
  `absoluteRegen` int NOT NULL DEFAULT 0,
  `potion` int NOT NULL DEFAULT 0,
  `bless_efficiency` int NOT NULL DEFAULT 0,
  `bless_exp` int NOT NULL DEFAULT 0,
  `lucky_item` int NOT NULL DEFAULT 0,
  `lucky_adena` int NOT NULL DEFAULT 0,
  `vital_potion` int NOT NULL DEFAULT 0,
  `vital_heal` int NOT NULL DEFAULT 0,
  `itemSpellProb_armor` int NOT NULL DEFAULT 0,
  `itemSpellProb_weapon` int NOT NULL DEFAULT 0,
  `absoluteRegen_hp` int NOT NULL DEFAULT 0,
  `absoluteRegen_mp` int NOT NULL DEFAULT 0,
  `potion_critical` int NOT NULL DEFAULT 0,
  `potion_delay` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`objid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_elf_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `character_elf_warehouse`;
CREATE TABLE `character_elf_warehouse`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_id` int NULL DEFAULT NULL,
  `item_name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NULL DEFAULT NULL,
  `is_equipped` int NULL DEFAULT NULL,
  `enchantlvl` int NULL DEFAULT NULL,
  `is_id` int NULL DEFAULT NULL,
  `durability` int NULL DEFAULT NULL,
  `charge_count` int NULL DEFAULT NULL,
  `remaining_time` int NULL DEFAULT NULL,
  `last_used` datetime NULL DEFAULT NULL,
  `attr_enchantlvl` int NULL DEFAULT NULL,
  `special_enchant` int NULL DEFAULT NULL,
  `doll_ablity` int NULL DEFAULT NULL,
  `bless` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`account_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_equipset
-- ----------------------------
DROP TABLE IF EXISTS `character_equipset`;
CREATE TABLE `character_equipset`  (
  `charId` int NOT NULL DEFAULT 0,
  `current_set` int NOT NULL DEFAULT 0,
  `slot1_item` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `slot2_item` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `slot3_item` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `slot4_item` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `slot1_name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `slot2_name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `slot3_name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `slot4_name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `slot1_color` int NOT NULL DEFAULT 0,
  `slot2_color` int NOT NULL DEFAULT 0,
  `slot3_color` int NOT NULL DEFAULT 0,
  `slot4_color` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`charId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_eventpush
-- ----------------------------
DROP TABLE IF EXISTS `character_eventpush`;
CREATE TABLE `character_eventpush`  (
  `push_id` int NOT NULL DEFAULT 0,
  `objId` int NOT NULL DEFAULT 0,
  `subject` varchar(90) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(300) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `web_url` varchar(200) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  `itemId` int NOT NULL DEFAULT 0,
  `item_amount` int NOT NULL DEFAULT 0,
  `item_enchant` int NULL DEFAULT 0,
  `doll_ablity` int NULL DEFAULT NULL,
  `used_immediately` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `status` int NOT NULL DEFAULT 0,
  `enable_date` datetime NOT NULL,
  `image_id` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`push_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_exclude
-- ----------------------------
DROP TABLE IF EXISTS `character_exclude`;
CREATE TABLE `character_exclude`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `char_id` int NOT NULL DEFAULT 0,
  `type` int NOT NULL DEFAULT 0,
  `exclude_id` int UNSIGNED NOT NULL DEFAULT 0,
  `exclude_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`char_id`, `type`, `exclude_id`) USING BTREE,
  INDEX `key_id`(`id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci COMMENT = 'MyISAM free: 10240 kB; MyISAM free: 10240 kB' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_fairly_config
-- ----------------------------
DROP TABLE IF EXISTS `character_fairly_config`;
CREATE TABLE `character_fairly_config`  (
  `object_id` int NOT NULL,
  `data` blob NULL,
  PRIMARY KEY (`object_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_favorbook
-- ----------------------------
DROP TABLE IF EXISTS `character_favorbook`;
CREATE TABLE `character_favorbook`  (
  `charObjId` int NOT NULL DEFAULT 0,
  `category` int NOT NULL DEFAULT 0,
  `slotId` int NOT NULL DEFAULT 0,
  `itemObjId` int NOT NULL DEFAULT 0,
  `itemId` int NOT NULL DEFAULT 0,
  `itemName` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  `count` int NOT NULL DEFAULT 1,
  `enchantLevel` int NOT NULL DEFAULT 0,
  `attrLevel` int NOT NULL DEFAULT 0,
  `bless` int NOT NULL DEFAULT 1,
  `endTime` datetime NULL DEFAULT NULL,
  `craftId` int NOT NULL DEFAULT 0,
  `awakening` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`charObjId`, `category`, `slotId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_hunting_quest
-- ----------------------------
DROP TABLE IF EXISTS `character_hunting_quest`;
CREATE TABLE `character_hunting_quest`  (
  `id` int NOT NULL DEFAULT 0,
  `objID` int NOT NULL DEFAULT 0,
  `map_number` int NULL DEFAULT 0,
  `location_desc` int NULL DEFAULT 0,
  `quest_id` int NULL DEFAULT NULL,
  `kill_count` int NULL DEFAULT NULL,
  `complete` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_items
-- ----------------------------
DROP TABLE IF EXISTS `character_items`;
CREATE TABLE `character_items`  (
  `id` int NOT NULL DEFAULT 0,
  `item_id` int NOT NULL,
  `char_id` int NOT NULL,
  `item_name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NOT NULL DEFAULT 0,
  `is_equipped` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enchantlvl` int NOT NULL DEFAULT 0,
  `is_id` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `durability` int NOT NULL DEFAULT 0,
  `charge_count` int NOT NULL DEFAULT 0,
  `remaining_time` int NOT NULL DEFAULT 0,
  `last_used` datetime NULL DEFAULT NULL,
  `bless` int NOT NULL DEFAULT 1,
  `attr_enchantlvl` int NOT NULL DEFAULT 0,
  `special_enchant` int NOT NULL DEFAULT 0,
  `doll_ablity` int NOT NULL DEFAULT 0,
  `end_time` datetime NULL DEFAULT NULL,
  `KeyVal` int NOT NULL DEFAULT 0,
  `package` tinyint(1) NOT NULL DEFAULT 0,
  `engrave` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `scheduled` tinyint(1) NOT NULL DEFAULT 0,
  `slot_0` int NOT NULL DEFAULT 0,
  `slot_1` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`char_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_monsterbooklist
-- ----------------------------
DROP TABLE IF EXISTS `character_monsterbooklist`;
CREATE TABLE `character_monsterbooklist`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `monsterlist` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `monquest` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_package_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `character_package_warehouse`;
CREATE TABLE `character_package_warehouse`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_id` int NULL DEFAULT NULL,
  `item_name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NULL DEFAULT NULL,
  `is_equipped` int NULL DEFAULT NULL,
  `enchantlvl` int NULL DEFAULT NULL,
  `is_id` int NULL DEFAULT NULL,
  `durability` int NULL DEFAULT NULL,
  `charge_count` int NULL DEFAULT NULL,
  `remaining_time` int NULL DEFAULT NULL,
  `last_used` datetime NULL DEFAULT NULL,
  `attr_enchantlvl` int NULL DEFAULT NULL,
  `bless` int NULL DEFAULT 0,
  `special_enchant` int NULL DEFAULT NULL,
  `doll_ablity` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`account_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 332536125 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_present_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `character_present_warehouse`;
CREATE TABLE `character_present_warehouse`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_id` int NULL DEFAULT NULL,
  `item_name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NULL DEFAULT NULL,
  `is_equipped` int NULL DEFAULT NULL,
  `enchantlvl` int NULL DEFAULT NULL,
  `is_id` int NULL DEFAULT NULL,
  `durability` int NULL DEFAULT NULL,
  `charge_count` int NULL DEFAULT NULL,
  `remaining_time` int NULL DEFAULT NULL,
  `last_used` datetime NULL DEFAULT NULL,
  `attr_enchantlvl` int NULL DEFAULT NULL,
  `special_enchant` int NULL DEFAULT NULL,
  `doll_ablity` int NULL DEFAULT NULL,
  `bless` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`account_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_quests
-- ----------------------------
DROP TABLE IF EXISTS `character_quests`;
CREATE TABLE `character_quests`  (
  `char_id` int UNSIGNED NOT NULL,
  `quest_id` int UNSIGNED NOT NULL DEFAULT 0,
  `quest_step` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`char_id`, `quest_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_revenge
-- ----------------------------
DROP TABLE IF EXISTS `character_revenge`;
CREATE TABLE `character_revenge`  (
  `number` int UNSIGNED NOT NULL DEFAULT 0,
  `char_id` int UNSIGNED NOT NULL DEFAULT 0,
  `result` int UNSIGNED NOT NULL DEFAULT 0,
  `starttime` datetime NULL DEFAULT NULL,
  `endtime` datetime NULL DEFAULT NULL,
  `chasestarttime` datetime NULL DEFAULT NULL,
  `chaseendtime` datetime NULL DEFAULT NULL,
  `usecount` int UNSIGNED NOT NULL DEFAULT 0,
  `amount` int UNSIGNED NOT NULL DEFAULT 0,
  `targetobjid` int NOT NULL DEFAULT 0,
  `targetclass` int UNSIGNED NOT NULL DEFAULT 0,
  `targetname` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `targetclanid` int UNSIGNED NOT NULL DEFAULT 0,
  `targetclanname` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`number`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_shop_limit
-- ----------------------------
DROP TABLE IF EXISTS `character_shop_limit`;
CREATE TABLE `character_shop_limit`  (
  `characterId` int NOT NULL DEFAULT 0,
  `buyShopId` int NOT NULL DEFAULT 0,
  `buyItemId` int NOT NULL DEFAULT 0,
  `buyCount` int NOT NULL DEFAULT 0,
  `buyTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `limitTerm` enum('WEEK','DAY','NONE') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'DAY',
  PRIMARY KEY (`characterId`, `buyShopId`, `buyItemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_skills_active
-- ----------------------------
DROP TABLE IF EXISTS `character_skills_active`;
CREATE TABLE `character_skills_active`  (
  `char_obj_id` int NOT NULL DEFAULT 0,
  `skill_id` int NOT NULL DEFAULT -1,
  `skill_name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`char_obj_id`, `skill_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_skills_passive
-- ----------------------------
DROP TABLE IF EXISTS `character_skills_passive`;
CREATE TABLE `character_skills_passive`  (
  `char_obj_id` int NOT NULL DEFAULT 0,
  `passive_id` int NOT NULL DEFAULT 0,
  `passive_name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`char_obj_id`, `passive_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_soldier
-- ----------------------------
DROP TABLE IF EXISTS `character_soldier`;
CREATE TABLE `character_soldier`  (
  `char_id` int NOT NULL,
  `npc_id` int NOT NULL DEFAULT 0,
  `count` int NOT NULL DEFAULT 0,
  `castle_id` int NOT NULL DEFAULT 0,
  `time` int NOT NULL,
  PRIMARY KEY (`char_id`, `time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_special_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `character_special_warehouse`;
CREATE TABLE `character_special_warehouse`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_id` int NULL DEFAULT NULL,
  `item_name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NULL DEFAULT NULL,
  `is_equipped` int NULL DEFAULT NULL,
  `enchantlvl` int NULL DEFAULT NULL,
  `is_id` int NULL DEFAULT NULL,
  `durability` int NULL DEFAULT NULL,
  `charge_count` int NULL DEFAULT NULL,
  `remaining_time` int NULL DEFAULT NULL,
  `last_used` datetime NULL DEFAULT NULL,
  `attr_enchantlvl` int NULL DEFAULT NULL,
  `doll_ablity` int NULL DEFAULT NULL,
  `bless` int NULL DEFAULT 0,
  `second_id` int NULL DEFAULT NULL,
  `round_id` int NULL DEFAULT NULL,
  `ticket_id` int NULL DEFAULT NULL,
  `maan_time` datetime NULL DEFAULT NULL,
  `regist_level` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`account_name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_teleport
-- ----------------------------
DROP TABLE IF EXISTS `character_teleport`;
CREATE TABLE `character_teleport`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `num_id` int UNSIGNED NOT NULL DEFAULT 0,
  `speed_id` int NOT NULL DEFAULT -1,
  `char_id` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  `randomX` int UNSIGNED NOT NULL DEFAULT 0,
  `randomY` int UNSIGNED NOT NULL DEFAULT 0,
  `item_obj_id` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`char_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 299679288 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_timecollection
-- ----------------------------
DROP TABLE IF EXISTS `character_timecollection`;
CREATE TABLE `character_timecollection`  (
  `charObjId` int NOT NULL DEFAULT 0,
  `groupId` int NOT NULL DEFAULT 0,
  `setId` int NOT NULL DEFAULT 0,
  `slots` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `registComplet` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `buffType` enum('SHORT','LONG','MAGIC') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'MAGIC',
  `buffTime` datetime NULL DEFAULT NULL,
  `refillCount` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`charObjId`, `groupId`, `setId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for character_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `character_warehouse`;
CREATE TABLE `character_warehouse`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_id` int NULL DEFAULT NULL,
  `item_name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NULL DEFAULT NULL,
  `is_equipped` int NULL DEFAULT NULL,
  `enchantlvl` int NULL DEFAULT NULL,
  `is_id` int NULL DEFAULT NULL,
  `durability` int NULL DEFAULT NULL,
  `charge_count` int NULL DEFAULT NULL,
  `remaining_time` int NULL DEFAULT NULL,
  `last_used` datetime NULL DEFAULT NULL,
  `attr_enchantlvl` int NULL DEFAULT NULL,
  `bless` int NULL DEFAULT 0,
  `special_enchant` int NULL DEFAULT NULL,
  `doll_ablity` int NULL DEFAULT NULL,
  `package` tinyint NULL DEFAULT 0,
  `buy_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`account_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 336226325 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for characters
-- ----------------------------
DROP TABLE IF EXISTS `characters`;
CREATE TABLE `characters`  (
  `account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `objid` int UNSIGNED NOT NULL DEFAULT 0,
  `char_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `level` int UNSIGNED NOT NULL DEFAULT 0,
  `HighLevel` int UNSIGNED NOT NULL DEFAULT 0,
  `Exp` bigint UNSIGNED NOT NULL DEFAULT 0,
  `MaxHp` int NOT NULL DEFAULT 0,
  `CurHp` int NOT NULL DEFAULT 0,
  `MaxMp` int NOT NULL DEFAULT 0,
  `CurMp` int NOT NULL DEFAULT 0,
  `Ac` int NOT NULL DEFAULT 0,
  `Str` int NOT NULL DEFAULT 0,
  `BaseStr` int NOT NULL DEFAULT 0,
  `Con` int NOT NULL DEFAULT 0,
  `BaseCon` int NOT NULL DEFAULT 0,
  `Dex` int NOT NULL DEFAULT 0,
  `BaseDex` int NOT NULL DEFAULT 0,
  `Cha` int NOT NULL DEFAULT 0,
  `BaseCha` int NOT NULL DEFAULT 0,
  `Intel` int NOT NULL DEFAULT 0,
  `BaseIntel` int NOT NULL DEFAULT 0,
  `Wis` int NOT NULL DEFAULT 0,
  `BaseWis` int NOT NULL DEFAULT 0,
  `Status` int UNSIGNED NOT NULL DEFAULT 0,
  `Class` int UNSIGNED NOT NULL DEFAULT 0,
  `gender` int UNSIGNED NOT NULL DEFAULT 0,
  `Type` int UNSIGNED NOT NULL DEFAULT 0,
  `Heading` int UNSIGNED NOT NULL DEFAULT 0,
  `LocX` int UNSIGNED NOT NULL DEFAULT 0,
  `LocY` int UNSIGNED NOT NULL DEFAULT 0,
  `MapID` int UNSIGNED NOT NULL DEFAULT 0,
  `Food` int UNSIGNED NOT NULL DEFAULT 0,
  `Alignment` int NOT NULL DEFAULT 0,
  `Title` varchar(35) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `ClanID` int UNSIGNED NOT NULL DEFAULT 0,
  `Clanname` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `ClanRank` int NOT NULL DEFAULT 0,
  `ClanContribution` int NOT NULL DEFAULT 0,
  `ClanWeekContribution` int NOT NULL DEFAULT 0,
  `pledgeJoinDate` int NOT NULL DEFAULT 0,
  `pledgeRankDate` int NOT NULL DEFAULT 0,
  `notes` varchar(60) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `BonusStatus` int NOT NULL DEFAULT 0,
  `ElixirStatus` int NOT NULL DEFAULT 0,
  `ElfAttr` int NOT NULL DEFAULT 0,
  `PKcount` int NOT NULL DEFAULT 0,
  `ExpRes` int NOT NULL DEFAULT 0,
  `PartnerID` int NOT NULL DEFAULT 0,
  `AccessLevel` int UNSIGNED NOT NULL DEFAULT 0,
  `OnlineStatus` int UNSIGNED NOT NULL DEFAULT 0,
  `HomeTownID` int NOT NULL DEFAULT 0,
  `Contribution` int NOT NULL DEFAULT 0,
  `HellTime` int UNSIGNED NOT NULL DEFAULT 0,
  `Banned` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `Karma` int NOT NULL DEFAULT 0,
  `LastPk` datetime NULL DEFAULT NULL,
  `DeleteTime` datetime NULL DEFAULT NULL,
  `ReturnStat` bigint NOT NULL,
  `lastLoginTime` datetime NULL DEFAULT NULL,
  `lastLogoutTime` datetime NULL DEFAULT NULL,
  `BirthDay` int NULL DEFAULT NULL,
  `PC_Kill` int NULL DEFAULT NULL,
  `PC_Death` int NULL DEFAULT NULL,
  `Mark_Count` int NOT NULL DEFAULT 60,
  `TamEndTime` datetime NULL DEFAULT NULL,
  `SpecialSize` int NOT NULL DEFAULT 0,
  `HuntPrice` int NULL DEFAULT NULL,
  `HuntText` varchar(30) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `HuntCount` int NULL DEFAULT NULL,
  `RingAddSlot` int NULL DEFAULT 0,
  `EarringAddSlot` int NULL DEFAULT 0,
  `BadgeAddSlot` int NULL DEFAULT 0,
  `ShoulderAddSlot` int NULL DEFAULT 0,
  `fatigue_point` int NOT NULL DEFAULT 0,
  `fatigue_rest_time` datetime NULL DEFAULT NULL,
  `EMETime` datetime NULL DEFAULT NULL,
  `EMETime2` datetime NULL DEFAULT NULL,
  `PUPLETime` datetime NULL DEFAULT NULL,
  `TOPAZTime` datetime NULL DEFAULT NULL,
  `EinhasadGraceTime` datetime NULL DEFAULT NULL,
  `EinPoint` int NULL DEFAULT 0,
  `EinCardLess` int NOT NULL DEFAULT 0,
  `EinCardState` int NOT NULL DEFAULT 0,
  `EinCardBonusValue` int NOT NULL DEFAULT 0,
  `ThirdSkillTime` datetime NULL DEFAULT NULL,
  `FiveSkillTime` datetime NULL DEFAULT NULL,
  `SurvivalTime` datetime NULL DEFAULT NULL,
  `potentialTargetId` int NOT NULL DEFAULT 0,
  `potentialBonusGrade` int NOT NULL DEFAULT 0,
  `potentialBonusId` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`objid`) USING BTREE,
  INDEX `key_id`(`account_name` ASC, `char_name` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_bless_buff
-- ----------------------------
DROP TABLE IF EXISTS `clan_bless_buff`;
CREATE TABLE `clan_bless_buff`  (
  `number` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `buff_id` int NOT NULL DEFAULT -1,
  `map_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `teleport_map_id` int UNSIGNED NULL DEFAULT 0,
  `teleport_x` int UNSIGNED NULL DEFAULT 0,
  `teleport_y` int UNSIGNED NULL DEFAULT 0,
  `buff_map_list` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`number`, `buff_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 13 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_contribution_buff
-- ----------------------------
DROP TABLE IF EXISTS `clan_contribution_buff`;
CREATE TABLE `clan_contribution_buff`  (
  `clan_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `clan_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `exp_buff_type` int UNSIGNED NULL DEFAULT 0,
  `exp_buff_time` datetime NULL DEFAULT NULL,
  `battle_buff_type` int UNSIGNED NULL DEFAULT 0,
  `battle_buff_time` datetime NULL DEFAULT NULL,
  `defens_buff_type` int UNSIGNED NULL DEFAULT 0,
  `defens_buff_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`clan_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_data
-- ----------------------------
DROP TABLE IF EXISTS `clan_data`;
CREATE TABLE `clan_data`  (
  `clan_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `clan_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `leader_id` int UNSIGNED NOT NULL DEFAULT 0,
  `leader_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `hascastle` int UNSIGNED NOT NULL DEFAULT 0,
  `hashouse` int UNSIGNED NOT NULL DEFAULT 0,
  `alliance` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `clan_birthday` datetime NOT NULL,
  `bot` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `bot_style` tinyint NOT NULL DEFAULT 0,
  `bot_level` tinyint NOT NULL DEFAULT 0,
  `max_online_user` int NOT NULL DEFAULT 0,
  `announcement` varchar(160) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `introductionMessage` varchar(160) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `enter_notice` varchar(160) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `emblem_id` int NOT NULL DEFAULT 0,
  `emblem_status` tinyint(1) NOT NULL DEFAULT 0,
  `contribution` int NOT NULL DEFAULT 0,
  `bless` int NOT NULL DEFAULT 0,
  `bless_count` int NOT NULL DEFAULT 0,
  `attack` int NOT NULL DEFAULT 0,
  `defence` int NOT NULL DEFAULT 0,
  `pvpattack` int NOT NULL DEFAULT 0,
  `pvpdefence` int NOT NULL DEFAULT 0,
  `under_dungeon` tinyint NOT NULL DEFAULT 0,
  `ranktime` int NOT NULL DEFAULT 0,
  `rankdate` datetime NULL DEFAULT NULL,
  `War_point` int NOT NULL DEFAULT 0,
  `enable_join` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  `join_type` int NOT NULL DEFAULT 1,
  `total_m` int NOT NULL DEFAULT 0,
  `current_m` int NOT NULL DEFAULT 0,
  `join_password` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `EinhasadBlessBuff` int NULL DEFAULT NULL,
  `Buff_List1` int NULL DEFAULT NULL,
  `Buff_List2` int NULL DEFAULT NULL,
  `Buff_List3` int NULL DEFAULT NULL,
  `dayDungeonTime` datetime NULL DEFAULT NULL,
  `weekDungeonTime` datetime NULL DEFAULT NULL,
  `vowTime` datetime NULL DEFAULT NULL,
  `vowCount` int NOT NULL DEFAULT 0,
  `clanNameChange` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `storeAllows` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `limit_level` int NOT NULL DEFAULT 30,
  `limit_user_names` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`clan_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 331885653 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_emblem_attention
-- ----------------------------
DROP TABLE IF EXISTS `clan_emblem_attention`;
CREATE TABLE `clan_emblem_attention`  (
  `clanname` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `attentionClanname` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`clanname`, `attentionClanname`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_history
-- ----------------------------
DROP TABLE IF EXISTS `clan_history`;
CREATE TABLE `clan_history`  (
  `num` int NOT NULL AUTO_INCREMENT,
  `clan_id` int NOT NULL DEFAULT 0,
  `ckck` int NOT NULL DEFAULT 0,
  `char_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `item_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `item_count` int NOT NULL DEFAULT 0,
  `time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`num`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_joinning
-- ----------------------------
DROP TABLE IF EXISTS `clan_joinning`;
CREATE TABLE `clan_joinning`  (
  `pledge_uid` int NOT NULL DEFAULT 0,
  `pledge_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `user_uid` int NOT NULL DEFAULT 0,
  `user_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `join_message` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `class_type` int NOT NULL DEFAULT 0,
  `join_date` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`pledge_uid`, `user_uid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_matching_apclist
-- ----------------------------
DROP TABLE IF EXISTS `clan_matching_apclist`;
CREATE TABLE `clan_matching_apclist`  (
  `pc_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `pc_objid` int NULL DEFAULT NULL,
  `clan_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_matching_list
-- ----------------------------
DROP TABLE IF EXISTS `clan_matching_list`;
CREATE TABLE `clan_matching_list`  (
  `clanname` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `text` varchar(500) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `type` int NULL DEFAULT NULL,
  PRIMARY KEY (`clanname`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `clan_warehouse`;
CREATE TABLE `clan_warehouse`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `clan_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_id` int NULL DEFAULT NULL,
  `item_name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NULL DEFAULT NULL,
  `is_equipped` int NULL DEFAULT NULL,
  `enchantlvl` int NULL DEFAULT NULL,
  `is_id` int NULL DEFAULT NULL,
  `durability` int NULL DEFAULT NULL,
  `charge_count` int NULL DEFAULT NULL,
  `remaining_time` int NULL DEFAULT NULL,
  `last_used` datetime NULL DEFAULT NULL,
  `attr_enchantlvl` int NULL DEFAULT NULL,
  `special_enchant` int NULL DEFAULT NULL,
  `doll_ablity` int NULL DEFAULT 0,
  `package` tinyint NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`clan_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_warehouse_list
-- ----------------------------
DROP TABLE IF EXISTS `clan_warehouse_list`;
CREATE TABLE `clan_warehouse_list`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `clanid` int NULL DEFAULT 0,
  `list` varchar(200) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  `date` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for clan_warehouse_log
-- ----------------------------
DROP TABLE IF EXISTS `clan_warehouse_log`;
CREATE TABLE `clan_warehouse_log`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `clan_name` varchar(30) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `item_name` varchar(30) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `item_count` int UNSIGNED NOT NULL,
  `type` bit(1) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for commands
-- ----------------------------
DROP TABLE IF EXISTS `commands`;
CREATE TABLE `commands`  (
  `name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `access_level` int NOT NULL DEFAULT 9999,
  `class_name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `description` varchar(300) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for connect_reward
-- ----------------------------
DROP TABLE IF EXISTS `connect_reward`;
CREATE TABLE `connect_reward`  (
  `id` int NOT NULL DEFAULT 0,
  `description` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `reward_type` enum('NORMAL','STANBY_SERVER') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NORMAL',
  `reward_item_id` int NOT NULL DEFAULT 0,
  `reward_item_count` int NOT NULL DEFAULT 0,
  `reward_interval_minute` int NOT NULL DEFAULT 0,
  `reward_start_date` datetime NULL DEFAULT NULL,
  `reward_finish_date` datetime NULL DEFAULT NULL,
  `is_use` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for craft_block
-- ----------------------------
DROP TABLE IF EXISTS `craft_block`;
CREATE TABLE `craft_block`  (
  `craft_id` int NOT NULL DEFAULT 0,
  `craft_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`craft_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for craft_info
-- ----------------------------
DROP TABLE IF EXISTS `craft_info`;
CREATE TABLE `craft_info`  (
  `craft_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `output_name_id` int NOT NULL DEFAULT 0,
  `probability_million` int NOT NULL DEFAULT 0,
  `preserve_name_ids` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `success_preserve_count` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `failure_preserve_count` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `is_success_count_type` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`craft_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21795 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for craft_npcs
-- ----------------------------
DROP TABLE IF EXISTS `craft_npcs`;
CREATE TABLE `craft_npcs`  (
  `npc_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `npc_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `craft_id_list` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`npc_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7310088 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for craft_success_count_user
-- ----------------------------
DROP TABLE IF EXISTS `craft_success_count_user`;
CREATE TABLE `craft_success_count_user`  (
  `accountName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `charId` int NOT NULL DEFAULT 0,
  `craftId` int NOT NULL DEFAULT 0,
  `success_count_type` enum('World','Account','Character','AllServers') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'World',
  `currentCount` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`accountName`, `charId`, `craftId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dogfight_tickets
-- ----------------------------
DROP TABLE IF EXISTS `dogfight_tickets`;
CREATE TABLE `dogfight_tickets`  (
  `item_id` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `price` int NOT NULL,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for droplist
-- ----------------------------
DROP TABLE IF EXISTS `droplist`;
CREATE TABLE `droplist`  (
  `mobId` int NOT NULL DEFAULT 0,
  `mobname_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `mobname_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `moblevel` int NOT NULL DEFAULT 0,
  `itemId` int NOT NULL DEFAULT 0,
  `itemname_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `itemname_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `min` int NOT NULL DEFAULT 0,
  `max` int NOT NULL DEFAULT 0,
  `chance` int NOT NULL DEFAULT 0,
  `Enchant` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`mobId`, `itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for droptype_npc
-- ----------------------------
DROP TABLE IF EXISTS `droptype_npc`;
CREATE TABLE `droptype_npc`  (
  `mobId` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `type` enum('map','share') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'map',
  PRIMARY KEY (`mobId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dungeon
-- ----------------------------
DROP TABLE IF EXISTS `dungeon`;
CREATE TABLE `dungeon`  (
  `src_x` int NOT NULL DEFAULT 0,
  `src_y` int NOT NULL DEFAULT 0,
  `src_mapid` int NOT NULL DEFAULT 0,
  `new_x` int NOT NULL DEFAULT 0,
  `new_y` int NOT NULL DEFAULT 0,
  `new_mapid` int NOT NULL DEFAULT 0,
  `new_heading` int NOT NULL DEFAULT 1,
  `min_level` int NOT NULL DEFAULT 0,
  `max_level` int NOT NULL DEFAULT 0,
  `note` varchar(75) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`src_x`, `src_y`, `src_mapid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dungeon_random
-- ----------------------------
DROP TABLE IF EXISTS `dungeon_random`;
CREATE TABLE `dungeon_random`  (
  `src_x` int NOT NULL DEFAULT 0,
  `src_y` int NOT NULL DEFAULT 0,
  `src_mapid` int NOT NULL DEFAULT 0,
  `new_x1` int NOT NULL DEFAULT 0,
  `new_y1` int NOT NULL DEFAULT 0,
  `new_mapid1` int NOT NULL DEFAULT 0,
  `new_x2` int NOT NULL DEFAULT 0,
  `new_y2` int NOT NULL DEFAULT 0,
  `new_mapid2` int NOT NULL DEFAULT 0,
  `new_x3` int NOT NULL DEFAULT 0,
  `new_y3` int NOT NULL DEFAULT 0,
  `new_mapid3` int NOT NULL DEFAULT 0,
  `new_x4` int NOT NULL DEFAULT 0,
  `new_y4` int NOT NULL DEFAULT 0,
  `new_mapid4` int NOT NULL DEFAULT 0,
  `new_x5` int NOT NULL DEFAULT 0,
  `new_y5` int NOT NULL DEFAULT 0,
  `new_mapid5` int NOT NULL DEFAULT 0,
  `new_heading` int NOT NULL DEFAULT 1,
  `note` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`src_x`, `src_y`, `src_mapid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dungeon_timer
-- ----------------------------
DROP TABLE IF EXISTS `dungeon_timer`;
CREATE TABLE `dungeon_timer`  (
  `timerId` int NOT NULL DEFAULT 0,
  `desc` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `descId` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `useType` enum('ACCOUNT','CHARACTER') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'ACCOUNT',
  `mapIds` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `timerValue` int NOT NULL DEFAULT 0,
  `bonusLevel` int NOT NULL DEFAULT 0,
  `bonusValue` int NOT NULL DEFAULT 0,
  `pccafeBonusValue` int NOT NULL DEFAULT 0,
  `resetType` enum('DAY','WEEK','NONE') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'DAY',
  `minLimitLevel` int NOT NULL DEFAULT 0,
  `maxLimitLevel` int NOT NULL DEFAULT 0,
  `serialId` int NOT NULL DEFAULT 0,
  `serialDescId` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `maxChargeCount` int NOT NULL DEFAULT 0,
  `group` enum('NONE','HIDDEN_FIELD','SILVER_KNIGHT_DUNGEON','HIDDEN_FIELD_BOOST') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  PRIMARY KEY (`timerId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dungeon_timer_account
-- ----------------------------
DROP TABLE IF EXISTS `dungeon_timer_account`;
CREATE TABLE `dungeon_timer_account`  (
  `account` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `timerId` int NOT NULL DEFAULT 0,
  `remainSecond` int NOT NULL DEFAULT 0,
  `chargeCount` int NOT NULL DEFAULT 0,
  `resetTime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`account`, `timerId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dungeon_timer_character
-- ----------------------------
DROP TABLE IF EXISTS `dungeon_timer_character`;
CREATE TABLE `dungeon_timer_character`  (
  `charId` int NOT NULL DEFAULT 0,
  `timerId` int NOT NULL DEFAULT 0,
  `remainSecond` int NOT NULL DEFAULT 0,
  `chargeCount` int NOT NULL DEFAULT 0,
  `resetTime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`charId`, `timerId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for dungeon_timer_item
-- ----------------------------
DROP TABLE IF EXISTS `dungeon_timer_item`;
CREATE TABLE `dungeon_timer_item`  (
  `itemId` int NOT NULL DEFAULT 0,
  `desc` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `timerId` int NOT NULL DEFAULT 0,
  `groupId` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for enchant_result
-- ----------------------------
DROP TABLE IF EXISTS `enchant_result`;
CREATE TABLE `enchant_result`  (
  `item_id` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `color_item` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `bm_scroll` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for etcitem
-- ----------------------------
DROP TABLE IF EXISTS `etcitem`;
CREATE TABLE `etcitem`  (
  `item_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_name_id` int UNSIGNED NOT NULL DEFAULT 0,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_powerbook` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `note` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_id` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `itemGrade` enum('ONLY','MYTH','LEGEND','HERO','RARE','ADVANC','NORMAL') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NORMAL',
  `item_type` enum('ARROW','WAND','LIGHT','GEM','TOTEM','FIRE_CRACKER','POTION','FOOD','SCROLL','QUEST_ITEM','SPELL_BOOK','PET_ITEM','OTHER','MATERIAL','EVENT','STING','TREASURE_BOX') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'OTHER',
  `use_type` enum('NONE','NORMAL','WAND1','WAND','SPELL_LONG','NTELE','IDENTIFY','RES','TELEPORT','INVISABLE','LETTER','LETTER_W','CHOICE','INSTRUMENT','SOSC','SPELL_SHORT','T_SHIRT','CLOAK','GLOVE','BOOTS','HELMET','RING','AMULET','SHIELD','GARDER','DAI','ZEL','BLANK','BTELE','SPELL_BUFF','CCARD','CCARD_W','VCARD','VCARD_W','WCARD','WCARD_W','BELT','SPELL_LONG2','EARRING','FISHING_ROD','RON','RON_2','ACCZEL','PAIR','HEALING','SHOULDER','BADGE','POTENTIAL_SCROLL','SPELLMELT','ELIXER_RON','INVENTORY_BONUS','TAM_FRUIT','RACE_TICKET','PAIR_2','MAGICDOLL','SENTENCE','SHOULDER_2','BADGE_2','PET_POTION','GARDER_2','DOMINATION_POLY','PENDANT','SHOVEL','LEV_100_POLY','SMELTING','PURIFY','CHARGED_MAP_TIME') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `material` enum('NONE(-)','LIQUID(액체)','WAX(밀랍)','VEGGY(식물성)','FLESH(동물성)','PAPER(종이)','CLOTH(천)','LEATHER(가죽)','WOOD(나무)','BONE(뼈)','DRAGON_HIDE(용비늘)','IRON(철)','METAL(금속)','COPPER(구리)','SILVER(은)','GOLD(금)','PLATINUM(백금)','MITHRIL(미스릴)','PLASTIC(블랙미스릴)','GLASS(유리)','GEMSTONE(보석)','MINERAL(광석)','ORIHARUKON(오리하루콘)','DRANIUM(드라니움)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(-)',
  `weight` int UNSIGNED NOT NULL DEFAULT 0,
  `iconId` int UNSIGNED NOT NULL DEFAULT 0,
  `spriteId` int UNSIGNED NOT NULL DEFAULT 0,
  `merge` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `max_charge_count` int UNSIGNED NOT NULL DEFAULT 0,
  `dmg_small` int UNSIGNED NOT NULL DEFAULT 0,
  `dmg_large` int UNSIGNED NOT NULL DEFAULT 0,
  `ac_bonus` int NOT NULL DEFAULT 0,
  `shortHit` int UNSIGNED NOT NULL DEFAULT 0,
  `shortDmg` int UNSIGNED NOT NULL DEFAULT 0,
  `longHit` int UNSIGNED NOT NULL DEFAULT 0,
  `longDmg` int UNSIGNED NOT NULL DEFAULT 0,
  `add_str` int NOT NULL DEFAULT 0,
  `add_con` int NOT NULL DEFAULT 0,
  `add_dex` int NOT NULL DEFAULT 0,
  `add_int` int NOT NULL DEFAULT 0,
  `add_wis` int NOT NULL DEFAULT 0,
  `add_cha` int NOT NULL DEFAULT 0,
  `add_hp` int NOT NULL DEFAULT 0,
  `add_mp` int NOT NULL DEFAULT 0,
  `add_hpr` int NOT NULL DEFAULT 0,
  `add_mpr` int NOT NULL DEFAULT 0,
  `add_sp` int NOT NULL DEFAULT 0,
  `min_lvl` int UNSIGNED NOT NULL DEFAULT 0,
  `max_lvl` int UNSIGNED NOT NULL DEFAULT 0,
  `m_def` int NOT NULL DEFAULT 0,
  `carryBonus` int NOT NULL DEFAULT 0,
  `defense_water` int NOT NULL DEFAULT 0,
  `defense_wind` int NOT NULL DEFAULT 0,
  `defense_fire` int NOT NULL DEFAULT 0,
  `defense_earth` int NOT NULL DEFAULT 0,
  `attr_all` int NOT NULL DEFAULT 0,
  `regist_stone` int NOT NULL DEFAULT 0,
  `regist_sleep` int NOT NULL DEFAULT 0,
  `regist_freeze` int NOT NULL DEFAULT 0,
  `regist_blind` int NOT NULL DEFAULT 0,
  `regist_skill` int NOT NULL DEFAULT 0,
  `regist_spirit` int NOT NULL DEFAULT 0,
  `regist_dragon` int NOT NULL DEFAULT 0,
  `regist_fear` int NOT NULL DEFAULT 0,
  `regist_all` int NOT NULL DEFAULT 0,
  `hitup_skill` int NOT NULL DEFAULT 0,
  `hitup_spirit` int NOT NULL DEFAULT 0,
  `hitup_dragon` int NOT NULL DEFAULT 0,
  `hitup_fear` int NOT NULL DEFAULT 0,
  `hitup_all` int NOT NULL DEFAULT 0,
  `hitup_magic` int NOT NULL DEFAULT 0,
  `damage_reduction` int NOT NULL DEFAULT 0,
  `MagicDamageReduction` int NOT NULL DEFAULT 0,
  `reductionEgnor` int NOT NULL DEFAULT 0,
  `reductionPercent` int NOT NULL DEFAULT 0,
  `PVPDamage` int NOT NULL DEFAULT 0,
  `PVPDamageReduction` int NOT NULL DEFAULT 0,
  `PVPDamageReductionPercent` int NOT NULL DEFAULT 0,
  `PVPMagicDamageReduction` int NOT NULL DEFAULT 0,
  `PVPReductionEgnor` int NOT NULL DEFAULT 0,
  `PVPMagicDamageReductionEgnor` int NOT NULL DEFAULT 0,
  `abnormalStatusDamageReduction` int NOT NULL DEFAULT 0,
  `abnormalStatusPVPDamageReduction` int NOT NULL DEFAULT 0,
  `PVPDamagePercent` int NOT NULL DEFAULT 0,
  `expBonus` int NOT NULL DEFAULT 0,
  `rest_exp_reduce_efficiency` int NOT NULL DEFAULT 0,
  `shortCritical` int NOT NULL DEFAULT 0,
  `longCritical` int NOT NULL DEFAULT 0,
  `magicCritical` int NOT NULL DEFAULT 0,
  `addDg` int NOT NULL DEFAULT 0,
  `addEr` int NOT NULL DEFAULT 0,
  `addMe` int NOT NULL DEFAULT 0,
  `poisonRegist` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `imunEgnor` int NOT NULL DEFAULT 0,
  `stunDuration` int NOT NULL DEFAULT 0,
  `tripleArrowStun` int NOT NULL DEFAULT 0,
  `strangeTimeIncrease` int NOT NULL DEFAULT 0,
  `strangeTimeDecrease` int NOT NULL DEFAULT 0,
  `potionRegist` int NOT NULL DEFAULT 0,
  `potionPercent` int NOT NULL DEFAULT 0,
  `potionValue` int NOT NULL DEFAULT 0,
  `hprAbsol32Second` int NOT NULL DEFAULT 0,
  `mprAbsol64Second` int NOT NULL DEFAULT 0,
  `mprAbsol16Second` int NOT NULL DEFAULT 0,
  `hpPotionDelayDecrease` int NOT NULL DEFAULT 0,
  `hpPotionCriticalProb` int NOT NULL DEFAULT 0,
  `increaseArmorSkillProb` int NOT NULL DEFAULT 0,
  `attackSpeedDelayRate` int NOT NULL DEFAULT 0,
  `moveSpeedDelayRate` int NOT NULL DEFAULT 0,
  `buffDurationSecond` int NOT NULL DEFAULT 0,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  `bless` int UNSIGNED NOT NULL DEFAULT 1,
  `trade` int UNSIGNED NOT NULL DEFAULT 0,
  `retrieve` int UNSIGNED NOT NULL DEFAULT 0,
  `specialretrieve` int UNSIGNED NOT NULL DEFAULT 0,
  `cant_delete` int UNSIGNED NOT NULL DEFAULT 0,
  `cant_sell` int UNSIGNED NOT NULL DEFAULT 0,
  `delay_id` int UNSIGNED NOT NULL DEFAULT 0,
  `delay_time` int UNSIGNED NOT NULL DEFAULT 0,
  `delay_effect` int UNSIGNED NOT NULL DEFAULT 0,
  `food_volume` int UNSIGNED NOT NULL DEFAULT 0,
  `save_at_once` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `Magic_name` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `level` int UNSIGNED NOT NULL DEFAULT 0,
  `attr` enum('EARTH','AIR','WATER','FIRE','NONE') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `alignment` enum('CAOTIC','NEUTRAL','LAWFUL','NONE') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `use_royal` int UNSIGNED NOT NULL DEFAULT 0,
  `use_knight` int UNSIGNED NOT NULL DEFAULT 0,
  `use_mage` int UNSIGNED NOT NULL DEFAULT 0,
  `use_elf` int UNSIGNED NOT NULL DEFAULT 0,
  `use_darkelf` int UNSIGNED NOT NULL DEFAULT 0,
  `use_dragonknight` int UNSIGNED NOT NULL DEFAULT 0,
  `use_illusionist` int UNSIGNED NOT NULL DEFAULT 0,
  `use_warrior` int UNSIGNED NOT NULL DEFAULT 0,
  `use_fencer` int UNSIGNED NOT NULL DEFAULT 0,
  `use_lancer` int UNSIGNED NOT NULL DEFAULT 0,
  `skill_type` enum('passive','active','none') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'none',
  `etc_value` int NOT NULL DEFAULT 0,
  `limit_type` enum('WORLD_WAR','BEGIN_ZONE','NONE') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `prob` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4100692 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for event
-- ----------------------------
DROP TABLE IF EXISTS `event`;
CREATE TABLE `event`  (
  `event_id` int NOT NULL DEFAULT 0,
  `description` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_en` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `start_date` datetime NULL DEFAULT NULL,
  `finish_date` datetime NULL DEFAULT NULL,
  `broadcast` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `event_flag` enum('SPAWN_NPC','DROP_ADENA','DROP_ITEM','POLY') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'SPAWN_NPC',
  `spawn_data` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `drop_rate` float NOT NULL DEFAULT 1,
  `finish_delete_item` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `finish_map_rollback` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`event_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for exp
-- ----------------------------
DROP TABLE IF EXISTS `exp`;
CREATE TABLE `exp`  (
  `level` int NOT NULL,
  `exp` int NOT NULL DEFAULT 0,
  `panalty` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`level`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for favorbook
-- ----------------------------
DROP TABLE IF EXISTS `favorbook`;
CREATE TABLE `favorbook`  (
  `listId` int NOT NULL DEFAULT 0,
  `category` int NOT NULL DEFAULT 0,
  `slotId` int NOT NULL,
  `itemIds` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `note` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`category`, `slotId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for free_pvp_region
-- ----------------------------
DROP TABLE IF EXISTS `free_pvp_region`;
CREATE TABLE `free_pvp_region`  (
  `worldNumber` int NOT NULL DEFAULT 0,
  `desc` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `isFreePvpZone` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  `box_index` int NOT NULL DEFAULT 0,
  `box_sx` int NOT NULL DEFAULT 0,
  `box_sy` int NOT NULL DEFAULT 0,
  `box_ex` int NOT NULL DEFAULT 0,
  `box_ey` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`worldNumber`, `box_index`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for getback
-- ----------------------------
DROP TABLE IF EXISTS `getback`;
CREATE TABLE `getback`  (
  `area_x1` int NOT NULL DEFAULT 0,
  `area_y1` int NOT NULL DEFAULT 0,
  `area_x2` int NOT NULL DEFAULT 0,
  `area_y2` int NOT NULL DEFAULT 0,
  `area_mapid` int NOT NULL DEFAULT 0,
  `getback_x1` int NOT NULL DEFAULT 0,
  `getback_y1` int NOT NULL DEFAULT 0,
  `getback_x2` int NOT NULL DEFAULT 0,
  `getback_y2` int NOT NULL DEFAULT 0,
  `getback_x3` int NOT NULL DEFAULT 0,
  `getback_y3` int NOT NULL DEFAULT 0,
  `getback_mapid` int NOT NULL DEFAULT 0,
  `getback_townid` int UNSIGNED NOT NULL DEFAULT 0,
  `getback_townid_elf` int UNSIGNED NOT NULL DEFAULT 0,
  `getback_townid_darkelf` int UNSIGNED NOT NULL DEFAULT 0,
  `scrollescape` int NOT NULL DEFAULT 1,
  `note` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`area_x1`, `area_y1`, `area_x2`, `area_y2`, `area_mapid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for getback_restart
-- ----------------------------
DROP TABLE IF EXISTS `getback_restart`;
CREATE TABLE `getback_restart`  (
  `area` int NOT NULL DEFAULT 0,
  `note` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `locx` int NOT NULL DEFAULT 0,
  `locy` int NOT NULL DEFAULT 0,
  `mapid` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`area`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for house
-- ----------------------------
DROP TABLE IF EXISTS `house`;
CREATE TABLE `house`  (
  `house_id` int UNSIGNED NOT NULL DEFAULT 0,
  `house_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `house_area` int UNSIGNED NOT NULL DEFAULT 0,
  `location` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `keeper_id` int UNSIGNED NOT NULL DEFAULT 0,
  `is_on_sale` int UNSIGNED NOT NULL DEFAULT 0,
  `is_purchase_basement` int UNSIGNED NOT NULL DEFAULT 0,
  `tax_deadline` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`house_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for hunting_quest
-- ----------------------------
DROP TABLE IF EXISTS `hunting_quest`;
CREATE TABLE `hunting_quest`  (
  `area_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `map_number` int NOT NULL DEFAULT 0,
  `location_desc` int NULL DEFAULT NULL,
  `quest_id` int NOT NULL DEFAULT 0,
  `is_use` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  PRIMARY KEY (`quest_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for hunting_quest_teleport
-- ----------------------------
DROP TABLE IF EXISTS `hunting_quest_teleport`;
CREATE TABLE `hunting_quest_teleport`  (
  `action_string` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `tel_mapid` int NOT NULL DEFAULT 0,
  `tel_x` int NULL DEFAULT NULL,
  `tel_y` int NULL DEFAULT NULL,
  `tel_itemid` int NULL DEFAULT NULL,
  PRIMARY KEY (`action_string`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for inter_race_region
-- ----------------------------
DROP TABLE IF EXISTS `inter_race_region`;
CREATE TABLE `inter_race_region`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `loc_x` int NULL DEFAULT NULL,
  `loc_y` int NULL DEFAULT NULL,
  `loc_mapid` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2977 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for item_bookmark
-- ----------------------------
DROP TABLE IF EXISTS `item_bookmark`;
CREATE TABLE `item_bookmark`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `book_id` int UNSIGNED NOT NULL DEFAULT 0,
  `item_id` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 147147 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for item_box
-- ----------------------------
DROP TABLE IF EXISTS `item_box`;
CREATE TABLE `item_box`  (
  `boxId` int NOT NULL DEFAULT 0,
  `name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `classType` enum('lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown','all') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'all',
  `itemId` int NOT NULL DEFAULT 0,
  `count` int NOT NULL DEFAULT 1,
  `enchant` int NOT NULL DEFAULT 0,
  `bless` int NOT NULL DEFAULT 1,
  `attr` int NOT NULL DEFAULT 0,
  `identi` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `limitTime` int NOT NULL DEFAULT 0,
  `limitMaps` varchar(200) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `questBox` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `effectId` int NOT NULL DEFAULT 0,
  `chance` int NOT NULL DEFAULT 100,
  `validateItems` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `boxDelete` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  PRIMARY KEY (`boxId`, `classType`, `itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for item_buff
-- ----------------------------
DROP TABLE IF EXISTS `item_buff`;
CREATE TABLE `item_buff`  (
  `item_id` int NOT NULL DEFAULT 0,
  `name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `skill_ids` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `delete` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for item_click_message
-- ----------------------------
DROP TABLE IF EXISTS `item_click_message`;
CREATE TABLE `item_click_message`  (
  `itemId` int NOT NULL,
  `type` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `msg` varchar(500) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `delete` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for item_collection
-- ----------------------------
DROP TABLE IF EXISTS `item_collection`;
CREATE TABLE `item_collection`  (
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(70) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `type` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for item_enchant_ablity
-- ----------------------------
DROP TABLE IF EXISTS `item_enchant_ablity`;
CREATE TABLE `item_enchant_ablity`  (
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(70) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `enchant` int NOT NULL DEFAULT 0,
  `ac_bonus` int NOT NULL DEFAULT 0,
  `ac_sub` int NOT NULL DEFAULT 0,
  `str` int NOT NULL DEFAULT 0,
  `con` int NOT NULL DEFAULT 0,
  `dex` int NOT NULL DEFAULT 0,
  `int` int NOT NULL DEFAULT 0,
  `wis` int NOT NULL DEFAULT 0,
  `cha` int NOT NULL DEFAULT 0,
  `shortDamage` int NOT NULL DEFAULT 0,
  `shortHit` int NOT NULL DEFAULT 0,
  `shortCritical` int NOT NULL DEFAULT 0,
  `longDamage` int NOT NULL DEFAULT 0,
  `longHit` int NOT NULL DEFAULT 0,
  `longCritical` int NOT NULL DEFAULT 0,
  `spellpower` int NOT NULL DEFAULT 0,
  `magicHit` int NOT NULL DEFAULT 0,
  `magicCritical` int NOT NULL DEFAULT 0,
  `magicDamage` int NOT NULL DEFAULT 0,
  `maxHp` int NOT NULL DEFAULT 0,
  `maxMp` int NOT NULL DEFAULT 0,
  `hpRegen` int NOT NULL DEFAULT 0,
  `mpRegen` int NOT NULL DEFAULT 0,
  `baseHpRate` int NOT NULL DEFAULT 0,
  `baseMpRate` int NOT NULL DEFAULT 0,
  `attrFire` int NOT NULL DEFAULT 0,
  `attrWater` int NOT NULL DEFAULT 0,
  `attrWind` int NOT NULL DEFAULT 0,
  `attrEarth` int NOT NULL DEFAULT 0,
  `attrAll` int NOT NULL DEFAULT 0,
  `mr` int NOT NULL DEFAULT 0,
  `carryBonus` int NOT NULL DEFAULT 0,
  `dg` int NOT NULL DEFAULT 0,
  `er` int NOT NULL DEFAULT 0,
  `me` int NOT NULL DEFAULT 0,
  `reduction` int NOT NULL DEFAULT 0,
  `reductionEgnor` int NOT NULL DEFAULT 0,
  `reductionMagic` int NOT NULL DEFAULT 0,
  `reductionPercent` int NOT NULL DEFAULT 0,
  `PVPDamage` int NOT NULL DEFAULT 0,
  `PVPReduction` int NOT NULL DEFAULT 0,
  `PVPReductionPercent` int NOT NULL DEFAULT 0,
  `PVPReductionEgnor` int NOT NULL DEFAULT 0,
  `PVPReductionMagic` int NOT NULL DEFAULT 0,
  `PVPReductionMagicEgnor` int NOT NULL DEFAULT 0,
  `abnormalStatusDamageReduction` int NOT NULL DEFAULT 0,
  `abnormalStatusPVPDamageReduction` int NOT NULL DEFAULT 0,
  `PVPDamagePercent` int NOT NULL DEFAULT 0,
  `registBlind` int NOT NULL DEFAULT 0,
  `registFreeze` int NOT NULL DEFAULT 0,
  `registSleep` int NOT NULL DEFAULT 0,
  `registStone` int NOT NULL DEFAULT 0,
  `toleranceSkill` int NOT NULL DEFAULT 0,
  `toleranceSpirit` int NOT NULL DEFAULT 0,
  `toleranceDragon` int NOT NULL DEFAULT 0,
  `toleranceFear` int NOT NULL DEFAULT 0,
  `toleranceAll` int NOT NULL DEFAULT 0,
  `hitupSkill` int NOT NULL DEFAULT 0,
  `hitupSpirit` int NOT NULL DEFAULT 0,
  `hitupDragon` int NOT NULL DEFAULT 0,
  `hitupFear` int NOT NULL DEFAULT 0,
  `hitupAll` int NOT NULL DEFAULT 0,
  `potionPlusDefens` int NOT NULL DEFAULT 0,
  `potionPlusPercent` int NOT NULL DEFAULT 0,
  `potionPlusValue` int NOT NULL DEFAULT 0,
  `hprAbsol32Second` int NOT NULL DEFAULT 0,
  `mprAbsol64Second` int NOT NULL DEFAULT 0,
  `mprAbsol16Second` int NOT NULL DEFAULT 0,
  `imunEgnor` int NOT NULL DEFAULT 0,
  `expBonus` int NOT NULL DEFAULT 0,
  `einBlessExp` int NOT NULL DEFAULT 0,
  `rest_exp_reduce_efficiency` int NOT NULL DEFAULT 0,
  `fowSlayerDamage` int NOT NULL DEFAULT 0,
  `titanUp` int NOT NULL DEFAULT 0,
  `stunDuration` int NOT NULL DEFAULT 0,
  `tripleArrowStun` int NOT NULL DEFAULT 0,
  `vanguardTime` int NOT NULL DEFAULT 0,
  `strangeTimeIncrease` int NOT NULL DEFAULT 0,
  `strangeTimeDecrease` int NOT NULL DEFAULT 0,
  `hpPotionDelayDecrease` int NOT NULL DEFAULT 0,
  `hpPotionCriticalProb` int NOT NULL DEFAULT 0,
  `increaseArmorSkillProb` int NOT NULL DEFAULT 0,
  `returnLockDuraion` int NOT NULL DEFAULT 0,
  `attackSpeedDelayRate` int NOT NULL DEFAULT 0,
  `moveSpeedDelayRate` int NOT NULL DEFAULT 0,
  `magicName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`itemId`, `enchant`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for item_key_boss
-- ----------------------------
DROP TABLE IF EXISTS `item_key_boss`;
CREATE TABLE `item_key_boss`  (
  `item_obj_id` int NOT NULL,
  `key_id` int NOT NULL,
  PRIMARY KEY (`item_obj_id`, `key_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for item_ment
-- ----------------------------
DROP TABLE IF EXISTS `item_ment`;
CREATE TABLE `item_ment`  (
  `itemId` int NOT NULL DEFAULT 0,
  `itemName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `mentType` enum('treasurebox','craft','drop','pickup') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'pickup',
  PRIMARY KEY (`itemId`, `mentType`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for item_selector
-- ----------------------------
DROP TABLE IF EXISTS `item_selector`;
CREATE TABLE `item_selector`  (
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `selectItemId` int NOT NULL DEFAULT 0,
  `selectName` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `select_desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `count` int NOT NULL DEFAULT 1,
  `enchant` int NOT NULL DEFAULT 0,
  `attr` enum('5','4','3','2','1','0') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '0',
  `bless` int NOT NULL DEFAULT 1,
  `limitTime` int NOT NULL DEFAULT 0,
  `delete` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  PRIMARY KEY (`itemId`, `selectItemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for item_selector_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `item_selector_warehouse`;
CREATE TABLE `item_selector_warehouse`  (
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `selectItemId` int NOT NULL DEFAULT 0,
  `selectName` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `select_desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `index` int NOT NULL DEFAULT 0,
  `enchantLevel` int NOT NULL DEFAULT 0,
  `attrLevel` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`itemId`, `selectItemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for item_terms
-- ----------------------------
DROP TABLE IF EXISTS `item_terms`;
CREATE TABLE `item_terms`  (
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `termMinut` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for letter
-- ----------------------------
DROP TABLE IF EXISTS `letter`;
CREATE TABLE `letter`  (
  `item_object_id` int UNSIGNED NOT NULL DEFAULT 0,
  `code` int UNSIGNED NOT NULL DEFAULT 0,
  `sender` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `receiver` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `date` datetime NULL DEFAULT NULL,
  `template_id` int UNSIGNED NOT NULL DEFAULT 0,
  `subject` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(2000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `isCheck` bit(1) NULL DEFAULT NULL,
  PRIMARY KEY (`item_object_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for letter_command
-- ----------------------------
DROP TABLE IF EXISTS `letter_command`;
CREATE TABLE `letter_command`  (
  `id` int NOT NULL,
  `subject` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `content` varchar(500) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for letter_spam
-- ----------------------------
DROP TABLE IF EXISTS `letter_spam`;
CREATE TABLE `letter_spam`  (
  `no` int NOT NULL DEFAULT 0,
  `name` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `spamname` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  PRIMARY KEY (`no`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for levelup_quests_item
-- ----------------------------
DROP TABLE IF EXISTS `levelup_quests_item`;
CREATE TABLE `levelup_quests_item`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `level` int NOT NULL DEFAULT 0,
  `type` int NOT NULL DEFAULT 0,
  `note` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `item_id` int NOT NULL DEFAULT 0,
  `count` int NOT NULL DEFAULT 0,
  `enchant` int NOT NULL DEFAULT 0,
  `attrlevel` int NOT NULL DEFAULT 0,
  `bless` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type`(`type` ASC) USING BTREE,
  INDEX `bid`(`level` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_adena_monster
-- ----------------------------
DROP TABLE IF EXISTS `log_adena_monster`;
CREATE TABLE `log_adena_monster`  (
  `startTime` datetime NULL DEFAULT NULL,
  `endTime` datetime NULL DEFAULT NULL,
  `accounts` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `name` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_adena_shop
-- ----------------------------
DROP TABLE IF EXISTS `log_adena_shop`;
CREATE TABLE `log_adena_shop`  (
  `startTime` datetime NULL DEFAULT NULL,
  `endTime` datetime NULL DEFAULT NULL,
  `accounts` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `name` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `count` int NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for log_chat
-- ----------------------------
DROP TABLE IF EXISTS `log_chat`;
CREATE TABLE `log_chat`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `char_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `clan_id` int NOT NULL,
  `clan_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `locx` int NOT NULL,
  `locy` int NOT NULL,
  `mapid` int NOT NULL,
  `type` int NOT NULL,
  `target_account_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `target_id` int NULL DEFAULT 0,
  `target_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `target_clan_id` int NULL DEFAULT NULL,
  `target_clan_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `target_locx` int NULL DEFAULT NULL,
  `target_locy` int NULL DEFAULT NULL,
  `target_mapid` int NULL DEFAULT NULL,
  `content` varchar(256) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_cwarehouse
-- ----------------------------
DROP TABLE IF EXISTS `log_cwarehouse`;
CREATE TABLE `log_cwarehouse`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `datetime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `type` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `clan_id` int NULL DEFAULT NULL,
  `clan_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `account` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `char_id` int NULL DEFAULT NULL,
  `char_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_id` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_enchantlvl` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_count` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_enchant
-- ----------------------------
DROP TABLE IF EXISTS `log_enchant`;
CREATE TABLE `log_enchant`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `char_id` int NOT NULL DEFAULT 0,
  `item_id` int UNSIGNED NOT NULL DEFAULT 0,
  `old_enchantlvl` int NOT NULL DEFAULT 0,
  `new_enchantlvl` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key_id`(`char_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 82 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_private_shop
-- ----------------------------
DROP TABLE IF EXISTS `log_private_shop`;
CREATE TABLE `log_private_shop`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `type` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `shop_account` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `shop_id` int NULL DEFAULT NULL,
  `shop_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `user_account` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `user_id` int NULL DEFAULT NULL,
  `user_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_id` int NULL DEFAULT NULL,
  `item_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_enchantlvl` int NULL DEFAULT NULL,
  `price` int NULL DEFAULT NULL,
  `item_count` int NULL DEFAULT NULL,
  `total_price` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_shop
-- ----------------------------
DROP TABLE IF EXISTS `log_shop`;
CREATE TABLE `log_shop`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `type` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `npc_id` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `user_account` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `user_id` int NULL DEFAULT NULL,
  `user_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_id` int NULL DEFAULT NULL,
  `item_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_enchantlvl` int NULL DEFAULT NULL,
  `price` int NULL DEFAULT NULL,
  `item_count` int NULL DEFAULT NULL,
  `total_price` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 227 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `log_warehouse`;
CREATE TABLE `log_warehouse`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `datetime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `type` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `account` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `char_id` int NULL DEFAULT NULL,
  `char_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_id` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_enchantlvl` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `item_count` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for magicdoll_info
-- ----------------------------
DROP TABLE IF EXISTS `magicdoll_info`;
CREATE TABLE `magicdoll_info`  (
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `dollNpcId` int NOT NULL DEFAULT 0,
  `blessItemId` int NULL DEFAULT 0,
  `grade` int NOT NULL DEFAULT 0,
  `bonusItemId` int NOT NULL DEFAULT 0,
  `bonusCount` int NOT NULL DEFAULT 0,
  `bonusInterval` int NOT NULL DEFAULT 0,
  `damageChance` int NOT NULL DEFAULT 0,
  `attackSkillEffectId` int NOT NULL DEFAULT 0,
  `haste` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for magicdoll_potential
-- ----------------------------
DROP TABLE IF EXISTS `magicdoll_potential`;
CREATE TABLE `magicdoll_potential`  (
  `bonusId` int NOT NULL DEFAULT 0,
  `name` varchar(70) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `isUse` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'true',
  `ac_bonus` int NOT NULL DEFAULT 0,
  `str` int NOT NULL DEFAULT 0,
  `con` int NOT NULL DEFAULT 0,
  `dex` int NOT NULL DEFAULT 0,
  `int` int NOT NULL DEFAULT 0,
  `wis` int NOT NULL DEFAULT 0,
  `cha` int NOT NULL DEFAULT 0,
  `allStatus` int NOT NULL DEFAULT 0,
  `shortDamage` int NOT NULL DEFAULT 0,
  `shortHit` int NOT NULL DEFAULT 0,
  `shortCritical` int NOT NULL DEFAULT 0,
  `longDamage` int NOT NULL DEFAULT 0,
  `longHit` int NOT NULL DEFAULT 0,
  `longCritical` int NOT NULL DEFAULT 0,
  `spellpower` int NOT NULL DEFAULT 0,
  `magicHit` int NOT NULL DEFAULT 0,
  `magicCritical` int NOT NULL DEFAULT 0,
  `hp` int NOT NULL DEFAULT 0,
  `mp` int NOT NULL DEFAULT 0,
  `hpr` int NOT NULL DEFAULT 0,
  `mpr` int NOT NULL DEFAULT 0,
  `hpStill` int NOT NULL DEFAULT 0,
  `mpStill` int NOT NULL DEFAULT 0,
  `stillChance` int NOT NULL DEFAULT 0,
  `hprAbsol` int NOT NULL DEFAULT 0,
  `mprAbsol` int NOT NULL DEFAULT 0,
  `attrFire` int NOT NULL DEFAULT 0,
  `attrWater` int NOT NULL DEFAULT 0,
  `attrWind` int NOT NULL DEFAULT 0,
  `attrEarth` int NOT NULL DEFAULT 0,
  `attrAll` int NOT NULL DEFAULT 0,
  `mr` int NOT NULL DEFAULT 0,
  `expBonus` int NOT NULL DEFAULT 0,
  `carryBonus` int NOT NULL DEFAULT 0,
  `dg` int NOT NULL DEFAULT 0,
  `er` int NOT NULL DEFAULT 0,
  `me` int NOT NULL DEFAULT 0,
  `reduction` int NOT NULL DEFAULT 0,
  `reductionEgnor` int NOT NULL DEFAULT 0,
  `reductionMagic` int NOT NULL DEFAULT 0,
  `reductionPercent` int NOT NULL DEFAULT 0,
  `PVPDamage` int NOT NULL DEFAULT 0,
  `PVPReduction` int NOT NULL DEFAULT 0,
  `PVPReductionEgnor` int NOT NULL DEFAULT 0,
  `PVPReductionMagic` int NOT NULL DEFAULT 0,
  `PVPReductionMagicEgnor` int NOT NULL DEFAULT 0,
  `toleranceSkill` int NOT NULL DEFAULT 0,
  `toleranceSpirit` int NOT NULL DEFAULT 0,
  `toleranceDragon` int NOT NULL DEFAULT 0,
  `toleranceFear` int NOT NULL DEFAULT 0,
  `toleranceAll` int NOT NULL DEFAULT 0,
  `hitupSkill` int NOT NULL DEFAULT 0,
  `hitupSpirit` int NOT NULL DEFAULT 0,
  `hitupDragon` int NOT NULL DEFAULT 0,
  `hitupFear` int NOT NULL DEFAULT 0,
  `hitupAll` int NOT NULL DEFAULT 0,
  `imunEgnor` int NOT NULL DEFAULT 0,
  `strangeTimeIncrease` int NOT NULL DEFAULT 0,
  `firstSpeed` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `secondSpeed` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `thirdSpeed` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `forthSpeed` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `skilId` int NOT NULL DEFAULT -1,
  `skillChance` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`bonusId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for map_balance
-- ----------------------------
DROP TABLE IF EXISTS `map_balance`;
CREATE TABLE `map_balance`  (
  `mapId` mediumint NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `damageType` enum('both','attack','magic') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'both',
  `damageValue` float NOT NULL DEFAULT 1,
  `reductionType` enum('both','attack','magic') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'both',
  `reductionValue` float NOT NULL DEFAULT 1,
  `expValue` float NOT NULL DEFAULT 1,
  `dropValue` float NOT NULL DEFAULT 1,
  `adenaValue` float NOT NULL DEFAULT 1,
  PRIMARY KEY (`mapId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for map_fix_key
-- ----------------------------
DROP TABLE IF EXISTS `map_fix_key`;
CREATE TABLE `map_fix_key`  (
  `locX` smallint UNSIGNED NOT NULL,
  `locY` smallint UNSIGNED NOT NULL,
  `mapId` smallint UNSIGNED NOT NULL,
  PRIMARY KEY (`locX`, `locY`, `mapId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for map_type
-- ----------------------------
DROP TABLE IF EXISTS `map_type`;
CREATE TABLE `map_type`  (
  `mapId` int NOT NULL DEFAULT 0,
  `desc` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `type` enum('COMBAT','SAFETY','NORMAL') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NORMAL',
  PRIMARY KEY (`mapId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mapids
-- ----------------------------
DROP TABLE IF EXISTS `mapids`;
CREATE TABLE `mapids`  (
  `mapid` int NOT NULL DEFAULT 0,
  `locationname` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `startX` int UNSIGNED NOT NULL DEFAULT 0,
  `endX` int UNSIGNED NOT NULL DEFAULT 0,
  `startY` int UNSIGNED NOT NULL DEFAULT 0,
  `endY` int UNSIGNED NOT NULL DEFAULT 0,
  `monster_amount` float UNSIGNED NOT NULL DEFAULT 0,
  `drop_rate` float UNSIGNED NOT NULL DEFAULT 0,
  `underwater` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `markable` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `teleportable` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `escapable` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `resurrection` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `painwand` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `penalty` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `take_pets` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `recall_pets` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `usable_item` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `usable_skill` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `dungeon` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `dmgModiPc2Npc` int NOT NULL DEFAULT 0,
  `dmgModiNpc2Pc` int NOT NULL DEFAULT 0,
  `decreaseHp` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `dominationTeleport` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `beginZone` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `redKnightZone` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `ruunCastleZone` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `interWarZone` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `geradBuffZone` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `growBuffZone` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `interKind` int NOT NULL DEFAULT 0,
  `script` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `cloneStart` int NOT NULL DEFAULT 0,
  `cloneEnd` int NOT NULL DEFAULT 0,
  `pngId` int NULL DEFAULT 0,
  PRIMARY KEY (`mapid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for marble
-- ----------------------------
DROP TABLE IF EXISTS `marble`;
CREATE TABLE `marble`  (
  `marble_id` int NOT NULL,
  `char_id` int NULL DEFAULT NULL,
  `char_name` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`marble_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mobgroup
-- ----------------------------
DROP TABLE IF EXISTS `mobgroup`;
CREATE TABLE `mobgroup`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `note` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `remove_group_if_leader_die` int UNSIGNED NOT NULL DEFAULT 0,
  `leader_id` int UNSIGNED NOT NULL DEFAULT 0,
  `minion1_id` int UNSIGNED NOT NULL DEFAULT 0,
  `minion1_count` int UNSIGNED NOT NULL DEFAULT 0,
  `minion2_id` int UNSIGNED NOT NULL DEFAULT 0,
  `minion2_count` int UNSIGNED NOT NULL DEFAULT 0,
  `minion3_id` int UNSIGNED NOT NULL DEFAULT 0,
  `minion3_count` int UNSIGNED NOT NULL DEFAULT 0,
  `minion4_id` int UNSIGNED NOT NULL DEFAULT 0,
  `minion4_count` int UNSIGNED NOT NULL DEFAULT 0,
  `minion5_id` int UNSIGNED NOT NULL DEFAULT 0,
  `minion5_count` int UNSIGNED NOT NULL DEFAULT 0,
  `minion6_id` int UNSIGNED NOT NULL DEFAULT 0,
  `minion6_count` int UNSIGNED NOT NULL DEFAULT 0,
  `minion7_id` int UNSIGNED NOT NULL DEFAULT 0,
  `minion7_count` int UNSIGNED NOT NULL DEFAULT 0,
  `minion8_id` int UNSIGNED NOT NULL DEFAULT 0,
  `minion8_count` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 134 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for mobskill
-- ----------------------------
DROP TABLE IF EXISTS `mobskill`;
CREATE TABLE `mobskill`  (
  `mobid` int UNSIGNED NOT NULL DEFAULT 0,
  `actNo` int UNSIGNED NOT NULL DEFAULT 0,
  `mobname` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `type` enum('NONE','ATTACK','SPELL','SUMMON','POLY','LINE_ATTACK','KIRTAS_METEOR','KIRTAS_BARRIER','TITANGOLEM_BARRIER','VALLACAS_FLY','VALLACAS_BRESS') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `prob` int UNSIGNED NOT NULL DEFAULT 0,
  `enableHp` int UNSIGNED NOT NULL DEFAULT 0,
  `enableCompanionHp` int UNSIGNED NOT NULL DEFAULT 0,
  `range` int NOT NULL DEFAULT 0,
  `limitCount` int NOT NULL DEFAULT 0,
  `ChangeTarget` enum('NO','COMPANION','ME','RANDOM') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NO',
  `AreaWidth` int UNSIGNED NOT NULL DEFAULT 0,
  `AreaHeight` int UNSIGNED NOT NULL DEFAULT 0,
  `Leverage` int UNSIGNED NOT NULL DEFAULT 0,
  `SkillId` int NOT NULL DEFAULT -1,
  `Gfxid` int UNSIGNED NOT NULL DEFAULT 0,
  `ActId` int UNSIGNED NOT NULL DEFAULT 0,
  `SummonId` int UNSIGNED NOT NULL DEFAULT 0,
  `SummonMin` int NOT NULL DEFAULT 0,
  `SummonMax` int NOT NULL DEFAULT 0,
  `PolyId` int UNSIGNED NOT NULL DEFAULT 0,
  `Msg` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`mobid`, `actNo`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for monster_book
-- ----------------------------
DROP TABLE IF EXISTS `monster_book`;
CREATE TABLE `monster_book`  (
  `monsternumber` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `monstername` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_kr` varchar(256) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `monster_id` int NULL DEFAULT NULL,
  `locx` int UNSIGNED NULL DEFAULT 0,
  `locy` int UNSIGNED NULL DEFAULT 0,
  `mapid` int UNSIGNED NULL DEFAULT 0,
  `type` int NULL DEFAULT NULL,
  `marterial` int NULL DEFAULT NULL,
  `book_step_first` int NULL DEFAULT NULL,
  `book_step_second` int NULL DEFAULT NULL,
  `book_step_third` int NULL DEFAULT NULL,
  `note` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`monsternumber`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 771 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for notice
-- ----------------------------
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice`  (
  `id` int NOT NULL,
  `message` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for notification
-- ----------------------------
DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification`  (
  `notification_id` int NOT NULL DEFAULT 0,
  `notification_type` enum('NORMAL(0)','CHANGE(1)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NORMAL(0)',
  `is_use` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  `is_hyperlink` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `displaydesc` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `displaydesc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `displaydesc_en` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `date_type` enum('NONE(0)','CUSTOM(1)','BOSS(2)','DOMINATION_TOWER(3)','COLOSSEUM(4)','TREASURE(5)','FORGOTTEN(6)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(0)',
  `date_boss_id` int NOT NULL DEFAULT 0,
  `date_custom_start` datetime NULL DEFAULT NULL,
  `date_custom_end` datetime NULL DEFAULT NULL,
  `teleport_loc` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `rest_gauge_bonus` int NOT NULL DEFAULT 0,
  `is_new` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `animation_type` enum('NO_ANIMATION(0)','ANT_QUEEN(1)','OMAN_MORPH(2)','AI_BATTLE(3)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NO_ANIMATION(0)',
  PRIMARY KEY (`notification_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for notification_event_npc
-- ----------------------------
DROP TABLE IF EXISTS `notification_event_npc`;
CREATE TABLE `notification_event_npc`  (
  `notification_id` int NOT NULL DEFAULT 0,
  `is_use` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  `order_id` int NOT NULL DEFAULT 0,
  `npc_id` int NOT NULL DEFAULT 0,
  `displaydesc` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `displaydesc_en` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `displaydesc_kr` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `rest_gauge_bonus` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`notification_id`, `order_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for npc
-- ----------------------------
DROP TABLE IF EXISTS `npc`;
CREATE TABLE `npc`  (
  `npcid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `classId` int NOT NULL DEFAULT 0,
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_powerbook` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_id` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `note` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `impl` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `spriteId` int UNSIGNED NOT NULL DEFAULT 0,
  `lvl` int UNSIGNED NOT NULL DEFAULT 0,
  `hp` int UNSIGNED NOT NULL DEFAULT 0,
  `mp` int UNSIGNED NOT NULL DEFAULT 0,
  `ac` int NOT NULL DEFAULT 0,
  `str` int NOT NULL DEFAULT 0,
  `con` int NOT NULL DEFAULT 0,
  `dex` int NOT NULL DEFAULT 0,
  `wis` int NOT NULL DEFAULT 0,
  `intel` int NOT NULL DEFAULT 0,
  `mr` int NOT NULL DEFAULT 0,
  `exp` int UNSIGNED NOT NULL DEFAULT 0,
  `alignment` int NOT NULL DEFAULT 0,
  `big` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `weakAttr` enum('NONE','EARTH','FIRE','WATER','WIND') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `ranged` int UNSIGNED NOT NULL DEFAULT 0,
  `is_taming` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `passispeed` int UNSIGNED NOT NULL DEFAULT 0,
  `atkspeed` int UNSIGNED NOT NULL DEFAULT 0,
  `atk_magic_speed` int UNSIGNED NOT NULL DEFAULT 0,
  `sub_magic_speed` int UNSIGNED NOT NULL DEFAULT 0,
  `undead` enum('NONE','UNDEAD','DEMON','UNDEAD_BOSS','DRANIUM') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `poison_atk` enum('NONE','DAMAGE','PARALYSIS','SILENCE') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `is_agro` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `is_agro_poly` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `is_agro_invis` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `family` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `agrofamily` int UNSIGNED NOT NULL DEFAULT 0,
  `agrogfxid1` int NOT NULL DEFAULT -1,
  `agrogfxid2` int NOT NULL DEFAULT -1,
  `is_picupitem` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `digestitem` int UNSIGNED NOT NULL DEFAULT 0,
  `is_bravespeed` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `hprinterval` int UNSIGNED NOT NULL DEFAULT 0,
  `hpr` int UNSIGNED NOT NULL DEFAULT 0,
  `mprinterval` int UNSIGNED NOT NULL DEFAULT 0,
  `mpr` int UNSIGNED NOT NULL DEFAULT 0,
  `is_teleport` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `randomlevel` int UNSIGNED NOT NULL DEFAULT 0,
  `randomhp` int UNSIGNED NOT NULL DEFAULT 0,
  `randommp` int UNSIGNED NOT NULL DEFAULT 0,
  `randomac` int NOT NULL DEFAULT 0,
  `randomexp` int UNSIGNED NOT NULL DEFAULT 0,
  `randomAlign` int NOT NULL DEFAULT 0,
  `damage_reduction` int UNSIGNED NOT NULL DEFAULT 0,
  `is_hard` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `is_bossmonster` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `can_turnundead` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `bowSpritetId` int UNSIGNED NOT NULL DEFAULT 0,
  `karma` int NOT NULL DEFAULT 0,
  `transform_id` int NOT NULL DEFAULT -1,
  `transform_gfxid` int NOT NULL DEFAULT 0,
  `light_size` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `is_amount_fixed` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `is_change_head` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `spawnlist_door` int NOT NULL DEFAULT 0,
  `count_map` int NOT NULL DEFAULT 0,
  `cant_resurrect` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `isHide` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`npcid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 100000030 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for npc2
-- ----------------------------
DROP TABLE IF EXISTS `npc2`;
CREATE TABLE `npc2`  (
  `npcid` int UNSIGNED NOT NULL,
  `classId` int NOT NULL DEFAULT 0,
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_powerbook` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_id` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `note` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `impl` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `spriteId` int UNSIGNED NOT NULL DEFAULT 0,
  `lvl` int UNSIGNED NOT NULL DEFAULT 0,
  `hp` int UNSIGNED NOT NULL DEFAULT 0,
  `mp` int UNSIGNED NOT NULL DEFAULT 0,
  `ac` int NOT NULL DEFAULT 0,
  `str` int NOT NULL DEFAULT 0,
  `con` int NOT NULL DEFAULT 0,
  `dex` int NOT NULL DEFAULT 0,
  `wis` int NOT NULL DEFAULT 0,
  `intel` int NOT NULL DEFAULT 0,
  `mr` int NOT NULL DEFAULT 0,
  `exp` int UNSIGNED NOT NULL DEFAULT 0,
  `alignment` int NOT NULL DEFAULT 0,
  `big` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `weakAttr` enum('NONE','EARTH','FIRE','WATER','WIND') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `ranged` int UNSIGNED NOT NULL DEFAULT 0,
  `is_taming` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `passispeed` int UNSIGNED NOT NULL DEFAULT 0,
  `atkspeed` int UNSIGNED NOT NULL DEFAULT 0,
  `atk_magic_speed` int UNSIGNED NOT NULL DEFAULT 0,
  `sub_magic_speed` int UNSIGNED NOT NULL DEFAULT 0,
  `undead` enum('NONE','UNDEAD','DEMON','UNDEAD_BOSS','DRANIUM') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `poison_atk` enum('NONE','DAMAGE','PARALYSIS','SILENCE') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `is_agro` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `is_agro_poly` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `is_agro_invis` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `family` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `agrofamily` int UNSIGNED NOT NULL DEFAULT 0,
  `agrogfxid1` int NOT NULL DEFAULT -1,
  `agrogfxid2` int NOT NULL DEFAULT -1,
  `is_picupitem` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `digestitem` int UNSIGNED NOT NULL DEFAULT 0,
  `is_bravespeed` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `hprinterval` int UNSIGNED NOT NULL DEFAULT 0,
  `hpr` int UNSIGNED NOT NULL DEFAULT 0,
  `mprinterval` int UNSIGNED NOT NULL DEFAULT 0,
  `mpr` int UNSIGNED NOT NULL DEFAULT 0,
  `is_teleport` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `randomlevel` int UNSIGNED NOT NULL DEFAULT 0,
  `randomhp` int UNSIGNED NOT NULL DEFAULT 0,
  `randommp` int UNSIGNED NOT NULL DEFAULT 0,
  `randomac` int NOT NULL DEFAULT 0,
  `randomexp` int UNSIGNED NOT NULL DEFAULT 0,
  `randomAlign` int NOT NULL DEFAULT 0,
  `damage_reduction` int UNSIGNED NOT NULL DEFAULT 0,
  `is_hard` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `is_bossmonster` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `can_turnundead` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `bowSpritetId` int UNSIGNED NOT NULL DEFAULT 0,
  `karma` int NOT NULL DEFAULT 0,
  `transform_id` int NOT NULL DEFAULT -1,
  `transform_gfxid` int NOT NULL DEFAULT 0,
  `light_size` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `is_amount_fixed` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `is_change_head` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `spawnlist_door` int NOT NULL DEFAULT 0,
  `count_map` int NOT NULL DEFAULT 0,
  `cant_resurrect` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `isHide` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false'
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for npc_info
-- ----------------------------
DROP TABLE IF EXISTS `npc_info`;
CREATE TABLE `npc_info`  (
  `npcId` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `recall` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `spawnActionId` int NOT NULL DEFAULT 0,
  `reward` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `rewardRange` enum('screen','map','self') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'screen',
  `rewardItemId` int NOT NULL DEFAULT 0,
  `rewardItemCount` int NOT NULL DEFAULT 0,
  `rewardEinhasad` int NOT NULL DEFAULT 0,
  `rewardNcoin` int NOT NULL DEFAULT 0,
  `rewardGfx` int NOT NULL DEFAULT 0,
  `msgRange` enum('screen','map','self') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'screen',
  `spawnMsg` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `dieMsg` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `dieMsgPcList` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `autoLoot` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `transformChance` int NOT NULL DEFAULT 0,
  `transformId` int NOT NULL DEFAULT 0,
  `transformGfxId` int NOT NULL DEFAULT 0,
  `scriptType` enum('text','number','none') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'none',
  `scriptContent` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  PRIMARY KEY (`npcId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for npc_night
-- ----------------------------
DROP TABLE IF EXISTS `npc_night`;
CREATE TABLE `npc_night`  (
  `npcId` int NOT NULL DEFAULT 0,
  `name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `targetMapId` int NOT NULL DEFAULT 0,
  `targetId` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`npcId`, `targetMapId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for npcaction
-- ----------------------------
DROP TABLE IF EXISTS `npcaction`;
CREATE TABLE `npcaction`  (
  `npcid` int UNSIGNED NOT NULL DEFAULT 0,
  `normal_action` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `caotic_action` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `teleport_url` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `teleport_urla` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`npcid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for npcaction_teleport
-- ----------------------------
DROP TABLE IF EXISTS `npcaction_teleport`;
CREATE TABLE `npcaction_teleport`  (
  `npcId` int NOT NULL DEFAULT 0,
  `note` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `actionName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `needLevel` int NOT NULL DEFAULT 0,
  `limitLevel` int NOT NULL DEFAULT 0,
  `needTimerId` int NOT NULL DEFAULT 0,
  `needItem` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `needBuff` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `needPcroomBuff` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `telX` int NOT NULL DEFAULT 0,
  `telY` int NOT NULL DEFAULT 0,
  `telMapId` int NOT NULL DEFAULT 0,
  `telRange` int NOT NULL DEFAULT 0,
  `telType` enum('random','inter','normal') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'normal',
  `randomMap` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `telTownId` int NOT NULL DEFAULT 0,
  `failAlignment` enum('caotic','neutral','lawful','none') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'none',
  `successActionName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `failLevelActionName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `failItemActionName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `failBuffActionName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`npcId`, `actionName`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for npcchat
-- ----------------------------
DROP TABLE IF EXISTS `npcchat`;
CREATE TABLE `npcchat`  (
  `npc_id` int UNSIGNED NOT NULL DEFAULT 0,
  `chat_timing` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `note` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `start_delay_time` int NOT NULL DEFAULT 0,
  `chat_id1` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `chat_id2` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `chat_id3` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `chat_id4` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `chat_id5` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `chat_interval` int UNSIGNED NOT NULL DEFAULT 0,
  `is_shout` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `is_world_chat` tinyint(1) NOT NULL DEFAULT 0,
  `is_repeat` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `repeat_interval` int UNSIGNED NOT NULL DEFAULT 0,
  `game_time` int NOT NULL DEFAULT 0,
  `percent` int UNSIGNED NULL DEFAULT 0,
  PRIMARY KEY (`npc_id`, `chat_timing`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for penalty_pass_item
-- ----------------------------
DROP TABLE IF EXISTS `penalty_pass_item`;
CREATE TABLE `penalty_pass_item`  (
  `itemId` int NOT NULL DEFAULT 0,
  `desc` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for penalty_protect_item
-- ----------------------------
DROP TABLE IF EXISTS `penalty_protect_item`;
CREATE TABLE `penalty_protect_item`  (
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `type` enum('have','equip') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'have',
  `itemPanalty` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'false',
  `expPanalty` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'false',
  `dropItemId` int NULL DEFAULT 0,
  `msgId` int NULL DEFAULT NULL,
  `mapIds` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `remove` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'false',
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for playsupport
-- ----------------------------
DROP TABLE IF EXISTS `playsupport`;
CREATE TABLE `playsupport`  (
  `mapid` int NOT NULL,
  `mapname` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `whole` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `surround` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `sub` tinyint UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`mapid`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for polyitems
-- ----------------------------
DROP TABLE IF EXISTS `polyitems`;
CREATE TABLE `polyitems`  (
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `polyId` int NOT NULL DEFAULT 0,
  `duration` int NOT NULL DEFAULT 1800,
  `type` enum('domination','normal') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'normal',
  `delete` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for polymorphs
-- ----------------------------
DROP TABLE IF EXISTS `polymorphs`;
CREATE TABLE `polymorphs`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `polyid` int NOT NULL,
  `minlevel` int NOT NULL,
  `weaponequip` int NULL DEFAULT NULL,
  `armorequip` int NULL DEFAULT NULL,
  `isSkillUse` int NOT NULL,
  `cause` int NULL DEFAULT NULL,
  `bonusPVP` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `formLongEnable` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 133924 CHARACTER SET = euckr COLLATE = euckr_korean_ci COMMENT = 'MyISAM free: 10240 kB' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for polyweapon
-- ----------------------------
DROP TABLE IF EXISTS `polyweapon`;
CREATE TABLE `polyweapon`  (
  `polyId` int NOT NULL DEFAULT 0,
  `weapon` enum('bow','spear','both') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'both',
  PRIMARY KEY (`polyId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for probability_by_spell
-- ----------------------------
DROP TABLE IF EXISTS `probability_by_spell`;
CREATE TABLE `probability_by_spell`  (
  `skill_id` int NOT NULL,
  `description` varchar(64) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(64) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `skill_type` enum('MAGICHIT','ABILITY','SPIRIT','DRAGONSPELL','FEAR') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'MAGICHIT',
  `pierce_lv_weight` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '0.0',
  `resis_lv_weight` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '0.0',
  `int_weight` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '0.0',
  `mr_weight` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '0.0',
  `pierce_weight` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '0.0',
  `resis_weight` varchar(16) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '0.0',
  `default_probability` int NULL DEFAULT 5,
  `min_probability` int NULL DEFAULT 5,
  `max_probability` int NULL DEFAULT 80,
  `is_loggin` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'false',
  PRIMARY KEY (`skill_id`, `skill_type`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for proto_packet
-- ----------------------------
DROP TABLE IF EXISTS `proto_packet`;
CREATE TABLE `proto_packet`  (
  `code` varchar(6) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `code_val` int NOT NULL DEFAULT 0,
  `className` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`code`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for race_div_record
-- ----------------------------
DROP TABLE IF EXISTS `race_div_record`;
CREATE TABLE `race_div_record`  (
  `id` int NOT NULL DEFAULT 0,
  `bug_number` int NOT NULL DEFAULT 0,
  `dividend` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`, `bug_number`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for race_record
-- ----------------------------
DROP TABLE IF EXISTS `race_record`;
CREATE TABLE `race_record`  (
  `number` int UNSIGNED NOT NULL DEFAULT 0,
  `win` int UNSIGNED NOT NULL DEFAULT 0,
  `lose` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`number`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for race_tickets
-- ----------------------------
DROP TABLE IF EXISTS `race_tickets`;
CREATE TABLE `race_tickets`  (
  `item_id` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `price` int NOT NULL,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for repair_item_cost
-- ----------------------------
DROP TABLE IF EXISTS `repair_item_cost`;
CREATE TABLE `repair_item_cost`  (
  `itemId` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `cost` int NULL DEFAULT NULL,
  PRIMARY KEY (`itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for report
-- ----------------------------
DROP TABLE IF EXISTS `report`;
CREATE TABLE `report`  (
  `target` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `reporter` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `count` int NOT NULL DEFAULT 1,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`target`, `reporter`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for resolvent
-- ----------------------------
DROP TABLE IF EXISTS `resolvent`;
CREATE TABLE `resolvent`  (
  `item_id` int NOT NULL DEFAULT 0,
  `note` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `crystal_count` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for robot_fishing
-- ----------------------------
DROP TABLE IF EXISTS `robot_fishing`;
CREATE TABLE `robot_fishing`  (
  `x` int NULL DEFAULT NULL,
  `y` int NULL DEFAULT NULL,
  `mapid` int NULL DEFAULT NULL,
  `heading` int NULL DEFAULT NULL,
  `fishingX` int NULL DEFAULT NULL,
  `fishingY` int NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for robot_location
-- ----------------------------
DROP TABLE IF EXISTS `robot_location`;
CREATE TABLE `robot_location`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `istown` enum('true','false') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'false',
  `x` int NOT NULL,
  `y` int NOT NULL,
  `map` int NOT NULL,
  `etc` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `count` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 41 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for robot_message
-- ----------------------------
DROP TABLE IF EXISTS `robot_message`;
CREATE TABLE `robot_message`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('pvp','die') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ment` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 156 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for robot_name
-- ----------------------------
DROP TABLE IF EXISTS `robot_name`;
CREATE TABLE `robot_name`  (
  `uid` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1008 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for robot_teleport_list
-- ----------------------------
DROP TABLE IF EXISTS `robot_teleport_list`;
CREATE TABLE `robot_teleport_list`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `x` int NULL DEFAULT NULL,
  `y` int NULL DEFAULT NULL,
  `mapid` int NULL DEFAULT NULL,
  `heading` int NULL DEFAULT NULL,
  `note` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `isuse` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for server_explain
-- ----------------------------
DROP TABLE IF EXISTS `server_explain`;
CREATE TABLE `server_explain`  (
  `num` int NOT NULL AUTO_INCREMENT,
  `subject` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  `content` varchar(1000) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`num`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for serverinfo
-- ----------------------------
DROP TABLE IF EXISTS `serverinfo`;
CREATE TABLE `serverinfo`  (
  `id` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `adenmake` bigint NULL DEFAULT 0,
  `adenconsume` bigint NULL DEFAULT 0,
  `adentax` int NULL DEFAULT 0,
  `bugdividend` float(10, 0) NULL DEFAULT 0,
  `accountcount` int NULL DEFAULT 0,
  `charcount` int NULL DEFAULT 0,
  `pvpcount` int NULL DEFAULT 0,
  `penaltycount` int NULL DEFAULT 0,
  `clanmaker` int NULL DEFAULT 0,
  `maxuser` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for shop
-- ----------------------------
DROP TABLE IF EXISTS `shop`;
CREATE TABLE `shop`  (
  `npc_id` int UNSIGNED NOT NULL DEFAULT 0,
  `item_id` int UNSIGNED NOT NULL DEFAULT 0,
  `order_id` int UNSIGNED NOT NULL DEFAULT 0,
  `selling_price` int NOT NULL DEFAULT -1,
  `pack_count` int UNSIGNED NOT NULL DEFAULT 0,
  `purchasing_price` int NOT NULL DEFAULT -1,
  `enchant` int NOT NULL DEFAULT 0,
  `pledge_rank` enum('NONE(None)','RANK_NORMAL_KING(Monarch)','RANK_NORMAL_PRINCE(Vice-Monarch)','RANK_NORMAL_KNIGHT(Guardian)','RANK_NORMAL_ELITE_KNIGHT(Elite)','RANK_NORMAL_JUNIOR_KNIGHT(Common)','RANK_NORMAL_REGULAR(Training)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(None)',
  `note` varbinary(50) NULL DEFAULT NULL,
  PRIMARY KEY (`npc_id`, `item_id`, `order_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for shop_aden
-- ----------------------------
DROP TABLE IF EXISTS `shop_aden`;
CREATE TABLE `shop_aden`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `itemid` int NULL DEFAULT NULL,
  `itemname` varchar(22) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `price` int NULL DEFAULT NULL,
  `type` int NULL DEFAULT 0,
  `status` int NULL DEFAULT 0,
  `html` varchar(22) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  `pack` int NULL DEFAULT 0,
  `enchant` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for shop_info
-- ----------------------------
DROP TABLE IF EXISTS `shop_info`;
CREATE TABLE `shop_info`  (
  `npcId` int NOT NULL DEFAULT 0,
  `name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `type` enum('clan','ein','ncoin','tam','berry','item') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'item',
  `currencyId` int NOT NULL DEFAULT 0,
  `currencyDescId` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`npcId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for shop_limit
-- ----------------------------
DROP TABLE IF EXISTS `shop_limit`;
CREATE TABLE `shop_limit`  (
  `shopId` int NOT NULL DEFAULT 0,
  `itemId` int NOT NULL DEFAULT 0,
  `itemName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `limitTerm` enum('WEEK','DAY','NONE') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'DAY',
  `limitCount` int NOT NULL DEFAULT 0,
  `limitType` enum('ACCOUNT','CHARACTER') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'ACCOUNT',
  PRIMARY KEY (`shopId`, `itemId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for shop_npc
-- ----------------------------
DROP TABLE IF EXISTS `shop_npc`;
CREATE TABLE `shop_npc`  (
  `npc_id` int NOT NULL,
  `id` int NOT NULL DEFAULT 1,
  `item_id` int NOT NULL DEFAULT 0,
  `memo` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `count` int NOT NULL DEFAULT 1,
  `enchant` int NOT NULL DEFAULT 0,
  `selling_price` int NOT NULL DEFAULT -1,
  `purchasing_price` int NOT NULL DEFAULT -1,
  PRIMARY KEY (`npc_id`, `id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for skills
-- ----------------------------
DROP TABLE IF EXISTS `skills`;
CREATE TABLE `skills`  (
  `skill_id` int NOT NULL DEFAULT -1,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `skill_level` int NOT NULL DEFAULT 0,
  `mpConsume` int UNSIGNED NOT NULL DEFAULT 0,
  `hpConsume` int UNSIGNED NOT NULL DEFAULT 0,
  `itemConsumeId` int UNSIGNED NOT NULL DEFAULT 0,
  `itemConsumeCount` int UNSIGNED NOT NULL DEFAULT 0,
  `reuseDelay` int UNSIGNED NOT NULL DEFAULT 0,
  `delayGroupId` int NOT NULL DEFAULT 0,
  `fixDelay` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `buffDuration` int UNSIGNED NOT NULL DEFAULT 0,
  `buffDuration_txt` varchar(30) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `target` enum('NONE','ATTACK','BUFF') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `target_to` enum('ME','PC','NPC','ALL','PLEDGE','PARTY','COMPANIION','PLACE') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'ALL',
  `target_to_txt` varchar(75) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `effect_txt` varchar(260) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `damage_value` int UNSIGNED NOT NULL DEFAULT 0,
  `damage_dice` int UNSIGNED NOT NULL DEFAULT 0,
  `damage_dice_count` int UNSIGNED NOT NULL DEFAULT 0,
  `probability_value` int UNSIGNED NOT NULL DEFAULT 0,
  `probability_dice` int UNSIGNED NOT NULL DEFAULT 0,
  `attr` enum('NONE','EARTH','FIRE','WATER','WIND','RAY') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `type` enum('NONE','PROB','CHANGE','CURSE','DEATH','HEAL','RESTORE','ATTACK','OTHER') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `alignment` int NOT NULL DEFAULT 0,
  `ranged` int NOT NULL DEFAULT 0,
  `area` int NOT NULL DEFAULT 0,
  `is_through` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `action_id` int UNSIGNED NOT NULL DEFAULT 0,
  `action_id2` int UNSIGNED NOT NULL DEFAULT 0,
  `action_id3` int UNSIGNED NOT NULL DEFAULT 0,
  `castgfx` int UNSIGNED NOT NULL DEFAULT 0,
  `castgfx2` int UNSIGNED NOT NULL DEFAULT 0,
  `castgfx3` int UNSIGNED NOT NULL DEFAULT 0,
  `sysmsgID_happen` int UNSIGNED NOT NULL DEFAULT 0,
  `sysmsgID_stop` int UNSIGNED NOT NULL DEFAULT 0,
  `sysmsgID_fail` int UNSIGNED NOT NULL DEFAULT 0,
  `classType` enum('lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown','normal','none') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'none',
  `grade` enum('ONLY','MYTH','LEGEND','RARE','NORMAL') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NORMAL',
  PRIMARY KEY (`skill_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for skills_handler
-- ----------------------------
DROP TABLE IF EXISTS `skills_handler`;
CREATE TABLE `skills_handler`  (
  `skillId` int NOT NULL DEFAULT -1,
  `name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `className` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`skillId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for skills_info
-- ----------------------------
DROP TABLE IF EXISTS `skills_info`;
CREATE TABLE `skills_info`  (
  `skillId` int NOT NULL DEFAULT -1,
  `skillname` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `useSkillId` int NOT NULL DEFAULT 0,
  `durationShowType` enum('NONE(0)','PERCENT(1)','MINUTE(2)','PERCENT_ORC_SERVER(3)','EINHASAD_COOLTIME_MINUTE(4)','LEGACY_TIME(5)','VARIABLE_VALUE(6)','DAY_HOUR_MIN(7)','AUTO_DAY_HOUR_MIN_SEC(8)','NSERVICE_TOPPING(9)','UNLIMIT(10)','CUSTOM(11)','COUNT(12)','RATE(13)','EINHASAD_FAVOR(14)','HIDDEN(15)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'AUTO_DAY_HOUR_MIN_SEC(8)',
  `icon` int NOT NULL DEFAULT 0,
  `onIconId` int NOT NULL DEFAULT 0,
  `offIconId` int NOT NULL DEFAULT 0,
  `simplePck` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `iconPriority` int NOT NULL DEFAULT 3,
  `tooltipStrId` int NOT NULL DEFAULT 0,
  `newStrId` int NOT NULL DEFAULT 0,
  `endStrId` int NOT NULL DEFAULT 0,
  `isGood` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  `overlapBuffIcon` int NOT NULL DEFAULT 0,
  `mainTooltipStrId` int NOT NULL DEFAULT 0,
  `buffIconPriority` int NOT NULL DEFAULT 0,
  `buffGroupId` int NOT NULL DEFAULT 0,
  `buffGroupPriority` int NOT NULL DEFAULT 0,
  `expireDuration` int NOT NULL DEFAULT 0,
  `boostType` enum('BOOST_NONE(0)','HP_UI_CHANGE(1)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'BOOST_NONE(0)',
  `isPassiveSpell` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`skillId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for skills_passive
-- ----------------------------
DROP TABLE IF EXISTS `skills_passive`;
CREATE TABLE `skills_passive`  (
  `passive_id` int NOT NULL DEFAULT -1,
  `name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `duration` int NOT NULL DEFAULT 0,
  `on_icon_id` int NOT NULL DEFAULT 0,
  `tooltip_str_id` int NOT NULL DEFAULT 0,
  `is_good` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'true',
  `class_type` enum('lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown','normal','none') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'none',
  `back_active_skill_id` int NOT NULL DEFAULT -1,
  `back_passive_id` int NOT NULL DEFAULT -1,
  `grade` enum('ONLY','MYTH','LEGEND','RARE','NORMAL') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NORMAL',
  PRIMARY KEY (`passive_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist`;
CREATE TABLE `spawnlist`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `count` int UNSIGNED NOT NULL DEFAULT 0,
  `npc_templateid` int UNSIGNED NOT NULL DEFAULT 0,
  `group_id` int UNSIGNED NOT NULL DEFAULT 0,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `randomx` int UNSIGNED NOT NULL DEFAULT 0,
  `randomy` int UNSIGNED NOT NULL DEFAULT 0,
  `locx1` int UNSIGNED NOT NULL DEFAULT 0,
  `locy1` int UNSIGNED NOT NULL DEFAULT 0,
  `locx2` int UNSIGNED NOT NULL DEFAULT 0,
  `locy2` int UNSIGNED NOT NULL DEFAULT 0,
  `heading` int UNSIGNED NOT NULL DEFAULT 0,
  `min_respawn_delay` int UNSIGNED NOT NULL DEFAULT 0,
  `max_respawn_delay` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  `respawn_screen` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `movement_distance` int UNSIGNED NOT NULL DEFAULT 0,
  `rest` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `near_spawn` tinyint UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2120015528 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_arrow
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_arrow`;
CREATE TABLE `spawnlist_arrow`  (
  `npc_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int NOT NULL DEFAULT 0,
  `tarx` int UNSIGNED NOT NULL DEFAULT 0,
  `tary` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  `start_delay` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`npc_id`, `locx`, `locy`, `mapid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 100088 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_boss
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_boss`;
CREATE TABLE `spawnlist_boss`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `spawn_group_id` int NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  `npcid` int NOT NULL DEFAULT 0,
  `spawnDay` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `spawnTime` text CHARACTER SET euckr COLLATE euckr_korean_ci NULL,
  `spawnX` int NOT NULL DEFAULT 0,
  `spawnY` int NOT NULL DEFAULT 0,
  `spawnMapId` int NOT NULL DEFAULT 0,
  `rndMinut` int NOT NULL DEFAULT 0,
  `rndRange` int NOT NULL DEFAULT 0,
  `heading` int UNSIGNED NOT NULL DEFAULT 0,
  `groupid` int NOT NULL DEFAULT 0,
  `movementDistance` int NOT NULL DEFAULT 0,
  `isYN` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `mentType` enum('NONE','WORLD','MAP','SCREEN') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ment` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '',
  `percent` int UNSIGNED NOT NULL DEFAULT 0,
  `aliveSecond` int NOT NULL DEFAULT 0,
  `spawnType` enum('NORMAL','DOMINATION_TOWER','DRAGON_RAID','POISON_FEILD') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NORMAL',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 201 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_boss_sign
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_boss_sign`;
CREATE TABLE `spawnlist_boss_sign`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `bossId` int NOT NULL DEFAULT 0,
  `npcId` int NOT NULL DEFAULT 0,
  `locX` int NOT NULL DEFAULT 0,
  `locY` int NOT NULL DEFAULT 0,
  `locMapId` int NOT NULL DEFAULT 0,
  `rndRange` int NOT NULL DEFAULT 0,
  `aliveSecond` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_clandungeon
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_clandungeon`;
CREATE TABLE `spawnlist_clandungeon`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` int UNSIGNED NOT NULL DEFAULT 0,
  `stage` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `npc_templateid` int UNSIGNED NOT NULL DEFAULT 0,
  `count` int UNSIGNED NOT NULL DEFAULT 0,
  `boss` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 130 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_door
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_door`;
CREATE TABLE `spawnlist_door`  (
  `id` int NOT NULL DEFAULT 0,
  `name` varchar(60) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `gfxid` int NOT NULL DEFAULT 0,
  `locx` int NOT NULL DEFAULT 0,
  `locy` int NOT NULL DEFAULT 0,
  `mapid` int NOT NULL DEFAULT 0,
  `direction` int NOT NULL DEFAULT 0,
  `left_edge_location` int NOT NULL DEFAULT 0,
  `right_edge_location` int NOT NULL DEFAULT 0,
  `hp` int NOT NULL DEFAULT 0,
  `keeper` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_furniture
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_furniture`;
CREATE TABLE `spawnlist_furniture`  (
  `item_obj_id` int UNSIGNED NOT NULL DEFAULT 0,
  `npcid` int UNSIGNED NOT NULL DEFAULT 0,
  `locx` int NOT NULL DEFAULT 0,
  `locy` int NOT NULL DEFAULT 0,
  `mapid` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`item_obj_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_indun
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_indun`;
CREATE TABLE `spawnlist_indun`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `npc_id` int UNSIGNED NOT NULL DEFAULT 0,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `heading` int NOT NULL DEFAULT 0,
  `randomRange` int NOT NULL DEFAULT 5,
  `mapId` int NOT NULL,
  `location` varchar(150) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 234 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_light
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_light`;
CREATE TABLE `spawnlist_light`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `npcid` int UNSIGNED NOT NULL DEFAULT 0,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 169 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_npc
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_npc`;
CREATE TABLE `spawnlist_npc`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `count` int UNSIGNED NOT NULL DEFAULT 0,
  `npc_templateid` int UNSIGNED NOT NULL DEFAULT 0,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `randomx` int UNSIGNED NOT NULL DEFAULT 0,
  `randomy` int UNSIGNED NOT NULL DEFAULT 0,
  `heading` int UNSIGNED NOT NULL DEFAULT 0,
  `respawn_delay` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  `movement_distance` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2101003933 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_npc_cash_shop
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_npc_cash_shop`;
CREATE TABLE `spawnlist_npc_cash_shop`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `npc_id` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(40) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  `heading` int NOT NULL DEFAULT 0,
  `title` varchar(35) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `shop_chat` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 37 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_npc_shop
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_npc_shop`;
CREATE TABLE `spawnlist_npc_shop`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `npc_id` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(40) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  `heading` int NOT NULL DEFAULT 0,
  `title` varchar(35) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `shop_chat` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 61 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_other
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_other`;
CREATE TABLE `spawnlist_other`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'with type 999: manually created not linked with this table.',
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `npc_id` int UNSIGNED NOT NULL DEFAULT 0,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `heading` int NOT NULL DEFAULT 0,
  `randomRange` int NOT NULL DEFAULT 0,
  `timeMillisToDelete` int NOT NULL DEFAULT 0,
  `gfxId` int NOT NULL DEFAULT 0,
  `actionStatus` int NOT NULL DEFAULT 4,
  `leftEdge` int NOT NULL DEFAULT 0,
  `rightEdge` int NOT NULL DEFAULT 0,
  `direction` int NOT NULL DEFAULT 0,
  `targetPlayer` tinyint(1) NOT NULL DEFAULT 0,
  `paralysisTime` int NOT NULL DEFAULT 0,
  `count` int NOT NULL DEFAULT 1,
  `mapId` int NOT NULL,
  `location` varchar(150) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 76 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_ruun
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_ruun`;
CREATE TABLE `spawnlist_ruun`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `stage` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `npcId` int UNSIGNED NOT NULL DEFAULT 0,
  `locX` int UNSIGNED NOT NULL DEFAULT 0,
  `locY` int UNSIGNED NOT NULL DEFAULT 0,
  `mapId` int UNSIGNED NOT NULL DEFAULT 0,
  `range` int UNSIGNED NOT NULL DEFAULT 0,
  `count` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_trap
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_trap`;
CREATE TABLE `spawnlist_trap`  (
  `id` int NOT NULL,
  `name` varchar(64) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `trapId` int NOT NULL,
  `mapId` int NOT NULL,
  `locX` int NOT NULL,
  `locY` int NOT NULL,
  `locRndX` int NOT NULL DEFAULT 0,
  `locRndY` int NOT NULL DEFAULT 0,
  `count` int NOT NULL DEFAULT 1,
  `span` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_ub
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_ub`;
CREATE TABLE `spawnlist_ub`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ub_id` int UNSIGNED NOT NULL DEFAULT 0,
  `pattern` int UNSIGNED NOT NULL DEFAULT 0,
  `group_id` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `npc_templateid` int UNSIGNED NOT NULL DEFAULT 0,
  `count` int UNSIGNED NOT NULL DEFAULT 0,
  `spawn_delay` int UNSIGNED NOT NULL DEFAULT 0,
  `seal_count` int UNSIGNED NOT NULL DEFAULT 0,
  `isBoss` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `isGateKeeper` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 142 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_unicorntemple
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_unicorntemple`;
CREATE TABLE `spawnlist_unicorntemple`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `npc_id` int UNSIGNED NOT NULL DEFAULT 0,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `heading` int NOT NULL DEFAULT 0,
  `count` int NOT NULL DEFAULT 1,
  `mapId` int NOT NULL,
  `locationname` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 106 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spawnlist_worldwar
-- ----------------------------
DROP TABLE IF EXISTS `spawnlist_worldwar`;
CREATE TABLE `spawnlist_worldwar`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `npc_id` int UNSIGNED NOT NULL DEFAULT 0,
  `locx` int UNSIGNED NOT NULL DEFAULT 0,
  `locy` int UNSIGNED NOT NULL DEFAULT 0,
  `mapid` int UNSIGNED NOT NULL DEFAULT 0,
  `heading` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5003 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spell_melt
-- ----------------------------
DROP TABLE IF EXISTS `spell_melt`;
CREATE TABLE `spell_melt`  (
  `skillId` int NOT NULL DEFAULT -1,
  `skillName` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `passiveId` int NOT NULL DEFAULT 0,
  `classType` enum('lancer','fencer','warrior','illusionist','dragonknight','darkelf','wizard','elf','knight','crown') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'crown',
  `skillItemId` int NOT NULL DEFAULT 0,
  `meltItemId` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`skillId`, `passiveId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spr_action
-- ----------------------------
DROP TABLE IF EXISTS `spr_action`;
CREATE TABLE `spr_action`  (
  `spr_id` int NOT NULL,
  `act_id` int NOT NULL,
  `act_name` varchar(128) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `framecount` int NULL DEFAULT NULL,
  `framerate` int NULL DEFAULT NULL,
  `numOfFrame` int NULL DEFAULT NULL,
  PRIMARY KEY (`spr_id`, `act_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for spr_info
-- ----------------------------
DROP TABLE IF EXISTS `spr_info`;
CREATE TABLE `spr_info`  (
  `spr_id` int NOT NULL,
  `spr_name` varchar(200) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `description` varchar(200) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `shadow` int NOT NULL DEFAULT 0,
  `type` int NOT NULL DEFAULT 0,
  `attr` int NOT NULL DEFAULT 0,
  `width` int NOT NULL DEFAULT 0,
  `height` int NOT NULL DEFAULT 0,
  `flying_type` int NOT NULL DEFAULT 0,
  `action_count` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`spr_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_bookquest_compensate
-- ----------------------------
DROP TABLE IF EXISTS `tb_bookquest_compensate`;
CREATE TABLE `tb_bookquest_compensate`  (
  `id` int NOT NULL,
  `difficulty` int NULL DEFAULT 1,
  `type` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `num1` int NULL DEFAULT NULL,
  `num2` int NULL DEFAULT NULL,
  `id1` int NULL DEFAULT NULL,
  `id2` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_lfccompensate
-- ----------------------------
DROP TABLE IF EXISTS `tb_lfccompensate`;
CREATE TABLE `tb_lfccompensate`  (
  `ID` int NOT NULL AUTO_INCREMENT,
  `LFCID` int NULL DEFAULT 0,
  `PARTITION` int NULL DEFAULT 0,
  `TYPE` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `IDENTITY` int NULL DEFAULT 0,
  `QUANTITY` int NULL DEFAULT 0,
  `LEVEL` int NULL DEFAULT 0,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_lfctypes
-- ----------------------------
DROP TABLE IF EXISTS `tb_lfctypes`;
CREATE TABLE `tb_lfctypes`  (
  `ID` int NOT NULL AUTO_INCREMENT,
  `TYPE` int NULL DEFAULT 0,
  `NAME` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `DESC_KR` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `USE` int NULL DEFAULT 0,
  `BUFF_SPAWN_TIME` int NULL DEFAULT 0,
  `POSSIBLE_LEVEL` int NULL DEFAULT 0,
  `MIN_PARTY` int NULL DEFAULT 0,
  `MAX_PARTY` int NULL DEFAULT 0,
  `NEED_ITEMID` int NULL DEFAULT 0,
  `NEED_ITEMCOUNT` int NULL DEFAULT 0,
  `PLAY_INST` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `MAPRT_LEFT` int NULL DEFAULT 0,
  `MAPRT_TOP` int NULL DEFAULT 0,
  `MAPRT_RIGHT` int NULL DEFAULT 0,
  `MAPRT_BOTTOM` int NULL DEFAULT 0,
  `MAPID` int NULL DEFAULT 0,
  `STARTPOS_REDX` int NULL DEFAULT 0,
  `STARTPOS_REDY` int NULL DEFAULT 0,
  `STARTPOS_BLUEX` int NULL DEFAULT 0,
  `STARTPOS_BLUEY` int NULL DEFAULT 0,
  `PLAYTIME` int NULL DEFAULT 0,
  `READYTIME` int NULL DEFAULT 0,
  `RAND_WINNER_RATIO` int NULL DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_monster_book
-- ----------------------------
DROP TABLE IF EXISTS `tb_monster_book`;
CREATE TABLE `tb_monster_book`  (
  `npc_id` int NULL DEFAULT NULL,
  `npc_name` varchar(50) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `book_id` int NOT NULL DEFAULT 0,
  `book_step_first` int NULL DEFAULT NULL,
  `book_step_second` int NULL DEFAULT NULL,
  `book_step_third` int NULL DEFAULT NULL,
  `book_clear_num` int NULL DEFAULT NULL,
  `week_difficulty` int NULL DEFAULT NULL,
  `week_success_count` int NULL DEFAULT NULL,
  `tel_x` int NULL DEFAULT NULL,
  `tel_y` int NULL DEFAULT NULL,
  `tel_mapId` int NULL DEFAULT NULL,
  PRIMARY KEY (`book_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_monster_book_clearinfo
-- ----------------------------
DROP TABLE IF EXISTS `tb_monster_book_clearinfo`;
CREATE TABLE `tb_monster_book_clearinfo`  (
  `book_id` int NOT NULL,
  `book_clear_num2` int NOT NULL,
  PRIMARY KEY (`book_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_user_monster_book
-- ----------------------------
DROP TABLE IF EXISTS `tb_user_monster_book`;
CREATE TABLE `tb_user_monster_book`  (
  `char_id` int NOT NULL,
  `book_id` int NOT NULL,
  `difficulty` int NULL DEFAULT 1,
  `step` int NULL DEFAULT 0,
  `completed` int NULL DEFAULT 0,
  PRIMARY KEY (`char_id`, `book_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_user_week_quest
-- ----------------------------
DROP TABLE IF EXISTS `tb_user_week_quest`;
CREATE TABLE `tb_user_week_quest`  (
  `char_id` int NOT NULL,
  `bookId` int NULL DEFAULT NULL,
  `difficulty` int NOT NULL,
  `section` int NOT NULL DEFAULT 0,
  `step` int NULL DEFAULT 0,
  `stamp` datetime NULL DEFAULT NULL,
  `completed` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`char_id`, `difficulty`, `section`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_weekquest_compensate
-- ----------------------------
DROP TABLE IF EXISTS `tb_weekquest_compensate`;
CREATE TABLE `tb_weekquest_compensate`  (
  `button_no` int NOT NULL,
  `ingredient_itemId` int NULL DEFAULT 0,
  `compen_exp` int NULL DEFAULT 0,
  `compen_exp_level` int NULL DEFAULT 0,
  `compen_itemId` int NULL DEFAULT 0,
  `compen_itemCount` int NULL DEFAULT 0,
  `compen_itemLevel` int NULL DEFAULT NULL,
  PRIMARY KEY (`button_no`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_weekquest_matrix
-- ----------------------------
DROP TABLE IF EXISTS `tb_weekquest_matrix`;
CREATE TABLE `tb_weekquest_matrix`  (
  `difficulty` int NOT NULL,
  `col1` int NULL DEFAULT NULL,
  `col2` int NULL DEFAULT NULL,
  `col3` int NULL DEFAULT NULL,
  `stamp` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`difficulty`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tb_weekquest_updateinfo
-- ----------------------------
DROP TABLE IF EXISTS `tb_weekquest_updateinfo`;
CREATE TABLE `tb_weekquest_updateinfo`  (
  `id` int NOT NULL,
  `lastTime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tj_coupon
-- ----------------------------
DROP TABLE IF EXISTS `tj_coupon`;
CREATE TABLE `tj_coupon`  (
  `objId` int NOT NULL DEFAULT 0,
  `charId` int NOT NULL DEFAULT 0,
  `itemId` int NOT NULL DEFAULT 0,
  `count` int NOT NULL DEFAULT 0,
  `enchantLevel` int NOT NULL DEFAULT 0,
  `attrLevel` int NOT NULL DEFAULT 0,
  `bless` int NOT NULL DEFAULT 1,
  `lostTime` datetime NOT NULL,
  PRIMARY KEY (`objId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for town
-- ----------------------------
DROP TABLE IF EXISTS `town`;
CREATE TABLE `town`  (
  `town_id` int UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `leader_id` int UNSIGNED NOT NULL DEFAULT 0,
  `leader_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `tax_rate` int UNSIGNED NOT NULL DEFAULT 0,
  `tax_rate_reserved` int UNSIGNED NOT NULL DEFAULT 0,
  `sales_money` int UNSIGNED NOT NULL DEFAULT 0,
  `sales_money_yesterday` int UNSIGNED NOT NULL DEFAULT 0,
  `town_tax` int UNSIGNED NOT NULL DEFAULT 0,
  `town_fix_tax` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`town_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for town_npc
-- ----------------------------
DROP TABLE IF EXISTS `town_npc`;
CREATE TABLE `town_npc`  (
  `npc_id` int NOT NULL DEFAULT 0,
  `description` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `town` enum('TALK_ISLAND','SILVER_KNIGHT','GLUDIO','ORCISH_FOREST','WINDAWOOD','KENT','GIRAN','HEINE','WERLDAN','OREN','ELVEN_FOREST','ADEN','SILENT_CAVERN','BEHEMOTH','SILVERIA','OUM_DUNGEON','RESISTANCE','PIRATE_ISLAND','RECLUSE_VILLAGE','HIDDEN_VALLEY','CLAUDIA','REDSOLDER','SKYGARDEN','RUUN') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'TALK_ISLAND',
  PRIMARY KEY (`npc_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for trap
-- ----------------------------
DROP TABLE IF EXISTS `trap`;
CREATE TABLE `trap`  (
  `id` int NOT NULL,
  `note` varchar(64) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `type` varchar(64) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `gfxId` int NOT NULL,
  `isDetectionable` tinyint(1) NOT NULL,
  `base` int NOT NULL,
  `dice` int NOT NULL,
  `diceCount` int NOT NULL,
  `poisonType` char(1) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'n',
  `poisonDelay` int NOT NULL DEFAULT 0,
  `poisonTime` int NOT NULL DEFAULT 0,
  `poisonDamage` int NOT NULL DEFAULT 0,
  `monsterNpcId` int NOT NULL DEFAULT 0,
  `monsterCount` int NOT NULL DEFAULT 0,
  `teleportX` int NOT NULL DEFAULT 0,
  `teleportY` int NOT NULL DEFAULT 0,
  `teleportMapId` int NOT NULL DEFAULT 0,
  `skillId` int NOT NULL DEFAULT -1,
  `skillTimeSeconds` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ub_managers
-- ----------------------------
DROP TABLE IF EXISTS `ub_managers`;
CREATE TABLE `ub_managers`  (
  `ub_id` int UNSIGNED NOT NULL DEFAULT 0,
  `ub_manager_npc_id` int UNSIGNED NOT NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ub_rank
-- ----------------------------
DROP TABLE IF EXISTS `ub_rank`;
CREATE TABLE `ub_rank`  (
  `ub_id` int NOT NULL DEFAULT 0,
  `char_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `score` int NOT NULL,
  PRIMARY KEY (`ub_id`, `char_name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ub_settings
-- ----------------------------
DROP TABLE IF EXISTS `ub_settings`;
CREATE TABLE `ub_settings`  (
  `ub_id` int UNSIGNED NOT NULL DEFAULT 0,
  `ub_name` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `ub_mapid` int UNSIGNED NOT NULL DEFAULT 0,
  `ub_area_x1` int UNSIGNED NOT NULL DEFAULT 0,
  `ub_area_y1` int UNSIGNED NOT NULL DEFAULT 0,
  `ub_area_x2` int UNSIGNED NOT NULL DEFAULT 0,
  `ub_area_y2` int UNSIGNED NOT NULL DEFAULT 0,
  `min_lvl` int UNSIGNED NOT NULL DEFAULT 0,
  `max_lvl` int UNSIGNED NOT NULL DEFAULT 0,
  `max_player` int UNSIGNED NOT NULL DEFAULT 0,
  `enter_royal` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_knight` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_mage` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_elf` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_darkelf` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_dragonknight` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_illusionist` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_Warrior` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_Fencer` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_Lancer` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_male` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `enter_female` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `use_pot` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `hpr_bonus` int NOT NULL DEFAULT 0,
  `mpr_bonus` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`ub_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ub_times
-- ----------------------------
DROP TABLE IF EXISTS `ub_times`;
CREATE TABLE `ub_times`  (
  `ub_id` int UNSIGNED NOT NULL DEFAULT 0,
  `ub_time` int UNSIGNED NOT NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for uml_conversion
-- ----------------------------
DROP TABLE IF EXISTS `uml_conversion`;
CREATE TABLE `uml_conversion`  (
  `oldname` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `newname` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`oldname`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for util_fighter
-- ----------------------------
DROP TABLE IF EXISTS `util_fighter`;
CREATE TABLE `util_fighter`  (
  `Num` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `WinCount` int UNSIGNED NOT NULL DEFAULT 0,
  `LoseCount` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`Num`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for util_racer
-- ----------------------------
DROP TABLE IF EXISTS `util_racer`;
CREATE TABLE `util_racer`  (
  `Num` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `WinCount` int NOT NULL DEFAULT 0,
  `LoseCount` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`Num`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for war_time
-- ----------------------------
DROP TABLE IF EXISTS `war_time`;
CREATE TABLE `war_time`  (
  `castleId` int NOT NULL,
  `castleName` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `day` enum('SUN','MON','TUE','WED','THU','FRI','SAT') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'SUN',
  `hour` enum('23','22','21','20','19','18','17','16','15','14','13','12','11','10','9','8','7','6','5','4','3','2','1','0') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '0',
  `minute` enum('50','40','30','20','10','0') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`castleId`, `day`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for weapon
-- ----------------------------
DROP TABLE IF EXISTS `weapon`;
CREATE TABLE `weapon`  (
  `item_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_name_id` int UNSIGNED NOT NULL DEFAULT 0,
  `desc_kr` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_powerbook` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `note` text CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL,
  `desc_id` varchar(45) CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT '',
  `itemGrade` enum('ONLY','MYTH','LEGEND','HERO','RARE','ADVANC','NORMAL') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NORMAL',
  `type` enum('SWORD','DAGGER','TOHAND_SWORD','BOW','SPEAR','BLUNT','STAFF','STING','ARROW','GAUNTLET','CLAW','EDORYU','SINGLE_BOW','SINGLE_SPEAR','TOHAND_BLUNT','TOHAND_STAFF','KEYRINGK','CHAINSWORD') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'SWORD',
  `material` enum('NONE(-)','LIQUID(액체)','WAX(밀랍)','VEGGY(식물성)','FLESH(동물성)','PAPER(종이)','CLOTH(천)','LEATHER(가죽)','WOOD(나무)','BONE(뼈)','DRAGON_HIDE(용비늘)','IRON(철)','METAL(금속)','COPPER(구리)','SILVER(은)','GOLD(금)','PLATINUM(백금)','MITHRIL(미스릴)','PLASTIC(블랙미스릴)','GLASS(유리)','GEMSTONE(보석)','MINERAL(광석)','ORIHARUKON(오리하루콘)','DRANIUM(드라니움)') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE(-)',
  `weight` int UNSIGNED NOT NULL DEFAULT 0,
  `iconId` int UNSIGNED NOT NULL DEFAULT 0,
  `spriteId` int UNSIGNED NOT NULL DEFAULT 0,
  `dmg_small` int UNSIGNED NOT NULL DEFAULT 0,
  `dmg_large` int UNSIGNED NOT NULL DEFAULT 0,
  `safenchant` int NOT NULL DEFAULT 0,
  `use_royal` int UNSIGNED NOT NULL DEFAULT 0,
  `use_knight` int UNSIGNED NOT NULL DEFAULT 0,
  `use_mage` int UNSIGNED NOT NULL DEFAULT 0,
  `use_elf` int UNSIGNED NOT NULL DEFAULT 0,
  `use_darkelf` int UNSIGNED NOT NULL DEFAULT 0,
  `use_dragonknight` int UNSIGNED NOT NULL DEFAULT 0,
  `use_illusionist` int UNSIGNED NOT NULL DEFAULT 0,
  `use_warrior` int UNSIGNED NOT NULL DEFAULT 0,
  `use_fencer` int UNSIGNED NOT NULL DEFAULT 0,
  `use_lancer` int UNSIGNED NOT NULL DEFAULT 0,
  `hitmodifier` int NOT NULL DEFAULT 0,
  `dmgmodifier` int NOT NULL DEFAULT 0,
  `add_str` int NOT NULL DEFAULT 0,
  `add_con` int NOT NULL DEFAULT 0,
  `add_dex` int NOT NULL DEFAULT 0,
  `add_int` int NOT NULL DEFAULT 0,
  `add_wis` int NOT NULL DEFAULT 0,
  `add_cha` int NOT NULL DEFAULT 0,
  `add_hp` int NOT NULL DEFAULT 0,
  `add_mp` int NOT NULL DEFAULT 0,
  `add_hpr` int NOT NULL DEFAULT 0,
  `add_mpr` int NOT NULL DEFAULT 0,
  `add_sp` int NOT NULL DEFAULT 0,
  `m_def` int NOT NULL DEFAULT 0,
  `haste_item` int UNSIGNED NOT NULL DEFAULT 0,
  `double_dmg_chance` int UNSIGNED NOT NULL DEFAULT 0,
  `magicdmgmodifier` int NOT NULL DEFAULT 0,
  `canbedmg` int UNSIGNED NOT NULL DEFAULT 0,
  `min_lvl` int UNSIGNED NOT NULL DEFAULT 0,
  `max_lvl` int UNSIGNED NOT NULL DEFAULT 0,
  `bless` int UNSIGNED NOT NULL DEFAULT 1,
  `trade` int UNSIGNED NOT NULL DEFAULT 0,
  `retrieve` int UNSIGNED NOT NULL DEFAULT 0,
  `specialretrieve` int UNSIGNED NOT NULL DEFAULT 0,
  `cant_delete` int UNSIGNED NOT NULL DEFAULT 0,
  `cant_sell` int UNSIGNED NOT NULL DEFAULT 0,
  `max_use_time` int UNSIGNED NOT NULL DEFAULT 0,
  `regist_skill` int NOT NULL DEFAULT 0,
  `regist_spirit` int NOT NULL DEFAULT 0,
  `regist_dragon` int NOT NULL DEFAULT 0,
  `regist_fear` int NOT NULL DEFAULT 0,
  `regist_all` int NOT NULL DEFAULT 0,
  `hitup_skill` int NOT NULL DEFAULT 0,
  `hitup_spirit` int NOT NULL DEFAULT 0,
  `hitup_dragon` int NOT NULL DEFAULT 0,
  `hitup_fear` int NOT NULL DEFAULT 0,
  `hitup_all` int NOT NULL DEFAULT 0,
  `hitup_magic` int NOT NULL DEFAULT 0,
  `damage_reduction` int NOT NULL DEFAULT 0,
  `MagicDamageReduction` int NOT NULL DEFAULT 0,
  `reductionEgnor` int NOT NULL DEFAULT 0,
  `reductionPercent` int NOT NULL DEFAULT 0,
  `PVPDamage` int NOT NULL DEFAULT 0,
  `PVPDamageReduction` int NOT NULL DEFAULT 0,
  `PVPDamageReductionPercent` int NOT NULL DEFAULT 0,
  `PVPMagicDamageReduction` int NOT NULL DEFAULT 0,
  `PVPReductionEgnor` int NOT NULL DEFAULT 0,
  `PVPMagicDamageReductionEgnor` int NOT NULL DEFAULT 0,
  `abnormalStatusDamageReduction` int NOT NULL DEFAULT 0,
  `abnormalStatusPVPDamageReduction` int NOT NULL DEFAULT 0,
  `PVPDamagePercent` int NOT NULL DEFAULT 0,
  `expBonus` int NOT NULL DEFAULT 0,
  `rest_exp_reduce_efficiency` int NOT NULL DEFAULT 0,
  `shortCritical` int NOT NULL DEFAULT 0,
  `longCritical` int NOT NULL DEFAULT 0,
  `magicCritical` int NOT NULL DEFAULT 0,
  `addDg` int NOT NULL DEFAULT 0,
  `addEr` int NOT NULL DEFAULT 0,
  `addMe` int NOT NULL DEFAULT 0,
  `poisonRegist` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `imunEgnor` int NOT NULL DEFAULT 0,
  `stunDuration` int NOT NULL DEFAULT 0,
  `tripleArrowStun` int NOT NULL DEFAULT 0,
  `strangeTimeIncrease` int NOT NULL DEFAULT 0,
  `strangeTimeDecrease` int NOT NULL DEFAULT 0,
  `potionRegist` int NOT NULL DEFAULT 0,
  `potionPercent` int NOT NULL DEFAULT 0,
  `potionValue` int NOT NULL DEFAULT 0,
  `hprAbsol32Second` int NOT NULL DEFAULT 0,
  `mprAbsol64Second` int NOT NULL DEFAULT 0,
  `mprAbsol16Second` int NOT NULL DEFAULT 0,
  `hpPotionDelayDecrease` int NOT NULL DEFAULT 0,
  `hpPotionCriticalProb` int NOT NULL DEFAULT 0,
  `increaseArmorSkillProb` int NOT NULL DEFAULT 0,
  `attackSpeedDelayRate` int NOT NULL DEFAULT 0,
  `moveSpeedDelayRate` int NOT NULL DEFAULT 0,
  `Magic_name` varchar(20) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5203005 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for weapon_damege
-- ----------------------------
DROP TABLE IF EXISTS `weapon_damege`;
CREATE TABLE `weapon_damege`  (
  `item_id` int NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `addDamege` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`item_id`, `name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for weapon_skill
-- ----------------------------
DROP TABLE IF EXISTS `weapon_skill`;
CREATE TABLE `weapon_skill`  (
  `weapon_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `note` varchar(255) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `attackType` enum('PVE','PVP','ALL') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'ALL',
  `probability` int UNSIGNED NOT NULL DEFAULT 0,
  `fix_damage` int UNSIGNED NOT NULL DEFAULT 0,
  `random_damage` int UNSIGNED NOT NULL DEFAULT 0,
  `area` int NOT NULL DEFAULT 0,
  `skill_id` int NOT NULL DEFAULT -1,
  `skill_time` int UNSIGNED NOT NULL DEFAULT 0,
  `effect_id` int UNSIGNED NOT NULL DEFAULT 0,
  `effect_target` int UNSIGNED NOT NULL DEFAULT 0,
  `arrow_type` int UNSIGNED NOT NULL DEFAULT 0,
  `attr` enum('NONE','EARTH','FIRE','WATER','WIND','RAY') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'NONE',
  `enchant_probability` int NOT NULL DEFAULT 0,
  `enchant_damage` int NOT NULL DEFAULT 0,
  `int_damage` int NOT NULL DEFAULT 0,
  `spell_damage` int NOT NULL DEFAULT 0,
  `enchant_limit` int NOT NULL DEFAULT 0,
  `hpStill` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `hpStill_probabliity` int NOT NULL DEFAULT 0,
  `hpStillValue` int NOT NULL DEFAULT 0,
  `mpStill` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NOT NULL DEFAULT 'false',
  `mpStill_probabliity` int NOT NULL DEFAULT 0,
  `mpStillValue` int NOT NULL DEFAULT 0,
  `stillEffectId` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`weapon_id`, `attackType`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1202004 CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for weapon_skill_model
-- ----------------------------
DROP TABLE IF EXISTS `weapon_skill_model`;
CREATE TABLE `weapon_skill_model`  (
  `item_id` int NOT NULL,
  `desc_en` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `desc_kr` varchar(100) CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `procType` enum('NORMAL(MELEE)','NORMAL(RANGED)','WIDE(FRONT)','WIDE(TARGET)','CHAIN-SPOT','CHAOTIC(NO-EFFECT)','CHAOTIC(EFFECT)','POISON-DAMAGE','DICE-DAGGER','DISEASE','HOLD','HP-DRAIN','HP-DRAIN(MORE-DAMAGE)','MP-DRAIN','MP-DRAIN(MORE-DAMAGE)','MP-DRAIN(DISEASE)','PUMPKIN-CURSE','SILENCE','TURN-UNDEAD','CRITICAL-ATTACK','CRITICAL-ATTACK(DEMON)','LEGENDARY-WEAPON','ARMOR(HP-REGEN)','ARMOR(MP-REGEN)','ARMOR(HP-MP-REGEN)','ARMOR(PROB-REDUCTION)') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT NULL,
  `condition` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '0',
  `default_prob` int NULL DEFAULT 0,
  `enchant_prob` int NULL DEFAULT 0,
  `stat_prob` enum('NONE','STR','DEX','CON','WIS','INT','CHA') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'NONE',
  `stat_weight` int NULL DEFAULT 0,
  `limit_low_val` int NULL DEFAULT 0,
  `limit_high_val` int NULL DEFAULT 0,
  `min_val` int NULL DEFAULT 0,
  `max_val` int NULL DEFAULT 0,
  `stat_val` enum('NONE','STR','DEX','CON','WIS','INT','CHA') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'NONE',
  `stat_val_weight` int NULL DEFAULT 0,
  `enchant_val` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT '0',
  `enchant_val_weight` int NULL DEFAULT 0,
  `effect` int NULL DEFAULT 0,
  `PVE_Effect` int NULL DEFAULT 0,
  `Location` int NULL DEFAULT 0,
  `attr_type` enum('NONE','EARTH','FIRE','WATER','WIND') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'NONE',
  `is_magic` enum('true','false') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'true',
  `is_sp_val` enum('false','true') CHARACTER SET euckr COLLATE euckr_korean_ci NULL DEFAULT 'false',
  `sp_val_weight` int NULL DEFAULT 0,
  PRIMARY KEY (`item_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for weapon_skill_spell_def
-- ----------------------------
DROP TABLE IF EXISTS `weapon_skill_spell_def`;
CREATE TABLE `weapon_skill_spell_def`  (
  `id` int NOT NULL,
  `def_dmg` int NULL DEFAULT NULL,
  `def_ratio` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = euckr COLLATE = euckr_korean_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Procedure structure for DELETE_DEATH_PENALTY_24_HOUR
-- ----------------------------
DROP PROCEDURE IF EXISTS `DELETE_DEATH_PENALTY_24_HOUR`;
delimiter ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `DELETE_DEATH_PENALTY_24_HOUR`()
BEGIN
	DELETE FROM character_death_exp WHERE delete_time <= NOW();
	DELETE FROM character_death_item WHERE delete_time <= NOW();
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
