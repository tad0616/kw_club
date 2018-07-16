<?php

include_once "header.php";
if (!$_SESSION['isclubAdmin']) {
    redirect_header("index.php", 3, _MD_KWCLUB_FORBBIDEN);
}
$xoopsOption['template_main'] = "kw_club_config.tpl";
include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');

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

    default:
        club_form();
        $op = 'club_form';
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('op', $op);
$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
include_once XOOPS_ROOT_PATH . '/footer.php';

function club_form()
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;

    //引入日期
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/cal.php";
    $cal = new My97DatePicker();
    $cal->render();

    //尚未設定社團期別
    if (!file_exists(XOOPS_ROOT_PATH . "/uploads/kw_club/kw_club_config.json")) {
        $arr_semester = get_semester();
        $xoopsTpl->assign('arr_semester', $arr_semester);
    } else {
        //已設定
        $json    = file_get_contents(XOOPS_URL . "/uploads/kw_club/kw_club_config.json");
        $kw_club = json_decode($json, true);
        foreach ($kw_club as $key => $value) {
            $xoopsTpl->assign($key, $value);
        }

        //刪除確認的JS
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert_obj  = new sweet_alert();
        $delete_club_func = $sweet_alert_obj->render('delete_club_func', "{$_SERVER['PHP_SELF']}?op=reset_config&id=", 'id');
        $xoopsTpl->assign('delete_club_func', $delete_club_func);

    }

    $arr_num = [];
    for ($i = 0; $i <= 10; $i++) {
        $arr_num[$i] = $i;
    }
    $xoopsTpl->assign('arr_num', $arr_num);

    //套用formValidator驗證機制
    if (!file_exists(TADTOOLS_PATH . "/formValidator.php")) {
        redirect_header("index.php", 3, _TAD_NEED_TADTOOLS);
    }
    include_once TADTOOLS_PATH . "/formValidator.php";
    $formValidator = new formValidator("#classform", true);
    $formValidator->render();

}

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
    $_SESSION['club_isfree']  = $club_isfree;
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
