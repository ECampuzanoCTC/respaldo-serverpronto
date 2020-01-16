<style>
   .mycompany_ipad.tab.tab_comon .nav-tabs li{padding-right:20px;width:auto}
</style>
<div class="tab tab_comon mycompany_ipad">
    <ul class="nav-tabs">
        <li <?php if (PAGE_NAME == 'mycompany') { ?>class="active" <?php } ?>>
            <a href="<?php echo HTTP_ROOT . 'my-company'; ?>" id="sett_mail_noti_prof" rel="tooltip" title="<?php echo __("My Company"); ?>">
                <div class="grp_impx grp_comp"></div>
                <div class="ellipsis-view maxWidth120"><?php echo __("My Company"); ?></div>
                <div class="cbt"></div> 
            </a>
        </li>
        <li <?php if (PAGE_NAME == 'groupupdatealerts') { ?>class="active" <?php } ?>>
            <a href="<?php echo HTTP_ROOT . 'reminder-settings'; ?>" id="sett_mail_repo_prof" rel="tooltip" title="<?php echo __("Daily Catch-Up"); ?>">
                <div class="ctab_sprt_icon grp_alt"></div>
                <div class="ellipsis-view maxWidth120"><?php echo __("Daily Catch-Up"); ?></div>
                <div class="cbt"></div>
            </a>
        </li>
        <li <?php if (PAGE_NAME == 'importexport' || PAGE_NAME == 'csv_dataimport' || PAGE_NAME == 'confirm_import') { ?>class="active" <?php } ?>>
            <a href="<?php echo HTTP_ROOT . 'import-export'; ?>" id="sett_imp_exp_prof" rel="tooltip" title="<?php echo __("Import & Export"); ?>">
                <div class="ctab_sprt_icon grp_impx"></div>
                <div class="ellipsis-view maxWidth120"><?php echo __("Import & Export"); ?></div>
                <div class="cbt"></div>
            </a>
        </li>
        <li <?php if (PAGE_NAME == 'task_type') { ?>class="active" <?php } ?>>
            <a href="<?php echo HTTP_ROOT . 'task-type'; ?>" id="sett_task_type" rel="tooltip" title="<?php echo __("Task Type"); ?>">
                <div style="height: 18px;width: 18px;display:inline-block;vertical-align:top">
                    <img src="<?php echo HTTP_ROOT . "img/tasktype.png"; ?>"  width="16px" height="16px"/>
                </div>
                <div class="fl ellipsis-view maxWidth120"><?php echo __("Task Type"); ?></div>
                <div class="cbt"></div>
            </a>
        </li>
        <?php if(defined('GINV') && GINV == 1 || defined('DBRD') && DBRD == 1){ 
            if(SES_TYPE == 1 || (SES_TYPE == 2 && $GLOBALS['is_admin_allowed'] == 1)){?>
        <li <?php if (CONTROLLER == 'users' && PAGE_NAME == 'cost_settings') { ?>class="active" <?php } ?> >
            <a href="<?php echo HTTP_ROOT . 'cost-settings'; ?>" id="sett_inv_setting">
                <div style="height: 18px;width: 18px;display:inline-block;">
                    <img src="<?php echo HTTP_ROOT . "img/tasktype.png"; ?>"  width="16px" height="16px"/>
                </div>
                <div style="display:inline-block;vertical-align:top">Cost Settings</div>
                <div class="cbt"></div>
            </a>
        </li>
        <?php }} ?>
        <?php /*if(defined('GINV') && GINV == 1 || defined('DBRD') && DBRD == 1){ 
            if(SES_TYPE == 1 || (SES_TYPE == 2 && $GLOBALS['is_admin_allowed'] == 1)){?>
        <li <?php if (CONTROLLER == 'users' && PAGE_NAME == 'default_rates') { ?>class="active" <?php } ?>>
            <a href="<?php echo HTTP_ROOT . 'default-rates'; ?>" id="sett_inv_setting">
                <div class="fl" style="height: 18px;width: 18px;margin-right: 6px;">
                    <img src="<?php echo HTTP_ROOT . "img/tasktype.png"; ?>"  width="16px" height="16px"/>
                </div>
                <div class="fl">Default Rate</div>
                <div class="cbt"></div>
            </a>
        </li>
        <?php }} */?>
         <?php if(defined('DBRD') && DBRD == 1 ){ 
             if(SES_TYPE == 1 || (SES_TYPE == 2 && $GLOBALS['is_admin_allowed'] == 1)){?>
        <li <?php if (CONTROLLER == 'users' && PAGE_NAME == 'salary_settings') { ?>class="active" <?php } ?> >
            <a href="<?php echo HTTP_ROOT . 'salary-settings'; ?>" id="sett_inv_setting">
                <div class="">
                    <img src="<?php echo HTTP_ROOT . "img/tasktype.png"; ?>"  width="16px" height="16px"/>
                </div>
                <div class="">Salary Management</div>
                <div class="cbt"></div>
            </a>
        </li>
        <?php }} ?>
        <?php if(defined('INV') && INV == 1){ ?>
        <li <?php if (CONTROLLER == 'invoices' && PAGE_NAME == 'settings') { ?>class="active" <?php } ?>> 
            <a href="<?php echo HTTP_ROOT . 'invoice-settings'; ?>" id="sett_inv_setting">
                <div class="fl" style="height: 18px;width: 18px;margin-right: 6px;">
                    <img src="<?php echo HTTP_ROOT . "img/tasktype.png"; ?>"  width="16px" height="16px"/>
                </div>
                <div class="fl">Invoice Settings</div>
                <div class="cbt"></div>
            </a>
        </li>
        <?php } ?>
        <?php if(defined('API') && API == 1){ ?>
        <li <?php if (CONTROLLER == 'Apis' && PAGE_NAME == 'settings') { ?>class="active" <?php } ?> >
            <a href="<?php echo HTTP_ROOT . 'api-settings'; ?>" id="sett_spi_setting" rel="tooltip" title="<?php echo __("API"); ?>">
                <div style="height: 18px;width: 18px;display:inline-block;vertical-align:top">
                    <img src="<?php echo HTTP_ROOT . "img/tasktype.png"; ?>"  width="16px" height="16px"/>
                </div>
                <div class="fl ellipsis-view maxWidth120 "><?php echo __("API"); ?></div>
                <div class="cbt"></div>
            </a>
        </li> 
        <?php } ?>
        <?php if (defined('CR') && CR == 1) { ?>
        <li <?php if (CONTROLLER == 'ClientRestriction' && PAGE_NAME == 'settings') { ?>class="active" <?php } ?> >
            <a href="<?php echo HTTP_ROOT . 'clientrestriction/ClientRestriction/settings'; ?>" id="sett_spi_setting">
                <div style="height: 18px;width: 18px;display:inline-block;vertical-align:top">
                    <img src="<?php echo HTTP_ROOT . "img/tasktype.png"; ?>"  width="16px" height="16px"/>
                </div>
                    <div class="fl"><?php echo __("Client Restrictions"); ?></div>
                <div class="cbt"></div>
            </a>
        </li>
        <?php } ?>
        <div class="cbt"></div>
    </ul>
</div>
