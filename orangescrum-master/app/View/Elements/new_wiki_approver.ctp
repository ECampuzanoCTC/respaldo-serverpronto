<style>
.popup_form .new_customer table tr td{padding:5px 20px 5px 0px;}
</style>
<center><div id="appr_wiki_err_msg" style="color:#FF0000;display:none;"></div></center>
<?php  echo $this->Form->create('WikiApprover',array('url'=>array('controller' =>'wiki', 'action' => 'add_wiki_approver','plugin'=>'Wiki'),'name'=>'frm_add_wiki_approver','id'=>'frm_add_wiki_approver')); ?>
<?php print $this->Form->input('wiki_approver_id', array('label'=>false,'type'=>'hidden', 'id' => 'wiki_apprv_id','value'=>'')); ?>
<input type="hidden" name="data[Project][wiki_approve_project_id]" id="wiki_approve_project_id" value="" />
    <div class="data-scroll new_category">
        <table cellpadding="0" cellspacing="0" class="col-lg-12" style="width:100%;">
			<tr>
				<td class="v-top" style="width:25%;">
					<div style="text-align:right">
						<span id="add_new_member_txt">
							<?php echo __("Add Users"); ?>:
						</span>
					<div class="opt_field">(<?php echo __("optional"); ?>)</div>
					</div>
				</td>
				<td style="text-align:left;width:75%;">
					<div class="fl check_user put_users_details">
					</div>
					<textarea id="members_list"  class="wickEnabled form-control expand getEmailsClassWiki" rows="2" wrap="virtual" name="data[Project][members_list]"></textarea>
				<div class="user_inst">(<?php echo __("Use comma to separate multiple email ids"); ?>)</div>
					<div id="err_mem_email" style="display: none;color: #FF0000;"></div>
					<div id="autopopup"></div>
				</td>
			</tr>
        </table>    
    </div>
    <div style="padding-left:145px;">
        <span id="wiki_appr_loader" style="display:none;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
        </span>
        <span id="btn_appr_wiki">
            <button type="button" value="Add" class="btn btn_blue" id="btn_add_approver_wiki" onclick="return checkWikiUserExists();" ><i class="icon-big-tick"></i><?php echo __('Create');?></button>
            <span class="or_cancel cancel_on_direct_pj"><?php echo __('or');?> <a onclick="closePopup();"><?php echo __('Cancel');?></a></span>
        </span>

    </div>
<?php echo $this->Form->end(); ?>