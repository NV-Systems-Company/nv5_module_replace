<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VIETNAM DIGITAL TRADING TECHNOLOGY  (contact@thuongmaiso.vn)
 * @Copyright (C) 2014 VIETNAM DIGITAL TRADING TECHNOLOGY . All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */
if (!defined('NV_MAINFILE')) die('Stop!!!');

$module_version = array(
    'name' => 'Replace Content',
    'modfuncs' => 'main,',
    'change_alias' => '',
    'submenu' => 'main',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '2.0.00',
    'date' => 'Tue, 20 Oct 2015 03:55:17 GMT',
    'author' => 'VIETNAM DIGITAL TRADING TECHNOLOGY (contact@thuongmaiso.vn)',
    'uploads_dir' => array(
        $module_upload,
        $module_upload . '/template'
    ),
    'note' => 'Module thay thế từ khóa và link trong module news và shops'
);