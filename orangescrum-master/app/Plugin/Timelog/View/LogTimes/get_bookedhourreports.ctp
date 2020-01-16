<style type="text/css">
    #pop-up-booked-rsrc{width:100%;}
    #pop-up-booked-rsrc > thead > tr > th, #pop-up-booked-rsrc > tbody > tr > td{border-bottom: 1px solid #ebeff2;}
    #pop-up-booked-rsrc > tbody > tr:last-child{border:0px;}
    .red-sqr{height: 15px;
    width: 15px;
    margin-right: 4px;
    vertical-align: -1px;
    display: inline-block;
    background: #FF3A46;}
    .pink-sqr{height: 15px;
    width: 15px;
    margin-right: 4px;
    vertical-align: -1px;
    display: inline-block;
    background: #7C6AFF;}
    .popup_title{padding: 20px 10px 0px 25px;height: inherit;}
    .ovrld-txt{cursor: pointer;color:#006699;text-decoration: underline;}
</style>
<div style="width:100%;padding: 0px 28px;">
<div style="float:left;display:inline-block">User: <?php echo $data_arr['user'];?></div>
<div style="float:right;display:inline-block"><div class="pink-sqr"></div><span <?php if(!empty($total_overload)){ ?>class="ovrld-txt" onclick="showOverloaddet(this);" <?php } ?> data-userid="<?php echo $data_arr['userId'];?>" data-date="<?php echo $data_arr['date'];?>" rel="tooltip" original-title="View details">Overload <?php echo number_format((float)($total_overload / 3600), 2, '.', '');?> Hr</span></div>
<div style="clear:both"></div>
</div>
<table id="pop-up-booked-rsrc" cellspacing='15' cellpadding='15'>
    <thead>
        <tr>
            <th>Project</th>
            <th>Task no</th>
            <th>Title</th>
            <th>Assigned Hours</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data_arr['booked_rsrs'] as $k => $val) { ?>
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
            <td style="color:red;text-align: center"><div class="red-sqr"></div><?php echo number_format((float)$val['hours_booked'], 2, '.', '');?> Hr</td>
            </tr>
        <?php } ?>
    </tbody>
</table>