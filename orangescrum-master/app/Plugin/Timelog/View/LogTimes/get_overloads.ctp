<style type="text/css">
#pop-up-booked-ovlrod{width:100%;}
#pop-up-booked-ovlrod > thead > tr > th, #pop-up-booked-ovlrod > tbody > tr > td{border-bottom: 1px solid #ebeff2;}
#pop-up-booked-ovlrod > tbody > tr:last-child{border:0px;}
.pink-sqr{height: 15px;
width: 15px;
margin-right: 4px;
vertical-align: -1px;
display: inline-block;
background: #7C6AFF;}
.popup_title{padding: 20px 10px 0px 25px;height: inherit;}
</style>
<table id="pop-up-booked-ovlrod" cellspacing='15' cellpadding='15'>
    <thead>
        <tr>
            <th>Project</th>
            <th>Task no</th>
            <th>Title</th>
            <th>Overload Hours</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($over_data['overload_rsrs'] as $k => $val) { ?>
            <tr>
            <td style="text-align: center">
            <?php $leng_over = strlen($val['project']) > 15 ? true : false;?>
            <span <?php echo ($leng_over) ? 'rel="tooltip" original-title="'.$val['project'].'"' : "";?>><?php echo $this->Text->truncate($val['project'],15,array('ellipsis' => '..','exact' => true));?></span>
            </td>
            <td style="text-align: center">#<?php echo $val['case_no'];?></td>
            <td style="text-align: center">
            <?php $task_leng_over = strlen($val['case_title']) > 15 ? true : false;?>
            <span <?php echo ($leng_over) ? 'rel="tooltip" original-title="'.$val['case_title'].'"' : "";?>><?php echo $this->Text->truncate($val['case_title'],15,array('ellipsis' => '..','exact' => true));?></span>
            </td>
            <td style="color:red;text-align: center"><div class="red-sqr"></div><?php echo number_format((float)$val['hours_overload'], 2, '.', '');?> Hr</td>
            </tr>
        <?php } ?>
		<tr>
			<td style="border:none;" colspan="4"><a href="javascript:void(0);" onclick="closePopupOv()">Back</a></td>
		</tr>
    </tbody>
</table>
<input type="hidden" value="<?php echo $date;?>" id="ovrld-date"/>
<input type="hidden" value="<?php echo $over_data['userId'];?>" id="ovrld-user"/>