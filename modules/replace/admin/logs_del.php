<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VIETNAM DIGITAL TRADING TECHNOLOGY  (contact@thuongmaiso.vn)
 * @Copyright (C) 2018 VIETNAM DIGITAL TRADING TECHNOLOGY . All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 30-07-2018 20:59
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');
// Delete all log
if ($nv_Request->get_title('logempty', 'post', '') == md5('replace_' . NV_CHECK_SESSION . '_' . $admin_info['userid'])) {
    if ($db->query('TRUNCATE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_logs')) {
        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['log_empty_log'], 'All', $admin_info['userid']);
        die('OK');
    } else {
        die($lang_module['log_del_error']);
    }
}
$id = $nv_Request->get_int('id', 'post,get', 0);
$contents = 'NO_' . $lang_module['log_del_error'];
$number_del = 0;
if ($id > 0) {
    if ($db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_logs WHERE id=' . $id)) {
        $contents = 'OK_' . $lang_module['log_del_ok'];
        ++$number_del;
    }
} else {
    $listall = $nv_Request->get_string('listall', 'post,get');
    $array_id = explode(',', $listall);
    $array_id = array_map('intval', $array_id);
    foreach ($array_id as $id) {
        if ($id > 0) {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_logs WHERE id=' . $id);
            ++$number_del;
        }
    }
    $contents = 'OK_' . $lang_module['log_del_ok'];
}
nv_insert_logs(NV_LANG_DATA, $module_name, $lang_global['delete'] . ' ' . $lang_module['logs_title'], $number_del, $admin_info['userid']);
nv_htmlOutput($contents);