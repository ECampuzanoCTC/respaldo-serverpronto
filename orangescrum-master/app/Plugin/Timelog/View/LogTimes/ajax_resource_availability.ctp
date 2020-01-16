<style>
.sml-bx-all{display:inline-block;height:8px;width:8px;margin-right:5px;}
.sml-grn{background-color: #6BBD2E}
.sml-booked{background-color: #FF3A46}
.sml-unavl{background-color:#7C6AFF}
.sml-grey{background-color: #716C71}
.sml-lgtgrey{background-color: #9a979a}
.info-cav{padding-bottom: 20px;padding-right: 20px;}
.button-nav{width: 60%;text-align: right;}
.mt10{margin-top: 10px;}
</style>


<div class="width-100 mt10">
    <div class="fl button-nav">
    <input type="hidden" value="<?php echo $date[0]; ?>" id="start_date"/>
        <button class="btn btn_blue prev-btn" onclick="prevMonthData()"><?php echo __("Prev");?></button>
    <input type="hidden" value="<?php echo $date[count($date)-1]; ?>" id="last_date"/>
        <button class="btn btn_blue nxt-btn" onclick="nextMonthData()"><?php echo __("Next");?></button>
    </div>
    <div class="fr info-cav" style="width: 40%;">

  <div style="float:right">
      <div><span class="sml-bx-all sml-grn"></span><?php echo __("Available");?></div>
        <div><span class="sml-bx-all sml-booked"></span><?php echo __("Booked");?></div>
        <div><span class="sml-bx-all sml-unavl"></span><?php echo __("Overloaded");?></div>
        <div><span class="sml-bx-all sml-grey"></span><?php echo __("Vacation");?></div>
    </div>
  <div style="clear:both"></div>
  </div>
    <div class="cb"></div>
</div> 
<table class="table">
    <thead>
        <tr>
            <th><?php echo __("Resource");?></th>
            <th colspan="<?php echo count($dates); ?>"><?php echo __("Date");?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <?php foreach ($dates as $k => $dt) { ?>
                <td><strong><?php echo $dt; ?></strong></td>
            <?php } ?>
        </tr>
        <?php foreach ($data as $k => $val) { ?>
            <tr><?php $leng_over = strlen($val['resource']) > 10 ? true : false;?>
                <td><strong <?php echo ($leng_over) ? 'rel="tooltip" original-title="'.$val['resource'].'"' : "";?>><?php echo $this->Text->truncate($val['resource'],10,array('ellipsis' => '..','exact' => true));?></strong></td>
                <?php
                foreach ($date as $key => $dt) {
                    $bookedHours = 0;
                    $bookedWidth = 0;
                    $availableWidth = 100;
                    $leave = 0;
                    $title = $GLOBALS['company_work_hour'] . __(" hrs Available",true);
                    if (array_key_exists($dt, $val['data'])) {
                        if(!array_key_exists($dt, $val['leave'])){
                            $bookedHours = $val['data'][$dt] / 3600;
                            $bookedWidth = ($bookedHours / $GLOBALS['company_work_hour']) * 100;
                            $availableWidth = 100 - $bookedWidth;
                            $title = $GLOBALS['company_work_hour'] - $bookedHours > 0 ? $GLOBALS['company_work_hour'] - $bookedHours . 'hrs Available' : (isset($val['overload'][$dt]) && $val['overload'][$dt] != 0  ? 'Overloaded. Click to see details.' : 'Not Available. Click to see details.');
                        } else {
                            $leave = 1;
                            $bookedWidth = 100;
                            $title = ucfirst($val['resource']).__(' is on leave',true);
                            $title.= $leave['reason'] != '' ? 'due to '.$leave['reason'] : '';
                        }
                    }else {
                        if (array_key_exists($dt, $val['leave_reason'])) {
                            $leave = 1;
                            $bookedWidth = 100;
                            $title = ucfirst($val['resource']) .__(' is on leave',true);
                            $title .= $val['leave_reason'][$dt] != '' ? 'due to ' . $val['leave_reason'][$dt] : '';
                        }
                    }
                    ?>
                    <?php $bookedWidth = ($bookedWidth > 100) ? 100 : $bookedWidth; 
                            $availableWidth = ($availableWidth < 0) ? 0 : $availableWidth; 
                            ?>

                    <td class="resource-booked" rel="tooltip" title="<?php echo $title; ?>">
                        <?php if($leave) { ?>
                        <span class="fl leave-percentage" style="background-color:#716C71;width:<?php echo $bookedWidth; ?>%;<?php if ($bookedWidth > 0) { ?>display: inline-block<?php } ?>"  <?php if($dt >= date('Y-m-d')) { ?> onclick="updateVacation(<?php echo $val['leave'][$dt]; ?>)" <?php } ?> ></span>
                        <?php } else { ?>
                            <span class="fl booked-percentage resrs-hours" style="background-color:<?php if($val['overload'][$dt]){ echo "#7c6aff";}else{echo "#FF3A46"; }?>;width:<?php echo $bookedWidth; ?>%;<?php if ($bookedWidth > 0) { ?>display: inline-block;<?php } ?><?php if ($bookedWidth == 100  || $bookedWidth >= 0) { ?>cursor: pointer<?php } ?>" data-bookstatus="<?php if ($bookedWidth == 100 || $bookedWidth >= 0) {echo "1";}else{echo "0";} ?>" data-usrid="<?php echo $k;?>" data-date="<?php echo $dt;?>"></span>
                           <span class="fl available-percentage" style="background-color:#6BBD2E;width:<?php echo $availableWidth; ?>%;<?php if ($availableWidth > 0) { ?>display: inline-block;<?php } ?><?php if ($availableWidth == 0) { ?>cursor: pointer<?php } ?>" data-bookstatus="<?php if ($availableWidth == 0) {echo "1";}else{echo "0";} ?>" data-usrid="<?php echo $k;?>" data-date="<?php echo date('M d, Y',strtotime($dt))?>" <?php if($dt >= date('Y-m-d')) { ?> onclick="applyForVacation(<?php echo $k; ?>, '<?php echo $dt; ?>')" <?php } ?> ></span>
                        <?php } ?>
                        <div class="cb"></div>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>