<h2>教師簡介</h2>

<table class="table table-bordered table-hover table-condensed">
    <thead>
        <tr class="success">
            <th nowrap>講師</th>
            <th nowrap>教師姓名</th>
            <th nowrap>電子信箱</th>
            <th nowrap>簡介</th>
        </tr>
    </thead>
    <tbody>
        <{foreach from=$teachers item=tea}>
            <tr>
                <td class="text-center"><img src="<{$tea.pic}>" alt="<{$tea.name}>" style="width: 72px;"></td>
                <td class="text-center"><{$tea.name}></td>
                <td class="text-center"><{$tea.email}></td>
                <td><{$tea.user_occ}><{$tea.bio}></td>
            </tr>
        <{/foreach}>
    </tbody>
</table>