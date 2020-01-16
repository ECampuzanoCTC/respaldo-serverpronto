<style type="text/css">
 .anchor {
    cursor: pointer;
}
</style>
<div class="timelog-table" style="border:0px none;">
    <div class="timelog-table-head">
        <div class="fl"><span class="time-log-head"><?php echo __('Customers');?></span></div>
        <div class="fr relative" style="margin-bottom:5px;">
            <?php echo $this->Form->button(__('Add Customer'), array('type' => 'button','onclick'=>'add_customer()', 'class' => 'btn btn_blue fr no-border-radius','style'=>'margin-right:0px;')); ?>
        </div>
    </div>
    <div class="cb"></div>
    <div class="timelog-detail-tbl">
        <table cellpadding="3" cellspacing="4">
            <tr>
                <th style="width:20%">
                    <a title="<?php echo __('Name');?>" onclick="invoices.ajaxSorting('customers', 'name', this);" class="anchor">
                        <div class="fl"><?php echo __('Name');?></div><div class="tsk_sort fl <?php if($order_by=='name' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>
                <th style="width:10%"><?php echo __('Organization');?></th>
                <th style="width:40%"><?php echo __('Address');?></th>
                <th style="width:10%" class="align_center">
                    <a title="<?php echo __('Currency');?>" onclick="invoices.ajaxSorting('customers', 'currency', this);" class="anchor">
                        <div class="fl"><?php echo __('Currency');?></div><div class="tsk_sort fl <?php if($order_by=='currency' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>
                <th style="width:10%" class="align_center">
                    <a title="<?php echo __('Status');?>" onclick="invoices.ajaxSorting('customers', 'status', this);" class="anchor">
                        <div class="fl"><?php echo __('Status');?></div><div class="tsk_sort fl <?php if($order_by=='status' && $order_sort != ''){echo 'tsk_'.strtolower($order_sort);}?>"></div>
                    </a>
                </th>
                <th style="width:5%" class="align_center"><?php echo __('Action');?></th>
            </tr>
            <?php if (!empty($customers)) { ?>
                <?php foreach ($customers as $cust) { ?>
                    <tr data-id="<?php echo $cust['InvoiceCustomer']['id']; ?>">
                        <td><?php echo $cust[0]['name']; ?></td>
                        <td><?php echo $cust['InvoiceCustomer']['organization']; ?></td>
                        <td><?php echo $cust[0]['details']; ?></td>                        
                        <td class="align_center"><?php echo $cust['InvoiceCustomer']['currency']; ?></td>
                        <td class="align_center"><?php echo __($cust['InvoiceCustomer']['status'], true); ?></td>
                        <td class="align_center"><center><a class="anchor edit_customer tooltip" title="<?php echo __('Edit'); ?>" style="display:block;"><span class="sprite note" style="left:0;"></span></a></center></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="12"><?php echo __('No records');?>......</td>
                </tr>
            <?php } ?>
            </table>
            <div class="cb"></div>
           
        </div>
    <div>
    <input type="hidden" id="getcasecount" value="<?php echo $caseCount; ?>" readonly="true"/>
    <?php if ($caseCount > 0) { ?>
        <div class="cb"></div>
        <div id='showCustomers_paginate'></div>
        <script type="text/javascript">
            pgShLbl = '<?php echo $this->Format->pagingShowRecords($caseCount, $page_limit, $casePage); ?>';
            var pageVars = {pgShLbl:pgShLbl,csPage:<?php echo $casePage; ?>,page_limit:<?php echo $page_limit; ?>,caseCount:<?php echo $caseCount; ?>};
            $("#showCustomers_paginate").html(tmpl("paginate_tmpl", pageVars)).show(); 
        </script>
        <div class="cb"></div>
    <?php } ?>
        <input type="hidden" id="totalcount" name="totalcount" value="<?php echo $count; ?>"/> 
        <div class="cb"></div>
    </div>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.tooltip').tipsy({gravity:'s',fade:true});
        $('.edit_customer').click(function(){
            var id = $(this).closest('tr').attr('data-id');
            $.post('<?php echo $this->Html->url(array('controller'=>'invoices','action'=>'customer_details'));?>',{id:id},function(res){
                if(typeof res.InvoiceCustomer != 'undefined'){
                    add_customer();
                    $.each(res.InvoiceCustomer,function(key,val){
                       switch(key){
                           case 'id': $("#cust_id").val(val);break;
                           case 'first_name': $("#cust_fname").val(val);break;
                           case 'last_name': $("#cust_lname").val(val);break;
                           case 'street': $("#cust_street").val(val);break;
                           case 'city': $("#cust_city").val(val);break;
                           case 'state': $("#cust_state").val(val);break;
                           case 'country': $("#cust_country").val(val);break;
                           case 'zipcode': $("#cust_zipcode").val(val);break;
                           case 'email': $("#cust_email").val(val);break;
                           case 'phone': $("#cust_phone").val(val);break;
                           case 'title': $("#cust_title").val(val);break;
                           case 'organization': $("#cust_organization").val(val);break;
                           case 'currency': $("#cust_currency").val(val);break;
                           case 'status': val == 'Inactive' ? $("#cust_status").attr('checked',true) : $("#cust_status").attr('checked',false);break;
                       }
                    });
                }
                $('.new_customer').find('.popup_title > span').html( _('Edit Customer') );
                $('#btn_add_customer').html(_('Update'));
                invoices.flag = true;
           },'json');
        });
    });
</script>