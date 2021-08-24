<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VIETNAM DIGITAL TRADING TECHNOLOGY  (contact@thuongmaiso.vn)
 * @Copyright (C) 2018 VIETNAM DIGITAL TRADING TECHNOLOGY . All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 30-07-2018 20:59
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');
if ($nv_Request->isset_request('changestatus', 'get')) {
	$new_status = $nv_Request->get_int('new_status', 'post', 0);
	$id = $nv_Request->get_int('id', 'post', 0);
	$data =  $db->query('SELECT * FROM '. NV_PREFIXLANG . '_' . $module_data . '_config WHERE id = ' . intval($id))->fetch();
	$sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_config SET
		status = ' . $new_status . '
	WHERE id =' . $id);
	$ct_query = (int) $sth->execute();
	if($ct_query){
		$note_action = $data['config_name'];
		nv_insert_replace_logs(NV_LANG_DATA, $module_data, $lang_module['update_status'], $note_action , $admin_info['userid']);
		$nv_Cache->delMod($module_name);
		die('OK_' . $id);
	}else
		die($lang_module['template_update_no']);
}
if ($nv_Request->isset_request('load_cat', 'get')) {
	$load_cat = $nv_Request->get_int('load_cat', 'get', '');
    $module_array = $nv_Request->get_title('module', 'get', '');
	list($module_f, $module) = explode('-',$module_array);
    $value = $nv_Request->get_title('value', 'get', '');
    if (!empty($value)) {
        $value = nv_base64_decode($value);
        $value = explode('|', $value);
    }
    $value[0] = !empty($value[0]) ? $value[0] : '';
    $value[1] = !empty($value[1]) ? $value[1] : 0;
    if (empty($module)) return '';
    if($module_array == '')
		die($lang_module['no_result']);
    $global_array_cat = array();
	if($module_f == 'news'){
		$result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat ORDER BY sort ASC');
		while ($l = $result->fetch()) {
			$global_array_cat[$l['catid']] = $l;
		}
	}elseif($module_f == 'shops'){
		$result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_catalogs ORDER BY sort ASC');
		while ($l = $result->fetch()) {
			$l['title'] = $l[NV_LANG_DATA . '_title'];
			$global_array_cat[$l['catid']] = $l;
		}
	}else{
		die('' . $lang_module['no_result'] . '');
	}
    while ($l = $result->fetch()) {
        $global_array_cat[$l['catid']] = $l;
    }
    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    if (!empty($global_array_cat)) {
        foreach ($global_array_cat as $cat_id => $cat_data) {
            $cat_data['space'] = '';
            if ($cat_data['lev'] > 0) {
                for ($i = 1; $i <= $cat_data['lev']; $i++) {
                    $cat_data['space'] .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                }
            }
            $array_catid = explode(',', $value[0]);
            $cat_data['catid_checked'] = '';
            $cat_data['catdefault_display'] = 'disabled="disabled"';
            if (in_array($cat_id, $array_catid)) {
                $cat_data['catid_checked'] = 'checked="checked"';
                $cat_data['catdefault_display'] = '';
            }
            //$cat_data['catdefault_checked'] = $cat_id == $value[1] ? ' checked="checked"' : '';
            $xtpl->assign('CAT', $cat_data);
            $xtpl->parse('list_cat.loop');
            $xtpl->parse('list_cat_2.loop');
        }
    }
	if($load_cat==1){
		$xtpl->parse('list_cat');
		$contents = $xtpl->text('list_cat');
	}else{
		$xtpl->parse('list_cat_2');
		$contents = $xtpl->text('list_cat_2');
	}
    die($contents);
}
if ($nv_Request->isset_request('replace', 'get')) {
	$replace = $nv_Request->get_int('replace', 'get', '');
	$module_array = $nv_Request->get_title('module', 'get,post', '');
	list($module_f, $module) = explode('-',$module_array);
	$cat_id = $nv_Request->get_int('catid', 'post', '');
	$id = $nv_Request->get_int('id', 'post', '');
	$autoreplace = $nv_Request->get_int('autoreplace', 'post', '');
	$autolink = $nv_Request->get_int('autolink', 'post', '');
	$configid = $nv_Request->get_int('configid', 'post', '');
	$datefrom = $nv_Request->get_title('date_from', 'post', '');
	$dateto = $nv_Request->get_title('date_to', 'post', '');
	$hometext = $nv_Request->get_int('hometext', 'post', '');
	//die($datefrom . ' - ' . $dateto);
	$where = '';
	if (! empty($datefrom)) {
        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $datefrom, $match)) {
            $from = mktime(0, 0, 0, $match[2], $match[1], $match[3]);
            $where .= ' AND edittime >= ' . $from;
        }
    }
    if (! empty($dateto)) {
        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $dateto, $match)) {
            $to = mktime(0, 0, 0, $match[2], $match[1], $match[3]);
            $where .= ' AND edittime <= ' . $to;
        }
    }
	if($module_array == '')
		die($lang_module['no_result']);
	if($module_f == 'news'){
		$list_id = $db->query('SELECT id FROM '. NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows WHERE catid='. $cat_id . ' ' . $where)->fetchAll();
	}elseif($module_f == 'shops'){
		$list_id = $db->query('SELECT id FROM '. $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_rows WHERE listcatid='. $cat_id . ' ' . $where)->fetchAll();
	}else{
		die('' . $lang_module['no_result'] . '');
	}
	$array_list='l';
	foreach($list_id as $k => $v){
		$array_list.=','.$v['id'];
	}
	$array_list=str_replace('l,','',$array_list);
	if($replace ==1)
		die($array_list);
	if($replace ==2){
		$json=array();
		if($module_f == 'news'){
			$title = $db->query('SELECT * FROM '. NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows WHERE catid='. $cat_id .' AND id=' . $id)->fetch();
			$alias = $db->query('SELECT alias FROM '. NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat WHERE catid='. $title['catid'] .'')->fetchColumn();
		}elseif($module_f == 'shops'){
			$title = $db->query('SELECT * FROM '. $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_rows WHERE listcatid='. $cat_id .' AND id=' . $id)->fetch();
			$alias = $db->query('SELECT ' . NV_LANG_DATA . '_alias FROM '. $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_catalogs WHERE catid='. $title['listcatid'] .'' )->fetchColumn();
		}else{
			die('' . $lang_module['no_result'] . '');
		}
		if(!empty($title['id'])){
			$json['id'] = $title['id'];
			if($module_f == 'news'){
				$json['title'] =  $title['title'] ;
				$json['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=' . $alias  . '/'.$title['alias'] . '-' . $title['id'],true);
			}elseif($module_f == 'shops'){
				$json['title'] = $title[NV_LANG_DATA .'_title'] ;
				$json['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=' . $alias  . '/'.$title[NV_LANG_DATA .'_alias'] ,true);
			}else{
				$json['title'] = '';
				$json['link'] = '';
			}
			$json['adminlink'] = nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=content&id=' . $title['id']);
			$json['success'] = 'SUCCESS';
		}
		else{
			$json['title'] =  $lang_module['no_result'];
			$json['success'] = 'NO';
		}
		header( 'Content-Type: application/json' );
		echo json_encode($json);
		exit();
		
	}elseif($replace ==3){
		if($module_f == 'news'){
			$title = $db->query('SELECT * FROM '. NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_rows WHERE catid='. $cat_id .' AND id=' . $id)->fetch();
			$title_name=$title['title'];
			$alias = $db->query('SELECT alias FROM '. NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_cat WHERE catid='. $title['catid'] .'')->fetchColumn();
		}elseif($module_f == 'shops'){
			$title = $db->query('SELECT * FROM '. $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_rows WHERE listcatid='. $cat_id .' AND id=' . $id)->fetch();
			$title_name=$title[NV_LANG_DATA .'_title'];
			$alias = $db->query('SELECT ' . NV_LANG_DATA . '_alias FROM '. $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_catalogs WHERE catid='. $title['listcatid'] .'' )->fetchColumn();
		}else{
			$json['body_result'] =  $lang_module['no_result'];
			$json['success'] = 'NO';
		}
		if(!empty($title['id'])){
			$json = array();
			$json['id'] = $title['id'];
			if($module_f == 'news'){
				$news_bodytext = $db->query('SELECT bodyhtml FROM '. NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_detail WHERE id=' . $id)->fetch();
				$json['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=' . $alias  . '/'.$title['alias'] . '-' . $title['id'],true);
				$news_hometext=$title['hometext'];
			}elseif($module_f == 'shops'){
				$news_bodytext = $db->query('SELECT ' . NV_LANG_DATA . '_bodytext FROM '. $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_rows WHERE id=' . $id)->fetch();
				$news_bodytext['bodyhtml'] = $news_bodytext[NV_LANG_DATA . '_bodytext'];
				$json['link'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=' . $alias  . '/'.$title[NV_LANG_DATA .'_alias'] ,true);
				$news_hometext=$title[NV_LANG_DATA .'_hometext'];
			}else{
				$json['body_result'] =  $lang_module['no_result'];
				$json['success'] = 'NO';
			}
			$data =  $db->query('SELECT * FROM '. NV_PREFIXLANG . '_' . $module_data . '_config WHERE id = ' . intval($configid))->fetch();
			$reg_post_body_replace = $data['case_replace'] ? '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))($news_bodytext)/imsU' : '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))($news_bodytext)/msU';
			$reg_post_home_replace = $data['case_replace'] ? '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))($news_hometext)/imsU' : '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))($news_hometext)/msU';
			$keyword = '[|]';
			$totalbody=0;
			$totalhome=0;
			if($autoreplace > 0 ){
				$data['config_replace'] = unserialize($data['config_replace']);
				$keyword .= ' | Keyword:';
				foreach ($data['config_replace'] as $replace) {
					$regexps = str_replace('$news_bodytext', $replace['find'], $reg_post_body_replace);
					$replaces = '$$$url$$';
					$newbodytext = preg_replace($regexps, $replaces, $news_bodytext['bodyhtml'], 100);
					$numbody=substr_count($newbodytext,'$$$url$$', 0, strlen($newbodytext));
					$news_bodytext['bodyhtml'] = str_replace('$$$url$$', $replace['replace'], $newbodytext);
					$keyword .= $replace['find'] . ',';
					$total+=$numbody;
					if($hometext >0){
						$regexpsh = str_replace('$news_hometext', $replace['find'], $reg_post_home_replace);
						$newhometext = preg_replace($regexpsh, $replaces, $news_hometext, 100);
						$numhome=substr_count($newhometext,'$$$url$$', 0, strlen($newhometext));
						$news_hometext = str_replace('$$$url$$', $replace['replace'], $newhometext);
						$totalhome+=$numhome;
					}
					
				}
			}
			$news_hometexts['bodyhtml']=$news_hometext;
			$reg_body_post = $data['casesens'] ? '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))($news_bodytext)/imsU' : '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))($news_bodytext)/msU';
			$reg_home_post = $data['casesens'] ? '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))($news_hometexts)/imsU' : '/(?!(?:[^<\[]+[>\]]|[^>\]]+<\/a>))($news_hometexts)/msU';
			
			if($autolink > 0 ){
				$data['config_keywords'] = unserialize($data['config_keywords']);
				$keyword .= ' | Link:';
				
				foreach ($data['config_keywords'] as $keyword) {
					$url = $keyword['link'];
					$key_home=$keyword['keywords'];
					$limit=$keyword['limit'];
					$regexp = str_replace('$news_bodytext', $keyword['keywords'], $reg_body_post);
					$replace = '<a title="$1" href="$$$url$$$" ' . ($keyword['target'] == 1 ? 'target="_blank"' : '') . '>$1</a>';
					$newbodytext = preg_replace($regexp, $replace, $news_bodytext['bodyhtml'], $keyword['limit']);
					
					if ($newbodytext != $keyword['keywords']) {
						$numbody=substr_count($newbodytext,'$$$url$$$', 0, strlen($newbodytext));
						$news_bodytext['bodyhtml'] = str_replace('$$$url$$$', $url, $newbodytext);
						$i++;	
					}
					$keyword .= $keyword['keywords'] . ',';
					$total+=$numbody;
					 $hometext_value = $db->query('SELECT config_value FROM '. $db_config['prefix'] .'_config WHERE module ="' . $site_mods[$module]['module_data'] . '" AND config_name ="htmlhometext"')->fetchColumn();
					 if((int)$hometext_value > 0){
						 if($hometext >0){
							$regexph = str_replace('$news_hometexts', $key_home, $reg_home_post);
							$newhometext = preg_replace($regexph, $replace, $news_hometexts['bodyhtml'], $limit);
							 if ($newhometext != $key_home) {
								$numhome=substr_count($newhometext,'$$$url$$$', 0, strlen($newhometext));
								$news_hometexts['bodyhtml'] = str_replace('$$$url$$$', $url, $newhometext);
								
								$i++;	
							} 
							$totalhome+=$numhome; 
							
						} 
					}   
				}
				//$regexph = str_replace('$news_hometext', 'NukeViet', $reg_home_post);
			}
			if($module_f == 'news'){
				$sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_detail SET
						bodyhtml=:bodyhtml
					WHERE id =' . $id);
				 $db->query("UPDATE " . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_rows SET
						hometext='" . $news_hometexts['bodyhtml'] . "' WHERE id =" . $id);
				$list_catid=explode(',',$title['listcatid']);
				foreach ($list_catid as $list) {
					 $db->query("UPDATE " . NV_PREFIXLANG . "_" . $site_mods[$module]['module_data'] . "_" . $list . " SET
						hometext='" . $news_hometexts['bodyhtml'] . "' WHERE id =" . $id); 
				}
					
			}elseif($module_f == 'shops'){
				$sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $site_mods[$module]['module_data'] . '_rows SET
						' .NV_LANG_DATA . '_bodytext=:bodyhtml, ' .NV_LANG_DATA . '_hometext="' . $news_hometexts['bodyhtml'] . '"
					WHERE id =' . $id);
			}else{
				$json['body_result'] =  $lang_module['no_result'];
				$json['success'] = 'NO';
			}
			$sth->bindParam(':bodyhtml', $news_bodytext['bodyhtml'], PDO::PARAM_STR, strlen($news_bodytext['bodyhtml']));
			$ct_query = (int) $sth->execute();
			if($ct_query){
				$json['adminlink'] = nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=content&id=' . $title['id']);
				$note_action = 'Module: ' . $module . ', ID: ' . $title['id'] . ' - ' . $lang_module['template_update'] . ' ' . $data['config_name'] . ' ' .$lang_module['on'] . ' "' . $title_name  .'" ' . $lang_module['success'] .' <a class="btn btn-primary btn-xs btn_edit" href="' . $json['adminlink'] . '" target="_blank"><em class="fa fa-edit margin-right"></em> ' . $lang_module['edit'] . '</a> &nbsp; <a class="btn btn-primary btn-xs btn_edit" href="' . $json['link'] . '" target="_blank"><em class="fa fa-view margin-right"></em> ' . $lang_module['preview'] . '</a>';
				
				if($total!=0){	
					nv_insert_replace_logs(NV_LANG_DATA, $module, ' ' . $data['config_name'], $note_action , $admin_info['userid']);
					if($totalhome!=0){
						$json['home_result'] =  $a;//$news_hometexts['bodyhtml'];//$lang_module['template_update_success'];
					}else{
						$json['home_result'] =  $a;//$news_hometexts['bodyhtml'];//$lang_module['template_no_update'];
					}
					$json['body_result'] =  $lang_module['template_update_success'];
					$json['success'] = 'SUCCESS';
				}else{
					if($totalhome!=0){
						nv_insert_replace_logs(NV_LANG_DATA, $module, ' ' . $data['config_name'], $note_action , $admin_info['userid']);
						$json['home_result'] =  $a;//$news_hometexts['bodyhtml'];//$lang_module['template_update_success'];
					}else{
						$json['home_result'] =  $a;//$news_hometexts['bodyhtml'];//$lang_module['template_no_update'];
					}
					$json['body_result'] = $lang_module['template_no_update'];
					$json['success'] = 'SUCCESS';
				}
			}else{
				die($lang_module['template_update_no']);
				$json['success'] = 'SUCCESS';
			}
		}
		else{
			$json['body_result'] =  $lang_module['no_result'];
			$json['success'] = 'NO';
		}
		header( 'Content-Type: application/json' );
		echo json_encode($json);
		exit();
	}
}