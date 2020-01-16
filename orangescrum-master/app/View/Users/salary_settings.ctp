<style type="text/css">
    .thwidth table th { width: 152px; }
    .user_profile_con.thwidth table.bankInfoTbl{margin-top:0}
    .user_profile_con.thwidth table.bankInfoTbl tr td:not(:first-child){padding-left:15px;}
    .role-prjnm{display: block;width: 150px;margin: 10px 10px auto;}
    .role-accord{display: block;width: 15px;height: 15px;float: right;margin: 10px 10px auto;cursor: pointer;background: url('<?php echo HTTP_ROOT; ?>img/images/bx_ico.png') no-repeat 0;}
    .role-accord.minimize{background: url('<?php echo HTTP_ROOT; ?>img/images/bx_ico_minimize.png') no-repeat 0;}
    .role-cls > td {width: 100%;}
    .hide_tbody {display:none;}
    .show_tbody{display:table-row-group}
   .user_profile_con.thwidth tr.role-cls table.bankInfoTbl{margin-top:20px}
   .sal-main-div{height:600px;overflow-y:auto}
/*   .user_profile_con.thwidth tr.role-cls table.bankInfoTbl tr th{text-align:left !important}*/
</style>
<div class="user_profile_con thwidth">
    <!--Tabs section starts -->
    <?php echo $this->element("company_settings"); ?>
<?php

if(count($user_list) > 0){ ?>
    <?php echo $this->form->create('User',array('url'=>'/users/salary_settings','onsubmit'=>'return submitsalarys()','class'=>'form-horizontal')); ?>
<div class="sal-main-div">  
<table cellspacing="0" cellpadding="0" class="col-lg-6" style="text-align:left;" id="sal_stng_tble">
    <?php $cnt=0; $limit = 2;?>
                 
        <tbody class="tble_body_<?php echo $cnt ; ?> cmn_tbody ">
        <tr>
            <th><b><?php echo __("User Name"); ?></b></th>
            <th><b><?php echo __("Salary"); ?></b></th>
            <input type="hidden" id="tot_prjcts" value="<?php echo count($user_list); ?>">
            <input type="hidden" id="proj_lmt" value ="<?php echo $limit ; ?>">
        </tr>
        
    <?php foreach($user_list as $ku => $vu){ ?>
        <tr>
            <td>
                <input type="hidden" name="data[User][id][]" value="<?php echo $vu['User']['user_id']; ?>" >
                <?php echo ucfirst($vu[0]['Name']); ?>
            </td>
            <td>
                <input type="number" step="any" min ="0" name="data[User][salary][]" class="form-control"  placeholder="Enter Salary" value="<?php echo $vu['User']['user_salary']; ?>">
            </td>
        </tr>
        <?php 
         } ?>
        <tr>
            
           <th></th>
            <td colspan="2" class="btn_align">
                <span id="subprof1">
                    <button type="submit" value="Save" name="submit_Profile"  id="submit_salary" class="btn btn_blue"><i class="icon-big-tick"></i><?php echo __("Save"); ?></button>
                    <span class="or_cancel"><?php echo __("or"); ?>
                        <a onclick="cancelProfile(HTTP_ROOT+'dashboard');"><?php echo __("Cancel"); ?></a>
                    </span>
                </span>
                <span id="subprof2" style="display:none">
                    <img src="<?php echo HTTP_IMAGES; ?>images/case_loader2.gif" alt="Loading..." />
                </span>
            </td> 
        </tr>
    </tbody>
</table>
</div>
<?php echo $this->Form->end(); ?>
        
<?php } ?>
</div>
<script>
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
       function submitsalarys(){ 
           $('#subprof1').hide();
            $('#subprof2').show();
       }
</script>
