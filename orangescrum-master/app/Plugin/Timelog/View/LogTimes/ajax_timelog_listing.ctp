
<div class="timelog-table" id="timelogtbl">
<?php #print_r($logtimesArr);exit; ?>
<div class="timelog-table-head">
<div>
<div class="spent-time col-lg-12">
<div id="hrs_details" style="display:none">
<div class="fl">
<?php /* <span class="time-log-head"><?php echo __('Time Log');?>:</span> */ ?>
</div>
<div class="fl">
<span class="total use-time" style="display:none;">Total: </span>
<span class="use-time"><?php echo __('Logged');?>:</span>
<span><?php echo $logtimesArr['details']['totalHrs']; ?></span>
</div>
<div class="fl" style="margin:0px 5px">
<span class="use-time"><?php echo __('Billable');?>:</span>
<span><?php echo $logtimesArr['details']['billableHrs']; ?></span>
</div>
<div class="fl" style="margin:0px 5px">
<span class="use-time"><?php echo __('Non-billable');?>:</span>
<span><?php echo $logtimesArr['details']['nonbillableHrs']; ?></span>
</div>
<div class="fl">
<span class="use-time"><?php echo __('Estimated');?>:</span>
<span><?php echo $logtimesArr['details']['estimatedHrs'];#$cntestmhrs; ?></span>
</div>
</div>
<div class="cb"></div>
<div id="unpaid_hrs_time">
    <div class="fl"><span class="time-log-head"><?php echo __("Time Logs");?></span></div>
    <div class="fl" id="paid_div" style="margin:5px 0 5px 25px;"><input type="checkbox" id="paid_list"><?php echo __("Paid Time");?>:<?php echo $logtimesArr['details']['paid_time'];?></div>
    <div class="fl" id="unpaid_div" style="margin:5px 0 5px 25px;"><input type="checkbox" id="unpaid_list"><?php echo __("Unpaid Time");?>:<?php echo $logtimesArr['details']['unpaid_time'];?></div>
    <div class="fl" style="margin:5px 0 5px 25px;">Total Time: <?php echo $logtimesArr['details']['totalHrs'];?></div>  
    </div>
<div class="cb"></div>
</div>
<div style="padding:0 20px;margin-bottom:10px">
    <div class="fl limit-drpdwn-div" style="width:100px;margin-top:5px;">
                <div id="unbilled_pg_limit" class="custom-task-fld labl-rt add_new_opt">
                    <select class="select form-control"  onchange="changePageLimit(this)">
                        <option value="30" <?php if($page_limit == 30){ echo 'selected'; }?>>30</option>
                        <option value="50" <?php if($page_limit == 50){ echo 'selected'; }?>>50</option>
                        <option value="100" <?php if($page_limit == 100){ echo 'selected'; }?>>100</option>
                    </select>
                    <input type="hidden" id="hidden_pg_limit" value="<?php echo $page_limit; ?>" />
                </div>
    </div>
 <div class="fr" style="margin:0px auto;padding-right: 0;display:none" id="crtinvce">
     
    <div class="logmore-btn fr"  title="">
        <a class="anchor" style="padding-left: 0px;margin-right:8px; width:150px; padding-right: 0px;" onclick="addToPayment();"><?php echo __('Create Payment');?></a>
    </div>
</div>
<div class="cb"></div> 
</div>
<div class="cb"></div>  
</div>

</div>
<div class="timelog-detail-tbl">
<table cellpadding="3" cellspacing="4">
<tr>
    <?php if(SES_TYPE < 3){ ?>
        <th style="width:5%" class="align_center"><?php echo $this->Form->checkbox('lgids', array('hiddenField' => false, 'value' => '1', 'id' => 'lgids','data-usid' =>'')); ?></th>
    <?php }else{ ?>
        <th style="width:5%" class="align_center">Payment Number</th>
    <?php } ?>
<th><a class="sorttitle" onclick="sorting('Date');" title="<?php echo __('Date');?>" href="javascript:void(0);">
<div class="fl"><?php echo __('Date');?></div>
<div id="tsk_sort1" class="tsk_sort fl "></div>
</a></th>
<th><a class="sorttitle" onclick="sorting('Name');" title="<?php echo __('Name');?>" href="javascript:void(0);">
<div class="fl"><?php echo __('Name');?></div>
<div id="tsk_sort2" class="tsk_sort fl "></div>
</a></th>
<th><a class="sorttitle" onclick="sorting('Task');" title="<?php echo __('Task');?>" href="javascript:void(0);">
<div class="fl"><?php echo __('Task');?></div>
<div id="tsk_sort3" class="tsk_sort fl "></div>
</a></th>
<?php if($prjctId == "all"){ ?>
<th><?php echo __('Project');?></th>      
<?php }?>
<th><?php echo __('Note');?></th>
<th><?php echo __('Start');?></th>
<th><?php echo __('End');?></th>
<th><?php echo __('Break');?></th>
<th><?php echo __('Billable');?></th>
<th>Hours</th>
<th style="text-align: center;padding: 0px;width: 5%;"><?php echo __('Action');?></th>
</tr>
<?php #pr($logtimesArr);exit; ?>
<?php if (!empty($logtimesArr['logs'])) { ?>
    
<?php foreach ($logtimesArr['logs'] as $key=>$log) { 
    if(in_array($log['LogTime']['log_id'],$invc_tlgid) || in_array($log['LogTime']['log_id'],$payment_tlgid)){
        $disable_style = 'style="cursor:not-allowed"'.'onclick = "return false;"' ;
    }
    else{
        $disable_style = '';
    }
    if(in_array($log['LogTime']['log_id'],$payment_tlgid)){
        $disable = "disabled" ;
    }
    else{
        $disable='';
    }
     if ($GLOBALS['DateFormat'] == 2) {
          $task_date =date('d M, Y',strtotime($log['LogTime']['task_date']));
        } else {
          $task_date =date('M d, Y',strtotime($log['LogTime']['task_date']));
        }
       
    ?>
<tr>
    <td class="align_center">
        <?php if(in_array($log['LogTime']['log_id'],$payment_tlgid)){ ?>
            <?php $paymentDetails = $this->Format->getPaymentDetails($log['LogTime']['log_id']);?>
            <a class='anchor' onclick="opt_list_action('download',<?php echo $paymentDetails[0]['Payment']['id'];?>);"><?php echo $paymentDetails[0]['Payment']['payment_no']; ?></a>
        <?php }else{  ?>
            <?php if(SES_TYPE < 3){ ?>
                <?php echo $this->Form->checkbox('ids', array('hiddenField' => false, 'value' => $log['LogTime']['log_id'], 'id' => 'ids' . $log['LogTime']['log_id'], 'class' => 'checkbox1 invoicechkbox','data-usrid' => $log['LogTime']['user_id'],'data-prjctid' =>$log['LogTime']['project_id'] )); ?></td>
            <?php } ?>
        <?php   }?>
<td  <?php if(SES_TYPE < 3){ ?> class="anchor" onclick="showlogfordate('<?php echo $task_date ?>')" <?php } ?> ><?php echo $task_date ?></td>
<?php $name = $this->Format->getUserDtls($log['LogTime']['user_id']);?>
<td <?php if(SES_TYPE < 3){ ?> class="anchor"  onclick="showlogforuser(<?php echo $log['LogTime']['user_id']; ?>, '<?php echo $name['User']['name']; ?>')" <?php } ?> >
<?php echo $name['User']['name']; ?></td>
<?php $tsks = $this->Format->getTaskdetails($log['LogTime']['project_id'],$log['LogTime']['task_id']); ?>
<td  class="anchor ellipsis-view" <?php if(SES_TYPE < 3){ ?>  onclick="showlogfortask('','',this)" data-csid="<?php echo $log['LogTime']['task_id']; ?>" <?php } ?> data-tskttl ="<?php echo $tsks['Easycase']['title']; ?>" title="<?php echo $tsks['Easycase']['title']; ?>" rel="tooltip" ><?php echo $tsks['Easycase']['title']; ?></td>
<?php if($prjctId == "all"){ ?>
<td id="name_<?php echo $log['LogTime']['project_id'] ;?>">
<?php echo $log['Project']['project_name']; ?></td>  
<?php } ?>
<td <?php if(!empty($log['LogTime']['description'])) { ?>rel="tooltip" <?php } ?> title="<?php echo stripslashes($log['LogTime']['description']); ?>" ><?php echo stripslashes($this->Format->frmtdata($log['LogTime']['description'],0,20)); ?></td>
<td><?php echo $this->Format->chngdttime($log['LogTime']['task_date'],$log['LogTime']['start_time']); ?> </td>
<td><?php echo $this->Format->chngdttime($log['LogTime']['task_date'],$log['LogTime']['end_time']); ?></td>
<td><?php echo $this->Format->format_time_hr_min($log['LogTime']['break_time']); ?></td>
<td align="center"><span <?php if($log['LogTime']['is_billable']){ ?> class="sprite yes" <?php } else { ?> class="sprite no" <?php } ?> ></span></td>
<td>
<span class="fl"><?php $hrs = floor($log['LogTime']['total_hours']/3600)." hrs ".(($log['LogTime']['total_hours']%3600)/60)." min"; echo $hrs; ?></span>
</td>
<?php  if($log['LogTime']['user_id'] == SES_ID || SES_TYPE == 1 || SES_TYPE == 2|| (defined('ROLE') && ROLE == 1 && array_key_exists('Edit Timelog Entry', $roleAccess) && $roleAccess['Edit Timelog Entry'] == 1) || (defined('ROLE') && ROLE == 1 && array_key_exists('Delete Timelog Entry', $roleAccess) && $roleAccess['Delete Timelog Entry'] == 1)){ 
$project_uniq = $this->Format->getprjctUnqid($log['LogTime']['project_id']);?>
<td data-logid="<?php echo $log['LogTime']['log_id'];?>" data-prjctUniqid="<?php echo $project_uniq['Project']['uniq_id']; ?>" data-prjctId="<?php echo $log['LogTime']['project_id']; ?>" class="e-d-icon">
<?php } else { ?>
<td class="e-d-icon" style="position:relative;">
	<div class="timelog-overlap" style="" rel="tooltip" title="<?php echo __('You are not authorised to modify'); ?>."></div>
<?php } ?>
<a class="anchor edit_time_log" <?php if(in_array($log['LogTime']['log_id'],$invc_tlgid) || in_array($log['LogTime']['log_id'],$payment_tlgid)){ ?>
        onclick = "return false;" <?php } else{  if(defined('ROLE') && ROLE == 1){ if(array_key_exists('Edit Timelog Entry', $roleAccess) && $roleAccess['Edit Timelog Entry'] == 1) { ?> onclick="editTimelog(this)" <?php }}else{ ?> onclick="editTimelog(this)" <?php }} ?>><span class="fl sprite note" <?php echo $disable_style; ?>></span></a>
<a class="anchor delete_time_log" <?php if(in_array($log['LogTime']['log_id'],$invc_tlgid) || in_array($log['LogTime']['log_id'],$payment_tlgid)){ ?>
        onclick = "return false;" <?php } else{ if(defined('ROLE') && ROLE == 1){ if(array_key_exists('Delete Timelog Entry', $roleAccess) && $roleAccess['Delete Timelog Entry'] == 1) { ?> onclick="deletetimelog(this);" <?php } } else{ ?> onclick="deletetimelog(this)" <?php } }?> ><span class="fl sprite delete" <?php echo $disable_style; ?>></span></a>
</td>
</tr>
<?php } ?>
<?php }else{ ?>
	<tr>
<td colspan="10"><?php echo __('No records');?>......</td>
</tr>
<?php } ?>
</table>
</div>
</div>

<?php if($logtimesArr['caseCount']){
	$caseCount = $logtimesArr['caseCount'];
	$page_limit = $logtimesArr['page_limit'];
	$casePage = $logtimesArr['csPage'];
?>
<div>
    <input type="hidden" id="flt_start_date" value ="<?php echo $startdate; ?>">
    <input type="hidden" id="flt_end_date" value ="<?php echo $enddate; ?> ">
    <input type="hidden" id="flt_resource" value ="<?php echo $usrid ; ?> ">
    <input type="hidden" id="getcasecount" value="<?php echo $caseCount; ?>" readonly="true"/>
    <?php if ($caseCount > 0) { ?>
        <div class="cb"></div>
        <div id='showUnbilledTime_paginate'></div>

        <script type="text/javascript">
            pgShLbl = '<?php echo $this->Format->pagingShowRecords($caseCount, $page_limit, $casePage); ?>';
            var pageVars = {pgShLbl:pgShLbl,csPage:<?php echo $casePage; ?>,page_limit:<?php echo $page_limit; ?>,caseCount:<?php echo $caseCount; ?>};
           
            $("#showUnbilledTime_paginate").html(tmpl("paginate_tmpl", pageVars)).show(); 
        </script>
        <div class="cb"></div>
    <?php } ?>
        <input type="hidden" id="totalcount" name="totalcount" value="<?php echo $count; ?>"/> 

        <div class="fl crt-task">
            <span><!-- Log time for this task --></span>
        </div>
        <div class="fr ht_log">
            <!--
            <span class="fl">Hide time log</span>
            <a href=""><span class="fl sprite up-btn"></span></a>
            -->
        </div>
        <div class="cb"></div>
    </div>
<?php /*
<table id="pagingtable" cellpadding="0" cellspacing="0" border="0" align="right" <?php if($logtimesArr['calltype'] != 'log'){?> style="margin-top:0px;"<?php } ?>>
	<tr>
		<td align="center" style="padding-top:5px;padding-right:35px;">
			<div class="show_total_case" style="font-weight:normal;color:#000;font-size:12px;">
				<?php echo  $this->Format->pagingShowRecords($caseCount,$page_limit,$casePage); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td align="center">
		<ul class="pagination" style="padding-right:35px;">
		<?php $caseCount = $logtimesArr['caseCount'];
			 $page = $logtimesArr['csPage'];
			$page_limit = $logtimesArr['page_limit'];
			if($page_limit < $caseCount){
				$numofpages = $caseCount / $page_limit;
				if(($caseCount % $page_limit) != 0){
					$numofpages = $numofpages+1;
				}
				$lastPage = $numofpages;
				$k = 1;
				$data1 = "";
				$data2 = "";
				if($numofpages > 5){
					$newmaxpage = $page+2;
					if($page >= 3){
						$k = $page-2;
						$data1 = "...";
					}
					if(($numofpages - $newmaxpage) >= 2){
						if($data1){
							$data2 = "...";
							$numofpages = $page+2;
						}else{
							if($numofpages >= 5){
								$data2 = "...";
								$numofpages = 5;
							}
						}
					}
				}
				if($data1){
					echo "<li><a href='javascript:void(0)' onclick='logpagging(1)' class=\"button_act\">&laquo; First</a></li>";
					echo "<li class='hellip'>&hellip;</li>";
				}
				if($page != 1){
					$pageprev = $page-1;
                     echo "<li><a href='javascript:void(0)' onclick='logpagging(".$pageprev.")' class=\"button_act\">&lt;&nbsp;Prev </a></li>";
				}else{
					echo "<li><a href='javascript:jsVoid();' class=\"button_prev\" style=\"cursor:text\">&lt;&nbsp;Prev</a></li>";
				}
				for($i = $k; $i <= $numofpages; $i++){
					if($i == $page) {
						echo "<li><a href='javascript:jsVoid();' class=\"button_page\" style=\"cursor:text\">".$i."</a></li>";
					}else {
                     if($projtype == 'inactive'){
                          echo "<li><a href='javascript:void(0)' onclick='logpagging(".$i.")' class=\"button_act\" >".$i."</a></li>";
                     }else{
                          echo "<li><a href='javascript:void(0)' onclick='logpagging(".$i.")' class=\"button_act\" >".$i."</a></li>";
                     }
					}
				}
				if(($caseCount - ($page_limit * $page)) > 0){
					$pagenext = $page+1;
                    echo "<li><a href='javascript:void(0)' onclick='logpagging(".$pagenext.")' class=\"button_act\" >Next&nbsp;&gt; </a></li>";       
				}else{
                     echo "<li><a href='javascript:void(0)' class=\"button_prev\">Next&nbsp;&gt;</a></li>";
				}
				if($data2){
					echo "<li class='hellip'>&hellip;</li>";
                    echo "<li><a href='javascript:void(0)' onclick='logpagging(".floor($lastPage).")' class=\"button_act\" >Last &raquo;</a></li>";
				}
			} ?>
		</ul>
	</td>
</tr>
</table> */ ?>
<?php }	?>
<?php if(SES_TYPE < 3){?>
<div class="timelog-table" id="resrc-utl" style="border: none;display:inline-block; margin-top:0px;">
    <div class="fr">
    <a href="<?php echo HTTP_ROOT."resource-utilization/" ?>" title="<?php echo __("Resource Utilization");?>" class="pull-right" style="color:#2d6dc4"><?php echo __("Resource Utilization Report");?></a>
    </div>
</div>
<?php } ?>
<div class="crt_task_btn_btm">
    <div class="pr">
        <div class="os_plus ctg_btn">
            <div class="ctask_ttip">
                <span class="label label-default">Start Timer</span>
</div>
            <a onclick="openTimer();" href="javascript:void(0)">
               <img src="<?php echo HTTP_ROOT; ?>img/images/strt_timer.png" class="tme_icn" />
            </a>
</div>
    </div>
    <?php if(defined('ROLE') && ROLE == 1 && array_key_exists('Manual Time Entry', $roleAccess) && $roleAccess['Manual Time Entry'] == 0){}else{ ?> 
    <div class="os_plus">
        <div class="ctask_ttip">
            <span class="label label-default">
                Log Time
            </span>
        </div>
        <a href="javascript:void(0)" onClick="createlog('0', '');">
          <img class="prjct_icn ctask_icn" src="<?php echo HTTP_ROOT; ?>img/images/crt_timelog.png"> 
            <img src="<?php echo HTTP_ROOT; ?>img/images/plusct.png" class="add_icn" />
        </a>
    </div> 
    <?php } ?>
</div>

</div>
</div>
<style>
    .anchor.ellipsis-view {max-width: 120px;}
</style>
<script>
    $(document).ready(function() {  
         if(ROLE == 1){ 
            var   last_projroleId = <?php echo $last_projroleId ;?> ;        
            var   current_projroleId = <?php echo $current_projroleId ;?> ;        
                     //   alert("<?php echo $last_projroleId ;?>");alert("<?php echo $current_projroleId; ?>");
                     if(last_projroleId != current_projroleId){  
                            // window.location.assign(HTTP_ROOT+"dashboard/#task");
                           window.location.reload();
                        } 
        }
        var filter_text = "<?php if(!empty($filter_text)){echo addslashes($filter_text);}else{ echo 'for all users and all dates';} ?>";
        $('#filter_text').html(filter_text.replace(/\\/g, ''));
      $('[rel=tooltip]').tipsy({gravity:'s', fade:true});
         $('#tlg_act_unbill').find('.counter').html('(<?php echo $logtimesArr['caseCount'] ;?>)');
    }); 
     
    $('#paid_list').on('change',function(){
        var data={};
        if($('#paid_list').is(':checked') && !$('#unpaid_list').is(':checked')){
            
            data.ispaid = "paid";
            createCookie('ispaid', 'paid', 365, '');
            switch_tlg_tab('','filter_paid',data);
        }else if(!$('#paid_list').is(':checked') && $('#unpaid_list').is(':checked')){
            
            data.ispaid = "unpaid";
             createCookie('ispaid', 'unpaid', 365, '');
            switch_tlg_tab('','filter_paid',data);
        }else{
            data.ispaid = "both";
             createCookie('ispaid', '', 365, '');
            switch_tlg_tab('','filter_paid',data);
        }
        
    });
    $('#unpaid_list').on('change',function(){
        var data={};
        if($('#unpaid_list').is(':checked') && !$('#paid_list').is(':checked')){
            
            data.ispaid = "unpaid";
            createCookie('ispaid', 'unpaid', 365, '');
            switch_tlg_tab('','filter_paid',data);
        }else if($('#paid_list').is(':checked') && !$('#unpaid_list').is(':checked')){
            
            data.ispaid = "paid";
            createCookie('ispaid', 'paid', 365, '');
            switch_tlg_tab('','filter_paid',data);
        }else{
            data.ispaid = "both";
            createCookie('ispaid', '', 365, '');
            switch_tlg_tab('','filter_paid',data);
        }
        
    });
    if (DATEFORMAT == 2) {
        var date_format = 'd M, yy';
    } else {
        var date_format = 'M d, yy';
    }
    $("#logstrtdt").datepicker({
			dateFormat: date_format,
			changeMonth: false,
			changeYear: false,
			hideIfNoPrevNext: true,
			onClose: function( selectedDate ) {
				$("#logenddt").datepicker( "option", "minDate", selectedDate );
			},
	    });
	$("#logenddt").datepicker({
			dateFormat: date_format,
			changeMonth: false,
			changeYear: false,
			hideIfNoPrevNext: true,
			onClose: function( selectedDate ) {
				$("#logstrtdt").datepicker( "option", "maxDate", selectedDate );
			},
	    });
    $('#lgids').on('click',function(event) {  //on click
            var _that = $(this);
            if (this.checked && $('.checkbox1:checked').length != $('.checkbox1').not(':disabled').length) { // check select status
                $('.checkbox1').each(function() { 
                    if(!$(this).is('[disabled=disabled]')){//loop through each checkbox
                    this.checked = true; //select all checkboxes with class "checkbox1"  is('[disabled=disabled]')
                    
                    }            
                });
                _that.prop('checked',true);
                $('#crtinvce').show();
            } else {
                $('#crtinvce').hide();
                $('.checkbox1').each(function() { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                      
                });
            }
        });
        var checkboxes = $(".checkbox1");       
        checkboxes.on('click',function() {  
            if($('.checkbox1:checked').length == $('.checkbox1').not(':disabled').length){                
                document.getElementById('lgids').checked=true;
            }else{ 
                document.getElementById('lgids').checked=false;
            }
          //  $('.btnaddto').attr("disabled", ! $('.checkbox1').is(":checked"));
             if(! $('.checkbox1').is(":checked")){  
                $('#crtinvce').hide();
             }else{
                 $('#crtinvce').show();
             }
        });
</script>