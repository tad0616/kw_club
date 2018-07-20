<h2>期別列表</h2>
<{if $all_kw_club_info}>
    <{if $isAdmin}>
        <{$delete_kw_club_info_func}>        
    <{/if}>

    <div id="kw_club_info_save_msg"></div>

    <table class="table table-striped table-hover">
        <thead>
            <tr class="info">
                
                <!--社團年度-->
                <th>
                    <{$smarty.const._MD_KWCLUB_YEAR}>
                </th>
                <!--報名起始日-->
                <th>
                    <{$smarty.const._MD_KWCLUB_START_DATE}>
                </th>
                <!--報名終止日-->
                <th>
                    <{$smarty.const._MD_KWCLUB_END_DATE}>
                </th>
                <!--是否登入報名-->
                <th>
                    <{$smarty.const._MD_KWCLUB_ISFREE}>
                </th>
                <!--候補人數-->
                <th>
                    <{$smarty.const._MD_KWCLUB_BACKUP_NUM}>
                </th>
                <!--設定者-->
                <th>
                    <{$smarty.const._MD_KWCLUB_UID}>
                </th>
                <!--設定時間-->
                <th>
                    <{$smarty.const._MD_KWCLUB_DATETIME}>
                </th>
                <!--是否過期-->
                <th>
                    <{$smarty.const._MD_KWCLUB_ENABLE}>
                </th>
                <{if $isAdmin}>
                    <th><{$smarty.const._TAD_FUNCTION}></th>
                <{/if}>
            </tr>
        </thead>

        <tbody id="kw_club_info_sort">
            <{foreach from=$all_kw_club_info item=data}>
                <tr id="tr_<{$data.club_id}>">
                    
                    <!--社團年度-->
                    <td>
                        <{$data.club_year}>
                    </td>

                    <!--報名起始日-->
                    <td>
                        <{$data.club_start_date}>
                    </td>

                    <!--報名終止日-->
                    <td>
                        <{$data.club_end_date}>
                    </td>

                    <!--是否登入報名-->
                    <td>
                        <{$data.club_isfree}>
                    </td>

                    <!--候補人數-->
                    <td>
                        <{$data.club_backup_num}>
                    </td>

                    <!--設定者-->
                    <td>
                        <{$data.club_uid}>
                    </td>

                    <!--設定時間-->
                    <td>
                        <{$data.club_datetime}>
                    </td>

                    <!--是否過期-->
                    <td>
                        <{$data.club_enable}>
                    </td>

                    <{if $isAdmin}>
                        <td>
                            <a href="javascript:delete_kw_club_info_func(<{$data.club_id}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                            <a href="<{$xoops_url}>/modules/kw_club?op=kw_club_info_form&club_id=<{$data.club_id}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                            <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
                        </td>
                    <{/if}>
                </tr>
            <{/foreach}>
        </tbody>
    </table>


    <{if $isAdmin}>
        <div class="text-right">
            <a href="<{$xoops_url}>/modules/kw_club?op=kw_club_info_form" class="btn btn-info"><{$smarty.const._TAD_ADD}></a>
        </div>
    <{/if}>

    <{$bar}>
<{else}>
    <div class="jumbotron text-center">
        <{if $isAdmin}>
            <a href="<{$xoops_url}>/modules/kw_club?op=kw_club_info_form" class="btn btn-info"><{$smarty.const._TAD_ADD}></a>
        <{else}>
            <h3><{$smarty.const._TAD_EMPTY}></h3>
        <{/if}>
    </div>
<{/if}>
