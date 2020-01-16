<% var count = 0; var totids = "";
if(file_srch.trim()) { 
	var vfile = "files"; %>
<div class="global-srch-res fl"><?php echo __("Search Results for"); ?>: <span><%= file_srch %></span></div>
    <div class="fl global-srch-rst">
    <a onclick="checkHashLoad('<%=vfile%>')" href="<%= HTTP_ROOT %>dashboard#files"> <?php echo __("Reset"); ?> </a>  
    </div>
	<div class="cb"></div>
<% } %>
<table width="98%" class="tsk_tbl arc_tbl files_tbl yoxview other_links" id="task">
	<% if(file_srch.trim() || parseInt(caseCount)) { %><?php /*?>Showing header on searching or when data >0 <?php */?>
	<tr style="" class="tab_tr">
        <td width="1%">&nbsp;</td>
        <td width="1%">
            <div class="fl"><?php echo __("Task"); ?>#</div><!--<div class="tsk_sort fl"></div>-->
        </td>
        <td width="27%" class="flnm_td">
            <div class="fl"><?php echo __("File Name"); ?></div><!--<div class="tsk_sort fl"></div>-->
        </td>        
        <td width="15%" class="flnm_td">
            <div class="fl"><?php echo __("Project Name"); ?></div><!--<div class="tsk_sort fl"></div>-->
        </td>
        <td width="7%" align="right">
            <?php echo __("Size"); ?><!--<div class="tsk_sort fl"></div>-->
        </td>    
	<td width="5%">&nbsp;</td>
	<% } %>
    </tr>
    	  <%  chkDateTime = '';
	    for(var key in caseAll) {
		var obj = caseAll[key];
		count++;
	
		var caseAutoId = obj.Easycase.id;
		var caseUniqId = obj.Easycase.uniq_id;
		var projUniqId = obj.Project.uniq_id;
		var caseNo = obj.Easycase.case_no;
		var fileId = obj.CaseFile.id;
		var fileName = obj.CaseFile.file;
		
		var newUpdDt = obj.newUpdDt;
		if(chkDateTime != newUpdDt) { %>
		    <tr>
			<td class="curr_day" align="left" colspan="4">
			    <div class=""><%= obj.newdt %></div>
			</td>
		    </tr>
		<% } %>
		<tr class="tr_all" id="curRow<%= fileId %>">
		    <td>
			<!--<div class="file_zip" title="Archive" rel="tooltip" onClick="archiveFile(this)" data-id="<%= fileId %>" data-name="<%= fileName %>" <% if(obj.is_archive == "1"){%> style="display:block" <% }else{ %> style="display:none" <% } %>></div>-->
			<div class="dropdown fl">
			    <div class="sett pop_bfr_arrow" data-toggle="dropdown"></div>
			    <ul class="dropdown-menu sett_dropdown-caret">
				<!--<li class="pop_arrow_new"></li>-->
				<?php if(defined('ROLE') && ROLE == 1 && array_key_exists('Archive File', $roleAccess) && $roleAccess['Archive File'] == 0){}else{ ?>
                                <li>
				    <a onClick="archiveFile(this)" data-id="<%= fileId %>" data-name="<%= fileName %>" href="javascript:void(0);"><div class="file_zip_DRPD" title="<?php echo __("Archive"); ?>" rel="tooltip" <% if(obj.is_archive == "1"){%> style="display:block;float:left;margin-right:10px;" <% }else{ %> style="display:none" <% } %>></div><?php echo __("Archive"); ?></a>
				</li>
                                <?php } ?>
                <?php if(defined('ROLE') && ROLE == 1 && array_key_exists('Delete File', $roleAccess) && $roleAccess['Delete File'] == 0){}else{ ?>
				
				<li><a onclick="removeFileFrmFiles(<%= fileId %>)" data-name="<%= fileName %>" id="file_remove_<%= fileId %>" href="javascript:void(0);"><div class="act_icon act_del_task fl" title="<?php echo __("Remove"); ?>"></div><?php echo __("Remove"); ?></a></li>
			    <?php } ?>
                            </ul>
			</div>
		    </td>
		    <td style="text-align:right;padding-right:20px">
			<div class="fr">
			    <a href="<%= HTTP_ROOT %>dashboard#details/<%= caseUniqId %>"><%= caseNo %></a></div>
		    </td>
		    <td class="file_pdr">
			<div class="<%= easycase.imageTypeIcon(obj.file_type) %>_file cmn_fl fl"></div>
			<div class="fl fl_wd_ipad gallery">
			    <% if(obj.download_url != '') { %>
				<div class="fl fl_nm_ipad">
                <a <?php if(defined('ROLE') && ROLE == 1 && array_key_exists('Download File', $roleAccess) && $roleAccess['Download File'] == 0){}else{ ?>href='<%= obj.download_url %>' target="_blank"<?php } ?> title="<%= obj.file_name %>" class="file_name_ipad">
				    <%= obj.file_name %>
				</a>
                </div>
			    <% } else { %>
                <div class="fl fl_nm_ipad">
                    <a <?php if(defined('ROLE') && ROLE == 1 && array_key_exists('Download File', $roleAccess) && $roleAccess['Download File'] == 0){}else{ ?>href='<%= obj.fileurl%>' target="_blank"<?php } ?> title=" <%= obj.file_name %>" class="file_name_ipad fl" <% if(obj.is_image) { %><?php if(defined('ROLE') && ROLE == 1 && array_key_exists('View File', $roleAccess) && $roleAccess['View File'] == 0){}else{ ?>rel="prettyImage[]"<?php } ?> <% } %>>
				    <%= obj.file_name %>
				</a>
                </div>
                <?php if(defined('ROLE') && ROLE == 1 && array_key_exists('Download File', $roleAccess) && $roleAccess['Download File'] == 0){}else{ ?>
				<div class="icon-download fl" alt="<?php echo __("Download"); ?>" rel="tooltip" title="<?php echo __("Download"); ?>" data-url="<%= obj.link_url %>" onclick="downloadImage(this);"></div>
                <?php } ?>
			    <% } %>
			    <div class="cb"></div>
			    <div class="fnt999"><?php echo __("Uploaded by"); ?> &nbsp;<span class="file_upld_by"><%= obj.usrName %></span>&nbsp;<span>
				<% if(obj.activity.indexOf('Today')==-1 && obj.activity.indexOf('Y\'day')==-1) { %><?php echo __("on"); ?><% } %>
				<span title="<%= obj.xct_activity %>"><%= obj.activity %></span></span></div>
			</div>
			<div class="cb"></div>
		    </td>
		    <td align="center"><%= obj.Project.name %></td>
		    <td align="right" style="display: block;"><%= obj.file_size %></td>
		    <td>&nbsp;</td>
		</tr>
	    <% 
	    chkDateTime = newUpdDt;
	    totids= totids+caseAutoId+"|";
	    } %>
</table>
<% if(parseInt(caseCount)) { %>
<table cellpadding="0" cellspacing="0" border="0" align="right" >
    <tr>
	<td align="center">
	    <div class="show_total_case">
		<%= total_files %>
	    </div>
	</td>
    </tr>
    <tr>
	<td align="center">
	    <ul class="pagination">
		<% var page = parseInt(casePage);
		if (page_limit < caseCount) {
		    var numofpages = caseCount / page_limit;
		    if ((caseCount % page_limit) != 0) {
			numofpages = numofpages + 1;
		    }
		    var lastPage = numofpages;

		    var k = 1;
		    var data1 = "";
		    var data2 = "";
		    if (numofpages > 5) {
			var newmaxpage = page + 2;
			if (page >= 3) {
			    var k = page - 2;
			    data1 = "...";
			}
			if ((numofpages - newmaxpage) >= 2) {
			    if (data1) {
				data2 = "...";
				numofpages = page + 2;
			    } else {
				if (numofpages >= 5) {
				    data2 = "...";
				    numofpages = 5;
				}
			    }
			}
		    }
		    if (data1) { %>
			<li><a href="javascript:void(0);" class="button_act" onClick="ajaxFilePage(1)">&laquo; First</a></li>
			<li class="hellip">&hellip;</li>
		    <% } 
		    if (page != 1) {
			pageprev = page - 1; %>
			<li><a href="javascript:void(0);" class="button_act" onClick="ajaxFilePage('<%= pageprev %>')">&lt;&nbsp;Prev</a></li>
		    <% } else { %>
			<li><a href="javascript:void(0);" class="button_prev">&lt;&nbsp;Prev</a></li>
		    <% }
		    for (i = k; i <= numofpages; i++) {
			if (i == page) { %>
			    <li><a href="javascript:void(0);" class="button_page"><%= i %></a></li>
			<% } else { %>
			    <li><a href="javascript:void(0);" class="button_act" onClick="ajaxFilePage('<%= i %>')"><%= i %></a></li>
			<% }
		    }
		    if ((caseCount - (page_limit * page)) > 0) {
			pagenext = page + 1; %>
			<li><a href="javascript:void(0);" class="button_act" onClick="ajaxFilePage('<%= pagenext %>')">Next&nbsp;&gt;</a></li>
		    <%  } else { %>
			<li><a href="javascript:void(0);" class="button_prev">Next&nbsp;&gt;</a></li>
		    <% }
		    if (data2) { %>
			<li class="hellip">&hellip;</li>
			<li><a href="javascript:void(0);" class="button_act" onClick="ajaxFilePage('<%= Math.floor(lastPage) %>')">Last &raquo;</a></li>
		    <% }
		}
		%>
	    </ul>
	</td>
    </tr>
</table>
<% } else if(file_srch.trim()){ %>
	<?php echo $this->element('no_data', array('nodata_name' => 'files-search')); ?>
<% } else if(!file_srch.trim()) {%>
    <?php echo $this->element('no_data', array('nodata_name' => 'files')); ?>
<% } %>

<input type="hidden" name="hid_cs" id="hid_cs" value="<%= count %>"/>
<input type="hidden" name="totid" id="totid" value="<%= totids %>"/>
<input type="hidden" name="chkID" id="chkID" value=""/>