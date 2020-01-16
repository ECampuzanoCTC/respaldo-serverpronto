<table cellpadding="0" cellspacing="0" class="col-lg-12 tab_cse">
    <tr>
	<th>&nbsp;</th>
	<td rowspan="6" style="text-align: left;"> 
	    <?php echo __("Choose which filters to show in your dashboard tabs"); ?>.<br/><br/>
	    <img src="<?php echo HTTP_ROOT . 'img/images/category_tab.png'; ?>"/>
	</td>
    </tr>
    <?php
    $tablists = Configure::read('DTAB');
    foreach ($tablists AS $tabkey => $tabvalue) {
	?>
        <tr>
	    <td class="tab_cls">
		<div class="tb_div_en">
                    <input type="checkbox" <?php if ($tabkey & ACT_TAB_ID) { ?>checked="true"<?php } if ($tabkey == 1 || $tabkey == 2) { ?>disabled="disabled"<?php } ?> value="<?php echo $tabkey; ?>" class="cattab_cls filter" style="cursor: pointer;" onclick="checkCheckbx();">
	    &nbsp;&nbsp;<?php echo $tabvalue["ftext"]; ?>
		</div>
	</td>
        </tr>
<?php } ?>
    <?php // echo CUST_ACT_TAB_ID; exit;
    if (!empty($SearchFilterList)) {
        end($tablists);
        $i = 1;
        foreach ($SearchFilterList as $skey => $sval) {
            ?>
    <tr>
                <td class="tab_cls">
                    <div class="tb_div_en">
                        <input name ="data['custtabvalue']" type="checkbox" <?php if ($custom_active_dashboard_tab & $i) { ?>checked="true"<?php } ?> value="<?php echo $i; ?>" class="custcattab_cls filter" style="cursor: pointer;" onclick="checkCheckbx(this);">
                        &nbsp;&nbsp;<?php echo $sval; ?>
                    </div>
                </td>
            </tr>
            <?php
            $i= $i+$i;
        }
    }
    ?>
            <tr>
                <td> <a id="manageFilterAnchor" href="<?php echo HTTP_ROOT ?>dashboard#searchFilters" class="show_all_opt_in_listonly">Manage Filters</a></td>
            </tr>
    <tr>
	<td></td>
	<td class="btn_align">
	    <span id="btn_cattype">
		<button type="button" value="Save" name="addcattab" class="btn btn_blue" onclick="savecategorytab();"><i class="icon-big-tick"></i><?php echo __("Save"); ?></button>
		<!--<button class="btn btn_grey reset_btn" type="button" name="cancel" onclick="closePopup();" ><i class="icon-big-cross"></i>Cancel</button>-->
        <span class="or_cancel">or<a name="cancel" onclick="closePopup();"><?php echo __("Cancel"); ?></a></span>
	    </span>
	    <span id="tab_ldr" style="display:none;">
		<img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif">
	    </span>
	</td>
    </tr>
</table>
<script type="text/javascript">
    function checkCheckbx(id){
      var checked = $('.filter:checked').length;
      if(checked>5){
          showTopErrSucc('error', 'Please only select five check box at a time');
          $(id).attr('checked',false);
          
      }
    }
    </script>