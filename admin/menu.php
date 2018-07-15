<?php
$adminmenu = array();
$i         = 1;

$adminmenu[$i]['title'] = _MI_TAD_ADMIN_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['desc']  = _MI_TAD_ADMIN_HOME_DESC;
$adminmenu[$i]['icon']  = 'images/admin/home.png';

$i++;
$adminmenu[$i]['title'] = "設定社團資料";
$adminmenu[$i]['link']  = 'admin/main.php';
$adminmenu[$i]['desc']  = _MI_KWCLUB_ADMENU2_DESC;
$adminmenu[$i]['icon']  = "images/admin/button.png";

$i++;
$adminmenu[$i]['title'] = _MI_KWCLUB_ADMENU5;
$adminmenu[$i]['link']  = 'admin/register.php';
$adminmenu[$i]['desc']  = _MI_KWCLUB_ADMENU5_DESC;
$adminmenu[$i]['icon']  = "images/admin/button.png";

$i++;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['desc']  = _MI_TAD_ADMIN_ABOUT_DESC;
$adminmenu[$i]['icon']  = 'images/admin/about.png';
