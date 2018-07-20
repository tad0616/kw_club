<?php

include_once "header.php";
if (!$_SESSION['isclubAdmin']) {
    redirect_header("index.php", 3, _MD_KWCLUB_FORBBIDEN);
}
$xoopsOption['template_main'] = "kw_club_config.tpl";
include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op      = system_CleanVars($_REQUEST, 'op', '', 'string');
$club_id = system_CleanVars($_REQUEST, 'club_id', '', 'int');

switch ($op) {

    //更新資料
    case "set_config":
        set_config();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case "update_config":
        update_config();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case "reset_config":
        reset_config();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case "insert_kw_club_info":
        $club_id = insert_kw_club_info();
        header("location: {$_SERVER['PHP_SELF']}?club_id=$club_id");
        exit;

    //更新資料
    case "update_kw_club_info":
        update_kw_club_info($club_id);
        header("location: {$_SERVER['PHP_SELF']}?club_id=$club_id");
        exit;

    //更新資料
    case "kw_club_info_form":
        kw_club_info_form($club_id);
        break;

    default:
        kw_club_info_list();
        $op = 'kw_club_info_list';
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('op', $op);
$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
include_once XOOPS_ROOT_PATH . '/footer.php';

function set_config()
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;

    $uid = $xoopsUser->uid();

    $myts = MyTextSanitizer::getInstance();

    $club_year       = (int) $_POST['club_year'];
    $club_start_date = $myts->addSlashes($_POST['club_start_date']);
    $club_end_date   = $myts->addSlashes($_POST['club_end_date']);
    $club_isfree     = (int) $_POST['club_isfree'];
    $club_backup_num = (int) $_POST['club_backup_num'];

    $sql    = "select `club_year` from `" . $xoopsDB->prefix('kw_club_info') . "` where `club_year` = {$club_year}";
    $result = $xoopsDB->query($sql) or web_error($sql);

    //check club_year isreset
    if (list($club_year) = $xoopsDB->fetchRow($result)) {
        $sql = "update  `" . $xoopsDB->prefix('kw_club_info') . "` set " . "
        `club_start_date`  =  '{$club_start_date}',
        `club_end_date` =  '{$club_end_date}',
        `club_isfree` = '{$club_isfree}',
        `club_backup_num` =  '{$club_backup_num}',
        `club_uid` = '{$uid}',
        `club_datetime` = NOW(),
        `club_enable` = '1'
        where `club_year`='{$club_year}'";
        $xoopsDB->queryF($sql) or web_error($sql);
    } else {
        //new
        $sql = "insert into `" . $xoopsDB->prefix('kw_club_info') . "` (
            `club_year`,
            `club_start_date`,
            `club_end_date`,
            `club_isfree`,
            `club_backup_num`,
            `club_uid`,
            `club_datetime`,
            `club_enable`
            ) values(
            '{$club_year}',
            '{$club_start_date}',
            '{$club_end_date}',
            '{$club_isfree}',
            '{$club_backup_num}',
            '{$uid}',
            NOW(),
            '1'
            )";
        $xoopsDB->query($sql) or web_error($sql);
    }
    //json
    $kw_club = array('club_year' => $club_year, 'club_start_date' => $club_start_date, 'club_end_date' => $club_end_date, 'club_isfree' => $club_isfree, 'club_backup_num' => $club_backup_num);
    $json    = json_encode($kw_club, JSON_UNESCAPED_UNICODE);
    file_put_contents(XOOPS_ROOT_PATH . "/uploads/kw_club/kw_club_config.json", $json);

    //設定相關變數
    $_SESSION['club_year']       = $club_year;
    $_SESSION['club_start_date'] = $club_start_date;
    $_SESSION['club_end_date']   = $club_end_date;
    $_SESSION['club_isfree']     = $club_isfree;
    $_SESSION['club_backup_num'] = $club_backup_num;

}

function update_config()
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;

    //update json
    $json    = file_get_contents(XOOPS_URL . "/uploads/kw_club/kw_club_config.json");
    $kw_club = json_decode($json, true);

    $myts = MyTextSanitizer::getInstance();

    $club_start_date = $myts->addSlashes($_POST['club_start_date']);
    $club_end_date   = $myts->addSlashes($_POST['club_end_date']);
    $club_backup_num = (int) $_POST['club_backup_num'];

    $kw_club = array('club_year' => $kw_club['club_year'], 'club_start_date' => $_POST['club_start_date'], 'club_end_date' => $_POST['club_end_date'], 'club_isfree' => $_POST['club_isfree'], 'club_backup_num' => $club_backup_num);
    $json    = json_encode($kw_club, JSON_UNESCAPED_UNICODE);
    file_put_contents(XOOPS_ROOT_PATH . "/uploads/kw_club/kw_club_config.json", $json);

    //update db
    $uid = $xoopsUser->uid();
    $sql = "update  `" . $xoopsDB->prefix('kw_club_info') . "` set " . "
    `club_start_date`  =  '{$club_start_date}',
    `club_end_date` =  '{$club_end_date}',
    `club_uid` = '{$uid}',
    `club_backup_num` = '{$club_backup_num}',
    `club_datetime` = NOW()";
    $xoopsDB->queryF($sql) or web_error($sql);

    //update session
    $_SESSION['club_start_date'] = $club_start_date;
    $_SESSION['club_end_date']   = $club_end_date;

}

//重設期別
function reset_config()
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;

    //已設定
    if (file_exists(XOOPS_ROOT_PATH . "/uploads/kw_club/kw_club_config.json")) {
        $json    = file_get_contents(XOOPS_URL . "/uploads/kw_club/kw_club_config.json");
        $kw_club = json_decode($json, true);
        foreach ($kw_club as $key => $value) {
            $$key = $value;
        }

        $uid = $xoopsUser->uid();

        $sql = "update  `" . $xoopsDB->prefix('kw_club_info') . "` set " . "
        `club_enable`  =  '0',
        `club_uid` = '{$uid}',
        `club_datetime` = NOW()
        where `club_year`='{$club_year}'";
        $xoopsDB->queryF($sql) or web_error($sql);

        unlink(XOOPS_ROOT_PATH . "/uploads/kw_club/kw_club_config.json");
    }
}

//列出所有kw_club_info資料
function kw_club_info_list()
{
    global $xoopsDB, $xoopsTpl;

    $myts = MyTextSanitizer::getInstance();

    $sql = "select * from `" . $xoopsDB->prefix("kw_club_info") . "` order by club_year desc";

    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = getPageBar($sql, 20, 10);
    $bar     = $PageBar['bar'];
    $sql     = $PageBar['sql'];
    $total   = $PageBar['total'];

    $result = $xoopsDB->query($sql) or web_error($sql);

    $all_kw_club_info = array();
    $i                = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        $all_kw_club_info[$i] = $all;
        //以下會產生這些變數： $club_id, $club_year, $club_start_date, $club_end_date, $club_isfree, $club_backup_num, $club_uid, $club_datetime, $club_enable
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //將是/否選項轉換為圖示
        $club_isfree_pic = $club_isfree == 1 ? '<img src="' . XOOPS_URL . '/modules/kw_club/images/yes.gif" alt="' . _YES . '" title="' . _YES . '">' : '<img src="' . XOOPS_URL . '/modules/kw_club/images/no.gif" alt="' . _NO . '" title="' . _NO . '">';

        //將 uid 編號轉換成使用者姓名（或帳號）
        $uid_name = XoopsUser::getUnameFromId($club_uid, 1);
        if (empty($uid_name)) {
            $uid_name = XoopsUser::getUnameFromId($club_uid, 0);
        }

        //將是/否選項轉換為圖示
        $club_enable_pic = $club_enable == 1 ? '<img src="' . XOOPS_URL . '/modules/kw_club/images/yes.gif" alt="' . _YES . '" title="' . _YES . '">' : '<img src="' . XOOPS_URL . '/modules/kw_club/images/no.gif" alt="' . _NO . '" title="' . _NO . '">';

        //將是/否選項轉換為圖示
        $club_isfree_text = $club_isfree == 1 ? '自由報名' : '登入報名';

        //過濾讀出的變數值
        $club_year       = $myts->htmlSpecialChars($club_year);
        $club_start_date = $myts->htmlSpecialChars($club_start_date);
        $club_end_date   = $myts->htmlSpecialChars($club_end_date);
        $club_backup_num = $myts->htmlSpecialChars($club_backup_num);

        $all_kw_club_info[$i]['club_id']          = $club_id;
        $all_kw_club_info[$i]['club_year']        = $club_year;
        $all_kw_club_info[$i]['club_start_date']  = $club_start_date;
        $all_kw_club_info[$i]['club_end_date']    = $club_end_date;
        $all_kw_club_info[$i]['club_isfree']      = $club_isfree;
        $all_kw_club_info[$i]['club_isfree_text'] = $club_isfree_text;
        $all_kw_club_info[$i]['club_backup_num']  = $club_backup_num;
        $all_kw_club_info[$i]['club_uid']         = $club_uid;
        $all_kw_club_info[$i]['club_uid_name']    = $uid_name;
        $all_kw_club_info[$i]['club_datetime']    = $club_datetime;
        $all_kw_club_info[$i]['club_enable']      = $club_enable;
        $all_kw_club_info[$i]['club_enable_pic']  = $club_enable_pic;
        $i++;
    }

    //刪除確認的JS
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert_obj          = new sweet_alert();
    $delete_kw_club_info_func = $sweet_alert_obj->render('delete_kw_club_info_func',
        "{$_SERVER['PHP_SELF']}?op=delete_kw_club_info&club_id=", "club_id");
    $xoopsTpl->assign('delete_kw_club_info_func', $delete_kw_club_info_func);

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('all_kw_club_info', $all_kw_club_info);

}

//kw_club_info編輯表單
function kw_club_info_form($club_id = '')
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;

    //抓取預設值
    if (!empty($club_id)) {
        $DBV = get_kw_club_info($club_id);
    } else {
        $DBV = array();
    }

    //預設值設定

    //設定 club_id 欄位的預設值
    $club_id = !isset($DBV['club_id']) ? $club_id : $DBV['club_id'];
    $xoopsTpl->assign('club_id', $club_id);
    //設定 club_year 欄位的預設值
    $club_year = !isset($DBV['club_year']) ? '' : $DBV['club_year'];
    $xoopsTpl->assign('club_year', $club_year);
    //設定 club_start_date 欄位的預設值
    $club_start_date = !isset($DBV['club_start_date']) ? date("Y-m-d 09:00") : $DBV['club_start_date'];
    $xoopsTpl->assign('club_start_date', $club_start_date);
    //設定 club_end_date 欄位的預設值
    $club_end_date = !isset($DBV['club_end_date']) ? date("Y-m-d 05:30") : $DBV['club_end_date'];
    $xoopsTpl->assign('club_end_date', $club_end_date);
    //設定 club_isfree 欄位的預設值
    $club_isfree = !isset($DBV['club_isfree']) ? '' : $DBV['club_isfree'];
    $xoopsTpl->assign('club_isfree', $club_isfree);
    //設定 club_backup_num 欄位的預設值
    $club_backup_num = !isset($DBV['club_backup_num']) ? '' : $DBV['club_backup_num'];
    $xoopsTpl->assign('club_backup_num', $club_backup_num);
    //設定 club_uid 欄位的預設值
    $user_uid = $xoopsUser ? $xoopsUser->uid() : "";
    $club_uid = !isset($DBV['club_uid']) ? $user_uid : $DBV['club_uid'];
    $xoopsTpl->assign('club_uid', $club_uid);
    //設定 club_datetime 欄位的預設值
    $club_datetime = !isset($DBV['club_datetime']) ? date("Y-m-d H:i:s") : $DBV['club_datetime'];
    $xoopsTpl->assign('club_datetime', $club_datetime);
    //設定 club_enable 欄位的預設值
    $club_enable = !isset($DBV['club_enable']) ? '' : $DBV['club_enable'];
    $xoopsTpl->assign('club_enable', $club_enable);

    $op = empty($club_id) ? "insert_kw_club_info" : "update_kw_club_info";
    //$op = "replace_kw_club_info";

    //套用formValidator驗證機制
    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _TAD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator = new formValidator("#myForm", true);
    $formValidator->render();

    //加入Token安全機制
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    $token      = new XoopsFormHiddenToken();
    $token_form = $token->render();
    $xoopsTpl->assign("token_form", $token_form);
    $xoopsTpl->assign('action', $_SERVER["PHP_SELF"]);
    $xoopsTpl->assign('next_op', $op);

    //引入日期
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/cal.php";
    $cal = new My97DatePicker();
    $cal->render();

    $arr_num = [];
    for ($i = 0; $i <= 10; $i++) {
        $arr_num[$i] = $i;
    }
    $xoopsTpl->assign('arr_num', $arr_num);

    $arr_semester = get_semester();
    $xoopsTpl->assign('arr_semester', $arr_semester);

}

//新增資料到kw_club_info中
function insert_kw_club_info()
{
    global $xoopsDB, $xoopsUser;

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $myts = MyTextSanitizer::getInstance();

    $club_id         = intval($_POST['club_id']);
    $club_year       = $myts->addSlashes($_POST['club_year']);
    $club_start_date = $myts->addSlashes($_POST['club_start_date']);
    $club_end_date   = $myts->addSlashes($_POST['club_end_date']);
    $club_isfree     = intval($_POST['club_isfree']);
    $club_backup_num = $myts->addSlashes($_POST['club_backup_num']);
    //取得使用者編號
    $club_uid      = ($xoopsUser) ? $xoopsUser->uid() : "";
    $club_uid      = !empty($_POST['club_uid']) ? intval($_POST['club_uid']) : $club_uid;
    $club_datetime = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));
    $club_enable   = intval($_POST['club_enable']);

    $sql = "insert into `" . $xoopsDB->prefix("kw_club_info") . "` (
        `club_year`,
        `club_start_date`,
        `club_end_date`,
        `club_isfree`,
        `club_backup_num`,
        `club_uid`,
        `club_datetime`,
        `club_enable`
    ) values(
        '{$club_year}',
        '{$club_start_date}',
        '{$club_end_date}',
        '{$club_isfree}',
        '{$club_backup_num}',
        '{$club_uid}',
        '{$club_datetime}',
        '{$club_enable}'
    )";
    $xoopsDB->query($sql) or web_error($sql);

    //取得最後新增資料的流水編號
    $club_id = $xoopsDB->getInsertId();

    return $club_id;
}

//更新kw_club_info某一筆資料
function update_kw_club_info($club_id = '')
{
    global $xoopsDB, $xoopsUser;

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $myts = MyTextSanitizer::getInstance();

    $club_id         = intval($_POST['club_id']);
    $club_year       = $myts->addSlashes($_POST['club_year']);
    $club_start_date = $myts->addSlashes($_POST['club_start_date']);
    $club_end_date   = $myts->addSlashes($_POST['club_end_date']);
    $club_isfree     = intval($_POST['club_isfree']);
    $club_backup_num = $myts->addSlashes($_POST['club_backup_num']);
    //取得使用者編號
    $club_uid      = ($xoopsUser) ? $xoopsUser->uid() : "";
    $club_uid      = !empty($_POST['club_uid']) ? intval($_POST['club_uid']) : $club_uid;
    $club_datetime = date("Y-m-d H:i:s", xoops_getUserTimestamp(time()));
    $club_enable   = intval($_POST['club_enable']);

    $sql = "update `" . $xoopsDB->prefix("kw_club_info") . "` set
    `club_year` = '{$club_year}',
    `club_start_date` = '{$club_start_date}',
    `club_end_date` = '{$club_end_date}',
    `club_isfree` = '{$club_isfree}',
    `club_backup_num` = '{$club_backup_num}',
    `club_uid` = '{$club_uid}',
    `club_datetime` = '{$club_datetime}',
    `club_enable` = '{$club_enable}'
    where `club_id` = '$club_id'";
    $xoopsDB->queryF($sql) or web_error($sql);

    return $club_id;
}

//刪除kw_club_info某筆資料資料
function delete_kw_club_info($club_id = '')
{
    global $xoopsDB;

    if (empty($club_id)) {
        return;
    }

    $sql = "delete from `" . $xoopsDB->prefix("kw_club_info") . "`
    where `club_id` = '{$club_id}'";
    $xoopsDB->queryF($sql) or web_error($sql);

}

//以流水號取得某筆kw_club_info資料
function get_kw_club_info($club_id = '')
{
    global $xoopsDB;

    if (empty($club_id)) {
        return;
    }

    $sql = "select * from `" . $xoopsDB->prefix("kw_club_info") . "`
    where `club_id` = '{$club_id}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}
