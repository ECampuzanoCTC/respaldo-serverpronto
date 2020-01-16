<style>
.popup_form .new_customer table tr td{padding:5px 20px 5px 0px;}
</style>
<center><div id="cat_err_msg" style="color:#FF0000;display:none;"></div></center>
<?php  echo $this->Form->create('Category',array('url'=>array('controller' =>'expense', 'action' => 'add_category','plugin'=>'Expense'),'name'=>'frm_add_category','id'=>'frm_add_category')); ?>
<?php print $this->Form->input('category_id', array('label'=>false,'type'=>'hidden', 'id' => 'cat_id','value'=>'')); ?>
<?php print $this->Form->input('project_id', array('label'=>false,'type'=>'hidden', 'id' => 'project_id','value'=>'')); ?>
    <div class="data-scroll new_category">
        <table cellpadding="0" cellspacing="0" class="col-lg-12">
            <tr>
                <td><?php echo __('Category Title');?>: <strong style="color:#FF0000;">*</strong></td>
                <td><?php echo $this->Form->text('cat_title', array('value' => '', 'class' => 'form-control', 'id' => 'cat_title', 'placeholder' => __("Category Title",true))); ?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <div class="checkbox fl"  style="">
                        <label>
                        <?php echo $this->Form->checkbox('cat_status', array('hiddenField' => false, 'value' => 'Inactive', 'id' => 'cat_status')); ?>
                        <?php echo __('Make Inactive',true);?>
                        </label>
                    </div>
                </td>
            </tr>
        </table>    
    </div>
    <div style="padding-left:145px;">
        <span id="cat_loader" style="display:none;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
        </span>
        <span id="btn">
            <button type="button" value="Add" class="btn btn_blue" id="btn_add_category"><i class="icon-big-tick"></i><?php echo __('Create');?></button>
            <input type="hidden" name="data[WikiCategory][hidFromPage]" id="hidFromPageIdExp" value="" />
			<input type="hidden" name="data[WikiCategory][hidFromPageId]" id="hidCategoryElementId" value="" />
            <span class="or_cancel cancel_on_direct_pj"><?php echo __('or');?> <a onclick="closePopup();"><?php echo __('Cancel');?></a></span>
        </span>

    </div>
<?php echo $this->Form->end(); ?>