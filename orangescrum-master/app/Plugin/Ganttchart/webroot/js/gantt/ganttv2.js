$(document).ready(function(){
    $(document).on('keydown', 'input[type="text"][name="name"]', function () {if(SES_TYPE < 3 || access_type > 1){$('#ganttv2_save_btn').attr('disabled',false);}});
    $(document).on('blur', '#progress', function (event) {$("#progress").val($("#progress").val()>100?100:$("#progress").val());});
    $(document).on('keydown', '.numbersOnly', function (event) {
        var key = window.event ? event.keyCode : event.which;
        //console.log(key);
        var key_arr = [8, 46, 37,38, 39,40, 9, 91, 92, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 116,16,187,46,17,82,65,35,36,190];
        if (jQuery.inArray(key, key_arr) > -1) {return true;} else if (key < 48 || key > 57) {return false;} else {return true;}
    });
    $('#ganttv2_save_btn').attr('disabled','disabled');
    check_gantt_change();
     //var projid = get_project_id();
    $('.gantt_next_page').click(function(){
        //console.log('click next = '+page_no);
        if(page_no>0){
            loadGanttFromServer(get_project_id(), '',++page_no);
        }else{
            // noaction
        }
    });
    $('.gantt_prev_page').click(function(){
        if(page_no>0){
            //page_no = page_no >0 ? page_no:1;
            //console.log('click prev = '+page_no);
            loadGanttFromServer(get_project_id(), '',--page_no);
        }else{
            // noaction
        }
    });
    $(".bartoggleblock").live('click',function(){
        $obj = $(this);
        $workSpace = $('#workSpace');
        var tot_width = parseInt($workSpace.width());
        if($obj.hasClass('close')) {
            $obj.removeClass('close').attr('title',"Hide Tasks");       
            $workSpace.find('.splitBox1').width(400);
            $workSpace.find('.splitBox2').css({width:tot_width-400,left:'405px'});
            $workSpace.find('.vSplitBar').css({left:'400px'});
       }else{
            $obj.addClass('close').attr('title',"Show Tasks");
            $workSpace.find('.splitBox1').width(30);        
            $workSpace.find('.splitBox2').css({width:tot_width-30,left:'35px'});
            $workSpace.find('.vSplitBar').css({left:'30px'});
       }
    });
   
});

function open_more_opt_v2(more_opt) {var sid = arguments[1];if (typeof sid != 'undefined') {$('.more_opt').filter(':not(#' + more_opt + sid + ')').children('ul').hide();$("#" + more_opt + sid).children("ul").toggle();} else {$('.more_opt').filter(':not(#' + more_opt + ')').children('ul').hide();$("#" + more_opt).children("ul").toggle();setTimeout(function(){$("#" + more_opt).show();},200);}}
function changepriority_v2(pri, val, objid) {$('#CS_priority').val(val);$('#CS_priority_v2').val(val);$('#selected_priority_v2').html(pri);$('#pr_col_v2').removeClass('low').removeClass('medium').removeClass('high').addClass(pri);}

function changeassignto_v2(id, val) {
$('#tsk_asgn_to_v2').html($('#tempassign_'+id).attr('data-assignto'));
$('#CS_assign_to').val(id);
$('#gantt_assignto').val(id);
$('#more_opt5_v2 ul').hide();
}


function notified_users_v2(uid, name) {
    if ($('#chk_' + uid).is(":checked")) {

    } else {
        if ($("#make_client").is(":checked")) {
            var clients = $("#client").val();
            clients = clients.split(',');
            for (var i = 0; i <= clients.length; i++) {
                if (uid == clients[i]) {
                    var count = 1;
                    break;
                } else {
                    count = 0;
                }
            }
            //alert(count);
            if (count == 0) {
                if ($('#chk_' + uid).is(':checked')) {
                } else {
                    $('#more_opt12').append("<div class='user_div fl'><span id='user" + uid + "' style='padding:5px;color:white;'>" + ucfirst(name) + "</span><span class='close' style='margin-left:5px;color:white;font-weight:bold;padding:5px;cursor:pointer;' onclick='closeuser_v2(" + uid + ")'>x</span></div>");
                    $('#chk_' + uid).attr('checked', 'checked');
                }
            }
        } else {
            $('#more_opt12').append("<div class='user_div fl'><span id='user" + uid + "' style='padding:5px;color:white;'>" + ucfirst(name) + "</span><span class='close' style='margin-left:5px; font-weight:bold;padding:5px;cursor:pointer;color:white;' onclick='closeuser_v2(" + uid + ")'>x</span></div>");
            $('#chk_' + uid).attr('checked', 'checked');
        }
    }
    removeAll_v2();
}
function closeuser_v2(id){$('#user' + id + '').parent('div').remove();$('#chk_' + id).removeAttr("checked");removeAll_v2();}
function checkedAllRes_v2() {
    var ids = $('#userIds').val();
    ids = ids.split(',');
    var names = $('#userNames').val();
    names = names.split(',');
    $('#more_opt12').html('');
    if ($('#chked_all').is(":checked")) {
        $('.viewmemdtls_cls').show();
        $('.notify_cls').attr("checked", "checked");
        for (var j = 1; j < ids.length; j++) {
            $('#more_opt12').append("<div class='user_div fl'><span id='user" + ids[j] + "' style='padding:5px;color:white;'>" + ucfirst(names[j]) + "</span><span class='close' style='margin-left:5px; font-weight:bold;padding:5px;color:white;cursor:pointer;' onclick='closeuser_v2(" + ids[j] + ")'>x</span></div>");
        }
        if ($('#make_client').is(":checked")) {
            var clients = $('#client').val();
            clients = clients.split(',');
            for (var i = 0; i < clients.length; i++) {
                $('.notify_cls').each(function() {
                    if ($(this).val() == clients[i]) {
                        $(this).attr('disabled', true);
                        $(this).attr('checked', false);
                        closeuser_v2(clients[i]);
                    }
                });
            }
        } else {
            $('.chk_client').attr("checked", "checked");
        }
    } else {
        $('.notify_cls').removeAttr("checked");
        $('.chk_client').removeAttr("checked");
        for (var k = 1; k < ids.length; k++) {
            closeuser_v2(ids[k]);
        }
    }
}
function removeAll_v2(){if (!$('input.notify_cls[type=checkbox]:not(:checked)').length){$('#chked_all').attr("checked", "checked");}else{$('#chked_all').removeAttr("checked");}}
function addUser_v2(id, name) {if ($('#chk_' + id).is(':checked')){$('#more_opt12').append("<div class='user_div fl'><span id='user" + id + "' style='padding:5px;color:white;'>" + ucfirst(name) + "</span><span class='close' style='margin-left:5px; font-weight:bold;padding:5px;color:white;cursor:pointer;' onclick='closeuser_v2(" + id + ")'>x</span></div>");}else{closeuser_v2(id);}removeAll_v2();}
function getAutocompleteTag_v2(id, url, width, plchlder) {$("#" + id).fcbkcomplete({json_url: HTTP_ROOT + url,addontab: true,maxitems: 10,input_min_size: 0,height: 10,cache: true,filter_selected: true,firstselected: true,width: width,complete_text: plchlder,oncreate:function(){setTimeout(function(){$('.maininput').blur();}, 2000);}});}
function openEditor_v2(editormessage, basic_or_free) {
    $("#divNewCase").hide();
    $("#divNewCaseLoader").show();
    (function($) {
        if (typeof (tinymce) != "undefined") {//console.log('Inside remove123---');
            tinymce.execCommand('mceRemoveControl', true, 'CS_message'); // remove any existing references
        }

        createTaskTemplatePlugin_v2();

        $('#CS_message').tinymce({
            // Location of TinyMCE script
            script_url: HTTP_ROOT + 'js/tinymce/tiny_mce.js',
            theme: "advanced",
            plugins: "paste, -tasktemplate", // - tells TinyMCE to skip the loading of the plugin
            theme_advanced_buttons1: "upload_file,|,google_drive,|,dropbox,|, bold,italic,strikethrough,underline,|, numlist,bullist,|, indent,outdent,|,tasktemplate",
            theme_advanced_resizing: false,
            theme_advanced_statusbar_location: "",
            theme_advanced_toolbar_align: "left",
            paste_text_sticky: true,
            gecko_spellcheck: true,
            paste_text_sticky_default: true,
            forced_root_block: false,
            width: "100%",
            height: "200px",
            setup: function(ed) {
                ed.addButton('upload_file', {
                    title: 'Select Multiple Files to Upload',
                    image: HTTP_ROOT + 'img/images/attach.png',
                    onclick: function() {
                        attachFile();
                    }
                });
                ed.addButton('google_drive', {
                    title: 'Google Drive',
                    image: HTTP_ROOT + 'img/images/google_drive.png',
                    onclick: function() {googleConnect(0, basic_or_free);}
                });
                ed.addButton('dropbox', {
                    title: 'Dropbox',
                    image: HTTP_ROOT + 'img/images/dropbox.png',
                    onclick: function() {connectDropbox(0, basic_or_free);}
                });
            },
            oninit: function() {
                $("#divNewCaseLoader").hide();
                $("#divNewCase").show();
                //$('#CS_message').tinymce().focus();
                $('#CS_message').val(editormessage);
                $('#CS_message').tinymce().setContent(editormessage);
                $("#tmpl_open").show();
            }
        });

    })($);
}
function createTaskTemplatePlugin_v2() {
    if (typeof (tinymce) != "undefined") {
        // Creates a new plugin class and a custom listbox
        tinymce.create('tinymce.plugins.TaskTemplatePlugin', {
            createControl: function(n, cm) {
                switch (n) {
                    case 'tasktemplate':
                        var mlb = cm.createListBox('tasktemplate', {
                            title: 'Task Template',
                            onselect: function(v) {
                                //tinyMCE.activeEditor.windowManager.alert('Value selected:' + v);
                                if (v && v.indexOf('##') != -1) {
                                    showTemplates(v.split('##')[0], v.split('##')[1]);
                                } else {
                                    tinyMCE.activeEditor.setContent(tinyPrevContent);
                                }
                            }
                        });

                        // Add task templete values to the list box
                        mlb.add('Set to default', 0);
                        if (countJS(TASKTMPL)) {
                            for (var tmpl in TASKTMPL) {
                                mlb.add(TASKTMPL[tmpl].CaseTemplate.name, TASKTMPL[tmpl].CaseTemplate.id + '##' + TASKTMPL[tmpl].CaseTemplate.name);
                            }
                        }
                        // Return the new listbox instance
                        return mlb;
                }
                return null;
            }
        });
        // Register plugin with a short name
        tinymce.PluginManager.add('tasktemplate', tinymce.plugins.TaskTemplatePlugin);
    }
}


var save_flag = true;
function saveGanttOnServer() {
    if (!ge.canWrite)return;
    if (!save_flag)return;
    //save_flag = false;

    var prj = ge.saveProject();
    //console.log(JSON.stringify(prj));
    delete prj.resources;
    delete prj.roles;

     var prof = new Profiler("saveServerSide");
     prof.reset();

     if (ge.deletedTaskIds.length>0) {if (!confirm(ge.deletedTaskIds.length+" "+GanttMaster.messages["TASK_THAT_WILL_BE_REMOVED"])) {return;}}
    var mlstn = new Array();
    $('.gdfTable').find('tr[level="0"]').each(function(){mlstn.push($(this).attr('taskid'));});
    var dt = new Date();
    var timezone = dt.getTimezoneOffset();
    $('.loadingdata').show();

    var params = {CM:"SVPROJECT",prj:JSON.stringify(prj),prjid: get_project_id(),timezone:timezone,dt:dt.getTime(),pg:page_no,mlstn:mlstn,
        lsct:$("#hidden_last_count").val()
    };
     $.ajax(HTTP_ROOT + "ganttchart/ganttchart/ganttv2_save", {dataType:"json",data: params,type:"POST",success: function(response) {
             save_flag = true;
             //check_gantt_change();
            if (response.ok) {
                prof.stop();
                if (response.project) {
                   ge.loadProject(response.project); //must reload as "tmp_" ids are now the good ones
                } else {
                   //ge.reset();
                   loadGanttFromServer(get_project_id(),'',1);
                }
                $('#ganttv2_save_btn').attr('disabled',true);
                if(response.success == 2){
					showTopErrSucc('error', response.message);
				}
             } else {
                var errMsg="Errors saving project\n";
                if (response.message) {
                    errMsg=errMsg+response.message+"\n";
                }if (response.errorMessages.length) {
                    errMsg += response.errorMessages.join("\n");
                }
                showTopErrSucc('error', errMsg);
             }
        }
    });
}
function check_gantt_change(){var myVar = setTimeout(function(){if(SES_TYPE < 3 || access_type > 1){if(ge.stacklen()>0 || !$('#ganttv2_save_btn').attr('disabled')){$('#ganttv2_save_btn').attr('disabled',false);}else{clearTimeout(myVar);$('#ganttv2_save_btn').attr('disabled',true);check_gantt_change();}}},1000);}
function milstoneonTask_v2(project_id,mlstname,mlstid){
    var mlstname = mlstname||'';
    var mlstid = mlstid||'';
    var project_id = project_id||'';
    $.post(HTTP_ROOT + 'milestones/milestone_list', {'project_id': project_id}, function(res) {
        $('#more_milestone_v2 ul li').remove();
        if (res) {
            $('#more_opt8 ul li').remove();
             if (mlstname == '') {
                //$('#selected_milestone_v2').html('Default Milestone');
            } else {
                //$('#selected_milestone_v2').html(shortLength(ucfirst(formatText(mlstname)), 15));
            }
            $('#CS_milestone,#CS_milestone_v2').val(mlstid);
            $('#value_milestone_v2').html(mlstid);
            $.each(res, function(key, value) {
                if(key==mlstid){mlstname=value;}
                $('#more_milestone_v2 ul').append('<li><a href="javascript:jsVoid()" onclick="changeMilestone_v2(\'' + key + '\',\'v2\',\'' + value + '\');" >' + shortLength(ucfirst(formatText(value)), 25) + '</a><span class="value">' + key + '</span></li>');
            });
            mlstname = mlstname!=''?mlstname:"Default Milestone";
            $('#selected_milestone_v2').html((ucfirst(formatText(mlstname))));
            //$('#more_milestone_v2 ul').append('<li><a href="javascript:jsVoid()" onclick="addEditMilestone(this,\'\',\'\',\'\',\'\',\'createTask\');" class="cnew_mlst"><span class="value"></span>&nbsp;+ Create Milestone</a></li>');
            //addTaskEvents();
        } else {
            $('#selected_milestone_v2').html('Default Milestone');
            $('#CS_milestone,#CS_milestone_v2').val('');
            //$('#more_opt8 ul').append('<li><a href="javascript:jsVoid()" onclick="addEditMilestone(this,\'\',\'\',\'\',\'\',\'createTask\');" class="cnew_mlst"><span class="value"></span>&nbsp;+ Create Milestone</a></li>');
        }
    }, 'json');
}
function changeMilestone_v2(val,lb,mlstname) {$("#CS_milestone_v2").val(val); var mlstname = mlstname||'Default Milestone'; $('#selected_milestone_v2').html((ucfirst(formatText(mlstname))));}
function changeTaskType_v2(val) { $("#CS_type_id_v2").val(val);}
function deleteCase(id,cno,pid){$.ajax(HTTP_ROOT+"easycases/delete_case", {dataType:"json",data: {"id":id,"cno":cno,"pid":pid},type:"POST",success: function(response) {}});}
function set_extra_params(task, taskEditor) {
    $(taskEditor).find("#more_opt_v2").find('li').each(function() {
        if (parseInt($.trim($(this).find('span.value').html())) === parseInt(task.type_id)) {
            var lbl = $(this).find('img').length>0?$(this).find('img').clone():$(this).find('.taxt_typ_width').clone();
            var text = $.trim($(this).find('span.name').html());
            $(taskEditor).find("span#ctsk_type_v2").find('.name').html(text);
            $(taskEditor).find("span#ctsk_type_v2").find('.taxt_typ_width').replaceWith(lbl);
            $(taskEditor).find("span#ctsk_type_v2").find('.value').html(task.type_id);
            setTimeout(function() {
                $("#CS_type_id").val(task.type_id);
                $("#CS_type_id_v2").val(task.type_id);
            }, 200)
        }
    });
    setTimeout(function() {
        $(taskEditor).find("#more_opt9_v2").find('li').each(function() {
            if ($.trim($(this).find('span.value').html()) == task.priority) {
                var p_text = $(this).find('a').attr('data-priority');
                $(taskEditor).find("#selected_priority_v2").html(p_text);
                $(taskEditor).find("#pr_col_v2").removeClass('high medium low');
                $(taskEditor).find("#pr_col_v2").addClass(p_text.toLowerCase());
                $('#CS_priority').val(task.priority);
            }
        });
        var defaultAsgnName = task.assigned_to;
        var defaultAsgn = task.assign_to;
        var ext = '_v2';
        if(defaultAsgn&&defaultAsgnName&&defaultAsgn!=SES_ID){$('#tsk_asgn_to'+ext).html(defaultAsgnName);$('#CS_assign_to'+ext).val(defaultAsgn);}else if(defaultAsgn==0){$('#tsk_asgn_to'+ext).html("Nobody");$('#CS_assign_to'+ext).val('0');$(".timelog_block").hide();}else{$('#tsk_asgn_to'+ext).html('&nbsp;&nbsp;me');$('#CS_assign_to'+ext).val(SES_ID);}
		$('#CS_assign_to').val(defaultAsgn);
    }, 500);
    return;
}

//-------------------------------------------  Create some demo data ------------------------------------------------------
function setRoles(){ge.roles=[{id:"tmp_1",name:"Project Manager"},{id:"tmp_2",name:"Worker"},{id:"tmp_3",name:"Stakeholder/Customer"}];}
function setResource(){var res=[];for(var i = 1; i <= 10; i++){res.push({id: "tmp_" + i, name: "Resource " + i});}ge.resources = res;}
function editResources(){}
function clearGantt(){ge.reset();}
function loadI18n() {
        GanttMaster.messages = {
            "CANNOT_WRITE": "Cannot write",
            "CHANGE_OUT_OF_SCOPE": "No rights for update parents out of editor scope",
            "START_IS_MILESTONE": "Start is milestone",
            "END_IS_MILESTONE": "End is milestone",
            "TASK_HAS_CONSTRAINTS": "Task has constraints",
            "GANTT_ERROR_DEPENDS_ON_OPEN_TASK": "Gantt error depends on open task",
            "GANTT_ERROR_DESCENDANT_OF_CLOSED_TASK": "Gantt error descendant of closed task",
            "TASK_HAS_EXTERNAL_DEPS": "Task has external deps",
            "GANTT_ERROR_LOADING_DATA_TASK_REMOVED": "Gantt error loading data task removed",
            "ERROR_SETTING_DATES": "Error setting dates",
            "CIRCULAR_REFERENCE": "Circular reference not allowed.",
            "CANNOT_DEPENDS_ON_ANCESTORS": "Cannot depends on ancestors",
            "CANNOT_DEPENDS_ON_DESCENDANTS": "Cannot depends on descendants",
            "INVALID_DATE_FORMAT": "Invalid date format",
            "TASK_MOVE_INCONSISTENT_LEVEL": "Task move inconsistent level",
            "GANTT_QUARTER_SHORT": "trim.",
            "GANTT_SEMESTER_SHORT": "sem.",
            "TASK_THAT_WILL_BE_REMOVED": "Tasks will be removed. Do you want to continue?"
        };
    }

//-------------------------------------------  Get project file as JSON (used for migrate project from gantt to Teamwork) ------------------------------------------------------
    function getFile(){return false;$("#gimBaPrj").val(JSON.stringify(ge.saveProject()));$("#gimmeBack").submit();$("#gimBaPrj").val("");/*  var uriContent = "data:text/html;charset=utf-8," + encodeURIComponent(JSON.stringify(prj));neww=window.open(uriContent,"dl");*/}
//-------------------------------------------  LOCAL STORAGE MANAGEMENT (for this demo only) ------------------------------------------------------
    Storage.prototype.setObject = function(key, value) {this.setItem(key, JSON.stringify(value));};
    Storage.prototype.getObject = function(key) {return this.getItem(key) && JSON.parse(this.getItem(key));};
    function loadFromLocalStorage(){var ret;if(localStorage){if(localStorage.getItem("teamworkGantDemo")){ret = localStorage.getItem("teamworkGantDemo");}}else{$("#taZone").show();}if((!ret || !ret.tasks || ret.tasks.length == 0) && $.trim($("#ta").val()) != '') {ret = JSON.parse($("#ta").val());/*actualiza data*/var offset = new Date().getTime() - ret.tasks[0].start;for (var i = 0; i < ret.tasks.length; i++)ret.tasks[i].start = ret.tasks[i].start + offset;}ge.loadProject(ret);ge.checkpoint(); /*empty the undo stack*/}
    function saveInLocalStorage(){var prj=ge.saveProject();if(localStorage){localStorage.setItem("teamworkGantDemo",prj);}else{$("#ta").val(JSON.stringify(prj));}}

//-------------------------------------------  Open a black popup for managing resources. This is only an axample of implementation (usually resources come from server) ------------------------------------------------------

function editResources() {
        //make resource editor
        var resourceEditor = $.JST.createFromTemplate({}, "RESOURCE_EDITOR");
        var resTbl = resourceEditor.find("#resourcesTable");
        for (var i = 0; i < ge.resources.length; i++){var res = ge.resources[i];resTbl.append($.JST.createFromTemplate(res, "RESOURCE_ROW"))}
        //bind add resource
        resourceEditor.find("#addResource").click(function() {resTbl.append($.JST.createFromTemplate({id: "new", name: "resource"}, "RESOURCE_ROW"))});
        //bind save event
        resourceEditor.find("#resSaveButton").click(function() {
            var newRes = [];
            //find for deleted res
            for (var i = 0; i < ge.resources.length; i++) {
                var res = ge.resources[i];var row = resourceEditor.find("[resId=" + res.id + "]");
                if (row.size() > 0) {
                    //if still there save it
                    var name = row.find("input[name]").val();if (name && name != "")res.name = name;newRes.push(res);
                } else {
                    //remove assignments
                    for (var j = 0; j < ge.tasks.length; j++) {var task = ge.tasks[j];var newAss = [];for (var k = 0; k < task.assigs.length; k++) {var ass = task.assigs[k];if (ass.resourceId != res.id)newAss.push(ass);}task.assigs = newAss;}
                }
            }
            //loop on new rows
            resourceEditor.find("[resId=new]").each(function(){var row = $(this);var name = row.find("input[name]").val();if (name && name != "")newRes.push(new Resource("tmp_" + new Date().getTime(), name));});
            ge.resources = newRes;closeBlackPopup();ge.redraw();
        });
        var ndo = createBlackPage(400, 500).append(resourceEditor);
    }
function get_project_id(){
     var projid = $("#projFil").val();return projid;
     /*var ganttprjcookie = getCookie('prjid');var pname = getCookie('pjname');if (ganttprjcookie) {projid = ganttprjcookie;}*/
}
function get_milestone_id(obj){$nobj = obj.prev();if($nobj.attr('level')==='0'){/*no action*/}else{get_milestone_id($nobj);}return $nobj.attr('taskid');}
function updateTaskStatusGroup(task_status_list){
    //console.log(task_status_list)
    otsg = task_status_list;
    if(typeof task_status_list != 'undefined' && task_status_list.length > 0){
        //tsg = task_status_list;
        $.each(task_status_list,function(key,val){
            //console.log(val)
            if($("#more_status_v2_values").length>0){
                $("#more_status_v2_values").append($('<li>').html($('<a>').attr({'sid':val.id}).html("<span class='value'>"+val.id+"</span>"+val.name)));
            }
            tsg[val.id] = val.name;
        });
    }
}