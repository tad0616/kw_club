<h1><{$smarty.const._MD_KWCLUB}></h1>

<!-- 社團期別下拉選單 -->
<{if $arr_year}>
    <div class="alert alert-info" style="margin: 10px auto;"><{$smarty.const._MD_KWCLUB_SELECT_YEAR}>
        <select name="select_year" onChange="location.href='<{$action}>?year='+this.value">
            <option value=""></option>
            <{foreach from=$arr_year item=arr_year}>
                <option value="<{$arr_year}>" <{if $arr_year==$year}>selected<{/if}>><{$arr_year}></option>
            <{/foreach}>
        </select>
    </div>
<{else}>
    <div class="alert alert-danger">
        <{$smarty.const._MD_KWCLUB_NEED_CONFIG}>
    </div>
<{/if}>


<{if $year}>
    <h2>
        <span style="color:blue;"><{$year}></span><{$smarty.const._MD_KWCLUB_LIST}>
        <small>（共 <{$total}> 筆活動）</small> 
    </h2>
    <{$smarty.const._MD_KWCLUB_APPLY_DATE}><{$smarty.const._TAD_FOR}><span style="color:red"><{$reg_start}>~<{$reg_end}></span>
    
    <{if $all_content}>
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr class="">
                    <th>
                    <!--社團編號-->
                    <{$smarty.const._MD_KWCLUB_CLASS_NUM}>
                    </th>
                    <th>
                    <!--社團名稱-->
                    <{$smarty.const._MD_KWCLUB_CLASS_TITLE}>
                    </th>
                    <th>
                        <!--社團類型-->
                        <{$smarty.const._MD_KWCLUB_CATE_ID}>
                    </th>
                    <th>
                    <!--開課教師-->
                    <{$smarty.const._MD_KWCLUB_TEACHER_ID}>
                    </th>
                    <th>
                        <!--上課地點-->
                        <{$smarty.const._MD_KWCLUB_PLACE_ID}>
                    </th>
                    <th>
                    <!--上課星期-->
                    <{$smarty.const._MD_KWCLUB_CLASS_WEEK}>
                    </th>
                    <th>
                        <!--招收對象-->
                        <{$smarty.const._MD_KWCLUB_CLASS_GRADE}>
                    </th>
                    <th>
                    <!--上課日期-->
                    <{$smarty.const._MD_KWCLUB_CLASS_DATE}>
                    </th>
                    <th>
                        <!--上課時間-->
                        <{$smarty.const._MD_KWCLUB_CLASS_TIME}>
                    </th>
                
                    <th>
                    <!--社團學費-->
                    <{$smarty.const._MD_KWCLUB_CLASS_MONEY}>
                    </th>
                    <th>
                    <!--額外費用-->
                    <{$smarty.const._MD_KWCLUB_CLASS_FEE}>
                    </th>
                    <th>
                        <!--招收人數-->
                        <{$smarty.const._MD_KWCLUB_CLASS_MENBER}>
                    </th>
                    <th>
                        <!--已報名人數-->
                        <{$smarty.const._MD_KWCLUB_CLASS_REGNUM}>
                    </th>
                    <th>
                        <!--社團備註-->
                        <{$smarty.const._MD_KWCLUB_CLASS_NOTE}>
                        </th>
                    <th><!--社團期別-->
                    <{$smarty.const._MD_KWCLUB_CLASS_YEAR}>
                    </th>
                    
                    <{if $smarty.session.isclubAdmin || $smarty.session.isclubUser }>
                    <th colspan =3>
                        管理
                    </th>
                    <{/if}>
                
                </tr>
            </thead>
            <tbody id="kw_club_class_sort">
                <{foreach from=$all_content item=data}>
                <tr id="tr_<{$data.class_id}>">
                    
                    <td>
                        <!--社團編號-->
                        <{$data.class_num}>
                    </td>
                    <td>
                        <!--社團名稱-->
                        <a href="<{$action}>?class_id=<{$data.class_id}>"><{$data.class_title}></a>
                    </td>
                    <td>
                        <!--社團類型-->
                        <{$data.cate_id}>
                        </td>
                    <td>
                        <!--開課教師-->
                        <{$data.teacher_id}>
                    </td>
                    <td>
                        <!--上課地點-->
                        <{$data.place_id}>
                        </td>
                    <td>
                        <!--上課星期-->
                        <{$data.class_week}>
                    </td>
                    <td>
                        <!--招收對象-->
                        <{$data.class_grade}>
                        </td>
                    <td>
                        <!--上課起始日-->
                        <{$data.class_date_open}>~<br>
                        <!--上課終止日-->
                        <{$data.class_date_close}>
                    </td>
                    <td>
                        <!--起始時間-->
                        <{$data.class_time_start}>~<br>
                        <!--終止時間-->
                        <{$data.class_time_end}>
                    </td>
                    

                    <td>
                        <!--社團學費-->
                        <{$data.class_money}>
                    </td>

                    <td>
                        <!--額外費用-->
                        <{$data.class_fee}>
                    </td>

                    <td>
                        <!--招收人數-->
                        <{$data.class_menber}>
                        </td>
                    <td>
                        <!--已報名人數-->
                        <{$data.class_regnum}>
                        <{if $data.class_regnum >= $data.class_menber}>
                            <font color='red'>滿</font> 
                        <{/if}>
                    </td>
                    <td>
                        <{if $data.class_regnum >= $data.class_menber}>
                        <font color='red'>後補報名中..</font> 
                        <{/if}>
                        <!--社團備註-->
                        <{$data.class_note}>
                        </td>
            <td>
                        <!--社團期別--><!--ID-->
                        <{$data.class_year}>
                        (<{$data.class_id}>)
                    </td>
                    <{if $smarty.session.isclubAdmin || $uid == $data.class_uid}>
                    
                    <td>
                        <!--是否啟用-->
                        <{$data.class_isopen}>
                    </td>
                    <td>
                        <!--UID-->
                        <{$data.class_uid}>
                        <{$data.class_uidname}>
                        
                        </td>
                    <td>
                        
                        <a href="<{$xoops_url}>/modules/kw_club/main.php?op=class_form&class_id=<{$data.class_id}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                        <{if $data.class_regnum == 0}>
                        <a href="javascript:delete_class_func(<{$data.class_id}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                        <{/if}>
                    </td>
                    <{/if}>
                </tr>
                <{/foreach}>
            </tbody>
        </table>
        
        <{$bar}>
    <{else}>
        <div class="alert alert-warning">
            <{$smarty.const._MD_KWCLUB_EMPTY_CLUB}>
        </div>
    <{/if}>

    <{if $smarty.session.isclubAdmin}>
        <div class="jumbotron text-center">
            <a href="<{$xoops_url}>/modules/kw_club/main.php?op=class_form" class="btn btn-info"><{$smarty.const._MD_KWCLUB_ADD_CLUB}></a>
        </div>
    <{/if}>

<{/if}>







