<style type="text/css">
    .thwidth table th { width: 152px; }
</style>
<div class="user_profile_con thwidth">
<!--Tabs section starts -->
<?php echo $this->element("company_settings"); ?>

<?php echo $this->Form->create('Invoice.Settings', array('url' => array('controller' => "invoices", 'action' => 'save_settings'))); ?>
<table cellspacing="0" cellpadding="0" class="col-lg-5" style="text-align:left;">
    <tbody>
        <tr>
            <th><?php echo __('Layout');?>:</th>
            <td>
                <?php echo $this->Form->select('layout',array('portrait'=>'Portrait','landscape'=>'Landscape'), array('value' => $layout, 'class' => 'form-control','empty'=>'Select Layout')); ?>
            </td>
        </tr>
        <tr>
            <th></th>
            <td class="btn_align">
                <span id="invoice-btns">
                    <button type="submit" name="submit_invoice" id="submit_invoice" class="btn btn_blue"><?php echo __('Save');?></button>        
                    <span class="or_cancel"><?php echo __('or');?>
                        <a onclick="cancelProfile('<?php echo $referer; ?>');"><?php echo __('Cancel');?></a>
                    </span>
                </span>
                <span id="invoice-loader" style="display:none">
                    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="<?php echo __('Loading');?>..." />
                </span>
            </td>
        </tr>						
    </tbody>
</table>
<?php echo $this->Form->end(); ?>
<div class="cbt"></div>
</div>

<script type="text/javascript">
    
</script>
