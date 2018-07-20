<form action="club_info.php" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
             
    <div class="form-group">
        <!--社團期別-->
        <label for="club_year" class="col-md-2 control-label"><{$smarty.const._MD_KWCLUB_YEAR}></label>
        <div class="col-md-4">
            <{if $club_year}>
                <div class="form-control-static">                
                    <{$club_year}>
                    <input type="hidden" name="club_year" id="club_year" value="<{$club_year}>">
                </div>
            <{else}>   
                <select class="form-control validate[required]" name="club_year" id="club_year" title="<{$smarty.const._MD_KWCLUB_YEAR}>">
                    <{foreach from=$arr_semester key=semester item=semester_txt}>
                        <option value="<{$semester}>" <{if $semester==$club_year}>selected<{/if}>><{$semester_txt}></option>
                    <{/foreach}>	
                </select>
            <{/if}>
        </div>

        
        <!--是否啟用-->
        <label class="col-sm-2 control-label">
            <{$smarty.const._MD_KWCLUB_ENABLE}>
        </label>
        <div class="col-sm-4">            
            <label class="radio-inline">
                <input type="radio" name="club_enable" id="club_enable_1" value="1" <{if $club_enable == "1"}>checked="checked"<{/if}>><{$smarty.const._YES}>
            </label>
            <label class="radio-inline">
                <input type="radio" name="club_enable" id="club_enable_0" value="0" <{if $club_enable != "1"}>checked="checked"<{/if}>><{$smarty.const._NO}>
            </label>
        </div>
    </div>

    <!--報名起訖-->
    <div class="form-group">
        <label for="class_date_open" class="col-md-2 control-label"><{$smarty.const._MD_KWCLUB_APPLY_DATE}></label>
        <div class="col-md-10">
            <div class="input-group">
                <input type="text" name="club_start_date" id="club_start_date" value="<{$club_start_date}>" class = "form-control validate[required]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm', startDate:'%y-%M-%d %H:%m'})">
                <span class="input-group-addon"><{$smarty.const._MD_KWCLUB_APPLY_FROM_TO}></span>
                <input type="text" name="club_end_date" id="club_end_date"  value="<{$club_end_date}>" class = "form-control validate[required]" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',minDate:'#F{$dp.$D(\'club_start_date\',{d:1});}'})">
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
        
        <!--候補人數-->
        <label for="club_backup_num" class="col-md-2 control-label">
            <{$smarty.const._MD_KWCLUB_BACKUP_NUM}>
        </label>
        <div class="col-md-4">
            <select class="form-control validate[required]" size="1" class = 'form-control col-sm-6' name="club_backup_num" id="club_backup_num" title="<{$smarty.const._MD_KWCLUB_BACKUP_NUM}>">
                <{foreach from=$arr_num item=num}>
                    <option value="<{$num}>" <{if $club_backup_num==$num}>selected<{/if}>><{$num}>人</option>
                <{/foreach}>
            </select>
        </div>

        <div class="col-sm-6">
            <!--設定者-->
            <input type='hidden' name="club_uid" value="<{$club_uid}>">

            <{$token_form}>

            <input type="hidden" name="op" value="<{$next_op}>">
            <input type="hidden" name="club_id" value="<{$club_id}>">
            <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}><{$smarty.const._MD_KWCLUB_ADMIN}></button>
        </div>
    </div>

</form>

