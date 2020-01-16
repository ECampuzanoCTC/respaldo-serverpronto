<style>
    .popup_bg{width:auto !important;max-width:700px}
    .status-report table th{border-bottom: 2px solid #ccc;border-top: 1px solid #ccc;padding: 10px;}
  .popup_form .status-report table tr td { padding: 10px;text-align: left;font-size: 14px}
</style>
<div class="data-scroll status-report">
    <table cellpadding="0" cellspacing="0" class="col-lg-12 ">
        <tr>
            <th>#</th>
            <th style ="text-align: left"><?php echo __("Status"); ?></th>
            <th style ="text-align: left"><?php echo __("Start Date"); ?></th>
            <th style ="text-align: left"><?php echo __("End Date"); ?></th>
            <th style ="text-align: left"><?php echo __("Duration");?></th>
            <th style ="text-align: left"><?php echo __("Username");?></th>
        </tr>
        <?php $i=1; 
        foreach ($state_record as $skey=>$sval){?>
        <?php if($skey >5){ break;?>
        <?php }else {if(!empty($sval['Status']['status_name'])){?>
        <tr>
            <td><?php echo $i;?> </td>
            <td><?php echo $sval['Status']['status_name'];?> </td>
            <td><?php echo $sval['Status']['actual_dt_created'];?> </td>
            <td><?php echo $sval['Status']['last_dt_updated'];?> </td>
            <td><?php echo $sval['Status']['duration'];?> </td>
            <td><?php echo $sval['Status']['username'];?> </td>
        </tr>
        <?php $i++;}}}?>
        <?php if(count($state_record)>5){?>
        <tr>
            <td></td>
            <td>
                <div class="fl">
                    <span id="ldr" style="display:none;">
                        <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="<?php echo __("Loading");?>..." title="<?php echo __("Loading");?>..." />
                    </span>
                </div>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td><a href ="javascript:void(0);" onclick='redirectMore("<?php echo $caseUniqId;?>")'><?php echo __("View More");?></a></td>
        </tr>
        <?php }?>
    </table>
</div>
<script type="text/javascript">
    function redirectMore(caseUniqId){
       var url1 =HTTP_ROOT + 'dashboard#details/'+caseUniqId;
         closePopup();
         window.location.href=url1;
    }
    </script>