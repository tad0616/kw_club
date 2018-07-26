<?php

$xoopsOption['template_main'] = "kw_club_adm_main.tpl";
include_once "header.php";
include_once "../function.php";

/*-----------執行動作判斷區----------*/
// include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
// include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
// $op = system_CleanVars($_REQUEST, 'op', '', 'string');

// //權限項目陣列（編號超級重要！設定後，以後切勿隨便亂改。）
// $item_list = array(
//     '1' => _MA_KWCLUB_ADD_CLUB,
//     '2' => _MA_KWCLUB_ADM_CLUB,
// );

// $mid       = $xoopsModule->mid();
// $perm_name = $xoopsModule->dirname();
// $formi     = new XoopsGroupPermForm('細部權限設定', $mid, $perm_name, '請勾選欲開放給群組使用的權限：<br>');
// foreach ($item_list as $item_id => $item_name) {
//     $formi->addItem($item_id, $item_name);
// }
// echo $formi->render();

$xoTheme->addStylesheet(XOOPS_URL . '/modules/tadtools/css/xoops_adm3.css');
include_once 'footer.php';
