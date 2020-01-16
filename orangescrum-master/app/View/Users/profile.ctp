<style type="text/css">
    .add-new-role,.add-new-role-form, .add-new-unit,.add-new-unit-form, .add-new-tech,.add-new-tech-form{text-align: right}
    .add-new-role-form input.form-control, .add-new-unit-form input.form-control, .add-new-tech-form input.form-control{margin-left:5px; width:46%; float: left}
    .add-new-role button.btn.btn_blue, .add-new-role-form button.btn.btn_blue, .add-new-unit button.btn.btn_blue,.add-new-unit-form button.btn.btn_blue, .add-new-tech button.btn.btn_blue,.add-new-tech-form button.btn.btn_blue{margin-right:0}
    .add-new-role-form button.btn.btn_blue, .add-new-unit-form button.btn.btn_blue, .add-new-tech-form button.btn.btn_blue{width:26%;padding:6px;float:left;margin-left:6px}
    .add-new-role button.btn.btn_blue, .add-new-unit button.btn.btn_blue, .add-new-tech button.btn.btn_blue{padding: 6px 10px}
    .cancel_new{float:left;margin-left:15px;margin-top:8px}
</style>
<div class="user_profile_con profileth">
<!--Tabs section starts -->
    <?php echo $this->element("personal_settings");?>
<!--Tabs section ends -->
<!--<div style="margin-top:20px;margin-bottom:0px;margin-left:22px;">
	<h2 style="margin-bottom:7px;">Personal Info</h2>
	<hr style="margin-top:0px;background:grey;"/>
</div> -->

<?php echo $this->form->create('User',array('url'=>'/users/profile','onsubmit'=>'return submitProfile()','enctype'=>'multipart/form-data','class'=>'form-horizontal')); ?>
<table cellspacing="0" cellpadding="0" class="col-lg-7" style="text-align:left;">
    <tbody>
        <tr>
            <th><?php echo __("Name"); ?>:</th>
            <td colspan="2">
		<input type="text" name="data[User][name]" placeholder="John" id="profile_name" class="form-control" value="<?php echo $userdata['User']['name']; ?>" />
	    </td>
        </tr>
        <tr>
            <th><?php echo __("Short Name"); ?>:</th>
            <td colspan="2">
		<input type="text" name="data[User][short_name]" placeholder="JD" id="short_name" class="form-control"  value="<?php echo $userdata['User']['short_name']; ?>"/>
	    </td>
        </tr>
		<tr>
            <th><?php echo __("Email"); ?>:</th>
        <td colspan="2">
		<input type="text" name="data[User][email]" placeholder="Email" id="email" class="form-control"  value="<?php echo $userdata['User']['email']; ?>"/>
	    </td>
        </tr
        <tr>
            <th><?php echo __("Time Zone"); ?>:</th>
            <td class="v-top" colspan="2">
		<select name="data[User][timezone_id]" id="timezone_id" class="form-control">
		    <?php foreach ($timezones as $get_timezone) { ?>
    		    <option  <?php if ($get_timezone['TimezoneName']['id'] == $userdata['User']['timezone_id']) { ?> selected <?php } ?> value="<?php echo $get_timezone['TimezoneName']['id']; ?>"><?php echo $get_timezone['TimezoneName']['gmt']; ?> <?php echo $get_timezone['TimezoneName']['zone']; ?></option>
		    <?php } ?>
		</select>
            </td>
        </tr>
		<?php if(defined('TPAY') && TPAY == 1 && SES_TYPE == 3) { ?>
        <tr>
           <th><?php echo __("Address"); ?>:</th>
            <td class="v-top" colspan="2">
                <textarea name="data[User][address]" id ="user_address" class="form-control"><?php echo $userdata['User']['address']; ?></textarea>
            </td>
        </tr>
        <tr>
            <th><?php echo __("Currency"); ?>:</th>
            <td class="v-top" colspan="2">
                 <?php echo $this->Form->input('currency',array('options'=>$this->Format->currency_opts(),'empty'=>'Select Currency', 'class' => 'form-control fl', 'id' => 'user_currency','value'=> $userdata['User']['currency'], 'placeholder' => "Currency", 'style' => 'width:100%','label'=>false)); ?>
            </td>
		</tr>
		<?php } ?>
        <?php if(defined('DBRD') && DBRD == 1){ ?>
        <tr>
            <th><?php echo __("Designation"); ?>:</th>
            <td class="v-top">
                <?php echo $this->Form->input('role',array('options'=>$this->Format->role_opts(),'empty'=>'Select Designation', 'class' => 'form-control fl', 'id' => 'user_role','value'=> $userdata['User']['role_id'], 'placeholder' => "Role", 'style' => 'width:100%','label'=>false)); ?>
            </td>
            <td class="v-top">
                <div class="add-new-role"><button type="button" value="Add New" name="add_new"  id="add_new_role" class="btn btn_blue" onclick="openAddNewRoleForm()">+ Add New</button></div>
                <div class="add-new-role-form" style="display:none"><input type="text" name="data[UserRole][role_name]" placeholder="Role/Designation" id="prof_roles" class="form-control"  value=""/><button type="button" value="Add New" name="add_new"  id="add_new_role" class="btn btn_blue" onclick="saveNewRole()">Save</button><a href="javascript:void(0)" class="cancel_new" onclick="cancel_new(this)">Cancel</a><?php /*<a href="javascript:void(0)" onclick="hide_addRole();">Cancel</a>*/ ?></div>
            </td>
		</tr>
        <?php } ?>
        <?php if(defined('DBRD') && DBRD == 1 ){ ?>
        <tr>
            <th><?php echo __("Business Unit"); ?>:</th>
            <td class="v-top">
                <?php echo $this->Form->input('business_unit',array('options'=>$this->Format->businessUnit_opts(),'empty'=>'Select Business Unit', 'class' => 'form-control fl', 'id' => 'business_unit_select','value'=> $userdata['User']['business_unit_id'], 'placeholder' => "Business Unit", 'style' => 'width:100%','label'=>false)); ?>
            </td>
            <td class="v-top">
                <div class="add-new-unit"><button type="button" value="Add New" name="add_new"  id="add_new_role" class="btn btn_blue" onclick="openAddNewUnitForm()">+ Add New </button></div>
                <div class="add-new-unit-form" style="display:none"><input type="text" name="data[RoleRate][role]" placeholder="Business Unit" id="business_unit" class="form-control"  value=""/><button type="button" value="Add New" name="add_new"  id="add_new_unit" class="btn btn_blue" onclick="saveNewUnit()">Save</button><a href="javascript:void(0)" class="cancel_new" onclick="cancel_new(this)">Cancel</a></div>
            </td>
		</tr>
        <?php } ?>
        <?php if(defined('DBRD') && DBRD == 1 ){ ?>
        <tr>
            <th><?php echo __("Technology"); ?>:</th>
            <td class="v-top">
                <?php echo $this->Form->input('technology',array('options'=>$this->Format->projTechnology_opts(),'empty'=>'Select Technology', 'class' => 'form-control fl', 'id' => 'technology_select','value'=> $userdata['User']['business_unit_id'], 'placeholder' => "Business Unit", 'style' => 'width:100%','label'=>false)); ?>
            </td>
            <td class="v-top">
                <div class="add-new-tech"><button type="button" value="Add New" name="add_new"  id="add_new_tech" class="btn btn_blue" onclick="openAddNewTechForms()">+ Add New </button></div>
                <div class="add-new-tech-form" style="display:none"><input type="text" name="data[RoleRate][role]" placeholder="Technology" id="technologys" class="form-control"  value=""/><button type="button" value="Add New" name="add_new"  id="add_new_tech" class="btn btn_blue" onclick="saveNewTechs()">Save</button><a href="javascript:void(0)" class="cancel_new" onclick="cancel_new(this)">Cancel</a></div>
            </td>
		</tr>
        <?php } ?>
       <?php if(defined('DBRD') && DBRD == 1 ){?>
        <tr>
            <th><?php echo __("Salary"); ?>:</th>
            <td>
                <input type="text" name="data[User][salary]" placeholder="Salary" id="salary" class="form-control"  value="<?php echo $userdata['User']['salary']; ?>" <?php if(SES_TYPE > 2){ echo "readonly" ;} ?> />
            </td>
        </tr>
       <?php } ?>
        <?php if(defined('DBRD') && DBRD == 1 ){?>
        <tr>
            <th><?php echo __("Contact Number"); ?>:</th>
            <td>
                <input type="text" name="data[User][contact_no]" placeholder="Contact Number" id="contact_no" class="form-control"  value="<?php echo $userdata['User']['contact_no']; ?>"/>
            </td>
        </tr>
        <tr>
            <th><?php echo __("Address"); ?>:</th>
            <td colspan="2">
                <textarea name="data[User][address]" placeholder="Address" id="address" class="form-control"/><?php echo $userdata['User']['address']; ?></textarea>
            </td>
        </tr>
        <tr>
            <th><?php echo __("City"); ?>:</th>
            <td>
                <input type="text" name="data[User][city]" placeholder="City" id="city" class="form-control"  value="<?php echo $userdata['User']['city']; ?>"/>
            </td>
        </tr>
        <tr>
            <th><?php echo __("Country"); ?>:</th>
            <td>
                <input type="text" name="data[User][country]" placeholder="Country" id="city" class="form-control"  value="<?php echo $userdata['User']['country']; ?>"/>
            </td>
        </tr>
       <?php } ?>
	<tr>
	    <th style="vertical-align:top;"><?php echo __("Profile Image"); ?>:</th>
	    <td colspan="2">	
		<div id="profDiv"></div>
		<?php
		if(defined('USE_S3') && USE_S3) {
			if($this->Format->pub_file_exists(DIR_USER_PHOTOS_S3_FOLDER, trim($userdata['User']['photo']))) {
				$user_img_exists = 1;
			}
		} elseif($this->Format->imageExists(DIR_USER_PHOTOS,trim($userdata['User']['photo']))){
			$user_img_exists = 1;
		}
		if($user_img_exists) { ?>
    		<div id="existProfImg" onmouseover="showEditDeleteImg()" onmouseout="hideEditDeleteImg()">
		    <?php if(defined('USE_S3') && USE_S3) {
				$fileurl = $this->Format->generateTemporaryURL(DIR_USER_PHOTOS_S3 . $userdata['User']['photo']);
			} else {
				$fileurl = HTTP_ROOT.'users/files/photos/'.$userdata['User']['photo'];
			} ?>
    		    <div>
    			<a href="<?php echo $fileurl; ?>" target="_blank">
    			    <img src="<?php echo HTTP_ROOT; ?>users/image_thumb/?type=photos&file=<?php echo trim($userdata['User']['photo']); ?>&sizex=100&sizey=100&quality=100" border="0" id="profphoto"/>
    			</a>
			    <?php echo $this->Form->hidden('photo', array('class' => 'text_field', 'id' => 'imgName1', 'name' => 'data[User][photo]')); ?>
			    <?php echo $this->Form->hidden('exst_photo', array('value' => $userdata['User']['photo'], 'class' => 'text_field', 'name' => 'data[User][exst_photo]')); ?>
    		    </div>
		    <div style="display:none" id="editDeleteImg">
    			<div id="uploadImgLnk">
			    <a title="<?php echo __('Edit Profile Image'); ?>" href="javascript:void(0);" onClick="openProfilePopup()">
				<div><img src="<?php echo HTTP_IMAGES; ?>images/edit_reply.png" border="0" class="ed_del"></div>
			    </a>
			</div>
    			<a title="<?php echo __('Delete Profile Image'); ?>" href="<?php echo HTTP_ROOT; ?>users/profile/<?php echo urlencode($userdata['User']['photo']); ?>">
			    <div onclick="return confirm('<?php echo __("Are you sure you want to delete")?>?')" ><img src="<?php echo HTTP_IMAGES; ?>images/delete.png" border="0" class="ed_del"></div>
			</a>
    		    </div>
    		    <div class="cb"></div>
    		</div>
		<?php } else { ?>
    		<div id="defaultUserImg" style="margin-left:10px;float:left;">
		    <img width="55" height="55" src="../files/photos/profile_Img.png">
    		</div>
		<div id="uploadImgLnk" class="fl" style="margin-top:20px;margin-left:5px;">									
    		    <a href="javascript:void(0);" onClick="openProfilePopup()" ><?php echo __("Choose Profile Image"); ?></a>
    		</div>
    		<input type="hidden" id="imgName1" name="data[User][photo]" />
		<?php } ?>
	    </td>
	</tr>
        <tr>
            <td colspan="3">
                <div style="float: left;"> <?php echo $this->Form->input('',array('label'=>FALSE,'name'=>'data[User][isemail]','type'=>'checkbox','style'=>'margin-top:- 10px;margin-left:120px;margin-right:10px;','div'=>FALSE,'checked'=>($userdata['User']['isemail'])?true:false));?>
               <span><?php echo __("Keep me upto date with new features"); ?></span>
                </div>
                

            </td>

        </tr>
        <tr>
	    <th></th>
            <td class="btn_align">
		<span id="subprof1">
		    <button type="submit" value="Update" name="submit_Profile"  id="submit_Profile" class="btn btn_blue"><i class="icon-big-tick"></i><?php echo __("Update"); ?></button>
		    <!--<button type="button" class="btn btn_grey" onclick="cancelProfile('<?php echo $referer;?>');"><i class="icon-big-cross"></i>Cancel</button>-->
			<span class="or_cancel"><?php echo __("or"); ?>
				<a onclick="cancelProfile('<?php echo $referer;?>');"><?php echo __("Cancel"); ?></a>
			</span>
		    <!--<a href="<?php //echo $referer; ?>">Cancel</a>-->
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
<script type="text/javascript">
function openAddNewRoleForm(){
    $('.add-new-role').hide();
    $('#prof_roles').val('');
    $('.add-new-role-form').show();
}
function saveNewRole(){
    var role = $('#prof_roles').val();
    if(trim(role) == ''){
        $('#prof_roles').css('border', '1px solid red');
        return false;
    }else{
        $.post(HTTP_ROOT+'users/saveNewRole', {'role':role}, function(data){
            if(data.success){
                showTopErrSucc('success', data.msg);
                var option = '<option value="'+data.id+'">'+role+'</option>';
                $('#user_role').append(option);
                $('#user_role').val(data.id);
                $('.add-new-role-form').hide();
                $('.add-new-role').show();
            }else{
                showTopErrSucc('error', data.msg);
            }
        }, 'json');
    }
}
function cancel_new(obj){
    $(obj).parent('div').hide();
    $(obj).parent('div').prev('div').show();//.show();
    // $('.add-new-role').show();
   // $('#prof_roles').val('');
   // $('.add-new-role-form').hide();
}
function openAddNewUnitForm(){
    $('.add-new-unit').hide();
    $('#business_unit').val('');
    $('.add-new-unit-form').show();
}
function saveNewUnit(){
    var business_unit = $('#business_unit').val();
    if(trim(business_unit) == ''){
        $('#business_unit').css('border', '1px solid red');
        return false;
    }else{
        $.post(HTTP_ROOT+'users/saveNewUnit', {'business_unit':business_unit}, function(data){
            if(data.success){
                showTopErrSucc('success', data.msg);
                var option = '<option value="'+data.id+'">'+business_unit+'</option>';
                $('#business_unit_select').append(option);
                $('#business_unit_select').val(data.id);
                $('.add-new-unit-form').hide();
                $('.add-new-unit').show();
            }else{
                showTopErrSucc('error', data.msg);
            }
        }, 'json');
    }
}
function openAddNewTechForms(){
    $('.add-new-tech').hide();
    $('#technologys').val('');
    $('.add-new-tech-form').show();
}
function saveNewTechs(){
    var technology = $('#technologys').val();
    if(trim(technology) == ''){
        $('#technologys').css('border', '1px solid red');
        return false;
    }else{
        $.post(HTTP_ROOT+'projects/saveNewTech', {'technology':technology}, function(data){
            if(data.success){
                showTopErrSucc('success', data.msg);
                var option = '<option value="'+data.id+'">'+technology+'</option>';
                $('#technology_select').append(option);
                $('#technology_select').val(data.id);
                $('.add-new-tech-form').hide();
                $('.add-new-tech').show();
            }else{
                showTopErrSucc('error', data.msg);
            }
        }, 'json');
    }
}

</script>