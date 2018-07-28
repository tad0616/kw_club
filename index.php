<?php
/*-----------引入檔案區--------------*/
include_once "header.php";
$xoopsOption['template_main'] = "kw_club_index.tpl";
include_once XOOPS_ROOT_PATH . "/header.php";

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op       = system_CleanVars($_REQUEST, 'op', '', 'string');
$class_id = system_CleanVars($_REQUEST, 'class_id', '0', 'int');
$cate_id  = system_CleanVars($_REQUEST, 'cate_id', '0', 'int');
$uid      = system_CleanVars($_REQUEST, 'uid', '', 'string');
$year     = system_CleanVars($_REQUEST, 'year', '', 'int');

$today = date('Y-m-d');
switch ($op) {

    case "teacher":
        $type = 'teacher'; //database name

        if (empty($cate_id)) {
            cate_list($type);
        } else {
            cate_show($type, $cate_id);
        }
        break;

    case "myclass":
        myclass();
        break;

    case "reg_form":
        reg_form();

        break;

    case "insert_reg":
        insert_reg();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    case "delete_reg":
        delete_reg();
        header("location: {$_SERVER['PHP_SELF']}?op=myclass&uid={$uid}");
        exit;

    default:
        if ($class_id) {
            class_show($class_id);
            $op = 'class_show';
        } else {
            class_list($year);
            $op = 'class_list';
        }

        break;
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign('op', $op);
$xoopsTpl->assign("toolbar", toolbar_bootstrap($interface_menu));
include_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------function區--------------*/

function reg_class($class_id)
{
    global $xoopsTpl;

    $main = "模組開發中";
    $xoopsTpl->assign('content', $main);
}

//reg編輯表單
function reg_form()
{
    global $xoopsDB, $xoopsTpl, $xoopsUser, $today;
    $class_id = system_CleanVars($_REQUEST, 'class_id', '0', 'int');
    // $class_title = system_CleanVars($_REQUEST, 'class_title', '', 'string');
    $class_grade = system_CleanVars($_REQUEST, 'class_grade', '', 'string');

    //是否報名額滿
    $is_full = ($is_full == 'yes') ? 1 : 0;

    if (empty($class_id) || empty($class_grade)) {
        redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
    }

    //自由報名
    if ($_SESSION['club_isfree'] == '0') {

        include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
        include_once XOOPS_ROOT_PATH . "/class/xoopseditor/xoopseditor.php";

        $span = $_SESSION['bootstrap'] == '3' ? 'form-control col-sm-' : 'span';
        $form = new XoopsThemeForm('', 'form', $_SERVER['PHP_SELF'], 'post', true);

        //報名者身分字號
        $reg_uidText = new XoopsFormText(_MD_KWCLUB_REG_USERID, "reg_uid", 25, 255, '請填身分證字號');
        $reg_uidText->setExtra("class = '{$span}5'");
        $form->addElement($reg_uidText, true);

        //報名者姓名
        $reg_nameText = new XoopsFormText(_MD_KWCLUB_REG_NAME, "reg_name", 25, 255, '請填姓名');
        $reg_nameText->setExtra("class = '{$span}5'");
        $form->addElement($reg_nameText, true);

        //報名者年級
        $grade = explode("、", $class_grade);
        for ($i = 0; $i < count($grade); $i++) {
            $options[$grade[$i]] = $grade[$i];
        }
        $reg_gradeSelect = new XoopsFormSelect(_MD_KWCLUB_REG_GRADE, "reg_grade", '$options[0]', false);
        $reg_gradeSelect->addOptionArray($options);
        $reg_gradeSelect->setExtra("class = '{$span}5'");
        $form->addElement($reg_gradeSelect, true);

        //報名者班級
        $reg_classText = new XoopsFormText(_MD_KWCLUB_REG_CLASS, "reg_class", 25, 255, '請填班級');
        $reg_classText->setExtra("class = '{$span}5'");
        $form->addElement($reg_classText, true);

        // 課程名稱 ID
        $form->addElement(new XoopsFormHidden("class_id", $class_id));
        // $form->addElement(new XoopsFormHidden("class_title", $class_title));
        // $form->addElement(new XoopsFormHidden("class_money", $class_money));
        // $form->addElement(new XoopsFormHidden("class_fee", $class_fee));
        $form->addElement(new XoopsFormHidden("is_full", $is_full));
        $form->addElement(new XoopsFormHidden("op", 'insert_reg'));
        $form->addElement(new XoopsFormHiddenToken());

        $SubmitTray = new XoopsFormElementTray('', '', '', true);
        $SubmitTray->addElement(new XoopsFormButton('', '', '以上資料無誤確定報名', 'submit'));
        $form->addElement($SubmitTray);
        $xoopsform = $form->render();
        $xoopsTpl->assign('xoopsform', $xoopsform);

        // $xoopsTpl->assign('op', 'reg_form');

    }
}

//新增資料到reg中
function insert_reg()
{
    global $xoopsDB, $xoopsUser, $today;

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    // $class_id = $_POST['class_id'];
    // $class_title = $_POST['class_title'];
    $class_id  = system_CleanVars($_REQUEST, 'class_id', '0', 'int');
    $arr_class = alter_class($class_id);

    //檢查是否設定期別
    if (isset($_SESSION['club_year'])) {
        $year = $_SESSION['club_year'];
    } else {
        redirect_header("index.php", 3, '錯誤!社團期數未設定!無法報名!請連絡管理員。');
    }

    //檢查報名是否可行
    chk_time();

    if (($arr_class['class_menber'] + $_SESSION['club_backup_num']) <= $arr_class['class_regnum']) {
        redirect_header("index.php", 3, '報名人數已滿!');
    }

    //檢查是否衝堂
    if (check_class_date($reg_uid, $class_id)) {
        redirect_header("index.php", 3, '錯誤!社團課程衝堂!!請再確認!');
    }

    $myts = MyTextSanitizer::getInstance();
    // $reg_uid      = !empty($_POST['reg_uid']) ? intval($_POST['reg_uid']) : $reg_uid;
    $reg_uid     = $myts->addSlashes($_POST['reg_uid']);
    $reg_name    = $myts->addSlashes($_POST['reg_name']);
    $reg_grade   = $myts->addSlashes($_POST['reg_grade']);
    $reg_class   = $myts->addSlashes($_POST['reg_class']);
    $is_full     = $myts->addSlashes($_POST['is_full']);
    $reg_ip      = get_ip();
    $class_title = $arr_class['class_title'];
    $class_money = $arr_class['class_money'];
    $class_fee   = $arr_class['class_fee'];

    $sql = "INSERT INTO `" . $xoopsDB->prefix("kw_club_reg") . "` (
            `reg_year`, `class_id`, `class_title`, `class_money`, `class_fee`,`reg_uid`, `reg_name`, `reg_grade`, `reg_class`, `reg_isreg`, `reg_datetime`,  `reg_ip`) VALUES
            (
                '{$year}',
                '{$class_id}',
                '{$class_title}',
                '{$class_money}',
                '{$class_fee}',
                '{$reg_uid}',
                '{$reg_name}',
                '{$reg_grade}',
                '{$reg_class}',
                '{$is_full}',
                NOW(),
                '{$reg_ip}'
            )";

    if ($xoopsDB->query($sql)) {
        // update xx_kw_club_class set class_regnum=class_regnum+1 where class_id =1;
        $update_sql = "update `" . $xoopsDB->prefix("kw_club_class") . "` set `class_regnum`=`class_regnum`+1 where `class_id`={$class_id}";
        $xoopsDB->query($update_sql);

        redirect_header("index.php", 3, '報名成功!');
        //取得最後新增資料的流水編號
        // $reg_sn = $xoopsDB->getInsertId();
        // return $reg_sn;

    } else {
        // web_error($sql);
        redirect_header("index.php", 3, '錯誤!重複報名!');
    }

}

function check_class_date($reg_uid, $class_id)
{
    global $xoopsDB;

    $arr_reg     = [];
    $check_class = 0;
    $class_new   = alter_class($class_id);
    $year        = $_SESSION['club_year'];
    $sql         = "select * from `" . $xoopsDB->prefix("kw_club_reg") . "` where `reg_uid`='{$reg_uid}' and `reg_year` = '{$year}'";

    $result = $xoopsDB->query($sql) or web_error($sql);
    while ($arr = $xoopsDB->fetchArray($result)) {
        $class_reg = alter_class($arr['class_id']);
        //check the date repeat

        if (!(strtotime($class_reg['class_date_close']) < strtotime($class_new['class_date_open'])) &&
            !(strtotime($class_reg['class_date_open']) > strtotime($class_new['class_date_close']))) {
            //check the week repeat

            $class_week_reg = explode("、", $class_reg['class_week']);
            $class_week_new = explode("、", $class_new['class_week']);
            foreach ($class_week_new as &$value) {
                if (in_array($value, $class_week_reg)) {
                    // check the time repeat
                    if (!(strtotime($class_reg['class_time_end']) < strtotime($class_new['class_time_start'])) &&
                        !(strtotime($class_reg['class_time_start']) > strtotime($class_new['class_time_end']))) {
                        $check_class++;
                    }
                }
            }
        }
    }
    echo $check_class;
    if ($check_class > 0) {
        return ture;
    } else {
        return false;
    }
}

function myclass()
{
    global $xoopsDB, $xoopsTpl;
    $reg_uid = system_CleanVars($_REQUEST, 'uid', '', 'string');
    $year    = system_CleanVars($_REQUEST, 'year', '', 'string');

    if (empty($reg_uid)) {

        $arr_reg = "";
        $i       = 0;
    } else {
        //報名年度
        if (empty($year) && isset($_SESSION['club_year'])) {
            $reg_year = $_SESSION['club_year'];
        } else {
            $reg_year = $year;
        }

        //取得社團期別
        $arr_year = get_all_year();
        $xoopsTpl->assign('arr_year', $arr_year);

        $myts = MyTextSanitizer::getInstance();
        $sql  = "select * from `" . $xoopsDB->prefix("kw_club_reg") . "` where `reg_uid` = '{$reg_uid}'  and `reg_year`={$reg_year}";

        $result   = $xoopsDB->query($sql) or web_error($sql);
        $arr_reg  = [];
        $i        = 0;
        $money    = 0;
        $in_money = 0;
        $un_money = 0;
        while ($arr = $xoopsDB->fetchArray($result)) {

            $class = alter_class($arr['class_id']);
            array_push($arr, $class['class_num'],
                $class['class_date_open'], $class['class_date_close'],
                $class['class_time_start'], $class['class_time_end'],
                $class['class_week'], $class['class_money'], $class['class_fee']);
            $arr_reg[] = $arr;
            if ($arr['reg_isfee'] == '1') {
                $in_money += $class['class_money'];
            } else {
                $un_money += $class['class_money'];
            }
            $money += ($class['class_money'] + $class['class_fee']);

            if ($i == 0) {
                $reg_name = $arr['reg_name'];
            }
            $i++;
        }
        // die(json_encode($arr_reg, JSON_UNESCAPED_UNICODE));
        $xoopsTpl->assign('reg_name', $reg_name);
        $xoopsTpl->assign('money', $money);
        $xoopsTpl->assign('in_money', $in_money);
        $xoopsTpl->assign('un_money', $un_money);
        $xoopsTpl->assign('arr_reg', $arr_reg);
        $xoopsTpl->assign('year', $year);

        $sql            = "select `club_end_date` from `" . $xoopsDB->prefix("kw_club_info") . "` where  `club_year`={$reg_year}";
        $result         = $xoopsDB->query($sql) or web_error($sql);
        list($end_date) = $xoopsDB->fetchRow($result);
        $xoopsTpl->assign('end_date', $end_date);
        $xoopsTpl->assign('today', Date("Y-m-d"));

        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert = new sweet_alert();
        if ($_SESSION['isclubAdmin']) {
            $delete_reg_func = $sweet_alert->render("delete_reg_func", "{$_SERVER['PHP_SELF']}?op=delete_reg&reg_sn=", 'reg_sn');
        }
        $delete_reg_func = $sweet_alert->render("delete_reg_func", "{$_SERVER['PHP_SELF']}?op=delete_reg&uid={$reg_uid}&reg_sn=", 'reg_sn', "確定要取消嗎？", "取消", "是！含淚取消報名！");
        $xoopsTpl->assign('delete_reg_func', $delete_reg_func);

    }

    $xoopsTpl->assign('reg_num', $i);
    $xoopsTpl->assign('arr_reg', $arr_reg);
    $xoopsTpl->assign('uid', $reg_uid);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    // $xoopsTpl->assign('op', 'myclass');
}

//顯示某一個社團
function class_showjson($class_id = '')
{
    global $xoopsTpl, $today;
    if (!file_exists(XOOPS_ROOT_PATH . "/uploads/kw_club/{$class_id}.json")) {
        $json = mk_json($class_id);
    } else {
        $json = file_get_contents(XOOPS_URL . "/uploads/kw_club/{$class_id}.json");
    }
    $all = json_decode($json, true);

    //檢查報名是否可行
    chk_time();

    if (($all['class_menber'] + $_SESSION['club_backup_num']) <= $all['class_regnum']) {
        $xoopsTpl->assign('is_full', 'yes');
    }

    // 取得分類資料()
    $cate_arr    = get_cate($all['cate_id'], 'cate');
    $teacher_arr = get_teacher_all();
    $place_arr   = get_cate($all['place_id'], 'place');

    $xoopsTpl->assign('class_id', $all['class_id']);
    $xoopsTpl->assign('class_year', $all['class_year']);
    $xoopsTpl->assign('class_num', $all['class_num']);
    $xoopsTpl->assign('cate_id', $cate_arr['cate_id']);
    $xoopsTpl->assign('cate_id_title', $cate_arr['cate_title']);
    $xoopsTpl->assign('class_title', $all['class_title']);
    $xoopsTpl->assign('teacher_id', $teacher_arr['teacher_id']);
    $xoopsTpl->assign('teacher_id_title', $teacher_arr['teacher_title']);
    $xoopsTpl->assign('class_week', $all['class_week']);
    $xoopsTpl->assign('class_grade', $all['class_grade']);
    $xoopsTpl->assign('class_date_open', $all['class_date_open']);
    $xoopsTpl->assign('class_date_close', $all['class_date_close']);
    $xoopsTpl->assign('class_time_start', $all['class_time_start']);
    $xoopsTpl->assign('class_time_end', $all['class_time_end']);
    $xoopsTpl->assign('place_id', $place_arr['place_id']);
    $xoopsTpl->assign('place_id_title', $place_arr['place_title']);
    $xoopsTpl->assign('class_menber', $all['class_menber']);
    $xoopsTpl->assign('class_money', $all['class_money']);
    $xoopsTpl->assign('class_fee', $all['class_fee']);
    $xoopsTpl->assign('class_regnum', $all['class_regnum']);
    $xoopsTpl->assign('class_note', $all['class_note']);
    $xoopsTpl->assign('class_date_start', $all['class_date_start']);
    $xoopsTpl->assign('class_date_end', $all['class_date_end']);
    $xoopsTpl->assign('class_ischecked', $all['class_ischecked']);
    $xoopsTpl->assign('class_isopen', $all['class_isopen']);
    $xoopsTpl->assign('class_desc', $all['class_desc']);

    // $xoopsTpl->assign('op', 'class_show');
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);

}

//以流水號秀出某筆kw_club_class資料內容
function class_show($class_id = '')
{
    global $xoopsDB, $xoopsUser, $xoopsTpl, $today;

    if (empty($class_id)) {
        redirect_header("index.php", 3, "無社團課程編號");
    }

    $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
    $xoopsTpl->assign('uid', $uid);

    $myts = MyTextSanitizer::getInstance();

    $sql = "select * from `" . $xoopsDB->prefix("kw_club_class") . "`
    where `class_id` = '{$class_id}' ";
    $result = $xoopsDB->query($sql) or web_error($sql);
    $all    = $xoopsDB->fetchArray($result);

    //檢查報名是否可行
    // chk_time();

    if (($all['class_menber'] + $_SESSION['club_backup_num']) <= $all['class_regnum']) {
        $xoopsTpl->assign('is_full', 'yes');
    }

    //以下會產生這些變數： $class_id, $class_year, $class_num, $cate_id, $class_title, $teacher_id, $class_week, $class_date_open, $class_date_close, $class_time_start, $class_time_end, $place_id, $class_menber, $class_money, $class_fee, $class_regnum, $class_note, $class_date_start, $class_date_end, $class_ischecked, $class_isopen, $class_desc
    foreach ($all as $k => $v) {
        $$k = $v;
    }

    //取得分類資料()
    $cate_arr    = get_cate($cate_id, 'cate');
    $teacher_arr = get_teacher_all();
    $place_arr   = get_cate($place_id, 'place');

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
    $xoopsTpl->assign('teacher_id_title', $teacher_arr[$teacher_id]['name']);
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
    // $xoopsTpl->assign('op', 'class_show');

}


//列出所有社團資料
function class_list($club_year = '')
{
    global $xoopsDB, $xoopsUser, $xoopsTpl, $today, $xoopsModuleConfig;

    $uid = ($xoopsUser) ? $xoopsUser->uid() : '';
    $xoopsTpl->assign('uid', $uid);

    //從 club_info 取得所有期別(select)
    $arr_year = get_all_year();
    $xoopsTpl->assign('arr_year', $arr_year);

    //已有設定社團期別
    if (!empty($_SESSION['club_year'])) {

        if (empty($year)) {
            $club_year = $_SESSION['club_year'];
        }

        $xoopsTpl->assign('year', $club_year);

        //社團列表
        $myts = MyTextSanitizer::getInstance();
        $sql  = "select * from `" . $xoopsDB->prefix("kw_club_class") . "` where `class_year`= '{$club_year}' order by class_num ";
        $result  = $xoopsDB->query($sql) or web_error($sql);

        //取得分類所有資料陣列
        $all_cate_arr    = get_cate_all();
        $all_place_arr   = get_place_all();
        $all_teacher_arr = get_teacher_all();
        $all_class_content     = array();
        $i               = 0;
        while ($all = $xoopsDB->fetchArray($result)) {
            //以下會產生這些變數： $class_id, $class_year, $class_num, $cate_id, $class_title, $teacher_id, $class_week, $class_date_open, $class_date_close, $class_time_start, $class_time_end, $place_id, $class_menber, $class_money, $class_fee, $class_regnum, $class_note, $class_date_start, $class_date_end, $class_ischecked, $class_isopen, $class_desc
            foreach ($all as $k => $v) {
                $$k = $v;
            }

            $all_class_content[$i]['class_id']         = (int)$class_id;
            $all_class_content[$i]['class_year']       = $class_year;
            $all_class_content[$i]['class_num']        = $class_num;
            $all_class_content[$i]['class_title']      = $myts->htmlSpecialChars($class_title);
            $all_class_content[$i]['class_week']       = $myts->htmlSpecialChars($class_week);
            $all_class_content[$i]['class_grade']      = $myts->htmlSpecialChars($class_grade);
            $all_class_content[$i]['class_date_open']  = $myts->htmlSpecialChars($class_date_open);
            $all_class_content[$i]['class_date_close'] = $myts->htmlSpecialChars($class_date_close);
            $all_class_content[$i]['class_time_start'] = $myts->htmlSpecialChars($class_time_start);
            $all_class_content[$i]['class_time_end']   = $myts->htmlSpecialChars($class_time_end);
            $all_class_content[$i]['cate_id']          = $myts->htmlSpecialChars($all_cate_arr[$cate_id]['cate_title']);
            $all_class_content[$i]['teacher_id']       = $myts->htmlSpecialChars($all_teacher_arr[$teacher_id]['name']);
            $all_class_content[$i]['place_id']         = $myts->htmlSpecialChars($all_place_arr[$place_id]['place_title']);
            $all_class_content[$i]['class_menber']     = (int)$class_menber;
            $all_class_content[$i]['class_money']      = (int)$class_money;
            $all_class_content[$i]['class_fee']        = (int)$class_fee;
            $all_class_content[$i]['class_pay']        = $class_money+$class_fee;
            $all_class_content[$i]['class_regnum']     = (int)$class_regnum;
            $all_class_content[$i]['class_note']       = $myts->htmlSpecialChars($class_note);
            $all_class_content[$i]['class_date_start'] = $myts->htmlSpecialChars($class_date_start);
            $all_class_content[$i]['class_date_end']   = $myts->htmlSpecialChars($class_date_end);
            $all_class_content[$i]['class_ischecked']  = (int)$class_ischecked;
            $all_class_content[$i]['class_isopen']     = $class_isopen ? '<img src="' . XOOPS_URL . '/modules/kw_club/images/yes.gif" alt="' . _YES . '" title="' . _YES . '">' : '<img src="' . XOOPS_URL . '/modules/kw_club/images/no.gif" alt="' . _NO . '" title="' . _NO . '">';
            $all_class_content[$i]['class_desc']       = $myts->displayTarea($class_desc, 1, 1, 0, 1, 0);
            $all_class_content[$i]['class_uid']        = (int)$class_uid;

            $i++;

        }
        $xoopsTpl->assign('all_class_content', $all_class_content);

        //刪除確認的JS
        if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
            redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
        }
        include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
        $sweet_alert_obj   = new sweet_alert();
        $sweet_alert_obj->render('delete_class_func', "club.php?op=delete_class&class_id=", "class_id");


    } else {
        $xoopsTpl->assign('error', _MD_KWCLUB_NEED_CONFIG);
    }

}