<?php echo $this->Html->script(array('ajaxfileupload','jquery.autogrow'));?>
<style type="text/css">
    .toggle-invoice-opts{background: #00bcd5 ;}
    .toggle-invoice-opts:hover{background: #07A1BA;}
    .no-border-radius{border-radius: 0px;}
    .crt-invoice-menu{left: auto; right: 0px; top: 30px;}
    .invoice-extra-block:hover .crt-invoice-menu{display:block;}
    .mr5{margin:5px;}
    .btn.invoice-btn{margin:0; padding: 6px 27px; font-family: 'OPENSANS-REGULAR';}
    .tab.tab_comon .nav-tabs.mod_wide{}
    .m_ig_cnt.timelog-detail-tbl{border:1px solid #ccc; width:97.1%; padding:15px 0px;}
    .timelog-detail-tbl .row{margin:0px;}
    #mlsttab{width:100%;}
    .timelog-table.bllbl_cnt{width:100%; border:0px; border-top:0px solid #ccc;}
    /*.timelog-table .timelog-detail-tbl td:last-child{border-right:0px;}*/
    .timelog-table{width:97%;font-family:"helvetica"}
    .timelog-table-head{margin:10px 0px 0px 5px;}
    .timelog-table-head .time-log-head{font-size: 20px;font-weight: normal;color:#444;}
    .logmore-btn a{width:150px; height:40px; color:#fff; background-color:#3FBA8B; font-size:14px; border-radius:5px;
                   display: block; padding:6px; text-align: center; text-decoration:none;} 
    .timelog-table-head .spent-time{margin:5px 0 10px;}
    .spent-time .total{font-size:16px;color:#444;}
    .spent-time .use-time{font-size:14px;color:#8E8E8E;}    
    .timelog-table .timelog-detail-tbl table {width:100%;}
    .timelog-table .timelog-detail-tbl th{background-color:#eee;font-size:13px;color:#222;padding:5px 5px; border:1px solid #CCC;border-left:0px none #CCC; font-weight:normal;  text-align: left;}
    .timelog-table .timelog-detail-tbl th:first-child{border-left:1px solid #CCC;}
    .timelog-table .timelog-detail-tbl td{border:1px solid #ccc;padding:8px 5px; }
    .timelog-table .timelog-detail-tbl table tr:hover { background-color: #ffffff;}
    .crt-task{font-size:14px;color:#558DD8;margin-left:10px; padding: 5px 0;}
    .ht_log{padding: 5px 0;}
    .sprite{background:url(img/sprite.png)no-repeat; position:relative; display:block; width:20px; height:20px;}
    .sprite.btn-clock{ background-position: 2px -63px;left:0;top:-17px;}
    .sprite.yes{background-position:2px -40px;left:-6px;top:0}
    .sprite.no{background-position:2px -20px;left:-6px;top:0}
    .sprite.up-btn{background-position: 2px -80px;left:-2px;top:0;}
    .sprite.note{ background-position:2px 0;left:8px;top:0;}
    /****** Add invoice css ********/
    #add_invoice {display:none;}
    #add_invoice .popup_form1{margin-left:-13px;}
    .crt_slide.task_action_bar, .task_action_bar_div .task_action_bar{background:none; border:0px; margin-top:5px;}
    #add_invoice .task_slide_in{padding:0px; height:40px;}
    #add_invoice.crt_task_slide table.create_table{margin-left:0px;}
    #add_invoice.crt_task_slide table.create_table tr td:first-child .lbl-m-wid{padding-left:0px;}
    /*********************Add Invoice form Css ************************/    
    #add_invoice_form {display:none;}
    #add_invoice_form .popup_form1{margin-left:-13px;}
    .crt_slide.task_action_bar, .task_action_bar_div .task_action_bar{background:none; border:0px; margin-top:5px;}
    #add_invoice_form .task_slide_in{padding:0px; height:40px;}
    #add_invoice_form.crt_task_slide table.create_table{margin-left:0px;}
    #add_invoice_form.crt_task_slide table.create_table tr td:first-child .lbl-m-wid{padding-left:0px;}
    .logtime-content .nav-tabs{ border-bottom: 1px solid #DCDADB;}
    .logtime-content.nav-tabs > li.active{background: none;}
    .logtime-content .nav-tabs li a { margin:0; padding:0; border: none; background: none; text-decoration: none;}
    .logtime-content .nav-tabs li a:hover,.logtime-content .nav-tabs li a:focus { margin:0; padding:0; border: none; background: none; text-decoration: none;}
    /*input:invalid {border:1px solid red;}*/
    .amount-align{text-align:right; padding-right:10px !important; }
    .subtotal-align{float:none; display: inline-block;width:auto;margin: 0; padding:0;}
    .sprite.no{left:25px;}
    /****************Resourcepopup********************/
    #show_resource_note{display:none;}
    .addLine{color: rgb(45, 103, 141);}
   .InvoiceDownloadEmail{display: block; float: left; width: 100%; padding: 10px 0 0;}
   .mt20{margin-top:20px;}
   .mr20{margin-right:20px !important;}
   .mr0{margin-right:0px !important;}
   textarea{resize: none;}
   .align_right{text-align: right !important;}
   .align_center{text-align: center !important;}
   .edit_description{padding: 6px 8px !important; line-height:12px;}
   .sprite.print {background: url("img/print.png") 1px 0px no-repeat;width: 19px;height: 17px;margin-left: 2px;position: relative;display: block;left: -11px;top: 3px;}
   .sprite.email {background:url("img/sprite_osv2.png") -266px -1px no-repeat; width: 19px;height: 17px;margin-left: 3px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.download {background: url("img/sprite_osv2.png") -267px -217px no-repeat;width: 19px;height: 17px;margin-left: 2px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.delete {background: url("img/sprite_osv2.png") -186px -170px no-repeat;width: 19px;height: 17px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.archive {background: url("img/sprite_osv2.png") -165px -170px no-repeat;width: 19px;height: 17px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.restore {background: url("img/sprite_osv2.png") -204px -148px no-repeat;width: 19px;height: 17px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.paid {background: url("img/paid.png") 0px 0px no-repeat;width: 19px;height: 17px;position: relative;display: block;left: -9px;top: 0;}
   .sprite.unpaid {background: url("img/unpaid.png") 0px 0px no-repeat;width: 19px;height: 17px;position: relative;display: block;left: -9px;top: 0;}
   .btn_invoice_munu{padding:0px;border-radius: 0px;width: 100%;  position: absolute;left: 0px;top: 20px;}
   .btn_invoice_munu li{display:list-item;text-align:center;  border-bottom: 1px solid #ccc; background: #3DBB89; list-style: none;}
   .btn_invoice_munu li a {width:100%;text-decoration:none;}
   .border-zero{border:0px none;}
   .form-control{box-shadow: none;}
   .timelog-detail-tbl table tr:hover{background: #fff;}
   .timelog-detail-tbl table tr.logrow:hover .form-control{box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, 0.6);}
   .invoice-list-option-btns span.sprite{display:inline-block;}
   
    /*invoice activity log start*/
    .invoice-date{max-height:400px;margin:40px 0 0 0;/*width:500px;*/overflow: hidden;overflow-y:auto;}
    .mnth_dt {margin:20px 0px 0px 20px;padding-left:20px;list-style-type:none;position:relative;border-left:1px dotted #333;}
    .bullet-blk{ background: #000 none repeat scroll 0 0;border: 1px solid #000; border-radius: 12px;
                 height:10px; left:-6px; position: absolute; top: 5%; width:10px;}
    .bullet-gry{ background: #ccc; border: 1px solid #ccc; border-radius: 12px; height:10px;
                 left:-26px; position: absolute; top: 10%; width:10px;}
    .mnth_dt .inv-dt{font-size:20px;color:#555;}
    .mnth_dt b{font-size:14px;color:#000;}
    /*.invo_view{margin:0px 0px 0px 0px;padding-left:20px;list-style-type:none;}*/
    .inv_lst{position:relative;}
    li.invo_view {margin:20px 0px 0px 0px;}
    .invo_view .inv-vw{color:#555;font-size:12px;margin-left:-12px;}
    .invo_view .inv-vw:not(b){font-size:13px;color:#000;}
    .invo_view .in-sent{color:#aaa;font-size:12px;margin-left:-12px;}
    .invo_view .in-sent:not(b){font-size:13px;color:#000;}
    .invo_view .s-time{font-size:12px;color:#aaa;}
   /*invoice activity log end*/
   .all_chk.ml5{margin:8px 0px 0px 5px}
   .timelog-detail-tbl .th_all .dropdown.active {background: #fff none repeat scroll 0 0;border: 1px solid #888888;border-radius:5px;padding:3px;cursor:pointer}
   .timelog-detail-tbl .th_all .dropdown.active ul{padding:5px;}
   .anchor {
    cursor: pointer;
}
.unbill_actv{
background: url("img/invoice.png") no-repeat scroll -28px -31px transparent;
    height: 17px;
    width: 19px;
}
.invoice_actv{
background: url("img/invoice.png") no-repeat scroll 0 -31px transparent;
    height: 17px;
    margin-right: 6px;
    width: 19px;
}
</style>
<div id="invoiceListing">     
    <div class="tab tab_comon" id="mlsttab">
        <ul class="nav-tabs mod_wide fl no-border">
            <?php if(defined('TLG') && TLG == 1 && SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) { ?>
                <li id="mlstab_act_unbill" >
                    <a class="anchor" onclick="switch_tab('logtime')" data-toggle="tooltip" title="<?php echo __("Unbilled Time"); ?>" >                           
                        <div class="unbill_actv fl"></div>
                        <div class="fl ellipsis-view maxWidth100 "><?php echo __('Unbilled Time');?> <span class="counter">(<?php print $caseCount;?>)</span></div>
                        <div class="cbt"></div>
                    </a>
                </li>
            <?php }  ?>
            <li id="mlstab_cmpl_invoice">
                <a class="anchor" onclick="switch_tab('invoice')" id="completed_tab" data-toggle="tooltip" title="<?php echo __('Invoice');?>"  >                            
                    <div class="invoice_actv fl"></div>
                    <div class="fl ellipsis-view maxWidth100"><?php echo __('Invoice');?> <span class="counter">(<?php print $invoiceCount;?>)</span></div>
                    <div class="cbt"></div>
                </a>
            </li>
            <li id="mlstab_cmpl_archived_invoice">
                <a class="anchor" onclick="switch_tab('archived')" id="archived_tab" data-toggle="tooltip" title="<?php echo __('Archived');?>" >                            
                    <div class="invoice_arcvd fl"></div>
                    <div class="fl ellipsis-view maxWidth100"><?php echo __('Archived');?> <span class="counter">(<?php print $archivedinvoiceCount;?>)</span></div>
                    <div class="cbt"></div>
                </a>
            </li>
            <?php if(SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) {?>
                <li id="tab_manage_customers" style="float:right;border-left:1px solid #dcdadb;">
                    <a class="anchor ellipsis-view  " onclick="switch_tab('customers')" title="<?php echo __('Manage Customers');?>"><?php echo __('Manage Customers');?></a>
                </li>
            <?php }  ?>
        </ul>
    </div>
    <div class="cbt"></div>
    <div id="show_invoiceListing"></div>
    <div class="Invoice_DownloadEmail" style="display: none;" ></div>
</div>
<div id="caseLoader">    
    <div class="loadingdata"> <?php echo __('Loading');?>...</div>
</div>
<div id='showUnbilled'></div>
<div id='showInvoiceDiv' style="margin-top:10px;"></div>
<div id='showArchivedInvoiceDiv' style="margin-top:10px;"></div>
<div id='showCustomers'></div>
<!--********** popup ********************* -->
<div id="add_invoice"  class="cmn_popup" >
    <div class="popup_bg">
        <div style="">
            <div class="popup_title">
                <span><i class="icon-create-project"></i><?php echo __('Add Unbilled time to Invoice');?></span>
                <a onclick="closePopup();" href="javascript:jsVoid();"><div class="fr close_popup">X</div></a>
            </div>
            <div class="popup_form" style="margin-top: 20px;">            
                <div style="" id="inner_log">
                    <form onsubmit="" method="POST" action="<?php echo $this->Html->url(array('controller' => 'easycases', 'action' => 'addInvoice')); ?>">
                        <div class="logtime-content">
                            <div style="margin:15px 30px;">
                                <label><?php echo __('Choose Invoice');?></label>
                                <div>                                        
                                    <select name="invoiceList" id="invoiceList" class="form-control" onchange="checkInvoice($(this));">
                                        <option value="0"><?php echo __('Add New Invoice');?>...</option>
                                        <?php
                                        if (!empty($invoice)) {
                                            foreach ($invoice as $k => $v) {
                                                echo "<option value='" . $k . "'>" . $v . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>__(Select)</option>";
                                        }
                                        ?>
                                        
                                    </select>
                                </div>
                            </div>          
                            <div class="log-btn">
                                <button class="btn btn_blue" name="submitInvoice" type="button" onclick="assign2Invoice();"><i class="icon-big-tick"></i><?php echo __('Update');?></button>
                                <span class="or_cancel cancel_on_direct_pj">or
                                    <a onclick="closePopup();"><?php echo __('Cancel');?></a>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="cb"></div>

<!--********** popup ********************* -->

<div id="add_invoice_form" class="cmn_popup">
    <div class="popup_bg">
        <div style="" class="new_invoice_form invoice_add_popup">
            <div class="popup_title">
                <span><i class="icon-create-project"></i><?php echo __('Generate Invoice');?></span>
                <a onclick="closeInvoicePopup();" href="javascript:jsVoid();"><div class="fr close_popup">X</div></a>
            </div>
            <div class="popup_form" style="margin-top: 20px;">
                <div class="loader_dv" style="display: none;"><center><img title="<?php echo __('Loading');?>..." alt="<?php echo __('Loading');?>..." src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif"></center></div>
                <div style="" id="inner_log">                 
                   <?php echo $this->Form->create(__('Invoice'), array('url' => array('controller' => 'easycases', 'action' => 'addInvoice'),'type'=>'post','id'=>'invoiceForm')); ?>               
                        <div class="logtime-content">

                            <div role="tabpanel">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#essential" aria-controls="home" role="tab" data-toggle="tab" title="<?php echo __('Essentials');?>"><?php echo __('Essentials');?></a></li>
                              <li role="presentation"><a href="#optional" aria-controls="profile" role="tab" data-toggle="tab" title="<?php echo __('Optional');?>"><?php echo __('Optional');?></a></li>   
                            </ul>

                            <!-- Tab panes -->
                              <div class="tab-content">
                                  <div role="tabpanel" class="tab-pane active" id="essential">                        
                                      <div style="margin:15px 30px;">
                                          <label><?php echo __('Invoice');?> # <span>*</span></label>
                                          <div>   
                                              <?php echo $this->Form->input('invoice_no', array('label' => false,'class'=>'form-control required','required'=>'required'));?>
                                          </div>
                                           <label><?php echo __('Invoice Date');?> <span>*</span></label>
                                            <div>   
                                              <?php echo $this->Form->input('issue_date', array('type' =>'text','label' => false,'id' => 'invoice_issue_data','class' => 'form-control','required'=>'required'));?>
                                          </div>
                                          <label><?php echo __('Due Date');?> <span>*</span></label>
                                            <div>   
                                              <?php echo $this->Form->input('due_date', array('type' =>'text','label' => false,'id' => 'invoice_due_data','class' => 'form-control','required'=>'required'));?>
                                          </div>

                                          <label> <?php echo __('Currency');?> <span>*</span></label>
                                            <div>   
                                              <select name="data[Invoice][currency]" class="form-control" required >                                   
                                                  <option value="usd"><?php echo __('USD');?> - <?php echo __('US Dollar');?></option>
                                              </select>
                                          </div>
                                             <label><?php echo __('Rate');?> <span>*</span></label>
                                            <div>   
                                                <?php echo $this->Form->input(__('price'), array('label' => false,'class'=>'form-control required','required'=>'required'));?>
                                                <!--<select name="data[Invoice][price]" class="form-control" required >                                   
                                                  <option value="0">Price will be based on time and expenses </option>
                                              </select> -->
                                            </div>
                                      </div>
                                  </div>
                                  <div role="tabpanel" class="tab-pane" id="optional">
                                        <div style="margin:15px 30px;">
                                              <label><?php echo __('Note');?> <span>(<?php echo __('optional');?></span></label>
                                             <div>   
                                                 <textarea name="data[Invoice][notes]" class="form-control"></textarea>
                                             </div>
                                              <label><?php echo __('Terms');?> <span>(<?php echo __('optional');?></span></label>
                                             <div>   
                                                  <textarea name="data[Invoice][terms]" class="form-control"></textarea>
                                            </div>
                                      </div>
                                  </div>
                                  <div class="log-btn">
                                      <button class="btn btn_blue" name="crtLogTimeSave" value="Create&amp;Save" type="submit"><i class="icon-big-tick"></i><?php echo __('Generate Invoice');?></button>
                                      <span class="or_cancel cancel_on_direct_pj"><?php echo __('or');?>
                                          <a onclick="closeInvoicePopup();"><?php echo __('Cancel');?></a>
                                      </span>
                                  </div>
                              </div>

                        </div>
                    </div>
                    <?php echo $this->Form->end();?>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="cb"></div>
<!--********** popup ********************* -->
<div id="EmailTemplate" class="cmn_popup" style="display:none;" >
    <div class="popup_bg">
        <div style="">
            <div class="popup_title">
                <span><i class="menu_os_invoice"></i><?php echo __('Send invoice');?></span>
                <a onclick="closeEmailPopup();" href="javascript:jsVoid();"><div class="fr close_popup">X</div></a>
            </div>
            <div class="popup_form" style="margin-top: 20px;">            
                <div style="" id="inner_log">                
                    <form onsubmit="" id="senEmailForm" method="POST" action="<?php echo $this->Html->url(array('controller' => 'invoices', 'action' => 'sendInvoiceEmail')); ?>">
                    <?php print $this->Form->input('invoiceId', array('label'=>false,'type'=>'hidden','value'=>$i['Invoice']['id'])); ?>
                    <table class="col-lg-12 new_auto_tab" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="v-top" style="width:100px;"><?php echo __('From');?>:</td>
                            <td class="marginbottom10"> 
                                <?php echo $this->Form->input('from', array('label' => false,'class'=>'form-control required','required'=>'required','value'=>@$email,'style'=>'border-radius:5px'));?>
                                <div id="from_err" style="font-size:12px;color:red;margin:3px"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="v-top" style="width:100px;"><?php echo __('To');?>:</td>
                            <td class="marginbottom10">   
                                <?php echo $this->Form->input('to', array('label' => false,'class'=>'form-control required','required'=>'required','style'=>'border-radius:5px'));?>
                                <div id="to_err" style="font-size:12px;color:red;margin:3px"></div>
                            </td>
                        </tr>
                        <tr>
                            <td class="v-top" style="width:100px;"><?php echo __('Subject');?>:</td>
                            <td class="marginbottom10">   
                                <?php echo $this->Form->input('subject', array('label' => false,'class'=>'form-control required','required'=>'required','style'=>'border-radius:5px'));?>
                            </td>
                        </tr>
                        <tr>
                            <td class="v-top" style="width:100px;"><?php echo __('Message');?>:</td>                             
                            <td class="marginbottom10">   
                               <textarea id="message" name="message" class="form-control" style="height:90px;border-radius:5px;"></textarea>
                            </td>   
                        </tr>
                        <tr>   
                            <td><img class="mceIcon" src="<?php print HTTP_IMAGES ;?>images/attach.png"></td>
                            <td style="text-align:left;"><a href="javascript:void(0);" id="downloadPDFEmailAddress" onclick="buildInvoicePdf('0')" style="color:blue;"> <?php echo __('Invoice');?> # <span id="pop_email_invoice_no"></span></a></td>
                        </tr>
                           
                        </table>    
                        <div id="invoice_btnn" class="log-btn" style="text-align:center;margin-bottom:15px;">
                            <button id="invoice_send_email" class="btn btn_blue" name="submitInvoice" type="button" onclick="sendInvoicebyEmail();"><i class="icon-big-tick"></i><?php echo __('Send');?></button>
                            <span class="or_cancel cancel_on_direct_pj"><?php echo __('or');?>
                                <a onclick="closeEmailPopup();"><?php echo __('Cancel');?></a>
                            </span>
                        </div>
                        <img src="<?php echo HTTP_ROOT."img/images/case_loader2.gif"; ?>" id="invoice_loader" style="display:none;margin-left:220px;"/>

                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
$(function(){
        $('#select_view').show();
        $('#select_view_mlst').hide();
        $('.filter_rt').hide();
        $('#calendar_btn').removeClass('disable');
        $('#actvt_btn').removeClass('disable');
        $('#kbview_btn').removeClass('disable');
        $('#lview_btn').removeClass('disable');
        $('#timelog_btn').removeClass('disable');
        $('#files_btn').removeClass('disable');
        $('#invoice_btn').addClass('disable');
        $('#filtered_items').hide();
        $('#filtered_items').html('');
        $('#mlist_crt_mlstbtn').hide();
        $('#savereset_filter').hide();
    });

    function cancelInvoice(){
        switch_tab(get_mode());
    }

    var save_flag = true;
    function saveInvoice(inv_id,mode){
        var mode = typeof mode != 'undefined' ? mode : '';
        $('#caseLoader').show();
        if($("#frm_create_invoice").isChanged() === true){
            $("#ismodified").val('Yes');
        }else{
            $("#ismodified").val('No');
        }
        if(save_flag){
            save_flag = false;
            $.ajax({
                url:$('#frm_create_invoice').attr('action'),
                data:$('#frm_create_invoice').serialize(),
                method:'post',
                dataType:'json',
                success:function(response){
                    save_flag = true;
                    $('#caseLoader').hide();
                    if(response.success == 'No'){
                        showTopErrSucc('error', response.msg);
                        return false;
                    }
                    $("#invoice_id").val(response.id);

                    if(mode == 'export_email' || mode == 'export_pdf' || mode == 'preview'){
                        showInvoicePage(response.id,$('#edit_invoiceNo').val(),'',mode);
                    }else if(mode == 'close'){
                        window.location.hash = 'invoice';
                        switch_tab('invoice');
                    }else if(mode == 'createnew'){
                        showInvoicePage('','New Invoice','new');
                    }else{
                        window.location.hash = 'invoice';
                        switch_tab('invoice');
                    }                
                }
            });
        }
    }

    function addToInvoice() {
         $(".popup_overlay").css({display: "block"});
         $('#add_invoice').show();
         $('.popup_bg').show();
         $('.invoice_popup').show();
     }

    function addInvoideForm(){
         $(".popup_overlay").css({display: "block"});
         $('#add_invoice_form').show();
          $('.popup_bg').show();
         $('.invoice_add_popup').show();
     }

    function closeInvoicePopup(){                       
         $('#add_invoice_form').hide();
         $('.invoice_add_popup').hide();
     }

     function sendInvoiceEmail(v,params){
        $("#invoiceId").val(v);
        $('#downloadPDFEmailAddress').attr('onclick',$('#downloadPDFEmailAddress').attr('onclick').replace(/\d+/g,v));
        params = typeof params == 'undefined' ? '':params;
        var user = typeof params.name !='undefined' ? params.name : $('#invoice_customer_opts').find('.opt1').html();
        var from = typeof params.from !='undefined' ? params.from : $("#invoice_company_name").val();
        //$("#subject").val(typeof params.subject !='undefined' ? params.subject : $('#edit_invoiceNo').val());
        $("#subject").val('Invoice from '+from);
        $('#pop_email_invoice_no').html(typeof params.subject !='undefined' ? params.subject : $('#edit_invoiceNo').val());
        
        if(trim($('#edit_invoice_to').val())!='' && typeof from !='undefined' && typeof name !='undefined' && trim(user) !='Choose a Customer'){
            $("#to").val(typeof params.to !='undefined' ? params.to : $('#invoice_customer_email').val());
            if(user!='')$("#message").val("Hi "+user+',\n Here\'s your invoice! We appreciate your prompt payment.\n\nThanks for your business!\n'+from);
        }
        $(".popup_overlay").css({display: "block"});
        $('#EmailTemplate').show();
        $('.popup_bg').css({'width':'540px'});
        $('.popup_bg').show();
    }

    function sendInvoicebyEmail(){
                var to = $('#to').val();
                var from = $('#from').val();
                var subject = $('#subject').val();
                var error = 0;
                var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                var letterNumber = /^[0-9a-zA-Z]+$/;

                if(to == "") {
                        $("#to").css({"border":"1px solid #FF0000"});
                        $("#to").focus();
                        error = 1;
                }else if(!to.match(emailRegEx)){
                        $("#to").css({"border":"1px solid #FF0000"});
                        $("#to_err").html('Invalid email');
                        $("#to").focus();
                        error = 1;
                }

                if(from == "") {
                        $("#from").css({"border":"1px solid #FF0000"});
                        $("#from").focus();
                        error = 1;
                }else if(!from.match(emailRegEx)){
                        $("#from").css({"border":"1px solid #FF0000"});
                        $("#from_err").html('Invalid email');
                        $("#from").focus();
                        error = 1;
                }

                if(subject == "") {
                        $("#subject").css({"border":"1px solid #FF0000"});
                        $("#subject").focus();
                        error = 1;
                }

                if(error == 1){
                        $('#to').focus();
                        return false;
                }else{
                        $('#invoice_btnn').hide();
                        $('#invoice_loader').show();
                        var e=$("#senEmailForm");
                        v=e.serialize();
                        $.post('<?php echo $this->Html->url(array('controller' => 'invoices', 'action' => 'sendInvoiceEmail')) ?>', {v: v}, function(res) {
                                        if (res == "success") {
                                                $('#invoice_loader').hide();
                                                $('#invoice_btnn').show();
                                                closeEmailPopup();
                                                showTopErrSucc('success',_("Email sent successfully."));
                                        }else{
                                                $('#invoice_loader').hide();
                                                $('#invoice_btnn').show();
                                                showTopErrSucc('error',_("Email not sent due to some problem."));
                                        }

                   });
           }
    }
                
    function closeEmailPopup(){
        $(".popup_overlay").css({display: "none"});
        $('#EmailTemplate').hide();
        $('.popup_bg').hide();
        $('#to').val('');
        $('#to').css({"border":"1px solid #ccc"});
        $('#from').css({"border":"1px solid #ccc"});
        $('#subject').val('');
        $('#subject').css({"border":"1px solid #ccc"});
        $('#message').val('');
    }

    function buildInvoicePdf(v,mode){
        $('#caseLoader').show();
        $.post('<?php echo $this->Html->url(array('controller' => 'invoices', 'action' => 'createInvoicePdf')) ?>', {v: v}, function(res) {
            if (parseInt(res) != 0) {
                $('#caseLoader').hide();
                // document.location = '<?php echo $this->Html->url(array('controller' => 'invoices', 'action' => 'downloadPdf')) ?>/'+v;
                //url = '<?php echo $this->Html->url(array("controller" => "easycases", "action" => "downloadPdf")) ?>/' + v + (typeof mode != 'undefined' ? '/'+mode : '');
                 url = '<?php echo HTTP_ROOT."easycases/downloadPdf"; ?>/' + v + (typeof mode != 'undefined' ? '/'+mode : '');
                window.open(url,'_blank');
            }
        });
    }

    function markPaidInvoice(id){
        $('#caseLoader').show();
        $.post(HTTP_ROOT+'markInvoicePaid', {id: id}, function(res) {
            if (parseInt(res) != 0) {
                $('#caseLoader').hide();
                showTopErrSucc('success', _('Invoice successfully marked as paid.'));
                var mode = get_mode();
                switch_tab(mode);
            }
        });
    }
    
    function markUnpaidInvoice(id){
        $('#caseLoader').show();
        $.post(HTTP_ROOT+'markUnpaidInvoice', {id: id}, function(res) {
            if (parseInt(res) != 0) {
                $('#caseLoader').hide();
                showTopErrSucc('success', _('Invoice successfully marked as unpaid.'));
                var mode = get_mode();
                switch_tab(mode);
            }
        });
    }
    function markGroupInvoicePaid(){
        var ids = new Array();
        $('.checkbox2:checked').each(function(){
            if($(this).is(':checked')){
                ids.push($(this).val());
            }
        });
        if(ids.length > 0){
            $('#caseLoader').show();
            $.post(HTTP_ROOT+'markGroupInvoicePaid', {ids: ids}, function(res) {
                if (parseInt(res) != 0) {
                    $('#caseLoader').hide();
                    showTopErrSucc('success', _('Invoices successfully marked as paid.'));
                    var mode = get_mode();
                    switch_tab(mode);
                }
            });
        }else{
            return false;
        }
    }
    
    function archiveInvoice(id){
        $('#caseLoader').show();
        $.post(HTTP_ROOT+'archiveInvoice', {id: id}, function(res) {
            if (parseInt(res) != 0) {
                $('#caseLoader').hide();
                showTopErrSucc('success', _('Invoice successfully archived.'));
                var mode = get_mode();
                switch_tab(mode);
            }
        });
    }
    
    function restoreInvoice(id){
        $('#caseLoader').show();
        $.post(HTTP_ROOT+'restoreInvoice', {id: id}, function(res) {
            if (parseInt(res) != 0) {
                $('#caseLoader').hide();
                showTopErrSucc('success', _('Invoice successfully restored.'));
                var mode = get_mode();
                switch_tab(mode);
            }
        });
    }
    
    function markGroupInvoiceUnpaid(){
        var ids = new Array();
        $('.checkbox2:checked').each(function(){
            if($(this).is(':checked')){
                ids.push($(this).val());
            }
        });
        if(ids.length > 0){
            $('#caseLoader').show();
            $.post(HTTP_ROOT+'markGroupInvoiceUnpaid', {ids: ids}, function(res) {
                if (parseInt(res) != 0) {
                    $('#caseLoader').hide();
                    showTopErrSucc('success', _('Invoices successfully marked as unpaid.'));
                    var mode = get_mode();
                    switch_tab(mode);
                }
            });
        }else{
            return false;
        }
    }
    
    function groupRestoreInvoice(){
        var ids = new Array();
        $('.checkbox3:checked').each(function(){
            if($(this).is(':checked')){
                ids.push($(this).val());
            }
        });
        if(ids.length > 0){
            $('#caseLoader').show();
            $.post(HTTP_ROOT+'groupRestoreInvoice', {ids: ids}, function(res) {
                if (parseInt(res) != 0) {
                    $('#caseLoader').hide();
                    showTopErrSucc('success', _('Invoices successfully restored.'));
                    var mode = get_mode();
                    switch_tab(mode);
                }
            });
        }else{
            return false;
        }
    }
    
    function groupArchiveInvoice(){
        var ids = new Array();
        $('.checkbox2:checked').each(function(){
            if($(this).is(':checked')){
                ids.push($(this).val());
            }
        });
        if(ids.length > 0){
            $('#caseLoader').show();
            $.post(HTTP_ROOT+'groupArchiveInvoice', {ids: ids}, function(res) {
                if (parseInt(res) != 0) {
                    $('#caseLoader').hide();
                    showTopErrSucc('success', _('Invoices successfully archived.'));
                    var mode = get_mode();
                    switch_tab(mode);
                }
            });
        }else{
            return false;
        }
    }

    function checkInvoice(e) {
        if (e.val() != '') {
            if (e.val() == 0) {
                var time_logs=$(".invoicechkbox:checked").map(function () {return this.value;}).get().join(","); 
                showInvoicePage('','New Invoice',time_logs);
                closePopup();
                //$( "#invoiceForm" ).submit();
                //addInvoideForm();
            }
        }

    }
    function updateSelectBox(){
        $.post('<?php echo $this->Html->url(array('controller'=>'invoices','action'=>'updateInvoicedropdown'));?>',{},function(res){
            $('#invoiceList').html(res);
        });
    }
    function assign2Invoice(){ 
        if($('#invoiceList').val()!='' && $('#invoiceList').val()!=0 ) {
            var invoiceId=$('#invoiceList').val();
            var time_logs=$(".invoicechkbox:checked").map(function () {return this.value;}).get().join(",");                                                
            $.post('<?php echo $this->Html->url(array('controller'=>'invoices','action'=>'add2Invoice'));?>',{invoice:invoiceId,log:time_logs},function(res){
                closePopup();
                showInvoicePage(invoiceId,$("#invoiceList").find('option:selected').html());
                //invoices.switch_tab('invoice');
            });
        }else{
            showTopErrSucc('error', _('Please select an invoice or create new invoice.'));
        }
    }
    function assign2InvoiceDirect(id,val){
        if(id !='' && id !=0 ) {
           var invoiceId=id;
           var time_logs=$(".invoicechkbox:checked").map(function () {return this.value;}).get().join(",");                                                
           $.post('<?php echo $this->Html->url(array('controller'=>'invoices','action'=>'add2Invoice'));?>',{invoice:invoiceId,log:time_logs},function(res){
                closePopup();                            
                showInvoicePage(id,val);
            });
        }
    }
    
    function showInvoicePage(v,n,ids,mode){
            var ids = typeof ids != 'undefined' ? ids : '';
            $('#mlstab_cmpl_invoice,#mlstab_act_unbill,#mlstab_cmpl_archived_invoice').removeClass('active');
            $('.newInvoice').remove();
            $('#mlsttab').find('#mlstab_cmpl_archived_invoice').after('<li class="active newInvoice"><a class="anchor"><div class="fl">'+n+'</div> <div class="cbt"></div> </a></li>');
            $('#showUnbilled').hide(); 
            $('#showArchivedInvoiceDiv').hide();
            $('#showInvoiceDiv').show(); 
            //$('#showInvoiceDiv').html('');
            $('#caseLoader').show();
            $.post('<?php echo $this->Html->url(array('controller'=>'invoices','action'=>'ajaxInvoicePage'));?>',{v:v,log:ids},function(res){
                    $('#caseLoader').hide();
                    $('#showInvoiceDiv').html(res);
                    $('.InvoiceDownloadEmail').show();
                    $('.InvoiceDownloadEmail').find('.saveInvoice').each(function(){
                        $(this).attr('onclick',$(this).attr('onclick').replace(/\d+/g,v));
                    });
                    trim($("#edit_invoiceNo").val())!=''?$('.newInvoice').find('.fl').html($("#edit_invoiceNo").val()):'';
                    if(mode == 'export_email'){
                        sendInvoiceEmail(v);
                    }else if(mode == 'preview'){
                        buildInvoicePdf(v,mode);
                    }else if(mode == 'export_pdf'){
                        buildInvoicePdf(v);
                    }
            });
            showCounter();
    }
    
    function switch_tab(mode){
        <?php if(SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) {?>
        <?php }else{?>
            mode = 'invoice';
        <?php }?>
        reset_tabs();
        window.location.hash = mode;
        $('#showArchivedInvoiceDiv').hide();
        $('#mlstab_cmpl_archived_invoice').removeClass('active');
        if(mode == 'customers'){
            $('#tab_manage_customers').addClass('active');
            $('#showCustomers').show();//.html('');
            $('#caseLoader').show();        
        }else if(mode == 'invoice'){
            $('#mlstab_cmpl_invoice').addClass('active');
            $('#showInvoiceDiv').show();
            $('#caseLoader').show();
        }else if(mode == 'archived'){
            $('#mlstab_cmpl_archived_invoice').addClass('active');
            $('#showArchivedInvoiceDiv').show();
            $('#caseLoader').show();
        }else{
            $('#mlstab_act_unbill').addClass('active');            
            $('#showUnbilled').show();//.html('');                        
            $('#caseLoader').show();
            updateSelectBox();
        }
        casePaging(1);
        showCounter();
    }

    function casePaging(page){
    var act_url = '';
    var mode = get_mode();
    if(mode == 'invoice'){
        act_url = '<?php echo $this->Html->url(array('controller'=>'invoices','action'=>'ajaxInvoiceList'));?>';
    }else if(mode == 'customers'){
        act_url = '<?php echo $this->Html->url(array('action'=>'ajaxCustomerList'));?>';
    }else if(mode == 'archived'){
        act_url = '<?php echo $this->Html->url(array('action'=>'ajaxArchivedInvoiceList'));?>';
    }else{
        act_url = '<?php echo $this->Html->url(array('controller'=>'invoices','action'=>'ajaxTimeList'));?>';
    }
    var params = {};
    params.sortby = getCookie("INVOICE_SORTBY");
    params.order = getCookie("INVOICE_SORTORDER");
    
    $.post(act_url,{page:page,params:params},function(res){
        $('#caseLoader').hide();
        if(mode == 'invoice'){ 
            $('#showInvoiceDiv').html(res);
        }else if(mode == 'customers'){
            $('#showCustomers').html(res);
        }else if(mode == 'archived'){
            $('#showArchivedInvoiceDiv').html(res);
        }else{ 
            $('#showUnbilled').html(res);
        }
    });
}

/*it is used to update top counters in tab*/
    function showCounter(){
        $.post('<?php echo $this->Html->url(array('controller'=>'invoices','action'=>'getCountInvoice'));?>',{},function(res){
            r=$.parseJSON(res);                            
            $('#mlstab_cmpl_invoice').find('.counter').html('('+r.invoice+')');
            $('#mlstab_cmpl_archived_invoice').find('.counter').html('('+r.archived+')');
            $('#mlstab_act_unbill').find('.counter').html('('+r.logtime+')');
        });
    }


    function add_customer(){
        $("#more_customer_options_cust").closest('tr').show();
        $('.customer_options').hide();
        $('#frm_add_customer').find('input[type=text],input[type=hidden],select').val('');
        $('#frm_add_customer').find('input[type=checkbox]').attr('checked',false);
        $("#cust_err_msg").html('');
        $('#cust_loader').hide();
        $('#more_opt1123123').hide();
        $("#btn_add_customer").show();
        $('.new_customer').find('.popup_title > span').html('<?php echo __('Add New Customer');?>')
        openPopup();
        $('#add_invoice').hide();
        $('.new_customer').show();        
        $('.loader_dv').hide();
        $('#btn_add_customer').html('Create');
    }

    function opt_list_action(mode,id,customer){
        if(mode == 'email'){
            var subject = $('#invoice_list'+id).attr('data-no');
            var from = '<?php echo $from;?>';
            var params = {subject:subject,from:from,name:'',to:''};
            if(customer>0){
                $.post('<?php echo $this->Html->url(array('controller'=>'invoices','action'=>'customer_details'));?>',{id:customer},function(res){
                    if(typeof res.InvoiceCustomer != 'undefined'){
                        params.to=res.InvoiceCustomer.email!=null?res.InvoiceCustomer.email:'';
                        var fname=res.InvoiceCustomer.first_name!=null?res.InvoiceCustomer.first_name:'';
                        var lname=res.InvoiceCustomer.last_name!=null?res.InvoiceCustomer.last_name:'';
                        var title=res.InvoiceCustomer.title != null ? res.InvoiceCustomer.title : '';                    
                        params.name = title+' '+fname+' '+lname;
                    }
                    //console.log(params)
                    sendInvoiceEmail(id,params);
                },'json');
            }else{
                sendInvoiceEmail(id,params);
            }
        }else if(mode == 'download'){
            buildInvoicePdf(id);
        }else if(mode == 'print'){
            buildInvoicePdf(id,'preview');
        }else if(mode == 'paid'){
            markPaidInvoice(id);
        }else if(mode == 'unpaid'){
            markUnpaidInvoice(id);
        }else if(mode == 'archive'){
            archiveInvoice(id);
        }else if(mode == 'restore'){
            restoreInvoice(id);
        }
    }

    $(document).ready(function() {
        $(document).click(function(e){
            $('#more_opt1123123').size()>0?($(e.target).closest('#invoice_customer_opts').size()>0?'':$('#more_opt1123123').hide()):'';
            $('#btn_invoice_munu').size()>0?($(e.target).closest('.inv_bts').size()>0?'':$('#btn_invoice_munu').hide()):'';
        });
        
        switch_tab(get_mode());

        /*add new customer pop-up start*/
        $("#btn_add_customer").click(function(){
            add_customer_action();
        });
        $("#more_customer_options_cust").click(function(){
            $(this).closest('tr').hide();
            $('.customer_options').show();
        });
        /*add new customer pop-up end*/


        $(document).on("click",".toggle-invoice-opts",function() {
            return false;
        });
        $(document).on("click",".create-invoice-without-unbilled-time",function() {
            //$(this).closest('.relative').find('.crt-invoice-menu').slideUp();
        });
        $('#ids').on('click',function(event) {  //on click
            $obj = $(this);
            $('.invoicechkbox').attr('checked',$obj.attr('checked'));
        });
        var checkboxes = $("input[type='checkbox'].invoicechkbox");
        checkboxes.click(function() {
            $('#ids').attr('checked',($('.invoicechkbox').not(':checked').length > 0?false:true));
            $('.btnaddto').attr("disabled", ! $('.invoicechkbox').is(":checked"));                         
            (!$('.invoicechkbox').is(":checked"))?$('.btnaddto').removeClass('btn_blue'):$('.btnaddto').addClass('btn_blue');

        });

        $("#invoice_issue_data").datepicker({
                    dateFormat: 'M d, yy',
                    changeMonth: false,
                    changeYear: false,
                    hideIfNoPrevNext: true,
                    onClose: function( selectedDate ) {
                            $("#invoice_due_data").datepicker( "option", "minDate", selectedDate );
                    },

        });
        $("#invoice_due_data").datepicker({
                    dateFormat: 'M d, yy',
                    changeMonth: false,
                    changeYear: false,
                    hideIfNoPrevNext: true,
                    onClose: function( selectedDate ) {
                            $("#invoice_issue_data").datepicker( "option", "maxDate", selectedDate );
                    },

        });

        $( "#invoiceForm" ).on( "submit", function( event ) {
            event.preventDefault();  
            var e=$( this );
            v=e.serialize();
            $.post('<?php print $this->Html->url(array('controller'=>'easycases','action'=>'addInvoice'));?>',{v:v},function(res){
                if(parseInt(res)!=0){
                    updateSelectBox(); 
                    closeInvoicePopup();                                    
                    $('#invoiceList').val(res);                                  
                    assign2InvoiceDirect(parseInt(res),$('#InvoiceInvoiceNo').val());  
                    e[0].reset(); 
                }else{                                    
                }
            });
        });
    });
    $.fn.extend({trackChanges:function(){$(":input",this).change(function(){$(this.form).data("changed",true);});},isChanged:function(){return this.data("changed");}});
</script>
<?php echo $this->Html->script('main_invoice'); ?>
<script type="text/template" id="paginate_tmpl">
<?php echo $this->element('paginate'); ?>
</script>
