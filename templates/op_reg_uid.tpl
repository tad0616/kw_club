<{if $smarty.session.isclubAdmin}>
    <{if $arr_year}>
        <div class="alert alert-info" style="margin: 10px auto;"><{$smarty.const._MD_KWCLUB_SELECT_YEAR}>
            <select name="club_year" onChange="location.href='register.php?op=reg_uid&club_year='+this.value;">
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
        <a href="register.php" class="btn btn-primary"><i class="fa fa-table" aria-hidden="true"></i>
            報名列表模式</a>
        <a href="pdf.php?year=<{$smarty.session.club_year}>" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i>
            匯出繳費pdf</a>
    </div>

    <h3><span class="club_year_text"><{$club_year_text}></span>所有報名繳費列表<small>（共 <{$total}> 筆）</small></h3>
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr class="success">
                <th>社團年度</th>
                <th>社團名稱</th>
                <th>社團學費</th>
                <th>報名日期</th>
                <th><{$smarty.const._MD_KWCLUB_REG_ISREG}></th>
                <th>是否繳費</th>
                <th>取消報名</th>
            </tr>
        </thead>
        <{foreach from=$reg_all key=reg_uid item=reg}>
            <tbody>
                <tr>
                    <th colspan=7>
                        <span style="color:blue"><{$reg.name}> </span>的報名結果<small>（<{$reg_uid}>）</small>
                    </th>
                </tr>

                <{foreach from=$reg.data key=class_id item=data}>
                    <tr>
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
                                <a href="javascript:delete_reg_func(<{$data.reg_sn}>);" class="btn btn-xs btn-danger">取消報名</a>
                            <{/if}>
                        </td>
                    </tr>
                <{/foreach}>
                <tr>
                    <td colspan="7" align='right'>總共 <{$reg.money}> 元，已繳 <span style='color: green'><{$reg.in_money}></span> 元，未繳 <span style='color: red'><{$reg.un_money}></span> 元</td>
                </tr>
            </tbody>
        <{/foreach}>
    </table>
<{/if}>