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
        redirect_header($_SERVER['PHP_SELF'], 3, '錯誤！未指定社團期數');
    }

    $xoopsTpl->assign('club_year', $club_year);
    $xoopsTpl->assign('club_year_text', club_year_to_text($club_year));

    //取得社團期別陣列
    $xoopsTpl->assign('arr_year', get_all_year());

    $xoopsTpl->assign('review', $review);

    $myts  = MyTextSanitizer::getInstance();
    $order = ($review == 'grade') ? 'ORDER BY a.`reg_grade`, a.`reg_class`' : 'ORDER BY a.`reg_grade` DESC';

    $sql = "select a.*,b.* from `" . $xoopsDB->prefix("kw_club_reg") . "` as a
    join `" . $xoopsDB->prefix("kw_club_class") . "` as b on a.`class_id` = b.`class_id`
    where b.`club_year`={$club_year} {$order}";
    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = getPageBar($sql, 20, 10);
    $bar     = $PageBar['bar'];
    $sql     = $PageBar['sql'];
    $total   = $PageBar['total'];
    $result  = $xoopsDB->query($sql) or web_error($sql);

    //取得社團所有資料陣列
    $class_arr = get_class_all();

    $all_reg = array();
    while ($all = $xoopsDB->fetchArray($result)) {

        //將是/否選項轉換為圖示
        $all['reg_isfee_pic'] = $all['reg_isfee'] == 1 ? '<img src="' . XOOPS_URL . '/modules/kw_club/images/yes.gif" alt="' . _YES . '" title="' . _YES . '">' : '<img src="' . XOOPS_URL . '/modules/kw_club/images/no.gif" alt="' . _NO . '" title="' . _NO . '">';
        $all['class_pay']     = $all['class_money'] + $all['class_fee'];

        $all_reg[] = $all;
    }

    //刪除確認的JS
    {
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
    }

    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert_obj = new sweet_alert();
    $sweet_alert_obj->render('delete_reg_func',
        "{$_SERVER['PHP_SELF']}?op=delete_reg&reg_sn=", "reg_sn");

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('total', $total);
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

    $reg_uid_all = get_reg_uid_all($club_year);
    // $json        = json_encode($reg_uid_all, JSON_UNESCAPED_UNICODE);
    // die($json);
    $money_all = $in_money_all = $un_money_all = $reg_name_all = $arr_reg = array();

    foreach ($reg_uid_all as $value) {
        $sql = "select a.* from `" . $xoopsDB->prefix("kw_club_reg") . "`  as a
        join `" . $xoopsDB->prefix("kw_club_class") . "` as b on a.`class_id` = b.`class_id`
        where a.`reg_uid` = '{$value}'  and b.`club_year`={$club_year}";

        $result   = $xoopsDB->query($sql) or web_error($sql);
        $i        = 0;
        $money    = 0;
        $in_money = 0;
        $un_money = 0;
        while ($arr = $xoopsDB->fetchArray($result)) {

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
