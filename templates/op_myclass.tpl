<h2>查詢我報名過的社團</h2>

<form action="index.php" method="post" id="myForm" class="myForm form-horizontal" role="form" style="margin: 20px auto 50px;">
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon3">請選擇期別</span>
        <select name="club_year" class="form-control">
            <{if $arr_year}>
                <{foreach from=$arr_year item=year}>
                    <option value="<{$year}>" <{if $club_year==$year}>selected<{/if}>><{$year}></option>
                <{/foreach}>
            <{else}>
                <option value="">目前沒有任何社團期別</option>
            <{/if}>
        </select>
        <span class="input-group-addon" id="basic-addon3">請輸入身分證字號</span>
        <input type="text" name="reg_uid" class="form-control" placeholder="請輸入身分證字號" value="<{$reg_uid}>">
        <span class="input-group-btn">
            <input type="hidden" name="op" value="myclass">
            <button class="btn btn-primary" type="submit">查詢</button>
        </span>
    </div>
</form>

<{if $reg_uid}>
    <{if $reg_name}>
        <h2>
            <span style="color: rgb(124, 58, 58);"><{$reg_name}></span>的社團列表
            <small>（共 <{$total}> 筆）</small>
        </h2>

        <table class="table table-bordered table-hover table-condensed">
            <thead>
                <tr class="success">
                    <th class="text-center">社團名稱</th>
                    <th class="text-center">上課時間</th>
                    <th class="text-center">社團學費</th>
                    <th class="text-center">報名日期</th>
                    <th class="text-center">是否後補</th>
                    <th class="text-center">功能</th>
                </tr>
            </thead>
            <tbody>
                <{foreach from=$arr_reg key=sn item=data}>
                    <tr>
                        <td>
                            <a href="index.php?class_id=<{$data.class_id}>"><{$data.class_title}></a>
                        </td>
                        <td>                      
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

                        <!-- 學費 -->
                        <td nowrap class="text-center">
                            <span data-toggle="tooltip" data-placement="bottom" <{if $data.class_fee}>style="color: #ad168a;"  title="<{$data.class_money}>元（學費） + <{$data.class_fee}>元（教材費）"<{/if}>>
                                <{$data.class_pay}>元
                            </span>
                            （<{ if $data.reg_isfee==1}><span style='color: green'>已繳費</span> <{else}><span style='color: red'>未繳費</span><{/if}>）
                        </td>

                        <!--報名時間-->
                        <td class="text-center">
                            <{$data.reg_datetime}>
                        </td>
                        
                        <!-- 是否後補 -->
                        <td class="text-center">
                            <{ if $data.reg_isreg=='正取'}>
                                <span style='color: rgb(6, 2, 238)'><{$data.reg_isreg}></span>
                            <{else}>
                                <span style='color: rgb(35, 97, 35)'><{$data.reg_isreg}></span>
                            <{/if}>
                        </td>
                        <td class="text-center">
                            <{if $today < $data.end_date }>
                                <a href="javascript:delete_reg_func(<{$data.reg_sn}>);" class="btn btn-danger btn-xs" >取消報名</a>
                            <{/if}>
                        </td>
                    </tr>
                <{/foreach}>
                <tr>
                    <td colspan="2" align='center'>總繳費金額</td>
                    <td  colspan="6" align='right'>總共 <{$money}> 元，已繳 <span style='color: green'><{$in_money}></span> 元，未繳 <span style='color: red'><{$un_money}></span> 元</td>
                </tr>
            </tbody>
        </table>
    <{else}>    
        <div class="alert alert-danger"><{$club_year}>期中，查無任何 <{$reg_uid}> 的報名資料</div>
    <{/if}>
<{/if}>


