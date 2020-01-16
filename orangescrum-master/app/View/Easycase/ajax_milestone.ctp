<input type="hidden" id="milestone_all">
<?php
$m = 0;
if (isset($milestones) && !empty($milestones)) {
    $m = 0;
    $totAsnCase = 0;
    $h = 0;
    
    foreach ($milestones as $milestone) {
        $Milebers = explode("-", $CookieMile);
        $m++;
        $mId = $milestone['Milestone']['id'];
        $AsnUniqId = $milestone['Milestone']['uniq_id'];
        $mTitle = $milestone['Milestone']['title'];
        ?>
        <li <?php if ($m > 5) {
            $h++; ?> id="hidMile_<?php echo $h; ?>" style="display:none;" <?php } ?>>
            <a href="javascript:void(0);">
                <div class="slide_menu_div1">
                    <input type="checkbox" id="Miles_<?php echo $m; ?>" onClick="checkboxMiles('Miles_<?php echo $m; ?>', 'check');filterRequest('milestone');"  <?php if (in_array($mId, $Milebers)) {
            echo "checked";
        } ?>/>
                    <font onClick="checkboxMiles('Miles_<?php echo $m; ?>', 'text');filterRequest('milestone');"   title='<?php echo $this->Format->formatText($mTitle); ?>'>
                    &nbsp;<?php echo $this->Format->formatText($mTitle); ?> (<?php echo!empty($milestone['EasycaseMilestone']) ? count($milestone['EasycaseMilestone']) : '0'; ?>)</font>
                    <div style="margin:0;color:#999999;line-height:16px;padding-left:20px;font-size:11px;">
                    </div>
                    <input type="hidden" name="Mileids_<?php echo $m; ?>" id="Mileids_<?php echo $m; ?>" value="<?php echo $mId; ?>" readonly="true">
                </div>
            </a>
        </li>
        <?php
    }
    if ($h != 0) {
        ?>
        <div class="slide_menu_div1 more-hide-div">
            <div class="more" align="right" id="Mile_more" >
                <a href="javascript:jsVoid();" onClick="moreLeftNav('Mile_more', 'Mile_hide', '<?php echo $h; ?>', 'hidMile_')"><?php echo __("more..."); ?></a>
            </div>
            <div class="more" align="right" id="Mile_hide" style="display:none;">
                <a href="javascript:jsVoid();" onClick="hideLeftNav('Mile_more', 'Mile_hide', '<?php echo $h; ?>', 'hidMile_')"><?php echo __("hide..."); ?></a>
            </div>
        </div>
        <?php }
    ?>
<?php } else { ?>
    <li><a href="javascript:void(0);">
            <div class="slide_menu_div1">
                <font title='Sorry, No Milestone'>
                &nbsp;<?php echo 'No Milestone'; ?> </font>
                <div style="margin:0;color:#999999;line-height:16px;padding-left:20px;font-size:11px;">
                </div>
            </div>
        </a></li>
<?php } ?>
<input type="hidden" id="totMileId" value="<?php echo $m; ?>" readonly="true"/>
