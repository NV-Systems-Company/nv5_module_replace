<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VIETNAM DIGITAL TRADING TECHNOLOGY  (contact@thuongmaiso.vn)
 * @Copyright (C) 2014 VIETNAM DIGITAL TRADING TECHNOLOGY . All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */
if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) die('Stop!!!');
define('NV_IS_FILE_ADMIN', true);
$allow_func = array(
    'main',
    'config',
	'ajax',
	'logs',
	'logs_del'
);
function nv_insert_replace_logs($lang = '', $module_name = '', $name_key = '', $note_action = '', $userid = 0, $link_acess = '')
{
    global $db_config, $db, $module_data;
    $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_logs
		(lang, module_name, name_key, note_action, link_acess, userid, log_time) VALUES
		(:lang, :module_name, :name_key, :note_action, :link_acess, :userid, ' . NV_CURRENTTIME . ')');
    $sth->bindParam(':lang', $lang, PDO::PARAM_STR);
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    $sth->bindParam(':name_key', $name_key, PDO::PARAM_STR);
    $sth->bindParam(':note_action', $note_action, PDO::PARAM_STR, strlen($note_action));
    $sth->bindParam(':link_acess', $link_acess, PDO::PARAM_STR);
    $sth->bindParam(':userid', $userid, PDO::PARAM_INT);
    if ($sth->execute()) {
        return true;
    }
    return false;
}
/**
 * nv_siteinfo_getlang()
 *
 * @return
 */
function nv_siteinfo_getlang()
{
    global $db_config, $nv_Cache, $db, $module_data;
    $sql = 'SELECT DISTINCT lang FROM ' . NV_PREFIXLANG . '_' . $module_data . '_logs';
    $result = $nv_Cache->db($sql, 'lang', 'siteinfo');
    $array_lang = array();
    if (!empty($result)) {
        foreach ($result as $row) {
            $array_lang[] = $row['lang'];
        }
    }
    return $array_lang;
}
/**
 * nv_siteinfo_getuser()
 *
 * @return
 */
function nv_siteinfo_getuser()
{
    global $db_config, $nv_Cache, $module_data;
    $sql = 'SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN ( SELECT DISTINCT userid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_logs WHERE userid!=0 ) ORDER BY username ASC';
    $result = $nv_Cache->db($sql, 'userid', 'siteinfo');
    $array_user = array();
    if (!empty($result)) {
        foreach ($result as $row) {
            $array_user[] = array(
                'userid' => $row['userid'],
                'username' => $row['username']
            );
        }
    }
    return $array_user;
}
/**
 * nv_siteinfo_getmodules()
 *
 * @return
 */
function nv_siteinfo_getmodules()
{
    global $db_config, $nv_Cache, $module_data;
    $sql = 'SELECT DISTINCT module_name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_logs';
    $result = $nv_Cache->db($sql, 'module_name', 'siteinfo');
    $array_modules = array();
    if (!empty($result)) {
        foreach ($result as $row) {
            $array_modules[] = $row['module_name'];
        }
    }
    return $array_modules;
}
/**
 * nv_get_lang_module()
 *
 * @param mixed $mod
 * @return
 */
function nv_get_lang_module($mod)
{
    global $site_mods;
    $lang_module = array();
    if (isset($site_mods[$mod])) {
        if (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_' . NV_LANG_INTERFACE . '.php')) {
            include NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_' . NV_LANG_INTERFACE . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_' . NV_LANG_DATA . '.php')) {
            include NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_' . NV_LANG_DATA . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_en.php')) {
            include NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/language/admin_en.php';
        }
    }
    return $lang_module;
}