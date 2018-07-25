<?php

//引入TadTools的函式庫
if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php")) {
    redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50", 3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php";

$semester_name_arr = array('00' => '暑假', '01' => '第一學期', '11' => '寒假', '02' => '第二學期');

//其他自訂的共同的函數

//從json中取得社團期別資料（會在header.php中讀取）
function get_club_info()
{
    global $xoopsDB, $xoopsTpl;

    if (!isset($_SESSION['club_year']) or empty($_SESSION['club_year'])) {
        // $sql = "select * from `" . $xoopsDB->prefix("kw_club_info") . "` where `club_enable`='1' and `club_start_date`< now() and `club_end_date` > now()";
        $sql       = "select * from `" . $xoopsDB->prefix("kw_club_info") . "` where `club_enable`='1'";
        $result    = $xoopsDB->query($sql) or web_error($sql);
        $club_info = $xoopsDB->fetchArray($result);

        $_SESSION['club_year']       = $club_info['club_year'];
        $_SESSION['club_start_date'] = $club_info['club_start_date'];
        $_SESSION['club_end_date']   = $club_info['club_end_date'];
        $_SESSION['club_isfree']     = $club_info['club_isfree'];
        $_SESSION['club_backup_num'] = $club_info['club_backup_num'];
    }
}

//以流水號取得某筆資料
function get_cate($cate_id, $type)
{
    global $xoopsDB;

    if (empty($cate_id) || empty($type)) {
        return;
    }

    $type_id = $type . "_id";
    $sql     = "select * from `" . $xoopsDB->prefix('kw_club_'.$type) . "`
    where `" . $type . "_id` = '{$cate_id}'";

    $result = $xoopsDB->query($sql) or web_error($sql);
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//取得所有報名者的uid
function get_reg_uid_all($reg_year)
{
    global $xoopsDB;
    if (empty($reg_year)) {
        return false;
    } else {
        // $year = $_SESSION['club_year'];
        $sql = "select `reg_uid` from `" . $xoopsDB->prefix("kw_club_reg") . "`  where `reg_year` = '{$reg_year}' ORDER BY `reg_grade`, `reg_class`";
        // echo $sql;
        $reg_uid = [];
        $result  = $xoopsDB->query($sql) or web_error($sql);
        while ($data = $xoopsDB->fetchRow($result)) {
            $uid = strtolower($data[0]);
            if (!in_array($uid, $reg_uid)) {
                array_push($reg_uid, $uid);
            }

        }
        return $reg_uid;
    }
}
//取得期別的所有社團編號
function get_class_num()
{
    global $xoopsDB;
    //確認期別
    if (!isset($_SESSION['club_year'])) {
        return false;
    } else {
        $year = $_SESSION['club_year'];
        $sql  = "select `class_num` from `" . $xoopsDB->prefix("kw_club_class") . "`  where `class_year` = '{$year}'";
        // echo $sql;
        $result = $xoopsDB->query($sql) or web_error($sql);
        while (list($class_num) = $xoopsDB->fetchRow($result)) {
            $data[] = $class_num;
        }

        //  die($data[0]);
        return $data;
    }

}

//以流水號取得某筆社團資料
function alter_class($class_id = '')
{
    global $xoopsDB;

    if (empty($class_id)) {
        return false;
        exit;
    }

    $sql    = "select * from `" . $xoopsDB->prefix("kw_club_class") . "`  where `class_id` = '{$class_id}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//以class_id取得多筆kw_club_reg資料(報名人數)
function check_class_reg($class_id = '')
{
    global $xoopsDB;

    if (empty($class_id)) {
        return;
    }
    $sql    = "select * from `" . $xoopsDB->prefix("kw_club_reg") . "`  where `class_id` = '{$class_id}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $data   = $xoopsDB->fetchArray($result);
    return true;
}

//以流水號取得某筆kw_club_reg報名資料
function get_reg($reg_sn = '')
{
    global $xoopsDB;

    if (empty($reg_sn)) {
        return;
    }

    $sql    = "select * from `" . $xoopsDB->prefix("kw_club_reg") . "`  where `reg_sn` = '{$reg_sn}'";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//取得所有社團類型陣列
function get_cate_all()
{
    global $xoopsDB;
    $sql      = "select * from `" . $xoopsDB->prefix("kw_club_cate") . "`";
    $result   = $xoopsDB->query($sql) or web_error($sql);
    $data_arr = '';
    while ($data = $xoopsDB->fetchArray($result)) {
        $cate_id            = $data['cate_id'];
        $data_arr[$cate_id] = $data;
    }
    return $data_arr;
}

//取得所有社團地點陣列
function get_place_all()
{
    global $xoopsDB;
    $sql      = "select * from `" . $xoopsDB->prefix("kw_club_place") . "`";
    $result   = $xoopsDB->query($sql) or web_error($sql);
    $data_arr = '';
    while ($data = $xoopsDB->fetchArray($result)) {
        $cate_id            = $data['place_id'];
        $data_arr[$cate_id] = $data;
    }
    return $data_arr;
}

//取得所有社團教師陣列
function get_teacher_all()
{
    global $xoopsDB;
    $sql      = "select * from `" . $xoopsDB->prefix("kw_club_teacher") . "`";
    $result   = $xoopsDB->query($sql) or web_error($sql);
    $data_arr = '';
    while ($data = $xoopsDB->fetchArray($result)) {
        $teacher_id            = $data['teacher_id'];
        $data_arr[$teacher_id] = $data;
    }
    return $data_arr;
}

//取得所有社團資料陣列
function get_class_all()
{
    global $xoopsDB;

    if (isset($_SESSION['club_year'])) {
        $year = $_SESSION['club_year'];

        $sql      = "select * from `" . $xoopsDB->prefix("kw_club_class") . "` where `class_year`= {$year}";
        $result   = $xoopsDB->query($sql) or web_error($sql);
        $data_arr = '';
        while ($data = $xoopsDB->fetchArray($result)) {
            $class_id            = $data['class_id'];
            $data_arr[$class_id] = $data;
        }
        return $data_arr;
    } else {
        $class_error = "目前尚未設定社團期別";
        return $class_error;
    }
}

//取得社團開課所有期別
function get_all_year()
{
    global $xoopsDB;
    $sql      = "select club_year from `" . $xoopsDB->prefix("kw_club_info") . "` order by `club_year` desc";
    $result   = $xoopsDB->query($sql) or web_error($sql);
    $arr_year = array();
    while (list($club_year) = $xoopsDB->fetchRow($result)) {
        $arr_year[] = (int) $club_year;
    }
    return $arr_year;
}

//取得學期
function get_semester()
{
    global $semester_name_arr, $xoopsDB;

    $sql    = "select club_year, club_start_date, club_end_date from `" . $xoopsDB->prefix("kw_club_info") . "` order by club_year desc";
    $result = $xoopsDB->query($sql) or web_error($sql);
    while (list($club_year, $club_start_date, $club_end_date) = $xoopsDB->fetchRow($result)) {
        $all_semester[$club_year] = substr($club_start_date, 0, 10) . '~' . substr($club_end_date, 0, 10);
    }

    //semester and year
    $arr_time  = getdate();
    $this_week = $arr_time['wday'];

    if ($arr_time['mon'] >= 1 && $arr_time['mon'] <= 4) // 2 3 4 (第二學期)
    {
        $this_semester = '02';
        $this_year     = $arr_time['year'] - 1912;

    } else if ($arr_time['mon'] > 7 && $arr_time['mon'] <= 11) //8-11 (第一學期)
    {
        $this_semester = '01';
        $this_year     = $arr_time['year'] - 1911;

    } else if ($arr_time['mon'] == 12) //12-01 (寒假)
    {
        $this_semester = '11';
        $this_year     = $arr_time['year'] - 1911;

    } else if ($arr_time['mon'] > 4 && $arr_time['mon'] <= 7) //5-7 (暑假)
    {
        $this_semester = '00';
        $this_year     = $arr_time['year'] - 1911;

    }

    $last_year = $this_year - 1;
    $next_year = $this_year + 1;
    // $summer_semester='00';
    // $first_semester='01';
    // $winter_semester='11';
    // $second_semester='02';

    foreach ($semester_name_arr as $k => $v) {
        $semester[$this_year . $k]['opt'] = $this_year . " " . $v;
        if (isset($all_semester[$this_year . $k])) {
            $semester[$this_year . $k]['opt'] .= " ({$all_semester[$this_year . $k]})";
            $semester[$this_year . $k]['disabled'] = true;
        }
    }
    foreach ($semester_name_arr as $k => $v) {
        $semester[$next_year . $k]['opt'] = $next_year . " " . $v;
        if (isset($all_semester[$next_year . $k])) {
            $semester[$next_year . $k]['opt'] .= " ({$all_semester[$next_year . $k]})";
            $semester[$next_year . $k]['disabled'] = true;
        }
    }

    return $semester;

}

//以流水號秀出某筆kw_club_class資料內容
function class_show($class_id = '')
{
    global $xoopsDB, $xoopsUser, $xoopsTpl, $today;

    if (empty($class_id)) {
        return;
    } else {
        $class_id = intval($class_id);
    }

    $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
    $xoopsTpl->assign('uid', $uid);

    $myts = MyTextSanitizer::getInstance();

    $sql = "select * from `" . $xoopsDB->prefix("kw_club_class") . "`
    where `class_id` = '{$class_id}' ";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);

    //檢查報名是否可行
    if ($_SESSION['club_start_date'] > $today || $_SESSION['club_end_date'] < $today) {
        $xoopsTpl->assign('is_timeout', 'yes');
    }
    if (($all['class_menber'] + $_SESSION['club_backup_num']) <= $all['class_regnum']) {
        $xoopsTpl->assign('is_full', 'yes');
    }

    //以下會產生這些變數： $class_id, $class_year, $class_num, $cate_id, $class_title, $teacher_id, $class_week, $class_date_open, $class_date_close, $class_time_start, $class_time_end, $place_id, $class_menber, $class_money, $class_fee, $class_regnum, $class_note, $class_date_start, $class_date_end, $class_ischecked, $class_isopen, $class_desc
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    //取得分類資料()
    $cate_arr    = get_cate($cate_id, 'kw_club_cate', 'cate');
    $teacher_arr = get_cate($teacher_id, 'kw_club_teacher', 'teacher');
    $place_arr   = get_cate($place_id, 'kw_club_place', 'place');

    //將是/否選項轉換為圖示
    $class_isopen = ($class_isopen == 1) ? '<img src="' . XOOPS_URL . '/modules/kw_club/images/yes.gif" alt="' . _YES . '" title="' . _YES . '">' : '<img src="' . XOOPS_URL . '/modules/kw_club/images/no.gif" alt="' . _NO . '" title="' . _NO . '">';

    //過濾讀出的變數值
    $class_num   = $myts->htmlSpecialChars($class_num);
    $class_title = $myts->htmlSpecialChars($class_title);

    $class_menber     = $myts->htmlSpecialChars($class_menber);
    $class_money      = $myts->htmlSpecialChars($class_money);
    $class_fee        = $myts->htmlSpecialChars($class_fee);
    $class_note       = $myts->htmlSpecialChars($class_note);
    $class_date_open  = $myts->htmlSpecialChars($class_date_open);
    $class_date_close = $myts->htmlSpecialChars($class_date_close);
    $class_time_start = $myts->htmlSpecialChars($class_time_start);
    $class_time_end   = $myts->htmlSpecialChars($class_time_end);
    $class_desc       = $myts->displayTarea($class_desc, 1, 1, 0, 1, 0);

    $xoopsTpl->assign('class_id', $class_id);
    $xoopsTpl->assign('class_year', $class_year);
    $xoopsTpl->assign('class_num', $class_num);
    $xoopsTpl->assign('cate_id', $cate_id);
    $xoopsTpl->assign('cate_id_title', $cate_arr['cate_title']);
    $xoopsTpl->assign('class_title', $class_title);
    $xoopsTpl->assign('teacher_id', $teacher_id);
    $xoopsTpl->assign('teacher_id_title', $teacher_arr['teacher_title']);
    $xoopsTpl->assign('class_week', $class_week);
    $xoopsTpl->assign('class_grade', $class_grade);
    $xoopsTpl->assign('class_date_open', $class_date_open);
    $xoopsTpl->assign('class_date_close', $class_date_close);
    $xoopsTpl->assign('class_time_start', $class_time_start);
    $xoopsTpl->assign('class_time_end', $class_time_end);
    $xoopsTpl->assign('place_id', $place_id);
    $xoopsTpl->assign('place_id_title', $place_arr['place_title']);
    $xoopsTpl->assign('class_menber', $class_menber);
    $xoopsTpl->assign('class_money', $class_money);
    $xoopsTpl->assign('class_fee', $class_fee);
    $xoopsTpl->assign('class_regnum', $class_regnum);
    $xoopsTpl->assign('class_note', $class_note);
    $xoopsTpl->assign('class_date_start', $class_date_start);
    $xoopsTpl->assign('class_date_end', $class_date_end);
    $xoopsTpl->assign('class_ischecked', $class_ischecked);
    $xoopsTpl->assign('class_isopen', $class_isopen);
    $xoopsTpl->assign('class_desc', $class_desc);
    $xoopsTpl->assign('class_uid', $class_uid);

    //已有人報名 報名列表
    if ($class_regnum > 0) {
        $sql     = "select * from `" . $xoopsDB->prefix("kw_club_reg") . "` where `reg_year`='{$class_year}' and `class_id`='{$class_id}' ";
        $result  = $xoopsDB->query($sql) or web_error($sql);
        $all_reg = [];
        $i       = 0;
        while ($all = $xoopsDB->fetchArray($result)) {

            $all_reg[$i] = $all;
            $i++;
        }

        $xoopsTpl->assign('all_reg', $all_reg);
    }

    //刪除訊息警告
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert_obj   = new sweet_alert();
    $delete_class_func = $sweet_alert_obj->render('delete_class_func', "club.php?op=delete_class&class_id=", "class_id");
    $xoopsTpl->assign('delete_class_func', $delete_class_func);

    //轉向網頁
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('op', 'class_show');

}

//列出所有kw_club_class資料
function class_list($year = '')
{
    global $xoopsDB, $xoopsUser, $xoopsTpl, $today, $xoopsModuleConfig;

    $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
    $xoopsTpl->assign('uid', $uid);

    //從 club_info 取得目前報名的期別(select)
    $arr_year = get_all_year();
    $xoopsTpl->assign('arr_year', $arr_year);

    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);

    //已有設定社團期別
    if (!empty($_SESSION['club_year'])) {

        if (empty($year)) {
            $year = $_SESSION['club_year'];
        } else {
            $year = $year; //已有設定社團期別
        }

        $xoopsTpl->assign('year', $year);

        //社團列表
        $myts = MyTextSanitizer::getInstance();
        $sql  = "select * from `" . $xoopsDB->prefix("kw_club_class") . "` where `class_year`= {$year} order by class_num ";

        //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
        $PageBar = getPageBar($sql, $xoopsModuleConfig['show_num'], 10);
        $bar     = $PageBar['bar'];
        $sql     = $PageBar['sql'];
        $total   = $PageBar['total'];
        $result  = $xoopsDB->query($sql) or web_error($sql);
        $xoopsTpl->assign('bar', $bar);
        $xoopsTpl->assign('total', $total);

        //取得分類所有資料陣列
        $all_cate_arr    = get_cate_all();
        $all_place_arr   = get_place_all();
        $all_teacher_arr = get_teacher_all();
        $all_content     = '';
        $i               = 0;
        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $class_id, $class_year, $class_num, $cate_id, $class_title, $teacher_id, $class_week, $class_date_open, $class_date_close, $class_time_start, $class_time_end, $place_id, $class_menber, $class_money, $class_fee, $class_regnum, $class_note, $class_date_start, $class_date_end, $class_ischecked, $class_isopen, $class_desc
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            //將是/否選項轉換為圖示
            $class_isopen = $class_isopen == 1 ? '<img src="' . XOOPS_URL . '/modules/kw_club/images/yes.gif" alt="' . _YES . '" title="' . _YES . '">' : '<img src="' . XOOPS_URL . '/modules/kw_club/images/no.gif" alt="' . _NO . '" title="' . _NO . '">';

            //過濾讀出的變數值
            $class_num        = $myts->htmlSpecialChars($class_num);
            $class_title      = $myts->htmlSpecialChars($class_title);
            $class_date_open  = $myts->htmlSpecialChars($class_date_open);
            $class_date_close = $myts->htmlSpecialChars($class_date_close);
            $class_time_start = $myts->htmlSpecialChars($class_time_start);
            $class_time_end   = $myts->htmlSpecialChars($class_time_end);
            $class_menber     = $myts->htmlSpecialChars($class_menber);
            $class_money      = $myts->htmlSpecialChars($class_money);
            $class_fee        = $myts->htmlSpecialChars($class_fee);
            $class_note       = $myts->htmlSpecialChars($class_note);
            $class_date_start = $myts->htmlSpecialChars($class_date_start);
            $class_date_end   = $myts->htmlSpecialChars($class_date_end);
            $class_desc       = $myts->displayTarea($class_desc, 1, 1, 0, 1, 0);

            $all_content[$i]['class_id']         = $class_id;
            $all_content[$i]['class_year']       = $class_year;
            $all_content[$i]['class_num']        = $class_num;
            $all_content[$i]['cate_id']          = $all_cate_arr[$cate_id]['cate_title'];
            $all_content[$i]['class_title']      = $class_title;
            $all_content[$i]['teacher_id']       = $all_teacher_arr[$teacher_id]['teacher_title'];
            $all_content[$i]['class_week']       = $class_week;
            $all_content[$i]['class_grade']      = $class_grade;
            $all_content[$i]['class_date_open']  = $class_date_open;
            $all_content[$i]['class_date_close'] = $class_date_close;
            $all_content[$i]['class_time_start'] = $class_time_start;
            $all_content[$i]['class_time_end']   = $class_time_end;
            $all_content[$i]['place_id']         = $all_place_arr[$place_id]['place_title'];
            $all_content[$i]['class_menber']     = $class_menber;
            $all_content[$i]['class_money']      = $class_money;
            $all_content[$i]['class_fee']        = $class_fee;
            $all_content[$i]['class_regnum']     = $class_regnum;
            $all_content[$i]['class_note']       = $class_note;
            $all_content[$i]['class_date_start'] = $class_date_start;
            $all_content[$i]['class_date_end']   = $class_date_end;
            $all_content[$i]['class_ischecked']  = $class_ischecked;
            $all_content[$i]['class_isopen']     = $class_isopen;
            $all_content[$i]['class_desc']       = $class_desc;
            $all_content[$i]['class_uid']        = $class_uid;
            $uid_name                            = XoopsUser::getUnameFromId($class_uid, 1);
            $all_content[$i]['class_uidname']    = $uid_name;

            $i++;

        }

        //刪除確認的JS
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert_obj   = new sweet_alert();
        $delete_class_func = $sweet_alert_obj->render('delete_class_func',
            "club.php?op=delete_class&class_id=", "class_id");
        $xoopsTpl->assign('delete_class_func', $delete_class_func);

        $xoopsTpl->assign('all_content', $all_content);

    } else {
        $xoopsTpl->assign('error', _MD_KWCLUB_NEED_CONFIG);
    }

}

function get_ip()
{

    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
        $ip = $_SERVER["REMOTE_ADDR"];
    } else {
        $ip = "noip";
    }
    return $ip;
}

function check_Angent()
{
    //Detect special conditions devices
    $iPod   = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
    $iPad   = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");

    if (stripos($_SERVER['HTTP_USER_AGENT'], "Android") && stripos($_SERVER['HTTP_USER_AGENT'], "mobile")) {
        $Android = true;
    } else if (stripos($_SERVER['HTTP_USER_AGENT'], "Android")) {
        $Android       = false;
        $AndroidTablet = true;
    } else {
        $Android       = false;
        $AndroidTablet = false;
    }
    $webOS      = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");
    $BlackBerry = stripos($_SERVER['HTTP_USER_AGENT'], "BlackBerry");
    $RimTablet  = stripos($_SERVER['HTTP_USER_AGENT'], "RIM Tablet");

    //do something with this information
    if ($iPod || $iPhone) {
        //were an iPhone/iPod touch -- do something here
        //header("Location: show2.php"); //手機版
        $Angent = "iPhone";
    } else if ($iPad) {
        //were an iPad -- do something here
        // header("Location: show2.php"); //手機版
        $Angent = "iPad";
    } else if ($Android) {
        //we're an Android Phone -- do something here
        // header("Location: show2.php"); //手機版
        $Angent = "Android";
    } else if ($AndroidTablet) {
        //we're an Android Phone -- do something here
        // header("Location: show2.php"); //手機版
        $Angent = "AndroidTablet";
    } else if ($webOS) {
        //we're a webOS device -- do something here
        // header("Location: show2.php"); //手機版
        $Angent = "webOS";
    } else if ($BlackBerry) {
        //we're a BlackBerry phone -- do something here
        //header("Location: show2.php"); //手機版
        $Angent = "BlackBerry";
    } else if ($RimTablet) {
        //we're a RIM/BlackBerry Tablet -- do something here
        // header("Location: show2.php"); //手機版
        $Angent = "RimTablet";
    } else {
        //we're not a mobile device.
        // header("Location: show1.php");  //電腦版
        $Angent = "pc";
    }

    return $Angent;
}

function mk_json($class_id)
{
    global $xoopsDB, $TadUpFiles;
    if (empty($class_id)) {
        return flase;
    } else {
        $myts = MyTextSanitizer::getInstance();

        $tbl       = $xoopsDB->prefix('kw_club_class');
        $sql       = "SELECT * FROM `$tbl` where `class_id`={$class_id} ";
        $result    = $xoopsDB->query($sql) or web_error($sql);
        $class     = $xoopsDB->fetchArray($result);
        $class_num = $class['class_num'];
        $json      = json_encode($class, JSON_UNESCAPED_UNICODE);
        file_put_contents(XOOPS_ROOT_PATH . "/uploads/kw_club/class/{$class_num}.json", $json);

        return true;
    }
}

//取得某一篇js_class
function js_class($class_num)
{
    global $xoopsDB, $xoopsTpl;

    if (file_exists(XOOPS_ROOT_PATH . "/uploads/kw_club/class/$class_num.json")) {
        $json = file_get_contents(XOOPS_URL . "/uploads/kw_club/class/$class_num.json");
        $arr  = json_decode($json, true);
        return $arr;
    } else {
        return false;
    }

}

//以流水號秀出某筆kw_club_cate資料內容
function cate_show($type, $cate_id = '')
{
    global $xoopsDB, $xoopsTpl;

    if (empty($cate_id) || empty($type)) {
        return;
    } else {
        $cate_id = intval($cate_id);
    }

    $myts = MyTextSanitizer::getInstance();

    $sql = "select * from `" . $xoopsDB->prefix('kw_club_'.$type) . "`
    where `" . $type . "_id` = '{$cate_id}' ";
    $result = $xoopsDB->query($sql) or web_error($sql);
    // $all    = $xoopsDB->fetchArray($result);
    $arr = $result->fetch_row();

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }

    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert_obj  = new sweet_alert();
    $delete_cate_func = $sweet_alert_obj->render('delete_cate_func', "{$_SERVER['PHP_SELF']}?type={$type}&op=delete_cate&cate_id=", "cate_id");
    $xoopsTpl->assign('delete_cate_func', $delete_cate_func);
    $xoopsTpl->assign('arr', $arr);
    $xoopsTpl->assign('action', "{$_SERVER['PHP_SELF']}?type=$type&op=cate_form");
    $xoopsTpl->assign('op', 'cate_show'); //template name

}

//列出所有kw_club_cate資料
function cate_list($type)
{
    global $xoopsDB, $xoopsTpl;

    $myts = MyTextSanitizer::getInstance();

    $sql    = "select * from `" . $xoopsDB->prefix('kw_club_'.$type) . "` order by " . $type . "_sort";
    $result = $xoopsDB->query($sql) or web_error($sql);

    $all_content = array();
    while ($all = $xoopsDB->fetchArray($result)) {

        //過濾讀出的變數值
        $all["{$type}_title"] = $myts->htmlSpecialChars($all["{$type}_title"]);
        $all["{$type}_desc"]  = $myts->htmlSpecialChars($all["{$type}_desc"]);
        $all_content[]        = $all;
    }

    //刪除確認的JS
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert_obj  = new sweet_alert();
    $delete_cate_func = $sweet_alert_obj->render('delete_cate_func', "{$_SERVER['PHP_SELF']}?type={$type}&op=delete_cate&cate_id=", "cate_id");
    $xoopsTpl->assign('delete_cate_func', $delete_cate_func);

    $xoopsTpl->assign('action', "{$_SERVER['PHP_SELF']}?type={$type}");
    $xoopsTpl->assign("all_{$type}_content", $all_content);
}

//刪除reg某筆資料資料
function delete_reg()
{
    global $xoopsDB;
    // if (!$_SESSION['isclubAdmin']) {
    //     redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    // }
    $reg_sn   = system_CleanVars($_REQUEST, 'reg_sn', '0', 'int');
    $class_id = system_CleanVars($_REQUEST, 'class_id', '0', 'int');
    $uid      = system_CleanVars($_REQUEST, 'uid', '0', 'string');

    if (empty($reg_sn)) {
        redirect_header("{$_SERVER['PHP_SELF']}?op=myclass&uid={$uid}", 3, '錯誤!');
    } else {
        $arr      = get_reg($reg_sn);
        $class_id = $arr['class_id'];

    }

    $sql = "update `" . $xoopsDB->prefix("kw_club_class") . "`
    set `class_regnum` =`class_regnum`-1   where `class_id` = '{$class_id}'";
    $xoopsDB->queryF($sql);

    $sql = "delete from `" . $xoopsDB->prefix("kw_club_reg") . "`  where `reg_sn` = '{$reg_sn}'";
    $xoopsDB->queryF($sql) or web_error($sql);

}
