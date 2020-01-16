<center><div id="err_msg" style="color:#FF0000;display:none;"></div></center>
<?php echo $this->Form->create('Project', array('url' => '/projects/settings', 'name' => 'projsettings', 'enctype' => 'multipart/form-data')); ?>
<table cellpadding="0" cellspacing="0" class="col-lg-12 new_auto_tab">
    <tr>
        <td><?php echo __("Project Name"); ?>:</td>
        <td>
            <?php echo $this->Form->text('name', array('value' => html_entity_decode(stripslashes($projArr['Project']['name'])), 'class' => 'form-control', 'id' => 'txt_proj', 'maxlength' => '50')); ?>
        </td>
    </tr>
    <tr>
        <td><?php echo __("Project Short Name"); ?>:</td>
        <td>
            <?php
            if (strtoupper($projArr['Project']['short_name']) == 'WCOS') {
                echo $this->Form->text('short_name', array('value' => stripslashes($projArr['Project']['short_name']), 'class' => 'form-control shrt_alphbts', 'id' => 'txt_shortProjEdit', 'maxlength' => '10', 'readonly' => 'readonly'));
            } else {
                echo $this->Form->text('short_name', array('value' => stripslashes($projArr['Project']['short_name']), 'class' => 'form-control shrt_alphbts', 'id' => 'txt_shortProjEdit', 'maxlength' => '10'));
            }
            ?>
        </td>
    </tr>
    <tr>
        <td><?php echo __("Default Assign To"); ?>:</td>
        <td>
            <select name="data[Project][default_assign]" id="sel_Typ" class="form-control">
                <option value="0" selected="selected">[<?php echo __("Select"); ?>]</option>
                <?php foreach ($quickMem as $asgnMem) { ?>
                    <option value="<?php echo $asgnMem['User']['id']; ?>" 
                    <?php
                    if ((isset($defaultAssign) && ($asgnMem['User']['id'] == $defaultAssign))) {
                        echo "selected='selected'";
                    } /* else if (!$defaultAssign && ($asgnMem['User']['id'] == SES_ID) || ($asgnMem['User']['id'] == SES_ID)) {

                      echo "selected='selected'";
                      } */
                    ?>															
                            ><?php
                                if (($asgnMem['User']['id'] == SES_ID)) {
                                    echo __('me', true);
                                } else {
                                    echo $this->Format->formatText($asgnMem['User']['name']);
                                }
                                ?></option>																
<?php } ?>
            </select>
        </td>
    </tr>
    <?php if(defined('TSG') && TSG == 1){ ?>
    <tr>
        <td><?php echo __("Work Flow"); ?>:</td>
        <td>
            <?php if($canChangeWorkflow == 1) {?>
            <select name="data[Project][workflow_id]" id="wfl_edit_id" class="form-control">
                <option value="default" selected="selected"><?php echo __('Default Task Status Group');?></option>
                <?php foreach($workflowList as $k=>$val){ ?>
                <option value="<?php echo $k;?>" <?php if($k == $projArr['Project']['workflow_id']){ ?>selected="selected" <?php }?>><?php echo $val; ?></option>
                <?php } ?>
            </select>
            <?php } else{ ?>
            <input type="text" name="wrkflw_display" id="wfl_edit_txt" class="form-control" readonly="readonly" value="<?php echo trim($workflow_name) == '' ? 'Default Workflow':$workflow_name ; ?>" />
            <input type="hidden" name="data[Project][workflow_id]" value="<?php echo trim($workflow_id) == '' ? '0':$workflow_id ; ?>">
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
    <?php if(defined('PT') && PT == 1){ ?>
    <tr>
        <td><?php echo __("Project Template"); ?>:</td>
        <td>
            <?php if($canChangeWorkflow == 1) {?>
            <select name="data[Project][module_id]" id="wfl_edit_id" class="form-control">
                <option value="0" selected="selected"><?php echo __('Select Project Template');?></option>
                <?php foreach($projectTemplateList as $k=>$val){ ?>
                <option value="<?php echo $k;?>" <?php if($k == $template_module_id){ ?>selected="selected" <?php }?>><?php echo $val; ?></option>
                <?php } ?>
            </select>
            <?php } else{ ?>
            <input type="text" name="prjct_tmplt_display" id="wfl_edit_txt" class="form-control" readonly="readonly" value="<?php echo trim($template_module_name) == '' ? 'No Project Template':$template_module_name ; ?>" />
           <?php /* <input type="hidden" name="data[Project][module_id]" value="<?php echo trim($template_module_id) == '' ? '0':$template_module_id ; ?>"> */ ?>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
	<tr>
        <td class="v-top"><?php echo __("Created by"); ?>:</td>
        <td class="auto_tab_fld">
            <?php echo $this->Format->formatText($uname); ?>,

<?php
$locDT = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, $projArr['Project']['dt_created'], "datetime");
$gmdate = $this->Tmzone->GetDateTime(SES_TIMEZONE, TZ_GMT, TZ_DST, TZ_CODE, GMT_DATE, "date");
$dateTime = $this->Datetime->dateFormatOutputdateTime_day($locDT, $gmdate, 'time');
?>
            <span class="fnt-14-gry"><?php echo $dateTime; ?></span>
        </td>
    </tr>
    <tr>
        <td></td>
        <td class="btn_align">
            <span id="settingldr" style="display:none;">
                <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader" />
            </span>

            <span id="btn" class="project_edit_button">
                <input type="hidden" name="data[Project][validateprj]" id="validateprj" readonly="true" value="0"/>
                <input type="hidden" name="data[Project][pg]" id="pg" readonly="true" value="0"/>
                <input type="hidden" value="<?php echo $uniqid; ?>" name="data[Project][uniq]" id="uniqid"/>
                <input type="hidden" value="<?php echo $projArr['Project']['id'] ?>" name="data[Project][id]"/>

                <button type="button" value="Save" class="btn btn_blue" onclick= "return submitProject('txt_proj', 'txt_shortProjEdit')" id="savebtn"><i class="icon-big-tick"></i><?php echo __("Save"); ?></button>
                <!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
                <span class="or_cancel"><?php echo __("or"); ?><a onclick="closePopup();"><?php echo __("Cancel"); ?></a></span>
            </span>
        </td>
    </tr>						
</table>
<?php $this->Form->end(); ?>
