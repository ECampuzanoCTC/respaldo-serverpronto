<?php 
echo $this->Html->css(array('jquery-ui-1.8.4.custom', "/".$this->plugin."/css/gantt/platform.css", "/" . $this->plugin . "/css/gantt/libs/dateField/jquery.dateField.css", "/" . $this->plugin . "/css/gantt/gantt.css", "/" . $this->plugin . "/css/gantt/ganttPrint.css", "/" . $this->plugin . "/css/gantt/libs/jquery.svg.css"));
echo $this->Html->css("/" . $this->plugin . "/css/print.css", array("media" => "print"));
echo $this->Html->css('https://fonts.googleapis.com/icon?family=Material+Icons', array('noversion' => true));
?>
<style type="text/css">
    body {overflow-x: hidden !important;}
    #milestonelist{display:block !important; margin-top: 7px;}
    #workSpace{width:100% !important;padding:0px; overflow-y:auto; overflow-x:hidden;border:1px solid #e5e5e5;position:relative;margin:0;}
    .slide_rht_con{ padding:60px 0px 35px 18px;}
    .resEdit { padding: 15px; }
    .resLine { width: 95%; padding: 3px; margin: 5px; border: 1px solid #d0d0d0; }
    .ganttButtonBar h1{ color: #000000; font-weight: bold; font-size: 28px; margin-left: 10px; }
    .material-icons, .teamworkIcon{color:#395376;}
    .splitBox1{position:relative;}
    .gantt-paginate button.btn{margin:0px !important;}
    .nodisplay{display:none;}
    .lbl-m-wid{width:100px;}
    .dropdown .more_opt ul{width:100%;}
    .dropdown .more_opt ul,#more_opt_v2 .wid, #openpopup_v2{max-height:120px; overflow-x: hidden; overflow-y: auto;}
    .btn.btn_blue:disabled { background: #f4f4f4; box-shadow: 0px 0px 0px #fff; color: #a2a2a2; border: 1px solid #d8d8d8;}
    .txtalrt{text-align:right;}
    .error-msg{color:#FF0000;}
    .pop-mbtm-10{margin-bottom:10px}
    .cstm-edit-design .createtask{width:100%}
    .cstm-edit-design .popup_title .pop-head{padding-left:5px}
    .cstm-edit-design .popup_title .pop-head .icon-create-proj:before{left:30px;top:20px}
    #workSpace_img{position:absolute; top:55px;left:0px;height:100%;width:100%; background: url("<?php echo HTTP_ROOT; ?>ganttchart/img/gantt/hours_spent_by_all_line.jpg") no-repeat 100%; display: none;}
    .button{vertical-align:middle;}
    .main-container-div{padding: 5px 0px 0px;}
    .prjnm_ttc{max-width:500px;width:300px;}
    .loadingdata{display:none;position: absolute; left:48%; top:100px;z-index:1;}
    .relative{position:relative;}
    .dropdown .more_opt ul { background:#fff;color:#C5C0B0;display:none;list-style:none; left: -7px;margin-top: 3px;position: absolute;width: 190px;padding: 0px; border: 0px none;}
    .dropdown .more_opt ul li{border:1px solid #D3D3D3;border-top: 0px none;border-bottom: 0px none;padding: 2px;}
    .dropdown .more_opt ul li:first-child{border-top:1px solid #D3D3D3;}
    .dropdown .more_opt ul li:last-child{border-bottom:1px solid #D3D3D3;}
</style>
<input type="hidden" id="hidden_last_count" value=""/>
<input type="hidden" id="CS_priority_v2" value=""/>
<input type="hidden" id="CS_type_id_v2" value=""/>
<input type="hidden" id="CS_milestone_v2" value=""/>
<div id="milestonelist" style="min-height:400px;" class="relative"> 
    <h1 class="print_preview_project_name" style="display:none;"></h1>
    <input type="hidden" id="hidden_projUpdateTop_v2" value=""/>
    <div class="loadingdata" style="">
        <img title="loading..." alt="loading..." src="<?php echo HTTP_ROOT; ?>/img/ajax-loader.gif"/>Loading...
    </div>
    <div id="workSpace_img" style=""></div>
    <div id="workSpace" style=""></div>
    <form id="gimmeBack" style="display:none;" action="" method="post"><input type="hidden" name="prj" id="gimBaPrj" value="<?php echo $puniq; ?>"></form>
</div>
<script type="text/javascript">
    var page_no = 1, tsg = [], otsg;
    var ge;  //this is the hugly but very friendly global var for the gantt editor
    $(document).ready(function () {
        $(document).on('click', 'body', function (e) {
            $(e.target).closest('.dropdown').length ? "" : $('.more_opt').find('ul').hide();
        });
        var projid = $("#projFil").val();
        var ganttprjcookie = getCookie('prjid');
        var pname = getCookie('pjname');
        if (ganttprjcookie) {
            projid = ganttprjcookie;
        }
        //if (pname) {$('#pname_dashboard,.print_preview_project_name').html(pname);}
        $('#pname_dashboard').css({'display': 'inline-block', 'max-width': '90%'}).addClass('ellipsis-view');
        $("#caseLoader").css("display", "block");
        $(".taskStatusSVG[status='STATUS_ACTIVE']").find('.imgEditSVG').remove();
        //load templates
        $("#ganttemplates").loadTemplates();

        // here starts gantt initialization
        ge = new GanttMaster();
        var workSpace = $("#workSpace");
        workSpace.css({width: $(window).width() - 20, height: $(window).height()-workSpace.position().top});
        ge.init(workSpace);

        //inject some buttons (for this demo only)
        /*$(".ganttButtonBar div").append("<button onclick='clearGantt();' class='button'>clear</button>")
         .append("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")
         .append("<button onclick='getFile();' class='button'>export</button>");*/
        //$(".ganttButtonBar h1").html("<a href='http://twproject.com' title='Twproject the friendly project and work management tool' target='_blank'><img  style='height:30px' src='../img/gantt/twGanttLogo.png'></a>");
        $(".ganttButtonBar div").addClass('buttons');
        //overwrite with localized ones
        loadI18n();

        //simulate a data load from a server.
        //loadGanttFromServer(projid);
        loadGanttFromServer(get_project_id());

        //fill default Teamwork roles if any
        if (!ge.roles || ge.roles.length == 0) {
            setRoles();
        }

        //fill default Resources roles if any
        if (!ge.resources || ge.resources.length == 0) {
            setResource();
        }

        /*/debug time scale
         $(".splitBox2").mousemove(function(e){
         var x=e.clientX-$(this).offset().left;
         var mill=Math.round(x/(ge.gantt.fx) + ge.gantt.startMillis)
         $("#ndo").html(x+" "+new Date(mill))
         });*/

        $(window).resize(function () {
            //workSpace.css({width: $(window).width() - 1, height: $(window).height() - workSpace.position().top-150});
            workSpace.css({width: $(window).width() - 1, height: $(window).height() - workSpace.offset().top});
            workSpace.trigger("resize.gantt");
        }).oneTime(150, "resize", function () {
            $(this).trigger("resize");
        });
        /*$("#more_opt5_v2").find('a').live('click', function () {
            change_assignee($(this).attr('value'), $(this).attr('data-name'));
        });*/
        });

    function loadGanttFromServer(projid, callback,page_nos) {
        $("#workSpace_img").hide();
        $("#workSpace,.ganttButtonBar").show();
        page_no = page_nos || '<?php echo $this->params->query('page');?>' || 1;
        var prof = new Profiler("loadServerSide");
        prof.reset();
        $('.loadingdata').show();
        $.ajax(HTTP_ROOT + "ganttchart/ganttchart/ganttv2_ajax", {
            dataType: "json",
            data: {CM: "LOADPROJECT", prjid: projid,page:page_no},
            type: "POST",
            success: function (response) {
                check_gantt_change();
                if(response.project.tasks.length === 0){
                    $("#workSpace_img").show();
                    $("#workSpace,.ganttButtonBar").hide();
                }
                (response.next === 'No') ? $('.gantt_next_page').hide():$('.gantt_next_page').show();
                (response.prev === 'No') ? $('.gantt_prev_page').hide():$('.gantt_prev_page').show();
                (typeof response.last_count != 'undefined')?$("#hidden_last_count").val(response.last_count):"";
                if (response.ok) {
                    prof.stop();
                    //console.log(response.project.tasks);
                    ge.loadProject(response.project);
                    ge.checkpoint(); //empty the undo stack
                    $("#hidden_projUpdateTop_v2").val(response.project.project_name);
                    if (typeof (callback) == "function") {callback(response);}
                    updateTaskStatusGroup(response.task_status_list);
                } else {
                    jsonErrorHandling(response);
                }
                $('.loadingdata').hide();
            }
        });
    }

    /*update individual tasks*/
    var task_save_flag = true;
    function saveTaskOnServer(task) {
        if (!ge.canWrite)
            return;
        //if (!task_save_flag)return;
        //task_save_flag = false;
        task.assign_to = $("#gantt_assignto").val()?$("#gantt_assignto").val():$("#CS_assign_to").val();
        task.priority = $("#CS_priority_v2").val();
        task.type_id = $("#CS_type_id_v2").val();
        task.progress = $("#tsk_progress_v2").html();
        task.milestone_id = $("#CS_milestone_v2").val();
        task.legend = $("#cslegend").val();

        var prj = ge.saveTask(task);
        delete prj.resources;
        delete prj.roles;

        var prof = new Profiler("saveServerSide");
        prof.reset();
        //console.log(task);return false;
        $.ajax(HTTP_ROOT + "ganttchart/ganttchart/ganttv2_save", {
            dataType: "json",
            data: {CM: "SVTASK", prj: JSON.stringify(prj), prjid: get_project_id()},
            type: "POST",
            success: function (response) {
                task_save_flag = true;
                //console.log($description);return;
                if (response.ok) {
                    prof.stop();
                    if (response.project) {
                        ge.loadProject(response.project); //must reload as "tmp_" ids are now the good ones
                    } else {
                        //ge.reset();
                        loadGanttFromServer(get_project_id());
                    }
                    if (response.message) {
                        showTopErrSucc('success', response.message); ;
                    }
                    $('#ganttv2_save_btn').attr('disabled',true);
                } else {
                    var errMsg = "Errors saving project\n";
                    if (response.message) {
                        errMsg = errMsg + response.message + "\n";
                    }

                    if (response.errorMessages.length) {
                        errMsg += response.errorMessages.join("\n");
                    }
                    //alert(errMsg);
                    showTopErrSucc('error', errMsg);
                }
            }
        });
    }
    var new_task_flag = true;
    function addTaskOnServer(task) {
        if (!ge.canWrite)
            return;
        /*remove empty tasks*/
        if (!task.name) {
            $(".taskEditRow[taskid^='tmp_']").each(function () {
                $(this).remove();
            });
            return false;
        }
        if (!new_task_flag)
            return;
        new_task_flag = false;

        var prj = ge.saveTask(task);
        delete prj.resources;
        delete prj.roles;
        var milestone_id = task.id.indexOf('tmp_') > -1 ? get_milestone_id($('tr[taskid="' + task.id + '"]')) : task.milestone;
        
        var params = {CS_project_id: get_project_id(), CS_istype: 1, CS_title: task.name, CS_type_id: 2, CS_priority: 1, CS_message: '',
            CS_assign_to: '<?php echo SES_ID; ?>', CS_due_date: '', CS_milestone: milestone_id.replace(/\D/g,''), postdata: 'Post', pagename: 'dashboard',
            emailUser: [<?php echo SES_ID; ?>], CS_id: 0, datatype: 0, CS_legend: 1, prelegend: '', hours: 0, estimated_hours: 0, completed: 0, taskid: 0,
            task_uid: 0, editRemovedFile: '', is_client: 0,
            seq_id: typeof task.rowElement[0].rowIndex != 'undefined' ? task.rowElement[0].rowIndex : '', };
        //console.log(params);return false;
        var prof = new Profiler("saveServerSide");
        prof.reset();
        //$.ajax(HTTP_ROOT + "Ganttchart/ganttv2_add", {
        $.ajax(HTTP_ROOT + "easycases/ajaxpostcase", {dataType: "json", data: params, type: "POST",
            //data: {CM:"ADDTASK",prj:JSON.stringify(prj),prjid: get_project_id()},
            success: function (response) {
                new_task_flag = true;
                //if (response.ok) {
                if (response.success == 'success') {
                    prof.stop();
                    if(ge.stacklen() > 0){
                        saveGanttOnServer(false);
                        return false;
                    }
                    if (response.project) {
                        ge.loadProject(response.project); /*must reload as "tmp_" ids are now the good ones*/
                    } else {
                        //ge.reset();
                        loadGanttFromServer(get_project_id());
                    }
                    if (response.message) {
                        showTopErrSucc('success', response.message); ;
                    }
                    $('#ganttv2_save_btn').attr('disabled',true);
                } else {
                    var errMsg = "Errors saving project\n";
                    if (response.message) {
                        errMsg = errMsg + response.message + "\n";
                    }
                    if (response.errorMessages.length) {
                        errMsg += response.errorMessages.join("\n");
                    }
                    //alert(errMsg);
                    showTopErrSucc('error', errMsg);
                }
            }
        });
    }
</script>

<div id="gantEditorTemplates" style="display:none;">
    <div class="__template__" type="GANTBUTTONS"><!--
    <div class="ganttButtonBar ">
      <h1 style="float:left" class="noprint">task tree/gantt</h1>
      <div class="buttons">
      <button onclick="$('#workSpace').trigger('undo.gantt');" class="button textual" title="<?php echo __("Undo"); ?>"><i class="material-icons">&#xE166;</i></button>
      <button onclick="$('#workSpace').trigger('redo.gantt');" class="button textual" title="<?php echo __("Redo"); ?>"><i class="material-icons">&#xE15A;</i></button>
      <span class="ganttButtonSeparator"></span>
      <button <?php if(SES_TYPE < 3 || (isset($GLOBALS['gantt_access_type']) && $GLOBALS['gantt_access_type'] == 2 )){ ?>onclick="$('#workSpace').trigger('addAboveCurrentTask.gantt');" <?php } ?> class="button textual" title="<?php echo __("Insert Task Above"); ?>"><i class="material-icons">&#xE25A;</i></button>
      <button <?php if(SES_TYPE < 3 || (isset($GLOBALS['gantt_access_type']) && $GLOBALS['gantt_access_type'] == 2 )){ ?>onclick="$('#workSpace').trigger('addBelowCurrentTask.gantt');" <?php } ?> class="button textual" title="<?php echo __("Insert Task Below"); ?>"><i class="material-icons">&#xE258;</i></button>
      <span class="ganttButtonSeparator"></span>
      <button onclick="$('#workSpace').trigger('indentCurrentTask.gantt');" class="button textual noprint" title="<?php echo __("Indent Task"); ?>"><span class="teamworkIcon">.</span></button>
      <button onclick="$('#workSpace').trigger('outdentCurrentTask.gantt');" class="button textual noprint" title="<?php echo __("Unindent Task"); ?>"><span class="teamworkIcon">:</span></button>
      <span class="ganttButtonSeparator noprint"></span>
      <button <?php if(SES_TYPE < 3 || (isset($GLOBALS['gantt_access_type']) && $GLOBALS['gantt_access_type'] == 2 )){ ?>onclick="$('#workSpace').trigger('moveUpCurrentTask.gantt');" <?php } ?> class="button textual" title="<?php echo __("Move Up"); ?>"><i class="material-icons">&#xE5D8;</i></button>
      <button <?php if(SES_TYPE < 3 || (isset($GLOBALS['gantt_access_type']) && $GLOBALS['gantt_access_type'] == 2 )){ ?> onclick="$('#workSpace').trigger('moveDownCurrentTask.gantt');" <?php } ?> class="button textual" title="<?php echo __("Move Down"); ?>"><i class="material-icons">&#xE5DB;</i></button>
      <span class="ganttButtonSeparator"></span>
      <button onclick="$('#workSpace').trigger('zoomMinus.gantt');" class="button textual" title="<?php echo __("Zoom Out"); ?>"><i class="material-icons">&#xE900;</i></button>
      <button onclick="$('#workSpace').trigger('zoomPlus.gantt');" class="button textual" title="<?php echo __("Zoom In"); ?>"><i class="material-icons">&#xE8FF;</i></button>
      <span class="ganttButtonSeparator"></span>
      <button <?php if(SES_TYPE < 3 || (isset($GLOBALS['gantt_access_type']) && $GLOBALS['gantt_access_type'] == 2 )){ ?> onclick="$('#workSpace').trigger('deleteCurrentTask.gantt');" <?php } ?> class="button textual" title="<?php echo __("Delete Task"); ?>"><i class="material-icons">&#xE872;</i></button>
      <span class="ganttButtonSeparator"></span>
      <button onclick="print();" class="button textual" title="<?php echo __("Print"); ?>"><i class="material-icons">&#xE8AD;</i></button>
      <span class="ganttButtonSeparator"></span>
      <button onclick="ge.gantt.showCriticalPath=!ge.gantt.showCriticalPath; ge.redraw();" class="button textual" title="<?php echo __("Critical Path"); ?>"><i class="material-icons">&#xE0D7;</i></button>
      <span class="ganttButtonSeparator noprint"></span>
      <button onclick="editResources();" class="button textual noprint" title="<?php echo __("Edit Resources"); ?>"><span class="teamworkIcon">M</span></button>
        &nbsp; &nbsp; &nbsp; &nbsp;
        <button onclick="saveGanttOnServer();" class="btn btn_blue" title="Save" id="ganttv2_save_btn"><?php echo __("Save"); ?></button>
      </div>
        <div class="gantt-paginate fr">
            <button class="btn gry_btn next fr gantt_next_page nodisplay" type="button" title="<?php echo __("Next"); ?>"><i class="icon-next"></i></button>
            <button class="btn gry_btn prev fr gantt_prev_page nodisplay" type="button" title="<?php echo __("Previous"); ?>"><i class="icon-prev"></i></button>
        </div>
        </div>
        --></div>

    <div class="__template__" type="TASKSEDITHEAD"><!--
    <table class="gdfTable" cellspacing="0" cellpadding="0">
      <thead>
      <tr style="height:40px">
        <th class="gdfColHeader noprint" style="width:35px;"></th>
        <th class="gdfColHeader noprint" style="width:25px;"></th>
        <th class="gdfColHeader gdfResizable noprint" style="width:30px;"><?php echo __("Code/short Name"); ?></th>

        <th class="gdfColHeader gdfResizable" style="width:400px;"><?php echo __("Name"); ?>
            <span class="bartoggleblock" title="<?php echo __("Hide Tasks"); ?>">
                <span class="teamworkIcon slide-left">&#xab;</span>
                <span class="teamworkIcon slide-right">&#xbb;</span>
            </span>
        </th>
        <th class="gdfColHeader gdfResizable noprint" style="width:80px;"><?php echo __("Start"); ?></th>
        <th class="gdfColHeader gdfResizable noprint" style="width:80px;"><?php echo __("End"); ?></th>
        <th class="gdfColHeader gdfResizable noprint" style="width:50px;"><?php echo __("Duration"); ?></th>
        <th class="gdfColHeader gdfResizable noprint" style="width:50px;"><?php echo __("Dep"); ?></th>
        <th class="gdfColHeader gdfResizable noprint" style="width:200px;"><?php echo __("Assignees"); ?></th>
      </tr>
      </thead>
    </table>
        --></div>

    <div class="__template__" type="TASKROW"><!--
    <tr taskId="(#=obj.id#)" class="taskEditRow" level="(#=level#)">
        <th class="gdfCell edit noprint" align="right" style="cursor:pointer;"><span class="taskRowIndex">(#=obj.getRow()+1#)</span> <span class="teamworkIcon" style="font-size:12px;" >e</span></th>
        <td class="gdfCell noClip noprint" align="center"><div class="taskStatus cvcColorSquare" status="(#=obj.status#)"></div></td>
        <td class="gdfCell noprint"><input type="text" name="code" value="(#=obj.code?obj.code:''#) asdf"></td>
        <td class="gdfCell indentCell" style="padding-left:(#=obj.level*50#)px;">
          <div class="(#=obj.isParent()?'exp-controller expcoll exp':'exp-controller'#)" align="center"></div>
          <input type="text" name="name" value="(#=obj.name#)">
        </td>

        <td class="gdfCell noprint"><input type="text" name="start"  value="" class="date"></td>
        <td class="gdfCell noprint"><input type="text" name="end" value="" class="date"></td>
        <td class="gdfCell noprint"><input type="text" name="duration" value="(#=obj.duration#)"></td>
        <td class="gdfCell noprint"><input type="text" name="depends" value="(#=obj.depends#)" (#=obj.hasExternalDep?"readonly":""#)></td>
        <td class="gdfCell taskAssigs noprint">(#=obj.getAssigsString()#)</td>
    </tr>
        --></div>

    <div class="__template__" type="TASKEMPTYROW"><!--
    <tr class="taskEditRow emptyRow" >
      <th class="gdfCell" align="right"></th>
      <td class="gdfCell noClip" align="center"></td>
      <td class="gdfCell"></td>
      <td class="gdfCell"></td>
      <td class="gdfCell"></td>
      <td class="gdfCell"></td>
      <td class="gdfCell"></td>
      <td class="gdfCell"></td>
      <td class="gdfCell"></td>
    </tr>
        --></div>

    <div class="__template__" type="TASKBAR"><!--
    <div class="taskBox taskBoxDiv" taskId="(#=obj.id#)" >
      <div class="layout (#=obj.hasExternalDep?'extDep':''#)">
        <div class="taskStatus" status="(#=obj.status#)"></div>
        <div class="taskProgress" style="width:(#=obj.progress>100?100:obj.progress#)%; background-color:(#=obj.progress>100?'red':'rgb(153,255,51);'#);"></div>
        <div class="milestone (#=obj.startIsMilestone?'active':''#)" ></div>
  
        <div class="taskLabel"></div>
        <div class="milestone end (#=obj.endIsMilestone?'active':''#)" ></div>
      </div>
    </div>
        --></div>

    <div class="__template__" type="CHANGE_STATUS"><!--
      <div class="taskStatusBox">
        <div class="taskStatus cvcColorSquare" status="STATUS_ACTIVE" title="active"></div>
        <div class="taskStatus cvcColorSquare" status="STATUS_DONE" title="completed"></div>
        <div class="taskStatus cvcColorSquare" status="STATUS_FAILED" title="failed"></div>
        <div class="taskStatus cvcColorSquare" status="STATUS_SUSPENDED" title="suspended"></div>
        <div class="taskStatus cvcColorSquare" status="STATUS_UNDEFINED" title="undefined"></div>
      </div>
        --></div>

    <div class="__template__" type="TASK_EDITOR"><!--
        <?php echo $this->element('ganttv2_edit_task'); ?>
        --></div>

    <div class="__template__" type="ASSIGNMENT_ROW"><!--
    <tr taskId="(#=obj.task.id#)" assigId="(#=obj.assig.id#)" class="assigEditRow" >
      <td ><select name="resourceId"  class="formElements" (#=obj.assig.id.indexOf("tmp_")==0?"":"disabled"#) ></select></td>
      <td ><select type="select" name="roleId"  class="formElements"></select></td>
      <td ><input type="text" name="effort" value="(#=getMillisInHoursMinutes(obj.assig.effort)#)" size="5" class="formElements"></td>
      <td align="center"><span class="teamworkIcon delAssig" style="cursor: pointer">d</span></td>
    </tr>
        --></div>

    <div class="__template__" type="RESOURCE_EDITOR"><!--
    <div class="resourceEditor" style="padding: 5px;">
  
      <h2>Project team</h2>
      <table  cellspacing="1" cellpadding="0" width="100%" id="resourcesTable">
        <tr>
          <th style="width:100px;"><?php echo __("name"); ?></th>
          <th style="width:30px;" id="addResource"><span class="teamworkIcon" style="cursor: pointer">+</span></th>
        </tr>
      </table>
  
      <div style="text-align: right; padding-top: 20px"><button id="resSaveButton" class="btn btn_blue"><?php echo __("Save"); ?></button></div>
    </div>
        --></div>

    <div class="__template__" type="RESOURCE_ROW"><!--
    <tr resId="(#=obj.id#)" class="resRow" >
      <td ><input type="text" name="name" value="(#=obj.name#)" style="width:100%;" class="formElements"></td>
      <td align="center"><span class="teamworkIcon delRes" style="cursor: pointer">d</span></td>
    </tr>
        --></div>

</div>
<?php
echo $this->Html->script(array('jquery-ui-1.10.3', HTTP_ROOT . $this->plugin . "/js/gantt/libs/jquery.livequery.min.js", HTTP_ROOT . $this->plugin . "/js/gantt/libs/jquery.timers.js", HTTP_ROOT . $this->plugin . "/js/gantt/libs/platform.js", HTTP_ROOT . $this->plugin . "/js/gantt/libs/date.js", HTTP_ROOT . $this->plugin . "/js/gantt/libs/i18nJs.js", HTTP_ROOT . $this->plugin . "/js/gantt/libs/dateField/jquery.dateField.js", HTTP_ROOT . $this->plugin . "/js/gantt/libs/JST/jquery.JST.js"));
echo $this->Html->script(array(HTTP_ROOT . $this->plugin . "/js/gantt/libs/jquery.svg.min.js", HTTP_ROOT . $this->plugin . "/js/gantt/libs/jquery.svgdom.1.8.js"));
echo $this->Html->script(array(HTTP_ROOT . $this->plugin . "/js/gantt/ganttUtilities.js", HTTP_ROOT . $this->plugin . "/js/gantt/ganttTask.js", HTTP_ROOT . $this->plugin . "/js/gantt/ganttDrawerSVG.js", HTTP_ROOT . $this->plugin . "/js/gantt/ganttGridEditor.js", HTTP_ROOT . $this->plugin . "/js/gantt/ganttMaster.js"));
echo $this->Html->script(array(HTTP_ROOT . $this->plugin . "/js/gantt/ganttv2.js"));
?>

<script type="text/javascript">
    $.JST.loadDecorator("ASSIGNMENT_ROW", function (assigTr, taskAssig) {
        var resEl = assigTr.find("[name=resourceId]");
        for (var i in taskAssig.task.master.resources) {
            var res = taskAssig.task.master.resources[i];
            var opt = $("<option>");
            opt.val(res.id).html(res.name);
            if (taskAssig.assig.resourceId == res.id)
                opt.attr("selected", "true");
            resEl.append(opt);
        }
        var roleEl = assigTr.find("[name=roleId]");
        for (var i in taskAssig.task.master.roles) {
            var role = taskAssig.task.master.roles[i];
            var optr = $("<option>");
            optr.val(role.id).html(role.name);
            if (taskAssig.assig.roleId == role.id)
                optr.attr("selected", "true");
            roleEl.append(optr);
        }
        if (taskAssig.task.master.canWrite && taskAssig.task.canWrite) {
            assigTr.find(".delAssig").click(function () {
                var tr = $(this).closest("[assigId]").fadeOut(200, function () {
                    $(this).remove();
                });
            });
        }
    });
    //add to whom default assigned on assign to select
    function change_assignee(defaultAsgn, defaultAsgnName) {
        if (defaultAsgn && defaultAsgnName && defaultAsgn != SES_ID) {
            $('#tsk_asgn_to_v2').html(defaultAsgnName);
            $('#CS_assign_to').val(defaultAsgn);
        } else {
            $('#tsk_asgn_to_v2').html('  <?php echo __("me"); ?>');
            $('#CS_assign_to').val(SES_ID);
        }
    }
    window.onbeforeunload = function () {
        return ge.stacklen() > 0 ? "<?php echo __("You have modified tasks, reloading the page will reset all changes"); ?>." : void 0
    };
    var baseurl = function(){return "<?php echo HTTP_ROOT;?>";}
</script>