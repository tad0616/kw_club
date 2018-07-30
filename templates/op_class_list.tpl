<h1><{$smarty.const._MD_KWCLUB}></h1>

<!-- 社團期別下拉選單 -->
<{if $arr_year}>
    <div class="alert alert-info" style="margin: 10px auto;"><{$smarty.const._MD_KWCLUB_SELECT_YEAR}>
        <select name="select_year" onChange="location.href='index.php?year='+this.value">
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
    <div class="row">
        <div class="col-sm-10">
            <h2>
                <span style="color:blue;"><{$smarty.session.club_year}></span><{$smarty.const._MD_KWCLUB_LIST}>
                <small>（共 <{$total}> 筆活動）</small> 
            </h2>
            <h4>
                <{$smarty.const._MD_KWCLUB_APPLY_DATE}><{$smarty.const._TAD_FOR}><span style="color:rgb(190, 63, 4);"><{$smarty.session.club_start_date|date_format:"%Y/%m/%d %H:%M"}> ~ <{$smarty.session.club_end_date|date_format:"%Y/%m/%d %H:%M"}></span>
            </h4>
        </div>
        <div class="col-sm-2" style="padding-top: 40px;">
            <{if $smarty.session.isclubAdmin || $smarty.session.isclubUser}>
                <a href="club.php" class="btn btn-primary btn-block"><{$smarty.const._MD_KWCLUB_ADD_CLUB}></a>
            <{/if}>
        </div>
    </div>

    <{if $all_class_content}>
        <table class="table table-bordered table-hover table-condensed">
            <thead>
                <tr class="success">
                    <!--社團編號-->
                    <th class="text-center">
                        <{$smarty.const._MD_KWCLUB_CLASS_NUM}>
                    </th>

                    <!--社團名稱-->
                    <th class="text-center">
                        <{$smarty.const._MD_KWCLUB_CLASS_TITLE}>
                    </th>

                    <!--上課日期-->
                    <th class="text-center">
                        <{$smarty.const._MD_KWCLUB_CLASS_DATE}>
                    </th>

                    <!--招收對象-->
                    <th class="text-center">
                        <{$smarty.const._MD_KWCLUB_CLASS_GRADE}>
                    </th>

                    <!--社團學費-->
                    <th nowrap class="text-center">
                        <{$smarty.const._MD_KWCLUB_CLASS_MONEY}>
                    </th>
                    
                    <!--招收人數-->
                    <th nowrap class="text-center">
                        招收
                    </th>

                    <!--已報名人數-->
                    <th nowrap class="text-center">
                        已報
                    </th>

                    <th class="text-center">
                        功能
                    </th>                
                </tr>
            </thead>
            <tbody id="kw_club_class_sort">
                <{foreach from=$all_class_content item=data}>
                    <tr id="tr_<{$data.class_id}>">                    
                        <td title="<{$data.class_id}>" class="text-center" <{if $data.class_note or $data.class_regnum >= $data.class_menber}>rowspan="2"<{/if}>>
                            <!--社團編號-->
                            <{$data.class_num}>
                            <div>
                                <span class="label label-info"><{$data.cate_id}></span>   
                            </div> 
                        </td>

                        <!--社團名稱-->    
                        <td>     
                            <!--是否啟用-->
                            <{$data.class_isopen}>                                   
                            <a href="index.php?class_id=<{$data.class_id}>"><{$data.class_title}></a>
                            <div style="font-size: 0.9em;">
                                <i class="fa fa-user-circle-o" aria-hidden="true" title="<{$smarty.const._MD_KWCLUB_TEACHER_ID}>"></i>
                                <{$data.teacher_id}>
                                <i class="fa fa-map-marker" aria-hidden="true" title="<{$smarty.const._MD_KWCLUB_PLACE_ID}>"></i>
                                <{$data.place_id}>
                            </div>
                        </td>


                        <!--上課日-->
                        <td nowrap>                            
                            <span class="number_b">
                                <{$data.class_date_open|date_format:"%Y/%m/%d"}>
                            </span>至
                            <span class="number_b">
                                <{$data.class_date_close|date_format:"%Y/%m/%d"}>
                            </span>
                            <!--起始時間-->
                            <div>                            
                                <!--上課星期-->
                                <{if $data.class_week=="一、二、三、四、五"}>
                                    每星期<span class="text_g">一到五</span>的
                                <{else}>
                                    每星期<span class="text_g"><{$data.class_week}></span>的
                                <{/if}>
                                <span class="number_o">
                                    <{$data.class_time_start|date_format:"%H:%M"}>
                                </span>
                                至
                                <span class="number_o">
                                    <{$data.class_time_end|date_format:"%H:%M"}>
                                </span>
                            </div>
                        </td>

                        <!--招收對象-->
                        <td>
                            <{if $data.class_grade=="幼、一、二、三、四、五、六"}>
                                幼兒園+所有年級
                            <{elseif $data.class_grade=="一、二、三、四、五、六"}>
                                一至六年級
                            <{elseif $data.class_grade=="一、二、三"}>
                                一至三年級
                            <{elseif $data.class_grade=="一、二"}>
                                低年級
                            <{elseif $data.class_grade=="三、四"}>
                                中年級
                            <{elseif $data.class_grade=="五、六"}>
                                高年級
                            <{else}>
                                <{$data.class_grade}>
                            <{/if}>
                        </td>

                        <!--社團學費-->
                        <td class="text-center">
                            <span data-toggle="tooltip" data-placement="bottom" <{if $data.class_fee}>style="color: #2679d3;"  title="<{$data.class_money}>元（學費） + <{$data.class_fee}>元（教材費）"<{/if}>>
                                <{$data.class_pay}>
                            </span>
                        </td>

                        <!--招收人數-->
                        <td class="text-center">
                            <{$data.class_menber}>
                        </td>

                        <!--已報名人數-->
                        <td class="text-center">                            
                            <{if $data.class_regnum >= $data.class_menber}>
                                <span class="circle" data-toggle="tooltip" data-placement="bottom" title="已報名人數 <{$data.class_regnum}> 人">滿</span> 
                            <{else}>
                                <{$data.class_regnum}>
                            <{/if}>
                        </td>

                        <td class="text-center">       
                            <{if $smarty.session.isclubAdmin || $uid == $data.class_uid}>                     
                                <a href="club.php?class_id=<{$data.class_id}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                                <{if $data.class_regnum == 0}>
                                    <div>
                                        <a href="javascript:delete_class_func(<{$data.class_id}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                                    </div>
                                <{/if}>                        
                            <{else}>                         
                                <a href="index.php?class_id=<{$data.class_id}>" class="btn btn-xs btn-info">詳情</a>
                            <{/if}>
                        </td>
                    </tr>
                    <!--社團備註-->
                    <{if $data.class_note or $data.class_regnum >= $data.class_menber}>
                        <tr>
                            <td colspan=11 style="font-size: 0.9em; color: rgb(151, 3, 107)">
                                <i class="fa fa-commenting" aria-hidden="true"></i>
                                <{$data.class_note}>
                                <{if $data.class_regnum >= $data.class_menber}>
                                    <span style="color:red;">（後補報名中...）</span> 
                                <{/if}>
                            </td>
                        </tr>
                    <{/if}>
                <{/foreach}>
            </tbody>
        </table>
        
        <{$bar}>
    <{else}>
        <div class="alert alert-warning">
            <{$smarty.const._MD_KWCLUB_EMPTY_CLUB}>
        </div>
    <{/if}>



<{/if}>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>