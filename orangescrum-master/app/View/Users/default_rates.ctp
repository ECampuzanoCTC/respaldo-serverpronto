<style type="text/css">
    .thwidth table th { width: 152px; }
    .user_profile_con.thwidth table.bankInfoTbl{margin-top:0}
    .user_profile_con.thwidth table.bankInfoTbl tr td:not(:first-child){padding-left:15px;}
    .role-prjnm{display: block;width: 150px;margin: 10px 10px auto;}
    
    .role-cls > td {width: 100%;}
    .hide_tbody {display:none;}
    .show_tbody{display:table-row-group}
   .user_profile_con.thwidth tr.role-cls table.bankInfoTbl{margin-top:20px}
/*   .user_profile_con.thwidth tr.role-cls table.bankInfoTbl tr th{text-align:left !important}*/
</style>
<div class="user_profile_con thwidth">
    <!--Tabs section starts -->
    <?php echo $this->element("company_settings"); ?>

    <form id="defaultRateForm" accept-charset="utf-8" method="post" onsubmit="return validateDefaultRate()" action="<?php echo HTTP_ROOT; ?>users/save_default_rates" >
       
        <table cellspacing="0" cellpadding="0" class="col-lg-12" style="text-align:left;" id="default_rate_tble">
            <tbody>
               
                <tr style="border-bottom:1px solid #ccc">
                    <th colspan="3" style="text-align:center;font-weight:bold">
                    <?php echo __("Default Rate"); ?>
                    </th>
                </tr>
               
                 
                 <tbody class="tble_body cmn_tbody ">
               
                <tr class="role-cls">
                    
                    <td>
                        <table cellspacing="0" cellpadding="0" class="col-lg-12 bankInfoTbl" style="text-align:left;">
                            <tbody>
                                <tr class="scroll-fixed-tr">
                                    <th style="width:25%;text-align: center">Resource</th>
                                    <th style="width:25%;text-align: center">Role</th>
                                    <th style="width:25%;text-align: center">Task Type</th>
                                    <th style="width:25%;text-align: center">Hourly Rate <?php /*(<small><?php echo $projectRole['Project']['currency'] ;?></small>) */ ?> </th>
                                </tr>
                                <?php
                                if (isset($default_rate) && !empty($default_rate)) {
                                  //  echo "<pre>";print_r($default_rate);exit;
                                    foreach ($default_rate as $id => $dflt_rate) {
                                        ?>
                                        <tr class="role_cls">
                                            <td style="width:25%">
                                                <select name="data[default_rates][<?php echo $id; ?>][user_id]" class="form-control user_select" id="user_id_<?php echo $id ;?>" <?php /*onchange="check_tsk_type(this)" onfocus="chck_tsk_typ(this)" */ ?> >
                                                    <option value="0">Select User</option>
                                                    <?php foreach($user_list as $ku => $user){?>
                                                    <option value="<?php echo $ku ; ?>" <?php if($ku == $dflt_rate['DefaultRate']['user_id']){ echo "selected=selected" ;}?> > <?php echo $user; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td style="width:25%">
                                                <input type="hidden" name="data[default_rates][<?php echo $id; ?>][id]" value="<?php echo $dflt_rate['DefaultRate']['id']; ?>"/>
                                              <?php echo $this->Form->input('role_id',array('options'=>$this->Format->role_opts(),'empty'=>'Select Role', 'class' => 'form-control fl usr_role', 'id' => 'user_role_'.$id ,'name'=>'data[default_rates]['.$id.'][role_id]','value'=> $dflt_rate['DefaultRate']['role_id'], 'placeholder' => "Role", 'style' => 'width:100%','label'=>false)); ?>
                                                 <?php /* <input type="text" name="data[role][<?php echo $id; ?>][<?php echo $role['project_id']; ?>][role]" placeholder="Role" title="Role" id ="role_role_<?php echo $id; ?>" class="form-control" value="<?php echo $role['role']; ?>"/>
                                                */ ?>
                                                </td>
                                            
                                            <td style="width:25%">
                                                <?php $task_types = $this->Format->types_list();  ?>
                                                <select name="data[default_rates][<?php echo $id; ?>][task_type_id]" class="form-control role_tsk_type_select" id="tsk_type_id_<?php echo $id ;?>" <?php /*onchange="check_tsk_type(this)" onfocus="chck_tsk_typ(this)" */ ?> >
                                                    <option value="0">Select Task Type</option>
                                                    <?php foreach($task_types as $k => $type){?>
                                                    <option value="<?php echo $type['Type']['id'] ; ?>" <?php if($type['Type']['id'] == $dflt_rate['DefaultRate']['task_type_id']){ echo "selected=selected" ;}?> > <?php echo $type['Type']['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            
                                               
                                            <td style="width:25%">
                                                <input type="number" step="any" min ="0" name="data[default_rates][<?php echo $id; ?>][rate]" placeholder="0.00" title="Rate" id ="default_rate_<?php echo $id; ?>" class="form-control" value="<?php echo $dflt_rate['DefaultRate']['rate']; ?>" />
                                            </td>
                                            <td><a href="javascript:void(0);" onclick="removeDefaultRate(this)" data-roleId ="<?php echo $dflt_rate['DefaultRate']['id']; ?>">Remove</a></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="role_cls">
                                        <td style="width:25%">
                                                <select name="data[default_rates][<?php echo $id; ?>][user_id]" class="form-control user_select" id="user_id_<?php echo $id ;?>" <?php /*onchange="check_tsk_type(this)" onfocus="chck_tsk_typ(this)" */ ?> >
                                                    <option value="0">Select User</option>
                                                    <?php foreach($user_list as $ku => $user){?>
                                                    <option value="<?php echo $ku ; ?>"> <?php echo $user; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        <td style="width:25%">
                                          <?php /*  <input type="text" name="data[role][0][<?php echo $projectRole['Project']['id']; ?>][role]" placeholder="Role" title="Role" id ="role_role_0" class="form-control"/>
                                            */ ?>
                                            <?php echo $this->Form->input('role_id',array('options'=>$this->Format->role_opts(),'empty'=>'Select Role', 'class' => 'form-control fl usr_role', 'id' => 'user_role_0','name'=>'data[default_rates][0][role_id]','placeholder' => "Role", 'style' => 'width:100%','label'=>false)); ?>
                                        </td>
                                        
                                        <td style="width:25%">
                                            <?php $task_types = $this->Format->types_list(); ?>
                                            <select name="data[default_rates][0][type_id]" class="form-control role_tsk_type_select" id="tsk_type_id__0"  <?php /* onchange="check_tsk_type(this)" onfocus="chck_tsk_typ(this)" */ ?> >
                                                <option value="0">Select Task Type</option>
                                                <?php foreach($task_types as $k => $type){?>
                                                <option value="<?php echo $type['Type']['id'] ; ?>" > <?php echo $type['Type']['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        
                                        <td style="width:25%">
                                            <input type="number" step="any" min ="0" name="data[default_rates][0][rate]" placeholder="0.00" title="Rate" id="default_rate_0"  class="form-control" />
                                        </td>
                                        
                                        <td></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="6" style="text-align:right">
                                        <a href="javascript:void(0);" onclick="moreDefaultRate(this)">+ Add More</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
               
        </tbody>
                <tr>
                    
                    <td colspan="3" class="btn_align">
                        <div id="invoice-btns" class="pull-left" style="margin-top:20px">
                            <button type="submit" name="submit_invoice" id="submit_invoice" class="btn btn_blue">Save</button>        
                            <span class="or_cancel">or
                                <a onclick="cancelProfile(<?php echo HTTP_ROOT ;?>'dashboard');">Cancel</a>
                            </span>
                        </div>
                        <div class="clearfix"></div>
                        <span id="invoice-loader" style="display:none">
                            <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." />
                        </span>
                    </td>
                    
                </tr>
                
                 
            </tbody>
        </table>
    </form>
        
       
       
    
    <div class="cbt"></div>
</div>

<script type="text/javascript">
    var previous_val = "";
    function validateDefaultRate() { 
        $('#invoice-btns').hide();
        $('#invoice-loader').show();
        var err = 0; 
        var sum_array= new Array();
        $(".role_cls").each(function(){
            var sum_total = 0 ;
            var sum_total = $(this).find('.user_select').val() + "-" +$(this).find('.usr_role').val() + "-" + $(this).find('.role_tsk_type_select').val() ;
            sum_array.push(sum_total);
        });
       
        if(check_duplicate(sum_array)!= false){
             $('#invoice-loader').hide();
             $('#invoice-btns').show();
             var dup_array = check_duplicate(sum_array).split('-');
            $(".role_cls").each(function(){
                $(this).find('.user_select').css('border', 'none');
                $(this).find('.usr_role').css('border', 'none');
                $(this).find('.role_tsk_type_select').css('border', 'none');
                if($(this).find('.user_select').val() == dup_array[0] &&  $(this).find('.usr_role').val() == dup_array[1] && $(this).find('.role_tsk_type_select').val() == dup_array[2]){
                    $(this).find('.user_select').css('border', '1px solid red');
                    $(this).find('.usr_role').css('border', '1px solid red');
                    $(this).find('.role_tsk_type_select').css('border', '1px solid red');
                }
                
               
            });
           //  $(".role_cls").eq(b_duplicate(sum_array)).css('border', '1px solid red');
            return false;
            
        } else {
            return true;
        }
       /* }
        alert(sum_array);
        console.log(sum_array);return false;
     if (err) {
         $('#invoice-btns').show();
        $('#invoice-loader').hide();
            return false;
            
        } else {
            return true;
        } */
    }
    function check_duplicate(arr){
        for(var i = 0; i < arr.length; i++){
           if(arr.indexOf(arr[i], i+1) > -1){
             return arr[i];
           }
        }
        return false;
      }
    function moreDefaultRate(obj) { 
        var clone = $(obj).closest('table').find('tr:nth-child(2)').clone();
        clone.find('[id^="user_role"]').attr('name', 'data[default_rates][' + ($(obj).closest('table').find("tr").length - 2) + '][role_id]').val("");
        clone.find('[id^="user_id"]').attr('name', 'data[default_rates][' + ($(obj).closest('table').find("tr").length - 2) + '][user_id]').val("0");
        clone.find('[id^="tsk_type_id"]').attr('name', 'data[default_rates][' + ($(obj).closest('table').find("tr").length - 2) + '][task_type_id]').val("0");
        clone.find('[id^="default_rate"]').attr('name', 'data[default_rates][' + ($(obj).closest('table').find("tr").length - 2) + '][rate]').val('');
        clone.find('td:last').html('<a href="javascript:void(0);" onclick="removeDefaultRate(this)">Remove</a>');
       //console.log($(obj).closest('table').find('tr').length +"----"+);
     //  if($(obj).closest('table').find('tr').length < (clone.find('.usr_role').children('option').length + 1)){
             $(obj).closest('table').find('tr:last').before(clone);
       // }
       /* else{
            showTopErrSucc('error', 'No more field can be added ');
        }  */
       
    }
    
    function removeDefaultRate(obj) {
        var length = $(obj).closest('table').find('tr').length;
        var table = $(obj).closest('table');
        var dflt_rateId = $(obj).attr('data-roleId');
        if (length == 3) {
            table.find('tr:nth-child(2)').find('td:last').html('');
        } else {
            if(typeof dflt_rateId != "undefined"){ 
                $.post(HTTP_ROOT+"users/delete_default_rate",{"dflt_rateId":dflt_rateId},function(data){
                    if(data == 'success'){
                        showTopErrSucc('success', "Row Removed Successfully");
                         $(obj).closest('tr').remove();
                        if (length == 4) {
                            table.find('tr:nth-child(2)').find('td:last').html('');
                        }
                    } else {
                         showTopErrSucc('error', 'Failed to remove the row');
                         return false;
                    }
                });
            }else{ 
            $(obj).closest('tr').remove();
                if (length == 4) {
                    table.find('tr:nth-child(2)').find('td:last').html('');
                }
            }
        }
    }
    
    
    function chck_prv(obj){
        previous_val = $(obj).val() ;
    }
    function check_user(obj){
        $(obj).closest('table').find(".usr_role").not(obj).each(function(){  //console.log($(".user_select").find('option[value='+$(obj).val()+']').length);
           if($(this).val() == $(obj).val()  ) { 
               $(obj).val(previous_val);
           }
           else{
                $(obj).val($(obj).val());
           }
        });
    }
   /*$(function(){
        var stickyRibbonTop = $('.scroll-fixed-tr').offset().top - 500;
          
        $(window).scroll(function(){
                if( $(window).scrollTop() > stickyRibbonTop ) {
                        $('.scroll-fixed-tr').css({'position': 'fixed',
                            'top': '0px',
                            'border': '2px solid red', 
                            'z-index':'9999'});
                } else {
                        $('.scroll-fixed-tr').css({position: 'static', top: '0px'});
                }
        });
}); */
 </script>