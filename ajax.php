<?php
include_once "header.php";

/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op     = system_CleanVars($_REQUEST, 'op', '', 'string');
$keyman = system_CleanVars($_REQUEST, 'keyman', '', 'string');

switch ($op) {
    //篩選使用者
    case "keyman":
        die(keyman($keyman));
        exit;
}

function keyman($keyman)
{
    global $xoopsDB;
    $groupid  = group_id_from_name(_MD_KWCLUB_TEACHER_GROUP);
    $user_arr = array();
    //列出群組中有哪些人
    if ($groupid) {
        $member_handler = xoops_gethandler('member');
        $user_arr       = $member_handler->getUsersByGroup($groupid);
    }

    $where = !empty($keyman) ? "where name like '%{$keyman}%' or uname like '%{$keyman}%' or email like '%{$keyman}%'" : "";

    $sql    = "select uid,uname,name from " . $xoopsDB->prefix("users") . " $where order by uname";
    $result = $xoopsDB->query($sql) or web_error($sql);

    $myts    = MyTextSanitizer::getInstance();
    $user_ok = $user_yet = "";
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $name  = $myts->htmlSpecialChars($name);
        $uname = $myts->htmlSpecialChars($uname);
        $name  = empty($name) ? "" : " ({$name})";
        if (!empty($user_arr) and in_array($uid, $user_arr)) {
            $user_ok .= "<option value=\"$uid\">{$uid} {$name} {$uname} </option>";
        } else {
            $user_yet .= "<option value=\"$uid\">{$uid} {$name} {$uname} </option>";
        }
    }
    return $user_yet;
}
