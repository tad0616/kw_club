<{$toolbar}>

<h2><{$smarty.const._MD_KWCLUB_INFO_SETUP}></h2>

<form class="form-horizontal" name="classform" id="classform" action="config.php" method="post" enctype = "multipart/form-data">

    <{if $club_year}>
        <h3><{$smarty.const._MD_KWCLUB_NOW_YEAR}><{$club_year}></h3>   
        <input type="hidden" name="club_year" id="club_year" value="<{$club_year}>">
        <input type="hidden" name="op" id="op" value="update_config">
    <{else}>                
        <div class="form-group">
            <label for="club_year" class="col-md-2 control-label"><{$smarty.const._MD_KWCLUB_YEAR}></label>
            <div class="col-md-10">
                <select class="form-control validate[required]" name="club_year" id="club_year" title="<{$smarty.const._MD_KWCLUB_YEAR}>">
                    <{foreach from=$arr_semester item=semester}>
                        <option value="<{$semester}>" <{if $semester==$club_year}>selected<{/if}>><{$semester}></option>
                    <{/foreach}>	
                </select>
                <span id="helpBlock" class="help-block">
                    <{$smarty.const._MD_KWCLUB_YEAR_HELP}>
                </span>
            </div>
        </div>
        <input type="hidden" name="op" id="op" value="set_config">
    <{/if}>

    <div class="form-group">
        <label for="class_date_open" class="col-md-2 control-label"><{$smarty.const._MD_KWCLUB_APPLY_DATE}></label>
        <div class="col-md-10">
            <div class="input-group">
                <input type="text" name="club_start_date" id="class_time_start" value="<{$club_start_date}>" class = "form-control validate[required]" onclick="WdatePicker({maxDate:'#F{$dp.$D(\'class_time_end\');}'})">
                <span class="input-group-addon"><{$smarty.const._MD_KWCLUB_APPLY_FROM_TO}></span>
                <input type="text" name="club_end_date" id="class_time_end"  value="<{$club_end_date}>" class = "form-control validate[required]" onclick="WdatePicker({minDate:'#F{$dp.$D(\'class_time_start\',{d:1});}'})">
            </div>
        </div>
    </div>

    <!--    
    <div class="form-group">
        <label for="class_isopen" class="col-md-2 control-label">報名方式</label>
        <div class="col-md-10">
            <label class="radio-inline">
                <input type='radio' name='club_isfree' id='class_isopen1' title='自由報名' value='0' <{if $club_isfree!=1}>checked<{/if}>>
                自由報名（不登入可報名）
            </label>
            <label class="radio-inline">
                <input type='radio' name='club_isfree' id='class_isopen2' title='登入報名' value='1' <{if $club_isfree==1}>checked<{/if}>>
                登入報名（須安裝單位名冊模組，上傳報名者相關資料）
            </label>
        </div>
    </div> 
   -->
   
    <!-- 報名方式：暫定為自由報名 -->
    <input type='hidden' name='club_isfree' value='0'>

    <div class="form-group">
        <label for="club_backup_num" class="col-md-2 control-label"><{$smarty.const._MD_KWCLUB_BACKUP_NUM}></label>
        <div class="col-md-5">
            <select class="form-control validate[required]" size="1" class = 'form-control col-sm-6' name="club_backup_num" id="club_backup_num" title="<{$smarty.const._MD_KWCLUB_BACKUP_NUM}>">
                <{foreach from=$arr_num item=num}>
                    <option value="<{$num}>" <{if $club_backup_num==$num}>selected<{/if}>><{$num}>人</option>
                <{/foreach}>
            </select>
        </div>
        <div class="col-md-5">
            <button type='submit' class='btn btn-primary'><{$smarty.const._TAD_SAVE}><{$smarty.const._MD_KWCLUB_INFO_SETUP}></button>
            <{if $club_year}>
                <a href="javascript:delete_club_func(0);" class="btn btn-danger"><{$smarty.const._TAD_RESET}><{$smarty.const._MD_KWCLUB_INFO_SETUP}></a>
            <{/if}>
        </div>
    </div>

</form>



<{includeq file="$xoops_rootpath/modules/kw_club/templates/op_kw_club_info_list.tpl"}>

