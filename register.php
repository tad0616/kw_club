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
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$class_id = system_CleanVars($_REQUEST, 'class_id', '', 'int');
$reg_sn   = system_CleanVars($_REQUEST, 'reg_sn', '', 'int');

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
        reg_uid();
        break;

    default:
        reg_list($reg_sn);
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
//列出繳費模式
function reg_uid()
{
    global $xoopsDB, $xoopsTpl;

    if (!$_SESSION['isclubAdmin']) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    $year = system_CleanVars($_REQUEST, 'year', '0', 'int');
    if (empty($year) && isset($_SESSION['isclubAdmin'])) {
        $year = $_SESSION['club_year'];
    } else {
        $year = $year;
    }
    $xoopsTpl->assign('year', $year);

    $arr_year = get_all_year();
    $xoopsTpl->assign('arr_year', $arr_year);

    $reg_uid_all = get_reg_uid_all($year);
    // $json        = json_encode($reg_uid_all, JSON_UNESCAPED_UNICODE);
    // die($json);
    $money_all    = [];
    $in_money_all = [];
    $un_money_all = [];
    $reg_name_all = [];
    $arr_reg      = [];
    foreach ($reg_uid_all as $value) {
        $sql = "select * from `" . $xoopsDB->prefix("kw_club_reg") . "` where `reg_uid` = '{$value}'  and `reg_year`={$year}";

        $result   = $xoopsDB->query($sql) or web_error($sql);
        $i        = 0;
        $money    = 0;
        $in_money = 0;
        $un_money = 0;
        while ($arr = $xoopsDB->fetchArray($result)) {

            // $class = get_club_class($arr['class_id']);
            // array_push($arr, $class['class_num'],
            //     $class['class_date_open'], $class['class_date_close'],
            //     $class['class_time_start'], $class['class_time_end'],
            //     $class['class_week'], $class['class_money'], $class['class_fee']);
            $arr_reg[$value][$i] = $arr;

            if ($arr['reg_isfee'] == '1') {
                $in_money += $arr['class_money'];
            } else {
                $un_money += $arr['class_money'];
            }
            $money += ($arr['class_money'] + $arr['class_fee']);

            if ($i == 0) {
                $reg_name = $arr['reg_name'];
            }
            $i++;
        }
        $in_money_all[$value] = $in_money;
        $un_money_all[$value] = $un_money;
        $money_all[$value]    = $money;
        $reg_name_all[$value] = $reg_name;
    }
    // $json = json_encode($arr_reg, JSON_UNESCAPED_UNICODE);
    // die($json);
    $xoopsTpl->assign('reg_name_all', $reg_name_all);
    $xoopsTpl->assign('money_all', $money_all);
    $xoopsTpl->assign('in_money_all', $in_money_all);
    $xoopsTpl->assign('un_money_all', $un_money_all);
    $xoopsTpl->assign('arr_reg', $arr_reg);

    $xoopsTpl->assign('reg_uid_all', $reg_uid_all);
    // $xoopsTpl->assign('op', 'reg_uid');

}

//更新kw_club_reg某一筆資料
function update_reg($reg_sn = '')
{
    global $xoopsDB, $xoopsUser;
    if (!$_SESSION['isclubAdmin']) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //XOOPS表單安全檢查
    // if (!$GLOBALS['xoopsSecurity']->check()) {
    //     $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
    //     redirect_header($_SERVER['PHP_SELF'], 3, $error);
    // }

    $myts = MyTextSanitizer::getInstance();

    $reg_sn      = $_POST['reg_sn'];
    $reg_year    = $_POST['reg_year'];
    $class_id    = $_POST['class_id'];
    $class_title = $myts->addSlashes($_POST['class_title']);
    $reg_uid     = $myts->addSlashes($_POST['reg_uid']);
    $reg_name    = $myts->addSlashes($_POST['reg_name']);
    $reg_grade   = $myts->addSlashes($_POST['reg_grade']);
    $reg_class   = $myts->addSlashes($_POST['reg_class']);
    $reg_isreg   = $_POST['reg_isreg'];
    $reg_isfee   = $_POST['reg_isfee'];

    $sql = "update `" . $xoopsDB->prefix("kw_club_reg") . "` set
       `reg_year` = '{$reg_year}',
       `class_id` = '{$class_id}',
       `class_title` = '{$class_title}',
       `reg_uid` = '{$reg_uid}',
       `reg_name` = '{$reg_name}',
       `reg_grade` = '{$reg_grade}',
       `reg_class` = '{$reg_class}',
       `reg_isreg` = '{$reg_isreg}',
       `reg_isfee` = '{$reg_isfee}'
    where `reg_sn` = '$reg_sn'";
    $xoopsDB->queryF($sql) or web_error($sql);

    // return $reg_sn;
}

//列出所有kw_club_reg資料
function reg_list($reg_sn = 0)
{
    global $xoopsDB, $xoopsTpl, $xoopsModuleConfig;

    $review = system_CleanVars($_REQUEST, 'review', '', 'string');
    $year   = system_CleanVars($_REQUEST, 'year', '0', 'int');
    // $review = 'reg_sn';

    //報名年度
    if (empty($year) && isset($_SESSION['club_year'])) {
        $reg_year = $_SESSION['club_year'];
    } else {
        $reg_year = $year;
    }
    if (empty($review)) {$review = 'reg_sn';}

    //取得社團期別
    $arr_year = get_all_year();
    $xoopsTpl->assign('arr_year', $arr_year);

    $xoopsTpl->assign('review', $review);
    $xoopsTpl->assign('year', $reg_year);

    if (!empty($reg_sn)) {
        $arr_class = get_class_all();
        $xoopsTpl->assign('arr_class', $arr_class);
    }

    $myts = MyTextSanitizer::getInstance();
    if ($review == 'grade') {
        $sql = "select * from `" . $xoopsDB->prefix("kw_club_reg") . "` where `reg_year`={$reg_year} ORDER BY `reg_grade`, `reg_class`";
    } else {
        $sql = "select * from `" . $xoopsDB->prefix("kw_club_reg") . "` where `reg_year`={$reg_year} ORDER BY {$review} DESC";
    }

    $result = $xoopsDB->query($sql) or web_error($sql);

    //取得社團所有資料陣列
    $class_arr = get_class_all();

    $all_content = [];
    $i           = 0;
    while ($all = $xoopsDB->fetchArray($result)) {

        $all_content[$i] = $all;
        $i++;
    }
    //判斷報名時間
    chk_time();

    //刪除確認的JS
    {
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
    }

    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert_obj         = new sweet_alert();
    $delete_kw_club_reg_func = $sweet_alert_obj->render('delete_reg_func',
        "{$_SERVER['PHP_SELF']}?op=delete_reg&reg_sn=", "reg_sn");
    $xoopsTpl->assign('delete_kw_club_reg_func', $delete_kw_club_reg_func);

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('total', $total);
    $xoopsTpl->assign('reg_sn', $reg_sn);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('all_content', $all_content);
    // $xoopsTpl->assign('op', 'reg_list');
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('today', $today);
    $xoopsTpl->assign('title', $title);
}
