<p style="color:red;margin-top:0"><?php echo __("The assigned user is not available fully on the date specified. User will be next available on the following dates");?></p>
<p style="color:green;margin-top:0"><?php echo __("Other available resources are as follows"); ?>:</p>
<div style="max-height:350px;overflow-y:scroll;">
<table cellspacing='15' cellpadding='15'>
    <tr>
        <th></th>
        <th><?php echo __("Resource"); ?></th>
        <th><?php echo __("Available Date & Hours"); ?></th>
    <input type="hidden" id="task_assigned_id" value ="<?php echo $assigned_Resource_id; ?>"> 
    <input type="hidden" id="task_id" value ="<?php echo $caseId; ?>"> 
    <input type="hidden" id="task_uniq_id" value ="<?php echo $caseUniqId; ?>"> 
    <input type="hidden" id="task_project_id" value ="<?php echo $project_id; ?>"> 
    <input type="hidden" id="task_gantt_start_date" value ="<?php echo $gantt_start_date; ?>"> 
    <input type="hidden" id="task_estimated_hr" value ="<?php echo $estimated_hours; ?>"> 
    </tr>
    <?php foreach($ResourceNextAvailableDate as $k => $usrdata){
        ?>
    <tr>
        <td valign="top">
           
        <input type="radio" id="choseResource_<?php echo $k; ?>" data-caseId="<?php echo $caseId; ?>" data-caseUniqId="<?php echo $caseUniqId; ?>" data-projectId ="<?php echo $project_id; ?>" data-resource="<?php echo $k; ?>" data-gantt-start-date='<?php echo $gantt_start_date;?>' data-est-hour='<?php echo $estimated_hours;?>' name="resource" <?php if($k == $assigned_Resource_id){echo "checked";}?>/></td>
        <td style="text-align: center" valign="top"><?php echo $k == SES_ID ? 'Me' : $usrdata['name']; ?></td>
        <td colspan="2">
            <table cellspacing='15' cellpadding='15'>
                <?php 
        foreach ($usrdata['AvailableHours']['next_available_dates'] as $key => $value) {?>
                <tr>
                    <td style="color:green;"><?php echo $value['date'];?></td>
                    <td style="color:green;"><?php echo $value['Avlhrs']." Hr";?></td>
    </tr>
    <?php } ?>
</table>
        </td>
        <?php /*?><td style="color:green">
        <?php $date_string = "";
        foreach ($usrdata['AvailableHours']['next_available_dates'] as $key => $value) {
            $date_string .= $value['date']." (".$value['Avlhrs']." Hrs), ";
        }
        ?>
        <?php echo rtrim($date_string, ', '); ?>
        </td><?php */?>
    </tr>
    <?php } ?>
</table>
</div>
<div style="padding-left:145px;">
    <span id="cust_loader_tsk_avl" style="display:none;">
        <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
    </span>
    <span id="btn_tsk_avl">
        <button type="button" value="Change" class="btn btn_blue" id="btn_resource" onclick="changeUnavailableResource()"><i class="icon-big-tick"></i><?php echo __('Change');?></button>
        <span class="or_cancel cancel_on_direct_pj"><?php echo __('or');?> <a onclick="closeChangeResourcePopup();"><?php echo __('Create Any Way');?></a></span>
    </span>

</div>