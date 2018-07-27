<{if $class_id==""}>
    <h2><{$smarty.const._MD_KWCLUB_ADD_CLUB}><small> <{$smarty.session.club_year}><{$smarty.const._MD_KWCLUB_Y}> (<span style="color:rgb(190, 63, 4);"><{$smarty.session.club_start_date|date_format:"%Y/%m/%d %H:%M"}> ~ <{$smarty.session.club_end_date|date_format:"%Y/%m/%d %H:%M"}></span>)</small></h2>	
<{else}>
    <h2><{$smarty.const._TAD_EDIT}><{$class_title}><{$smarty.const._MD_KWCLUB_CLUB}> <{$class_id}><small> -<{$smarty.session.club_year}><{$smarty.const._MD_KWCLUB_Y}></small></h>	
<{/if}>

<form class="form-horizontal" name="classform" id="classform" action="club.php" method="post" onsubmit="return xoopsFormValidate_classform();" enctype = "multipart/form-data">

    <!-- 社團編號 -->
    <div class="form-group">
        <label for="class_num" class="col-md-2 control-label"><{$smarty.const._MD_KWCLUB_PICK_CLUB}><span class="caption-required">*</span></label>
        <div class="col-md-10">
            <select class="form-control" size="1" name="class_num" id="class_num" title="<{$smarty.const._MD_KWCLUB_CLASS_NUM}>" onChange="location.href='club.php?op=class_form&class_num='+this.value">
                <{if $class_id==""}>                    
                    <option value="<{$num}>"><{$smarty.const._MD_KWCLUB_ADD_CLASS}></option> 
                    <{foreach from=$js_class key=id item=arr_n }>
                        <{if $class_num==$id}>
                            <option value="<{$id}>" selected><{$id}>_<{$arr_n}></option>
                        <{else}>
                            <option value="<{$id}>" ><{$id}>_<{$arr_n}></option>
                        <{/if}>
                    <{/foreach}>	
                <{else}>
                    <option value="<{$class_num}>" ><{$smarty.const._MD_KWCLUB_MODIFY_CLUB}></option>
                <{/if}>                
            </select>

        </div>
    </div>

    <!-- 社團名稱 -->
    <div class="form-group">
        <label for="class_title" class="col-md-2 control-label"><{$smarty.const._MD_KWCLUB_CLASS_TITLE}><span class="caption-required">*</span></label>
        <div class="col-md-10">
            <input class='form-control validate[required]' type='text' name='class_title' title='<{$smarty.const._MD_KWCLUB_CLASS_TITLE}>' id='class_title' value='<{$class_title}>' /> 
        </div>
    </div>

    <!-- 社團類型 -->
    <div class="form-group">
        <label for="cate_id" class="col-md-2 control-label"><{$smarty.const._MD_KWCLUB_CATE_ID}><span class="caption-required">*</span></label>
        <div class="col-md-10">
            <select class="form-control validate[required]" size="1" name="cate_id" id="cate_id" title="<{$smarty.const._MD_KWCLUB_CATE_ID}>">
                <{foreach from=$arr_cate key=id item=arr_c}><!--類型-->
                    <option value="<{$id}>" <{if $cate_id==$id}>selected<{/if}>><{$arr_c}></option>
                <{/foreach}>    
            </select>
        </div>
    </div>

    <!-- 開課教師 -->
    <div class="form-group">
        <label for="teacher_id" class="col-md-2 control-label">開課教師<span class="caption-required">*</span></label>
        <div class="col-md-10">
            <select class="form-control validate[required]" size="1" name="teacher_id" id="teacher_id" title="開課教師">
                <{foreach from=$arr_teacher key="uid" item="teacher" }><!--老師-->
                    <option value="<{$uid}>" <{if $teacher_id==$uid}>selected<{/if}>><{$teacher.name}> (<{$teacher.email}>)</option>
                <{/foreach}>	
            </select>
        </div>
    </div>

    <!-- 上課地點 -->
    <div class="form-group">
            <label for="place_id" class="col-md-2 control-label">上課地點<span class="caption-required">*</span></label>
            <div class="col-md-10">
                <select class="form-control validate[required]" size="1" name="place_id" id="place_id" title="">
                    <{foreach from=$arr_place key="id"  item="arr_p" }><!--老師-->
                        <option value="<{$id}>" <{if $place_id==$id}>selected<{/if}>><{$arr_p}></option>
                    <{/foreach}>	
                </select>
            </div>
        </div>
    <div class="form-group">
        <label for="class_week" class="col-md-2 control-label">上課星期<span class="caption-required">*</span></label>
        <div class="col-md-10">
            <{foreach from = $c_week key=v item=wname}>                
                <label class="checkbox-inline">
                    <input type='checkbox' name='class_week[]' id="class_week<{$v}>" title='<{$v}>' value='<{$wname}>' <{if in_array($wname,$class_week)}>checked="checked"<{/if}>>星期<{$wname}>
                </label>                
            <{/foreach}>
        </div>
    </div>
    <div class="form-group">
        <label for="class_grade" class="col-md-2 control-label">招收對象<span class="caption-required">*</span></label>
        <div class="col-md-10">
            <{foreach from = $c_grade key=v item=gname}>            
                <label class="checkbox-inline">
                    <{if $v==0}>                          
                        <input type='checkbox' name='class_grade[]' id="class_grade<{$v}>" title='<{$v}>' value='<{$gname}>' <{if in_array($gname,$class_grade)}>checked="checked"<{/if}>><{$gname}>兒園
                    <{else}>                        
                        <input type='checkbox' name='class_grade[]' id="class_grade<{$v}>" title='<{$v}>' value='<{$gname}>' <{if in_array($gname,$class_grade)}>checked="checked"<{/if}>><{$gname}>年級
                    <{/if}>
                </label>  
            <{/foreach}>               
        </div>
    </div>
    <div class="form-group">
        <label for="class_menber" class="col-md-2 control-label">招收人數<span class="caption-required">*</span></label>
        <div class="col-md-10"><input class='form-control validate[required]' type='text' name='class_menber' title='招收人數' id='class_menber' size='30' maxlength='255' value='<{$class_menber}>' />
        </div>
    </div>
    <div class="form-group">
        <label for="class_money" class="col-md-2 control-label">社團學費<span class="caption-required">*</span></label>
        <div class="col-md-10">
            <input class='form-control validate[required]' type='text' name='class_money' title='社團學費' id='class_money' size='30' maxlength='255' value='<{$class_money}>' />
        </div>
    </div>
    <div class="form-group">
        <label for="class_fee" class="col-md-2 control-label">額外費用<span class="caption-required"></span></label>
        <div class="col-md-10"><input class='form-control' type='text' name='class_fee' title='額外費用' id='class_fee' size='30' maxlength='255' value='<{$class_fee}>' />
        </div>
    </div>
    <div class="form-group">
        <label for="class_date_open" class="col-md-2 control-label">開課日期<span class="caption-required">*</span></label>
        <div class="input-group col-md-2">
            <input class = "form-control validate[required]" type="text" name="class_date_open" id="class_date_open" size="30" maxlength="25" value="<{$class_date_open}>" 
            onclick="WdatePicker({minDate:'<{$smarty.session.club_end_date}>' })"  >
        </div>
        <label for="class_date_close" class="col-md-2 control-label">終止日期<span class="caption-required">*</span></label>
        <div class="input-group col-md-2">
            <input class = "form-control validate[required]" type="text" name="class_date_close" id="class_date_close" size="30" maxlength="25" value="<{$class_date_close}>"  onclick="WdatePicker({minDate:'#F{$dp.$D(\'class_date_open\',{d:1});}'})" >
        </div>
    </div>

    <div class="form-group">
        <label for="class_date_open" class="col-md-2 control-label">起始時間<span class="caption-required">*</span></label>
        <div class="input-group col-md-2">
            <input class = "form-control validate[required]" type="text" name="class_time_start" id="class_time_start" size="30" maxlength="25" value="<{$class_time_start}>"   
            onclick="WdatePicker({dateFmt:'HH:mm', minTime:'07:00:00', maxTime:'17:30:00' })" >
        </div>
        <label for="class_date_close" class="col-md-2 control-label">終止時間<span class="caption-required">*</span></label>
        <div class="input-group col-md-2">
            <input class = "form-control validate[required]" type="text" name="class_time_end" id="class_time_end" size="30" maxlength="25" value="<{$class_time_end}>"   onclick="WdatePicker({dateFmt:'HH:mm', minTime:'#F{$dp.$D(\'class_time_start\')}',maxTime:'21:30:00'})"  >
        </div>
    </div>

    <div class="form-group">
            <label for="class_note" class="col-md-2 control-label">社團備註</label>
            <div class="col-md-10"><input class='form-control ' type='text' name='class_note' title='社團備註' id='class_note' size='30' maxlength='255' value='<{$class_note}>' />
            </div>
        </div>
    <div class="form-group">
        <label for="class_isopen" class="col-md-2 control-label">是否啟用<span class="caption-required">*</span></label>
        <div class="col-md-10">
            <label class="radio-inline"><input type='radio' name='class_isopen' id='class_isopen1' title='啟用' value='1' checked>啟用&nbsp;</label>
            <label class="radio-inline"><input type='radio' name='class_isopen' id='class_isopen2' title='停用' value='0'>停用&nbsp;</label>
        </div>
    </div>
    <div class="form-group">
        <label for="class_desc" class="col-md-2 control-label">社團簡介<span class="caption-required">*</span></label>
        <div class="col-md-10">      
            <textarea class='form-control validate[required]' name='class_desc' id='class_desc'  title='社團簡介' rows='18' cols='60' class='ckeditor_css'><{$class_desc}></textarea>
            <script>
                    CKEDITOR.replace('class_desc');
            </script>
    </div>
    </div>
   
    
    <{if !empty($class_id) }>
        <input type="hidden" name="class_id" id="class_id" value="<{$class_id}>" />
        <input type="hidden" name="op" id="op" value="update_class" />
    <{else}>
        <input type="hidden" name="op" id="op" value="insert_class" />
    <{/if}>
    <{$token}>
    <div class="form-group">
            <div class="col-md-2"> </div>
            <div class="col-md-10"><span class="form-inline">
                <input type='submit' class='btn btn-default' name=''  id='' value='送出' title='送出'></span>
            </div>
    </div>
</form>


