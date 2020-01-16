<div class="ganttTaskEditor cstm-edit-design">
    <div class="popup_overlay"></div>
    <div>
        <div class="new_project cmn_popup" style="position:relative;">
            <div class="popup_title">
                <span class="pop-head"><?php echo __('Edit Task'); ?></span>
                <a href="javascript:jsVoid();"><div class="fr close_popup close_popup_btn">X</div></a>
            </div>

        </div>
        <div class="crt_tsk cmn_popup1">
            <div class="">
                <div class="noprint">
                    <input type="text" name="code" id="code" value="" class="formElements noprint">
                    <div id="status" class="taskStatus" status=""></div>
                </div>
                <div id="inner_task">
                    <input type="hidden" name="data[Easycase][istype]" id="CS_istype" value="1" readonly="true"/>
                    <div class="cb"></div>
                    <div class="loader_dv_edit" style="display: none;"><center><img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="<?php echo __('Loading'); ?>..." title="<?php echo __('Loading'); ?>..." /></center></div>
                    <div class="cb"></div>
                    <div class="error-msg" id="ganttv2_task_error" style="text-align: center;margin-top:10px;"></div>
                    <div class="col-sm-12">
                        <div class="col-sm-12 pop-mbtm-10">
                            <label><?php echo __('Title'); ?>:</label>
                            <input class="form-control" type="text" placeholder="<?php echo __('Task Title'); ?>..." id="name" name="name" maxlength='240' onblur='blur_txt();
                                    checkAllProj();' onfocus='focus_txt()' onkeydown='return onEnterPostCase(event)' onkeyup='checktitle_value();' />
                        </div>
                        <div class="col-sm-12 pop-mbtm-10">
                            <label><?php echo __('Description'); ?>:</label>
                            <textarea rows="3" cols=""  id="description" name="description" class="form-control"></textarea>
                        </div>
                        <div class="col-sm-6 pop-mbtm-10">
                            <label><?php echo __('Project'); ?>:</label>
                            <input type="hidden" name="data[Easycase][istype]" id="CS_istype" value="1" readonly="true"/>
                            <div class="createtask dropdown-blocks form-control" style="">
                                <div style="font-weight: bold;" id="edit_project_div" class="ttc"></div>
                                <div id="create_project_div">
                                    <div id="projUpdateTop_v2" class="popup_link link_as_drp_dwn  fl wid1 ellipsis-view" style="max-width: 100%;">
                                        <?php echo $ctProjName; ?>
                                    </div>
                                    <input type="hidden" readonly="readonly" value="<?php echo $projUniq1; ?>" id="curr_active_project"/>

                                    <div id="projAllmsg" style="display:none;color:#C0504D; padding-top:10px;"><?php echo __('Oops! No project selected.'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 pop-mbtm-10">
                            <label><?php echo __('Milestone'); ?>:</label>
                            <div class="dropdown option-toggle p-6 wid dropdown-blocks" style="" data-drop="more_milestone_v2">
                                <div class="opt1" id="milestone_v2">
                                    <a class="anchor" class="ttfont" onclick="open_more_opt_v2('more_milestone_v2');">
                                        <span id="selected_milestone_v2" class="ellipsis-view" style="max-width: 95%; margin:0px;padding: 0px;"><?php echo __('Default Milestone'); ?></span>
                                        <span class="value" id="value_milestone_v2"></span>
                                        <i class="caret mtop-10 fr"></i>
                                    </a>
                                </div>
                                <div class="more_opt" id="more_milestone_v2">
                                    <ul class="wid">
                                        <?php if (is_array($milestones)) { ?>
                                            <?php foreach ($milestones as $key => $val) { ?>
                                                <li>
                                                    <a class="ttfont anchor" onclick="changeMilestone_v2('<?php echo $val['Milestone']['id']; ?>', 'v2','<?php echo $val['Milestone']['title']; ?>')" style="padding-left:10px;"><?php echo $val['Milestone']['title']; ?></a>
                                                    <span class="value"><?php echo $val['Milestone']['id']; ?></span>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="cb"></div>
                        </div>
                        <div class="cb"></div>
                        <div class="col-sm-6 pop-mbtm-10">
                            <label><?php echo __('Task Type'); ?>:</label>
                            <div id="sample" class="dropdown option-toggle p-6  wid dropdown-blocks" data-drop="more_opt_v2">
                                <div class="opt1" id="opt1_v2">
                                    <a href="javascript:jsVoid()" class="ttfont" onclick="open_more_opt_v2('more_opt_v2');">
                                        <span id="ctsk_type_v2">
                                            <?php
                                            if (isset($taskdetails) && $taskdetails['type_id']) {
                                                foreach ($select as $k => $v) {
                                                    if ($v['Type']['id'] == $taskdetails['type_id']) {
                                                        if (trim($v['Type']['short_name']) && file_exists(WWW_ROOT . "img/images/types/" . $v['Type']['short_name'] . ".png")) {
                                                            $imgicn = HTTP_IMAGES . 'images/types/' . $v['Type']['short_name'] . '.png';
                                                        } else {
                                                            //$imgicn = HTTP_IMAGES.'images/types/default.png';
                                                        }
                                                        if (trim($imgicn)) {
                                                            ?>
                                                            <img class="flag" src="<?php echo $imgicn; ?>" alt="type" style="padding-top:3px;"/>&nbsp;<?php echo __($v['Type']['name'], true); ?>
                                                            <span class="value">2</span>
                                                        <?php } else { ?>
                                                            <?php echo __($v['Type']['name'], true); ?>
                                                        <?php } ?>
                                                        <?php
                                                        break;
                                                    }
                                                }
                                            } else {
                                                ?>
                                                <span class="taxt_typ_width"></span>
                                                <span class="name"><?php echo __($GLOBALS['TYPE'][0]['Type']['name'], true); ?></span>
                                                <span class="value"></span>
                                            <?php } ?>
                                        </span> 
                                        <i class="caret mtop-10 fr"></i>
                                    </a>
                                </div>
                                <div class="more_opt" id="more_opt_v2">
                                    <ul class="wid task_type_opts">
                                        <?php
                                        foreach ($GLOBALS['TYPE'] as $k => $v) {
                                            foreach ($v as $key => $value) {
                                                foreach ($value as $key1 => $result) {
                                                    if ($key1 == 'name' && $key1 = 'short_name') {
                                                        //$im = $value['short_name'].".png";
                                                        $onlick="onclick=\"javascript:changeTaskType_v2('{$value['id']}','{$value['name']}')\"";
                                                        if (trim($value['short_name']) && file_exists(WWW_ROOT . "img/images/types/" . $value['short_name'] . ".png")) {
                                                            $im1 = $this->Format->todo_typ_src($value['short_name'], $value['name']);
                                                            echo "<li><a class='anchor' {$onlick}><img class='flag' src='" . $im1 . "' alt='' /><span class='value'>" . $value['id'] . "</span><span class='name'>" . __($value['name'], true) . "</span></a></li>";
                                                        } else {
                                                            echo "<li><a class='anchor' {$onlick}><span class='taxt_typ_width'>" . $value['name'] . "</span><span class='value'>" . $value['id'] . "</span><span class='name'>" . __($value['name'], true) . "</span></a></li>";
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        ?>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 pop-mbtm-10">
                            <label><?php echo __('Priority'); ?>:</label>
                            <div class=" dropdown option-toggle p-6 wid dropdown-blocks" style="text-align:left;" data-drop="more_opt9_v2111">
                                <div class="opt1" id="opt2_v2">
                                    <span id="pr_col_v2" class="low fl" ></span>
                                    <a href="javascript:jsVoid()" class="ttfont" onclick="open_more_opt_v2('more_opt9_v2');">
                                        <span id="selected_priority_v2">
                                            <?php echo (isset($taskdetails['priority']) && $taskdetails['priority']) ? $taskdetails['priority'] : "Low"; ?>
                                        </span>
                                        <span class="value">2</span>
                                        <i class="caret mtop-10 fr"></i>
                                    </a>
                                </div>
                                <div class="more_opt" id="more_opt9_v2">
                                    <ul class="wid">
                                        <li><span class="low fl"></span><a href="javascript:jsVoid()" data-priority="Low" class="ttfont" onclick="changepriority_v2('low', 2, 'v2')"><?php echo __('Low'); ?></a><span class="value">2</span></li>
                                        <li><span class="medium fl"></span><a href="javascript:jsVoid()" data-priority="Medium" class="ttfont" onclick="changepriority_v2('medium', 1, 'v2')"><?php echo __('Medium'); ?></a><span class="value">1</span></li>
                                        <li><span class="high fl"></span><a href="javascript:jsVoid()" data-priority="High" class="ttfont" onclick="changepriority_v2('high', 0, 'v2')"><?php echo __('High');?></a><span class="value">0</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="cb"></div>
                        <div class="col-sm-6 pop-mbtm-10">
                            <label><?php echo __('Assign To'); ?>:</label>
                            <div id="sample1" class="dropdown option-toggle p-6 wid dropdown-blocks" data-drop="more_opt5_v2">
                                <div class="opt1" id="opt5">
                                    <a href="javascript:jsVoid()" class="ttfont" onclick="open_more_opt_v2('more_opt5_v2');">
                                        <span id="tsk_asgn_to_v2"></span><i class="caret mtop-10 fr"></i>
                                    </a>
                                </div>
                                <div class="more_opt" id="more_opt5_v2">
                                    <ul class="wid">
										<?php foreach ($GLOBALS['projUser'][$ctProjUniq] as $ku => $vu) { $t_nm = $vu['User']['name'].' ('.$vu['User']['short_name'].')';
										if(SES_ID == $vu['User']['id']){$t_nm ='me';} ?>
										<li><a id="tempassign_<?php echo $vu['User']['id']; ?>" href="javascript:jsVoid()" data-assignto="<?php echo $t_nm; ?>" class="ttfont" onclick="changeassignto_v2(<?php echo $vu['User']['id']; ?>, 'v2')"><?php echo $t_nm; ?></a></li>
										<?php
										} ?>
									</ul>
									<input type="hidden" name="assignto" id="gantt_assignto"  value=""/>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 pop-mbtm-10">
                            <label><?php echo __('Task Status'); ?>:</label>
                            <div class=" hidden">
                                <input type="hidden" name="cslegend" id="cslegend"  value=""/>
                                <input type="hidden" name="duration" id="duration"  value=""/>
                                <input type="hidden" name="progress" id="progress" value="" placeholder="<?php echo __("Task status"); ?>..." class="form-control numbersOnly" style="width:230px"/>
                            </div>
                            <div id="sample_status_v2" class=" dropdown option-toggle p-6 wid dropdown-blocks" data-drop="more_status_v2">
                                <div class="opt1" id="opt_status_v2">
                                    <a href="javascript:jsVoid()" class="ttfont" onclick="open_more_opt_v2('more_status_v2');">
                                        <span id="tsk_status_v2"></span><i class="caret mtop-10 fr"></i>
                                    </a>
                                </div>
                                <div class="more_opt" id="more_status_v2">
                                    <?php /* <ul class="wid anchor"><li><a>10</a></li><li><a>20</a></li><li><a>30</a></li><li><a>40</a></li><li><a>50</a></li><li><a>60</a></li><li><a>70</a></li><li><a>80</a></li><li><a>90</a></li><li><a>100</a></li></ul> */ ?>
                                    <ul class="wid anchor" id="more_status_v2_values"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="cb"></div>
                        <div class="col-sm-6 pop-mbtm-10">
                            <label><?php echo __('Start'); ?>:</label>
                            <div class="">
                                <input type="text" name="start" id="start"  value="" class="date form-control" />
                                <input type="checkbox" id="startIsMilestone" class="noprint">
                            </div>
                        </div>
                        <div class="col-sm-6 pop-mbtm-10">
                            <label><?php echo __('End'); ?>:</label>
                            <div class="">
                                <input type="text" name="end" id="end" value="" class="date form-control" />
                                <input type="checkbox" id="endIsMilestone" class="noprint">
                            </div>
                        </div>
                    </div>

                    <div class="cb"></div>

                    <div class="cb"></div>
                    <input type="hidden" value="" name="easycase_uid" id="easycase_uid"  readonly="readonly"/>
                    <input type="hidden" value="" name="easycase_id" id="CSeasycaseid" readonly="readonly" />
                    <input type="hidden" value="" name="editRemovedFile" id="editRemovedFile" readonly="readonly" />
                    <input type="hidden" name="hid_http_images" id="hid_http_images" value="<?php echo HTTP_IMAGES; ?>" readonly="true" />
                    <div class="col-lg-12 task_slide_in btm_block" style="padding:17px 25px;max-width:100%;">
                        <div class="" style="text-align: center;">
                            <button id="saveButton" class="btn btn_blue"><?php echo __('Save'); ?></button>
                            <span id="btn"><span class="or_cancel cancel_on_direct_pj"><?php echo __('or'); ?> <a class="close_popup_btn"><?php echo __('Cancel'); ?></a></span></span>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    case_quick('', 'more_opt5_v2');
    //milstoneonTask_v2(get_project_id());
    $("#CS_type_id").val(getSelectedValue("opt1_v2"));
    $('#opt2_v2').parent('div').addClass('dropdown wid');
</script>