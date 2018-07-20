<{$toolbar}>
<{includeq file="$xoops_rootpath/modules/kw_club/templates/op_`$op`.tpl"}>

<{if $error}>
    <div class="alert alert-danger">
        <{$error}>
    </div>
<{/if}>

<{foreach from=$smarty.session key=k item=v}>
    $_SESSION['<{$k}>'] = '<{$v}>';<br>
<{/foreach}>