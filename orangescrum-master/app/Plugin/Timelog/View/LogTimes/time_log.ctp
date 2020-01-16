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
    .timelog-table{width:100%;font-family:"helvetica"}
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
    /*.sprite.no{left:25px;}*/
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
	.timelog-detail-tbl .e-d-icon{text-align:center}
	.timelog-detail-tbl .e-d-icon a{display:inline-block;margin:0 5px}
	.timelog-detail-tbl .e-d-icon a span{display:inline-block}
    .anchor {
        cursor: pointer;
    }
    .input-disabled{background-color:#EBEBE4;border:1px solid #ABADB3;padding:2px 1px;}
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
	.timelog-fltr{padding-left:0}
    #paid_div > input {margin-right: 5px;}
    #unpaid_div > input {margin-right: 5px;}
    .timelog-fltr .rsrc-fltr{margin:0px 0px}
    .timelog-fltr .dt-fltr{margin:10px 0px}
    .timelog-fltr .dt-fltr input.form-control{width:21%;margin-right: 20px}
    .timelog-fltr .rsrc-fltr select.form-control{height:40px;width:73%;}
    .timelog-fltr .dt-fltr span.dt-hypen{margin-right:20px; line-height: 35px;display: inline-block}
    .timelog-fltr .fltr-txt{margin-right:5px;width:100px;display:inline-block;line-height: 35px;}
    .timelog-fltr .filter-info{margin:10px 0px 10px 0px;padding:0}
    .timelog-fltr .filter-info .db-filter-reset-icon{display:inline-block}
</style>
<?php if(SES_TYPE < 3){ ?>
<div class="col-lg-7 timelog-fltr" id="tlg_fltr">
    <div class="row rsrc-fltr">
        <span class="fl fltr-txt"><?php echo __("Resource"); ?>:</span>
        <select class="fl form-control"style="" id="slct_rsrc" onchange="filterByResource(this);">
            <option value=''>All Resources</option>
            <?php foreach($resCaseProj as $k=>$v){
                    echo "<option value='".$v['User']['id']."' data-unqid='".$v['User']['uniq_id']."'>".$v['User']['name']." ".$v['User']['last_name']."</option>";
            } ?>
        </select>
        <input type="hidden" id="tlog_resource" value=""/>
    </div>
    <div class="row dt-fltr">
        <span class="fl fltr-txt"><?php echo __("Date"); ?>:</span>
        <?php 
            if(strpos($_COOKIE['datelog'], ':')){
                $dt = explode($_COOKIE['datelog'], ':');
                $frm = $dt[0];
                $to = $dt[1];
            }
        ?>
        <input type="text" name="from-date" class="form-control fl" value="" placeholder="From Date" readonly  id="logstrtdt" value="<?php echo $frm; ?>"/> <span class="fl dt-hypen">-</span> <input type="text" name="to" class="fl form-control"value="" placeholder="To Date" readonly id="logenddt" value="<?php echo $to; ?>"/>
        <button class="fl btn btn_blue" onclick="general.filterDate('timelog', 'custom', 'Custom');" id="btn_timelog_search"><?php echo __("Search"); ?></button> 
    </div>
    <div id="resetdiv" class="filter-info col-lg-12">
        <span style="display:inline-block"><?php echo __('Showing time log');?>:</span><span id="filter_text" class="filter-text">&nbsp;<?php if(!empty($filter_text)){echo $filter_text;}else{echo __("for all users and all dates");} ?></span>
        <div id="btn-reset-timelog" class="db-filter-reset-icon" onclick="hidereset();" rel="tooltip" <?php if($logtimesArr['reset'] == 1){?>style="display:inline-block;"<?php }else{ ?>style="display:none;"<?php } ?> original-title="Reset Filters"></div>
    </div>
    <div class="cb"></div>

</div>
<div class="col-lg-5 fr" style="margin:7px auto;padding-right: 0" id="tlg_exprt">
    <div class="logmore-btn fr"  title="">
        <a class="anchor" style="padding-left: 0px;margin-right:8px; width:150px; padding-right: 0px;" onclick="ajax_timelog_export_csv();"><span class="icon-exp"></span><?php echo __('Export(.csv)');?></a>
    </div>
</div>
<div class="cb"></div>
    <?php } ?>

<div id="timeLogListing">     
    <div class="tab tab_comon" id="tlg_tab">
        <ul class="nav-tabs mod_wide fl no-border">

            <li id="tlg_act_unbill" >
                <a class="anchor" onclick="switch_tlg_tab('logtime')" data-toggle="tooltip" title="Log Times" >                           
                    <div class="unbill_actv fl"></div>
                    <div class="fl ellipsis-view maxWidth100 "><?php echo __("Time log"); ?> <span class="counter">(<?php print $caseCount;?>)</span></div>
                    <div class="cbt"></div>
                </a>
            </li>

            <li id="tlg_cmpl_invoice">
                <a class="anchor" onclick="switch_tlg_tab('payments')" id="completed_tab" data-toggle="tooltip" title='<?php echo __("Payments"); ?>'>                            
                    <div class="invoice_actv fl"></div>
                    <div class="fl ellipsis-view maxWidth100"><?php echo __("Payments"); ?> <span class="counter">(<?php print $paymentCount;?>)</span></div>
                    <div class="cbt"></div>
                </a>
            </li>

        </ul>
    </div>
    <div class="cbt"></div>
    <div id="show_invoiceListing"></div>
    <div class="Invoice_DownloadEmail" style="display: none;" ></div>
</div>
<div id="caseLoader">    
    <div class="loadingdata"> <?php echo __("Loading"); ?>...</div>
</div>
<div id='timelog_lstng_view'class="" style="display:block;margin-top: 12px;"></div>
<div id="calendar_view" class="calendar_section calendar_resp" style="display:block;margin-top: 12px;"></div>
<div id="chart_view" class="chart_section chart_resp" style="display:none;margin-top: 12px;"></div>
<div id="payment_view" class="" style="display:block;margin-top: 12px;"></div>
<div id="invce_view" class="" style="display:block;margin-top: 12px;"></div>
<!-- send email of payment popup -->
<div id="EmailTemplate" class="cmn_popup" style="display:none;" >
    <div class="popup_bg">
        <div style="">
            <div class="popup_title">
                <span><i class="menu_os_invoice"></i><?php echo __("Send Payment"); ?></span>
                <a onclick="closeEmailPopup();" href="javascript:jsVoid();"><div class="fr close_popup">X</div></a>
            </div>
            <div class="popup_form" style="margin-top: 20px;">            
                <div style="" id="inner_log">                
                    <form onsubmit="" id="senEmailForm" method="POST" action="<?php echo $this->Html->url(array('plugin'=>'Timelog','controller' => 'LogTimes', 'action' => 'sendInvoiceEmail')); ?>">
                    <?php print $this->Form->input('paymentId', array('label'=>false,'type'=>'hidden','value'=>$i['Payment']['id'])); ?>
                        <table class="col-lg-12 new_auto_tab" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="v-top" style="width:100px;"><?php echo __("From");?>:</td>
                                <td class="marginbottom10"> 
                                <?php echo $this->Form->input('from', array('label' => false,'class'=>'form-control required','value'=>$email,'required'=>'required','style'=>'border-radius:5px'));?>
                                    <div id="from_err" style="font-size:12px;color:red;margin:3px"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-top" style="width:100px;">To:</td>
                                <td class="marginbottom10">   
                                <?php echo $this->Form->input('to', array('label' => false,'class'=>'form-control required','required'=>'required','style'=>'border-radius:5px'));?>
                                    <div id="to_err" style="font-size:12px;color:red;margin:3px"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-top" style="width:100px;"><?php echo __("Subject");?>:</td>
                                <td class="marginbottom10">   
                                <?php echo $this->Form->input('subject', array('label' => false,'class'=>'form-control required','required'=>'required','style'=>'border-radius:5px'));?>
                                </td>
                            </tr>
                            <tr>
                                <td class="v-top" style="width:100px;"><?php echo __("Message"); ?>:</td>                             
                                <td class="marginbottom10">   
                                    <textarea id="message" name="data['message']" class="form-control" style="height:90px;border-radius:5px;"></textarea>
                                </td>   
                            </tr>
                        <?php /*
                        <tr>   
                            <td><img class="mceIcon" src="<?php print HTTP_IMAGES ;?>images/attach.png"></td>
                            <td style="text-align:left;"><a href="javascript:void(0);" id="downloadPDFEmailAddress" onclick="buildInvoicePdf('0')" style="color:blue;"> Payment # <span id="pop_email_invoice_no"></span></a></td>
                        </tr>
                          */ ?> 
                        </table>    
                        <div id="invoice_btnn" class="log-btn" style="text-align:center;margin-bottom:15px;">
                            <button id="invoice_send_email" class="btn btn_blue" name="submitInvoice" type="button" onclick="sendInvoicebyEmail();"><i class="icon-big-tick"></i><?php echo __("Send"); ?></button>
                            <span class="or_cancel cancel_on_direct_pj"><?php echo __("or"); ?>
                                <a onclick="closeEmailPopup();"><?php echo __("Cancel"); ?></a>
                            </span>
                        </div>
                        <img src="<?php echo HTTP_ROOT."img/images/case_loader2.gif"; ?>" id="payment_loader" style="display:none;margin-left:220px;"/>

                </div>
                </form>

            </div>
        </div>
    </div>

</div>

<div class="cb"></div>

<!-- to add payment popup -->
<div id="add_invoice"  class="cmn_popup" >
    <div class="popup_bg">
        <div style="">
            <div class="popup_title">
                <span><i class="icon-create-project"></i><?php echo __("Add Unbilled time to Payment"); ?></span>
                <a onclick="closePopup();" href="javascript:jsVoid();"><div class="fr close_popup">X</div></a>
            </div>
            <div class="popup_form" style="margin-top: 20px;">            
                <div style="" id="inner_log">
                    <form onsubmit="" method="POST" action="<?php echo $this->Html->url(array('controller' => 'easycases', 'action' => 'addInvoice')); ?>">
                        <div class="logtime-content">
                            <div style="margin:15px 30px;">
                                <label><?php echo __("Choose Payment"); ?></label>
                                <div>                                        
                                    <select name="invoiceList" id="invoiceList" class="form-control" onchange="checkPayment($(this));">

                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="payee_id" id="payee_id">
                            <div class="log-btn">
                                <button class="btn btn_blue" name="submitInvoice" type="button" onclick="assign2Invoice();"><i class="icon-big-tick"></i><?php echo __("Update"); ?></button>
                                <span class="or_cancel cancel_on_direct_pj">or
                                    <a onclick="closePopup();"><?php echo __("Cancel"); ?></a>
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
<script type="text/template" id="paginate_tmpl">
<?php echo $this->element('paginate_tlg'); ?>
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#custom_duedate').hide();
        $('#select_view div').tipsy({
            gravity: 'n',
            fade: true
        });


        if (CONTROLLER == 'LogTimes' && PAGE_NAME == 'time_log') {
            $('#lview_btn').addClass('disable');
        }
        $('.custom_date_li,.custom-date-btn,#ui-datepicker-div').on('click', function (e) {
            e.stopPropagation();
        });
        $(document).off('click').on('click', function (e) {
            $(e.target).hasClass('tlog-fltr-vtn') ? '' : $('#dropdown_menu_all_timelog_filters').hide();
            $(e.target).hasClass('top-links') || $(e.target).closest('.top-links').length ? $(e.target).closest('li').addClass('open') : $('.top-links').closest('li').removeClass('open');
        });
    });

    function create_user_invoices() {
        var time_logs = $(".checkbox1:checked").map(function () {
            return this.value;
        }).get().join(",");
        var user_id = $(".checkbox1:checked").map(function () {
            return $(this).attr('data-usrid');
        }).get();
        var usr_id = $(".checkbox1:checked").attr('data-usrid');
        var checkout = false;
        $(".checkbox1:checked").each(function () {
            if ($(this).attr('data-usrid') != usr_id) {
                showTopErrSucc("error", '<?php echo __("Timelog of multiple user cannot be selected");?>.');
                checkout = true;
                return false;
            }
        });
        if (checkout) {
            checkout = false;
            $('.checkbox1').each(function () { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });
            return false;
        }
        if (usr_id == "<?php echo SES_ID ?>") {
            showTopErrSucc('error', '<?php echo __("Self Invoice cannot be created");?>.');
            $('.checkbox1').each(function () { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });
            return false;
        }
        showInvoicePage('', 'New Payment', time_logs, '', usr_id);
    }
    function showInvoicePage(v, n, ids, mode, usr_id) {

        var ids = typeof ids != 'undefined' ? ids : '';
        $('#tlg_act_unbill,#tlg_cmpl_invoice').removeClass('active');
        $('.newInvoice').remove();
        $('#tlg_tab').find('#tlg_cmpl_invoice').after('<li class="active newInvoice"><a class="anchor"><div class="fl">' + n + '</div> <div class="cbt"></div> </a></li>');
        $('#timelog_lstng_view').hide();
        $("#timelogtbl").hide();
        $("#payment_view").hide();
        $('#invce_view').show();
        //$('#showInvoiceDiv').html('');
        
        $('#caseLoader').show();
        $.post('<?php echo $this->Html->url(array('controller'=>'LogTimes','action'=>'ajaxUserPaymentPage'));?>', {v: v, log: ids, usr_id: usr_id}, function (res) {
            $('#caseLoader').hide();
            $('#tlg_exprt').hide();
            $('#tlg_fltr').hide();
            $('#invce_view').html(res);
            $('.InvoiceDownloadEmail').show();
            $('.InvoiceDownloadEmail').find('.saveInvoice').each(function () {
                $(this).attr('onclick', $(this).attr('onclick').replace(/\d+/g, v));
            });
            trim($("#edit_invoiceNo").val()) != '' ? $('.newInvoice').find('.fl').html($("#edit_invoiceNo").val()) : '';
            /* if(mode == 'payment_listing'){
             $('#edit_invoice_from,#edit_invoice_to,#edit_invoiceNo,#cust_currency,#edit_issue_dates').attr('readonly',true);
             
             $('#edit_invoice_from,#emailInvoice').addClass('input-disabled');
             $('#emailInvoice').attr('onclick','return false');
             }else */
            if (mode == 'export_email') {
                sendInvoiceEmail(v);
            } else if (mode == 'preview') {
                buildInvoicePdf(v, mode);
            } else if (mode == 'export_pdf') {
                buildInvoicePdf(v);
            }
        });

    }
    function cancelInvoice() {
        switch_tlg_tab(get_mode());
    }

    var save_flag = true;
    function savePayment(inv_id, mode) {
        var mode = typeof mode != 'undefined' ? mode : '';
        $('#caseLoader').show();
        if ($("#frm_create_invoice").isChanged() === true) {
            $("#ismodified").val('Yes');
        } else {
            $("#ismodified").val('No');
        }
        var cust_id = $('#invoice_customer_id').val();
        if (save_flag) {
            save_flag = false;
            $.ajax({
                url: $('#frm_create_invoice').attr('action'),
                data: $('#frm_create_invoice').serialize(),
                method: 'post',
                dataType: 'json',
                success: function (response) {
                    save_flag = true;
                    $('#caseLoader').hide();
                    if (response.success == 'No') {
                        showTopErrSucc('error', response.msg);
                        return false;
                    }
                    $("#invoice_id").val(response.id);

                    if (mode == 'export_email' || mode == 'export_pdf' || mode == 'preview') {
                        showInvoicePage(response.id, $('#edit_invoiceNo').val(), '', mode, cust_id);
                    } else if (mode == 'close') {
                        window.location.hash = 'payments';
                        switch_tlg_tab('payments');
                    } else if (mode == 'createnew') {
                        showInvoicePage('', 'New Payment', 'new');
                    } else {
                        window.location.hash = 'logtime';
                        switch_tlg_tab('logtime');
                    }
                }
            });
        }
    }
    function addInvoideForm() {
        $(".popup_overlay").css({display: "block"});
        $('#add_invoice_form').show();
        $('.popup_bg').show();
        $('.invoice_add_popup').show();
    }

    function closeInvoicePopup() {
        $('#add_invoice_form').hide();
        $('.invoice_add_popup').hide();
    }

    function sendInvoiceEmail(v, params) {
        $("#paymentId").val(v);
        // $('#downloadPDFEmailAddress').attr('onclick',$('#downloadPDFEmailAddress').attr('onclick').replace(/\d+/g,v));
        params = typeof params == 'undefined' ? '' : params;
        var user = typeof params.name != 'undefined' ? params.name : $('#invoice_user_email').val();
        var from = typeof params.from != 'undefined' ? params.from : $("#invoice_company_name").val();
        var to = typeof params.to != 'undefined' ? params.to : $('#invoice_customer_emails').val()
        //$("#subject").val(typeof params.subject !='undefined' ? params.subject : $('#edit_invoiceNo').val());
        $("#subject").val('Payments from ' + from);
        $('#pop_email_invoice_no').html(typeof params.subject != 'undefined' ? params.subject : $('#edit_invoiceNo').val());
        if (trim($('#edit_invoice_to').val()) != '' && typeof from != 'undefined' && typeof name != 'undefined' && trim(user) != 'Choose a Customer') {
            $("#to").val(to);
            if (user != '')
                $("#message").val("Hi " + to + ',\n Here\'s your invoice! We appreciate your prompt payment.\n\nThanks for your business!\n' + from);
        }
        $(".popup_overlay").css({display: "block"});
        $('#EmailTemplate').show();
        $('.popup_bg').css({'width': '540px'});
        $('.popup_bg').show();
    }

    function sendInvoicebyEmail() {
        var to = $('#to').val();
        var from = $('#from').val();
        var subject = $('#subject').val();
        var error = 0;
        var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var letterNumber = /^[0-9a-zA-Z]+$/;

        if (to == "") {
            $("#to").css({"border": "1px solid #FF0000"});
            $("#to").focus();
            error = 1;
        } else if (!to.match(emailRegEx)) {
            $("#to").css({"border": "1px solid #FF0000"});
            $("#to_err").html('Invalid email');
            $("#to").focus();
            error = 1;
        }

        if (from == "") {
            $("#from").css({"border": "1px solid #FF0000"});
            $("#from").focus();
            error = 1;
        } else if (!from.match(emailRegEx)) {
            $("#from").css({"border": "1px solid #FF0000"});
            $("#from_err").html('Invalid email');
            $("#from").focus();
            error = 1;
        }

        if (subject == "") {
            $("#subject").css({"border": "1px solid #FF0000"});
            $("#subject").focus();
            error = 1;
        }

        if (error == 1) {
            $('#to').focus();
            return false;
        } else {
            $('#invoice_btnn').hide();
            $('#payment_loader').show();
            $('#caseLoader').show();
            var e = $("#senEmailForm");
            v = e.serialize();
            $.post('<?php echo $this->Html->url(array('controller' => 'LogTimes', 'action' => 'sendInvoiceEmail')) ?>', {v: v}, function (res) {
                if (res == "success") {
                    $('#payment_loader').hide();
                    $('#caseLoader').hide();
                    $('#invoice_btnn').show();
                    closeEmailPopup();
                    showTopErrSucc('success'," <?php echo __("Email sent successfully");?>.");
                    switch_tlg_tab(get_mode());
                } else {
                    $('#caseLoader').hide();
                    $('#invoice_btnn').show();
                    showTopErrSucc('error', "<?php echo __("Email not sent due to some problem");?>.");
                }

            });
        }
    }

    function closeEmailPopup() {
        $(".popup_overlay").css({display: "none"});
        $('#EmailTemplate').hide();
        $('.popup_bg').hide();
        $('#to').val('');
        $('#to').css({"border": "1px solid #ccc"});
        $('#from').css({"border": "1px solid #ccc"});
        $('#subject').val('');
        $('#subject').css({"border": "1px solid #ccc"});
        $('#message').val('');
    }

    function buildInvoicePdf(v, mode) {
        $('#caseLoader').show();
        $.post('<?php echo $this->Html->url(array('plugin'=>'Timelog','controller' => 'LogTimes', 'action' => 'createPaymentPdf')) ?>', {v: v}, function (res) {
            if (parseInt(res) != 0) {
                $('#caseLoader').hide();
                var url = '<?php echo HTTP_ROOT."easycases/downloadPaymentPdf"; ?>/' + v + (typeof mode != 'undefined' ? '/' + mode : '');
                window.open(url, '_blank');
            }
        });
    }

    function markPaidInvoice(id) {
        $('#caseLoader').show();
        $.post('<?php echo $this->Html->url(array('plugin'=>'Timelog','controller' => 'LogTimes', 'action' => 'markPaymentPaid')) ?>', {id: id}, function (res) {
            if (parseInt(res) != 0) {
                $('#caseLoader').hide();
                showTopErrSucc('success', "<?php echo __("Payment successfully marked as paid");?>.");
                var mode = get_mode();
                switch_tlg_tab(mode);
            }
        });
    }

    function markUnpaidInvoice(id) {
        $('#caseLoader').show();
        $.post('<?php echo $this->Html->url(array('plugin'=>'Timelog','controller' => 'LogTimes', 'action' => 'markPaymentUnpaid')) ?>', {id: id}, function (res) {
            if (parseInt(res) != 0) {
                $('#caseLoader').hide();
                showTopErrSucc('success', "<?php echo __("Payment successfully marked as unpaid");?>.");
                var mode = get_mode();
                switch_tlg_tab(mode);
            }
        });
    }
    function markGroupInvoicePaid() {
        var ids = new Array();
        $('.checkbox2:checked').each(function () {
            if ($(this).is(':checked')) {
                ids.push($(this).val());
            }
        });
        if (ids.length > 0) {
            $('#caseLoader').show();
            $.post(HTTP_ROOT + 'markGroupInvoicePaid', {ids: ids}, function (res) {
                if (parseInt(res) != 0) {
                    $('#caseLoader').hide();
                    showTopErrSucc('success', "<?php echo __("Payments successfully marked as paid");?>.");
                    var mode = get_mode();
                    switch_tlg_tab(mode);
                }
            });
        } else {
            return false;
        }
    }


    function assign2Invoice() {
        if ($('#invoiceList').val() != '' && $('#invoiceList').val() != 0) {
            var paymentId = $('#invoiceList').val();
            var time_logs = $(".invoicechkbox:checked").map(function () {
                return this.value;
            }).get().join(",");
            $.post('<?php echo $this->Html->url(array('controller'=>'LogTimes','action'=>'addToPayment'));?>', {payment: paymentId, log: time_logs}, function (res) {
                closePopup();
                showInvoicePage(paymentId, $("#invoiceList").find('option:selected').html(), '', '', $('#payee_id').val());
            });
        } else {
            showTopErrSucc('error', "<?php echo __("Please select an invoice or create new invoice");?>.");
        }
    }

    function switch_tlg_tab(mode, fltr, val) {
        <?php if(SES_TYPE == 1 || SES_TYPE == 2 || IS_MODERATOR == 1) {?>
        <?php }else{?>
        //  mode = 'logtime';
        <?php }?>
        reset_tabs_tlg();
        if (mode != '') {
            window.location.hash = mode;
        }
        if (mode == 'payments') {
            $('#tlg_cmpl_invoice').addClass('active');
            $('#payment_view').show();
            $('#caseLoader').show();
        } else if (mode == 'calendar') {
            //  window.location.hash= 'calendar';
            $('#unbilled_pg_limit').hide();
            checkUrl();
            getCalenderForTimeLog('calendar');
        } else {
            $('#tlg_act_unbill').addClass('active');
            $('#timelog_lstng_view').show();//.html('');                        
            $('#caseLoader').show();
        }
        if (typeof fltr != 'undefined') {
            casePagingTLG(1, val);
        }
        else {
            casePagingTLG(1, '');
        }
        showCounter();
    }

    function casePagingTLG(page, data) {
        var act_url = '';
        var mode = get_mode();
        var params = {};
        var page_limit = 30;
        if (mode == 'payments') {
            act_url = '<?php echo $this->Html->url(array('plugin'=>'Timelog','controller'=>'LogTimes','action'=>'ajaxPaymentList'));?>';
            params.sortby = getCookie("PAYMENT_SORTBY");
            params.order = getCookie("PAYMENT_SORTORDER");
        } else {
            act_url = '<?php echo $this->Html->url(array('plugin'=>'Timelog','controller'=>'LogTimes','action'=>'ajax_timelog_listing'));?>';
            page_limit = $('#hidden_pg_limit').val();
            // params.projFil = $('#projFil').val();
        }

        var projFil = typeof data.projFil != 'undefined' ? data.projFil : $('#projFil').val();
        var usrid = typeof data.usrid != 'undefined' ? data.usrid : "";
        var user_name = typeof data.user_name != 'undefined' ? data.user_name : "";
        var csid = typeof data.csid != 'undefined' ? data.csid : "";
        var cstitle = typeof data.cstitle != 'undefined' ? data.cstitle : "";
        var date = typeof data.date != 'undefined' ? data.date : "";
        var ispaid = typeof data.ispaid != 'undefined' ? data.ispaid : "";

        // params.sortby = getCookie("INVOICE_SORTBY");
        // params.order = getCookie("INVOICE_SORTORDER");

        $.post(act_url, {casePage: page, params: params, projFil: projFil, usrid: usrid, user_name: user_name, csid: csid, cstitle: cstitle, date: date, page_limit: page_limit, ispaid: ispaid}, function (res) {
            $('#caseLoader').hide();
            if (mode == 'payments') {
                $('#tlg_fltr').hide();
                $('#tlg_exprt').hide();
                $('#payment_view').html(res);
            } else {
                $('#tlg_fltr').show();
                $('#tlg_exprt').show();
                $('#timelog_lstng_view').html(res);
                $('#timelogloader').hide();
                if (typeof ispaid != 'undefined') {
                    ispaid = ispaid == "" ? getCookie('ispaid') : ispaid;
                    console.log(ispaid);
                    if (ispaid == 'paid') {
                        $('#paid_list').prop('checked', true);
                    } else if (ispaid == 'unpaid') {
                        $('#unpaid_list').prop('checked', true);
                    } else {
                        $('#paid_list').prop('checked', true);
                        $('#unpaid_list').prop('checked', true);
                    }
                }
            }
        });
    }

    /*it is used to update top counters in tab*/
    function showCounter() {
        var prjId = $('#projFil').val().trim();
        console.log($('#projFil').val());
        $.post('<?php echo $this->Html->url(array('plugin'=>'Timelog','controller'=>'LogTimes','action'=>'getPaymentCount'));?>', {'prjId': prjId}, function (res) {
            $('#tlg_cmpl_invoice').find('.counter').html('(' + res.payment + ')');
            $('#tlg_act_unbill').find('.counter').html('(' + res.logtime + ')');
        }, 'json');
    }

    function opt_list_action(mode, id, customer) {
        if (mode == 'email') {
            var subject = $('#invoice_list' + id).attr('data-no');
            var from = '<?php echo $from;?>';
            var params = {subject: subject, from: from, name: '', to: ''};
            if (customer > 0) {
                $.post('<?php echo $this->Html->url(array('plugin'=>'Timelog','controller'=>'LogTimes','action'=>'customer_details'));?>', {id: customer}, function (res) {
                    if (typeof res.User != 'undefined') {
                        params.to = res.User.email != null ? res.User.email : '';
                        params.name = res.User.name != null ? res.User.name : '';
                    }

                    sendInvoiceEmail(id, params);
                }, 'json');
            } else {
                sendInvoiceEmail(id, params);
            }
        } else if (mode == 'download') {
            buildInvoicePdf(id);
        } else if (mode == 'print') {
            buildInvoicePdf(id, 'preview');
        } else if (mode == 'paid') {
            markPaidInvoice(id);
        } else if (mode == 'unpaid') {
            markUnpaidInvoice(id);
        }
    }

    $(document).ready(function () {
        $(document).click(function (e) {
            $('#more_opt1123123').size() > 0 ? ($(e.target).closest('#invoice_customer_opts').size() > 0 ? '' : $('#more_opt1123123').hide()) : '';
            $('#btn_invoice_munu').size() > 0 ? ($(e.target).closest('.inv_bts').size() > 0 ? '' : $('#btn_invoice_munu').hide()) : '';
        });

        switch_tlg_tab(get_mode());

        /*add new customer pop-up start*/
        $("#btn_add_customer").click(function () {
            add_customer_action();
        });
        $("#more_customer_options").click(function () {
            $(this).closest('tr').hide();
            $('.customer_options').show();
        });
        /*add new customer pop-up end*/


        $(document).on("click", ".toggle-invoice-opts", function () {
            return false;
        });
        $(document).on("click", ".create-invoice-without-unbilled-time", function () {
            //$(this).closest('.relative').find('.crt-invoice-menu').slideUp();
        });
        /*
         $('#ids').on('click',function(event) {  //on click
         $obj = $(this);
         $('.invoicechkbox').attr('checked',$obj.attr('checked'));
         });
         var checkboxes = $("input[type='checkbox'].invoicechkbox");
         checkboxes.click(function() {
         $('#ids').attr('checked',($('.invoicechkbox').not(':checked').length > 0?false:true));
         $('.btnaddto').attr("disabled", ! $('.invoicechkbox').is(":checked"));                         
         (!$('.invoicechkbox').is(":checked"))?$('.btnaddto').removeClass('btn_blue'):$('.btnaddto').addClass('btn_blue');
         
         }); */

        $("#invoice_issue_data").datepicker({
            dateFormat: 'M d, yy',
            changeMonth: false,
            changeYear: false,
            hideIfNoPrevNext: true,
            onClose: function (selectedDate) {
                $("#invoice_due_data").datepicker("option", "minDate", selectedDate);
            },
        });
        $("#invoice_due_data").datepicker({
            dateFormat: 'M d, yy',
            changeMonth: false,
            changeYear: false,
            hideIfNoPrevNext: true,
            onClose: function (selectedDate) {
                $("#invoice_issue_data").datepicker("option", "maxDate", selectedDate);
            },
        });

        $("#invoiceForm").on("submit", function (event) {
            event.preventDefault();
            var e = $(this);
            v = e.serialize();
            $.post('<?php print $this->Html->url(array('controller'=>'easycases','action'=>'addInvoice'));?>', {v: v}, function (res) {
                if (parseInt(res) != 0) {
                    // updateSelectBox(); 
                    closeInvoicePopup();
                    $('#invoiceList').val(res);
                    assign2InvoiceDirect(parseInt(res), $('#InvoiceInvoiceNo').val());
                    e[0].reset();
                } else {
                }
            });
        });
    });
    $.fn.extend({trackChanges: function () {
            $(":input", this).change(function () {
                $(this.form).data("changed", true);
            });
        }, isChanged: function () {
            return this.data("changed");
        }});
    function get_mode() {
        return trim(window.location.hash.replace('#', ''));
    }
    /* to lis the payments */
    function showPaymentList() {
        $('#tlg_lst,#tlg_tab').removeClass('active');
        $('.newInvoice').remove();
        $('#tlg_pymnt').addClass('active');
        $("#timelogtbl").hide();
        $('#caseLoader').show();
        $('#invce_view').hide();
        $.post('<?php echo $this->Html->url(array('plugin'=>'Timelog','controller'=>'LogTimes','action'=>'ajaxPaymentList'));?>', {}, function (res) {
            $('#caseLoader').hide();
            $('#payment_view').html(res);
        });

    }

    /* to delete the payments */
    function deletePayment(v) {
        if (confirm('<?php echo __("Are you sure you want to delete this payment");?> ?')) {
            $.post('<?php echo $this->Html->url(array('plugin'=>'Timelog','controller'=>'LogTimes','action'=>'deletepayment'));?>', {v: v}, function (res) {
                if (parseInt(res) != 0) {
                    showTopErrSucc('success', "<?php echo __("Payment deleted successfully");?>.");
                    var mode = get_mode();
                    switch_tlg_tab(mode);
                } else {
                    showTopErrSucc('error', "<?php echo __("Payment not deleted");?>.");
                }
            });
        }
    }

    function reset_tabs_tlg() {
        $('#tlg_act_unbill').removeClass('active');
        $('#tlg_cmpl_invoice').removeClass('active');
        $('.newInvoice').remove();
        $('#slct_rsrc').val('');
        $('#timelog_lstng_view').hide();
        $('#calendar_view').hide();
        $('#payment_view').hide();
        $('#invce_view').hide();
        $('#tlg_fltr').hide();
        $('#tlg_exprt').hide();
        $('#tlg_tab').show();
        $('#hrs_details').hide();
        $('#unpaid_hrs_time').show();
    }

    function ajaxSortings(type, cases, el) {
        $('#tlg_fltr').hide();
        var tcls = '';
        if (typeof (getCookie("PAYMENT_SORTBY") != 'undefined') && getCookie("PAYMENT_SORTBY") == cases) {
            if (getCookie('PAYMENT_SORTORDER') == 'ASC') {
                remember_filters("PAYMENT_SORTORDER", 'DESC');
                tcls = 'tsk_desc';
            } else {
                remember_filters("PAYMENT_SORTORDER", 'ASC');
                tcls = 'tsk_asc';
            }
        } else {
            remember_filters("PAYMENT_PAGE", type);
            remember_filters("PAYMENT_SORTBY", cases);
            remember_filters("PAYMENT_SORTORDER", 'DESC');
            tcls = 'tsk_asc';
        }

        $('.tsk_sort').removeClass('tsk_asc tsk_desc');
        $(el).find('.tsk_sort').addClass(tcls);
        switch_tlg_tab(get_mode());
    }
    /* add payment popup */
    function addToPayment() {
        var user_id = $(".checkbox1:checked").map(function () {
            return $(this).attr('data-usrid');
        }).get();
        var usr_id = $(".checkbox1:checked").attr('data-usrid');
        var prjct_id = $("#projFil").val();
        var checkout = false;
        $(".checkbox1:checked").each(function () {
            if ($(this).attr('data-usrid') != usr_id) {
                showTopErrSucc('error', '<?php echo __("Timelog of multiple user cannot be selected");?>.');
                checkout = true;
                $('#crtinvce').hide();
                return false;
            }
        });
        if (checkout) {
            checkout = false;
            $('.checkbox1').each(function () { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });
            $('#crtinvce').hide();
            return false;
        }
        else if (usr_id == "<?php echo SES_ID ?>") {
            showTopErrSucc('error', '<?php echo __("Self Invoice cannot be created");?>.');
            $('#crtinvce').hide();
            $('.checkbox1').each(function () { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });
            return false;
        }
        else {
            $(".popup_overlay").css({display: "block"});
            $('#add_invoice').show();
            $('.popup_bg').show();
            $('.invoice_popup').show();
            $.post('<?php echo $this->Html->url(array('controller'=>'LogTimes','action'=>'unpaidPaymentList'));?>', {usr_id: usr_id, prjct_id: prjct_id}, function (res) {
                var paymnt_data = '<option value="">Select</option>';
                if (res.length != 0) {
                    paymnt_data += '<option value="0">Add New Payment...</option>';
                    for (var kys in res) {
                        paymnt_data += "<option value='" + kys + "'>" + res[kys] + "</option>";
                    }
                }
                else {
                    paymnt_data += '<option value="0">Add New Payment...</option>';
                }
                $('#invoiceList').html(paymnt_data);
                $('#payee_id').val(usr_id);
            }, 'json');
        }

    }

    function checkPayment(e) {
        if (e.val() != '') {
            if (e.val() == 0) {
                var time_logs = $(".checkbox1:checked").map(function () {
                    return this.value;
                }).get().join(",");
                var user_id = $(".checkbox1:checked").map(function () {
                    return $(this).attr('data-usrid');
                }).get();
                var usr_id = $(".checkbox1:checked").attr('data-usrid');
                showInvoicePage('', 'New Payment', time_logs, '', usr_id);
                closePopup();
                //$( "#invoiceForm" ).submit();
                //addInvoideForm();
            }
        }

    }
    function changePageLimit(obj) {
        var pg_limit = $(obj).val();
        $('#hidden_pg_limit').val(pg_limit);
        switch_tlg_tab('logtime');
    }
</script>
