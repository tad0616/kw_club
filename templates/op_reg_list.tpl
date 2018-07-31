今天：<{$today}> <{$title}>

<{if $arr_year}>
    <div class="alert alert-info" style="margin: 10px auto;"><{$smarty.const._MD_KWCLUB_SELECT_YEAR}>
        <select name="club_year" id="club_year" onChange="location.href='register.php?club_year=' + $('#club_year').val() + '&review=' + $('#review').val() ;">
            <option value=""></option>
            <{foreach from=$arr_year item=year}>
                <option value="<{$year}>" <{if $club_year==$year}>selected<{/if}>><{$year}></option>
            <{/foreach}>
        </select>
        <select name="review" id="review" onChange="location.href='register.php?club_year=' + $('#club_year').val() + '&review=' + $('#review').val() ;">
            <option value="reg_sn" <{if $review=='reg_sn'}>selected<{/if}>>依報名排序</option>
            <option value="class_id" <{if $review=='class_id'}>selected<{/if}>>依社團排序</option>        
            <option value="grade" <{if $review=='grade'}>selected<{/if}>>依年級排序</option>
            <option value="reg_uid" <{if $review=='reg_uid'}>selected<{/if}>>依報名者排序</option>        
        </select>
    </div>
<{else}>
    <div class="alert alert-danger">
        <{$smarty.const._MD_KWCLUB_NEED_CONFIG}>
    </div>
<{/if}>




<div align="right">
    <a href="register.php?op=reg_uid&club_year=<{$club_year}>" class="btn btn-info">繳費統計模式</a>
    <a href="excel.php?club_year=<{$club_year}>&review=<{$review}>" class="btn btn-info">所有報名列表匯出excel</a>
</div>  


<h2><{if $club_year}><{$club_year}>期<{/if}>社團報名列表<small>（共 <{$total}> 筆報名資料）</small></h2>

<{includeq file="$xoops_rootpath/modules/kw_club/templates/sub_kw_club_reg_list_table.tpl"}>


<{$bar}>


<script type='text/javascript'>
    function xoopsFormValidate_regform() { 
        var myform = window.document.regform; 
        var hasSelected = false; 
        var selectBox = myform.class_title;
        for (i = 0; i < selectBox.options.length; i++) { 
            if (selectBox.options[i].selected == true && selectBox.options[i].value != '') 
            { 
                hasSelected = true; 
                break; 
            } 
        }
        if (!hasSelected) { 
            window.alert("請輸入社團名稱"); 
            selectBox.focus(); 
            return false; 
        }

        if (myform.reg_uid.value == "") { window.alert("請輸入報名者身分證字號"); myform.reg_uid.focus(); return false; }
        if (myform.reg_name.value == "") { window.alert("請輸入報名者名稱"); myform.reg_name.focus(); return false; }
        if (myform.reg_grade.value == "") { window.alert("請輸入報名者年級"); myform.reg_grade.focus(); return false; }
        if (myform.reg_class.value == "") { window.alert("請輸入報名者班級"); myform.reg_class.focus(); return false; }
        if (myform.reg_isreg.value == "") { window.alert("請輸入是否後補"); myform.class_isreg.focus(); return false; }
        if (myform.reg_isfee.value == "") { window.alert("請輸入是否繳費"); myform.class_isfee.focus(); return false; }
        
        return true;
    }
</script>



