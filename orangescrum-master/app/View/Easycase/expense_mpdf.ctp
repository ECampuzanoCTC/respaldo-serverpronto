<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>INVOICE OS</title>
        <style type="text/css">
            body{margin:0;padding:0;font-family:'Helvetica';color:#000;}
            .cb{clear:both;}
            .fl{float:left;}
            .fr{float:right;}

            /*invoice page*/
            .invoice-content{width:100%;height:auto;margin:0 auto;border:1px solid #FFF;background:#FFF;padding:20px;}
            .invo-cont1{background:url("../img/pdf/orangescrum_logo.png")no-repeat;display:block;background-position:center 30px;margin-bottom:15px;}
            .invoice-content .invo-lft-cont1{width:50%;margin:0px 0px 0px 0px;}
            .invoice-content  .invo-hd{width:50%;float:right;}
            .invo-lft-cont1 p{font-size:12px;line-height: 20px;}
            .invoice-content .invo-rht-cont1{width:50%;margin:0px 0px 0px 0px; }
            .invoice-content .invo-lft-cont1 .bill{margin-top:40px;}
            .invoice-content .invo-lft-cont1 p a{font-size:20px;text-decoration:none;color:rgb(30,135,190);}/*#0000FF*/
            .invoice-content .invo-lft-cont1 .bill h2{font-size:25px;font-weight:bold;color:rgb(31,136,193);}/*#0000FF*/
            .invoice-content .invo-lft-cont1 .bill p{font-size:12px;color:#000;}
            .invo-rht-cont1{text-align:center;}
            .invoice-content .invo-rht-cont1 h1 a span{font-size:45px;color:#D8783C;text-decoration:none;}
            .invo-rht-cont1 .invo-dt{width:100%;margin:5px 0; float:right;}
            .invoice-content .invo-lft-dt{width:45%;text-align:left;font-size:12px;}
            .invoice-content .invo-rht-dt{width:55%;text-align:left;font-size:12px;}

            .invoice-content .tab-cont table{width:100%;border-collapse:collapse;}
            .invoice-content .tab-cont th{background-color:#1F88C1; color: #fff; font-size:20px; padding-bottom: 4px; padding-top: 5px; text-align: left;}
            .invoice-content .tab-cont th, .tab-cont td{border-right:0px none #ccc;border-bottom:0px none #ccc;font-size:12px;padding:6px 10px;}
            .invoice-content .tab-cont th, .tab-cont td:first-child{border-left:0px none #ccc;}
            .invoice-content .tab-cont th {border-color:#70B1D3; border-top-color: #1F88C1;border-bottom-color: #1F88C1;font-size:12px;}
            .invoice-content .tab-cont th:first-child {border-left:0px none #1F88C1;}
            .invoice-content .tab-cont th:last-child {border-right:0px none #1F88C1;}
            .invoice-content .tab-cont td:first-child {border-left:0px none #ccc;}
            .invoice-content .tab-cont td:last-child {border-right:0px none #ccc;}

            .invoice-content .tab-cont .total{width:40%;float:right;background:#116594;color:#fff;font-size:20px;}
            .invoice-content .inf{width:96%;border:1px solid #ccc;padding:10px 2%;background:#ddd;color:#000;font-size:12px;margin:0 auto;margin-top:60px;text-align:left;min-height:45px;}
            .invoice-content .percent{font-size:11px;}
            .invoice-content .tot-txt{margin:5px 0px 6px 60px;}
            .invoice-content .tot-digt{margin:5px 15px 6px 0;}

            .invoice-content .tab-cont th.a-center{text-align:center;}
            .invoice-content .tab-cont th.a-right{text-align:right;}
            .a-center{text-align:center;}
            .a-right{text-align:right;}
            
            .invoice-content .tol-amount .tot-tr td{padding:5px 10px 4px 0;background-color:#FFF;font-size:12px;}
            .inv-summary{border-top:0px none #ccc;}
            .inv-summary tr td:first-child{border-left:1px solid #1F88C1;}
            .inv-summary tr td:last-child{border-right:1px solid #1F88C1;}
            .tol-amount{margin-top:10px;}

            .invoice-content .tol-amount table{width:60%;border-collapse:collapse;float:right;background:#FFF;}
            .invoice-content .tol-amount td{background-color:#FFF;color: #000;font-size:12px;padding-bottom: 4px;padding-top: 5px;text-align:right;}
            .invoice-content .tol-amount tr.tot-tr td{color: #000; font-weight: bold;}
            hr{  border: 0px none;border-top: 1px solid #1F88C1;}

            .listInfo td{padding:3px 10px;  color: rgb(34, 34, 34); font-size: 11px; font-weight: normal;}
            .pdfGrid{width:100%; font-family:'Helvetica'; border-collapse: collapse;border-spacing: 0; margin: 50px 0 50px 0 }
            .pdfGrid th {background-color: #eee;color: rgb(34, 34, 34);font-size: 11px; font-weight: normal;padding: 10px 0 8px 10px;text-align: left;border:1px solid #CCC;border-left:0px none #CCC;}
            .pdfGrid th:first-child{border-left:1px solid #CCC;}
            .pdfGrid td {border: 1px solid rgb(204, 204, 204);padding: 8px 0 8px 10px; color: rgb(34, 34, 34); font-size: 12px; font-weight: normal;}
            p{font-size: 12px; font-weight: normal;font-family:'Helvetica';}
            .listbold{font-weight:bold !important;text-align: left;}
            td{word-break: break-all;}
            .text-right{text-align: right;}
            .width100{width:100%;}
            .width50{width:50%;}
            .width220{width:220px;}
            .invoice-content td.invo-rht-dt{text-align: right;}
            .invoice-content td.invo-lht-dt{text-align: left;}
            .invoice-color{color:#D8783C;}
            .invo-rht-cont1,.invo-hd ,.invo-dt,.margintp{ margin-top:0px; padding-top:0px;}
            .marginbtm{margin-bottom:0px; padding-bottom:0px;}
            .margin5{margin-top:-10px;}
        </style>
    </head>
    <body style="padding-right:3%;">
        <?php $grandTotal = 0; ?>
        <div class="invoice-content">
            <div class="invo-cont1">
                <table class="width100">
                    <tr>
                        <td class="invo-lft-cont1">
                
                    <?php /*?><?php if(isset($i['Invoice']['logo']) && trim($i['Invoice']['logo']) !='' && $this->Format->pub_file_exists(DIR_INVOICE_PHOTOS_S3_FOLDER. $company_id . '/', $i['Invoice']['logo'])) { ?>
                        <img src="<?php echo $this->Format->generateTemporaryURL(DIR_INVOICE_PHOTOS_S3 . $company_id . '/'.  $i['Invoice']['logo']);?>" style="max-height:100px;" />
                        <?php sleep(2);?>
                        <?php ?>
                        <?php }else if ($i['Invoice']['logo'] != '' && $this->Format->imageExists(DIR_USER_PHOTOS, $i['Invoice']['logo'])) { ?>
                        <img src="<?php echo HTTP_ROOT.'files'.DS.'photos'.DS.trim($i['Invoice']['logo']);?>" style="max-height:100px;" /><?php ?>
                    <?php } else { ?>
                        <?php ?><img src="<?php echo HTTP_IMAGES; ?>default-invoice-logo.png" style="max-height:100px;" /><?php  ?>&nbsp;
                    <?php } ?>
                        
                    <?php if (!empty($i['Invoice']['invoice_from'])) { ?>
                        <br /><br />
                        <p>  <?php echo nl2br($i['Invoice']['invoice_from']); ?> </p>
                        <br />
                    <?php } ?>

                    <div class="bill">
                        <h2><span style="color:#1f88c1">Bill To</span></h2>
                        <?php if (!empty($i['Invoice']['invoice_to'])) { ?>
                            <br />
                            <p><?php echo nl2br($i['Invoice']['invoice_to']); ?></p>               
                        <?php } ?>
                    </div><?php */?>
					
					<div class="bill">
                        <h2><span style="color:#1f88c1">Company</span></h2>
                        <?php if (!empty($company_name)) { ?>
                            <br />
                            <p><?php echo $company_name; ?></p>               
                        <?php }else{ ?>
							<p><?php echo "NA"; ?></p>
						<?php } ?>
                    </div><br /><br />
					<div class="bill">
                        <h2><span style="color:#1f88c1">Bill To</span></h2>
                        <?php if (!empty($i[0]['Expense']['client_id'])) { ?>
                            <br />
                            <p>
								<?php
									$getCustomerDetails = $this->Format->getCustomerDetails($i[0]['Expense']['client_id']);
								?>
								<?php echo $getCustomerDetails['InvoiceCustomer']['title']." ".$getCustomerDetails['InvoiceCustomer']['full_name']; ?>
								<br/>
								<?php echo $getCustomerDetails['InvoiceCustomer']['email']; ?>
								<br/>
								<?php echo $getCustomerDetails['InvoiceCustomer']['organization']; ?>
								<br/>
								<?php if($getCustomerDetails['InvoiceCustomer']['street']) { echo $getCustomerDetails['InvoiceCustomer']['street'].","; } ?>
								<br/>
								<?php if($getCustomerDetails['InvoiceCustomer']['city']) { echo $getCustomerDetails['InvoiceCustomer']['city'].","; } ?>
								<?php if($getCustomerDetails['InvoiceCustomer']['state']) { echo $getCustomerDetails['InvoiceCustomer']['state'].","; } ?>
								<br/>
								<?php if($getCustomerDetails['InvoiceCustomer']['country']) { echo $getCustomerDetails['InvoiceCustomer']['country'].","; } ?>
								<?php if($getCustomerDetails['InvoiceCustomer']['zipcode']) { echo $getCustomerDetails['InvoiceCustomer']['zipcode'].","; } ?>
							</p>
                        <?php } ?>
                    </div>
					
                </td>
                        <td class="invo-rht-cont1" align="right" valign="top">
                    <div class="invo-hd text-right">                       
                        <div class="invo-dt text-right">
                            <table class="width100 margintp">
                                <tr>
                                    <td class="invo-rht-dt margintp" colspan="2" style="text-align:right;">
										<h1 style="padding:0px;margin:0px;"><a><span style="color:#D8783C; font-size:28px;padding:0px;margin:0px;">EXPENSE REPORT</span></a></h1>
									</td>
                                </tr>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                                <?php /*?><tr>
                                    <td class="invo-rht-dt" align="right">Invoice#</td>
                                    <td class="invo-lft-dt"  align="left">:&nbsp;&nbsp;<?php print $i['Invoice']['invoice_no']; ?></td>
                                </tr><?php */?>
                                 <?php /*?><tr>
                                    <td class="invo-rht-dt" align="right" >Terms</td>
                                    <td class="invo-lft-dt"  align="left">:&nbsp;&nbsp;<?php
                                if (isset($i['Invoice']['invoice_term'])) {
                                    $invoice_term = $i['Invoice']['invoice_term'];
                                    echo intval($invoice_term) == 0 ? 'Due on receipt' : 'Net ' . $invoice_term;
                                }
                                ?></td>
                                </tr><?php */?>
                                <tr>
                                    <?php /*?><td class="invo-rht-dt" style="width:100%;text-align:right;" colspan="2">Created On:&nbsp;&nbsp;<?php echo date('M d,Y', strtotime($i[0]['Expense']['created'])); ?> </td><?php */?>
									<td class="invo-rht-dt" style="width:100%;text-align:right;" colspan="2">Created On:&nbsp;&nbsp;<?php echo date('M d,Y', strtotime(date('Y-m-d'))); ?> </td>
                                </tr>
								<tr>
                                    <td class="invo-rht-dt" style="width:100%;text-align:right;" colspan="2">Created By:&nbsp;&nbsp;<?php echo $this->Format->getExpenseCreatorName($i[0]['Expense']['user_id']); ?> </td>
                                </tr>
								<?php
								if(isset($i[0]['Expense']['save_status'], $i[0]['Expense']['submitted_date']) && $i[0]['Expense']['save_status'] == 2 && $i[0]['Expense']['submitted_date'] != '0000-00-00 00:00:00')
								{
								?>
									<tr>
										<td class="invo-rht-dt" style="width:100%;text-align:right;" colspan="2">Submitted On:&nbsp;&nbsp;<?php echo date('M d,Y', strtotime($i[0]['Expense']['submitted_date'])); ?> </td>
									</tr>
								<?php } ?>
                            </table>
                        </div>
                    </div>
                </td>
                    </tr>
                </table>
            </div>
            
			<div class="tab-cont">
				<table border="0" cellpadding="0" cellspacing="0" style="width:100%;margin-bottom:20px;padding-bottom:20px;text-align:center;">
					<tr>
						<td colspan="6" style="border:1px solid #D8783C;background-color:#D8783C;color:#FFFFFF;font-size:16px;text-align:center;">
							<?php
								$project_name = (isset($project_list[$i[0]["Expense"]['project_id']]) && !empty($project_list[$i[0]["Expense"]['project_id']])) ? " <b>" . $project_list[$i[0]["Expense"]['project_id']] . "</b>" : " <b> NA </b>";
								echo $project_name;
							?>
						</td>
					</tr>
				</table>
			</div>
			
			<div class="tab-cont">
                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
                    <tr>
                        <th style="<?php if($layout == 'portrait'){?>width:15%;<?php }else{ ?>width:10%;<?php }?>">Date</th>
                        <th style="<?php if($layout == 'portrait'){?>width:37%;<?php }else{ ?>width:48%;<?php }?>">Description</th>
                        <th style="width:15%">Category</th>
                        <th style="width:10%">Currency</th>
                        <th style="width:15%" class="a-right">Total Inc. Tax</th>
						<th style="width:10%" class="a-right">Tax</th>
                    </tr>
					
					<?php if (!empty($i)) {
							 $grandTotalValueIncTax = 0;
							 $grandTotalValueTax = 0;
					 ?>
						<?php foreach ($i as $Expenses) { ?>
							 <?php 
							$expenseTitle = $Expenses['Expense']['description'];
		
							$project_name = (isset($project_list[$Expenses["Expense"]['project_id']]) && !empty($project_list[$Expenses["Expense"]['project_id']])) ? " <b>( <span style='color:#07A1BA;'>Project: </span>" . $project_list[$Expenses["Expense"]['project_id']] . " )</b>" : " <b>( NA )</b>";
							#$project_name =(!empty($prjid) && $prjid=='all')?$project_name:'';
							?>
								<tbody>
									<tr>
										<td colspan="6" class="tsk-ttl-row" style="text-align:center;background-color:#CCC;font-size:13px;font-weight:bold;"><?php echo $expenseTitle; ?></td>
									</tr>
								</tbody>
								<tbody class="log-items">
								<?php
									$allTotalIncTax = 0;
									$allTotalTax = 0;
									foreach($Expenses['ExpenseDetail'] as $key=>$value){
									$allTotalIncTax = $allTotalIncTax + $value['total_inc_vat'];
									$allTotalTax = $allTotalTax + $value['vat_value'];
									
									if($key % 2 == 0){
										$rowBgColor = 'style="background-color:#FFFFFF;"';
									}else{
										$rowBgColor = 'style="background-color:#DDEEF2;"';
									}
								?>
									<tr <?php echo $rowBgColor; ?>>
										<td><?php echo $this->format->get_date($value['expense_date']); ?></td>
										<td title="<?php echo htmlentities($value['expense_description']); ?>" <?php if (!empty(htmlentities($value['expense_description']))) { ?> rel="tooltip" <?php } ?> ><?php echo htmlentities($value['expense_description']); ?></td>
										<td><?php echo $this->Format->getCategoryDetails($value['category_id']); ?></td>
										<td><?php echo $this->Format->getCurrencyDetails($value['currency_id']); ?></td>
										<td style="text-align:right;"><?php echo number_format($value['total_inc_vat'], 2); ?></td>
										<td style="text-align:right;"><?php echo number_format($value['vat_value'], 2); ?></td>
									</tr>
								<?php
									}
									
									$grandTotalValueIncTax = $grandTotalValueIncTax + $allTotalIncTax;
									$grandTotalValueTax = $grandTotalValueTax + $allTotalTax;
									
								?>
								<tr><td colspan="6"><hr class="margintp marginbtm" /></td></tr>
								<tr>
									<td colspan="4" style="text-align:right;margin-bottom:30px;padding-bottom:30px;"><b>Total</b></td>
									<td style="text-align:right;margin-bottom:30px;padding-bottom:30px;"><b><?php echo number_format($allTotalIncTax,2); ?></b></td>
									<td style="text-align:right;margin-bottom:30px;padding-bottom:30px;"><b><?php echo number_format($allTotalTax,2); ?></b></td>
								</tr>
								
								</tbody>
						<?php } ?>
						
						<tbody border="0" cellpadding="0" cellspacing="0" style="width:100%;margin-bottom:20px;padding-bottom:20px;margin-top:30px;padding-top:30px;text-align:center;">
							<tr><td colspan="6"><hr class="margintp marginbtm" /></td></tr>
							<tr>
								<td colspan="4" style="text-align:right;margin-bottom:30px;padding-bottom:30px;"><b>Grand Total</b></td>
								<td style="text-align:right;margin-bottom:30px;padding-bottom:30px;"><b><?php echo number_format($grandTotalValueIncTax,2); ?></b></td>
								<td style="text-align:right;margin-bottom:30px;padding-bottom:30px;"><b><?php echo number_format($grandTotalValueTax,2); ?></b></td>
							</tr>
						</tbody>
						
					<?php } else { ?>
							<tr>
								<td colspan="12">No records......</td>
							</tr>
					<?php } ?>
                </table>
                <hr class="margintp marginbtm" />
            </div>
            <?php /*?><?php if (trim($i['Invoice']['notes']) != '') { ?>
                <div class="inf">
                    <b>Remittance Details:</b>
                    <p><?php echo nl2br($i['Invoice']['notes']); ?></p>
                </div>
            <?php } ?>
            <?php if (trim($i['Invoice']['terms']) != '') { ?>
                <div class="inf">
                    <b>Note:</b>
                    <p> <?php echo nl2br($i['Invoice']['terms']); ?> </p>
                </div>
            <?php } ?><?php */?>
        </div>
    </body>
</html>