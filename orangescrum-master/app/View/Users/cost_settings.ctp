<style type="text/css">
    .thwidth table th { width: 152px; }
    .user_profile_con.thwidth table.bankInfoTbl{margin-top:0}
    .user_profile_con.thwidth table.bankInfoTbl tr td:not(:first-child){padding-left:15px;}
    .role-prjnm{display: block;width: 100%;margin: 10px 10px auto;}
    .role-accord{display: block;width: 15px;height: 15px;float: right;margin: 10px 10px auto;cursor: pointer;background: url('<?php echo HTTP_ROOT; ?>img/images/bx_ico.png') no-repeat 0;}
    .role-accord.minimize{background: url('<?php echo HTTP_ROOT; ?>img/images/bx_ico_minimize.png') no-repeat 0;}
    .role-cls > td {width: 100%;}
    .hide_tbody {display:none;}
    .show_tbody{display:table-row-group}
   .user_profile_con.thwidth tr.role-cls table.bankInfoTbl{margin-top:20px}
/*   .user_profile_con.thwidth tr.role-cls table.bankInfoTbl tr th{text-align:left !important}*/
</style>
<div class="user_profile_con thwidth">
    <!--Tabs section starts -->
    <?php echo $this->element("company_settings"); ?>

    <form id="costSettingsForm" accept-charset="utf-8" method="post" onsubmit="return validateSettings()" action="<?php echo HTTP_ROOT; ?>save-cost-settings" >
        <div style="float: left;">
            <?php echo $this->Form->input('',array('label'=>FALSE,'name'=>'data[Company][is_allowed]','type'=>'checkbox','style'=>'margin-top: 34px;margin-left:30px;margin-right:10px;','div'=>FALSE,'checked'=>($GLOBALS['is_admin_allowed'])?true:false)); //?><span><?php echo __("Allow Admin"); ?></span>
                
        </div>
        <div style="clear:both"></div>
        <table cellspacing="0" cellpadding="0" class="col-lg-12" style="text-align:left;" id="cost_stng_tble">
            <tbody>
               
                <tr style="border-bottom:1px solid #ccc">
                    <th colspan="3" style="text-align:center;font-weight:bold">
                 <div class="row">
                <div class="col-lg-4">
                    <form id="srch_frm" method="post" style="text-align:left">
                        <span style="display:inline-block;vertical-align: middle;">
                          <input type="text" step="any" name="search_project" placeholder="Search Project" title="Search Project" class="form-control prft_cls" value="<?php echo $search_text ; ?>"/>  
                        </span>
                        
                    <button type="submit" name="submit_search" id="submit_search" class="btn btn_blue" style="display:inline-block;vertical-align: middle;"><?php echo __("Search"); ?></button>        
                    <?php if($search_text) {?>
                            <input type="hidden" value="1" name="is_searched">
                          <span style="display:inline-block;vertical-align: middle;font-size:12px;font-weight: 200"><a href="<?php echo HTTP_ROOT ;?>cost-settings"><?php echo __("Reset"); ?></a></span>
                          <?php } ?>
                    </form>
                </div>
                <div class="col-lg-4"><?php echo __("Role Based Rate"); ?></div>
                    <div class="col-lg-4">
                    <span class="pull-right">
                          <button type="button" name="add_role" id="add_role" class="btn btn_blue" onclick="open_addRole()"><?php echo __("Add Role"); ?></button>        
                    </span>
                          <div style="clear:both"></div>
                    </div>
                  </div>
                    </th>
                </tr>
                <?php $cnt=0; $limit = 10;?>
                 
                 <tbody class="tble_body_<?php echo $cnt ; ?> cmn_tbody ">
                
                <?php $prevPrj = ''; #echo "<pre>";echo count($roles);print_r($roles);exit;
               
                foreach ($roles as $key => $projectRole) {
                if($projectRole['Project']['id'] != $prevPrj){ ?>
                <input type="hidden" id="tot_prjcts" value="<?php echo count($roles); ?>">
                <input type="hidden" id="proj_lmt" value ="<?php echo $limit ; ?>">
                <tr style="font-weight:bold;background: #eee;border: 1px solid #ccc;cursor: pointer" onclick="toggleRoles(this)">
                    <td style="text-align: left;"><span class="role-prjnm ellipsis-view"><?php echo $projectRole['Project']['name']; ?></span></td>
                    <td style="text-align: right"><span class="role-accord"></span></td>
                </tr> 
                <?php } ?>
                <tr class="role-cls" style="display:none">
                    
                    <td>
                        <table cellspacing="0" cellpadding="0" class="col-lg-12 bankInfoTbl" style="text-align:left;">
                            <tbody>
                                <tr>
                                    <th style="width:20%;text-align: center"><?php echo __("Resource"); ?></th>
                                    <th style="width:20%;text-align: center"><?php echo __("Task Type"); ?></th>
                                    <th style="width:20%;text-align: center"><?php echo __("Role"); ?></th>
                                    <th style="width:20%;text-align: center"><?php echo __("Hourly Rate"); ?> (<small><?php echo $projectRole['Project']['currency'] ;?></small>)</th>
                                    <th style="width:20%;text-align: center"><?php echo __("Hourly Actual Cost"); ?> (<small><?php echo $projectRole['Project']['currency'] ;?></small>)</th>
                                <?php /*    <th style="width:20%;text-align: center">Hourly Profit</th> */?>
                                    
                                </tr>
                                <?php
                                if (!empty($projectRole['RoleRate'])) {
                                    foreach ($projectRole['RoleRate'] as $id => $role) {
                                        ?>
                                        <tr class="role_cls">
                                            <td style="width:20%">
                                                <select name="data[role][<?php echo $id; ?>][<?php echo $role['project_id']; ?>][user_id]" class="form-control user_select" id="user_id_proj_<?php echo $id ;?>" onchange="check_user(this)" onfocus="chck_prv(this)">
                                                    <option value="0">Select User</option>
                                                <?php foreach($projectRole["User"] as $ku => $vu){?>
                                                    <option value="<?php echo $ku ; ?>" <?php if($ku == $role['user_id']){ echo "selected=selected" ;}?> > <?php echo $vu; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td style="width:20%">
                                                <?php $task_types = $this->Format->types_list();  ?>
                                                <select name="data[role][<?php echo $id; ?>][<?php echo $role['project_id']; ?>][type_id]" class="form-control role_tsk_type_select" id="tsk_type_id_proj_<?php echo $id ;?>" <?php /*onchange="check_tsk_type(this)" onfocus="chck_tsk_typ(this)" */ ?>  >
                                                    <option value="0">Select Task Type</option>
                                                    <?php foreach($task_types as $k => $type){?>
                                                    <option value="<?php echo $type['Type']['id'] ; ?>" <?php if($type['Type']['id'] == $role['type_id']){ echo "selected=selected" ;}?> > <?php echo $type['Type']['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <td style="width:20%">
                                                <input type="hidden" name="data[role][<?php echo $id; ?>][<?php echo $role['project_id']; ?>][id]" value="<?php echo $role['id']; ?>"/>
                                                <input type="hidden" name="data[role][<?php echo $id; ?>][<?php echo $role['project_id']; ?>][project_id]" id="role_prjid_<?php echo $id; ?>" value="<?php echo $role['project_id']; ?>"/>
                                              <?php echo $this->Form->input('role_id',array('options'=>$this->Format->role_opts(),'empty'=>'Select Role', 'class' => 'form-control fl usr_role', 'id' => 'user_role_'.$id ,'name'=>'data[role]['.$id.']['.$role['project_id'].'][role_id]','value'=> $role['role_id'], 'placeholder' => "Role", 'style' => 'width:100%','label'=>false)); ?>
                                                 <?php /* <input type="text" name="data[role][<?php echo $id; ?>][<?php echo $role['project_id']; ?>][role]" placeholder="Role" title="Role" id ="role_role_<?php echo $id; ?>" class="form-control" value="<?php echo $role['role']; ?>"/>
                                                */ ?>
                                                </td>
                                               
                                            <td style="width:20%">
                                                 
                                                <input type="number" step="any" min ="0" name="data[role][<?php echo $id; ?>][<?php echo $role['project_id']; ?>][rate]" placeholder="0.00" title="Rate" id ="role_rate_<?php echo $id; ?>" class="form-control" onchange="chng_profit(this)" value="<?php echo $role['rate']; ?>" />
                                            
                                                
                                            </td>
                                            
                                               
                                            <td style="width:20%">
                                                <input type="number" step="any" min ="0" name="data[role][<?php echo $id; ?>][<?php echo $role['project_id']; ?>][actual_rate]" placeholder="0.00" title="Actual Cost" id ="role_actrate_<?php echo $id; ?>" class="form-control actl_rate" onchange="add_profit(this)" value="<?php echo $role['actual_rate']; ?>" />
                                            </td>
                                            <?php /*
                                            <td style="width:20%">
                                                <input type="text" step="any" min ="0" name="data[role][<?php echo $id; ?>][<?php echo $role['project_id']; ?>][profit]" placeholder="0.00" title="Profit" class="form-control prft_cls" id ="role_profit_<?php echo $id; ?>" value="<?php echo $role['profit']; ?>" readonly/>
                                            </td> */ ?>
                                            <td><a href="javascript:void(0);" onclick="removeRoleRate(this)" data-roleId ="<?php echo $role['id']; ?>">Remove</a></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="role_cls">
                                        <td style="width:20%">
                                                <select name="data[role][0][<?php echo $projectRole['Project']['id']; ?>][user_id]" class="form-control user_select" id="user_id_proj_0" onchange="check_user(this)" onfocus="chck_prv(this)">
                                                    <option value="0">Select User</option>
                                                    <?php foreach($projectRole["User"] as $ku => $vu){?>
                                                    <option value="<?php echo $ku ; ?>"> <?php echo $vu; ?></option>
                                                    <?php } ?>
                                                </select>
                                        </td>
                                        <td style="width:20%">
                                            <?php $task_types = $this->Format->types_list(); ?>
                                            <select name="data[role][0][<?php echo $projectRole['Project']['id']; ?>][type_id]" class="form-control role_tsk_type_select" id="tsk_type_id_proj_<?php echo $id ;?>" <?php /*onchange="check_tsk_type(this)" onfocus="chck_tsk_typ(this)" */ ?> >
                                                <option value="0">Select Task Type</option>
                                                <?php foreach($task_types as $k => $type){?>
                                                <option value="<?php echo $type['Type']['id'] ; ?>" <?php if($type['Type']['id'] == $role['type_id']){ echo "selected=selected" ;}?> > <?php echo $type['Type']['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td style="width:20%">
                                          <?php /*  <input type="text" name="data[role][0][<?php echo $projectRole['Project']['id']; ?>][role]" placeholder="Role" title="Role" id ="role_role_0" class="form-control"/>
                                            */ ?>
                                            <?php echo $this->Form->input('role_id',array('options'=>$this->Format->role_opts(),'empty'=>'Select Role', 'class' => 'form-control fl usr_role', 'id' => 'user_role_0','name'=>'data[role][0]['.$projectRole['Project']['id'].'][role_id]','placeholder' => "Role", 'style' => 'width:100%','label'=>false)); ?>
                                            <input type="hidden" name="data[role][0][<?php echo $projectRole['Project']['id']; ?>][project_id]" id="role_prjid_0" value="<?php echo $projectRole['Project']['id']; ?>" class="form-control"/>
                                        </td>
                                        <td style="width:20%">
                                            <input type="number" step="any" min ="0" name="data[role][0][<?php echo $projectRole['Project']['id']; ?>][rate]" placeholder="0.00" title="Rate" id="role_rate_0" onchange="chng_profit(this)" class="form-control" />
                                        </td>
                                        <td style="width:20%">
                                                <input type="number" step="any" min ="0" name="data[role][0][<?php echo $projectRole['Project']['id']; ?>][actual_rate]" placeholder="0.00" title="Actual Cost" id="role_actrate_0" onchange="add_profit(this)" class="form-control actl_rate" />
                                        </td>
                                        <?php /*
                                        <td style="width:20%">
                                            <input type="text" step="any" min ="0" name="data[role][0][<?php echo $projectRole['Project']['id']; ?>][profit]" placeholder="0.00" title="Profit" id="role_profit_0"class="form-control prft_cls" readonly/>
                                        </td> */ ?>
                                        <td></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="6" style="text-align:right">
                                        <a href="javascript:void(0);" onclick="moreRoleRate(this)">+ <?php echo __("Add More"); ?></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php $prevPrj = $projectRole['Project']['id'];
                $cnt ++ ;
                if($cnt%$limit == 0){
                    
                    echo "</tbody>" ;
                    echo "<tbody class='tble_body_".$cnt." cmn_tbody hide_tbody'>" ;
                }
                } ?>
        </tbody>
                <tr>
                    
                    <td colspan="3" class="btn_align">
                         <div  class="pull-right" style="margin-top:20px">
                        <input type="button" id="seeLessRecords" value="Prev">
                        <input type="button" id="seeMoreRecords" value="Next">
                         </div>
                        <div id="invoice-btns" class="pull-left" style="margin-top:20px">
                            <button type="submit" name="submit_invoice" id="submit_invoice" class="btn btn_blue"><?php echo __("Save"); ?></button>        
                            <span class="or_cancel">or
                                <a onclick="cancelProfile(HTTP_ROOT+'dashboard');"><?php echo __("Cancel"); ?></a>
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
<div class="popup_overlay"></div>

<script type="text/javascript">
    var previous_val = "";
    $(document).ready(function(){
         var tot_prjcts = $("#tot_prjcts").val(); 
         var prj_lmts = $('#proj_lmt').val(); 
         if(parseInt(tot_prjcts) > parseInt(prj_lmts)){ 
             $("#seeMoreRecords").show();
             $("#seeLessRecords").hide();
         } else {
             $("#seeMoreRecords").hide();
             $("#seeLessRecords").hide();
         } 
         $("#seeMoreRecords").click(function(){
             var tbody = $('.cmn_tbody:visible:last').next('.cmn_tbody') ;
          if(tbody.hasClass('hide_tbody')){
              $("#seeLessRecords").show();
             
             $('.cmn_tbody:visible:last').addClass('hide_tbody');
              $('.cmn_tbody:visible:last').removeClass('show_tbody');
              tbody.addClass('show_tbody');
              tbody.removeClass('hide_tbody');
          } 
          if(tbody.next('.cmn_tbody').length < 1){
              $("#seeMoreRecords").hide();
          } 
         });
         $("#seeLessRecords").click(function(){
             var tbody = $('.cmn_tbody:visible:last');//;.attr('class') ; alert(tbody);
             $("#seeMoreRecords").show();
          if(tbody.hasClass('show_tbody')){
            tbody.removeClass('show_tbody');
              tbody.addClass('hide_tbody');
              tbody.prev('.cmn_tbody').addClass('show_tbody');
              tbody.prev('.cmn_tbody').removeClass('hide_tbody');
              if($('.tble_body_0').is(':visible')){
                  $("#seeLessRecords").hide();
              }
          }
         });
      /* var trs = $(".cmn_tbody");console.log(trs.length);
        var btnMore = $("#seeMoreRecords");
        var btnLess = $("#seeLessRecords");
        var trsLength = trs.length;
        var currentIndex = 2;

        trs.hide();
        trs.slice(0, 2).show(); 
        checkButton();

        btnMore.click(function (e) { 
            e.preventDefault();
            $(".cmn_tbody").slice(currentIndex, currentIndex + 2).show();
            currentIndex += 2;
            checkButton();
        });

        btnLess.click(function (e) { 
            e.preventDefault();
            $(".cmn_tbody").slice(currentIndex - 2, currentIndex).hide();          
            currentIndex -= 2;
            checkButton();
        });
        function checkButton() {
        var currentLength = $("cmn_tbody:visible").length; console.log(currentLength);console.log(trsLength);
        if (currentLength >= trsLength && currentLength < 2) {
            btnMore.hide();            
        } else {
            btnMore.show();   
        }
        if (trsLength > 4 && currentLength > 4) {
            btnLess.show();
        } else {
            btnLess.hide();
        }
    } */

    }); 
    
    
    function validateSettings() {
        $('#invoice-btns').hide();
        $('#invoice-loader').show();
        var err = 0; 
        $(".user_select").each(function(){
            var usr_slct = $(this).val();
            if (usr_slct == 0) {
          //  showTopErrSucc('error', 'Please Select an User');
          //  err = 1;
            }
        });
     if (err) {
         $('#invoice-btns').show();
        $('#invoice-loader').hide();
            return false;
            
        } else {
            return true;
        }
    }
    function moreRoleRate(obj) {
        var clone = $(obj).closest('table').find('tr:nth-child(2)').clone();
        clone.find('[id^="user_id_proj"]').attr('name', 'data[role][' + ($(obj).closest('table').find("tr").length - 2) + ']['+clone.find('[id^="role_prjid_"]').val() +'][user_id]').val(0);
        clone.find('[id^="user_role"]').attr('name', 'data[role][' + ($(obj).closest('table').find("tr").length - 2) + ']['+clone.find('[id^="role_prjid_"]').val() +'][role_id]').val('');
        clone.find('[id^="role_rate"]').attr('name', 'data[role][' + ($(obj).closest('table').find("tr").length - 2) + ']['+clone.find('[id^="role_prjid_"]').val() +'][rate]').val('');
        clone.find('[id^="role_actrate"]').attr('name', 'data[role][' + ($(obj).closest('table').find("tr").length - 2) + ']['+clone.find('[id^="role_prjid_"]').val() +'][actual_rate]').val('');
        clone.find('[id^="tsk_type_id_proj"]').attr('name', 'data[role][' + ($(obj).closest('table').find("tr").length - 2) + ']['+clone.find('[id^="role_prjid_"]').val() +'][type_id]').val(0);
        //clone.find('[id^="role_profit"]').attr('name', 'data[role][' + ($(obj).closest('table').find("tr").length - 2) + ']['+clone.find('[id^="role_prjid_"]').val() +'][profit]').val('');
        clone.find('[id^="role_prjid_"]').attr('name', 'data[role][' + ($(obj).closest('table').find("tr").length - 2) + ']['+clone.find('[id^="role_prjid_"]').val() +'][project_id]');
        clone.find('td:last').html('<a href="javascript:void(0);" onclick="removeRoleRate(this)">Remove</a>');
       //console.log($(obj).closest('table').find('tr').length +"----"+);
       if($(obj).closest('table').find('tr').length < (clone.find('.user_select').children('option').length + 1)){
             $(obj).closest('table').find('tr:last').before(clone);
        }
        else{
            showTopErrSucc('error', 'No more field can be added ');
        }
       
    }
    function removeRoleRate(obj) {
        var length = $(obj).closest('table').find('tr').length;
        var table = $(obj).closest('table');
        var role_id = $(obj).attr('data-roleId');
        if (length == 3) {
            table.find('tr:nth-child(2)').find('td:last').html('');
        } else {
            if(typeof role_id != "undefined"){
                $.post(HTTP_ROOT+"users/delete_roledata",{"roleId":role_id},function(data){
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
    
    function toggleRoles(obj,type){
        if(type == "span"){
        $(obj).toggleClass('minimize');
        $(obj).closest('tr').next('tr').toggle();
        } else {
             $(obj).next('tr').toggle();
        }
    }
    function chck_prv(obj){
        previous_val = $(obj).val() ;
    }
    function check_user(obj){
        $(obj).closest('table').find(".user_select").not(obj).each(function(){  //console.log($(".user_select").find('option[value='+$(obj).val()+']').length);
           if($(this).val() == $(obj).val()  ) { 
               $(obj).val(previous_val);
           }
           else{
                $(obj).val($(obj).val());
           }
        });
        
      /*  if($(obj).val() != 0){
            var url = HTTP_ROOT+"users/getHourlyRate";
            var user_id = $(obj).val() ;
            $.post(url,{'user_id':user_id},function(data){
                $(obj).parent('td').next('td').next('td').next('td').next('td').find('.actl_rate').val(data);
            });
        } */
        
    }
    
    function add_profit(obj){ 
        
        var rate = $(obj).parent('td').prev('td').find('input[type="number"]').val();
        var actual_rate = $(obj).val();
        var profit = Math.abs(rate - actual_rate) ; 
        if(rate != ""){
        $(obj).parent('td').next('td').find('input[type="text"]').val(profit);
        }
    }
    function chng_profit(obj){
        var rate = $(obj).val();
        var actual_rate = $(obj).parent('td').next('td').find('input[type="number"]').val();
        var prft = $(obj).parent('td').next('td').next('td').find('.prft_cls').val();
        //if(actual_rate == '')
        if(actual_rate != ""){
            var profit = Math.abs(rate - actual_rate) ;
             $(obj).parent('td').next('td').next('td').find('.prft_cls').val(profit);
        }
       
    }
    
    function open_addRole(){
        openPopup();
        $(".new_role").show();
        $(".loader_dv").hide();
        //setting default form field value

        $('#inner_roletype').show();
        $("#role").val('');
        $('#role').css('border', 'none');
        $("#role").focus();
    }
    function AddNewRole(){
        var role = $('#role').val();
        if(trim(role) == ''){
            $('#role').css('border', '1px solid red');
            return false;
        }else{
            $.post(HTTP_ROOT+'users/saveNewRole', {'role':role}, function(data){
                if(data.success){
                    showTopErrSucc('success', data.msg);
                    var option = '<option value="'+data.id+'">'+role+'</option>';
                    $('.usr_role').append(option);
                   // $('#user_role').val(data.id);
                    closePopup();
                }else{
                    showTopErrSucc('error', data.msg);
                    closePopup();
                }
            }, 'json');
        }
    }
</script>
