<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VIETNAM DIGITAL TRADING TECHNOLOGY  (contact@thuongmaiso.vn)
 * @Copyright (C) 2018 VIETNAM DIGITAL TRADING TECHNOLOGY . All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 30-07-2018 20:59
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$row = array();
$error = array();
 $row['news_module'] = '';
$row['news_catid'] = !empty($row['news_catid']) ? nv_base64_encode($row['news_catid']) : '';
$row['news_groups'] = !empty($row['news_groups']) ? nv_base64_encode($row['news_groups']) : '';
$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$sql = "SELECT title, custom_title, module_file FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_modules WHERE module_file = 'news' or module_file = 'shops'";
$list = $nv_Cache->db($sql, 'title', 'modules');
foreach ($list as $l) {
    if (isset($site_mods[$l['title']])) {
        $l['selected'] = $l['title'] == $row['news_module'] ? 'selected' : '';
        $xtpl->assign('NEWS', $l);
        $xtpl->parse('main.news_module');
    }
}
$sql = "SELECT id,config_name, config_keywords, config_replace FROM " . NV_PREFIXLANG . '_' . $module_data . "_config WHERE status = 1";
$list_config = $nv_Cache->db($sql, 'config_name', $module_name);
foreach ($list_config as $l=>$valus) {
	$xtpl->assign('CONFIG', $valus);
    $xtpl->parse('main.replace');
}
if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');
$page_title = $lang_module['items_add'];
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';