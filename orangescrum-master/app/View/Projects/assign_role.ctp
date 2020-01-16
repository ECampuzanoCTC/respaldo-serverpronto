<style type="text/css">
	.popup_form table.ad_prj_usr_tbl tr.hdr_tr th,.popup_form .ad_prj_usr_tbl tr td,.popup_form table.ad_prj_usr_tbl tr td{padding:10px}
	.popup_form .ad_prj_usr_tbl tr th{padding:5px 10px}
	.assgn-role-btn{padding:0 10px}
</style>
<?php echo $this->Form->create('ProjectUser', array('url' => '/projects/assignProjectUserRole', 'name' => 'projectuserasgnrole')); ?>
<table cellpadding="0" cellspacing="0" class="col-lg-12 ad_prj_usr_tbl ad_prj_usr_ipad">
    <input type="hidden" id="adusrprojnm" value="<?php echo $this->Format->formatText($pjname); ?>">
    <tr>
        <td valign="top">
            <div class="scrl-ovr">
                <table cellpadding="0" cellspacing="0" class="col-lg-12 users_no" style="width:100%">
                    <tr class="hdr_tr">
                        <th colspan="3" class="nm_ipad" style="background:#ABBAC3;color: #FFFFFF;"><?php echo __("User(s) in this"); ?><?php //echo $this->Format->formatText($pjname);  ?> Project</th>
                    </tr>
                    <tr>
                        <th class="nm_ipad" style="background:#ABBAC3;color: #FFFFFF;"><?php echo __("Users"); ?></th>
                        <th class="nm_ipad" style="background:#ABBAC3;color: #FFFFFF;"><?php echo __("Role"); ?></th>
						<th class="nm_ipad" style="background:#ABBAC3;color: #FFFFFF;"><?php echo __("Action"); ?></th>
                    </tr>
                    <?php
                    $userCount = count($memsExstArr);
                    $count = 0;
                    $class = "";
                    $totCase = 0;
                    $totids = "";
                    if ($userCount) {
                        $typ = "";
                        foreach ($memsExstArr as $memsAvlArr) {
                            $user_id = $memsAvlArr['User']['id'];
                            $user_name = ucfirst($memsAvlArr['User']['name']);
                            $user_shortName = $memsAvlArr['User']['short_name'];
                            $user_email = $memsAvlArr['User']['email'];
                            $user_istype = $memsAvlArr['User']['istype'];
                            $count++;
                            if ($count % 2 == 0) {
                                $class = "row_col";
                            } else {
                                $class = "row_col_alt";
                            }
                            ?>
                            <tr id="extlisting<?php echo $user_id; ?>" class="rw-cls1 <?php echo $class; ?>"  <?php /* onmouseover="displayDeleteImg('<?php echo $user_id; ?>');" onmouseout="hideDeleteImg('<?php echo $user_id; ?>');" */ ?>>
                                <td <?php echo $class; ?>">
                                    <div class="fl" title="<?php echo $user_email; ?>">
                                        <?php echo $this->Format->shortLength($user_name, 25); ?>
                                    </div>
                                    <div id="deleteImg_<?php echo $user_id; ?>" title="Delete" class="dropdown_cross fr" style="display:none;color:#D4696F;font-weight:bold;cursor:pointer" onclick="deleteUsersInProject('<?php echo $user_id; ?>', '<?php echo $projid; ?>', '<?php echo urlencode($user_name); ?>');">&times;</div>
                                    <div class="cb"></div>
                                </td>
                                <td <?php echo $class; ?>">
                                    <div class="fl">
                                        <?php echo $this->Form->input('ProjectUser.id.', array('type' => 'hidden', 'value' => $memsAvlArr['ProjectUser']['id'])); ?>
                                        <?php echo $this->Form->input('ProjectUser.role_id.', array('value' => $memsAvlArr['0']['role_id'], 'class' => 'form-control', 'type' => 'select', 'div' => false, 'label' => false, 'options' => $roles,'onchange'=>'addActionId(this)')); ?>
                                    </div>
                                    <div class="cb"></div>
                                </td>
								<td>
								<div>
									<a href="javascript:void(0)" class="actionViewId" data-roleId ="<?php echo $memsAvlArr['0']['role_id']; ?>" data-roleName ="<?php echo $memsAvlArr['0']['role_name']; ?>" onclick="manage_project_role(this)">View<a>
								<div>
								</td>
                            </tr>
                            <?php
                            $totids.= $user_id . "|";
                            $typ = $user_istype;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="2">
                                <center class="fnt_clr_rd"><?php echo __("No user(s) available."); ?></center>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </td>
    </tr>
</table>
<?php $this->Form->end(); ?>
<input type="hidden" name="hid_cs" id="hid_cs" value="<?php echo $count; ?>"/>
<input type="hidden" name="totid" id="totid" value="<?php echo $totids; ?>"/>
<input type="hidden" name="chkID" id="chkID" value=""/>
<input type="hidden" name="slctcaseid" id="slctcaseid" value=""/>
<input type="hidden" id="getusercount" value="<?php echo $userCount; ?>" readonly="true"/>
<input type="hidden" name="project_id" id="projectId" value="<?php echo $projid; ?>"/>
<input type="hidden" name="project_name" id="project_name" value="<?php echo $pjname; ?>"/>
<input type="hidden" name="cntmng" id="cntmng" value="<?php echo $cntmng; ?>"/>