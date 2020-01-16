<div class="timelog-table" id='showUnbilled' style="border:0px;">
    <div class="timelog-table-head">
        <div>
            <div class="fl">
                <span class="time-log-head"><?php echo __('Invoice');?></span>              
            </div>
            <?php if(defined('TLG') && TLG != 1){ ?>
            <div class="fr mr5 relative" style="">
                <?php echo $this->Form->button('Create Invoice', array('type' => 'button', 'class' => 'btn btn_blue invoice-btn fl', "onclick" => "showInvoicePage('','New Invoice','new');")); ?>
            </div>
            <?php }else{ ?>
            <div class="fr"></div>
            <?php } ?>
            <div class="cb"></div>
        </div>
    </div>
    <div class="timelog-detail-tbl">
        <table cellpadding="3" cellspacing="4">
            <tr>
                <th style="width:5%" class="th_all">
                    <div class="dropdown fl">
                        <?php echo $this->Form->checkbox('inv_ids', array('hiddenField' => false, 'value' => '1', 'id' => 'inv_ids', 'class'=> 'inv_chk_all fl')); ?>
                        <?php if(SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1){ ?>
                        <div class="all_chk ml5 fl"></div>
                        <ul class="dropdown-menu" id="dropdown_menu_chk">
                            <li><a href="javascript:void(0);" onclick="groupArchiveInvoice()"><div class="sprite archive fl" title="<?php echo __('Archive');?>"></div><?php echo __('Archive');?></a></li>
                            <?php /* <li><a href="javascript:void(0);" onclick="groupRestoreInvoice()"><div class="sprite restore fl" title="Restore"></div>Restore</a></li> */ ?>
                            <li><a href="javascript:void(0);" onclick="markGroupInvoicePaid()"><div class="sprite paid fl" title="<?php echo __('Mark as Paid');?>"></div><?php echo __('Mark as Paid');?></a></li>
                            <li><a href="javascript:void(0);" onclick="markGroupInvoiceUnpaid()"><div class="sprite unpaid fl" title="<?php echo __('Mark as Unpaid');?>"></div><?php echo __('Mark as Unpaid');?></a></li>
                        </ul>
                        <?php } ?>
                    </div>
                </th>
                <th style="width:15%">
                    <a title="<?php echo __('Invoice');?> #" onclick="invoices.ajaxSorting('invoices', 'invoice', this);" class="anchor">
                        <div class="fl"><?php echo __('Invoice');?> #</div><div class="tsk_sort fl <?php if($order_by=='invoice' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>
                <th style="width:15%">
                    <a title="<?php echo __('Invoice Date');?>" onclick="invoices.ajaxSorting('invoices', 'invoice_date', this);" class="anchor">
                        <div class="fl"><?php echo __('Invoice Date');?></div><div class="tsk_sort fl <?php if($order_by=='invoice_date' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>
                <th style="width:20%">
                    <a title="<?php echo __('Customer');?>" onclick="invoices.ajaxSorting('invoices', 'customer', this);" class="anchor">
                        <div class="fl"><?php echo __('Customer');?></div><div class="tsk_sort fl <?php if($order_by=='customer' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>
                <th style="width:15%">
                    <a title="<?php echo __('Due Date');?>" onclick="invoices.ajaxSorting('invoices', 'due_date', this);" class="anchor">
                        <div class="fl"><?php echo __('Due Date');?></div><div class="tsk_sort fl <?php if($order_by=='due_date' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>
                <th style="width:10%" class="align_center">
                    <a title="<?php echo __('Amount');?>" onclick="invoices.ajaxSorting('invoices', 'amount', this);" class="anchor">
                        <div class="fl"><?php echo __('Amount');?></div><div class="tsk_sort fl <?php if($order_by=='amount' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>   
                <th style="width:5%" class="align_center"><?php echo __('Paid');?></th>
                <th style="width:15%" class="align_center"><?php echo __('Action');?></th>
        </tr>
    <?php if (!empty($inv)) { ?>
        <?php foreach ($inv as $invoice) { ?>
        <tr id="invoice_list<?php echo $invoice['Invoice']['id']; ?>" data-no="<?php echo $invoice['Invoice']['invoice_no']; ?>">
            <td class="align_center"><?php echo $this->Form->checkbox('inv_ids', array('hiddenField' => false, 'value' => $invoice['Invoice']['id'], 'id' => 'inv_ids' . $invoice['Invoice']['id'], 'class' => 'checkbox2 invoicechkbox')); ?></td>
            <td> <a class="anchor" onclick='showInvoicePage(<?php echo $invoice['Invoice']['id']; ?>,"<?php echo $invoice['Invoice']['invoice_no']; ?>");'><?php echo $invoice['Invoice']['invoice_no']; ?></a></td>
            <td> <?php echo $this->Format->get_date($invoice['Invoice']['issue_date']); ?></td>
            <td> <?php echo trim($invoice[0]['customer_name'])!=''? $invoice[0]['customer_name']:'---'; ?></td>
            <?php /*?><td><?php $name = $this->Format->getUserDtls($invoice['Invoice']['user_id']);echo $name['User']['name']; ?></td><?php */?>
            <td><?php echo $this->Format->get_date($invoice['Invoice']['due_date']); ?></td>
            <td class="align_right"><?php echo $this->Format->format_price($invoice['Invoice']['price']);?></td>
            <td class="align_center"><span <?php if ($invoice['Invoice']['is_paid']) { ?> class="sprite yes" <?php } else { ?> class="sprite no" <?php } ?> style="left:25%;" ></span></td>
            <td style="text-align:center;" align="center" class="invoice-list-option-btns">
                <center>
                <span class="sprite email anchor  tooltip" title="<?php echo __('Email'); ?>" onclick="opt_list_action('email','<?php print $invoice['Invoice']['id']?>','<?php print $invoice['Invoice']['customer_id']?>');"></span>
                <span class="sprite download anchor  tooltip" title="<?php echo __('Download'); ?>" onclick="opt_list_action('download','<?php print $invoice['Invoice']['id']?>');"></span>
                <span class="sprite print anchor  tooltip" title="<?php echo __('Print'); ?>" onclick="opt_list_action('print','<?php print $invoice['Invoice']['id']?>');"></span>
                <?php if(SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) {?>
                    <?php if ($invoice['Invoice']['is_paid'] == 0) { ?>
                        <span class="sprite paid anchor  tooltip" title="<?php echo __('Mark as Paid'); ?>" onclick="opt_list_action('paid',<?php print $invoice['Invoice']['id']?>);"></span>
                    <?php }else{ ?>
                        <span class="sprite unpaid anchor  tooltip" title="<?php echo __('Mark as Unpaid'); ?>" onclick="opt_list_action('unpaid',<?php print $invoice['Invoice']['id']?>);"></span>
                    <?php } ?>
                    <?php if ($invoice['Invoice']['is_active'] == 1) { ?>
                        <span class="sprite archive anchor  tooltip" title="<?php echo __('Archive'); ?>" onclick="opt_list_action('archive',<?php print $invoice['Invoice']['id']?>);"></span>
                    <?php }else{ ?>
                        <span class="sprite restore anchor  tooltip" title="<?php echo __('Restore'); ?>" onclick="opt_list_action('restore',<?php print $invoice['Invoice']['id']?>);"></span>
                    <?php } ?>
                    <span class="sprite delete anchor  tooltip" title="<?php echo __('Delete'); ?>" onclick="deleteInvoice(<?php print $invoice['Invoice']['id']?>);"></span>
                <?php } ?>
                </center>
            </td>
		</tr>
        <?php } ?>
    <?php } else { ?>
          <tr><td colspan="8"> <?php echo __('No records');?>......</td></tr>
    <?php } ?>
   </table>
</div>
    <?php if ($caseCount > 0) { ?>
        <div class="cb"></div>
        <div id='show_invoice_paginate'></div>

        <script type="text/javascript">
            pgShLbl = '<?php echo $this->Format->pagingShowRecords($caseCount, $page_limit, $casePage); ?>';
            var pageVars = {pgShLbl:pgShLbl,csPage:<?php echo $casePage; ?>,page_limit:<?php echo $page_limit; ?>,caseCount:<?php echo $caseCount; ?>};
            //console.log(pageVars);
            $("#show_invoice_paginate").html(tmpl("paginate_tmpl", pageVars)).show(); 
        </script>
        <div class="cb"></div>
    <?php } ?>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.tooltip').tipsy({gravity:'s',fade:true});
        $('.checkbox2').click(function(){
           if($(this).is(':checked')){
                $('#inv_ids').parents('.dropdown').addClass('active');
                $('#inv_ids').next('.all_chk').attr('data-toggle','dropdown');
           } else{
                $('#inv_ids').parents('.dropdown').removeClass('active');
                $('#inv_ids').next('.all_chk').attr('data-toggle','');
           }
    });
        $('.inv_chk_all').click(function(){
           if($(this).is(':checked')){
                $('#inv_ids').parents('.dropdown').addClass('active');
                $('#inv_ids').next('.all_chk').attr('data-toggle','dropdown');
           } else{
                $('#inv_ids').parents('.dropdown').removeClass('active open');
                $('#inv_ids').next('.all_chk').attr('data-toggle','');
           }
        });
        $('#inv_ids').click(function(event) {  //on click
            if (this.checked) { // check select status
                $('.checkbox2').each(function() { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox2"              
                });
            } else {
                $('.checkbox2').each(function() { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox2"                      
                });
            }
        });
        var checkboxes = $("input[type='checkbox']");       
        checkboxes.click(function() {  
            if($('.checkbox2').not(':checked').length > 0){                
                document.getElementById('inv_ids').checked=false;
            }else{ 
                document.getElementById('inv_ids').checked=true;
            }
            $('.btnaddto').attr("disabled", ! $('.checkbox2').is(":checked"));
             if(! $('.checkbox2').is(":checked")){  
                $('.btnaddto').removeClass('btn_blue');
             }else{
                 $('.btnaddto').addClass('btn_blue');
             }
        });
    });
    function deleteInvoice(v){
        if(confirm("<?php echo __('Are you sure you want to delete this invoice ?');?>")){
            $.post('<?php echo $this->Html->url(array('controller'=>'invoices','action'=>'deleteInvoice'));?>',{v:v},function(res){
                if(parseInt(res) != 0){
                    showTopErrSucc('success', "<?php echo __('Invoice deleted successfully.');?>");
                    var mode = get_mode();
                    switch_tab(mode);
                }else{
                    showTopErrSucc('error',  "<?php echo __('Invoice not deleted.');?>");
                }
            });
        }
    }

        
</script>