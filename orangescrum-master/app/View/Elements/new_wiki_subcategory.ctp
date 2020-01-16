<style>
.popup_form .new_customer table tr td{padding:5px 20px 5px 0px;}
</style>
<center><div id="cat_err_msg" style="color:#FF0000;display:none;"></div></center>
<?php  echo $this->Form->create('WikiSubCategory',array('url'=>array('controller' =>'wiki', 'action' => 'add_wiki_subcategory','plugin'=>'Wiki'),'name'=>'frm_add_wiki_subcategory','id'=>'frm_add_wiki_subcategory')); ?>
<?php print $this->Form->input('wiki_subcategory_id', array('label'=>false,'type'=>'hidden', 'id' => 'wiki_subcat_id','value'=>'')); ?>
<?php print $this->Form->input('project_id', array('label'=>false,'type'=>'hidden', 'id' => 'project_id','value'=>'')); ?>
    <div class="data-scroll new_category">
        <table cellpadding="0" cellspacing="0" class="col-lg-12">
            <tr>
                <td><?php echo __('Sub-Category Title');?>: <strong style="color:#FF0000;">*</strong></td>
                <td><?php echo $this->Form->text('wiki_subcat_title', array('value' => '', 'class' => 'form-control', 'id' => 'wiki_subcat_title', 'placeholder' => __("Sub-Category Title",true))); ?><input type="hidden" name="data[WikiCategory][hidPrevSubCatTitle]" id="hidPrevSubCatTitleId" value="" /></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <div class="checkbox fl"  style="">
                        <label>
                        <?php echo $this->Form->checkbox('wiki_subcat_status', array('hiddenField' => false, 'value' => '0', 'id' => 'wiki_subcat_status')); ?>
                        <?php echo __('Make Inactive',true);?>
                        </label>
                    </div>
                </td>
            </tr>
        </table>    
    </div>
    <div style="padding-left:145px;">
        <span id="wiki_subcat_loader" style="display:none;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
        </span>
        <span id="btn">
            <button type="button" value="Add" class="btn btn_blue" id="btn_add_wiki_subcategory"><i class="icon-big-tick"></i><?php echo __('Create');?></button>
			<input type="hidden" name="data[WikiSubCategory][hidFromPage]" id="hidFromPageIdSub" value="" />
            <span class="or_cancel cancel_on_direct_pj"><?php echo __('or');?> <a onclick="closePopup();"><?php echo __('Cancel');?></a></span>
        </span>

    </div>
<?php echo $this->Form->end(); ?>