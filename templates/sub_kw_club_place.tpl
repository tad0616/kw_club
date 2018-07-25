<h2>上課地點設定</h2>

<{if $all_place_content}>    
    <ul class="list-group">
        <{foreach from=$all_place_content item=data}>
        <li class="list-group-item">
            <a href="<{$action}>&op=place_show&place_id=<{$data.place_id}>"><{$data.place_title}></a>
            <{if $smarty.session.isclubAdmin}>
            <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
                <a href="javascript:delete_place_func(<{$data.place_id}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                <a href="<{$xoops_url}>/modules/kw_club/config.php?type=<{$type}>&op=place_form&place_id=<{$data.place_id}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
          <{/if}>
        </li>
        <{/foreach}>
    </ul>
<{/if}>
