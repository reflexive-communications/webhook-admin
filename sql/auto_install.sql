-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the existing tables - this section generated from drop.tpl
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_webhook_legacy`;
DROP TABLE IF EXISTS `civicrm_webhook`;

SET FOREIGN_KEY_CHECKS=1;
-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_webhook
-- *
-- * Webhook configs
-- *
-- *******************************************************/
CREATE TABLE `civicrm_webhook` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Webhook ID',
  `name` varchar(255) NULL DEFAULT NULL COMMENT 'Webhook name',
  `description` text NULL DEFAULT NULL COMMENT 'Webhook description',
  `handler` varchar(255) NULL DEFAULT NULL COMMENT 'Handler class',
  `query_string` varchar(255) NULL DEFAULT NULL COMMENT 'Webhook query parameter',
  `processor` varchar(255) NULL DEFAULT NULL COMMENT 'Processor class',
  `options` text NULL DEFAULT NULL COMMENT 'Custom serialized data for PHP',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `index_query`(query_string)
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_webhook_legacy
-- *
-- * Webhook configs
-- *
-- *******************************************************/
CREATE TABLE `civicrm_webhook_legacy` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Webhook ID',
  `name` varchar(255) NULL DEFAULT NULL COMMENT 'Webhook name',
  `description` text NULL DEFAULT NULL COMMENT 'Webhook description',
  `handler` varchar(255) NULL DEFAULT NULL COMMENT 'Handler class',
  `query_string` varchar(255) NULL DEFAULT NULL COMMENT 'Webhook query parameter',
  `processor` varchar(255) NULL DEFAULT NULL COMMENT 'Processor class',
  `options` text NULL DEFAULT NULL COMMENT 'Custom serialized data for PHP',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `index_query`(query_string)
)
ENGINE=InnoDB;
