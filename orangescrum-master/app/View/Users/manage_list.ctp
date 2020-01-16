<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/demo_page.css">
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/demo_table.css">
<link rel="stylesheet"  type="text/css" href="<?php echo HTTP_ROOT; ?>css/betauser.css">
<script type="text/javascript" src="<?php echo JS_PATH . 'datatable.js'; ?>"></script>
<script type="text/javascript" src="<?php echo JS_PATH . 'betauser.js'; ?>"></script>
<style type="text/css">
    .table.table-condensed.table-hover.table-striped.m-list-tbl.dataTable{width:100%}
    .task_listing table.table td.text-center, .task_listing table.table th.text-center, table.table td.text-center, table.table th.text-center {text-align: center;}
    .task_listing table.table th, .cmn-popup table.table th {font-weight: normal;color: #727272;font-size: 14px;vertical-align: middle;border-bottom: 2px solid #ccc;text-align: left;background: #fff;}
    .task_listing table.table td, .cmn-popup table.table td {border: 0px;vertical-align: middle;color: #888;font-size: 14px;text-align: left;}
    ul.dropdown-menu li a{text-align: left}
    ul.dropdown-menu li a .glyphicon{margin-right:5px;margin-left:5px}
    .pop_arrow_new{margin-top: -12px}
</style>
<div class="tab tab_comon tab_task">
    <ul class="nav-tabs mod_wide">
        <li id="task_li" <?php if ($role == '' || $role == 'all') { ?>class="active" <?php } ?>>
            <a href="javascript:void(0);" onclick="select_user_stat('all');" title="<?php echo __("Active"); ?>">
                <div class="ctab_sprt_icon usrr_actv"></div>
                <div class="ellipsis-view maxWidth120"><?php echo __("Active"); ?><span class="counter">(<?php echo $active_user_cnt; ?>)</span></div>
                <div class="cbt"></div>
            </a>
        </li>
        <li id="file_li" <?php if ($role == 'invited') { ?>class="active" <?php } ?>>
            <a href="javascript:void(0);" onclick="select_user_stat('invited');" title="<?php echo __("Invited"); ?>">
                <div class="ctab_sprt_icon usrr_invt"></div>
                <div class="ellipsis-view maxWidth120"><?php echo __("Invited"); ?><span class="counter">(<?php echo $invited_user_cnt; ?>)</span></div>
                <div class="cbt"></div>
            </a>
        </li>
        <li id="task_li" <?php if ($role == 'disable') { ?>class="active" <?php } ?>>
            <a href="javascript:void(0);" onclick="select_user_stat('disable');" title="<?php echo __("Disabled"); ?>">
                <div class="ctab_sprt_icon usrr_disbl"></div>
                <div class="ellipsis-view maxWidth120"><?php echo __("Disabled"); ?><span class="counter">(<?php echo $disabled_user_cnt; ?>)</span></div>
                <div class="cbt"></div>
            </a>
        </li>
        <?php if (defined('INV') && INV == 1) { /* ?>
          <li id="task_li" <?php if($role == 'clients'){?>class="active" <?php }?>>
          <a href="javascript:void(0);" onclick="filterUserRole('clients','<?php echo $user_srch;?>');" title="<?php echo __("Clients"); ?>">
          <div class="usrr_disbl fl"></div>
          <div class="fl ellipsis-view maxWidth120"><?php echo __("Clients"); ?><span class="counter">(<?php echo $totalclients;?>)</span></div>
          <div class="cbt"></div>
          </a>
          </li>
          <?php */
        }
        ?>
        <div class="cbt"></div>
    </ul>
</div>

<?php #echo "<pre>";print_r($userArr); exit;  ?>
<div class=" task_lis_page">
    <div class="task_listing">
        <div class="proj_grids glide_div" id="project_grid_div">
            <div class="loader_bg" id="projectLoader"><div class="loader-wrapper md-preloader md-preloader-warning"><svg version="1.1" height="48" width="48" viewBox="0 0 75 75"><circle cx="37.5" cy="37.5" r="25" stroke-width="4"></circle></svg></div></div>
            <div class="utilization_filter_msg" data-column-id="filter_msg" style="display:none;"></div>
            <div class="fr pos_ets_btn"><button type="button" value="Export" name="Export" class="btn btn_blue" onclick="export_usrlstng()">Export Task (.csv)</button></div>
            <div class="cb"></div>
            <div class="m-cmn-flow">
                <table id="grid-keep-selection" class="table table-condensed table-hover table-striped m-list-tbl">
                    <thead>
                        <tr>
                            <th class="text-center tophead manage-list-th1">&nbsp;</th>
                            <th class="tophead manage-list-th2 order-column"><?php echo __("User Name"); ?></th>
                            <th class="text-center tophead manage-list-th3 order-column"><?php echo __("Email"); ?></th>
                            <th class="text-center tophead manage-list-th4">Projects</th>
                            <th class="text-center text-center text-center tophead manage-list-th5" ><?php echo __("Total Project(s)"); ?></th>
                            <th class="text-center tophead manage-list-th6 order-column"><?php echo __("User Type"); ?></th>
                            <th class="text-center tophead manage-list-th7 order-column"><?php echo __("Designation"); ?></th>
                            <?php /*  <th class="text-center tophead manage-list-th8 order-column">Start<br />Date</th>
                              <th class="text-center tophead manage-list-th9 order-column">End<br />Date</th>
                              <th class="text-center tophead manage-list-th10 order-column">Estimated Hour(s)</th>
                              <th class="text-center tophead manage-list-th11 order-column">Number Of Days</th>
                              <th class="text-center tophead manage-list-th12 order-column">Storage<br />Used(Mb)</th>
                              <th class="text-center tophead manage-list-th13 order-column">Hour(s)<br />Spent</th> */ ?>
                            <th class="text-center tophead manage-list-th14"><?php echo __("Last Activity");?></th>
                        </tr>
                    </thead>
                    <?php
                    //}
                    // echo "<pre>";print_r($prjAllArr);exit;
                    if (count($userArr)) {
                        foreach ($userArr as $k => $user) {
                            if ($user['CompanyUser']['user_type'] == 1) {
                                $colors = 'user-owner';
                                $usr_typ_name = __('Owner', true);
                            } else if ($user['CompanyUser']['user_type'] == 2) {
                                $colors = 'user-admin';
                                $usr_typ_name = __('Admin', true);
                            } else if ($user['CompanyUser']['user_type'] == 3 && $role != 3) {
                                $colors = 'user-usr';
                                $usr_typ_name = __('User', true);
                            }

                            if ($role == 'invited') {
                                $colors = 'user-usr';
                                $usr_typ_name = __('User', true);
                            }
                            if (defined('CR') && CR == 1) {
                                if ($user['CompanyUser']['is_client'] == 1) {
                                    $colors = 'cli_clr';
                                    $usr_typ_name = __('Client', true);
                                }
                                if ($user['CompanyUser']['is_client'] == 1 && $user['CompanyUser']['user_type'] == 2) {
                                    $colors = 'cli_clr';
                                    $usr_typ_name = __('Admin/Client', true);
                                }
                            }
                            if(isset($user['User']['role']) && !empty($user['User']['role'])){
                                $usr_typ_name = $user['User']['role'] ;
                            }
                            ?>
                            <tr class="row_tr prjct_lst_tr">
                                <td class="text-center">
                                    <div class="dropdown">
                                        <div data-toggle="dropdown" class="sett dropdown-toggle" ></div>
        <?php if (SES_TYPE == 1 || SES_TYPE == 2 || (SES_TYPE == 3 && $prjArr['Project']['user_id'] == SES_ID)) { ?>
                                            <ul class="dropdown-menu " >
                                                <li class="pop_arrow_new"></li>
                                                <?php if ($user['CompanyUser']['user_type'] == 1) { ?>
                                                    <?php
                                                    if (defined('ROLE') && ROLE == 1 && array_key_exists('Assign Project', $roleAccess) && $roleAccess['Assign Project'] == 0) {
                                                        
                                                    } else {
                                                        ?>
                                                        <li><a class="icon-assign-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['name']; ?>"><i class="glyphicon glyphicon-paste "></i> <?php echo __("Assign Project"); ?></a></li>
                                                    <?php } ?>
                                                    <?php
                                                    if (defined('ROLE') && ROLE == 1 && array_key_exists('Remove Project', $roleAccess) && $roleAccess['Remove Project'] == 0) {
                                                        
                                                    } else {
                                                        ?>
                                                        <li><input id="rmv_allprj_<?php echo $user['User']['id']; ?>" type="hidden" value="<?php echo $user['User']['all_projects']; ?>"/>
                                                            <a id="rmv_prj_<?php echo $user['User']['id']; ?>" class="icon-remprj-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['name']; ?>" data-total-project="<?php echo $user['User']['total_project']; ?>" <?php if ($user['User']['all_project'] == '') { ?> style="display:none;" <?php } ?>><i class="glyphicon glyphicon-minus-sign "></i> <?php echo __("Remove Project"); ?></a></li>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <?php if ($role == 'invited') { ?>
                                                        <?php
                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Assign Project', $roleAccess) && $roleAccess['Assign Project'] == 0) {
                                                            
                                                        } else {
                                                            ?>
                                                            <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                <a class="icon-assign-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>"><i class="glyphicon glyphicon-paste "></i> <?php echo __("Assign Project"); ?></a>
                                                                <input id="rmv_allprj_<?php echo $user['User']['id']; ?>" type="hidden" value="<?php echo $user['User']['all_projects']; ?>"/>
                                                                <span id="rmv_prj_<?php echo $user['User']['id']; ?>" <?php if ($user['User']['all_project'] == '') { ?> style="display:none;"<?php } ?>></span>
                                                            </li>
                                                        <?php } ?>
                                                        <?php
                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Remove Project', $roleAccess) && $roleAccess['Remove Project'] == 0) {
                                                            
                                                        } else {
                                                            ?>
                                                            <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                <a class="icon-remprj-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>" data-total-project="<?php echo $user['User']['total_project']; ?>"><i class="glyphicon glyphicon-minus-sign "></i> <?php echo __("Remove Project"); ?></a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php
                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Delete User', $roleAccess) && $roleAccess['Delete User'] == 0) {
                                                            
                                                        } else {
                                                            ?>
                                                            <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                <a class="icon-delete-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?del=<?php echo urlencode($user['User']['uniq_id']); ?>&role=<?php echo $_GET['role']; ?>" Onclick="return confirm('Are you sure you want to delete \'<?php echo $user['User']['email']; ?>\' ?')"><i class="glyphicon glyphicon-remove-circle "></i> <?php echo __("Delete"); ?></a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php
                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Add New User', $roleAccess) && $roleAccess['Add New User'] == 0) {
                                                            
                                                        } else {
                                                            ?>
                                                            <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">				  
                                                                <a class="icon-resend-usr" href="javascript:void(0);" onclick="return resend_invitation('<?php echo $user['User']['qstr']; ?>','<?php echo $user['User']['email']; ?>');"><i class="glyphicon glyphicon-envelope "></i>  <?php echo __("Resend"); ?></a>
                                                            </li>
                                                        <?php } ?>
                                                    <?php } else if ($role == 'disable') { ?>
                                                        <?php
                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Enable User', $roleAccess) && $roleAccess['Enable User'] == 0) {
                                                            
                                                        } else {
                                                            ?>
                                                            <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                <a class="icon-enable-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?act=<?php echo urlencode($user['User']['uniq_id']); ?>&role=<?php echo $_GET['role']; ?>" Onclick="return confirm('<?php echo __("Are you sure you want to enable"); ?> \'<?php echo $user['User']['name']; ?>\' ?')"><i class="glyphicon glyphicon-ok-sign"></i> <?php echo __("Enable"); ?></a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php
                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Remove Project', $roleAccess) && $roleAccess['Remove Project'] == 0) {
                                                            
                                                        } else {
                                                            ?>
                                                            <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                <input id="rmv_allprj_<?php echo $user['User']['id']; ?>" type="hidden" value="<?php echo $user['User']['all_projects']; ?>"/>
                                                                <a id="rmv_prj_<?php echo $user['User']['id']; ?>" class="icon-remprj-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['name']; ?>" data-total-project="<?php echo $user['User']['total_project']; ?>" <?php if ($user['User']['all_project'] == '') { ?> style="display:none;" <?php } ?>><i class="glyphicon glyphicon-minus-sign "></i> <?php echo __("Remove Project"); ?></a>
                                                            </li>
                                                        <?php } ?>
                                                    <?php } else if ($role == 'client' && defined('INV') && INV == 1) { ?>
                                                        <?php
                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Enable User', $roleAccess) && $roleAccess['Enable User'] == 0) {
                                                            
                                                        } else {
                                                            ?>
                                                            <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                <a class="icon-enable-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?act=<?php echo urlencode($user['User']['uniq_id']); ?>&role=<?php echo $_GET['role']; ?>" Onclick="return confirm('<?php echo __("Are you sure you want to enable"); ?> \'<?php echo $user['User']['name']; ?>\' ?')"><i class="glyphicon glyphicon-ok-sign"></i> <?php echo __("Enable"); ?></a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php
                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Remove Project', $roleAccess) && $roleAccess['Remove Project'] == 0) {
                                                            
                                                        } else {
                                                            ?>
                                                            <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                <input id="rmv_allprj_<?php echo $user['User']['id']; ?>" type="hidden" value="<?php echo $user['User']['all_projects']; ?>"/>
                                                                <a id="rmv_prj_<?php echo $user['User']['id']; ?>" class="icon-remprj-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['name']; ?>" data-total-project="<?php echo $user['User']['total_project']; ?>" <?php if ($user['User']['all_project'] == '') { ?> style="display:none;" <?php } ?>><i class="glyphicon glyphicon-minus-sign "></i>  <?php echo __("Remove Project"); ?></a>
                                                            </li>
                                                        <?php } ?>
                                                        <?php
                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Disable User', $roleAccess) && $roleAccess['Disable User'] == 0) {
                                                            
                                                        } else {
                                                            ?>
                                                            <li><a class="icon-disable-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?deact=<?php echo urlencode($user['User']['uniq_id']); ?>" Onclick="return confirm('<?php echo __("Are you sure you want to disable"); ?> \'<?php echo $user['User']['name']; ?>\' ?')"><i class="glyphicon glyphicon-ban-circle "></i><?php echo __("Disable"); ?></a></li>
                                                        <?php } ?>
                                                        <!----- Client Restriction---->
                                                            <?php if (defined('CR') && CR == 1) { ?> 
                                                            <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                <?php if ($user['CompanyUser']['is_client'] == '0') { ?>
                                                                    <?php
                                                                    if (defined('ROLE') && ROLE == 1 && array_key_exists('Mark Client', $roleAccess) && $roleAccess['Mark Client'] == 0) {
                                                                        
                                                                    } else {
                                                                        ?>
                                                                        <a class="icon-client-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?grant_client=<?php echo urlencode($user['User']['uniq_id']); ?>" onclick="return confirm('<?php echo __("Are you sure you want to mark"); ?> \'<?php echo ucfirst($user['User']['name']); ?>\' <?php echo __("as client"); ?> ?')"><i class="glyphicon glyphicon-user"></i> <?php echo __("Mark Client"); ?></a>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <?php
                                                                    if (defined('ROLE') && ROLE == 1 && array_key_exists('Revoke Client', $roleAccess) && $roleAccess['Revoke Client'] == 0) {
                                                                        
                                                                    } else {
                                                                        ?>
                                                                        <a class="icon-revclient-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?revoke_client=<?php echo urlencode($user['User']['uniq_id']); ?>" onclick="return confirm('<?php echo __("Are you sure you want to revoke client access from"); ?> \'<?php echo ucfirst($user['User']['name']); ?>\' ?')"><i class="glyphicon glyphicon-user"></i> <?php echo __("Revoke Client"); ?> </a>
                                                                <?php } ?>
                                                                </li>
            <?php } }?>
                                                            <!-- End-->
                                                            <?php
                                                            if (defined('ROLE') && ROLE == 1 && array_key_exists('Add New User', $roleAccess) && $roleAccess['Add New User'] == 0) {
                                                                
                                                            } else {
                                                                ?>
                                                                <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">				  
                                                                    <a class="icon-resend-usr" href="javascript:void(0);" onclick="return resend_invitation('<?php echo $user['User']['qstr']; ?>','<?php echo $user['User']['email']; ?>');"><i class="glyphicon glyphicon-envelope "></i>  <?php echo __("Resend"); ?></a>
                                                                </li>
                                                            <?php } ?>
                                                        <?php } else {
                                                            ?>
                                                            <?php
                                                            if (defined('ROLE') && ROLE == 1 && array_key_exists('Assign Project', $roleAccess) && $roleAccess['Assign Project'] == 0) {
                                                                
                                                            } else {
                                                                ?>
                                                                <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                    <a class="icon-assign-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['name']; ?>"><i class="glyphicon glyphicon-paste "></i>  <?php echo __("Assign Project"); ?></a>
                                                                </li>
                                                            <?php } ?>
                                                            <?php
                                                            if (defined('ROLE') && ROLE == 1 && array_key_exists('Remove Project', $roleAccess) && $roleAccess['Remove Project'] == 0) {
                                                                
                                                            } else {
                                                                ?>
                                                                <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                    <input id="rmv_allprj_<?php echo $user['User']['id']; ?>" type="hidden" value="<?php echo $user['User']['all_projects']; ?>"/>
                                                                    <a class="icon-remprj-usr" href="javascript:void(0);" data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['name']; ?>" data-total-project="<?php echo $user['User']['total_project']; ?>"><i class="glyphicon glyphicon-minus-sign "></i> <?php echo __("Remove Project"); ?></a>
                                                                    <span id="rmv_prj_<?php echo $user['User']['id']; ?>" <?php if ($user['User']['all_project'] == '') { ?> style="display:none;"<?php } ?>></span>
                                                                </li>
                                                            <?php } ?>
                                                            <?php
                                                            if (defined('ROLE') && ROLE == 1 && array_key_exists('Disable User', $roleAccess) && $roleAccess['Disable User'] == 0) {
                                                                
                                                            } else {
                                                                ?>
                                                                <li><a class="icon-disable-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?deact=<?php echo urlencode($user['User']['uniq_id']); ?>" Onclick="return confirm('<?php echo __("Are you sure you want to disable"); ?> \'<?php echo $user['User']['name']; ?>\' ?')"><i class="glyphicon glyphicon-ban-circle "></i><?php echo __("Disable"); ?></a></li>
                                                            <?php } ?>
                                                            <!----- Client Restriction---->
                                                                <?php if (defined('CR') && CR == 1) { ?> 
                                                                <li data-usr-id="<?php echo $user['User']['id']; ?>" data-usr-name="<?php echo $user['User']['email']; ?>">
                                                                    <?php if ($user['CompanyUser']['is_client'] == '0') { ?>
                                                                        <?php
                                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Mark Client', $roleAccess) && $roleAccess['Mark Client'] == 0) {
                                                                            
                                                                        } else {
                                                                            ?>
                                                                            <a class="icon-client-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?grant_client=<?php echo urlencode($user['User']['uniq_id']); ?>" onclick="return confirm('<?php echo __("Are you sure you want to mark"); ?> \'<?php echo ucfirst($user['User']['name']); ?>\' <?php echo __("as client"); ?> ?')"><i class="glyphicon glyphicon-user"></i> <?php echo __("Mark Client"); ?></a>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <?php
                                                                        if (defined('ROLE') && ROLE == 1 && array_key_exists('Revoke Client', $roleAccess) && $roleAccess['Revoke Client'] == 0) {
                                                                            
                                                                        } else {
                                                                            ?>
                                                                            <a class="icon-revclient-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?revoke_client=<?php echo urlencode($user['User']['uniq_id']); ?>" onclick="return confirm('<?php echo __("Are you sure you want to revoke client access from"); ?> \'<?php echo ucfirst($user['User']['name']); ?>\' ?')"><i class="glyphicon glyphicon-user"></i> <?php echo __("Revoke Client"); ?></a>
                                                                    <?php } ?>
                                                                    </li>
                            <?php } ?>
                                                                <!-- End-->
                                                                <li>
                                                                    <?php if ($istype == 1) { ?>
                                                                        <?php if ($user['CompanyUser']['user_type'] == 2) { ?>
                                                                            <a class="icon-revadmin-usr" href="<?php echo HTTP_ROOT; ?>users/manage/?revoke_admin=<?php echo urlencode($user['User']['uniq_id']); ?>" Onclick="return confirm('<?php echo __("Are you sure you want to revoke Admin privilege from"); ?> \'<?php echo $user['User']['name']; ?>\' ?')"><i class="glyphicon glyphicon-minus "></i><?php echo __("Revoke Admin"); ?></a>
                                                                        <?php } else { ?>
                                                                            <a class="icon-admin-usr " href="<?php echo HTTP_ROOT; ?>users/manage/?grant_admin=<?php echo urlencode($user['User']['uniq_id']); ?>" Onclick="return confirm('<?php echo __("Are you sure you want to grant Admin privilege to"); ?> \'<?php echo $user['User']['name']; ?>\' ?')"><i class="glyphicon glyphicon-plus "></i><?php echo __("Grant Admin"); ?></a>
                                                                    <?php } ?><?php } ?>
                                                                </li>
                                                            <?php } ?>
                                                        <?php } ?>			  
                                                    <?php } ?>			  
                                            </ul>

        <?php } ?>
                                    </div>
                                </td>
                                <td align="left"><?php echo ucfirst($user['User']['name']); ?>
                                </td>
                                <td class="text-center" title="<?php echo $user['User']['email']; ?>" ><?php
                            $email = $this->Format->shortLength($user['User']['email'], 25);
                            echo $email;
                            ?></td>
                                <td class="text-center" title="<?php echo __($user['User']['all_projects']); ?>" ><span class="ellipsis-view" style="display:block;width:150px"><?php echo __($user['User']['all_project']); ?></span></td>
                                <td class="text-center"><?php echo $user['User']['total_project']; ?></td>
                                <td class="text-center"><?php echo $usr_typ_name; ?></td> 
                                <td class="text-center"><?php echo $user['Role']['role_name']; ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($user['CompanyUser']['is_active'] == 0 && $_GET['role'] == 'invited') {
                                        $activity = "<span class='fnt_clr_rd'>" . __("Invited") . "</span>";
                                    } else if ($_GET['role'] == 'recent') {
                                        if ($user['User']['is_active'] == 2) {
                                            $activity = "<span class='fnt_clr_rd'>" . __("Invited") . "</span>";
                                        } else if (($istype == 1 || $istype == 2) && !$user['User']['dt_last_login']) {
                                            $activity = "<span class='fnt_clr_rd'>" . __("No activity yet") . "</span>";
                                        } else if ($user['User']['dt_last_login']) {
                                            $activity = $user['User']['latest_activity'];
                                        }
                                    } else {
                                        if ($user['User']['dt_last_login']) {
                                            $activity = $user['User']['latest_activity'];
                                        } elseif ($user['CompanyUser']['is_active']) {
                                            
                                        }
                                        if (($istype == 1 || $istype == 2) && !$user['User']['dt_last_login']) {
                                            if ($user['CompanyUser']['is_active'] == 2) {
                                                $activity = "<span class='fnt_clr_rd'>" . __("Invited") . "</span>";
                                            } else {
                                                $activity = "<span class='fnt_clr_rd'>" . __("No activity yet") . "</span>";
                                            }
                                        }
                                    }
                                    echo $activity;
                                    ?>		
                                </td>


                            </tr>
                            <?php
                        }
                    }
                    ?>
                </table>
            </div>
            <div class="loader_bg" id="projectLoader"><div class="loader-wrapper md-preloader md-preloader-warning"><svg version="1.1" height="48" width="48" viewBox="0 0 75 75"><circle cx="37.5" cy="37.5" r="25" stroke-width="4"></circle></svg></div></div>
        </div>
    </div>

<?php if (SES_TYPE < 3) { ?>
        <div class="crt_task_btn_btm">
            <div class="os_plus">
                <div class="ctask_ttip">
                    <span class="label label-default">
    <?php echo __("Add New User"); ?>
                    </span>
                </div>
                <a href="javascript:void(0)" onClick="newUser()">
                    <img src="<?php echo HTTP_ROOT; ?>img/images/add_usr.png" class="prjct_icn ctask_icn" />
                    <img src="<?php echo HTTP_ROOT; ?>img/images/plusct.png" class="add_icn" />
                </a>
            </div>
        </div>
<?php } ?>

    <script>
        $(document).ready(function () {
            var url = HTTP_ROOT + 'projects/manage/grid';
            $('#grid-keep-selection').dataTable({
                "bAutoWidth": false,
                "aLengthMenu": [[10, 20, 30, 50, 100], [10, 20, 30, 50, 100]],
                "iDisplayLength": 10,
                "iDisplayStart": 0,
                "aaSorting": [[ 0, "asc" ]],
                // "aaSorting": [],
                        
                "sPaginationType": "full_numbers",
                //"aoColumns": [{sWidth:'3%'},{sWidth:'9%'},{sWidth:'8%'},{sWidth:'9%'},{sWidth:'6%'},{sWidth:'6%'},{sWidth:'10%'},{sWidth:'10%'},{sWidth:'5%'},{sWidth:'10%'},{ "bSortable": true,sWidth:'5%'},{ "bSortable": true,sWidth:'12%'}]
                "aoColumnDefs": [
                    {'bSortable': false, 'aTargets': [0]}
                ],
                oinit: function () {
                    console.log('loaded')
                }
            });
        });
        function select_user_stat(role){
            var url = HTTP_ROOT + "users/manage/list-view/?role=" + role;
               
            window.location = url;
        }
        function export_usrlstng(){
            var srch_val = $("#grid-keep-selection_filter").find('input[type=text]').val();
            var role = "<?php echo $_GET['role'] != '' ? $_GET['role'] : 'all'; ?>" ;
            window.open(HTTP_ROOT +"users/export_user_listing/"+srch_val+"/?role="+role);
        
        }
    </script>