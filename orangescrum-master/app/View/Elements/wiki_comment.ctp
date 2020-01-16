<style>
.popup_form .new_customer table tr td{padding:5px 20px 5px 0px;}
</style>
<center><div id="comment_err_msg" style="color:#FF0000;display:none;"></div></center>
<?php  echo $this->Form->create('WikiComment',array('url'=>array('controller' =>'wiki', 'action' => 'add_wiki_comment','plugin'=>'Wiki'),'name'=>'frm_add_wiki_comment','id'=>'frm_add_wiki_comment')); ?>
    <div class="data-scroll new_category">
        <table cellpadding="0" cellspacing="0" class="col-lg-12">
            <tr>
                <td style="vertical-align:top;width:35%;"><?php echo __('Comments');?>: <strong style="color:#FF0000;">*</strong></td>
                <td style="vertical-align:top;width:65%;">
					<textarea name="data[WikiComment][comment_data]" id="comment_data_id" class="comment_data_class form-control"></textarea>
					<input type="hidden" name="data[WikiComment][hid_wiki_id_val]" id="hidWikiId" value="" />
				</td>
            </tr>
        </table>    
    </div>
    <div style="padding-left:189px;">
        <span id="wiki_com_loader" style="display:none;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
        </span>
        <span id="btn_comment">
            <button type="button" value="Add" class="btn btn_blue" id="btn_add_wiki_comment"><i class="icon-big-tick"></i><?php echo __('Add');?></button>
            <span class="or_cancel cancel_on_direct_pj"><?php echo __('or');?> <a onclick="closePopup();"><?php echo __('Cancel');?></a></span>
        </span>
    </div>
<?php echo $this->Form->end(); ?>