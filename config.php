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
$type    = system_CleanVars($_REQUEST, 'type', '', 'string');
$cate_id = system_CleanVars($_REQUEST, 'cate_id', '', 'int');

switch ($op) {

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

    //新增資料
    case "insert_cate":
        insert_cate($type);
        header("location: {$_SERVER['PHP_SELF']}?type=$type&op=cate_form");
        exit;

    //更新資料
    case "update_cate":
        update_cate($type, $cate_id);
        header("location: {$_SERVER['PHP_SELF']}?type=$type&op=cate_form");
        exit;

    case "delete_cate":
        delete_cate($type, $cate_id);
        header("location: {$_SERVER['PHP_SELF']}?type=$type&op=cate_form");
        exit;

    case "cate_form":
        cate_form($type, $cate_id);
        break;

    default:
        kw_club_info_list();
        cate_list('cate');
        cate_list('place');
        $op = 'kw_club_info_list';
        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('op', $op);
$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
include_once XOOPS_ROOT_PATH . '/footer.php';

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

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/easy_responsive_tabs.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/easy_responsive_tabs.php";
    $responsive_tabs = new easy_responsive_tabs('#setupTab');
    $responsive_tabs->rander();
}

//kw_club_info編輯表單
function kw_club_info_form($club_id = '')
{
    global $xoopsDB, $xoopsTpl, $xoopsUser, $semester_name_arr;

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
    if ($club_year) {
        $year = substr($club_year, 0, 3);
        $st   = substr($club_year, -2);
        $xoopsTpl->assign('club_year_txt', $year . " " . $semester_name_arr[$st]);
    }
    //設定 club_start_date 欄位的預設值
    $club_start_date = !isset($DBV['club_start_date']) ? date("Y-m-d 08:00") : $DBV['club_start_date'];
    $xoopsTpl->assign('club_start_date', $club_start_date);
    //設定 club_end_date 欄位的預設值
    $club_end_date = !isset($DBV['club_end_date']) ? date("Y-m-d 17:30") : $DBV['club_end_date'];
    $xoopsTpl->assign('club_end_date', $club_end_date);
    //設定 club_isfree 欄位的預設值
    $club_isfree = !isset($DBV['club_isfree']) ? 0 : $DBV['club_isfree'];
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
    $club_enable = !isset($DBV['club_enable']) ? 1 : $DBV['club_enable'];
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

    //設定相關變數
    $_SESSION['club_year']       = $club_year;
    $_SESSION['club_start_date'] = $club_start_date;
    $_SESSION['club_end_date']   = $club_end_date;
    $_SESSION['club_isfree']     = $club_isfree;
    $_SESSION['club_backup_num'] = $club_backup_num;

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

    //update session
    $_SESSION['club_year']       = $club_year;
    $_SESSION['club_start_date'] = $club_start_date;
    $_SESSION['club_end_date']   = $club_end_date;
    $_SESSION['club_isfree']     = $club_isfree;
    $_SESSION['club_backup_num'] = $club_backup_num;

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

//kw_club_cate編輯表單
function cate_form($type, $cate_id = '')
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;

    //抓取預設值
    if (!empty($cate_id)) {
        $DBV = get_cate($cate_id, $type);

    } else {
        $DBV = array();
    }

    //預設值設定

    //設定 cate_id 欄位的預設值
    $cate_id = !isset($DBV[$type . '_id']) ? "" : $DBV[$type . '_id'];
    $xoopsTpl->assign('cate_id', $cate_id);
    //設定 cate_title 欄位的預設值
    $cate_title = !isset($DBV[$type . '_title']) ? "" : $DBV[$type . '_title'];
    $xoopsTpl->assign('cate_title', $cate_title);
    //設定 cate_desc 欄位的預設值
    $cate_desc = !isset($DBV[$type . '_desc']) ? "" : $DBV[$type . '_desc'];
    $xoopsTpl->assign('cate_desc', $cate_desc);
    //設定 cate_sort 欄位的預設值
    $cate_sort = !isset($DBV[$type . '_sort']) ? "" : $DBV[$type . '_sort'];
    $xoopsTpl->assign('cate_sort', $cate_sort);
    //設定 cate_enable 欄位的預設值
    $cate_enable = !isset($DBV[$type . '_enable']) ? "" : $DBV[$type . '_enable'];
    $xoopsTpl->assign('cate_enable', $cate_enable);

    $op     = empty($cate_id) ? "insert_cate" : "update_cate";
    $enable = empty($cate_enable) ? "1" : $cate_enable;
    //$op="replace_kw_club_cate";

    $span = $_SESSION['bootstrap'] == '3' ? 'form-control col-sm-' : 'span';
    $form = new XoopsThemeForm('', 'form', $_SERVER['PHP_SELF'], 'post', true);
    $form->setExtra('enctype = "multipart/form-data"');

    //類型標題
    $cate_titleText = new XoopsFormText(_MD_KWCLUB_CATE_TITLE, "cate_title", 30, 255, $cate_title);
    $cate_titleText->setExtra("class = '{$span}6'");
    $form->addElement($cate_titleText, true);

    //類型排序
    $cate_sortText = new XoopsFormText(_MD_KWCLUB_CATE_SORT, "cate_sort", 30, 255, $cate_sort);
    $cate_sortText->setExtra("class = '{$span}6'");
    $form->addElement($cate_sortText, true);

    //類型說明
    $cate_descText = new XoopsFormText(_MD_KWCLUB_CATE_DESC, "cate_desc", 30, 255, $cate_desc);
    $cate_descText->setExtra("class = '{$span}6'");
    $form->addElement($cate_descText, false);

    //是否啟用

    $cate_isopenRadio          = new XoopsFormRadio(_MD_KWCLUB_CATE_ENABLE, 'cate_enable', $enable);
    $options_array_isshow['1'] = '啟用';
    $options_array_isshow['0'] = '停用';
    $cate_isopenRadio->addOptionArray($options_array_isshow);
    $form->addElement($cate_isopenRadio, true);

    //hidden
    $form->addElement(new XoopsFormHidden("op", $op));
    $form->addElement(new XoopsFormHidden("type", $type));
    $form->addElement(new XoopsFormHidden("cate_id", $cate_id));
    $form->addElement(new XoopsFormHiddenToken());

    $SubmitTray = new XoopsFormElementTray('', '', '', true);
    $SubmitTray->addElement(new XoopsFormButton('', '', _TAD_SUBMIT, 'submit'));
    $form->addElement($SubmitTray);
    $xoopsform = $form->render();
    $xoopsTpl->assign('xoopsform', $xoopsform);

    //列表
    $myts = MyTextSanitizer::getInstance();
    $sql  = "select * from `" . $xoopsDB->prefix($table) . "` order by " . $type . "_sort";
    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = getPageBar($sql, 20, 10);
    $bar     = $PageBar['bar'];
    $sql     = $PageBar['sql'];
    $total   = $PageBar['total'];

    $result = $xoopsDB->query($sql) or web_error($sql);

    $all_cate_content = array();
    $i                = 0;
    while ($all = $result->fetch_row()) {
        $all_cate_content[$i] = $all;
        $i++;
    }

    //刪除確認的JS
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert_obj  = new sweet_alert();
    $delete_cate_func = $sweet_alert_obj->render('delete_cate_func', "{$_SERVER['PHP_SELF']}?type={$type}&op=delete_cate&cate_id=", "cate_id");
    $xoopsTpl->assign('delete_cate_func', $delete_cate_func);

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('action', "{$_SERVER['PHP_SELF']}");
    $xoopsTpl->assign('all_cate_content', $all_cate_content);

    $xoopsTpl->assign('op', 'cate_form');

}

//新增資料到kw_club_cate中
function insert_cate($type)
{
    global $xoopsDB, $xoopsTpl, $error;

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $myts = MyTextSanitizer::getInstance();

    $cate_id     = $_POST['cate_id'];
    $cate_title  = $myts->addSlashes($_POST['cate_title']);
    $cate_desc   = $myts->addSlashes($_POST['cate_desc']);
    $cate_sort   = $_POST['cate_sort'];
    $cate_enable = $_POST['cate_enable'];
    // $type        = $_POST['type'];

    $sql    = "select * from `" . $xoopsDB->prefix('kw_club_' . $type) . "` where `{$type}_sort`={$cate_sort}";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $num    = $result->num_rows;

    if ($num > 0) {
        $error = "排序的數字已存在，請輸入其他阿拉伯數字";
        // $xoopsTpl->assign('error', $error);

        header("location: {$_SERVER['PHP_SELF']}?type=$type&op=cate_form");
        exit;
    } else {

        $sql = "insert into `" . $xoopsDB->prefix('kw_club_' . $type) . "` (" .
            "`" . $type . "_title`, " .
            "`" . $type . "_desc`, " .
            "`" . $type . "_sort`, " .
            "`" . $type . "_enable` " .
            ") values(
        '{$cate_title}',
        '{$cate_desc}',
        '{$cate_sort}',
        '{$cate_enable}'
    )";
        $xoopsDB->query($sql) or web_error($sql);

        //取得最後新增資料的流水編號
        $cate_id = $xoopsDB->getInsertId();

        return $cate_id;
    }
}

//更新kw_club_cate某一筆資料
function update_cate($type, $cate_id = '')
{
    global $xoopsDB, $xoopsTpl, $error;

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $myts = MyTextSanitizer::getInstance();

    $cate_id     = $_POST['cate_id'];
    $cate_title  = $myts->addSlashes($_POST['cate_title']);
    $cate_desc   = $myts->addSlashes($_POST['cate_desc']);
    $cate_sort   = $_POST['cate_sort'];
    $cate_enable = $_POST['cate_enable'];

    // //check sort
    $sql    = "select * from `" . $xoopsDB->prefix('kw_club_' . $type) . "` where `{$type}_sort`={$cate_sort}";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $num    = $result->num_rows;

    if ($num > 0) {
        $error = "排序的數字已存在，請輸入其他阿拉伯數字";
        // $xoopsTpl->assign('error', $error);
        header("location: {$_SERVER['PHP_SELF']}?type=$type&op=cate_form");
        exit;
    } else {

        $sql = "update `" . $xoopsDB->prefix('kw_club_' . $type) . "` set" .
            "`" . $type . "_title` = '{$cate_title}'," .
            "`" . $type . "_desc` = '{$cate_desc}'," .
            "`" . $type . "_sort` = '{$cate_sort}'," .
            "`" . $type . "_enable` = '{$cate_enable}'
    where `" . $type . "_id` = '$cate_id'";
        $xoopsDB->queryF($sql) or web_error($sql);

        return $cate_id;
    }

}

//刪除kw_club_cate某筆資料資料
function delete_cate($type, $cate_id = '')
{
    global $xoopsDB;

    if (empty($cate_id)) {
        redirect_header("config.php", 3, '刪除錯誤!沒有id!');
    }

    $sql = "delete from `" . $xoopsDB->prefix('kw_club_' . $type) . "`
    where `" . $type . "_id` = '{$cate_id}'";
    $xoopsDB->queryF($sql) or web_error($sql);

}
