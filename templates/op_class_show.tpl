<h2 class="text-center">   


    <span class="label label-info"><{$cate_id_title}></span>    
    <{$class_title}>
    <!--是否開班-->
    <{if $smarty.session.isclubAdmin}>
        <span class="badge">
            <{if $class_ischecked=='1'}>
                開班
            <{elseif $class_ischecked=='0'}>
                不開班
            <{else}>
                尚未報名完成
            <{/if}>
        </span>
    <{/if}> 

    <p class="text-center" style="font-size: 0.7em; margin: 30px auto 5px; padding:20px; border-top: 1px dashed #5f8aca;">
        <span style="color:blue;"><{$smarty.session.club_year}></span> 期 
        <{$smarty.const._MD_KWCLUB_APPLY_DATE}><{$smarty.const._TAD_FOR}><span style="color:rgb(190, 63, 4);"><{$smarty.session.club_start_date|date_format:"%Y/%m/%d %H:%M"}> ~ <{$smarty.session.club_end_date|date_format:"%Y/%m/%d %H:%M"}></span>
    </p>
</h2>

<div class="row" style="margin: 30px auto;">
    <div class="col-sm-6">
        <!--社團簡介-->
        <div class="well">
            <{$class_desc}>
        </div>
    </div>
    <div class="col-sm-6">

        <!--開課教師-->
        <div class="row">
            <label class="col-sm-3 text-right">
                <{$smarty.const._MD_KWCLUB_TEACHER_ID}>
            </label>
            <div class="col-sm-9">
                <{$teacher_id_title}>
            </div>
        </div>

        <!--上課地點-->
        <div class="row">
            <label class="col-sm-3 text-right">
                <{$smarty.const._MD_KWCLUB_PLACE_ID}>
            </label>
            <div class="col-sm-9">
                <{$place_id_title}>
            </div>
        </div>

        <!--招收對象-->
        <div class="row">
            <label class="col-sm-3 text-right">
                <{$smarty.const._MD_KWCLUB_CLASS_GRADE}>
            </label>
            <div class="col-sm-9">
                <{$class_grade}>
            </div>
        </div>

        <!--上課日-->
        <div class="row">
            <label class="col-sm-3 text-right">
                <{$smarty.const._MD_KWCLUB_CLASS_DATE}>
            </label>
            <div class="col-sm-9">   
                <span class="number_b">
                    <{$class_date_open|date_format:"%Y/%m/%d"}>
                </span>至
                <span class="number_b">
                    <{$class_date_close|date_format:"%Y/%m/%d"}>
                </span>   
                <div>            
                    <!--上課星期-->
                    每星期<span class="text_g"><{$class_week}></span>的
                    <span class="number_o">
                        <{$class_time_start|date_format:"%H:%M"}>
                    </span>
                    至
                    <span class="number_o">
                        <{$class_time_end|date_format:"%H:%M"}>
                    </span>
                </div> 
            </div>
        </div>


        <!--社團學費-->
        <div class="row">
            <label class="col-sm-3 text-right">
                <{$smarty.const._MD_KWCLUB_CLASS_MONEY}>
            </label>
            <div class="col-sm-9">
                <{$class_money}>元<{if $class_fee}>（學費） + <{$class_fee}>元（教材費）<{/if}>
            </div>
        </div>

        <!--招收人數-->
        <div class="row">
            <label class="col-sm-3 text-right">
                <{$smarty.const._MD_KWCLUB_CLASS_MENBER}>
            </label>
            <div class="col-sm-9">
                <{$class_menber}> 人
            </div>
        </div>

        <!--報名人數-->
        <div class="row">
            <label class="col-sm-3 text-right">
                <{$smarty.const._MD_KWCLUB_CLASS_REGNUM}>
            </label>
            <div class="col-sm-9">
                <{$class_regnum}> 人
            </div>
        </div>        

        <!--社團備註-->
        <{if $class_note}>
            <div class="row">
                <label class="col-sm-3 text-right">
                    <{$smarty.const._MD_KWCLUB_CLASS_NOTE}>
                </label>
                <div class="col-sm-9">
                    <{$class_note}>
                </div>
            </div>    
        <{/if}>
    
    </div>
</div>


<div class="alert alert-info text-center">
    <{if $is_full}>
        <a href="#" class="btn btn-danger disabled"><i class="fa fa-user-plus" aria-hidden="true"></i>
            報名額滿</a>
    <{elseif $chk_time}>
        <a href="index.php?op=reg_form&class_id=<{$class_id}>" class="btn btn-primary"><i class="fa fa-user-plus" aria-hidden="true"></i>
            我要報名</a>
    <{elseif $class_regnum >= $class_menber}> 
        <a href="index.php?op=reg_form&class_id=<{$class_id}>&is_full=1" class="btn btn-warning"><i class="fa fa-user-plus" aria-hidden="true"></i>
            我要報名後補</a>
    <{else}>
        <a href="#" class="btn btn-danger disabled"><i class="fa fa-user-plus" aria-hidden="true"></i>
            非報名時間</a>
    <{/if}>

    <{if $smarty.session.isclubAdmin || $smarty.session.isclubUser }>
        <{if $smarty.session.isclubAdmin || $uid == $class_uid }>
            <{if $class_regnum == 0}>
                <a href="javascript:delete_class_func(<{$class_id}>);" class="btn btn-danger">
                    <i class="fa fa-trash" aria-hidden="true"></i><{$smarty.const._TAD_DEL}>
                </a>
            <{/if}>

            <a href="club.php?class_id=<{$class_id}>" class="btn btn-warning">
                <i class="fa fa-pencil-square" aria-hidden="true"></i>
                <{$smarty.const._MD_KWCLUB_MODIFY_CLUB}>
            </a>
        <{/if}>
        <!-- <a href="club.php" class="btn btn-primary">
            <i class="fa fa-plus-square" aria-hidden="true"></i>
            <{$smarty.const._MD_KWCLUB_ADD_CLUB}>
        </a> -->
    <{/if}>
</div>


<br>

<{if $smarty.session.isclubAdmin || $uid == $class_uid }>
    <h3>
        <span style="color:green"><{$class_title}></span>
        社團報名列表
        <small>（共 <{$class_regnum}> 筆報名資料）</small>
    </h3>

    <div id="kw_club_class_save_msg"></div>

    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr class="info">

                <!--報名者姓名-->
                <th>
                    <{$smarty.const._MD_KWCLUB_REG_NAME}>
                </th>
                <!--報名者班級-->
                <th>
                    <{$smarty.const._MD_KWCLUB_REG_CLASS}>
                </th>
                <!--報名時間-->
                <th>
                    <{$smarty.const._MD_KWCLUB_REG_DATETIME}>
                </th>

                <{if $smarty.session.isclubAdmin}>
                    <!--是否後補-->
                    <th>
                        <{$smarty.const._MD_KWCLUB_REG_ISREG}>
                    </th>
                    <!--是否繳費-->
                    <th>
                        <{$smarty.const._MD_KWCLUB_REG_ISFEE}>
                    </th>
                    <!--報名者ID-->
                    <th>
                        <{$smarty.const._MD_KWCLUB_REG_UID}>
                    </th>
                    <th>
                        <{$smarty.const._TAD_FUNCTION}>
                    </th>
                <{/if}>
            </tr>
        </thead>
        <tbody>
            <{foreach from=$all_reg item=data}>
                <tr id="tr_<{$data.class_id}>">
                    <td>
                        <span data-toggle="tooltip" data-placement="bottom" title="<{$data.reg_sn}>"><{$data.reg_name}></span>
                    </td>
                    <td>
                        <{if $data.reg_grade=='幼'}>                          
                            <{$data.reg_grade}>兒園<{$data.reg_class}>
                        <{else}>                        
                            <{$data.reg_grade}>年<{$data.reg_class}>
                        <{/if}>
                        
                    </td>
                    <td>
                        <{$data.reg_datetime}>
                    </td>

                    <{if $smarty.session.isclubAdmin}>
                        <td>
                            <{$data.reg_isreg}>
                        </td>
                        <td>
                            <{$data.reg_isfee}>
                        </td>
                        <td>
                            <a href="index.php?op=myclass&uid=<{$data.reg_uid}>"><{$data.reg_uid}></a>
                        </td>
                        <td>
                            <a href="<{$action}>?reg_sn=<{$data.reg_sn}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                            <a href="javascript:delete_reg_func(<{$data.reg_sn}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                        </td>
                    <{/if}>
                </tr>
            <{/foreach}>
        </tbody>
    </table>
<{/if}>



