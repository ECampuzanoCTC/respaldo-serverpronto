<style>
    .popup_form table tr td {
        padding: 10px 15px 10px 0;

    }
</style>
<?php $userArr = $GLOBALS['projOwnAdmin']; ?> 
<center><div id="err_msg" style="color:#FF0000;display:none;"></div></center>
<?php echo $this->Form->create('Project', array('url' => '/projects/add_project', 'name' => 'projectadd', 'onsubmit' => 'return projectAdd(\'txt_Proj\',\'txt_shortProj\',\'loader\',\'btn\')')); ?>
<div class="data-scroll">
    <table cellpadding="0" cellspacing="0" class="create_new_proj_pop">
        <tr>
            <td class="popup_label"><?php echo __("Project Name"); ?> :</td>
            <td>
                <?php echo $this->Form->text('name', array('value' => '', 'class' => 'form-control', 'id' => 'txt_Proj', 'placeholder' => "My Project", 'maxlength' => '50')); ?>

            </td>

            <td><?php echo __("Short Name"); ?>:</td>
            <td>
                <?php echo $this->Form->text('short_name', array('value' => '', 'class' => 'form-control ttu', 'id' => 'txt_shortProj', 'placeholder' => "MP", 'maxlength' => '10')); ?>
                <span id="ajxShort" style="display:none">
                    <img src="<?php echo HTTP_IMAGES; ?>images/del.gif" width="16" height="16"/>
                </span>
                <span id="ajxShortPage"></span>
            </td>
        </tr>
        <?php if (defined('DBRD') && DBRD == 1) { ?>
            <tr>
                <td><?php echo __('Description'); ?>:</td>

                <td>
                    <?php echo $this->Form->textarea('description', array('value' => '', 'class' => 'form-control', 'id' => 'prj_description')); ?>
                </td>
                <td><?php echo __('Project Manager'); ?>:</td>
                <td>
                    <?php echo $this->Form->input('Project.manager', array('options' => $this->Format->projManager_opts(), 'empty' => 'Select Project Manager', 'class' => 'form-control fr', 'id' => 'proj_manager', 'style' => 'width:100%', 'label' => false)); ?>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Client'); ?>:</td>
                <td colspan="2">
                    <select id="add_client" class="form-control" name="data[Project][invoice_customer_id]">
                        <option value=0 >Select Client</option>
                        <option value="add">Add New</option>
                        <?php foreach ($GLOBALS['customer_list'] as $kc => $vc) { ?>
                            <option value="<?php echo $kc; ?>"><?php echo $vc; ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" id="add_proj_cust_id" name="data[Project][add_customer]">
                </td>
            </tr>
            <tr style="display:none" class="clnt_more">
                <td colspan="2">
            <center><div id="cust_err_msg" style="color:#FF0000;display:none;"></div></center>
            <div class="data-scroll new_customer">
                <table cellpadding="0" cellspacing="0" class="col-lg-12">
                    <tr>
                        <td><?php echo __("Company Name"); ?>:</td>
                        <td><?php echo $this->Form->text('cust_organization', array('value' => '', 'class' => 'form-control', 'id' => 'prj_cust_organization', 'name' => 'data[Customer][cust_organisation]', 'placeholder' => "Company name")); ?></td>
                    </tr>
                    <tr>
                        <td class="popup_label" valign="top"><?php echo __("Name"); ?>:</td>
                        <td>
                            <?php echo $this->Form->text('cust_title', array('value' => '', 'class' => 'form-control fl', 'id' => 'prj_cust_title', 'name' => 'data[Customer][cust_title]', 'placeholder' => "Title", 'style' => 'width:14%', 'maxlength' => '10')); ?>
                            <?php echo $this->Form->text('cust_fname', array('value' => '', 'class' => 'form-control fl', 'id' => 'prj_cust_fname', 'name' => 'data[Customer][cust_fname]', 'placeholder' => "First Name", 'style' => 'width:43%', 'maxlength' => '100')); ?>
                            <?php echo $this->Form->text('cust_lname', array('value' => '', 'class' => 'form-control fr', 'id' => 'prj_cust_lname', 'name' => 'data[Customer][cust_lname]', 'placeholder' => "Last Name", 'style' => 'width:42%', 'maxlength' => '100')); ?>
                            <div class="cb"></div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo __("Email"); ?>:</td>
                        <td><?php echo $this->Form->text('cust_email', array('value' => '', 'class' => 'form-control', 'id' => 'prj_cust_email', 'name' => 'data[Customer][cust_email]', 'placeholder' => "Email")); ?></td>
                    </tr>
                    <?php /* <tr>
                      <td>Currency:</td>
                      <td>
                      <?php echo $this->Form->input('cust_currency', array('options' => $this->Format->currency_opts(), 'empty' => 'Select Currency', 'class' => 'form-control fl', 'id' => 'prj_cust_currency', 'name' => 'data[Customer][cust_currency]', 'placeholder' => "Currency", 'style' => 'width:100%', 'label' => false)); ?>
                      </td>
                      </tr> */ ?>

                    <tr class="customer_options" style="display:none;">
                        <td><?php echo __("Address"); ?>:</td>
                        <td>
                            <?php echo $this->Form->text('cust_street', array('value' => '', 'class' => 'form-control fl', 'id' => 'prj_cust_street', 'name' => 'data[Customer][cust_street]', 'placeholder' => "Street", 'style' => 'width:100%')); ?>                    
                        </td>
                    </tr>
                    <tr class="customer_options" style="display:none;">
                        <td>&nbsp;</td>
                        <td>
                            <?php echo $this->Form->text('cust_city', array('value' => '', 'class' => 'form-control fl', 'id' => 'prj_cust_city', 'name' => 'data[Customer][cust_city]', 'placeholder' => "City", 'style' => 'width:49%')); ?>
                            <?php echo $this->Form->text('cust_state', array('value' => '', 'class' => 'form-control fr', 'id' => 'prj_cust_state', 'name' => 'data[Customer][cust_state]', 'placeholder' => "State", 'style' => 'width:50%')); ?>

                        </td>
                    </tr>

                    <tr class="customer_options" style="display:none;">
                        <td>&nbsp;</td>
                        <td>
                            <?php echo $this->Form->text('cust_country', array('class' => 'form-control fl', 'id' => 'prj_cust_country', 'name' => 'data[Customer][cust_country]', 'placeholder' => "Country", 'style' => 'width:49%')); ?>
                            <?php echo $this->Form->text('cust_zipcode', array('class' => 'form-control fr', 'id' => 'prj_cust_zipcode', 'name' => 'data[Customer][cust_zipcode]', 'placeholder' => "Postal Code", 'style' => 'width:50%', 'maxlength' => '10')); ?>
                        </td>
                    </tr>
                    <tr class="customer_options" style="display:none;">
                        <td>&nbsp;</td>
                        <td>
                            <?php echo $this->Form->text('cust_phone', array('value' => '', 'class' => 'form-control fl', 'id' => 'prj_cust_phone', 'name' => 'data[Customer][cust_phone]', 'placeholder' => "Phone Number", 'style' => 'width:49%', 'maxlength' => '20')); ?>
                        </td>
                    </tr>
                    <?php if (SES_TYPE < 3) { ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <div class="checkbox fl"  style="">
                                    <label>
                                        <?php echo $this->Form->checkbox('cust_status', array('hiddenField' => false, 'value' => 'allow', 'name' => 'data[Customer][status]', 'id' => 'prj_cust_status')); ?>
                                        <?php echo __("Allow access to log in as a user"); ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr><td>&nbsp;</td><td><a class="fl anchor" style="color:#006699" id="more_customer_options_proj">+ <?php echo __("Details"); ?></a></td></tr>
                </table>    
            </div>
            </td>
            </tr>
        <?php } ?>
        <?php if (defined('TSG') && TSG == 1) { ?>
            <tr>
                <td><?php echo __('Task Status Group'); ?>:</td>
                <td colspan="2">
                    <select id="add_workflow" class="form-control" name="data[Project][workflow_id]">
                        <option value="default" selected="selected"><?php echo __("Default Task Status Group"); ?></option>
                        <?php foreach ($GLOBALS['workflowList'] as $k => $val) { ?>
                            <option value="<?php echo $k; ?>"><?php echo $val; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        <?php } ?>
        <?php if (defined('PT') && PT == 1) { ?>
            <tr id="default_projtemp_tr"  >
                <td><?php echo __("Template"); ?>:<div class="opt_field">(<?php echo __("optional"); ?>)</div></td>
                <td class="v-top">
                    <select name="data[Project][module_id]" id="sel_Typ" class="form-control" onchange="checkNewTmplVal(this.value);
                            view_btn_case(this.value);">
                        <option value="0" selected>[<?php echo __("Select"); ?>]</option>
                        <?php if ($templates_modules) {
                            foreach ($templates_modules as $templates_modules => $val) {
                                ?>

                                <option value="<?php echo $val['ProjectTemplate']['id'] ?>"><?php echo $val['ProjectTemplate']['module_name'] ?></option>
                            <?php }
                        }
                        ?>
                    </select>
                    <!--span id="btn_cse" style="display:none;margin-top:10px;">
                        <a href="javascript:jsVoid();" style="margin-left:3px;width:100px;font-size:12px;" class="blue small" onclick="viewTemplateCases();">View Task</a>
                    </span>
                    <span id="btn_load" style="display:none;margin-top:10px;">
                        <a href="javascript:jsVoid()" style="text-decoration:none;cursor:wait;margin-left:3px;width:100px;">
                            Loading...<img src="<?php echo HTTP_IMAGES; ?>images/del.gif" width="16" height="16" alt="loading..." title="loading..."/>
                        </a>
                    </span-->
                </td>
            </tr>
        <?php } ?>
        <?php
        if (defined('CR') && CR == 1 && SES_CLIENT == 1 && $this->Format->get_client_permission('user') == 1) {
            /*             * Not Show create project */
        } else {
            ?> 
            <?php if (defined('ROLE') && ROLE == 1 && array_key_exists('Add Users to Project', $roleAccess) && $roleAccess['Add Users to Project'] == 0) {
                
            } else {
                ?>
        <?php if (!isset($is_active_proj) || $is_active_proj) { ?>
                    <tr>
                        <td class="v-top">
                            <div style="text-align:right">
                                <span id="add_new_member_txt">
                                    <?php if (count($userArr) < 2) { ?>
                                        <?php echo __("Add new Users"); ?>:
            <?php } else { ?>	
                <?php echo __("Add Users"); ?>:
            <?php } ?>	
                                </span>
                                <div class="opt_field">(<?php echo __("optional"); ?>)</div>
                            </div>
                        </td>
                        <td style="text-align:left" colspan="2">
                            <div class="fl check_user">
                                    <?php foreach ($userArr AS $k => $usr) { ?>
                                    <label class="checkbox-inline" style="margin:0 10px 5px 0;">
                                        <input type="checkbox" checked="checked" name="data[Project][members][]" class="proj_mem_chk" onclick="addremoveadmin(this)"  value="<?php echo $usr['User']['id']; ?>"/>
                                        &nbsp;<span id="puser<?php echo $usr['User']['id']; ?>"><?php echo $usr['User']['name']; ?></span>
                                        <?php if ($usr['CompanyUser']['user_type'] == 1) { ?>
                                            <span class="user_green">(<?php echo __("owner"); ?>)</span>
                                    <?php } else { ?>
                                            <span class="user_red">(<?php echo __("admin"); ?>)</span>
                <?php } ?>
                                    </label>
            <?php } ?>								
                            </div>
                            <textarea id="members_list"  class="wickEnabled form-control expand" rows="2" wrap="virtual" name="data[Project][members_list]"></textarea>
                            <div class="user_inst">(<?php echo __("Use comma to separate multiple email ids"); ?>)</div>
                            <div id="err_mem_email" style="display: none;color: #FF0000;"></div>
                            <div id="autopopup"></div>
                        </td>
                    </tr>
                    <?php /* ?><tr id="default_assignto_tr" <?php if(count($userArr)<2){?>style="display: none;" <?php }?>>
                      <td>Default Assign To:</td>
                      <td>
                      <select id="select_default_assign" class="form-control" name="data[Project][default_assign]">
                      <option value="">-Select-</option>
                      <?php foreach ($userArr AS $k => $usr) { ?>
                      <option value="<?php echo $usr['User']['id']; ?>" <?php if (!$k) { ?>selected<?php } ?>><?php echo $usr['User']['name']; ?></option>
                      <?php } ?>
                      </select>
                      </td>
                      </tr><?php */ ?>
                <?php }
            }
            ?>
<?php } ?>
<?php if (defined('DBRD') && DBRD == 1) { ?>
            <tr>
                <td><?php echo __("Date Range"); ?>:</td>
                <td>
                    <input type="text" placeholder="<?php echo __("Start Date"); ?>" id="projct_start_date" class="datepicker form-control" onkeyup="$(this).val('');"/>
                    <input type="hidden" name="data[Project][start_date]" id="prjct_start_date" class="datepicker form-control" readonly="readonly" />
                </td>
                <td align="right">
                    <input type="text" placeholder="<?php echo __("End Date"); ?>" id="projct_end_date" class="datepicker form-control" onkeyup="$(this).val('');" />
                    <input type="hidden" name="data[Project][end_date]" id="prjct_end_date" />
                </td>
            </tr>
            <tr>
                <td><?php echo __("Estimated Hours"); ?>:</td>
                <td>
                    <input type="text" name="data[Project][estimated_hours]" placeholder="00:00" onkeypress="return numericDecimal(event)" id="estd_hrs" class="form-control" />
                </td>
                <td><?php echo __("Budget"); ?>:</td>
                <td>
                    <input type="text" name="data[Project][budget]" placeholder="00:00" onkeypress="return numericDecimal(event)" id="budget" class="form-control"  />
                </td>
            </tr>
            <tr>
                <td><?php echo __("Currency"); ?>:</td>
                <td>
    <?php echo $this->Form->input('Project.currency', array('options' => $this->Format->currency_opts(), 'empty' => __('Select currency',true), 'class' => 'form-control fl', 'id' => 'proj_currency', 'value' => "USD", 'placeholder' => "Currency", 'style' => 'width:100%', 'label' => false)); ?>
    <?php //echo $this->Form->input('cust_currency', array('options' => $this->Format->currency_opts(), 'empty' => 'Select Currency', 'class' => 'form-control fl', 'id' => 'prj_cust_currency', 'name' => 'data[Customer][cust_currency]', 'placeholder' => "Currency", 'style' => 'width:100%', 'label' => false));   ?>
                </td>

                <td><?php echo __("Default Rate"); ?>:</td>
                <td>
                    <input type="text" name="data[Project][hourly_rate]" placeholder="0" onkeypress="return numericDecimal(event)" id="hourly_rate" class="form-control"  />
                </td>
            </tr>
            <tr>
                <td><?php echo __("Min Tolerance"); ?> % :</td>
                <td>
                    <input type="text" name="data[Project][min_range_percent]" placeholder="00" onkeypress="return numericDecimal(event)" id="min_range_percent" class="form-control"  />
                </td>

                <td><?php echo __("Max Tolerance"); ?> % :</td>
                <td>
                    <input type="text" name="data[Project][max_range_percent]" placeholder="00" onkeypress="return numericDecimal(event)" id="max_range_percent" class="form-control" />
                </td>
            </tr>
            <tr>
                <td><?php echo __("Cost Approved"); ?>:</td>
                <td colspan="3">
                    <input type="text" name="data[Project][cost_approved]" placeholder="00.00" onkeypress="return numericDecimal(event)" id="cost_approved" class="form-control"  />
                </td>
            </tr>
            <?php /*
              <tr>
              <td>Contingency Fund:</td>
              <td colspan="2">
              <input type="text" name="data[Project][contingency_fund]" placeholder="00:00" onkeypress="return numericDecimal(event)" id="contingency_fund" class="form-control"  value="<?php echo $projectdata['Project']['contingency_fund']; ?>"/>
              </td>
              </tr> */ ?>
            <tr>
                <td><?php echo __("Project Status"); ?>:</td>
                <td class="v-top"><?php #pr($this->Format->projStatus_opts());exit;?>
    <?php echo $this->Form->input('Project.status_id', array('options' => $this->Format->projStatus_opts(), 'empty' => __('Select Status',true), 'class' => 'form-control fl', 'id' => 'proj_status', 'placeholder' => __("Status",true), 'style' => 'width:100%', 'label' => false)); ?>
                </td>
                <td class="v-top">
    <?php if (PAGE_NAME != 'onbording') { ?>
                        <div class="add-new-sts"><button type="button" value="Add" name="add_new"  id="add_new_role" class="btn btn_blue" onclick="openAddNewStatusForm()">+ <?php echo __("Add");?></button></div>
                        <div class="add-new-sts-form" style="display:none"><input type="text" name="data[ProjesctStatus][status]" placeholder="Status" id="status" class="form-control"  value=""/><button type="button" value="Add" name="add_new"  id="add_new_status" class="btn btn_blue" onclick="saveNewStatus(this)"><?php echo __("Save");?> </button><a href="javascript:void(0)" class="cancel_new_fld" onclick="cancel_new_fld(this)"><?php echo __("Cancel");?></a></div>
    <?php } ?>
                </td>
            </tr>
            <tr>
                <td><?php echo __("Project Type"); ?>:</td>
                <td class="v-top">
    <?php echo $this->Form->input('Project.type_id', array('options' => $this->Format->projType_opts(), 'empty' => __('Select Type',true), 'class' => 'form-control fl', 'id' => 'proj_type', 'placeholder' => "Type", 'style' => 'width:100%', 'label' => false)); ?>
                </td>
                <td class="v-top">
    <?php if (PAGE_NAME != 'onbording') { ?>
                        <div class="add-new-typ"><button type="button" value="Add New" name="add_new"  id="add_new_role" class="btn btn_blue" onclick="openAddNewTypeForm()">+ <?php echo __("Add");?></button></div>
                        <div class="add-new-typ-form" style="display:none"><input type="text" name="data[ProjectType][type]" placeholder="Type" id="type" class="form-control"  value=""/><button type="button" value="Add New" name="add_new"  id="add_new_type" class="btn btn_blue" onclick="saveNewType(this)"><?php echo __("Save");?></button><a href="javascript:void(0)" class="cancel_new_fld" onclick="cancel_new_fld(this)"><?php echo __("Cancel");?></a></div>
    <?php } ?>
                </td>
            </tr>
            <tr>
                <td><?php echo __("Technology"); ?>:</td>
                <td class="v-top">
    <?php echo $this->Form->input('Project.technology_id', array('options' => $this->Format->projTechnology_opts(), 'empty' => __('Select Technology',true), 'class' => 'form-control fl', 'id' => 'proj_technology', 'placeholder' => __("Technology",true), 'style' => 'width:100%', 'label' => false)); ?>
                </td>
                <td class="v-top">
    <?php if (PAGE_NAME != 'onbording') { ?>
                        <div class="add-new-techn"><button type="button" value="Add New" name="add_new"  id="add_new_tech" class="btn btn_blue" onclick="openAddNewTechForm()">+ <?php echo __("Add");?></button></div>
                        <div class="add-new-techn-form" style="display:none"><input type="text" name="data[ProjectTechnology][technology]" placeholder="Technology" id="technology" class="form-control"  value=""/><button type="button" value="Add New" name="add_new"  id="add_new_tech" class="btn btn_blue" onclick="saveNewTech(this)"><?php echo __("Save");?></button><a href="javascript:void(0)" class="cancel_new_fld" onclick="cancel_new_fld(this)"><?php echo __("Cancel");?></a></div>
    <?php } ?>
                </td>
            </tr>

            <tr>
                <td><?php echo __("Industry"); ?></td>
                <td><?php echo $this->Form->input('Project.industry_type', array('options' => $this->Format->industry_list(), 'empty' => __('Select Industry',true), 'class' => 'form-control fl', 'id' => 'proj_industry', 'placeholder' => __("Select Industry",true), 'style' => 'width:100%', 'label' => false)); ?></td>

            </tr>

<?php } ?>   



    </table>    
</div>
<div style="padding-left:145px;">
    <?php
    $totProj = "";
    if ((!$user_subscription['is_free']) && ($user_subscription['project_limit'] != "Unlimited")) {
        $totProj = $this->Format->checkProjLimit($user_subscription['project_limit']);
    }
    if ($totProj && $totProj >= $user_subscription['project_limit']) {
        if (SES_TYPE == 3) {
            ?>
            <font color="#FF0000"><?php echo __("Sorry, Project Limit Exceeded"); ?>!</font>
            <br/>
            <font color="#F05A14"><?php echo __("Please contact your account owner to create more projects"); ?></font>
        <?php
    } else {
        ?>
            <font color="#FF0000"><?php echo __("Sorry, Project Limit Exceeded"); ?>!</font>
            <br/>
            <font color="#F05A14"><a href="<?php echo HTTP_ROOT; ?>pricing"><?php echo __("Upgrade"); ?></a> <?php echo __("you account to create more projects"); ?></font>
        <?php
    }
} else {
    ?>
        <input type="hidden" name="data[Project][validate]" id="validate" readonly="true" value="0"/>
        <span id="loader" style="display:none;">
            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loader"/>
        </span>
        <span id="btn">
            <button type="button" value="Create" name="crtProject" class="btn btn_blue" onclick="return projectAdd('txt_Proj', 'txt_shortProj', 'loader', 'btn');"><i class="icon-big-tick"></i><?php echo __("Create"); ?></button>
            <!--<button class="btn btn_grey" type="button" onclick="closePopup();"><i class="icon-big-cross"></i>Cancel</button>-->
            <span class="or_cancel"><?php echo __("or"); ?>
                <a onclick="closePopup();"><?php echo __("Cancel"); ?></a>
            </span>
        </span>
    <?php
}
?>
</div>
<?php echo $this->Form->end(); ?>
<script>
    function checkNewTmplVal(tmplVal) {
        if (tmplVal == 'new') {
            $('.crt_project_tmpl').hide();
            $('.new_project').hide();
            createNewTemplate();
        } else {
            $("#projtemptitle").val('');
            $("#projtemptitle").focus();
            $("#project_temp_err").html('');
            $(".project_temp_popup").hide();
        }
    }
    var new_tmpl = '';
    function createNewTemplate() {
        $('.crt_project_tmpl').hide();
        $('.new_project').hide();
        openPopup();
        new_tmpl = 1;
        $("#projtemptitle").val('').keyup();
        $("#projtemptitle").focus();
        $("#project_temp_err").html('');
        $(".project_temp_popup").show();
    }
    function CreateTemplate() {
        var temp_id = $("#hid_tmpl").val();
        if (temp_id != 0) {
            if ($('#projFil').val() !== 'all') {
                var cbval = '';
                var case_id = new Array();
                var spval = '';
                var case_no = new Array();
                $('input[id^="actionChk"]').each(function (i) {
                    if ($(this).is(":checked") && !($(this).is(":disabled"))) {
                        cbval = $(this).val();
                        spval = cbval.split('|');
                        case_id.push(spval[0]);
                        case_no.push(spval[1]);
                    }
                });
            } else {
                return false;
            }
            if (1) {
                $("#crtprjtmpl_btn").hide();
                $("#crtprjtmplloader").show();
                $.post(HTTP_ROOT + "templates/createProjectTemplateFromTasks", {"temp_id": temp_id, "case_id": case_id}, function (res) {
                    closePopup();
                    if (res.msg == 'success') {
                        showTopErrSucc('success', 'Template updated successfully');
                        document.location.href = HTTP_ROOT + "templates/projects";
                    } else {
                        showTopErrSucc('error', "Unable to update project template.");
                        return false;
                    }
                }, 'json');
            } else {
                return false;
            }
        } else {
            showTopErrSucc('error', "Please select one template.");
            return false;
        }
    }
    $(function () {
        if (DATEFORMAT == 2) {
            var date_format = 'd M, yy';
        } else {
            var date_format = 'M d, yy';
        }
        $("#projct_start_date").datepicker({
            altField: "#prjct_start_date",
            dateFormat: date_format,
            altFormat: 'yy-m-d',
            changeMonth: false,
            changeYear: false,
            hideIfNoPrevNext: true,
            onClose: function (selectedDate) {
                $("#projct_end_date").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#projct_end_date").datepicker({
            altField: "#prjct_end_date",
            dateFormat: date_format,
            altFormat: 'yy-m-d',
            changeMonth: false,
            changeYear: false,
            hideIfNoPrevNext: true,
            onClose: function (selectedDate) {
                $("#projct_start_date").datepicker("option", "maxDate", selectedDate);
            }
        });

        $('#add_customer_to_project').on('click', function () {
            if ($(this).is(':checked')) {
                $(this).closest('tr').next('tr').show();
            } else {
                $(this).closest('tr').next('tr').hide();
            }
        });

        $("#more_customer_options_proj").click(function () {
            $(this).closest('tr').hide();
            $('.customer_options').show();
        });
        $('#add_client').on('change', function () {
            if ($(this).val() == 'add') {
                $(this).closest('tr').next('tr').show();
                $('#add_proj_cust_id').val('add_customer');
            } else {
                $(this).closest('tr').next('tr').hide();
                $('#add_proj_cust_id').val('');
            }
        });
    });
</script>