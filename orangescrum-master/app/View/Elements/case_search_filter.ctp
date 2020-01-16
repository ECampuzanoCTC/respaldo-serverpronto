<style type="text/css">
    .pr_low{background:none !important;}
    .pr_medium{background:none !important;}
    .pr_high{background:none !important;}
    .label{font-weight:normal;}
    .tsk_tbl td {border-right: 0px solid #FFF !important;border-bottom: 0px solid #FFF !important;}
    .anchor{cursor:pointer;}
    .act_log_task{background: url("<?php echo HTTP_ROOT; ?>img/Time_log_icon.png") no-repeat;width:20px;height:19px;}
    .act_timer{background: url("<?php echo HTTP_ROOT; ?>img/timer.png") no-repeat;width:20px;height:19px;}
    .task_title_icons_reoccurrence{background:url(<?php echo HTTP_ROOT; ?>/img/task_act_icons.png)no-repeat 0px 0px;position:relative;width:13px;height:11px;margin-top: 5px;display: block;float: left;margin-right:5px;}
    #custom_date .cdate_div_cls {padding-left:10px;}
</style>
<div class="task_listing timelog_lview">
    <div class="m-cmn-flow">
        <table width="100%" class="tsk_tbl compactview_tbl">
            <tr style="" class="tab_tr">
                <td class="task_cn" style="width: 16%;">
                    <a href="javascript:void(0);" title="<?php echo __('Name'); ?>" class="sortcaseno" onclick="ajaxSorting(<%= '\'name\', ' + caseCount + ', this' %> );">
                        <div class="fl"><?php echo __('Name'); ?></div>

                        <span class="sorting_arw"><% if(typeof orderBy != 'undefined' && orderBy == "name") { %>
                            <% if(orderByType == "ASC"){ %>
                            <div class=" tsk_sort fl tsk_asc"></div>
                            <% }else{ %>
                            <div class=" tsk_sort fl tsk_desc"></div>
                            <% } %>								
                            <% }else{ %>
                            <div class=" tsk_sort  fl "></div>
                            <% } %></span>
                    </a>
                </td>
                <td class="assign_wd_td">
                    <a class="sortcaseAt" href="javascript:void(0);" title="<?php echo __('Task Count'); ?>" class="sortcaseno">
                        <div class="fl"><?php echo __('Task Count'); ?></div></a>
                </td>
                <td class="task_wd">
                    <a class="sorttitle" href="javascript:void(0);" title="<?php echo __('Filter Items'); ?>" class="sortcaseno">
                        <div class="fl"><?php echo __('Filter Items'); ?></div></a>
                </td>

                <td class="assign_wd_td">
                    <a class="sortcaseAt" href="javascript:void(0);" title="<?php echo __('Action'); ?>" class="sortcaseno" >
                        <div class="fl"><?php echo __('Action'); ?></a>
                </td>
            </tr>
            <% if(typeof details != 'undefined' && details.length >0 ){
            for(var filter in details){ 
            %>
            <tr>
                <td id="name<%= details[filter]["SearchFilter"]["id"] %>"><span><%= details[filter]["SearchFilter"]["name"] %></span></td>
                <td><%= details[filter]["SearchFilter"]["namewithcount"] %></td>
                <td class="tag-btn-td">
                    <% var fid=details[filter]["SearchFilter"]["id"];
                    var ftypes=JSON.parse(details[filter]["SearchFilter"]["json_array"]);
                    for(var ftype in ftypes){
                    if(ftypes[ftype] !="" && ftypes[ftype] !="all"){ 
                    if(ftype=="STATUS"){ %>
                    <%= ftypes[ftype] %>
                    <% } else if(ftype=="CS_TYPES"){ %>
                    <%= ftypes[ftype] %>
                    <%}else if(ftype=="PRIORITY"){ %>
                    <%= ftypes[ftype] %>
                    <%}else if(ftype=="MEMBERS"){ %>
                    <%= ftypes[ftype] %>
                    <%}else if(ftype=="ASSIGNTO"){ %>
                    <%= ftypes[ftype] %>
                    <%}else if(ftype=="MILESTONE"){ %>
                    <%= ftypes[ftype] %>
                    <%}else if(ftype=="DATE" && ftypes[ftype] !="" && ftypes[ftype] !="any"){ 
                    x=ftypes[ftype].split("-"); 
                    for(var j=0;x.length > j; j++){	
                    if(x[j]=="one")
                    v="Past hour";
                    else if(x[j]=="24")
                    v="past 24 hours";
                    else if(x[j]=="any")
                    v="Any time";
                    else if(x[j]=="week")
                    v="Past week";	
                    else if(x[j]=="month")
                    v="Past month";	
                    else if(x[j]=="year")
                    v="Past year";							
                    else	
                    v=decodeURIComponent(x[j].replace(":","-"));
                    %>
                    <div style="margin-bottom: 5px;" class="fl filter_opn" rel="tooltip" title="Time"><%= v %><a href="javascript:void(0);" onclick="deleteFilterItem(<%= fid %>,<%= '\''+ ftype + '\'' %>,<%= '\''+x[j]+ '\'' %>,this);" class="fr">X</a></div>
                    <%}
                    }else if(ftype=="DUE_DATE" && ftypes[ftype] !="" && ftypes[ftype] !="any"){
                    x=ftypes[ftype].split("-");
                    for(var j=0;x.length > j; j++){	
                    if(x[j]=="overdue")
                    v="Overdue";
                    else if(x[j]=="24")
                    v="Today";
                    else if(x[j]=="any")
                    v="Any Time";							
                    else	
                    v=decodeURIComponent(x[j].replace(":","-"));
                    %>
                    <div style="margin-bottom: 5px;" class="fl filter_opn" rel="tooltip" title="Due Date" ><%= v %><a href="javascript:void(0);" onclick="deleteFilterItem(<%= fid %>,<%= '\''+ ftype + '\'' %>,<%= '\''+x[j]+ '\'' %>,this);"  class="fr">X</a></div>
                    <% }
                    }
                    }
                    }
                    %>
                </td>
                <td class="action_tlv">
                    <a href="javascript:void(0);" class="anchor" id="EditFilter<%= details[filter]["SearchFilter"]["id"] %>" title="Edit" onclick="editInlineFilter(<%= details[filter]["SearchFilter"]["id"] %>)"><div class="act_icon act_edit_task fl" title="Edit"></div></a> &nbsp;&nbsp; 
                    <a href="javascript:void(0);" class="anchor" title="Delete" onclick="deleteInlineFilter(<%= details[filter]["SearchFilter"]["id"] %>)"><div class="act_icon act_del_task fl" title="Delete"></div></a>
                </td>
            </tr>
            <% } }else{ %>
            <tr><td colspan="4" style="color:red; text-align: center;">No filter has been created. </td></tr>
            <% } %>
        </table>
    </div>
    <% $("#task_paginate").html('');
    if(caseCount && caseCount!=0) {
    var pageVars = {pgShLbl:pgShLbl,csPage:csPage,page_limit:page_limit,caseCount:caseCount};
    $("#task_paginate").html(tmpl("paginate_tmpl", pageVars));
    } %>
</div>

