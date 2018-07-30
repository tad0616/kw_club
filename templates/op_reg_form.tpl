<h2>報名「<{$class.class_title}>」</h2>
<p>為維護您的報名權益，請務必填寫正確資訊！！否則將取消錄取！</p>


<!--套用formValidator驗證機制-->
<form action="index.php" method="post" id="cateForm" enctype="multipart/form-data" class="myForm form-horizontal" role="form">

    <!--身分證字號-->
    <div class="form-group">
        <label class="col-sm-2 control-label">
            <{$smarty.const._MD_KWCLUB_REG_UID}>
        </label>
        <div class="col-sm-10">
            <input type="text" name="reg_uid" id="reg_uid" class="form-control validate[required]" value="<{$reg_uid}>" placeholder="請填身分證字號">
        </div>
    </div>

    <!--報名者姓名-->
    <div class="form-group">
        <label class="col-sm-2 control-label">
            <{$smarty.const._MD_KWCLUB_REG_NAME}>
        </label>
        <div class="col-sm-10">
            <input type="text" name="reg_name" id="reg_name" class="form-control " value="<{$reg_name}>" placeholder="請填姓名">
        </div>
    </div>

    <!--報名者年級-->
    <div class="form-group">
        <label for="reg_grade" class="col-sm-2 control-label"><{$smarty.const._MD_KWCLUB_REG_GRADE}><span class="caption-required">*</span></label>
        <div class="col-sm-10">
            <{foreach from = $class_grade_arr key=v item=gname}>            
                <label class="radio-inline">
                    <{if $gname=='幼'}>                          
                        <input type='radio' name='reg_grade' id="reg_grade<{$v}>" title='<{$gname}>兒園' value='<{$gname}>' <{if $gname == $reg_grade}>checked="checked"<{/if}>><{$gname}>兒園
                    <{else}>                        
                        <input type='radio' name='reg_grade' id="reg_grade<{$v}>" title='<{$gname}>年級' value='<{$gname}>' <{if $gname == $reg_grade}>checked="checked"<{/if}>><{$gname}>年級
                    <{/if}>
                </label>  
            <{/foreach}>               
        </div>
    </div>

    <!--報名者班級-->
    <div class="form-group">
        <label class="col-sm-2 control-label">
            <{$smarty.const._MD_KWCLUB_REG_CLASS}>
        </label>
        <div class="col-sm-10">
            <{foreach from = $school_class key=v item=cname}>            
                <label class="radio-inline">
                    <input type='radio' name='reg_class' id="reg_class<{$v}>" title='<{$cname}>' value='<{$cname}>' <{if $cname == $reg_class}>checked="checked"<{/if}>><{$cname}>
                </label>  
            <{/foreach}>   
        </div>
    </div>

    <div class="text-center">
        
        <{$reg_token}>

        <!--類型排序-->
        <input type="hidden" name="class_id"  value="<{$class_id}>" >
        <input type="hidden" name="op" value="insert_reg">
        <button type="submit" class="btn btn-primary">以上資料無誤，確定報名！</button>
    </div>
</form>
