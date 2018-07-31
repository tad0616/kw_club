<{if $smarty.session.isclubAdmin}>
    <{if $arr_year}>
        <div class="alert alert-info" style="margin: 10px auto;"><{$smarty.const._MD_KWCLUB_SELECT_YEAR}>
            <select name="club_year" onChange="location.href='index.php?op=myclass&reg_uid=<{$reg_uid}>&club_year='+this.value;">
                <{if $arr_year}>
                    <{foreach from=$arr_year key=year item=year_txt}>                
                        <option value="<{$year}>" <{if $club_year==$year}>selected<{/if}>><{$year_txt}></option>                
                    <{/foreach}>
                <{else}>
                    <option value="">目前沒有任何社團期別</option>
                <{/if}>
            </select>
        </div>
    <{else}>
        <div class="alert alert-danger">
            <{$smarty.const._MD_KWCLUB_NEED_CONFIG}>
        </div>
    <{/if}>

    <div align="right">
        <a href="register.php" class="btn btn-info">報名統計列表模式</a>
        <a href="pdf.php?year=<{$smarty.session.club_year}>" class="btn btn-info">匯出繳費pdf</a>
    </div>

    <h2>所有報名繳費列表<small>（共  筆）</small></h2>

    <div id="kw_club_class_save_msg"></div>

    <{foreach from=$reg_uid_all key=sn item=uid}>
    <span style="color:blue"><{$reg_name_all[$uid]}> </span>的報名結果 <{$uid}>
    <table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr class="">
        <th>社團年度</th>
        <th>社團名稱</th>
        <th>社團學費</th>
        <th>報名日期</th>
        <th><{$smarty.const._MD_KWCLUB_REG_ISREG}></th>
        <th>是否繳費</th>
        <th>取消報名</th>
        </tr>
    </thead>
    <tbody id="kw_club_class_sort">
        <{foreach from=$arr_reg[$uid] item=data}>
        <tr id="tr_<{$data.class_id}>">         
        <td>
        <!--社團年度-->
            <{$data.club_year}>
        </td>
        <td><a href="index.php?class_id=<{$data.class_id}>"> <{$data.class_title}></a>
        </td>
        <td><{$data.class_money}>(額外費用<{$data.class_fee}>)</td><!--學費-->
        <td><{$data.reg_datetime}></td><!--報名時間-->
        <td>
            <{ if $data.reg_isreg=='正取'}>
                <span style='color: rgb(6, 2, 238)'><{$data.reg_isreg}></span>
            <{else}>
                <span style='color: rgb(35, 97, 35)'><{$data.reg_isreg}></span>
            <{/if}>
        </td>
        <td>
            <{ if $data.reg_isfee==1}>
                <span style='color: green'>已繳費</span>    
            <{else}>
                <span style='color: red'>未繳費</span>
            <{/if}>    
        </td>
        <td>
        
            <{if !($today > $end_day) }>
            <a href="javascript:delete_reg_func(<{$data.reg_sn}>);" class="btn btn-danger" >取消報名</a>
        <{/if}>
        </td>
        </tr>
        <{foreachelse}>
            <tr>
                <td colspan=4>你目前沒有報名的社團!!</td>
            </tr>
        <{/foreach}>
        <tr>
            <td colspan="2" align='center'>總繳費金額</td>
            <td  colspan="6" align='right'>總共<{$money_all[$uid]}>元，已繳<span style='color: green'><{$in_money_all[$uid]}></span>元，未繳<span style='color: red'><{$un_money_all[$uid]}></span>元</td></tr>
        </tbody>
    </table>
    <{/foreach}>

<{/if}>