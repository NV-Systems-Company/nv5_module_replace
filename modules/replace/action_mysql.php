<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VIETNAM DIGITAL TRADING TECHNOLOGY  (contact@thuongmaiso.vn)
 * @Copyright (C) 2014 VIETNAM DIGITAL TRADING TECHNOLOGY . All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */
if (!defined('NV_IS_FILE_MODULES')) die('Stop!!!');
$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs";
$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config(
  id int(11) NOT NULL AUTO_INCREMENT,
  config_name varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  config_replace text COLLATE utf8mb4_unicode_ci NOT NULL,
  config_keywords text COLLATE utf8mb4_unicode_ci NOT NULL,
  casesens int(2) NOT NULL DEFAULT '0',
  case_replace int(2) NOT NULL DEFAULT '0',
  userid int(11) NOT NULL DEFAULT '0',
  status int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY config_name (config_name)
) ENGINE=MyISAM";
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs (
  id int(11) NOT NULL AUTO_INCREMENT,
  lang varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  module_name varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  name_key varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  note_action text COLLATE utf8mb4_unicode_ci NOT NULL,
  link_acess varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  userid mediumint(8) UNSIGNED NOT NULL,
  log_time int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM";