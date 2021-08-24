<?php

/**
 * @Project NUKEVIET 4.x
 * @Author ThuongMaiSo (hoangnt@nguyenvan.vn)
 * @Copyright (C) 2015 ThuongMaiSo. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 20 Oct 2015 03:55:17 GMT
 */
if (!defined('NV_IS_MOD_AUTOGET_NEWS')) {
    die('Stop!!!');
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
header('Location:' . NV_BASE_SITEURL);
die();
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';