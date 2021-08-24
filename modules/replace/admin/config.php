<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VIETNAM DIGITAL TRADING TECHNOLOGY  (contact@thuongmaiso.vn)
 * @Copyright (C) 2018 VIETNAM DIGITAL TRADING TECHNOLOGY . All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 30-07-2018 20:59
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');
$page_title = $lang_module['replace_list'];
$list_all =  $db->query('SELECT * FROM '. NV_PREFIXLANG . '_' . $module_data . '_config ')->fetchAll();
$data = array();
$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
if($data['id'] == 0){
	$data['mod'] = 'add';
	$data['casesens'] = 0;
	$data['case_replace'] = 0;
}else{
	$data['mod'] = 'edit'; 
	$data =  $db->query('SELECT * FROM '. NV_PREFIXLANG . '_' . $module_data . '_config WHERE id = ' . intval($data['id']))->fetch();
	$data['newstatus'] = ($data['status'] ==1) ? 0 : 1;
}
if ($nv_Request->isset_request('savesetting', 'post')) {
    $data['config_name'] = $nv_Request->get_title('config_name', 'post', 0);
    $data['config_keywords'] = $nv_Request->get_array('config_keywords', 'post');
    $data['casesens'] = $nv_Request->get_int('casesens', 'post', 0);
    $data['case_replace'] = $nv_Request->get_int('case_replace', 'post', 0);
    $data['config_replace'] = $nv_Request->get_array('config_replace', 'post');
    if (!empty($data['config_keywords'])) {
        foreach ($data['config_keywords'] as $index => $value) {
            if (empty($value['keywords']) or empty($value['link'])) {
                unset($data['config_keywords'][$index]);
            } else {
                $data['config_keywords'][$index]['limit'] = empty($value['limit']) ? -1 : $value['limit'];
            }
        }
    }
    $data['config_keywords'] = !empty($data['config_keywords']) ? serialize($data['config_keywords']) : '';
    if (!empty($data['config_replace'])) {
        foreach ($data['config_replace'] as $index => $value) {
            if (empty($value['find']) or empty($value['replace'])) {
                unset($data['config_replace'][$index]);
            }
        }
    }
    $data['config_replace'] = !empty($data['config_replace']) ? serialize($data['config_replace']) : '';
    if($data['mod'] == 'add'){
		$sth = $db->prepare("INSERT INTO " . NV_PREFIXLANG . '_' . $module_data . "_config ( config_name, config_replace, config_keywords, casesens, case_replace, userid, status) VALUES ( :config_name, :config_replace, :config_keywords, " . $data['casesens'] . ", " . $data['case_replace'] . ", " . $admin_info['admin_id'] . ", '1')");
		$sth->bindParam(':config_name', $data['config_name'], PDO::PARAM_STR);
		$sth->bindParam(':config_replace', $data['config_replace'], PDO::PARAM_STR);
		$sth->bindParam(':config_keywords', $data['config_keywords'], PDO::PARAM_STR);
		$sth->execute();
	}else{
		$sth = $db->prepare("UPDATE " . NV_PREFIXLANG . '_' . $module_data . "_config SET  config_name = :config_name, config_replace = :config_replace, config_keywords = :config_keywords, userid=" . $admin_info['admin_id'] . ", casesens = " . intval($data['casesens']) . ", case_replace = " . intval($data['case_replace']) . " WHERE id = " . intval($data['id']));
		$sth->bindParam(':config_name', $data['config_name'], PDO::PARAM_STR);
		$sth->bindParam(':config_replace', $data['config_replace'], PDO::PARAM_STR);
		$sth->bindParam(':config_keywords', $data['config_keywords'], PDO::PARAM_STR);
		$sth->execute();
	}	
    //nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['config'], "Config", $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . '=' . $op);
    die();
}
$data['ck_casesens'] = $data['casesens'] ? 'checked="checked"' : '';
$data['ck_case_replace'] = $data['case_replace'] ? 'checked="checked"' : '';
$array_status = array(
    $lang_module['inactive'],
    $lang_module['active']
);
$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $data);
$xtpl->assign('NV_BASE_ADMINURL ', NV_BASE_ADMINURL );
foreach($list_all as $key=>$list){
	$list['newstatus'] = ($list['status'] ==1) ? 0 : 1;
	$list['username']=$db->query('SELECT username FROM '. NV_USERS_GLOBALTABLE . ' WHERE userid=' . $list['userid'] . ' ')->fetchColumn();
	if($list['status']=='1') $list['check'] = "checked='checked'";
	$list['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . '=' . $op .'&id=' . $list['id'];
	if (!empty($list['config_keywords'])) {
		$list['config_keywords'] = !empty($list['config_keywords']) ? unserialize($list['config_keywords']) : '';
		foreach ($list['config_keywords'] as $keywords) {
			$xtpl->assign('LISTKEYWORD', $keywords);  
			$xtpl->parse('main.list.keywords');
		}
	}
	$xtpl->assign('LIST', $list);
	foreach ($array_status as $key => $val) {
        $xtpl->assign('STATUS', array(
            'key' => $key,
            'val' => $val,
            'selected' => ($key == $list['status']) ? ' selected="selected"' : ''
        ));
        $xtpl->parse('main.list.status');
    }
	$xtpl->parse('main.list');
}
if($data['id'] == 0){
	$xtpl->assign('action_mod', $lang_module['add'] . ' ' . $lang_module['replace']);
}else{
	$xtpl->assign('action_mod', $lang_module['edit'] . ' ' . $lang_module['replace'] );
}
// Tu khoa
$array_keywords = array();
$array_keywords[] = array(
    'keywords' => '',
    'link' => '',
    'limit' => -1,
    'target' => ''
);
$array_target = array(
    0 => $lang_module['config_autolink_target_0'],
    1 => $lang_module['config_autolink_target_1']
);
$data['config_keywords'] = !empty($data['config_keywords']) ? unserialize($data['config_keywords']) : $array_keywords;
if (!empty($data['config_keywords'])) {
    $number = 0;
    foreach ($data['config_keywords'] as $keywords) {
        $keywords['number'] = $number;
        $keywords['limit'] = $keywords['limit'] > 0 ? $keywords['limit'] : '';
        $xtpl->assign('KEYWORDS', $keywords);
        foreach ($array_target as $index => $value) {
            $sl = $index == $keywords['target'] ? 'selected="selected"' : '';
            $xtpl->assign('TARGET', array(
                'index' => $index,
                'value' => $value,
                'selected' => $sl
            ));
            $xtpl->parse('main.keywords.target');
        }
        $xtpl->parse('main.keywords');
        $number++;
    }
    $xtpl->assign('KEYWORDS_COUNT', $number);
}
foreach ($array_target as $index => $value) {
    $xtpl->assign('TARGET', array(
        'index' => $index,
        'value' => $value
    ));
    $xtpl->parse('main.target_js');
}
// tim kiem thay the
$array_replace = array();
$array_replace[] = array(
    'find' => '',
    'replace' => ''
);
$data['config_replace'] = !empty($data['config_replace']) ? unserialize($data['config_replace']) : $array_replace;
if (!empty($data['config_replace'])) {
    $number = 0;
    foreach ($data['config_replace'] as $replace) {
        $replace['number'] = $number;
		$xtpl->assign('REPLACE', $replace);
        $xtpl->parse('main.replace');
        $number++;
    }
    $xtpl->assign('REPLACE_COUNT', $number);
}
$xtpl->parse('main');
$contents = $xtpl->text('main');
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';