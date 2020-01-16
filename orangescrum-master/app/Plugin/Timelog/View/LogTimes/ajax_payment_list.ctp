<style>
    .sprite.print {background: url("<?php echo HTTP_ROOT ;?>img/print.png") 1px 0px no-repeat;width: 19px;height: 17px;margin-left: 0px;position: relative;display: block;left: -11px;top: 3px;}
   .sprite.email {background:url("<?php echo HTTP_ROOT ;?>img/sprite_osv2.png") -266px -1px no-repeat; width: 19px;height: 17px;margin-left: 5px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.download {background: url("<?php echo HTTP_ROOT ;?>img/sprite_osv2.png") -267px -217px no-repeat;width: 19px;height: 17px;margin-left: 0px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.delete {background: url("<?php echo HTTP_ROOT ;?>img/sprite_osv2.png") -186px -170px no-repeat;width: 19px;height: 17px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.archive {background: url("<?php echo HTTP_ROOT ;?>img/sprite_osv2.png") -165px -170px no-repeat;width: 19px;height: 17px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.restore {background: url("<?php echo HTTP_ROOT ;?>img/sprite_osv2.png") -204px -148px no-repeat;width: 19px;height: 17px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.paid {background: url("<?php echo HTTP_ROOT ;?>img/paid.png") 0px 0px no-repeat;width: 19px;height: 17px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.unpaid {background: url("<?php echo HTTP_ROOT ;?>img/unpaid.png") 0px 0px no-repeat;width: 19px;height: 17px;position: relative;display: block;left: -9px;top: 0;}
   .invoice-list-option-btns span.sprite{display:inline-block;}
</style>
    <div class="timelog-table" id='showUnbilled' style="border:0px;">
    <div class="timelog-table-head">
        <div>
            <div class="fl">
                <span class="time-log-head"><?php echo __("Payments");?></span>              
            </div>
            <div class="fr"></div>
            <div class="cb"></div>
        </div>
    </div>
    <div class="timelog-detail-tbl">
        <table cellpadding="3" cellspacing="4">
            <tr>
                
                <th style="width:15%">
                    <a title="Payment #" onclick="ajaxSortings('payments', 'payment', this);" class="anchor">
                        <div class="fl"><?php echo __("Payment");?> #</div><div class="tsk_sort fl <?php if($order_by=='payment' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>
                <th style="width:15%">
                    <a title="Generated On" onclick="ajaxSortings('payments', 'issue_date', this);" class="anchor">
                        <div class="fl"><?php echo __("Generated On");?></div><div class="tsk_sort fl <?php if($order_by=='issue_date' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>
                <th style="width:20%">
                    <a title="Paid to" <?php /*onclick="ajaxSortings('payments', 'payee', this);" class="anchor" */ ?>>
                        <div class="fl"><?php echo __("Paid To");?></div><div class=" fl <?php if($order_by=='payee' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>
                <th style="width:10%">
                    <a title="Received On" <?php /*onclick="invoices.ajaxSorting('invoices', 'due_date', this);" class="anchor" */?>>
                        <div class="fl"><?php echo __("Received On");?></div><div class=" fl <?php /*if($order_by=='due_date' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}*/?>"></div>
                    </a>
                </th>
                <th style="width:10%" class="align_center">
                    <a title="Amount" onclick="ajaxSortings('payments', 'amount', this);" class="anchor">
                        <div class="fl"><?php echo __("Amount");?></div><div class="tsk_sort fl <?php if($order_by=='amount' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>   
                <th style="width:5%" class="align_center"><?php echo __("Currency");?></th>
                <th style="width:5%" class="align_center"><?php echo __("Paid");?></th>
                <th style="width:15%" class="align_center"><?php echo __("Action");?></th>
        </tr>
    <?php if (!empty($inv)) { ?>
        <?php foreach ($inv as $payments) { 
          /*  if($payments['Payment']['is_paid'] == 0 && (SES_TYPE == 1 || SES_TYPE == 2)){
                $paynt_number = "<a class='anchor' onclick='showInvoicePage(".$payments['Payment']['id'].",".$payments['Payment']['payment_no']."," "," ",".$payments['Payment']['payee_id'].");'>".$payments['Payment']['payment_no']."</a>" ;
            }else{
                $paynt_number = $payments['Payment']['payment_no'] ;
            } */?>
        <tr id="invoice_list<?php echo $payments['Payment']['id']; ?>" data-no="<?php echo $payments['Payment']['payment_no']; ?>">
           <?php /* <td class="align_center"><?php echo $this->Form->checkbox('inv_ids', array('hiddenField' => false, 'value' => $invoice['Invoice']['id'], 'id' => 'inv_ids' . $invoice['Invoice']['id'], 'class' => 'checkbox2 invoicechkbox')); ?></td> */ ?>
            <td><?php if($payments['Payment']['is_paid'] == 0 && (SES_TYPE == 1 || SES_TYPE == 2)){ ?>
                <a class='anchor' onclick="showInvoicePage(<?php echo $payments['Payment']['id'];?>,'<?php echo $payments['Payment']['payment_no']; ?>','','',<?php echo $payments['Payment']['payee_id'];?>);"><?php echo $payments['Payment']['payment_no']; ?></a>
           <?php }else{
                echo $payments['Payment']['payment_no'] ;
            }?></td>
            <td> <?php echo $this->Format->get_date($payments['Payment']['issue_date']); ?></td>
            <td> <?php echo trim($payments['User']['payee_name'])!=''? $payments['User']['payee_name']:'---'; ?></td>
            <?php /*?><td><?php $name = $this->Format->getUserDtls($invoice['Invoice']['user_id']);echo $name['User']['name']; ?></td><?php */?>
            <td><?php if(!empty($payments['Payment']['receive_date'])){ echo $this->Format->get_date($payments['Payment']['receive_date']); } ?></td>
            <td class="align_right"><?php echo $this->Format->format_price($payments['Payment']['price']);?></td>
            <td class="align_right"><?php echo $payments['Payment']['currency'];?></td>
            <td class="align_center"><span <?php if ($payments['Payment']['is_paid']) { ?> class="sprite yes" <?php } else { ?> class="sprite no" <?php } ?> style="left:25%;" ></span></td>
            <td style="text-align:center;" align="center" class="invoice-list-option-btns">
                <center>
                <?php if(SES_TYPE < 3){?>
                <span class="sprite email anchor  tooltip" title="<?php echo __('Email');?>" onclick="opt_list_action('email','<?php print $payments['Payment']['id']?>','<?php print $payments['Payment']['payee_id']?>');"></span>
                <span class="sprite download anchor  tooltip" title="<?php echo __('Download');?>" onclick="opt_list_action('download','<?php print $payments['Payment']['id']?>');"></span>
                <?php } ?>
                <span class="sprite print anchor  tooltip" title="<?php echo __('Print');?>" onclick="opt_list_action('print','<?php print $payments['Payment']['id']?>');"></span>
                
                    <?php if ($payments['Payment']['is_paid'] == 0) { ?>
                        <span class="sprite paid anchor  tooltip" title="<?php echo __('Mark as Paid');?>" onclick="opt_list_action('paid',<?php print $payments['Payment']['id']?>);"></span>
                    <?php }else{ ?>
                        <span class="sprite unpaid anchor  tooltip" title="<?php echo __('Mark as Unpaid');?>" onclick="opt_list_action('unpaid',<?php print $payments['Payment']['id']?>);"></span>
                    <?php } 
                    if(SES_TYPE < 3 && $payments['Payment']['is_paid'] == 0){?>
                    <span class="sprite delete anchor  tooltip" title="<?php echo __('Delete');?>" onclick="deletePayment(<?php print $payments['Payment']['id']?>);"></span>
                    <?php } ?>
                </center>
            </td>
		</tr>
        <?php } ?>
    <?php } else { ?>
          <tr><td colspan="8"> <?php echo __("No records");?>......</td></tr>
    <?php } ?>
   </table>
</div>
    <?php if ($caseCount > 0) { ?>
        <div class="cb"></div>
        <div id='show_payment_paginate'></div>

        <script type="text/javascript">
            pgShLbl = '<?php echo $this->Format->pagingShowRecords($caseCount, $page_limit, $casePage); ?>';
            var pageVars = {pgShLbl:pgShLbl,csPage:<?php echo $casePage; ?>,page_limit:<?php echo $page_limit; ?>,caseCount:<?php echo $caseCount; ?>};
            $("#show_payment_paginate").html(tmpl("paginate_tmpl", pageVars)).show(); 
        </script>
        <div class="cb"></div>
    <?php } ?>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.tooltip').tipsy({gravity:'s',fade:true}); 
    });
    
  
</script>