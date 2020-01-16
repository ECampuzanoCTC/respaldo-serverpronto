<style>
.popup_form .new_customer table tr td{padding:5px 20px 5px 0px;}
</style>
<center><div id="appr_err_msg" style="color:#FF0000;display:none;"></div></center>
<?php  echo $this->Form->create('Approver',array('url'=>array('controller' =>'expense', 'action' => 'add_approver','plugin'=>'Expense'),'name'=>'frm_add_approver','id'=>'frm_add_approver')); ?>
<?php print $this->Form->input('approver_id', array('label'=>false,'type'=>'hidden', 'id' => 'apprv_id','value'=>'')); ?>
<input type="hidden" name="data[Project][approve_project_id]" id="approve_project_id" value="" />
    <div class="data-scroll new_category">
        <table cellpadding="0" cellspacing="0" class="col-lg-12">
			<tr>
				<td class="v-top">
					<div style="text-align:right">
						<span id="add_new_member_txt">
							<?php echo __("Add Users"); ?>:
						</span>
					<div class="opt_field">(<?php echo __("optional"); ?>)</div>
					</div>
				</td>
				<td style="text-align:left">
					<div class="fl check_user put_users_details">
					</div>
					<textarea id="members_list"  class="wickEnabled form-control expand getEmailsClass" rows="2" wrap="virtual" name="data[Project][members_list]"></textarea>
				<div class="user_inst">(<?php echo __("Use comma to separate multiple email ids"); ?>)</div>
					<div id="err_mem_email" style="display: none;color: #FF0000;"></div>
					<div id="autopopup"></div>
				</td>
			</tr>
        </table>    
    </div>
    <div style="padding-left:145px;">
        <span id="appr_loader" style="display:none;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
        </span>
        <span id="btn_appr">
            <button type="button" value="Add" class="btn btn_blue" id="btn_add_approver" onclick="return checkUserExists();" ><i class="icon-big-tick"></i><?php echo __('Create');?></button>
            <span class="or_cancel cancel_on_direct_pj"><?php echo __('or');?> <a onclick="closePopup();"><?php echo __('Cancel');?></a></span>
        </span>

    </div>
<?php echo $this->Form->end(); ?>