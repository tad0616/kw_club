<?php

//引入TadTools的函式庫
if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php")) {
    redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50", 3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php";

$semester_name_arr = array('00' => '暑假', '01' => '第一學期', '11' => '寒假', '02' => '第二學期');
$grade_name_arr = array('幼','一','二','三','四','五','六','七','八','九');

//其他自訂的共同的函數

//從json中取得社團期別資料（會在header.php中讀取）
function get_club_info()
{
    global $xoopsDB, $xoopsTpl;

    if (!isset($_SESSION['club_start_date_ts']) or empty($_SESSION['club_start_date_ts'])) {
        // $sql = "select * from `" . $xoopsDB->prefix("kw_club_info") . "` where `club_enable`='1' and `club_start_date`< now() and `club_end_date` > now()";
        $sql       = "select * from `" . $xoopsDB->prefix("kw_club_info") . "` where `club_enable`='1' order by club_start_date desc limit 0,1";
        $result    = $xoopsDB->query($sql) or web_error($sql);
        $club_info = $xoopsDB->fetchArray($result);

        $_SESSION['club_year']          = $club_info['club_year'];
        $_SESSION['club_start_date']    = $club_info['club_start_date'];
        $_SESSION['club_start_date_ts'] = strtotime($club_info['club_start_date']);
        $_SESSION['club_end_date']      = $club_info['club_end_date'];
        $_SESSION['club_end_date_ts']   = strtotime($club_info['club_end_date']);
        $_SESSION['club_isfree']        = $club_info['club_isfree'];
        $_SESSION['club_backup_num']    = $club_info['club_backup_num'];
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
    $sql     = "select * from `" . $xoopsDB->prefix('kw_club_' . $type) . "`
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
function get_club_class($class_id = '')
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
    $data_arr = array();
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
    $data_arr = array();
    while ($data = $xoopsDB->fetchArray($result)) {
        $cate_id            = $data['place_id'];
        $data_arr[$cate_id] = $data;
    }
    return $data_arr;
}

//取得所有社團老師陣列
function get_teacher_all()
{
    global $xoopsDB;
    //開課教師
    $groupid = group_id_from_name(_MD_KWCLUB_TEACHER_GROUP);
    $sql     = "select b.* from `" . $xoopsDB->prefix("groups_users_link") . "` as a
   join " . $xoopsDB->prefix("users") . " as b on a.`uid`=b.`uid`
   where a.`groupid`='{$groupid}' order by b.`name`";
    $result      = $xoopsDB->query($sql) or web_error($sql);
    $arr_teacher = array();
    while ($teacher = $xoopsDB->fetchArray($result)) {
        $uid               = $teacher['uid'];
        $arr_teacher[$uid] = $teacher;
    }
    return $arr_teacher;
}

//取得所有社團資料陣列
function get_class_all()
{
    global $xoopsDB;

    if (isset($_SESSION['club_year'])) {
        $year = $_SESSION['club_year'];

        $sql      = "select * from `" . $xoopsDB->prefix("kw_club_class") . "` where `class_year`= {$year}";
        $result   = $xoopsDB->query($sql) or web_error($sql);
        $data_arr = array();
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

    $sql = "select * from `" . $xoopsDB->prefix('kw_club_' . $type) . "`
    where `" . $type . "_id` = '{$cate_id}' ";
    $result = $xoopsDB->query($sql) or web_error($sql);
    // $all    = $xoopsDB->fetchArray($result);
    $arr = $result->fetch_row();

    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MA_NEED_TADTOOLS);
    }

    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert_obj = new sweet_alert();
    $sweet_alert_obj->render("delete_{$type}_func", "{$_SERVER['PHP_SELF']}?type={$type}&op=delete_{$type}&{$type}_id=", "{$type}_id");
    $xoopsTpl->assign('arr', $arr);
    $xoopsTpl->assign('action', "{$_SERVER['PHP_SELF']}?type=$type&op=cate_form");
    // $xoopsTpl->assign('op', 'cate_show'); //template name

}

//列出所有kw_club_cate資料
function cate_list($type)
{
    global $xoopsDB, $xoopsTpl;

    $myts = MyTextSanitizer::getInstance();

    $sql    = "select * from `" . $xoopsDB->prefix('kw_club_' . $type) . "` order by " . $type . "_sort";
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
    $sweet_alert_obj = new sweet_alert();
    $sweet_alert_obj->render("delete_{$type}_func", "{$_SERVER['PHP_SELF']}?type={$type}&op=delete_{$type}&{$type}_id=", "{$type}_id");

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

//根據名稱找群組編號
function group_id_from_name($group_name = "")
{
    global $xoopsDB;
    $sql           = "select groupid from " . $xoopsDB->prefix("groups") . " where `name`='{$group_name}'";
    $result        = $xoopsDB->queryF($sql) or web_error($sql);
    list($groupid) = $xoopsDB->fetchRow($result);
    return $groupid;
}

//判斷身份
function isclub($group_name = '')
{
    global $xoopsUser;
    if ($xoopsUser) {
        $groupid = group_id_from_name($group_name);
        var_dump($groupid);
        if ($groupid) {
            $groups = $xoopsUser->getGroups();
            var_dump($groups);
            if (in_array($groupid, $groups)) {
                return true;
            }
        }
    }
    return false;
}

//檢查是否為報名時間
function chk_time($mode = '')
{
    $today = time();
    if ($_SESSION['club_start_date_ts'] > $today || $_SESSION['club_end_date_ts'] < $today) {
        if ($mode == 'return') {
            return false;
        } else {
            redirect_header("index.php", 5, "目前不是報名時間喔！<p>報名期間為 {$_SESSION['club_start_date']} ~ {$_SESSION['club_end_date']}</p>");
        }
    }
    return true;
}
