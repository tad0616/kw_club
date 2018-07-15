<{$toolbar}>

<h2><{$smarty.const._MD_KWCLUB_INFO_SETUP}></h2>

<form class="form-horizontal" name="classform" id="classform" action="config.php" method="post" enctype = "multipart/form-data">

    <{if $semester}>
        <h3><{$smarty.const._MD_KWCLUB_NOW_YEAR}><{$semester}></h3>   
        <input type="hidden" name="kw_club_year" id="kw_club_year" value="<{$kw_club_year}>">
        <input type="hidden" name="op" id="op" value="update_config">
    <{else}>                
        <div class="form-group">
            <label for="kw_club_year" class="col-md-2 control-label"><{$smarty.const._MD_KWCLUB_YEAR}></label>
            <div class="col-md-10">
                <select class="form-control validate[required]" name="kw_club_year" id="kw_club_year" title="<{$smarty.const._MD_KWCLUB_YEAR}>">
                    <{foreach from=$arr_semester item=club_year }><!--期別-->
                        <option value="<{$club_year}>" <{if $kw_club_year==$club_year}>selected<{/if}>><{$club_year}></option>
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
                <input type="text" name="start_reg" id="class_time_start" value="<{$start_reg}>" class = "form-control validate[required]" onclick="WdatePicker({maxDate:'#F{$dp.$D(\'class_time_end\');}'})">
                <span class="input-group-addon"><{$smarty.const._MD_KWCLUB_APPLY_FROM_TO}></span>
                <input type="text" name="end_reg" id="class_time_end"  value="<{$end_reg}>" class = "form-control validate[required]" onclick="WdatePicker({minDate:'#F{$dp.$D(\'class_time_start\',{d:1});}'})">
            </div>
        </div>
    </div>

    <!--    
    <div class="form-group">
        <label for="class_isopen" class="col-md-2 control-label">報名方式</label>
        <div class="col-md-10">
            <label class="radio-inline">
                <input type='radio' name='isfree_reg' id='class_isopen1' title='自由報名' value='0' <{if $isfree_reg!=1}>checked<{/if}>>
                自由報名（不登入可報名）
            </label>
            <label class="radio-inline">
                <input type='radio' name='isfree_reg' id='class_isopen2' title='登入報名' value='1' <{if $isfree_reg==1}>checked<{/if}>>
                登入報名（須安裝單位名冊模組，上傳報名者相關資料）
            </label>
        </div>
    </div> 
   -->
   
    <!-- 報名方式：暫定為自由報名 -->
    <input type='hidden' name='isfree_reg' value='0'>

    <div class="form-group">
        <label for="backup_num" class="col-md-2 control-label"><{$smarty.const._MD_KWCLUB_BACKUP_NUM}></label>
        <div class="col-md-5">
            <select class="form-control validate[required]" size="1" class = 'form-control col-sm-6' name="backup_num" id="backup_num" title="<{$smarty.const._MD_KWCLUB_BACKUP_NUM}>">
                <{foreach from=$arr_num item=num}>
                    <option value="<{$num}>" <{if $backup_num==$num}>selected<{/if}>><{$num}>人</option>
                <{/foreach}>
            </select>
        </div>
        <div class="col-md-5">
            <button type='submit' class='btn btn-primary'><{$smarty.const._TAD_SAVE}><{$smarty.const._MD_KWCLUB_INFO_SETUP}></button>
            <{if $semester}>
                <a href="javascript:delete_club_func(0);" class="btn btn-danger"><{$smarty.const._TAD_RESET}><{$smarty.const._MD_KWCLUB_INFO_SETUP}></a>
            <{/if}>
        </div>
    </div>

</form>


