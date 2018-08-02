
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
                    <span class="editable" id="reg_name_<{$data.reg_sn}>" data-toggle="tooltip" data-placement="bottom" title="於 <{$data.reg_datetime}>，從 <{$data.reg_ip}>，報名編號：<{$data.reg_sn}>"><{$data.reg_name}></span>
                </td>
                <td class="text-center">

                        <{if $data.reg_grade=='幼'}>
                            <span class="editable" id="reg_grade_<{$data.reg_sn}>"><{$data.reg_grade}>兒園</span><span class="editable" id="reg_class_<{$data.reg_sn}>"><{$data.reg_class}></span>
                        <{else}>
                            <span class="editable" id="reg_grade_<{$data.reg_sn}>"><{$data.reg_grade}>年</span><span class="editable" id="reg_class_<{$data.reg_sn}>"><{$data.reg_class}></span>
                        <{/if}>

                </td>

                <{if $smarty.session.isclubAdmin}>
                    <td class="text-center">
                        <{ if $data.reg_isreg==$smarty.const._MD_KWCLUB_OFFICIALLY_ENROLL}>
                            <span class="editable" id="reg_isreg_<{$data.reg_sn}>" style='color: rgb(6, 2, 238)'><{$data.reg_isreg}></span>
                        <{else}>
                            <span class="editable" id="reg_isreg_<{$data.reg_sn}>" style='color: rgb(35, 97, 35)'><{$data.reg_isreg}></span>
                        <{/if}>
                    </td>
                    <td class="text-center">

                        <a href="register.php?op=update_reg_isfee&amp;reg_isfee=<{if $data.reg_isfee==1}>0<{else}>1<{/if}>&amp;reg_sn=<{$data.reg_sn}>" data-toggle="tooltip" data-placement="top" title="點此改為<{if $data.reg_isfee==1}>「未繳費」<{else}>「已繳費」<{/if}>"><{$data.reg_isfee_pic}></a>

                        <span data-toggle="tooltip" data-placement="bottom" <{if $data.class_fee}>style="color: #ad168a;"  title="<{ if $data.reg_isfee==1}>已繳<{else}>未繳<{/if}>（學費）<{$data.class_money}>元 + （教材費）<{$data.class_fee}>元"<{/if}>><{$data.class_pay}> 元</span>
                    </td>
                    <td class="text-center">
                        <span class="editable" id="reg_uid_<{$data.reg_sn}>"><{$data.reg_uid}></span>
                    </td>
                    <td class="text-center">
                        <a href="index.php?reg_uid=<{$data.reg_uid}>&op=myclass" class="btn btn-xs btn-info">詳情</a>
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
<div class="text-right">
    上表中有標<span class="editable">藍色底線</span>者，可直接點擊編輯修改
</div>