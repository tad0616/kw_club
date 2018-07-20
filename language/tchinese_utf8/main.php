<?php
include_once XOOPS_ROOT_PATH . "/modules/tadtools/language/{$xoopsConfig['language']}/main.php";

//前後台語系
// define('_MD_KWCLUB_CLASS_ID', '社團ID');
define('_MD_KWCLUB_CLASS_YEAR', '社團年度');
define('_MD_KWCLUB_CLASS_TITLE', '社團名稱');
define('_MD_KWCLUB_CLASS_NUM', '社團序號');
define('_MD_KWCLUB_CATE_ID', '社團類型');
define('_MD_KWCLUB_TEACHER_ID', '開課教師');
define('_MD_KWCLUB_CLASS_WEEK', '上課星期');
define('_MD_KWCLUB_CLASS_GRADE', '招收對象');
define('_MD_KWCLUB_CLASS_DATE', '上課日期');
define('_MD_KWCLUB_CLASS_TIME', '上課時間');
define('_MD_KWCLUB_CLASS_DATE_OPEN', '上課起始日');
define('_MD_KWCLUB_CLASS_DATE_CLOSE', '上課終止日');
define('_MD_KWCLUB_CLASS_TIME_START', '起始時間');
define('_MD_KWCLUB_CLASS_TIME_END', '終止時間');
define('_MD_KWCLUB_PLACE_ID', '上課地點');
define('_MD_KWCLUB_CLASS_MENBER', '招收人數');
define('_MD_KWCLUB_CLASS_MONEY', '社團學費');
define('_MD_KWCLUB_CLASS_FEE', '額外費用');
define('_MD_KWCLUB_CLASS_NOTE', '社團備註');
define('_MD_KWCLUB_CLASS_REGNUM', '報名人數');
define('_MD_KWCLUB_CLASS_REG', '報名');
define('_MD_KWCLUB_CLASS_DATE_START', '報名起始');
define('_MD_KWCLUB_CLASS_DATE_END', '報名終止');
define('_MD_KWCLUB_CLASS_ISOPEN', '是否啟用');
define('_MD_KWCLUB_CLASS_ISCHECKED', '是否開班');
define('_MD_KWCLUB_CLASS_DESC', '社團簡介');
define('_MD_KWCLUB_CLASS_UID', 'UID');

//前台選單
define('_MD_KWCLUB_INDEX_LIST', '社團列表');
define('_MD_KWCLUB_INDEX_MYCLASS', '我的社團');
define('_MD_KWCLUB_INDEX_TEACHER', '教師簡介');
define('_MD_KWCLUB_INDEX_FORM', '開設課程');
define('_MD_KWCLUB_CATE', '新增項目');
define('_MD_KWCLUB_REG', '報名統計');

//教師列表
define('_MD_KWCLUB_CATEID', '流水號');
define('_MD_KWCLUB_CATE_TITLE', '名稱');
define('_MD_KWCLUB_CATE_DESC', '簡介');
define('_MD_KWCLUB_CATE_SORT', '排序');
define('_MD_KWCLUB_CATE_ENABLE', '狀態');

define('_MD_KWCLUB_REG_YEAR', '報名年度');
define('_MD_KWCLUB_CLASS_ID', '課程編號');
define('_MD_KWCLUB_REG_USERID', '身份字號');
define('_MD_KWCLUB_REG_NAME', '姓名');
define('_MD_KWCLUB_REG_GRADE', '年級');
define('_MD_KWCLUB_REG_CLASS', '班級');

//kw_club_reg-edit
define('_MD_KWCLUB_REG_SN', '報名編號');
define('_MD_KWCLUB_REG_UID', '報名者編號');
define('_MD_KWCLUB_REG_DATETIME', '報名日期');
define('_MD_KWCLUB_REG_ISREG', '是否後補');
define('_MD_KWCLUB_REG_ISFEE', '是否繳費');
define('_MD_KWCLUB_REG_IP', 'IP');

//by tad
if ($_SESSION['isclubAdmin']) {
    define('_MD_KWCLUB_NEED_CONFIG', '尚未設定社團期別，<a href="config.php">請先進行社團報名期別設定</a>後，再新增課程！');
} else {
    define('_MD_KWCLUB_NEED_CONFIG', '尚未設定社團期別，請通知管理員，進行社團報名期別設定，以便新增課程！');
}
define('_MD_KWCLUB_SELECT_YEAR', '請選擇社團期別：');
define('_MD_KWCLUB_EMPTY_YEAR', '目前沒有期別');
define('_MD_KWCLUB', '社團報名');
define('_MD_KWCLUB_LIST', '期社團列表');
define('_MD_KWCLUB_APPLY_DATE', '報名起訖時間');
define('_MD_KWCLUB_APPLY_FROM_TO', '起至');
define('_MD_KWCLUB_EMPTY_CLUB', '此期尚未新增社團！');
define('_MD_KWCLUB_FORBBIDEN', '您沒有執行此動作的權限！');
define('_MD_KWCLUB_INFO_SETUP', '社團期別設定');
define('_MD_KWCLUB_YEAR', '社團期別');
define('_MD_KWCLUB_NOW_YEAR', '目前設定的期數是：');
define('_MD_KWCLUB_YEAR_HELP', '（除非截止日期或重設，否則設定後就無法變更。00暑假、01第一學期、11寒假、02第二學期）');
define('_MD_KWCLUB_ADMIN', '期別設定');
define('_MD_KWCLUB_BACKUP_NUM', '候補人數');
define('_MD_KWCLUB_STATISTICS', '報名統計');
define('_MD_KWCLUB_ADD_CLUB', '新增社團');

define('_MD_KWCLUB_START_DATE', '報名起始日');
define('_MD_KWCLUB_END_DATE', '報名終止日');
define('_MD_KWCLUB_ISFREE', '是否登入報名');
define('_MD_KWCLUB_UID', '設定者');
define('_MD_KWCLUB_DATETIME', '設定時間');
define('_MD_KWCLUB_ENABLE', '是否過期');
