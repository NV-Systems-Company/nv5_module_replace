<?php

/**
 * @Project NUKEVIET 4.x
 * @Author ThuongMaiSo (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2015 ThuongMaiSo. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 20 Oct 2015 03:55:17 GMT
 */
if (!defined('NV_IS_FILE_SITEINFO')) die('Stop!!!');

$lang_siteinfo = nv_get_lang_module($mod);

if ($data['type'] == 'item_error') {
    $data['title'] = $data['content']['content'];
    $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $data['obid'];
} else {
    $data['title'] = sprintf($lang_siteinfo['notification_post_queue'], $data['content']['num']);
    $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;status=0';
}