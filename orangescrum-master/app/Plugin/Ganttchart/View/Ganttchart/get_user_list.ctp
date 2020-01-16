<?php if(empty($user_list)){
	echo "<span style='color:red;margin: 30%;'> No user found.</span>";
}else{ ?>
<div>
   <?php echo $this->Form->create('Project', array('url' => '/ganttchart/Ganttchart/add_gantt_setting', 'name' => 'gantt_settings', 'onsubmit' => 'return gantt_stng_add()')); ?>
    <div class="data-scroll">
    <table cellpadding="0" cellspacing="0" class="col-lg-12">
        <tr>
            <th>User Name</th>
            <th>View</th>
            <th>View And Edit</th>
            <th>No Access</th>
        </tr>
        <?php foreach($user_list as $ku =>$vu){ ?>
        <tr>
            <td>
                <input type="hidden" name="data[gantt_settings][id][]" value="<?php echo $vu['GanttSetting']['id']; ?>">
                <input type="hidden" name="data[gantt_settings][user_id][]" value="<?php echo $vu['User']['user_id']; ?>">
               
                <?php 
					echo $vu[0]['Name'];
					if(isset($vu['CompanyUser']['is_client']) && $vu['CompanyUser']['is_client']){
						echo ' (client)';
					}
				?>
            </td>
            <td><input type="checkbox" class ="chkbox" name="data[gantt_settings][access_type][]" value="1" <?php  if($vu['GanttSetting']['access_type'] == 1){ echo "checked"; } ?> > </td>
            <td><input type="checkbox" class ="chkbox" name="data[gantt_settings][access_type][]" value="2" <?php  if($vu['GanttSetting']['access_type'] == 2){ echo "checked"; } ?> ></td>
            <td><input type="checkbox" class ="chkbox" name="data[gantt_settings][access_type][]" value="0" <?php  if($vu['GanttSetting']['access_type'] == 0){ echo "checked"; } ?> ></td>
        </tr>
        <?php }?>
         
    </table>
        </div>
        <span id="btn">
            <button type="button" value="Create" name="crtProject" class="btn btn_blue chk_cls_hd" onclick="return gantt_stng_add(this);"><i class="icon-big-tick"></i><?php echo __("Submit"); ?></button>
            <span class="or_cancel chk_cls_hd"><?php echo __('or'); ?>
                <a onclick="closePopup();"><?php echo __("Cancel"); ?></a>
            </span>
        </span>

 <?php echo $this->Form->end(); ?>   
</div>   
    <style>
        .gannt_setting.cmn_popup{padding:28px 28px 8px;position:relative}
.gannt_setting .popup_form table tr td{
font-size: 16px;
padding: 10px 0;
text-align: center;}
.gannt_setting #ProjectGetUserListForm > span {
  display: inline-block;
  text-align: center;margin-top:20px;
  width: 100%;
}
.gannt_setting .close_popup{right:18px;top:34px;position:absolute}
.gannt_setting .popup_title{padding:4px 10px 57px 25px}
.gannt_setting .popup_bg{padding:1px 1px 0}
    </style>
<script>
    function gantt_stng_add(obj){	
        document.gantt_settings.submit();
		$(obj).hide();
		$('.chk_cls_hd').hide();
		$('.loader_dv').show();
        return true;
    }
    $(".chkbox").click(function(){
    
    if($(this).prop("checked") == true){
    $(this)
        .closest("tr")
        .find(".chkbox").prop('checked', false);
       $(this).prop('checked', true);
       
    } else {
        $(this)
        .closest("tr")
        .find(".chkbox").last("td").prop('checked', true);
    }
    
    //$(currentObj).removeAttr("disabled");
});
</script>
<?php } ?>