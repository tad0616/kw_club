<h3>新增或編輯
    <select name="select_type" onChange="location.href=this.options[this.selectedIndex].value;">
        <option value="<{$action}>?op=cate_form&type=teacher" <{if $type=='teacher'}>selected<{/if}>>開課教師</option>
        <option value="<{$action}>?op=cate_form&type=cate" <{if $type=='cate'}>selected<{/if}>>社團類型</option>
        <option value="<{$action}>?op=cate_form&type=place" <{if $type=='place'}>selected<{/if}>>上課地點</option>
    </select>
</h3>


<{$xoopsform}>


<{if $all_cate_content}>
  <table class="table table-striped table-hover">
    <thead>
      <tr class="">
          <th>
            <!--類型標題-->
            <{$smarty.const._MD_KWCLUB_CATE_TITLE}>
          </th>
          <th>
            <!--類型說明-->
            <{$smarty.const._MD_KWCLUB_CATE_DESC}>
          </th>
        <{if $smarty.session.isclubAdmin}>
          <th>
          <!--類型排序-->
          <{$smarty.const._MD_KWCLUB_CATE_SORT}>
          </th>
          <th>
          <!--是否啟用-->
            <{$smarty.const._MD_KWCLUB_CATE_ENABLE}>
          </th>
          <th>
              <!--流水號-->
              <{$smarty.const._MD_KWCLUB_CATEID}>
          </th>
          <th><{$smarty.const._TAD_FUNCTION}></th>
        <{/if}>
      </tr>
    </thead>

    <tbody id="kw_club_cate_sort">

      <{foreach from=$all_content item=data}>
        <tr id="tr_<{$data.0}>">
          <td>
              <!--類型標題-->
              <a href="<{$action}>&op=cate_show&cate_id=<{$data[0]}>"><{$data[1]}></a>
            </td>
            <td>
              <!--類型說明-->
              <{$data[2]}>
            </td>
          <{if $smarty.session.isclubAdmin}>
            <td>
              <!--類型排序-->
                  <{$data[3]}>
          </td>
            <td>    <!--是否啟用-->
              <{$data[4]}>
            </td>
            <td>    <!--流水號-->
              <{$data[0]}>
            </td>
            <td>
              <a href="javascript:delete_cate_func(<{$data[0]}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
              <a href="<{$xoops_url}>/modules/kw_club/config.php?type=<{$type}>&op=cate_form&cate_id=<{$data[0]}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
              <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
            </td>
          <{/if}>
        </tr>
      <{/foreach}>
    </tbody>
  </table>
  <{$bar}>

<{/if}>

