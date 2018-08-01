<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
if (!$_SESSION['isclubAdmin']) {
    redirect_header("index.php", 3, _MD_KWCLUB_FORBBIDEN);
}
$xoopsOption['template_main'] = "kw_club_register.tpl";
include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op        = system_CleanVars($_REQUEST, 'op', '', 'string');
$class_id  = system_CleanVars($_REQUEST, 'class_id', '', 'int');
$reg_sn    = system_CleanVars($_REQUEST, 'reg_sn', '', 'int');
$review    = system_CleanVars($_REQUEST, 'review', 'reg_sn', 'string');
$club_year = system_CleanVars($_REQUEST, 'club_year', $_SESSION['club_year'], 'int');
$reg_isfee = system_CleanVars($_REQUEST, 'reg_isfee', '', 'int');

switch ($op) {

    //更新資料
    case "update_reg":
        update_reg($reg_sn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case "delete_reg":
        delete_reg();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case "reg_uid":
        reg_uid($club_year);
        break;

    case "update_reg_isfee":
        update_reg_isfee($reg_sn, $reg_isfee);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    default:
        reg_list($club_year, $review);
        $op = 'reg_list';
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('op', $op);
$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
$xoTheme->addStylesheet(XOOPS_URL . '/modules/kw_club/css/module.css');
include_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------功能函數區--------------*/
//列出所有kw_club_reg資料
function reg_list($club_year = '', $review = 'reg_sn')
{
    global $xoopsDB, $xoopsTpl, $xoopsModuleConfig;

    //檢查是否設定期別
    if (empty($club_year)) {
        redirect_header('index.php', 3, '錯誤！未指定社團期數');
    }
    $xoopsTpl->assign('club_year', $club_year);
    $xoopsTpl->assign('club_year_text', club_year_to_text($club_year));

    //取得社團期別陣列
    $xoopsTpl->assign('arr_year', get_all_year());

    $xoopsTpl->assign('review', $review);

    $order = ($review == 'grade') ? 'ORDER BY a.`reg_grade`, a.`reg_class`' : 'ORDER BY a.`reg_grade` DESC';

    //取得報名資料
    $all_reg = get_class_reg($club_year, '', $order, true);

    $xoopsTpl->assign('all_reg', $all_reg);
    $xoopsTpl->assign('today', date("Y-m-d"));

}

//列出繳費模式
function reg_uid($club_year = "")
{
    global $xoopsDB, $xoopsTpl;

    //檢查是否設定期別
    if (empty($club_year)) {
        redirect_header($_SERVER['PHP_SELF'], 3, '錯誤！未指定社團期數');
    }

    $xoopsTpl->assign('club_year', $club_year);
    $xoopsTpl->assign('club_year_text', club_year_to_text($club_year));

    //取得社團期別陣列
    $xoopsTpl->assign('arr_year', get_all_year());

    $reg_all = get_reg_uid_all($club_year);
    // die(var_dump($reg_all));
    $xoopsTpl->assign('reg_all', $reg_all);
    $xoopsTpl->assign('total', sizeof($reg_all));

}

//更新kw_club_reg某一筆資料
function update_reg($reg_sn = '')
{
    global $xoopsDB, $xoopsUser;

    //XOOPS表單安全檢查
    // if (!$GLOBALS['xoopsSecurity']->check()) {
    //     $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
    //     redirect_header($_SERVER['PHP_SELF'], 3, $error);
    // }

    $myts = MyTextSanitizer::getInstance();

    $reg_sn      = (int) $_POST['reg_sn'];
    $club_year   = (int) $_POST['club_year'];
    $class_id    = (int) $_POST['class_id'];
    $class_title = $myts->addSlashes($_POST['class_title']);
    $reg_uid     = $myts->addSlashes($_POST['reg_uid']);
    $reg_name    = $myts->addSlashes($_POST['reg_name']);
    $reg_grade   = $myts->addSlashes($_POST['reg_grade']);
    $reg_class   = $myts->addSlashes($_POST['reg_class']);
    $reg_isreg   = $myts->addSlashes($_POST['reg_isreg']);
    $reg_isfee   = (int) $_POST['reg_isfee'];

    $sql = "update `" . $xoopsDB->prefix("kw_club_reg") . "` set
    `class_id` = '{$class_id}',
    `reg_uid` = '{$reg_uid}',
    `reg_name` = '{$reg_name}',
    `reg_grade` = '{$reg_grade}',
    `reg_class` = '{$reg_class}',
    `reg_isreg` = '{$reg_isreg}',
    `reg_isfee` = '{$reg_isfee}'
    where `reg_sn` = '$reg_sn'";
    $xoopsDB->queryF($sql) or web_error($sql);

}

//改變繳費狀態
function update_reg_isfee($reg_sn, $reg_isfee)
{
    global $xoopsDB;

    $sql = "update `" . $xoopsDB->prefix("kw_club_reg") . "` set
    `reg_isfee` = '{$reg_isfee}'
    where `reg_sn` = '$reg_sn'";
    $xoopsDB->queryF($sql) or web_error($sql);
}
