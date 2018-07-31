<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr class="info">
            <!--社團名稱-->
            <{if !$class_id}>
                <th class="text-center">
                    <{$smarty.const._MD_KWCLUB_CLASS_TITLE}>
                </th>
            <{/if}>
            <!--報名者姓名-->
            <th class="text-center">
                <{$smarty.const._MD_KWCLUB_REG_NAME}>
            </th>
            <!--報名者班級-->
            <th class="text-center">
                <{$smarty.const._MD_KWCLUB_REG_CLASS}>
            </th>

            <{if $smarty.session.isclubAdmin}>
                <!--是否後補-->
                <th class="text-center">
                    <{$smarty.const._MD_KWCLUB_REG_ISREG}>
                </th>
                <!--是否繳費-->
                <th class="text-center">
                    <{$smarty.const._MD_KWCLUB_REG_ISFEE}>
                </th>
                <!--報名者ID-->
                <th class="text-center">
                    <{$smarty.const._MD_KWCLUB_REG_UID}>
                </th>
                <th class="text-center">
                    <{$smarty.const._TAD_FUNCTION}>
                </th>
            <{/if}>
        </tr>
    </thead>
    <tbody>
        <{foreach from=$all_reg item=data}>
            <tr>
                <!--社團名稱-->
                <{if !$class_id}>
                    <td>
                        <a href="index.php?class_id=<{$data.class_id}>" data-toggle="tooltip" data-placement="bottom" title="<{$data.class_id}>"><{$data.class_title}></a>
                    </td>
                <{/if}>
                <td class="text-center">
                    <span data-toggle="tooltip" data-placement="bottom" title="於 <{$data.reg_datetime}>，從 <{$data.reg_ip}>，報名編號：<{$data.reg_sn}>"><{$data.reg_name}></span>
                </td>
                <td class="text-center">
                    <{if $data.reg_grade=='幼'}>                          
                        <{$data.reg_grade}>兒園<{$data.reg_class}>
                    <{else}>                        
                        <{$data.reg_grade}>年<{$data.reg_class}>
                    <{/if}>                        
                </td>

                <{if $smarty.session.isclubAdmin}>
                    <td class="text-center">
                        <{ if $data.reg_isreg=='正取'}>
                            <span style='color: rgb(6, 2, 238)'><{$data.reg_isreg}></span>
                        <{else}>
                            <span style='color: rgb(35, 97, 35)'><{$data.reg_isreg}></span>
                        <{/if}>
                    </td>
                    <td class="text-center">                        
                        <span data-toggle="tooltip" data-placement="bottom" <{if $data.class_fee}>style="color: #ad168a;"  title="<{ if $data.reg_isfee==1}>已繳<{else}>未繳<{/if}>（學費）<{$data.class_money}>元 + （教材費）<{$data.class_fee}>元"<{/if}>>
                            <{$data.reg_isfee_pic}>
                            <{$data.class_pay}> 元
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="register.php?op=myclass&uid=<{$data.reg_uid}>"><{$data.reg_uid}></a>
                    </td>
                    <td class="text-center">
                        <a href="register.php?reg_sn=<{$data.reg_sn}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                        <a href="javascript:delete_reg_func(<{$data.reg_sn}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                    </td>
                <{/if}>
            </tr>
        <{foreachelse}>
            <tr>
                <td colspan=18>此期沒有人報名！</td>
            </tr>
        <{/foreach}>
    </tbody>
</table>
