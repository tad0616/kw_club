<?php
//載入XOOPS主設定檔（必要）
include_once "../../mainfile.php";
//載入自訂的共同函數檔
include_once "function.php";

//判斷是否對該模組有管理權限（通常就是站長了）
if (!isset($_SESSION['isClubModAdmin'])) {
    $_SESSION['isClubModAdmin'] = ($xoopsUser) ? $xoopsUser->isAdmin($xoopsModule->mid()) : false;
}

//有「管理社團」權限的用戶
if (!isset($_SESSION['isclubAdmin'])) {
    $_SESSION['isclubAdmin'] = ($xoopsUser) ? power_chk("", 2) : false;
}

//有「新增社團」權限的用戶
if (!isset($_SESSION['isclubUser'])) {
    $_SESSION['isclubUser'] = ($xoopsUser) ? power_chk("", 1) : false;
}

//工具列設定

//回模組首頁

$interface_menu[_MD_KWCLUB_INDEX_MYCLASS] = "index.php?op=myclass";
$interface_icon[_MD_KWCLUB_INDEX_MYCLASS] = "fa-chevron-right";

$interface_menu[_MD_KWCLUB_INDEX_TEACHER] = "index.php?op=teacher";
$interface_icon[_MD_KWCLUB_INDEX_TEACHER] = "fa-chevron-right";

if ($_SESSION['isclubUser']) {
    $interface_menu[_MD_KWCLUB_INDEX_FORM] = "main.php?op=class_form";
    $interface_icon[_MD_KWCLUB_INDEX_FORM] = "fa-chevron-right";

    $interface_menu[_MD_KWCLUB_CATE] = "cate.php?type=cate&op=cate_form";
    $interface_icon[_MD_KWCLUB_CATE] = "fa-chevron-right";
}

//模組後台
if ($_SESSION['isclubAdmin']) {
    $interface_menu[_MD_KWCLUB_REG] = "register.php";
    $interface_icon[_MD_KWCLUB_REG] = "fa-chevron-right";

    $interface_menu[_MD_KWCLUB_ADMIN] = "config.php";
    $interface_icon[_MD_KWCLUB_ADMIN] = "fa-chevron-right";

    $interface_menu[_MD_KWCLUB_STATISTICS] = "statistics.php";
    $interface_icon[_MD_KWCLUB_STATISTICS] = "fa-chevron-right";
}

if ($_SESSION['isClubModAdmin']) {
    $interface_menu[_TAD_TO_ADMIN] = "admin/main.php";
    $interface_icon[_TAD_TO_ADMIN] = "fa-chevron-right";
}

get_club_info();
