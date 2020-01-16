<div class="user_profile_con profileth">
    <?php echo $this->element("personal_settings"); ?>
    <?php echo $this->Form->create('DateTimeFormat', array('url' => '/users/saveDateTime', 'name' => 'dateTimeForm')); ?>
    <input name="default_view_id" type="hidden" value="<?php echo $id; ?>" />
    <table cellspacing="0" cellpadding="0" class="col-lg-5" style="text-align:left;">
        <tbody>
            <tr>
                <th><?php echo __("Time Format"); ?>:</th>
                <td>
                    <?php
                    $time_formats = json_decode(TIMEFORMAT, true);
                    echo $this->Form->select('time_format', $time_formats, array('value' => !empty($time_format) ? $time_format : 1, 'class' => 'select form-control floating-label', 'data-dynamic-opts' => 'true', 'empty' => 'Choose One'));
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php echo __("Date Format"); ?>:</th>
                <td>
                    <?php
                    $date_formats = json_decode(DATEFORMAT, true);
                    echo $this->Form->select('date_format', $date_formats, array('value' => !empty($date_format) ? $date_format : 1, 'class' => 'select form-control floating-label ', 'data-dynamic-opts' => 'true', 'empty' => 'Choose One'));
                    ?>
                </td>
            </tr>
            <tr>
                <th></th>
                <td class="btn_align">
                    <span id="subprof1">
                        <button type="submit" value="Update" name="submit_Profile"  id="submit_Profile" class="btn btn_blue"><i class="icon-big-tick"></i><?php echo __("Update"); ?></button>
                        <span class="or_cancel"><?php echo __("or"); ?>
                            <a onclick="cancelProfile('<?php echo $referer; ?>');"><?php echo __("Cancel"); ?></a>
                        </span>
                    </span>
                    <span id="subprof2" style="display:none">
                        <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="<?php echo __("Loading"); ?>..." />
                    </span>
                </td>
            </tr>						
        </tbody>
    </table>
    <?php echo $this->Form->end(); ?>

    <div class="cbt"></div>
</div>
