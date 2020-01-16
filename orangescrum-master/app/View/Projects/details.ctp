<style type="text/css">
    .add-new-sts,.add-new-sts-form, .add-new-typ,.add-new-typ-form, .add-new-techn,.add-new-techn-form{text-align: right}
    .add-new-sts-form input.form-control, .add-new-typ-form input.form-control, .add-new-techn-form input.form-control{margin-left:5px; width:46%; float: left}
    .add-new-sts button.btn.btn_blue, .add-new-sts-form button.btn.btn_blue, .add-new-typ button.btn.btn_blue,.add-new-typ-form button.btn.btn_blue, .add-new-techn button.btn.btn_blue,.add-new-techn-form button.btn.btn_blue{margin-right:0}
    .add-new-sts-form button.btn.btn_blue, .add-new-typ-form button.btn.btn_blue, .add-new-techn-form button.btn.btn_blue{width:26%;padding:6px;float:left;margin-left:6px}
    .add-new-sts button.btn.btn_blue, .add-new-typ button.btn.btn_blue, .add-new-techn button.btn.btn_blue{padding: 6px 10px}
    .user_profile_con table th{width: auto}
    .cancel_new_fld{float:left;margin-left:15px;margin-top:8px}
</style>
<div class="user_profile_con profileth">
<?php echo $this->form->create('Project',array('url'=>'/projects/details','onsubmit'=>'return submitProjectDetails()','class'=>'form-horizontal')); ?>
<table cellspacing="0" cellpadding="0" class="col-lg-7" style="text-align:left;">
    <tbody>
        <tr>
            <th>Name:</th>
            <td colspan="3">
                <input type="hidden" name="data[Project][id]" class="form-control" value="<?php echo $projectdata['Project']['id']; ?>"/>
                <input type="hidden" name="data[Project][uniq_id]" class="form-control" value="<?php echo $projectdata['Project']['uniq_id']; ?>"/>
                <input type="text" name="data[Project][name]" placeholder="John" id="project_name" class="form-control" value="<?php echo $projectdata['Project']['name']; ?>" readonly="readonly"/>
            </td>
        </tr>
        <tr>
            <th>Short Name:</th>
            <td colspan="3">
                <input type="text" name="data[Project][short_name]" placeholder="JD" id="short_name" class="form-control"  value="<?php echo $projectdata['Project']['short_name']; ?>" readonly="readonly"/>
            </td>
        </tr>
        <tr>
            <th>Description:</th>
            <td colspan="3">
                <?php echo $this->Form->textarea('Project.description', array('value' => $projectdata['Project']['description'], 'class' => 'form-control', 'id' => 'prj_description')); ?>
            </td>
        </tr>
        <tr>
        <td><?php echo __('Client'); ?>:</td>
        <td colspan="3">
            <select id="add_clients" class="form-control" name="data[Project][invoice_customer_id]">
                <option value=0 >Select Client</option>
                <option value="add">Add New</option>
                <?php foreach($GLOBALS['customer_list'] as $kc=>$vc){ ?>
                <option value="<?php echo $kc;?>" <?php if($kc == $projectdata['Project']['invoice_customer_id']){ echo "selected" ;}?> ><?php echo $vc; ?></option>
                <?php } ?>
            </select>
            <input type="hidden" id="add_proj_cust_ids" name="data[Project][add_customer]">
        </td>
        </tr>
        <tr style="display:none" class="add_cust_tr">
                <td colspan="3">
                    <center><div id="cust_err_msg" style="color:#FF0000;display:none;"></div></center>
                    <div class="new_customer">
                        <table cellpadding="0" cellspacing="0" class="col-lg-12">
                            <tr>
                                <td>Company Name:</td>
                                <td><?php echo $this->Form->text('cust_organization', array('value' => '', 'class' => 'form-control', 'id' => 'prj_cust_organization', 'name' => 'data[Customer][cust_organisation]', 'placeholder' => "Company name")); ?></td>
                            </tr>
                            <tr>
                                <td class="popup_label" valign="top">Name:</td>
                                <td>
                                    <?php echo $this->Form->text('cust_title', array('value' => '', 'class' => 'form-control fl', 'id' => 'prj_cust_title', 'name' => 'data[Customer][cust_title]', 'placeholder' => "Title", 'style' => 'width:14%', 'maxlength' => '10')); ?>
                                    <?php echo $this->Form->text('cust_fname', array('value' => '', 'class' => 'form-control fl', 'id' => 'prj_cust_fname', 'name' => 'data[Customer][cust_fname]', 'placeholder' => "First Name", 'style' => 'width:43%', 'maxlength' => '100')); ?>
                                    <?php echo $this->Form->text('cust_lname', array('value' => '', 'class' => 'form-control fr', 'id' => 'prj_cust_lname', 'name' => 'data[Customer][cust_lname]', 'placeholder' => "Last Name", 'style' => 'width:42%', 'maxlength' => '100')); ?>
                                    <div class="cb"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td><?php echo $this->Form->text('cust_email', array('value' => '', 'class' => 'form-control', 'id' => 'prj_cust_email', 'name' => 'data[Customer][cust_email]', 'placeholder' => "Email")); ?></td>
                            </tr>
                           <?php /* <tr>
                                <td>Currency:</td>
                                <td>
                                    <?php echo $this->Form->input('cust_currency', array('options' => $this->Format->currency_opts(), 'empty' => 'Select Currency', 'class' => 'form-control fl', 'id' => 'prj_cust_currency', 'name' => 'data[Customer][cust_currency]', 'placeholder' => "Currency", 'style' => 'width:100%', 'label' => false)); ?>
                                </td>
                            </tr> */ ?>
                            
                            <tr class="customer_options" style="display:none;">
                                <td>Address:</td>
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
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div class="checkbox fl"  style="">
                                        <label>
                                            <?php echo $this->Form->checkbox('cust_status', array('hiddenField' => false, 'value' => 'allow', 'name' => 'data[Customer][status]', 'id' => 'prj_cust_status')); ?>
                                            Allow access to log in as a user
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr><td>&nbsp;</td><td><a class="fl anchor" style="color:#006699" id="more_customer_options_projs">+ Details</a></td>
                            <tr><td>&nbsp;</td><td><a class="fl anchor" style="color:#006699" id="cancel_customer_add">Cancel</a></td></tr>
                            
                        </table>    
                    </div>
                </td>
            </tr>
        <tr>
            <th>Estimated Hours:</th>
            <td colspan="3">
                <input type="text" name="data[Project][estimated_hours]" placeholder="00:00" onkeypress="return numericDecimal(event)" id="estd_hrs" class="form-control"  value="<?php echo $projectdata['Project']['estimated_hours']; ?>"/>
            </td>
        </tr>
        <tr>
            <th>Date Range:</th>
            <td>
                <input type="text" placeholder="Start Date" id="proj_start_date" class="datepicker form-control"  value="<?php if(isset($projectdata['Project']['start_date']) && !empty($projectdata['Project']['start_date'])){ echo date('M d, Y', strtotime($projectdata['Project']['start_date']));} ?>" style="width:95%"/>
                <input type="hidden" name="data[Project][start_date]" id="prj_start_date" class="datepicker form-control"  value="<?php echo $projectdata['Project']['start_date']; ?>"/>
            </td>
            <td align="right">
                <input type="text" placeholder="End Date" id="proj_end_date" class="datepicker form-control"  value="<?php if(isset($projectdata['Project']['end_date']) && !empty($projectdata['Project']['end_date'])){ echo date('M d, Y', strtotime($projectdata['Project']['end_date'])); }?>" style="width:95%"/>
                <input type="hidden" name="data[Project][end_date]" id="prj_end_date" class="datepicker form-control" value="<?php echo $projectdata['Project']['end_date']; ?>"/>
            </td>
        </tr>
        <tr>
            <th>Budget:</th>
            <td colspan="2">
                <input type="text" name="data[Project][budget]" placeholder="00:00" onkeypress="return numericDecimal(event)" id="budget" class="form-control"  value="<?php echo $projectdata['Project']['budget']; ?>"/>
            </td>
        </tr>
         <tr>
            <th>Currency:</th>
            <td colspan="2">
                <?php echo $this->Form->input('Project.currency',array('options'=>$this->Format->currency_opts(),'empty'=>'Select currency', 'class' => 'form-control fl', 'id' => 'proj_currency','value'=> $projectdata['Project']['currency'], 'placeholder' => "Currency", 'style' => 'width:100%','label'=>false)); ?>
            </td>
        </tr>
        <tr>
            <th>Default Rate:</th>
            <td colspan="2">
                <input type="text" name="data[Project][hourly_rate]" placeholder="00:00" onkeypress="return numericDecimal(event)" id="hourly_rate" class="form-control"  value="<?php echo $projectdata['Project']['hourly_rate']; ?>"/>
            </td>
        </tr>
        <tr>
            <th>Min Tolerance % :</th>
            <td colspan="2">
                <input type="text" name="data[Project][min_range_percent]" placeholder="0%" onkeypress="return numericDecimal(event)" id="min_range_percent" class="form-control"  value="<?php echo $projectdata['Project']['min_range_percent']; ?>"/>
            </td>
        </tr>
        <tr>
            <th>Max Tolerance % :</th>
            <td colspan="2">
                <input type="text" name="data[Project][max_range_percent]" placeholder="0%" onkeypress="return numericDecimal(event)" id="max_range_percent" class="form-control"  value="<?php echo $projectdata['Project']['max_range_percent']; ?>"/>
            </td>
        </tr>
        <tr>
            <th>Cost Approved:</th>
            <td colspan="2">
                <input type="text" name="data[Project][cost_approved]" placeholder="00:00" onkeypress="return numericDecimal(event)" id="cost_approved" class="form-control"  value="<?php echo $projectdata['Project']['cost_approved']; ?>"/>
            </td>
        </tr>
       <?php /* <tr>
            <th>Contingency Fund:</th>
            <td colspan="2">
                <input type="text" name="data[Project][contingency_fund]" placeholder="00:00" onkeypress="return numericDecimal(event)" id="contingency_fund" class="form-control"  value="<?php echo $projectdata['Project']['contingency_fund']; ?>"/>
            </td>
        </tr> */ ?>
        <tr>
            <th>Status:</th>
            <td class="v-top"><?php #pr($this->Format->projStatus_opts());exit;?>
                <?php echo $this->Form->input('Project.status_id',array('options'=>$this->Format->projStatus_opts(),'empty'=>'Select Status', 'class' => 'form-control fl', 'id' => 'proj_status','value'=> $projectdata['Project']['status_id'], 'placeholder' => "Status", 'style' => 'width:100%','label'=>false)); ?>
            </td>
            <td class="v-top">
                <div class="add-new-sts"><button type="button" value="Add New" name="add_new"  id="add_new_role" class="btn btn_blue" onclick="openAddNewStatusForm()">+ Add New</button></div>
                <div class="add-new-sts-form" style="display:none"><input type="text" name="data[ProjesctStatus][status]" placeholder="Status" id="status" class="form-control"  value=""/><button type="button" value="Add New" name="add_new"  id="add_new_status_detl" class="btn btn_blue" onclick="saveNewStatus(this)">Save</button><a href="javascript:void(0)" class="cancel_new_fld" onclick="cancel_new_fld(this)">Cancel</a></div>
            </td>
		</tr>
        <tr>
            <th>Project Type:</th>
            <td class="v-top">
                <?php echo $this->Form->input('Project.type_id',array('options'=>$this->Format->projType_opts(),'empty'=>'Select Type', 'class' => 'form-control fl', 'id' => 'proj_type','value'=> $projectdata['Project']['type_id'], 'placeholder' => "Type", 'style' => 'width:100%','label'=>false)); ?>
            </td>
            <td class="v-top">
                <div class="add-new-typ"><button type="button" value="Add New" name="add_new"  id="add_new_role" class="btn btn_blue" onclick="openAddNewTypeForm()">+ Add New</button></div>
                <div class="add-new-typ-form" style="display:none"><input type="text" name="data[ProjectType][type]" placeholder="Type" id="type" class="form-control"  value=""/><button type="button" value="Add New" name="add_new"  id="add_new_type_detl" class="btn btn_blue" onclick="saveNewType(this)">Save</button><a href="javascript:void(0)" class="cancel_new_fld" onclick="cancel_new_fld(this)">Cancel</a></div>
            </td>
		</tr>
        <tr>
            <th>Technology:</th>
            <td class="v-top">
                <?php echo $this->Form->input('Project.technology_id',array('options'=>$this->Format->projTechnology_opts(),'empty'=>'Select Technology', 'class' => 'form-control fl', 'id' => 'proj_technology','value'=> $projectdata['Project']['technology_id'], 'placeholder' => "Technology", 'style' => 'width:100%','label'=>false)); ?>
            </td>
            <td class="v-top">
                <div class="add-new-techn"><button type="button" value="Add New" name="add_new"  id="add_new_tech" class="btn btn_blue" onclick="openAddNewTechForm()">+ Add New</button></div>
                <div class="add-new-techn-form" style="display:none"><input type="text" name="data[ProjectTechnology][technology]" placeholder="Technology" id="technology" class="form-control"  value=""/><button type="button" value="Add New" name="add_new"  id="add_new_tech_detl" class="btn btn_blue" onclick="saveNewTech(this)">Save</button><a href="javascript:void(0)" class="cancel_new_fld" onclick="cancel_new_fld(this)">Cancel</a></div>
            </td>
        </tr>
        <tr>
            <th>Industry:</th>
            <td class="v-top" colspan="2">
                 <?php echo $this->Form->input('Project.industry_type',array('options'=>$this->Format->industry_list(),'empty'=>'Select Industry', 'class' => 'form-control fl', 'id' => 'proj_industry','value'=> $projectdata['Project']['industry_type'], 'placeholder' => "Technology", 'style' => 'width:100%','label'=>false)); ?>
            </td>
        </tr>
        <tr>
            <th>Project Manager:</th>
            <td class="v-top" colspan="2">
                <?php echo $this->Form->input('Project.manager',array('options'=>$this->Format->projManager_opts(),'empty'=>'Select Project Manager', 'class' => 'form-control fl', 'id' => 'proj_technology','value'=> $projectdata['Project']['manager'], 'placeholder' => "Technology", 'style' => 'width:100%','label'=>false)); ?>
            </td>
        </tr>
        <tr>
            <th></th>
            <td colspan="2" class="btn_align">
                <span id="subprof1">
                    <button type="submit" value="Update" name="submit_Profile"  id="submit_Profile" class="btn btn_blue"><i class="icon-big-tick"></i>Update</button>
                    <span class="or_cancel">or
                        <a onclick="canceldetail('projects/manage');">Cancel</a>
                    </span>
                </span>
                <span id="subprof2" style="display:none">
                    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." />
                </span>
            </td>
        </tr>						
    </tbody>
</table>
<?php echo $this->Form->end(); ?>

<div class="cbt"></div>
</div>
<script type="text/javascript">
    $(function(){ 
        $("#proj_start_date").datepicker({
            altField: "#prj_start_date",
            dateFormat: 'M d, yy',
            altFormat: 'yy-m-d',
            changeMonth: false,
            changeYear: false,
            hideIfNoPrevNext: true,
            onClose: function( selectedDate ) {
                $("#proj_end_date").datepicker( "option", "minDate", selectedDate );
            }
        });
        $("#proj_end_date").datepicker({
            altField: "#prj_end_date",
            dateFormat: 'M d, yy',
            altFormat: 'yy-m-d',
            changeMonth: false,
            changeYear: false,
            hideIfNoPrevNext: true,
            onClose: function( selectedDate ) {
                $( "#proj_start_date" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
        $("#more_customer_options_projs").click(function(){
            $(this).closest('tr').hide();
            $('.customer_options').show();
        });
        $('#add_clients').on('change',function(){ 
            if($(this).val() == 'add'){
                $(this).closest('tr').next('tr').show();
                $('#add_proj_cust_ids').val('add_customer');
            }else{
                $(this).closest('tr').next('tr').hide();
                 $('#add_proj_cust_id').val('');
            }
        });
        $("#cancel_customer_add").click(function(){
            $('.add_cust_tr').hide();
                 
        });
    });
    
    function submitProjectDetails(){
        var name = $('#project_name').val().trim();
        var short_name = $('#short_name').val().trim();
        var errMsg;
        var done = 1;
        if (name == "") {
            errMsg = "<?php echo __("Project Name cannot be left blank");?>!";
            $('#project_name').focus();
            done = 0;
        } else if (short_name == "") {
            errMsg = "<?php echo __("Short Name cannot be left blank");?>!";
            $('#short_name').focus();
            done = 0;
        }

        if (done == 0) {
            var op = 100;
            showTopErrSucc('error', errMsg);
            return false;
        } else {
            $('#subprof1').hide();
            $('#subprof2').show();
        }
    }
    function canceldetail(url){
        window.location.href= HTTP_ROOT+url ;
    }
</script>